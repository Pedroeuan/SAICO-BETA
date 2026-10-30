<?php

namespace App\Services\Analytics;

use DateTimeImmutable;
use DateTimeZone;

// Pure preparation rules; no database access and no changes to source records.
final class SalidasDataset
{
    public const VERSION = '1.0.0';

    public const CONDITIONS = [
        'aceite' => ['suficiente', 'escaso', 'no_hay'],
        'anticongelante' => ['suficiente', 'escaso', 'no_hay'],
        'liquido_frenos' => ['suficiente', 'escaso', 'no_hay'],
        'liquido_limpiaparabrisas' => ['suficiente', 'escaso', 'no_hay'],
        'estado_llantas' => ['buen_estado', 'regular', 'malo'],
        'llanta_delantera_izq_calibracion' => ['baja', 'normal', 'alta'],
        'llanta_delantera_der_calibracion' => ['baja', 'normal', 'alta'],
        'llanta_trasera_izq_calibracion' => ['baja', 'normal', 'alta'],
        'llanta_trasera_der_calibracion' => ['baja', 'normal', 'alta'],
        'nivel_gasolina' => ['Lleno', '3/4', '1/2', '1/4', 'Vacio'],
    ];

    public static function clean(mixed $value): mixed
    {
        return is_string($value) ? (trim($value) === '' ? null : trim($value)) : $value;
    }

    public static function date(mixed $value, DateTimeZone $zone): ?DateTimeImmutable
    {
        $value = self::clean($value);
        if ($value === null) {
            return null;
        }
        $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', (string) $value, $zone);

        return $date && $date->format('Y-m-d H:i:s') === $value ? $date : null;
    }

    public static function row(array $source, array $checks, array $conditions, DateTimeImmutable $now): array
    {
        $flags = [];
        $durationReasons = [];
        $kmReasons = [];
        $start = self::date($source['fecha_salida'], $now->getTimezone());
        $end = self::date($source['fecha_regreso'], $now->getTimezone());
        $created = self::date($source['created_at'], $now->getTimezone());
        $status = self::clean($source['estatus']);
        $completed = $status === 'finalizado';
        if (! $completed) {
            $durationReasons[] = $kmReasons[] = 'no_finalizada';
        }
        if (! in_array($status, ['activo', 'finalizado'], true)) {
            $flags[] = 'estado_desconocido';
        }
        if (! $source['vehicle_exists']) {
            $durationReasons[] = $kmReasons[] = 'vehiculo_inexistente';
        }
        if (! $source['driver_exists']) {
            $flags[] = 'chofer_inexistente';
        }
        if (! $source['requester_exists']) {
            $flags[] = 'solicitante_inexistente';
        }
        if (! $start) {
            $durationReasons[] = 'fecha_salida_ausente_o_invalida';
        }
        if (! $end && $completed) {
            $durationReasons[] = 'fecha_regreso_ausente_o_invalida';
        }
        if (self::clean($source['fecha_regreso']) !== null && ! $end) {
            $flags[] = 'fecha_regreso_invalida';
        }
        if ($start && $start > $now) {
            $durationReasons[] = 'fecha_salida_futura';
        }
        if ($end && $end > $now) {
            $durationReasons[] = 'fecha_regreso_futura';
        }
        if ($status === 'activo' && $end) {
            $flags[] = 'activa_con_regreso';
        }
        if ($status === 'activo' && $start && $now->getTimestamp() - $start->getTimestamp() > 30 * 86400) {
            $flags[] = 'activa_mayor_30_dias';
        }
        $duration = $start && $end ? ($end->getTimestamp() - $start->getTimestamp()) / 60 : null;
        if ($duration !== null && $duration <= 0) {
            $durationReasons[] = 'duracion_no_positiva';
        }
        if ($duration !== null && $duration > 1440) {
            $flags[] = 'duracion_mayor_24h_revisar';
        }
        $stored = self::clean($source['duracion_minutos']);
        if ($completed && ! is_numeric($stored)) {
            $flags[] = 'duracion_guardada_ausente_o_invalida';
        }
        if ($duration !== null && is_numeric($stored) && abs($duration - (float) $stored) > 1) {
            $flags[] = 'duracion_guardada_difiere';
        }
        if (! $created) {
            $flags[] = 'fecha_creacion_ausente_o_invalida';
        }
        if ($created && $start && $created > $start) {
            $flags[] = 'solicitud_registrada_despues_de_salida';
        }
        $row = [
            'salida_id' => (int) $source['id'], 'vehiculo_id' => (int) $source['vehiculo_id'],
            'estatus' => $status, 'fecha_solicitud' => $created?->format('Y-m-d H:i:s'),
            'fecha_salida' => $start?->format('Y-m-d H:i:s'), 'fecha_regreso' => $end?->format('Y-m-d H:i:s'),
            'salida_hora' => $start ? (int) $start->format('G') : null,
            'salida_dia_semana' => $start ? (int) $start->format('N') : null,
            'salida_mes' => $start ? (int) $start->format('n') : null,
            'duracion_guardada_minutos' => is_numeric($stored) ? (float) $stored : null,
            'duracion_calculada_minutos' => $duration === null ? null : round($duration, 4),
        ];
        foreach ($checks as $check) {
            if (! in_array($check['tipo'], ['salida', 'entrada'], true)) {
                $flags[] = 'tipo_checklist_desconocido';
            }
        }
        foreach (['salida', 'entrada'] as $type) {
            $matches = array_values(array_filter($checks, fn ($c) => $c['tipo'] === $type));
            $row['checklists_'.$type] = count($matches);
            $conditionRows = count($matches) === 1 ? ($conditions[$matches[0]['id']] ?? []) : [];
            $row['condiciones_'.$type] = count($conditionRows);
            $condition = count($matches) === 1 && count($conditionRows) === 1 ? $conditionRows[0] : [];
            if (count($matches) !== 1) {
                $kmReasons[] = 'checklist_'.$type.'_ausente_o_duplicado';
            } elseif (count($conditionRows) !== 1) {
                $kmReasons[] = 'condicion_'.$type.'_ausente_o_duplicada';
            }
            $km = self::clean($condition['kilometraje'] ?? null);
            $validKm = is_numeric($km) && (float) $km >= 0;
            $row['km_'.$type] = $validKm ? (float) $km : null;
            if (! $validKm) {
                $kmReasons[] = 'km_'.$type.'_ausente_o_invalido';
            }
            foreach (self::CONDITIONS as $field => $values) {
                $value = self::clean($condition[$field] ?? null);
                $valid = $value !== null && in_array($value, $values, true);
                $row[$type.'_'.$field] = $valid ? $value : null;
                if ($condition && ! $valid) {
                    $flags[] = $type.'_'.$field.($value === null ? '_faltante' : '_valor_desconocido');
                }
            }
        }
        $distance = $row['km_salida'] !== null && $row['km_entrada'] !== null ? $row['km_entrada'] - $row['km_salida'] : null;
        if ($distance !== null && $distance < 0) {
            $kmReasons[] = 'kilometraje_decreciente';
        }
        if ($distance === 0.0) {
            $flags[] = 'kilometraje_sin_cambio';
        }
        $row['km_recorridos'] = $distance;
        $row['elegible_kpi_duracion'] = (int) empty($durationReasons);
        $row['elegible_kpi_km'] = (int) empty($kmReasons);
        $row['ambos_checklists_unicos'] = (int) ($row['checklists_salida'] === 1 && $row['checklists_entrada'] === 1);
        // Review flags do not silently discard observations. No operationally validated label exists yet.
        $row['candidato_duracion_exploratoria'] = (int) (empty($durationReasons) && $created !== null && $created <= $start);
        $row['requiere_revision_duracion'] = (int) ($duration !== null && $duration > 1440);
        $row['procedencia_verificada'] = 0;
        $row['motivos_exclusion_duracion'] = implode('|', array_unique($durationReasons));
        $row['motivos_exclusion_km'] = implode('|', array_unique($kmReasons));
        $row['motivos_exclusion_modelo'] = implode('|', array_unique(array_merge($durationReasons, ! $created ? ['sin_fecha_solicitud'] : ($start && $created > $start ? ['solicitud_posterior_a_salida'] : []))));
        $row['alertas_calidad'] = implode('|', array_unique(array_merge($flags, $durationReasons, $kmReasons)));

        return $row;
    }
}
