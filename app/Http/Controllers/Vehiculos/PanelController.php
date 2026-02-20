<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use App\Models\Vehiculos\Checklist\SalidaChecklist;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function index()
    {
        $fechaActual      = Carbon::now();
        $fechaMesAnterior = Carbon::now()->copy()->subMonth();

        // VEHÍCULOS
        $totalVehiculos = Vehiculo::count();
        $disponibles    = Vehiculo::where('estatus', 'disponible')->count();
        $ocupados       = Vehiculo::where('estatus', 'ocupado')->count();
        $inactivos      = Vehiculo::where('estatus', 'inactivo')->count();

        $vencidos    = Vehiculo::where('documentacion_estatus', 'vencida')->count();
        $incompletos = Vehiculo::where('documentacion_estatus', 'incompleta')->count();

        // ALERTAS DE DOCUMENTACIÓN (DASHBOARD)
        $documentosVencidos = Vehiculo::where('documentacion_estatus', 'vencida')->get();
        
        $proximo15dias = Carbon::now()->addDays(15);
        $documentosProximoVencer = Vehiculo::where('documentacion_estatus', 'completa')
            ->where(function($q) use ($proximo15dias) {
                $q->whereBetween('poliza_seguro_vencimiento', [Carbon::now(), $proximo15dias])
                  ->orWhereBetween('tarjeta_circulacion_vencimiento', [Carbon::now(), $proximo15dias]);
            })
            ->get();

        $documentosSinRegistrar = Vehiculo::where('documentacion_estatus', 'incompleta')->get();

        // SALIDAS
        $totalSalidas   = SalidaVehiculo::count();
        $salidasActivas = SalidaVehiculo::where('estatus', 'activo')->count();
        $salidasMes = SalidaVehiculo::whereMonth('fecha_salida', $fechaActual->month)->whereYear('fecha_salida', $fechaActual->year)->count();
        $salidasFinalizadas = SalidaVehiculo::where('estatus', 'finalizado')->count();

        // VEHÍCULO MÁS USADO
        $vehiculoMasUsado = SalidaVehiculo::select('vehiculo_id', DB::raw('count(*) as total'))->groupBy('vehiculo_id')->orderByDesc('total')->with('vehiculo')->first();

        // USUARIOS
        $usuariosActivos = User::where('Estatus', 'Alta')->count();
        $licenciasVencidas = User::whereNotNull('licencia_vencimiento')
            ->whereDate('licencia_vencimiento', '<', $fechaActual->toDateString())
            ->count();

        // SALIDAS POR MES
        $salidasPorMes = SalidaVehiculo::selectRaw('MONTH(fecha_salida) as mes, COUNT(*) as total')->whereYear('fecha_salida', $fechaActual->year)->groupBy('mes')->orderBy('mes')->pluck('total','mes');

        $datosMeses = [];
        for ($i = 1; $i <= 12; $i++) {
            $datosMeses[] = $salidasPorMes[$i] ?? 0;
        }

        // ANÁLISIS COMPARATIVO
        $mesActual = SalidaVehiculo::whereMonth('fecha_salida', $fechaActual->month)->whereYear('fecha_salida', $fechaActual->year)->count();
        $mesAnterior = SalidaVehiculo::whereMonth('fecha_salida', $fechaMesAnterior->month)->whereYear('fecha_salida', $fechaMesAnterior->year)->count();
        $variacionMensual = $mesAnterior > 0 ? round((($mesActual - $mesAnterior) / $mesAnterior) * 100, 2): 0;

        // TIEMPO PROMEDIO
        $tiempoPromedioUso = SalidaVehiculo::whereNotNull('fecha_regreso')->selectRaw('AVG(TIMESTAMPDIFF(HOUR, fecha_salida, fecha_regreso)) as promedio')->value('promedio') ?? 0;

        // TOP 5 VEHÍCULOS
        $topVehiculos = SalidaVehiculo::select('vehiculo_id', DB::raw('count(*) as total'))->groupBy('vehiculo_id')->orderByDesc('total')->with('vehiculo')->take(5)->get();
        $topVehiculosGrafica = SalidaVehiculo::select('vehiculo_id', DB::raw('count(*) as total'))
            ->groupBy('vehiculo_id')
            ->orderByDesc('total')
            ->with('vehiculo')
            ->take(10)
            ->get();

        $labelsVehiculos = $topVehiculosGrafica->map(function ($item) {
            return $item->vehiculo->placa ?? 'N/A';
        })->values();
        $dataVehiculos = $topVehiculosGrafica->pluck('total')->values();

        $topSolicitantes = SalidaVehiculo::select('solicitado_por', DB::raw('count(*) as total'))
            ->groupBy('solicitado_por')
            ->orderByDesc('total')
            ->with('solicitante')
            ->take(5)
            ->get();

        $labelsSolicitantes = $topSolicitantes->map(function ($item) {
            return $item->solicitante->name ?? 'N/A';
        })->values();
        $dataSolicitantes = $topSolicitantes->pluck('total')->values();

        // KPI DISPONIBILIDAD
        $nivelDisponibilidad = $totalVehiculos > 0 ? round(($disponibles / $totalVehiculos) * 100, 2): 0;
        $nivelFinalizadas = $totalSalidas > 0 ? round(($salidasFinalizadas / $totalSalidas) * 100, 2): 0;

        $totalChecklistsSalida = SalidaChecklist::where('tipo', 'salida')->count();
        $checklistsIncompletos = DB::table('salidas_checklists')
            ->where('tipo', 'salida')
            ->where(function ($q) {
                $q->whereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('checklist_documentos')
                        ->whereColumn('checklist_documentos.salida_checklist_id', 'salidas_checklists.id')
                        ->where('estatus', '!=', 'ok');
                })->orWhereExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('checklist_herramientas')
                        ->whereColumn('checklist_herramientas.salida_checklist_id', 'salidas_checklists.id')
                        ->where('disponible', 0);
                });
            })
            ->count();
        $checklistsCompletos = max($totalChecklistsSalida - $checklistsIncompletos, 0);
        $nivelChecklistsCompletos = $totalChecklistsSalida > 0 ? round(($checklistsCompletos / $totalChecklistsSalida) * 100, 2): 0;

        // PROYECCIÓN ANUAL
        $promedioMensual = $fechaActual->month > 0 ? SalidaVehiculo::whereYear('fecha_salida', $fechaActual->year)->count() / $fechaActual->month: 0;
        $proyeccionAnual = round($promedioMensual * 12);

        return view('vehiculos.panel.index', compact(
            'totalVehiculos',
            'disponibles',
            'ocupados',
            'inactivos',
            'vencidos',
            'incompletos',
            'documentosVencidos',
            'documentosProximoVencer',
            'documentosSinRegistrar',
            'totalSalidas',
            'salidasActivas',
            'salidasMes',
            'salidasFinalizadas',
            'vehiculoMasUsado',
            'usuariosActivos',
            'licenciasVencidas',
            'datosMeses',
            'variacionMensual',
            'tiempoPromedioUso',
            'topVehiculos',
            'labelsVehiculos',
            'dataVehiculos',
            'labelsSolicitantes',
            'dataSolicitantes',
            'nivelDisponibilidad',
            'nivelFinalizadas',
            'totalChecklistsSalida',
            'checklistsCompletos',
            'checklistsIncompletos',
            'nivelChecklistsCompletos',
            'proyeccionAnual'
        ));
    }
}
