<?php

namespace App\Http\Controllers\Vehiculos;

use App\Exports\VehiculosRendimientoExport;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RendimientoExportController extends Controller
{
    public function pdf(Request $request, string $periodo)
    {
        [$inicio, $fin, $labelPeriodo] = $this->resolverRango($periodo, $request);

        $data = $this->buildData($inicio, $fin, strtolower(trim($periodo)));
        $data['periodo'] = $labelPeriodo;
        $data['inicio'] = $inicio;
        $data['fin'] = $fin;

        $logoPath = public_path('images/Logo_AICO_R.jpg');
        $data['logoSrc'] = file_exists($logoPath)
            ? 'data:image/jpeg;base64,' . base64_encode((string) file_get_contents($logoPath))
            : null;

        $data['chartEstatusSrc'] = $this->renderPie3DBase64(
            [
                'Vehículo con salida' => (int) $data['salidasActivas'],
                'Vehículo Disponible' => (int) $data['salidasFinalizadas'],
            ],
            ['#f59e0b', '#10b981']
        );

        $topVehiculosChart = $data['porVehiculo']->take(5);
        $topVehiculosData = [];
        foreach ($topVehiculosChart as $item) {
            $label = ($item->placa ?? 'N/A') . ' (' . ($item->marca ?? 'N/A') . ')';
            $topVehiculosData[$label] = (int) ($item->total ?? 0);
        }

        $data['chartTopVehiculosSrc'] = $this->renderPie3DBase64(
            $topVehiculosData,
            ['#2563eb', '#7c3aed', '#06b6d4', '#22c55e', '#f97316']
        );

        $salidasPeriodoData = [];
        foreach (($data['chartSalidasPeriodo'] ?? []) as $row) {
            $salidasPeriodoData[(string) ($row['label'] ?? 'N/A')] = (float) ($row['valor'] ?? 0);
        }
        $data['chartSalidasPeriodoSrc'] = $this->renderPie3DBase64(
            $salidasPeriodoData,
            ['#60a5fa', '#38bdf8', '#34d399', '#fbbf24', '#fb7185', '#a78bfa', '#22c55e']
        );

        $choferesData = [];
        foreach (($data['chartSolicitantes'] ?? collect()) as $row) {
            $choferesData[(string) ($row->label ?? 'N/A')] = (float) ($row->valor ?? 0);
        }
        $data['chartChoferesSrc'] = $this->renderPie3DBase64(
            $choferesData,
            ['#06b6d4', '#14b8a6', '#0ea5e9', '#22c55e', '#f59e0b']
        );

        $kmPeriodoData = [];
        foreach (($data['chartKmPeriodo'] ?? []) as $row) {
            $kmPeriodoData[(string) ($row['label'] ?? 'N/A')] = (float) ($row['valor'] ?? 0);
        }
        $data['chartKmPeriodoSrc'] = $this->renderPie3DBase64(
            $kmPeriodoData,
            ['#84cc16', '#facc15', '#2dd4bf', '#38bdf8', '#22c55e', '#f97316']
        );

        $incidenciasData = [];
        foreach (($data['chartIncidencias'] ?? collect()) as $row) {
            $incidenciasData[(string) ($row->label ?? 'N/A')] = (float) ($row->valor ?? 0);
        }
        $data['chartIncidenciasSrc'] = $this->renderPie3DBase64(
            $incidenciasData,
            ['#f43f5e', '#f97316', '#ef4444', '#fb7185', '#f59e0b']
        );

        $pdf = Pdf::loadView('vehiculos.reportes.rendimiento_pdf', $data)
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="rendimiento_vehiculos_' . $periodo . '.pdf"',
        ]);
    }

    public function excel(Request $request, string $periodo)
    {
        [$inicio, $fin] = $this->resolverRango($periodo, $request);

        return Excel::download(
            new VehiculosRendimientoExport($inicio, $fin),
            "rendimiento_vehiculos_{$periodo}.xlsx"
        );
    }

    private function resolverRango(string $periodo, Request $request): array
    {
        $now = Carbon::now();
        $periodo = strtolower(trim($periodo));

        if ($request->filled('mes')) {
            try {
                $now = Carbon::createFromFormat('Y-m', $request->input('mes'))->startOfMonth();
            } catch (\Throwable $e) {
                $now = Carbon::now();
            }
        } elseif ($request->filled('fecha')) {
            $now = Carbon::parse($request->input('fecha'));
        }

        switch ($periodo) {
            case 'semana':
                $inicio = $now->copy()->startOfWeek();
                $fin = $now->copy()->endOfWeek();
                $label = 'Semana';
                break;
            case 'mes_pasado':
                $inicio = $now->copy()->subMonthNoOverflow()->startOfMonth();
                $fin = $now->copy()->subMonthNoOverflow()->endOfMonth();
                $label = 'Mes Pasado';
                break;
            case 'anio':
            case 'año':
                $inicio = $now->copy()->startOfYear();
                $fin = $now->copy()->endOfYear();
                $label = 'Anio';
                break;
            case 'mes':
            default:
                $inicio = $now->copy()->startOfMonth();
                $fin = $now->copy()->endOfMonth();
                $label = 'Mes';
                break;
        }

        return [$inicio, $fin, $label];
    }

    private function buildData(Carbon $inicio, Carbon $fin, string $periodo): array
    {
        $totales = DB::table('salidas_vehiculos as sv')
            ->whereBetween('sv.fecha_salida', [$inicio, $fin])
            ->selectRaw('COUNT(*) as total_salidas')
            ->selectRaw("SUM(CASE WHEN sv.estatus = 'activo' THEN 1 ELSE 0 END) as salidas_activas")
            ->selectRaw("SUM(CASE WHEN sv.estatus = 'finalizado' THEN 1 ELSE 0 END) as salidas_finalizadas")
            ->selectRaw('AVG(sv.duracion_minutos) as tiempo_promedio')
            ->first();

        $kmChecklistPorVehiculo = DB::table('salidas_vehiculos as svk')
            ->join('salidas_checklists as sck', 'sck.salida_vehiculo_id', '=', 'svk.id')
            ->join('checklist_condiciones as cck', 'cck.salida_checklist_id', '=', 'sck.id')
            ->whereNotNull('cck.kilometraje')
            ->groupBy('svk.vehiculo_id')
            ->selectRaw('svk.vehiculo_id, MAX(cck.kilometraje) as km_checklist_max');

        $porVehiculo = DB::table('salidas_vehiculos as sv')
            ->join('vehiculos as v', 'v.id', '=', 'sv.vehiculo_id')
            ->leftJoinSub($kmChecklistPorVehiculo, 'kmc', function ($join) {
                $join->on('kmc.vehiculo_id', '=', 'sv.vehiculo_id');
            })
            ->whereBetween('sv.fecha_salida', [$inicio, $fin])
            ->selectRaw('sv.vehiculo_id')
            ->selectRaw('v.placa, v.marca, v.modelo, v.anio, v.estatus, v.kilometraje_actual, v.documentacion_estatus')
            ->selectRaw('COALESCE(kmc.km_checklist_max, v.kilometraje_actual, 0) as kilometraje_reporte')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('COALESCE(SUM(sv.duracion_minutos), 0) as total_minutos')
            ->selectRaw('COALESCE(AVG(sv.duracion_minutos), 0) as promedio_minutos')
            ->selectRaw('MAX(sv.fecha_salida) as ultima_salida')
            ->groupBy(
                'sv.vehiculo_id',
                'v.placa',
                'v.marca',
                'v.modelo',
                'v.anio',
                'v.estatus',
                'v.kilometraje_actual',
                'v.documentacion_estatus',
                'kmc.km_checklist_max'
            )
            ->orderByDesc('total')
            ->get();

        $totalSalidas = (int) ($totales->total_salidas ?? 0);
        $salidasActivas = (int) ($totales->salidas_activas ?? 0);
        $salidasFinalizadas = (int) ($totales->salidas_finalizadas ?? 0);
        $tiempoPromedio = (float) ($totales->tiempo_promedio ?? 0);

        $chartSalidasPeriodo = [];
        $chartKmPeriodo = [];
        $tituloSalidasPeriodo = 'Salidas por Mes';
        $tituloKmPeriodo = 'Km Recorridos por Mes';

        if ($periodo === 'semana') {
            $tituloSalidasPeriodo = 'Salidas por Dia (Semana)';
            $tituloKmPeriodo = 'Km Recorridos por Dia (Semana)';

            $salidasSemanaRaw = DB::table('salidas_vehiculos as sv')
                ->whereBetween('sv.fecha_salida', [$inicio, $fin])
                ->selectRaw('DATE(sv.fecha_salida) as fecha, COUNT(*) as total')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->pluck('total', 'fecha');

            $kmSemanaRaw = DB::table('salidas_vehiculos as sv')
                ->join('salidas_checklists as scs', function ($join) {
                    $join->on('scs.salida_vehiculo_id', '=', 'sv.id')
                        ->where('scs.tipo', '=', 'salida');
                })
                ->join('checklist_condiciones as ccs', 'ccs.salida_checklist_id', '=', 'scs.id')
                ->join('salidas_checklists as sce', function ($join) {
                    $join->on('sce.salida_vehiculo_id', '=', 'sv.id')
                        ->where('sce.tipo', '=', 'entrada');
                })
                ->join('checklist_condiciones as cce', 'cce.salida_checklist_id', '=', 'sce.id')
                ->whereBetween('sv.fecha_salida', [$inicio, $fin])
                ->selectRaw('DATE(sv.fecha_salida) as fecha, SUM(GREATEST(cce.kilometraje - ccs.kilometraje, 0)) as total_km')
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->pluck('total_km', 'fecha');

            $dias = ['dom', 'lun', 'mar', 'mie', 'jue', 'vie', 'sab'];
            $cursor = $inicio->copy()->startOfDay();
            $finSemana = $fin->copy()->startOfDay();

            while ($cursor->lte($finSemana)) {
                $fechaKey = $cursor->toDateString();
                $diaLabel = ucfirst($dias[(int) $cursor->dayOfWeek]) . ' ' . $cursor->format('d/m');

                $chartSalidasPeriodo[] = [
                    'label' => $diaLabel,
                    'valor' => (int) ($salidasSemanaRaw[$fechaKey] ?? 0),
                ];
                $chartKmPeriodo[] = [
                    'label' => $diaLabel,
                    'valor' => round((float) ($kmSemanaRaw[$fechaKey] ?? 0), 2),
                ];
                $cursor->addDay();
            }
        } elseif ($periodo === 'anio' || $periodo === 'año') {
            $tituloSalidasPeriodo = 'Salidas por Mes';
            $tituloKmPeriodo = 'Km Recorridos por Mes';
            $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

            $salidasPorMesRaw = DB::table('salidas_vehiculos as sv')
                ->whereYear('sv.fecha_salida', $fin->year)
                ->selectRaw('MONTH(sv.fecha_salida) as mes, COUNT(*) as total')
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total', 'mes');

            $kmPorMesRaw = DB::table('salidas_vehiculos as sv')
                ->join('salidas_checklists as scs', function ($join) {
                    $join->on('scs.salida_vehiculo_id', '=', 'sv.id')
                        ->where('scs.tipo', '=', 'salida');
                })
                ->join('checklist_condiciones as ccs', 'ccs.salida_checklist_id', '=', 'scs.id')
                ->join('salidas_checklists as sce', function ($join) {
                    $join->on('sce.salida_vehiculo_id', '=', 'sv.id')
                        ->where('sce.tipo', '=', 'entrada');
                })
                ->join('checklist_condiciones as cce', 'cce.salida_checklist_id', '=', 'sce.id')
                ->whereYear('sv.fecha_salida', $fin->year)
                ->selectRaw('MONTH(sv.fecha_salida) as mes, SUM(GREATEST(cce.kilometraje - ccs.kilometraje, 0)) as total_km')
                ->groupBy('mes')
                ->orderBy('mes')
                ->pluck('total_km', 'mes');

            for ($i = 1; $i <= 12; $i++) {
                $chartSalidasPeriodo[] = ['label' => $meses[$i - 1], 'valor' => (int) ($salidasPorMesRaw[$i] ?? 0)];
                $chartKmPeriodo[] = ['label' => $meses[$i - 1], 'valor' => round((float) ($kmPorMesRaw[$i] ?? 0), 2)];
            }
        } else {
            $tituloSalidasPeriodo = 'Salidas por Semana del Mes';
            $tituloKmPeriodo = 'Km por Semana del Mes';
            $labelsSemanas = ['Sem 1', 'Sem 2', 'Sem 3', 'Sem 4', 'Sem 5', 'Sem 6'];

            $salidasSemanaMesRaw = DB::table('salidas_vehiculos as sv')
                ->whereBetween('sv.fecha_salida', [$inicio, $fin])
                ->selectRaw('FLOOR((DAY(sv.fecha_salida)-1)/7)+1 as semana_mes, COUNT(*) as total')
                ->groupBy('semana_mes')
                ->orderBy('semana_mes')
                ->pluck('total', 'semana_mes');

            $kmSemanaMesRaw = DB::table('salidas_vehiculos as sv')
                ->join('salidas_checklists as scs', function ($join) {
                    $join->on('scs.salida_vehiculo_id', '=', 'sv.id')
                        ->where('scs.tipo', '=', 'salida');
                })
                ->join('checklist_condiciones as ccs', 'ccs.salida_checklist_id', '=', 'scs.id')
                ->join('salidas_checklists as sce', function ($join) {
                    $join->on('sce.salida_vehiculo_id', '=', 'sv.id')
                        ->where('sce.tipo', '=', 'entrada');
                })
                ->join('checklist_condiciones as cce', 'cce.salida_checklist_id', '=', 'sce.id')
                ->whereBetween('sv.fecha_salida', [$inicio, $fin])
                ->selectRaw('FLOOR((DAY(sv.fecha_salida)-1)/7)+1 as semana_mes, SUM(GREATEST(cce.kilometraje - ccs.kilometraje, 0)) as total_km')
                ->groupBy('semana_mes')
                ->orderBy('semana_mes')
                ->pluck('total_km', 'semana_mes');

            for ($i = 1; $i <= 6; $i++) {
                $chartSalidasPeriodo[] = ['label' => $labelsSemanas[$i - 1], 'valor' => (int) ($salidasSemanaMesRaw[$i] ?? 0)];
                $chartKmPeriodo[] = ['label' => $labelsSemanas[$i - 1], 'valor' => round((float) ($kmSemanaMesRaw[$i] ?? 0), 2)];
            }
        }

        $chartSolicitantes = DB::table('salidas_vehiculos as sv')
            ->leftJoin('users as u', 'u.id', '=', 'sv.chofer_id')
            ->whereBetween('sv.fecha_salida', [$inicio, $fin])
            ->selectRaw("COALESCE(NULLIF(TRIM(u.name), ''), 'Sin nombre') as label, COUNT(*) as valor")
            ->groupBy('sv.chofer_id', 'u.name')
            ->orderByDesc('valor')
            ->limit(5)
            ->get();

        $chartIncidencias = DB::table('salidas_vehiculos as sv')
            ->join('vehiculos as v', 'v.id', '=', 'sv.vehiculo_id')
            ->leftJoin('salidas_checklists as sc', 'sc.salida_vehiculo_id', '=', 'sv.id')
            ->leftJoin('checklist_condiciones as cc', 'cc.salida_checklist_id', '=', 'sc.id')
            ->whereBetween('sv.fecha_salida', [$inicio, $fin])
            ->selectRaw("CONCAT('Placa ', v.placa, ' - ', COALESCE(v.marca, 'N/A')) as label")
            ->selectRaw("COUNT(DISTINCT CASE WHEN cc.observaciones IS NOT NULL AND TRIM(cc.observaciones) <> '' THEN sv.id END) as valor")
            ->groupBy('sv.vehiculo_id', 'v.placa', 'v.marca')
            ->havingRaw("COUNT(DISTINCT CASE WHEN cc.observaciones IS NOT NULL AND TRIM(cc.observaciones) <> '' THEN sv.id END) > 0")
            ->orderByDesc('valor')
            ->limit(10)
            ->get();
        return compact(
            'totalSalidas',
            'salidasActivas',
            'salidasFinalizadas',
            'tiempoPromedio',
            'porVehiculo',
            'chartSalidasPeriodo',
            'tituloSalidasPeriodo',
            'chartSolicitantes',
            'chartKmPeriodo',
            'tituloKmPeriodo',
            'chartIncidencias'
        );
    }

    private function renderPie3DBase64(array $data, array $palette): string
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

        $w = 1280;
        $h = 720;
        $img = imagecreatetruecolor($w, $h);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        $bg = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        $cx = 400;
        $cy = 310;
        $diameter = 500;
        $depth = 42;

        $sliceColors = [];
        foreach ($labels as $i => $label) {
            $hex = $palette[$i % count($palette)];
            $sliceColors[] = [
                'top' => $this->hexToColor($img, $hex),
                'side' => $this->hexToColor($img, $hex, 0.65),
            ];
        }

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

        $legendX = 720;
        $legendTop = 56;
        $legendBottom = $h - 50;
        $legendCount = max(1, count($labels));
        $columns = $legendCount > 7 ? 2 : 1;
        $rowsPerColumn = (int) ceil($legendCount / $columns);
        $availableHeight = max(180, $legendBottom - $legendTop);
        $rowStep = (int) floor($availableHeight / max(1, $rowsPerColumn));
        $rowStep = max(56, min(96, $rowStep));
        $boxSize = max(34, min(56, $rowStep - 18));
        $fontSize = max(16, min(28, $rowStep - 25));
        $colWidth = 260;
        $ttfPath = resource_path('fonts/arial.ttf');
        foreach ($labels as $i => $label) {
            $pct = ($values[$i] / $total) * 100;
            $col = (int) floor($i / $rowsPerColumn);
            $row = $i % $rowsPerColumn;
            $x = $legendX + ($col * $colWidth);
            $y = $legendTop + ($row * $rowStep);

            imagefilledrectangle($img, $x, $y, $x + $boxSize, $y + $boxSize, $sliceColors[$i]['top']);
            $textColor = imagecolorallocate($img, 30, 41, 59);
            $text = $label . ' (' . number_format($pct, 1) . '%)';
            $labelLen = function_exists('mb_strlen') ? mb_strlen($label) : strlen($label);
            $labelFontSize = $fontSize;
            if ($labelLen >= 10) {
                $labelFontSize = max(14, $fontSize - 2);
            }
            if ($labelLen >= 14) {
                $labelFontSize = max(12, $fontSize - 3);
            }
            if (function_exists('imagettftext') && file_exists($ttfPath)) {
                imagettftext(
                    $img,
                    $labelFontSize,
                    0,
                    $x + $boxSize + 16,
                    $y + (int) ($boxSize * 0.82),
                    $textColor,
                    $ttfPath,
                    $text
                );
            } else {
                imagestring($img, 5, $x + $boxSize + 16, $y + 12, $text, $textColor);
            }
        }

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode($png ?: '');
    }

    private function renderKmRadarBase64(array $series): string
    {
        if (empty($series)) {
            $series = [
                ['label' => 'Sin datos', 'valor' => 0],
            ];
        }

        $values = array_map(fn ($row) => (float) ($row['valor'] ?? 0), $series);
        $labels = array_map(fn ($row) => (string) ($row['label'] ?? ''), $series);
        $max = max(1.0, (float) max($values));
        $count = max(1, count($values));

        $w = 920;
        $h = 620;
        $img = imagecreatetruecolor($w, $h);
        imagealphablending($img, true);
        imagesavealpha($img, true);
        $bg = imagecolorallocate($img, 255, 255, 255);
        imagefilledrectangle($img, 0, 0, $w, $h, $bg);

        $cx = 300;
        $cy = 300;
        $maxR = 210;

        $grid = imagecolorallocate($img, 214, 220, 228);
        $axis = imagecolorallocate($img, 190, 199, 210);
        $line = imagecolorallocate($img, 72, 148, 211);
        $fill = imagecolorallocatealpha($img, 72, 148, 211, 88);
        $point = imagecolorallocate($img, 56, 121, 176);
        $text = imagecolorallocate($img, 51, 65, 85);

        for ($i = 1; $i <= 5; $i++) {
            $r = (int) (($maxR / 5) * $i);
            imageellipse($img, $cx, $cy, $r * 2, $r * 2, $grid);
        }

        $poly = [];
        for ($i = 0; $i < $count; $i++) {
            $angle = -M_PI_2 + (2 * M_PI * $i / $count);
            $xOuter = (int) round($cx + cos($angle) * $maxR);
            $yOuter = (int) round($cy + sin($angle) * $maxR);
            imageline($img, $cx, $cy, $xOuter, $yOuter, $axis);

            $rData = (int) round(($values[$i] / $max) * $maxR);
            $x = (int) round($cx + cos($angle) * $rData);
            $y = (int) round($cy + sin($angle) * $rData);
            $poly[] = $x;
            $poly[] = $y;
        }

        if (count($poly) >= 6) {
            imagefilledpolygon($img, $poly, count($poly) / 2, $fill);
            imagepolygon($img, $poly, count($poly) / 2, $line);
        }

        for ($i = 0; $i < $count; $i++) {
            $angle = -M_PI_2 + (2 * M_PI * $i / $count);
            $rData = (int) round(($values[$i] / $max) * $maxR);
            $x = (int) round($cx + cos($angle) * $rData);
            $y = (int) round($cy + sin($angle) * $rData);
            imagefilledellipse($img, $x, $y, 10, 10, $point);

            $lx = (int) round($cx + cos($angle) * ($maxR + 26));
            $ly = (int) round($cy + sin($angle) * ($maxR + 26));
            imagestring($img, 4, $lx - 18, $ly - 7, $labels[$i], $text);
        }

        imagestring($img, 5, 610, 95, 'Km Recorridos', $text);
        imagestring($img, 4, 610, 126, 'Max: ' . number_format($max, 2) . ' km', $text);
        imagefilledrectangle($img, 610, 165, 642, 177, $line);
        imagestring($img, 4, 652, 161, 'Tendencia del periodo', $text);

        ob_start();
        imagepng($img);
        $png = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode($png ?: '');
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
}
