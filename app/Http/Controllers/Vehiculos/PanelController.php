<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use App\Models\Vehiculos\Checklist\SalidaChecklist;
use App\Models\Notificacion\Notificacion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    public function index()
    {
        $fechaActual      = Carbon::now();
        $fechaMesAnterior = Carbon::now()->copy()->subMonth();
        $hoy = Carbon::today();
        $anioActual = $fechaActual->year;
        $mesActualNum = $fechaActual->month;

        // VEHÍCULOS
        $resumenVehiculos = Vehiculo::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN estatus = 'disponible' THEN 1 ELSE 0 END) as disponibles")
            ->selectRaw("SUM(CASE WHEN estatus = 'ocupado' THEN 1 ELSE 0 END) as ocupados")
            ->selectRaw("SUM(CASE WHEN estatus = 'inactivo' THEN 1 ELSE 0 END) as inactivos")
            ->selectRaw("SUM(CASE WHEN documentacion_estatus = 'vencida' THEN 1 ELSE 0 END) as vencidos")
            ->selectRaw("SUM(CASE WHEN documentacion_estatus = 'incompleta' THEN 1 ELSE 0 END) as incompletos")
            ->first();

        $totalVehiculos = (int) ($resumenVehiculos->total ?? 0);
        $disponibles = (int) ($resumenVehiculos->disponibles ?? 0);
        $ocupados = (int) ($resumenVehiculos->ocupados ?? 0);
        $inactivos = (int) ($resumenVehiculos->inactivos ?? 0);
        $vencidos = (int) ($resumenVehiculos->vencidos ?? 0);
        $incompletos = (int) ($resumenVehiculos->incompletos ?? 0);

        // ALERTAS DE DOCUMENTACIÓN (DASHBOARD)
        $documentosVencidos = Vehiculo::query()
            ->select(['id', 'placa', 'marca', 'poliza_seguro_vencimiento', 'tarjeta_circulacion_vencimiento'])
            ->where('documentacion_estatus', 'vencida')
            ->get();
        
        $proximo15dias = Carbon::today()->addDays(15);
        $documentosProximoVencer = Vehiculo::query()
            ->select(['id', 'placa', 'marca', 'poliza_seguro_vencimiento', 'tarjeta_circulacion_vencimiento'])
            ->where('documentacion_estatus', 'completa')
            ->where(function($q) use ($hoy, $proximo15dias) {
                $q->whereBetween('poliza_seguro_vencimiento', [$hoy->toDateString(), $proximo15dias->toDateString()])
                  ->orWhereBetween('tarjeta_circulacion_vencimiento', [$hoy->toDateString(), $proximo15dias->toDateString()]);
            })
            ->get();

        $documentosSinRegistrar = Vehiculo::query()
            ->select(['id', 'placa', 'marca'])
            ->where('documentacion_estatus', 'incompleta')
            ->get();

        // Notificaciones vehiculares (aditivo): próximas a vencer y vencidas.
        $this->crearNotificacionesVehiculares($documentosProximoVencer, $documentosVencidos);

        // SALIDAS
        $resumenSalidas = SalidaVehiculo::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN estatus = 'activo' THEN 1 ELSE 0 END) as activas")
            ->selectRaw("SUM(CASE WHEN estatus = 'finalizado' THEN 1 ELSE 0 END) as finalizadas")
            ->first();
        $totalSalidas   = (int) ($resumenSalidas->total ?? 0);
        $salidasActivas = (int) ($resumenSalidas->activas ?? 0);
        $salidasFinalizadas = (int) ($resumenSalidas->finalizadas ?? 0);
        $salidasMes = SalidaVehiculo::whereMonth('fecha_salida', $mesActualNum)->whereYear('fecha_salida', $anioActual)->count();

        // VEHÍCULO MÁS USADO
        $vehiculoMasUsado = SalidaVehiculo::select('vehiculo_id', DB::raw('count(*) as total'))->groupBy('vehiculo_id')->orderByDesc('total')->with('vehiculo')->first();

        // USUARIOS
        $usuariosActivos = User::where('Estatus', 'Alta')->count();
        $licenciasVencidas = User::whereNotNull('licencia_vencimiento')
            ->whereDate('licencia_vencimiento', '<', $fechaActual->toDateString())
            ->count();

        // SALIDAS POR MES
        $salidasPorMes = SalidaVehiculo::selectRaw('MONTH(fecha_salida) as mes, COUNT(*) as total')
            ->whereYear('fecha_salida', $anioActual)
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        $datosMeses = [];
        for ($i = 1; $i <= 12; $i++) {
            $datosMeses[] = $salidasPorMes[$i] ?? 0;
        }

        // ANÁLISIS COMPARATIVO
        $mesActual = SalidaVehiculo::whereMonth('fecha_salida', $mesActualNum)->whereYear('fecha_salida', $anioActual)->count();
        $mesAnterior = SalidaVehiculo::whereMonth('fecha_salida', $fechaMesAnterior->month)->whereYear('fecha_salida', $fechaMesAnterior->year)->count();
        $variacionMensual = $mesAnterior > 0 ? round((($mesActual - $mesAnterior) / $mesAnterior) * 100, 2): 0;

        // TIEMPO PROMEDIO (MINUTOS)
        $tiempoPromedioUso = SalidaVehiculo::whereNotNull('fecha_regreso')
            ->selectRaw('AVG(COALESCE(duracion_minutos, TIMESTAMPDIFF(MINUTE, fecha_salida, fecha_regreso))) as promedio')
            ->value('promedio') ?? 0;

        // TOP 5 VEHÍCULOS
        $topVehiculosGrafica = SalidaVehiculo::select('vehiculo_id', DB::raw('count(*) as total'))
            ->groupBy('vehiculo_id')
            ->orderByDesc('total')
            ->with('vehiculo')
            ->take(10)
            ->get();
        $topVehiculos = $topVehiculosGrafica->take(5);

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

        // KM RECORRIDOS POR MES (AÑO ACTUAL): ENTRADA - SALIDA
        $kmPorMes = DB::table('salidas_vehiculos as sv')
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
            ->whereYear('sv.fecha_salida', $anioActual)
            ->selectRaw('MONTH(sv.fecha_salida) as mes, SUM(GREATEST(cce.kilometraje - ccs.kilometraje, 0)) as total_km')
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total_km', 'mes');

        $datosKmMeses = [];
        for ($i = 1; $i <= 12; $i++) {
            $datosKmMeses[] = round((float) ($kmPorMes[$i] ?? 0), 2);
        }

        $kmRecorridosMes = DB::table('salidas_vehiculos as sv')
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
            ->whereMonth('sv.fecha_salida', $mesActualNum)
            ->whereYear('sv.fecha_salida', $anioActual)
            ->selectRaw('SUM(GREATEST(cce.kilometraje - ccs.kilometraje, 0)) as total_km')
            ->value('total_km') ?? 0;

        $kmRecorridosAnio = DB::table('salidas_vehiculos as sv')
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
            ->whereYear('sv.fecha_salida', $anioActual)
            ->selectRaw('SUM(GREATEST(cce.kilometraje - ccs.kilometraje, 0)) as total_km')
            ->value('total_km') ?? 0;

        $promedioKmMensual = $fechaActual->month > 0
            ? round(((float) $kmRecorridosAnio) / $fechaActual->month, 2)
            : 0;

        // INCIDENCIAS: salida con al menos una observacion en checklist de salida/entrada
        $incidenciasPorVehiculo = DB::table('salidas_vehiculos as sv')
            ->join('vehiculos as v', 'v.id', '=', 'sv.vehiculo_id')
            ->leftJoin('salidas_checklists as sc', 'sc.salida_vehiculo_id', '=', 'sv.id')
            ->leftJoin('checklist_condiciones as cc', 'cc.salida_checklist_id', '=', 'sc.id')
            ->select(
                'sv.vehiculo_id',
                'v.placa',
                DB::raw("COUNT(DISTINCT CASE WHEN cc.observaciones IS NOT NULL AND TRIM(cc.observaciones) <> '' THEN sv.id END) as incidencias")
            )
            ->groupBy('sv.vehiculo_id', 'v.placa')
            ->orderByDesc('incidencias')
            ->take(10)
            ->get();

        $vehiculoMasIncidencias = $incidenciasPorVehiculo->first(function ($item) {
            return (int) $item->incidencias > 0;
        });

        $incidenciasMes = DB::table('salidas_vehiculos as sv')
            ->leftJoin('salidas_checklists as sc', 'sc.salida_vehiculo_id', '=', 'sv.id')
            ->leftJoin('checklist_condiciones as cc', 'cc.salida_checklist_id', '=', 'sc.id')
            ->whereMonth('sv.fecha_salida', $mesActualNum)
            ->whereYear('sv.fecha_salida', $anioActual)
            ->whereRaw("cc.observaciones IS NOT NULL AND TRIM(cc.observaciones) <> ''")
            ->selectRaw('COUNT(DISTINCT sv.id) as total')
            ->value('total') ?? 0;

        $labelsIncidencias = $incidenciasPorVehiculo->map(function ($item) {
            return $item->placa ?? 'N/A';
        })->values();
        $dataIncidencias = $incidenciasPorVehiculo->pluck('incidencias')->map(function ($value) {
            return (int) $value;
        })->values();

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
        $promedioMensual = $mesActualNum > 0
            ? SalidaVehiculo::whereYear('fecha_salida', $anioActual)->count() / $mesActualNum
            : 0;
        $proyeccionAnual = round($promedioMensual * 12);

        // COMPORTAMIENTO Y SEGMENTACION (por rol)
        $inicioMesAnalitica = Carbon::now()->startOfMonth();
        $filtroEventosMes = DB::table('salidas_eventos_flujo')
            ->where('salidas_eventos_flujo.created_at', '>=', $inicioMesAnalitica);

        $inicioFormCount = (clone $filtroEventosMes)->where('evento', 'inicio_form_salida')->count();
        $salidaCreadaCount = (clone $filtroEventosMes)->where('evento', 'salida_creada')->count();
        $checklistSalidaCount = (clone $filtroEventosMes)->where('evento', 'checklist_salida_creado')->count();
        $checklistEntradaCount = (clone $filtroEventosMes)->where('evento', 'checklist_entrada_creado')->count();

        $conversionInicioASalida = $inicioFormCount > 0
            ? round(($salidaCreadaCount / $inicioFormCount) * 100, 2)
            : 0;
        $conversionSalidaAChecklistSalida = $salidaCreadaCount > 0
            ? round(($checklistSalidaCount / $salidaCreadaCount) * 100, 2)
            : 0;
        $conversionChecklistSalidaAEntrada = $checklistSalidaCount > 0
            ? round(($checklistEntradaCount / $checklistSalidaCount) * 100, 2)
            : 0;

        $demandaPorRol = (clone $filtroEventosMes)
            ->where('evento', 'salida_creada')
            ->selectRaw("COALESCE(NULLIF(TRIM(rol), ''), 'Sin rol') as rol, COUNT(*) as total")
            ->groupBy('rol')
            ->orderByDesc('total')
            ->get();

        $fidelidadUsuarios = (clone $filtroEventosMes)
            ->leftJoin('users as u', 'u.id', '=', 'salidas_eventos_flujo.user_id')
            ->whereIn('salidas_eventos_flujo.evento', [
                'salida_creada',
                'checklist_salida_creado',
                'checklist_entrada_creado',
                'salida_finalizada',
            ])
            ->selectRaw("COALESCE(NULLIF(TRIM(u.name), ''), 'Usuario N/A') as usuario")
            ->selectRaw("COALESCE(NULLIF(TRIM(salidas_eventos_flujo.rol), ''), 'Sin rol') as rol")
            ->selectRaw("COUNT(*) as total_eventos")
            ->groupBy('usuario', 'rol')
            ->orderByDesc('total_eventos')
            ->limit(10)
            ->get();

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
            'datosKmMeses',
            'kmRecorridosMes',
            'promedioKmMensual',
            'vehiculoMasIncidencias',
            'incidenciasMes',
            'labelsIncidencias',
            'dataIncidencias',
            'nivelDisponibilidad',
            'nivelFinalizadas',
            'totalChecklistsSalida',
            'checklistsCompletos',
            'checklistsIncompletos',
            'nivelChecklistsCompletos',
            'proyeccionAnual',
            'inicioFormCount',
            'salidaCreadaCount',
            'checklistSalidaCount',
            'checklistEntradaCount',
            'conversionInicioASalida',
            'conversionSalidaAChecklistSalida',
            'conversionChecklistSalidaAEntrada',
            'demandaPorRol',
            'fidelidadUsuarios'
        ));
    }

    private function crearNotificacionesVehiculares($documentosProximoVencer, $documentosVencidos): void
    {
        $userId = Auth::id();
        if (!$userId) {
            return;
        }

        $hoy = Carbon::today();

        foreach ($documentosProximoVencer as $vehiculo) {
            $diasPoliza = $vehiculo->poliza_seguro_vencimiento
                ? $hoy->diffInDays(Carbon::parse($vehiculo->poliza_seguro_vencimiento)->startOfDay(), false)
                : null;
            $diasTarjeta = $vehiculo->tarjeta_circulacion_vencimiento
                ? $hoy->diffInDays(Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->startOfDay(), false)
                : null;
            $diasValidos = collect([$diasPoliza, $diasTarjeta])->filter(fn($d) => !is_null($d) && $d >= 0);
            $diasMinimo = $diasValidos->isNotEmpty() ? (int) $diasValidos->min() : null;

            if (is_null($diasMinimo)) {
                continue;
            }

            $mensajeCorto = $diasMinimo === 0
                ? "Vehículo {$vehiculo->placa} vence hoy"
                : "Vehículo {$vehiculo->placa} vence en {$diasMinimo} días";
            $mensajeLargo = "La documentación del vehículo {$vehiculo->placa} ({$vehiculo->marca}) está próxima a vencer.";

            $existe = Notificacion::where('users_id', $userId)
                ->where('Mensaje_Corto', $mensajeCorto)
                ->where('Mensaje_Largo', $mensajeLargo)
                ->first();

            if (!$existe) {
                Notificacion::create([
                    'users_id' => $userId,
                    'Mensaje_Corto' => $mensajeCorto,
                    'Mensaje_Largo' => $mensajeLargo,
                    'url' => url("/vehiculos/edit/{$vehiculo->id}"),
                    'leida' => 0,
                ]);
            }
        }

        foreach ($documentosVencidos as $vehiculo) {
            $mensajeCorto = "Vehículo {$vehiculo->placa} con doc. vencida";
            $mensajeLargo = "La documentación del vehículo {$vehiculo->placa} ({$vehiculo->marca}) está vencida.";

            $existe = Notificacion::where('users_id', $userId)
                ->where('Mensaje_Corto', $mensajeCorto)
                ->where('Mensaje_Largo', $mensajeLargo)
                ->first();

            if (!$existe) {
                Notificacion::create([
                    'users_id' => $userId,
                    'Mensaje_Corto' => $mensajeCorto,
                    'Mensaje_Largo' => $mensajeLargo,
                    'url' => url("/vehiculos/edit/{$vehiculo->id}"),
                    'leida' => 0,
                ]);
            }
        }
    }
}
