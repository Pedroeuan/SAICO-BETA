<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Vehiculo;
use App\Http\Requests\Vehiculos\VehiculoRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tabsValidas = ['listado', 'documentos', 'estadisticas', 'movimientos'];
        $tabActiva = in_array($request->input('tab'), $tabsValidas, true)
            ? $request->input('tab')
            : 'listado';

        $vehiculos = Vehiculo::query()
            ->select(['id', 'placa', 'marca', 'modelo', 'anio', 'estatus', 'documentacion_estatus'])
            ->orderByDesc('id')
            ->get();

        // Estadísticas y alertas
        $resumenVehiculos = Vehiculo::query()
            ->selectRaw('COUNT(*) as total_vehiculos')
            ->selectRaw("SUM(CASE WHEN estatus = 'disponible' THEN 1 ELSE 0 END) as disponibles")
            ->selectRaw("SUM(CASE WHEN estatus = 'ocupado' THEN 1 ELSE 0 END) as ocupados")
            ->selectRaw("SUM(CASE WHEN documentacion_estatus = 'vencida' THEN 1 ELSE 0 END) as vencidos")
            ->first();

        $totalVehiculos = (int) ($resumenVehiculos->total_vehiculos ?? 0);
        $disponibles = (int) ($resumenVehiculos->disponibles ?? 0);
        $ocupados = (int) ($resumenVehiculos->ocupados ?? 0);
        $vencidos = (int) ($resumenVehiculos->vencidos ?? 0);

        // Alertas de documentación
        $hoy = now();
        $documentosVencidos = collect();
        $documentosProximoVencer = collect();
        $documentosSinRegistrar = collect();
        if ($tabActiva === 'documentos') {
            $proximo15dias = $hoy->copy()->addDays(15);
            $docColumns = ['id', 'placa', 'marca', 'poliza_seguro_vencimiento', 'tarjeta_circulacion_vencimiento'];

            $documentosVencidos = Vehiculo::query()
                ->select($docColumns)
                ->where('documentacion_estatus', 'vencida')
                ->get();

            $documentosProximoVencer = Vehiculo::query()
                ->select($docColumns)
                ->where('documentacion_estatus', 'completa')
                ->where(function ($q) use ($hoy, $proximo15dias) {
                    $q->whereBetween('poliza_seguro_vencimiento', [$hoy->toDateString(), $proximo15dias->toDateString()])
                        ->orWhereBetween('tarjeta_circulacion_vencimiento', [$hoy->toDateString(), $proximo15dias->toDateString()]);
                })
                ->get();

            $documentosSinRegistrar = Vehiculo::query()
                ->select(['id', 'placa', 'marca'])
                ->where('documentacion_estatus', 'incompleta')
                ->get();
        }
        $vencidosCount = $vencidos;

        // Reporte mensual optimizado (agregado en SQL, no en PHP).
        $mesSeleccionado = (int) $request->input('mes', $hoy->month);
        $anioSeleccionado = (int) $request->input('anio', $hoy->year);

        if ($mesSeleccionado < 1 || $mesSeleccionado > 12) {
            $mesSeleccionado = $hoy->month;
        }

        $inicioMes = \Carbon\Carbon::createFromDate($anioSeleccionado, $mesSeleccionado, 1)->startOfMonth()->toDateString();
        $finMes = \Carbon\Carbon::createFromDate($anioSeleccionado, $mesSeleccionado, 1)->endOfMonth()->toDateString();

        $movimientosMensuales = collect();
        $resumenMovimientos = $this->emptyMovimientosResumen();
        if ($tabActiva === 'movimientos') {
            $movimientosMensuales = $this->buildMovimientosMensualesQuery($inicioMes, $finMes)
                ->orderByDesc('total_general')
                ->paginate(15)
                ->appends([
                    'mes' => $mesSeleccionado,
                    'anio' => $anioSeleccionado,
                    'tab' => 'movimientos',
                ]);

            $resumenMovimientos = $this->buildMovimientosMensualesResumen(
                collect($movimientosMensuales->items())
            );
        }

        return view('vehiculos.index', compact(
            'vehiculos',
            'totalVehiculos',
            'disponibles',
            'ocupados',
            'vencidos',
            'documentosVencidos',
            'documentosProximoVencer',
            'documentosSinRegistrar',
            'vencidosCount',
            'movimientosMensuales',
            'resumenMovimientos',
            'mesSeleccionado',
            'anioSeleccionado',
            'tabActiva'
        ));
    }

    public function movimientosMensualesPdf(Request $request)
    {
        $mesSeleccionado = (int) $request->input('mes', now()->month);
        $anioSeleccionado = (int) $request->input('anio', now()->year);

        if ($mesSeleccionado < 1 || $mesSeleccionado > 12) {
            $mesSeleccionado = now()->month;
        }

        $inicioMes = \Carbon\Carbon::createFromDate($anioSeleccionado, $mesSeleccionado, 1)->startOfMonth()->toDateString();
        $finMes = \Carbon\Carbon::createFromDate($anioSeleccionado, $mesSeleccionado, 1)->endOfMonth()->toDateString();

        $movimientosMensuales = $this->buildMovimientosMensualesQuery($inicioMes, $finMes)
            ->orderByDesc('total_general')
            ->get();

        $resumen = $this->buildMovimientosMensualesResumen($movimientosMensuales);

        $chartDir = storage_path('app/tmp/vehiculos_charts');
        if (!is_dir($chartDir)) {
            @mkdir($chartDir, 0775, true);
        }
        $chartToken = uniqid('veh_chart_', true);

        $chartCantidadPath = $this->renderPie3DFile(
            [
                'Mantenimientos' => $resumen['mantenimientos_count'],
                'Pagos' => $resumen['pagos_count'],
                'Combustible' => $resumen['combustible_count'],
                'Llantas' => $resumen['llantas_count'],
            ],
            ['#7c3aed', '#06b6d4', '#f97316', '#22c55e'],
            $chartDir . DIRECTORY_SEPARATOR . $chartToken . '_cantidad.png'
        );

        $chartMontoPath = $this->renderPie3DFile(
            [
                'Mantenimiento' => $resumen['mantenimientos_total'],
                'Pagos' => $resumen['pagos_total'],
                'Combustible' => $resumen['combustible_total'],
                'Llantas' => $resumen['llantas_total'],
            ],
            ['#f97316', '#2563eb', '#0ea5e9', '#22c55e'],
            $chartDir . DIRECTORY_SEPARATOR . $chartToken . '_monto.png'
        );

        $topPlacas = $movimientosMensuales
            ->sortByDesc('total_general')
            ->take(5)
            ->values();

        // Detalle ejecutivo de conceptos para identificar en qué se gasta.
        $detalleMantenimientos = DB::table('mantenimientos as m')
            ->join('vehiculos as v', 'v.id', '=', 'm.vehiculo_id')
            ->whereBetween('m.fecha', [$inicioMes, $finMes])
            ->selectRaw("
                m.fecha as fecha,
                v.placa as placa,
                'Mantenimiento' as origen,
                m.tipo as concepto,
                COALESCE(m.costo, 0) as monto
            ")
            ->get();

        $detallePagos = DB::table('pagos_vehiculo as p')
            ->join('vehiculos as v', 'v.id', '=', 'p.vehiculo_id')
            ->whereBetween('p.fecha_pago', [$inicioMes, $finMes])
            ->selectRaw("
                p.fecha_pago as fecha,
                v.placa as placa,
                'Pago administrativo' as origen,
                p.tipo_pago as concepto,
                COALESCE(p.monto, 0) as monto
            ")
            ->get();

        $detalleCombustible = collect();
        if (Schema::hasTable('cargas_combustible')) {
            $detalleCombustible = DB::table('cargas_combustible as c')
                ->join('vehiculos as v', 'v.id', '=', 'c.vehiculo_id')
                ->whereBetween('c.fecha_carga', [$inicioMes, $finMes])
                ->selectRaw("
                    c.fecha_carga as fecha,
                    v.placa as placa,
                    'Combustible' as origen,
                    CONCAT(UCASE(LEFT(c.tipo_combustible, 1)), SUBSTRING(c.tipo_combustible, 2), ' - ', COALESCE(c.proveedor, 'Sin proveedor')) as concepto,
                    COALESCE(c.costo_total, 0) as monto
                ")
                ->get();
        }

        $detalleLlantas = collect();
        if (Schema::hasTable('historial_llantas')) {
            $detalleLlantas = DB::table('historial_llantas as l')
                ->join('vehiculos as v', 'v.id', '=', 'l.vehiculo_id')
                ->whereBetween('l.fecha_instalacion', [$inicioMes, $finMes])
                ->selectRaw("
                    l.fecha_instalacion as fecha,
                    v.placa as placa,
                    'Llantas' as origen,
                    CONCAT(COALESCE(l.posicion, 'Sin posicion'), ' - ', COALESCE(l.marca, 'Sin marca')) as concepto,
                    COALESCE(l.costo, 0) as monto
                ")
                ->get();
        }

        $detalleGastos = $detalleMantenimientos
            ->merge($detallePagos)
            ->merge($detalleCombustible)
            ->merge($detalleLlantas)
            ->sortByDesc('fecha')
            ->values()
            ->take(12);

        $chartPlacasPath = $this->renderPie3DFile(
            $topPlacas->pluck('total_general', 'placa')->toArray(),
            ['#7c3aed', '#2563eb', '#06b6d4', '#22c55e', '#f59e0b'],
            $chartDir . DIRECTORY_SEPARATOR . $chartToken . '_placas.png'
        );

        $chartCantidadSrc = 'data:image/png;base64,' . base64_encode((string) @file_get_contents($chartCantidadPath));
        $chartMontoSrc = 'data:image/png;base64,' . base64_encode((string) @file_get_contents($chartMontoPath));
        $chartPlacasSrc = 'data:image/png;base64,' . base64_encode((string) @file_get_contents($chartPlacasPath));
        $Logo = public_path('images/Logo_AICO_R.jpg');
        $LogoSrc = null;
        if (file_exists($Logo)) {
            $ext = strtolower(pathinfo($Logo, PATHINFO_EXTENSION));
            $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
            $LogoSrc = 'data:' . $mime . ';base64,' . base64_encode((string) @file_get_contents($Logo));
        }

        return Pdf::loadView('vehiculos.reportes.movimientos_mensuales_pdf', [
            'movimientosMensuales' => $movimientosMensuales,
            'mesSeleccionado' => $mesSeleccionado,
            'anioSeleccionado' => $anioSeleccionado,
            'resumen' => $resumen,
            'chartCantidadSrc' => $chartCantidadSrc,
            'chartMontoSrc' => $chartMontoSrc,
            'chartPlacasSrc' => $chartPlacasSrc,
            'topPlacas' => $topPlacas,
            'detalleGastos' => $detalleGastos,
            'Logo' => $Logo,
            'LogoSrc' => $LogoSrc,
        ])->setPaper('letter', 'portrait')
          ->setOptions([
              'isRemoteEnabled' => true,
              'isHtml5ParserEnabled' => true,
          ])
          ->stream("movimientos_vehiculos_{$anioSeleccionado}_{$mesSeleccionado}.pdf");
    }

    private function buildMovimientosMensualesQuery(string $inicioMes, string $finMes)
    {
        $subMantenimientos = DB::table('mantenimientos')
            ->selectRaw('vehiculo_id, COUNT(*) as mantenimientos_count, COALESCE(SUM(costo), 0) as mantenimientos_total')
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->groupBy('vehiculo_id');

        $subPagos = DB::table('pagos_vehiculo')
            ->selectRaw('vehiculo_id, COUNT(*) as pagos_count, COALESCE(SUM(monto), 0) as pagos_total')
            ->whereBetween('fecha_pago', [$inicioMes, $finMes])
            ->groupBy('vehiculo_id');

        $subUso = DB::table('salidas_vehiculos as sv')
            ->leftJoin('salidas_checklists as scs', function ($join) {
                $join->on('scs.salida_vehiculo_id', '=', 'sv.id')
                    ->where('scs.tipo', '=', 'salida');
            })
            ->leftJoin('checklist_condiciones as ccs', 'ccs.salida_checklist_id', '=', 'scs.id')
            ->leftJoin('salidas_checklists as sce', function ($join) {
                $join->on('sce.salida_vehiculo_id', '=', 'sv.id')
                    ->where('sce.tipo', '=', 'entrada');
            })
            ->leftJoin('checklist_condiciones as cce', 'cce.salida_checklist_id', '=', 'sce.id')
            ->whereBetween('sv.fecha_salida', [$inicioMes . ' 00:00:00', $finMes . ' 23:59:59'])
            ->selectRaw("
                sv.vehiculo_id,
                COUNT(DISTINCT sv.id) as salidas_count,
                SUM(CASE WHEN sv.estatus IN ('finalizado', 'finaliizado') THEN 1 ELSE 0 END) as salidas_finalizadas,
                COALESCE(SUM(GREATEST(COALESCE(cce.kilometraje, 0) - COALESCE(ccs.kilometraje, 0), 0)), 0) as km_recorridos_total,
                COALESCE(AVG(COALESCE(sv.duracion_minutos, TIMESTAMPDIFF(MINUTE, sv.fecha_salida, sv.fecha_regreso))), 0) as duracion_promedio_minutos
            ")
            ->groupBy('sv.vehiculo_id');

        $hasCombustible = Schema::hasTable('cargas_combustible');
        $hasLlantas = Schema::hasTable('historial_llantas');

        $totalParts = [
            'COALESCE(m.mantenimientos_total, 0)',
            'COALESCE(p.pagos_total, 0)',
        ];

        $query = DB::table('vehiculos as v')
            ->leftJoinSub($subMantenimientos, 'm', function ($join) {
                $join->on('m.vehiculo_id', '=', 'v.id');
            })
            ->leftJoinSub($subPagos, 'p', function ($join) {
                $join->on('p.vehiculo_id', '=', 'v.id');
            })
            ->leftJoinSub($subUso, 'u', function ($join) {
                $join->on('u.vehiculo_id', '=', 'v.id');
            })
            ->selectRaw('
                v.id,
                v.placa,
                v.marca,
                v.modelo,
                COALESCE(m.mantenimientos_count, 0) as mantenimientos_count,
                COALESCE(m.mantenimientos_total, 0) as mantenimientos_total,
                COALESCE(p.pagos_count, 0) as pagos_count,
                COALESCE(p.pagos_total, 0) as pagos_total,
                COALESCE(u.salidas_count, 0) as salidas_count,
                COALESCE(u.salidas_finalizadas, 0) as salidas_finalizadas,
                COALESCE(u.km_recorridos_total, 0) as km_recorridos_total,
                COALESCE(u.duracion_promedio_minutos, 0) as duracion_promedio_minutos
            ');

        if ($hasCombustible) {
            $subCombustible = DB::table('cargas_combustible')
                ->selectRaw('vehiculo_id, COUNT(*) as combustible_count, COALESCE(SUM(costo_total), 0) as combustible_total')
                ->whereBetween('fecha_carga', [$inicioMes, $finMes])
                ->groupBy('vehiculo_id');

            $query->leftJoinSub($subCombustible, 'c', function ($join) {
                $join->on('c.vehiculo_id', '=', 'v.id');
            })->selectRaw('
                COALESCE(c.combustible_count, 0) as combustible_count,
                COALESCE(c.combustible_total, 0) as combustible_total
            ');

            $totalParts[] = 'COALESCE(c.combustible_total, 0)';
        } else {
            $query->selectRaw('0 as combustible_count, 0 as combustible_total');
            $totalParts[] = '0';
        }

        if ($hasLlantas) {
            $subLlantas = DB::table('historial_llantas')
                ->selectRaw('vehiculo_id, COUNT(*) as llantas_count, COALESCE(SUM(costo), 0) as llantas_total')
                ->whereBetween('fecha_instalacion', [$inicioMes, $finMes])
                ->groupBy('vehiculo_id');

            $query->leftJoinSub($subLlantas, 'l', function ($join) {
                $join->on('l.vehiculo_id', '=', 'v.id');
            })->selectRaw('
                COALESCE(l.llantas_count, 0) as llantas_count,
                COALESCE(l.llantas_total, 0) as llantas_total
            ');

            $totalParts[] = 'COALESCE(l.llantas_total, 0)';
        } else {
            $query->selectRaw('0 as llantas_count, 0 as llantas_total');
            $totalParts[] = '0';
        }

        $totalExpression = '(' . implode(' + ', $totalParts) . ')';

        return $query
            ->selectRaw($totalExpression . ' as total_general')
            ->where(function ($q) use ($hasCombustible, $hasLlantas) {
                $q->whereRaw('COALESCE(m.mantenimientos_count, 0) > 0')
                    ->orWhereRaw('COALESCE(p.pagos_count, 0) > 0')
                    ->orWhereRaw('COALESCE(u.salidas_count, 0) > 0')
                    ->orWhereRaw('COALESCE(u.km_recorridos_total, 0) > 0');

                if ($hasCombustible) {
                    $q->orWhereRaw('COALESCE(c.combustible_count, 0) > 0');
                }

                if ($hasLlantas) {
                    $q->orWhereRaw('COALESCE(l.llantas_count, 0) > 0');
                }
            });
    }

    private function buildMovimientosMensualesResumen($movimientosMensuales): array
    {
        $resumen = $this->emptyMovimientosResumen();

        $resumen['vehiculos_con_movimientos'] = (int) $movimientosMensuales->count();
        $resumen['mantenimientos_count'] = (int) $movimientosMensuales->sum('mantenimientos_count');
        $resumen['pagos_count'] = (int) $movimientosMensuales->sum('pagos_count');
        $resumen['combustible_count'] = (int) $movimientosMensuales->sum('combustible_count');
        $resumen['llantas_count'] = (int) $movimientosMensuales->sum('llantas_count');
        $resumen['salidas_count'] = (int) $movimientosMensuales->sum('salidas_count');
        $resumen['salidas_finalizadas'] = (int) $movimientosMensuales->sum('salidas_finalizadas');
        $resumen['mantenimientos_total'] = (float) $movimientosMensuales->sum('mantenimientos_total');
        $resumen['pagos_total'] = (float) $movimientosMensuales->sum('pagos_total');
        $resumen['combustible_total'] = (float) $movimientosMensuales->sum('combustible_total');
        $resumen['llantas_total'] = (float) $movimientosMensuales->sum('llantas_total');
        $resumen['km_recorridos_total'] = (float) $movimientosMensuales->sum('km_recorridos_total');
        $resumen['total_general'] = (float) $movimientosMensuales->sum('total_general');
        $resumen['costo_promedio_km'] = $resumen['km_recorridos_total'] > 0
            ? round($resumen['total_general'] / $resumen['km_recorridos_total'], 2)
            : 0.0;

        return $resumen;
    }

    private function emptyMovimientosResumen(): array
    {
        return [
            'vehiculos_con_movimientos' => 0,
            'mantenimientos_count' => 0,
            'pagos_count' => 0,
            'combustible_count' => 0,
            'llantas_count' => 0,
            'salidas_count' => 0,
            'salidas_finalizadas' => 0,
            'mantenimientos_total' => 0.0,
            'pagos_total' => 0.0,
            'combustible_total' => 0.0,
            'llantas_total' => 0.0,
            'km_recorridos_total' => 0.0,
            'total_general' => 0.0,
            'costo_promedio_km' => 0.0,
        ];
    }

    private function renderPie3DFile(array $data, array $palette, string $outputPath): string
    {
        $values = array_values(array_map(fn ($v) => max(0, (float) $v), $data));
        $labels = array_keys($data);
        $total = array_sum($values);

        if ($total <= 0) {
            $values = [1.0];
            $labels = ['Sin datos'];
            $palette = ['#9ca3af'];
            $total = 1.0;
        }

        $w = 1120;
        $h = 640;
        $img = imagecreatetruecolor($w, $h);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        $bg = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        $cx = 360;
        $cy = 290;
        $diameter = 430;
        $depth = 44;

        $sliceColors = [];
        foreach ($labels as $i => $label) {
            $hex = $palette[$i % count($palette)];
            $sliceColors[] = [
                'top' => $this->hexToColor($img, $hex),
                'side' => $this->hexToColor($img, $hex, 0.65),
                'hex' => $hex,
            ];
        }

        // Cuerpo 3D (capas inferiores)
        for ($layer = $depth; $layer >= 1; $layer--) {
            $start = 0.0;
            foreach ($values as $i => $v) {
                $angle = ($v / $total) * 360.0;
                imagefilledarc(
                    $img,
                    $cx,
                    $cy + $layer,
                    $diameter,
                    (int) ($diameter * 0.62),
                    $start,
                    $start + $angle,
                    $sliceColors[$i]['side'],
                    IMG_ARC_PIE
                );
                $start += $angle;
            }
        }

        // Capa superior
        $start = 0.0;
        foreach ($values as $i => $v) {
            $angle = ($v / $total) * 360.0;
            imagefilledarc(
                $img,
                $cx,
                $cy,
                $diameter,
                (int) ($diameter * 0.62),
                $start,
                $start + $angle,
                $sliceColors[$i]['top'],
                IMG_ARC_PIE
            );
            $start += $angle;
        }

        // Leyenda
        $legendX = 680;
        $legendY = 120;
        $font = 5;
        $ttfPath = resource_path('fonts/arial.ttf');
        foreach ($labels as $i => $label) {
            $pct = ($values[$i] / $total) * 100;
            imagefilledrectangle($img, $legendX, $legendY + ($i * 48), $legendX + 34, $legendY + 34 + ($i * 48), $sliceColors[$i]['top']);
            $textColor = imagecolorallocate($img, 30, 41, 59);
            $text = $label . ' (' . number_format($pct, 1) . '%)';
            if (function_exists('imagettftext') && file_exists($ttfPath)) {
                imagettftext(
                    $img,
                    28,
                    0,
                    $legendX + 44,
                    $legendY + 26 + ($i * 48),
                    $textColor,
                    $ttfPath,
                    $text
                );
            } else {
                imagestring($img, $font, $legendX + 44, $legendY + 10 + ($i * 48), $text, $textColor);
            }
        }

        $saved = imagepng($img, $outputPath);
        imagedestroy($img);

        if (!$saved || !file_exists($outputPath)) {
            throw new \RuntimeException("No se pudo generar la grafica PNG en: {$outputPath}");
        }

        return str_replace('\\', '/', $outputPath);
    }

    private function hexToColor($img, string $hex, float $factor = 1.0): int
    {
        $hex = ltrim($hex, '#');
        $r = (int) hexdec(substr($hex, 0, 2));
        $g = (int) hexdec(substr($hex, 2, 2));
        $b = (int) hexdec(substr($hex, 4, 2));

        $r = max(0, min(255, (int) ($r * $factor)));
        $g = max(0, min(255, (int) ($g * $factor)));
        $b = max(0, min(255, (int) ($b * $factor)));

        return imagecolorallocate($img, $r, $g, $b);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehiculos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculoRequest $request)
    {
        //dd($request->all());
        $data = $request->validated();
        $ESPERADATO = 'ESPERA DE DATO';

        // crear vehículo sin archivos primero
        /*$vehiculo = Vehiculo::create(
            array_merge($data, ['kilometraje_actual' => $data['kilometraje_actual'] ?? 0])
        );*/
        $vehiculo = new Vehiculo($data);
        $vehiculo->kilometraje_actual = $data['kilometraje_actual'] ?? 0;
        $vehiculo->save(); 
        // almacenar PDFs en carpeta organizada
        if ($request->hasFile('poliza_seguro_pdf') && $request->file('poliza_seguro_pdf') != null) {
            $path = $request->file('poliza_seguro_pdf')->store("vehiculos/{$vehiculo->id}/poliza", 'public');
            $vehiculo->poliza_seguro_pdf = $path;
        }else{
            $vehiculo->poliza_seguro_pdf = $ESPERADATO;
        }
        if ($request->hasFile('tarjeta_circulacion_pdf') && $request->file('tarjeta_circulacion_pdf') != null) {
            $path = $request->file('tarjeta_circulacion_pdf')->store("vehiculos/{$vehiculo->id}/tarjeta", 'public');
            $vehiculo->tarjeta_circulacion_pdf = $path;
        }else{
            $vehiculo->tarjeta_circulacion_pdf = $ESPERADATO;
        }
        // fechas
        /*if ($request->filled('poliza_seguro_vencimiento') && $request->input('poliza_seguro_vencimiento') != null) {
            $vehiculo->poliza_seguro_vencimiento = $request->input('poliza_seguro_vencimiento');
        }else{
            $vehiculo->poliza_seguro_vencimiento = '2001-01-01';
        }
        if ($request->filled('tarjeta_circulacion_vencimiento') && $request->input('tarjeta_circulacion_vencimiento') != null) {
            $vehiculo->tarjeta_circulacion_vencimiento = $request->input('tarjeta_circulacion_vencimiento');
        }else{
            $vehiculo->tarjeta_circulacion_vencimiento = '2001-01-01';
        }*/
        $vehiculo->poliza_seguro_vencimiento = $request->filled('poliza_seguro_vencimiento')
        ? $request->input('poliza_seguro_vencimiento')
        : null;

        if ($request->hasFile('foto_principal')) {

            $archivo = $request->file('foto_principal');

            $ruta = $archivo->storeAs(
                "vehiculos/{$vehiculo->id}/FotoPrincipal",
                "FotoPrincipal.".$archivo->getClientOriginalExtension(),
                'public'
            );

            // Guardamos SOLO la ruta relativa
            $vehiculo->foto_principal = $ruta;
        }
        
        $vehiculo->tarjeta_circulacion_vencimiento = $request->filled('tarjeta_circulacion_vencimiento')
        ? $request->input('tarjeta_circulacion_vencimiento')
        : null;


        $vehiculo->save();

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        return view('vehiculos.edit', compact('vehiculo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehiculoRequest $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $data = $request->validated();
        // 1) actualizar solo campos que no son docs/fechas de docs
        $vehiculo->fill(collect($data)->except([
            'poliza_seguro_pdf',
            'tarjeta_circulacion_pdf',
            'poliza_seguro_vencimiento',
            'tarjeta_circulacion_vencimiento',
        ])->toArray());

        // 2) archivos (solo si suben uno nuevo)
        if ($request->hasFile('poliza_seguro_pdf')) {
            $vehiculo->poliza_seguro_pdf = $request->file('poliza_seguro_pdf')
                ->store("vehiculos/{$NombreVehiculo}/poliza", 'public');
        }

        if ($request->hasFile('tarjeta_circulacion_pdf')) {
            $vehiculo->tarjeta_circulacion_pdf = $request->file('tarjeta_circulacion_pdf')
                ->store("vehiculos/{$NombreVehiculo}/tarjeta", 'public');
        }

        // 3) fechas docs: SOLO cambiar si vienen con valor
        if ($request->filled('poliza_seguro_vencimiento')) {
            $vehiculo->poliza_seguro_vencimiento = $request->input('poliza_seguro_vencimiento');
        }

        // 🔁 Si se sube una nueva imagen
        if ($request->hasFile('foto_principal')) {

            // Eliminar imagen anterior
            if ($vehiculo->foto_principal) {
                Storage::disk('public')->delete($vehiculo->foto_principal);
            }

            $archivo = $request->file('foto_principal');

            $ruta = $archivo->storeAs(
                "vehiculos/{$vehiculo->id}/FotoPrincipal",
                "FotoPrincipal.".$archivo->getClientOriginalExtension(),
                'public'
            );

            $vehiculo->foto_principal = $ruta;
        }

        // Otros campos que estés actualizando
        $vehiculo->placa = $request->placa;
        $vehiculo->marca = $request->marca;
        $vehiculo->modelo = $request->modelo;

        if ($request->filled('tarjeta_circulacion_vencimiento')) {
            $vehiculo->tarjeta_circulacion_vencimiento = $request->input('tarjeta_circulacion_vencimiento');
        }

        $vehiculo->save();

        return redirect()->route('vehiculos.index')->with('success', 'Vehiculo actualizado');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Vehiculo::findOrFail($id)->first();

        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update([
            'estatus' => 'baja',
        ]);
        
        return redirect()->route('vehiculos.index')->with('success', 'Vehiculo eliminado');

    }
}
