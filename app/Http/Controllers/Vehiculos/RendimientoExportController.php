<?php

namespace App\Http\Controllers\Vehiculos;

use App\Exports\VehiculosRendimientoExport;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaVehiculo;
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

        $data = $this->buildData($inicio, $fin);
        $data['periodo'] = $labelPeriodo;
        $data['inicio'] = $inicio;
        $data['fin'] = $fin;

        return Pdf::loadView('vehiculos.reportes.rendimiento_pdf', $data)
            ->setPaper('letter', 'portrait')
            ->download("rendimiento_vehiculos_{$periodo}.pdf");
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
                $label = 'Año';
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

    private function buildData(Carbon $inicio, Carbon $fin): array
    {
        $salidas = SalidaVehiculo::whereBetween('fecha_salida', [$inicio, $fin]);

        $totalSalidas = (clone $salidas)->count();
        $salidasActivas = (clone $salidas)->where('estatus', 'activo')->count();
        $salidasFinalizadas = (clone $salidas)->where('estatus', 'finalizado')->count();
        $tiempoPromedio = (clone $salidas)->avg('duracion_minutos') ?? 0;

        $porVehiculo = (clone $salidas)
            ->select(
                'vehiculo_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(duracion_minutos) as total_minutos'),
                DB::raw('AVG(duracion_minutos) as promedio_minutos'),
                DB::raw('MAX(fecha_salida) as ultima_salida')
            )
            ->groupBy('vehiculo_id')
            ->orderByDesc('total')
            ->with('vehiculo')
            ->get();

        $maxTotalVehiculo = max((int) ($porVehiculo->max('total') ?? 0), 1);
        $estatusChart = [
            ['label' => 'Activas', 'valor' => (int) $salidasActivas, 'color' => '#f59e0b'],
            ['label' => 'Finalizadas', 'valor' => (int) $salidasFinalizadas, 'color' => '#10b981'],
        ];
        $maxEstatus = max((int) $salidasActivas, (int) $salidasFinalizadas, 1);

        return compact(
            'totalSalidas',
            'salidasActivas',
            'salidasFinalizadas',
            'tiempoPromedio',
            'porVehiculo',
            'maxTotalVehiculo',
            'estatusChart',
            'maxEstatus'
        );
    }
}
