<?php

namespace App\Services\Analytics;

use DateTimeImmutable;
use DateTimeZone;

final class OperationalDataset
{
    public const VERSION = '1.0.0';

    public static function row(array $exit, array $events, bool $uniqueChecklists, DateTimeImmutable $cutoff): array
    {
        $reasons = [];
        $times = [];
        $captures = [];
        foreach (['salida', 'entrada'] as $type) {
            $matches = array_values(array_filter($events, fn ($e) => ($e['tipo'] ?? null) === $type));
            if (count($matches) !== 1) {
                $reasons[] = $type.'_ausente_o_duplicada';
                continue;
            }
            $event = $matches[0];
            try {
                $zone = new DateTimeZone($event['zona_horaria']);
                foreach (['ocurrido_at', 'registrado_at'] as $field) {
                    $value = $event[$field] ?? '';
                    $date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $value, $zone);
                    if (!$date || $date->format('Y-m-d H:i:s') !== $value) {
                        throw new \InvalidArgumentException('Invalid date');
                    }
                    if ($field === 'ocurrido_at') $times[$type] = $date;
                    else $captures[$type] = $date;
                }
                if ($times[$type] > $captures[$type] || $captures[$type] > $cutoff) {
                    $reasons[] = $type.'_cronologia_invalida';
                }
                $created = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $exit['created_at'] ?? '', $cutoff->getTimezone());
                if (!$created || $created->format('Y-m-d H:i:s') !== ($exit['created_at'] ?? '') || $times[$type] < $created) {
                    $reasons[] = $type.'_anterior_a_solicitud_o_solicitud_invalida';
                }
            } catch (\Throwable $e) {
                unset($times[$type], $captures[$type]);
                $reasons[] = $type.'_fecha_o_zona_invalida';
            }
        }
        $duration = isset($times['salida'], $times['entrada'])
            ? ($times['entrada']->getTimestamp() - $times['salida']->getTimestamp()) / 60 : null;
        if ($duration !== null && $duration <= 0) $reasons[] = 'duracion_no_positiva';
        if (isset($captures['salida'], $captures['entrada']) && $captures['entrada'] < $captures['salida']) $reasons[] = 'capturas_invertidas';
        if (($exit['estatus'] ?? null) !== 'finalizado') $reasons[] = 'no_finalizada';
        if (!$uniqueChecklists) $reasons[] = 'checklists_incompletos_o_duplicados';
        if (empty($exit['vehicle_exists'])) $reasons[] = 'vehiculo_ausente';
        $closure = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $exit['fecha_regreso'] ?? '', $cutoff->getTimezone());
        if (!$closure || $closure->format('Y-m-d H:i:s') !== ($exit['fecha_regreso'] ?? '') || $closure > $cutoff || (isset($captures['entrada']) && $closure < $captures['entrada'])) {
            $reasons[] = 'cierre_ausente_o_invalido';
        }
        $eligible = !$reasons;
        return [
            'salida_id' => $exit['id'],
            'salida_real' => isset($times['salida']) ? $times['salida']->format(DATE_ATOM) : null,
            'regreso_real' => isset($times['entrada']) ? $times['entrada']->format(DATE_ATOM) : null,
            'etiqueta_disponible_desde' => $eligible ? $closure->format(DATE_ATOM) : null,
            'duracion_operativa_minutos' => $eligible ? round($duration, 3) : null,
            'elegible_kpi_operativo' => (int) $eligible,
            'requiere_revision_24h' => (int) ($eligible && $duration > 1440),
            'retraso_captura_salida_minutos' => isset($times['salida'], $captures['salida']) ? round(($captures['salida']->getTimestamp() - $times['salida']->getTimestamp()) / 60, 3) : null,
            'retraso_captura_regreso_minutos' => isset($times['entrada'], $captures['entrada']) ? round(($captures['entrada']->getTimestamp() - $times['entrada']->getTimestamp()) / 60, 3) : null,
            'motivos_exclusion' => implode('|', $reasons),
            'aprobado_entrenamiento' => 0,
        ];
    }
}
