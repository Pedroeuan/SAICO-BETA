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
        if ($tabActiva === 'movimientos') {
            $subMantenimientos = DB::table('mantenimientos')
                ->selectRaw('vehiculo_id, COUNT(*) as mantenimientos_count, COALESCE(SUM(costo), 0) as mantenimientos_total')
                ->whereBetween('fecha', [$inicioMes, $finMes])
                ->groupBy('vehiculo_id');

            $subPagos = DB::table('pagos_vehiculo')
                ->selectRaw('vehiculo_id, COUNT(*) as pagos_count, COALESCE(SUM(monto), 0) as pagos_total')
                ->whereBetween('fecha_pago', [$inicioMes, $finMes])
                ->groupBy('vehiculo_id');

            $movimientosMensuales = DB::table('vehiculos as v')
                ->leftJoinSub($subMantenimientos, 'm', function ($join) {
                    $join->on('m.vehiculo_id', '=', 'v.id');
                })
                ->leftJoinSub($subPagos, 'p', function ($join) {
                    $join->on('p.vehiculo_id', '=', 'v.id');
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
                    (COALESCE(m.mantenimientos_total, 0) + COALESCE(p.pagos_total, 0)) as total_general
                ')
                ->where(function ($q) {
                    $q->whereRaw('COALESCE(m.mantenimientos_count, 0) > 0')
                        ->orWhereRaw('COALESCE(p.pagos_count, 0) > 0');
                })
                ->orderByDesc('total_general')
                ->paginate(15)
                ->appends([
                    'mes' => $mesSeleccionado,
                    'anio' => $anioSeleccionado,
                    'tab' => 'movimientos',
                ]);
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

        $subMantenimientos = DB::table('mantenimientos')
            ->selectRaw('vehiculo_id, COUNT(*) as mantenimientos_count, COALESCE(SUM(costo), 0) as mantenimientos_total')
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->groupBy('vehiculo_id');

        $subPagos = DB::table('pagos_vehiculo')
            ->selectRaw('vehiculo_id, COUNT(*) as pagos_count, COALESCE(SUM(monto), 0) as pagos_total')
            ->whereBetween('fecha_pago', [$inicioMes, $finMes])
            ->groupBy('vehiculo_id');

        $movimientosMensuales = DB::table('vehiculos as v')
            ->leftJoinSub($subMantenimientos, 'm', function ($join) {
                $join->on('m.vehiculo_id', '=', 'v.id');
            })
            ->leftJoinSub($subPagos, 'p', function ($join) {
                $join->on('p.vehiculo_id', '=', 'v.id');
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
                (COALESCE(m.mantenimientos_total, 0) + COALESCE(p.pagos_total, 0)) as total_general
            ')
            ->where(function ($q) {
                $q->whereRaw('COALESCE(m.mantenimientos_count, 0) > 0')
                    ->orWhereRaw('COALESCE(p.pagos_count, 0) > 0');
            })
            ->orderByDesc('total_general')
            ->get();

        $resumen = [
            'vehiculos_con_movimientos' => $movimientosMensuales->count(),
            'mantenimientos_count' => (int) $movimientosMensuales->sum('mantenimientos_count'),
            'pagos_count' => (int) $movimientosMensuales->sum('pagos_count'),
            'mantenimientos_total' => (float) $movimientosMensuales->sum('mantenimientos_total'),
            'pagos_total' => (float) $movimientosMensuales->sum('pagos_total'),
            'total_general' => (float) $movimientosMensuales->sum('total_general'),
        ];

        $chartDir = storage_path('app/tmp/vehiculos_charts');
        if (!is_dir($chartDir)) {
            @mkdir($chartDir, 0775, true);
        }
        $chartToken = uniqid('veh_chart_', true);

        $chartCantidadPath = $this->renderPie3DFile(
            [
                'Mantenimientos' => $resumen['mantenimientos_count'],
                'Pagos' => $resumen['pagos_count'],
            ],
            ['#7c3aed', '#06b6d4'],
            $chartDir . DIRECTORY_SEPARATOR . $chartToken . '_cantidad.png'
        );

        $chartMontoPath = $this->renderPie3DFile(
            [
                'Mantenimiento' => $resumen['mantenimientos_total'],
                'Pagos' => $resumen['pagos_total'],
            ],
            ['#f97316', '#2563eb'],
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

        $detalleGastos = $detalleMantenimientos
            ->merge($detallePagos)
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
        Vehiculo::findOrFail($id)->delete();

        return redirect()->route('vehiculos.index')->with('success', 'Vehiculo eliminado');

    }
}
