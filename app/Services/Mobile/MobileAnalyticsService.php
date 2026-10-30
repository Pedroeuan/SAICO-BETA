<?php

namespace App\Services\Mobile;

use App\Models\User;
use App\Services\Analytics\SalidasDataset;
use App\Services\Analytics\OperationalDataset;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Support\Facades\DB;

class MobileAnalyticsService
{
    public function __construct(private readonly MobileVehiculoService $vehicles) {}

    public function vehicleOptions(User $user): array
    {
        $visible = $this->vehicles->listVisibleSalidas($user)->withoutEagerLoads()->reorder()->select('vehiculo_id');
        return DB::table('vehiculos')->whereIn('id', $visible->toBase())
            ->orderBy('placa')->get(['id', 'placa', 'marca', 'modelo'])
            ->map(fn ($vehicle) => ['id' => $vehicle->id, 'label' => trim($vehicle->placa.' - '.$vehicle->marca.' '.$vehicle->modelo)])->all();
    }

    public function summary(User $user, array $filters = []): array
    {
        $timezone = config('app.timezone');
        $now = new DateTimeImmutable('now', new DateTimeZone($timezone));
        $query = $this->vehicles->listVisibleSalidas($user)->withoutEagerLoads()->reorder();
        if (! empty($filters['desde'])) {
            $query->where('fecha_salida', '>=', CarbonImmutable::parse($filters['desde'], $timezone)->startOfDay()->format('Y-m-d H:i:s'));
        }
        if (! empty($filters['hasta'])) {
            $query->where('fecha_salida', '<', CarbonImmutable::parse($filters['hasta'], $timezone)->addDay()->startOfDay()->format('Y-m-d H:i:s'));
        }
        if (! empty($filters['vehiculo_id'])) {
            $query->where('vehiculo_id', (int) $filters['vehiculo_id']);
        }
        $query->select(['salidas_vehiculos.id', 'vehiculo_id', 'estatus', 'fecha_salida', 'fecha_regreso', 'duracion_minutos', 'created_at'])
            ->selectRaw('EXISTS(SELECT 1 FROM vehiculos v WHERE v.id=salidas_vehiculos.vehiculo_id) AS vehicle_exists')
            ->selectRaw('EXISTS(SELECT 1 FROM users u WHERE u.id=salidas_vehiculos.chofer_id) AS driver_exists')
            ->selectRaw('EXISTS(SELECT 1 FROM users u WHERE u.id=salidas_vehiculos.solicitado_por) AS requester_exists');
        $operationalCount = 0; $operationalSum = 0.0; $operationalLong = 0;
        $total = 0;
        $completed = 0;
        $active = 0;
        $durationCount = 0;
        $durationSum = 0.0;
        $kmCount = 0;
        $kmSum = 0.0;
        $both = 0;
        $longDurations = 0;
        $quality = [];
        $months = [];
        // Same snapshot for exits and their checklists; reads only, no model save/update calls.
        DB::transaction(function () use ($query, $now, &$total, &$completed, &$active, &$durationCount, &$durationSum, &$kmCount, &$kmSum, &$both, &$longDurations, &$quality, &$months, &$operationalCount, &$operationalSum, &$operationalLong) {
            $query->chunkById(500, function ($exits) use ($now, &$total, &$completed, &$active, &$durationCount, &$durationSum, &$kmCount, &$kmSum, &$both, &$longDurations, &$quality, &$months, &$operationalCount, &$operationalSum, &$operationalLong) {
                $checkRows = DB::table('salidas_checklists')->whereIn('salida_vehiculo_id', $exits->pluck('id'))->get(['id', 'salida_vehiculo_id', 'tipo']);
                $conditionRows = DB::table('checklist_condiciones')->whereIn('salida_checklist_id', $checkRows->pluck('id'))->get(array_merge(['id', 'salida_checklist_id', 'kilometraje'], array_keys(SalidasDataset::CONDITIONS)));
                $events = DB::table('salidas_tiempos_operativos')->whereIn('salida_vehiculo_id', $exits->pluck('id'))->get(['salida_vehiculo_id', 'tipo', 'ocurrido_at', 'registrado_at', 'zona_horaria'])->groupBy('salida_vehiculo_id');
                $checks = [];
                $conditions = [];
                foreach ($checkRows as $c) {
                    $checks[$c->salida_vehiculo_id][] = (array) $c;
                }
                foreach ($conditionRows as $c) {
                    $conditions[$c->salida_checklist_id][] = (array) $c;
                }
                foreach ($exits as $exit) {
                    $row = SalidasDataset::row($exit->getAttributes(), $checks[$exit->id] ?? [], $conditions, $now);
                    $operational = OperationalDataset::row($exit->getAttributes(), $events->get($exit->id, collect())->map(fn ($e) => (array) $e)->all(), (bool) $row['ambos_checklists_unicos'], $now);
                    if ($operational['elegible_kpi_operativo']) {
                        $operationalCount++;
                        $operationalSum += $operational['duracion_operativa_minutos'];
                        $operationalLong += $operational['requiere_revision_24h'];
                    }
                    $total++;
                    $isCompleted = $row['estatus'] === 'finalizado';
                    $completed += (int) $isCompleted;
                    $active += (int) ($row['estatus'] === 'activo');
                    if ($row['elegible_kpi_duracion']) {
                        $durationCount++;
                        $durationSum += $row['duracion_calculada_minutos'];
                        $longDurations += $row['requiere_revision_duracion'];
                    }
                    if ($row['elegible_kpi_km']) {
                        $kmCount++;
                        $kmSum += $row['km_recorridos'];
                    }
                    if ($isCompleted) {
                        $both += $row['ambos_checklists_unicos'];
                    }
                    foreach (explode('|', $row['alertas_calidad']) as $flag) {
                        if ($flag === '') {
                            continue;
                        }
                        // Missing arrival is normal for active exits, not a data defect.
                        if (! $isCompleted && in_array($flag, ['no_finalizada', 'checklist_entrada_ausente_o_duplicado', 'km_entrada_ausente_o_invalido'], true)) {
                            continue;
                        }
                        $quality[$flag] = ($quality[$flag] ?? 0) + 1;
                    }
                    if ($row['fecha_salida'] !== null) {
                        $month = substr($row['fecha_salida'], 0, 7);
                        $months[$month] ??= ['mes' => $month, 'salidas' => 0, 'finalizadas' => 0];
                        $months[$month]['salidas']++;
                        $months[$month]['finalizadas'] += (int) $isCompleted;
                    }
                }
            }, 'salidas_vehiculos.id', 'id');
        });
        ksort($months);
        ksort($quality);

        return [
            'generado_en' => $now->format(DATE_ATOM), 'zona_horaria' => $timezone, 'version_reglas' => SalidasDataset::VERSION,
            'alcance' => $this->vehicles->canViewAll($user) ? 'todas_las_salidas' : 'salidas_como_chofer_o_solicitante',
            'filtros' => ['desde' => $filters['desde'] ?? null, 'hasta' => $filters['hasta'] ?? null, 'vehiculo_id' => isset($filters['vehiculo_id']) ? (int) $filters['vehiculo_id'] : null, 'campo_fecha' => 'fecha_salida', 'limites_inclusivos' => true],
            'sin_datos' => $total === 0,
            'version_reglas_operativas' => OperationalDataset::VERSION,
            'indicadores' => [
                'duracion_operativa' => ['valor' => $operationalCount ? round($operationalSum / $operationalCount, 2) : null, 'unidad' => 'minutos', 'registros_validos' => $operationalCount, 'registros_excluidos' => $completed - $operationalCount, 'intervalos_mayores_24h' => $operationalLong, 'descripcion' => 'Horas reales declaradas por el usuario; no certificadas por GPS.'],
                'cobertura_operativa' => ['valor' => $completed ? round(100 * $operationalCount / $completed, 2) : null, 'unidad' => 'porcentaje', 'numerador' => $operationalCount, 'denominador' => $completed],
                'salidas_total' => ['valor' => $total, 'unidad' => 'salidas'],
                'salidas_finalizadas' => ['valor' => $completed, 'unidad' => 'salidas'],
                'salidas_activas' => ['valor' => $active, 'unidad' => 'salidas'],
                'kilometros_registrados' => ['valor' => $kmCount ? round($kmSum, 3) : null, 'unidad' => 'km', 'registros_validos' => $kmCount, 'registros_excluidos' => $completed - $kmCount],
                'duracion_promedio' => ['valor' => $durationCount ? round($durationSum / $durationCount, 2) : null, 'unidad' => 'minutos', 'registros_validos' => $durationCount, 'registros_excluidos' => $completed - $durationCount, 'intervalos_mayores_24h' => $longDurations, 'requiere_revision' => $longDurations > 0, 'descripcion' => 'Intervalo registrado entre salida y cierre; no certifica tiempo fisico de viaje.'],
                'cobertura_checklists' => ['valor' => $completed ? round(100 * $both / $completed, 2) : null, 'unidad' => 'porcentaje', 'numerador' => $both, 'denominador' => $completed, 'descripcion' => 'Finalizadas con un checklist unico de salida y otro de entrada; no mide completitud de campos.'],
            ],
            'serie_mensual' => array_values($months),
            'calidad' => ['conteos_por_alerta' => $quality, 'nota' => 'Las alertas pueden coincidir en una misma salida; no sumar como registros distintos.'],
        ];
    }
}
