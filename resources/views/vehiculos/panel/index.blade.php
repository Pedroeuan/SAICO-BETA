@extends('adminlte::page')

@section('title', 'Panel Vehicular')

@section('css')
<style>
    #my-notification .dropdown-menu {
        max-height: 320px;
        width: 360px;
        max-width: 90vw;
        overflow-y: auto;
    }

    #my-notification .dropdown-item {
        white-space: normal;
        word-break: break-word;
    }
</style>
@endsection

@section('content_header')
    <h1>Panel de Control Vehicular</h1>
    @php
        $mesFiltro = request('mes', now()->format('Y-m'));
    @endphp
   
    @if($vencidos > 0)
    <div class="alert alert-danger">
         Hay {{ $vencidos }} vehiculos con documentacion vencida.
    </div>
    @endif
@stop
<br>
<br>
<br>
@section('content')

<div class="card">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="panelTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="resumen-tab" data-toggle="tab" href="#resumen" role="tab">
                     Resumen
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="graficas-tab" data-toggle="tab" href="#graficas" role="tab">
                     Gráficas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ranking-tab" data-toggle="tab" href="#ranking" role="tab">
                     Ranking
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="indicadores-tab" data-toggle="tab" href="#indicadores" role="tab" aria-controls="indicadores" aria-selected="false">
                     Indicadores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="alertas-tab" data-toggle="tab" href="#alertas" role="tab" aria-controls="alertas" aria-selected="false">
                     Alertas de Documentación
                </a>
            </li>
            <li class="nav-item">
            <a class="nav-link" id="exportaciones-tab" data-toggle="tab"
            href="#exportaciones" role="tab"
            aria-controls="exportaciones" aria-selected="false">
                Exportaciones
            </a>
        </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">

            <!-- TAB 1 RESUMEN -->
            <div class="tab-pane fade show active" id="resumen" role="tabpanel">

                <div class="row">
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $totalVehiculos }}</h3>
                                <p>Total Vehículos</p>
                            </div>
                            <div class="icon"><i class="fas fa-car"></i></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $disponibles }}</h3>
                                <p>Disponibles</p>
                            </div>
                            <div class="icon"><i class="fas fa-check"></i></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $vencidos }}</h3>
                                <p>Doc. Vencida</p>
                            </div>
                            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $licenciasVencidas }}</h3>
                                <p>Licencias Vencidas</p>
                            </div>
                            <div class="icon"><i class="fas fa-id-card"></i></div>
                        </div>
                    </div>
                </div>

                <hr>

                <p><strong>Total Salidas:</strong> {{ $totalSalidas }}</p>
                <p><strong>Salidas Activas:</strong> {{ $salidasActivas }}</p>
                <p><strong>Salidas del Mes:</strong> {{ $salidasMes }}</p>

                <p>
                    <strong>Variación mensual:</strong>
                    @php
                    $colorVariacion = $variacionMensual >= 0 ? 'success' : 'danger';
                    $icono = $variacionMensual >= 0 ? '+' : '-';
                    @endphp
                    <span class="badge bg-{{ $colorVariacion }} fs-6">
                        {{ $icono }} {{ $variacionMensual }}%
                    </span>

                </p>

                <p><strong>Tiempo promedio:</strong> {{ round($tiempoPromedioUso ?? 0, 2) }} min</p>
                <p><strong>Proyección anual:</strong> {{ $proyeccionAnual }}</p>

            </div>

            <!-- TAB 2 GRAFICAS -->
            <div class="tab-pane fade" id="graficas" role="tabpanel">

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-center">Salidas por Mes</h6>
                        <canvas id="graficaSalidasMes"></canvas>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-center">Salidas por Vehículo (Top 10)</h6>
                        <canvas id="graficaSalidasVehiculo"></canvas>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4">
                        <h6 class="text-center">Disponibles vs Ocupados vs Inactivos</h6>
                        <canvas id="graficaEstados"></canvas>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-center">Top 5 Usuarios Solicitantes</h6>
                        <canvas id="graficaSolicitantes"></canvas>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-center">Checklists Completos vs Incompletos</h6>
                        <canvas id="graficaChecklists"></canvas>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="text-center">Km Recorridos por Mes</h6>
                        <canvas id="graficaKmMes"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-center">Incidencias por Vehiculo</h6>
                        <canvas id="graficaIncidenciasVehiculo"></canvas>
                    </div>
                </div>

            </div>

            <!-- TAB 3 RANKING -->
            <div class="tab-pane fade" id="ranking" role="tabpanel">

                @if($vehiculoMasUsado)
                    <h4>
                        Vehículo más usado:
                        {{ $vehiculoMasUsado->vehiculo->placa }}
                        ({{ $vehiculoMasUsado->total }} salidas)
                    </h4>
                @endif

                <table class="table table-sm table-hover table-bordered align-middle text-center mt-3">

                    <thead>
                        <tr>
                            <th>Vehículo</th>
                            <th>Total Salidas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topVehiculos as $item)
                        <tr>
                            <td>{{ $item->vehiculo->placa ?? 'N/A' }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <!-- TAB 4 INDICADORES -->
            <div class="tab-pane fade" id="indicadores" role="tabpanel" aria-labelledby="indicadores-tab">
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h6>Total Salidas</h6>
                    <h3 class="fw-bold">{{ $totalSalidas }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body text-center py-4">
                    <h6>Salidas Activas</h6>
                    <h3 class="fw-bold">{{ $salidasActivas }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body text-center py-4">
                    <h6>Tiempo Promedio (min)</h6>
                    <h3 class="fw-bold">{{ round($tiempoPromedioUso, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body text-center py-4">
                    <h6>Vehiculo Mas Usado</h6>
                    <h5 class="fw-bold">{{ $vehiculoMasUsado->vehiculo->placa ?? 'N/A' }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-info text-white">
                <div class="card-body text-center py-4">
                    <h6>Km Recorridos del Mes</h6>
                    <h3 class="fw-bold">{{ round($kmRecorridosMes ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-secondary text-white">
                <div class="card-body text-center py-4">
                    <h6>Promedio Km Mensual</h6>
                    <h3 class="fw-bold">{{ round($promedioKmMensual ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-dark text-white">
                <div class="card-body text-center py-4">
                    <h6>Incidencias del Mes</h6>
                    <h3 class="fw-bold">{{ $incidenciasMes ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body text-center py-4">
                    <h6>Vehiculo con Mas Incidencias</h6>
                    <h5 class="fw-bold">{{ $vehiculoMasIncidencias->placa ?? 'Sin incidencias' }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <strong>Porcentajes Clave</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-1"><strong>Disponibilidad:</strong> {{ $nivelDisponibilidad }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $nivelDisponibilidad }}%" aria-valuenow="{{ $nivelDisponibilidad }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong>Checklists completos:</strong> {{ $nivelChecklistsCompletos }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $nivelChecklistsCompletos }}%" aria-valuenow="{{ $nivelChecklistsCompletos }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong>Salidas finalizadas:</strong> {{ $nivelFinalizadas }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $nivelFinalizadas }}%" aria-valuenow="{{ $nivelFinalizadas }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <strong>Comportamiento y Segmentacion (Mes Actual)</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p class="mb-1"><strong>Inicio formulario</strong></p>
                    <h4>{{ $inicioFormCount ?? 0 }}</h4>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Salidas creadas</strong></p>
                    <h4>{{ $salidaCreadaCount ?? 0 }}</h4>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Checklist salida</strong></p>
                    <h4>{{ $checklistSalidaCount ?? 0 }}</h4>
                </div>
                <div class="col-md-3">
                    <p class="mb-1"><strong>Checklist entrada</strong></p>
                    <h4>{{ $checklistEntradaCount ?? 0 }}</h4>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4">
                    <p class="mb-1"><strong>Conversion inicio a salida</strong> {{ $conversionInicioASalida ?? 0 }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $conversionInicioASalida ?? 0 }}%"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong>Conversion salida a checklist salida</strong> {{ $conversionSalidaAChecklistSalida ?? 0 }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $conversionSalidaAChecklistSalida ?? 0 }}%"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="mb-1"><strong>Conversion checklist salida a entrada</strong> {{ $conversionChecklistSalidaAEntrada ?? 0 }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $conversionChecklistSalidaAEntrada ?? 0 }}%"></div>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h6>Demanda por Rol (salidas creadas)</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($demandaPorRol ?? collect()) as $item)
                                    <tr>
                                        <td>{{ $item->rol }}</td>
                                        <td class="text-center">{{ $item->total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Sin datos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6>Fidelidad de Uso (Top 10)</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th class="text-center">Eventos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($fidelidadUsuarios ?? collect()) as $item)
                                    <tr>
                                        <td>{{ $item->usuario }}</td>
                                        <td>{{ $item->rol }}</td>
                                        <td class="text-center">{{ $item->total_eventos }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Sin datos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
            </div>

            <!-- TAB 5 ALERTAS DE DOCUMENTACION -->
    <div class="tab-pane fade" id="alertas" role="tabpanel" aria-labelledby="alertas-tab">

        <h4 class="mb-4">Estado de Documentación de Vehículos</h4>

        <!-- VENCIDOS -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <strong>Documentación Vencida ({{ count($documentosVencidos) }})</strong>
                    </div>
                    <div class="card-body">
                        @if($documentosVencidos->count() > 0)
                        <div class="table-responsive">
                            <table id="tablaVehiculos" class="table table-sm table-hover table-bordered align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>Placa</th>
                                        <th>Marca</th>
                                        <th>Póliza Vencimiento</th>
                                        <th>Tarjeta Vencimiento</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentosVencidos as $vehiculo)
                                    <tr>
                                        <td><strong>{{ $vehiculo->placa }}</strong></td>
                                        <td>{{ $vehiculo->marca }}</td>
                                        <td>
                                            @if($vehiculo->poliza_seguro_vencimiento)
                                                <span class="badge bg-danger">
                                                    {{ \Carbon\Carbon::parse($vehiculo->poliza_seguro_vencimiento)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No registrada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($vehiculo->tarjeta_circulacion_vencimiento)
                                                <span class="badge bg-danger">
                                                    {{ \Carbon\Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No registrada</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-sm btn-warning px-3">Actualizar</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>  
                            </table>
                           </div>
                        @else
                            <p class="text-success"><strong>Ningún vehículo con documentación vencida</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- PROXIMO A VENCER (15 DIAS) -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <strong>Documentación Próxima a Vencer (< 15 días) ({{ count($documentosProximoVencer) }})</strong>
                    </div>
                    <div class="card-body">
                        @if($documentosProximoVencer->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered align-middle text-center">
                                    <thead>
                                    <tr>
                                        <th>Placa</th>
                                        <th>Marca</th>
                                        <th>Póliza Vencimiento</th>
                                        <th>Tarjeta Vencimiento</th>
                                        <th>Días Restantes</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentosProximoVencer as $vehiculo)
                                    <tr>
                                        <td><strong>{{ $vehiculo->placa }}</strong></td>
                                        <td>{{ $vehiculo->marca }}</td>
                                        <td>
                                            @if($vehiculo->poliza_seguro_vencimiento)
                                                <span class="badge bg-warning">
                                                    {{ \Carbon\Carbon::parse($vehiculo->poliza_seguro_vencimiento)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No registrada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($vehiculo->tarjeta_circulacion_vencimiento)
                                                <span class="badge bg-warning">
                                                    {{ \Carbon\Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">No registrada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $hoy = \Carbon\Carbon::today();
                                                $diasPoliza = $vehiculo->poliza_seguro_vencimiento
                                                    ? $hoy->diffInDays(\Carbon\Carbon::parse($vehiculo->poliza_seguro_vencimiento)->startOfDay(), false)
                                                    : null;
                                                $diasTarjeta = $vehiculo->tarjeta_circulacion_vencimiento
                                                    ? $hoy->diffInDays(\Carbon\Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->startOfDay(), false)
                                                    : null;
                                                $diasValidos = collect([$diasPoliza, $diasTarjeta])->filter(fn($d) => !is_null($d) && $d >= 0);
                                                $diasMinimo = $diasValidos->isNotEmpty() ? (int) $diasValidos->min() : null;
                                            @endphp
                                            @if(is_null($diasMinimo))
                                                <strong>N/A</strong>
                                            @elseif($diasMinimo === 0)
                                                <strong>Vence hoy</strong>
                                            @else
                                                <strong>{{ $diasMinimo }} días</strong>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-sm btn-warning px-3">Renovar</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p class="text-success"><strong>Ningún vehículo próximo a vencer</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- SIN DOCUMENTACION -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <strong>Documentación Incompleta ({{ count($documentosSinRegistrar) }})</strong>
                    </div>
                    <div class="card-body">
                        @if($documentosSinRegistrar->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered align-middle text-center">
                                    <thead>
                                    <tr>
                                        <th>Placa</th>
                                        <th>Marca</th>
                                        <th>Póliza PDF</th>
                                        <th>Tarjeta PDF</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($documentosSinRegistrar as $vehiculo)
                                    <tr>
                                        <td><strong>{{ $vehiculo->placa }}</strong></td>
                                        <td>{{ $vehiculo->marca }}</td>
                                        <td>
                                            @if($vehiculo->poliza_seguro_pdf)
                                                <span class="badge bg-success">Registrada</span>
                                            @else
                                                <span class="badge bg-danger">Falta</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($vehiculo->tarjeta_circulacion_pdf)
                                                <span class="badge bg-success">Registrada</span>
                                            @else
                                                <span class="badge bg-danger">Falta</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-sm btn-info px-3">Completar</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p class="text-success"><strong>OK Todos los vehiculos tienen documentacion completa</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        

    </div
    <!-- TAB EXPORTACIONES -->
<div class="tab-pane fade" id="exportaciones" role="tabpanel" aria-labelledby="exportaciones-tab">

    <div class="card mt-3 shadow-sm">
        <div class="card-header bg-light">
            <strong>Exportar Reportes</strong>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Periodo</th>
                            <th>PDF</th>
                            <th>Excel</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Semana</strong></td>
                            <td>
                                <a class="btn btn-danger btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'semana']) }}"
                                   target="_blank" rel="noopener">
                                   <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-success btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.excel', ['periodo' => 'semana']) }}">
                                   <i class="fas fa-file-excel"></i> Descargar
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Mes</strong></td>
                            <td>
                                <a class="btn btn-danger btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'mes']) }}"
                                   target="_blank" rel="noopener">
                                   <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-success btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.excel', ['periodo' => 'mes']) }}">
                                   <i class="fas fa-file-excel"></i> Descargar
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Mes Pasado</strong></td>
                            <td>
                                <a class="btn btn-danger btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'mes_pasado']) }}"
                                   target="_blank" rel="noopener">
                                   <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-success btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.excel', ['periodo' => 'mes_pasado']) }}">
                                   <i class="fas fa-file-excel"></i> Descargar
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td><strong>Año</strong></td>
                            <td>
                                <a class="btn btn-danger btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'anio']) }}"
                                   target="_blank" rel="noopener">
                                   <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-success btn-sm w-100"
                                   href="{{ route('salidas.rendimiento.excel', ['periodo' => 'anio']) }}">
                                   <i class="fas fa-file-excel"></i> Descargar
                                </a>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr>

    <!-- EXPORTACIÓN POR MES FILTRADO -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>Exportar por Mes Específico</strong>
        </div>

        <div class="card-body">
            <form class="form-inline" method="GET" action="{{ route('salidas.panel') }}">
                <label class="mr-2">Seleccionar Mes:</label>
                <input type="month" name="mes"
                       class="form-control form-control-sm mr-2"
                       value="{{ $mesFiltro }}">

                <button type="submit" class="btn btn-secondary btn-sm mr-2">
                    Aplicar
                </button>

                <a class="btn btn-outline-danger btn-sm mr-2"
                   href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'mes']) }}?mes={{ $mesFiltro }}"
                   target="_blank" rel="noopener">
                    <i class="fas fa-file-pdf"></i> Ver PDF
                </a>

                <a class="btn btn-outline-success btn-sm"
                   href="{{ route('salidas.rendimiento.excel', ['periodo' => 'mes']) }}?mes={{ $mesFiltro }}">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
            </form>
        </div>
    </div>

</div>
    
</div>
        </div>
    </div>
</div>

@stop


@section('js')
<!-- jQuery (solo si no lo carga AdminLTE) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts personalizados -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const notificationMenu = document.querySelector('#my-notification .dropdown-menu');
    if (!notificationMenu) return;

    function normalizeNotificationMenu() {
        const items = notificationMenu.querySelectorAll('.dropdown-item');
        items.forEach((item) => {
            const text = (item.textContent || '').trim().toLowerCase();
            if (text === 'todas las notificaciones') {
                item.textContent = 'Ver todas las notificaciones';
                item.classList.add('font-weight-bold');
            }
        });
    }

    const observer = new MutationObserver(normalizeNotificationMenu);
    observer.observe(notificationMenu, { childList: true, subtree: true });
    normalizeNotificationMenu();
});

$(document).ready(function() {
    if ($('#tablaVehiculos').length) {
        $('#tablaVehiculos').DataTable({
            language: {
                decimal: "",
                emptyTable: "No hay datos disponibles en la tabla",
                info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                infoEmpty: "Mostrando 0 a 0 de 0 entradas",
                infoFiltered: "(filtrado de _MAX_ entradas totales)",
                thousands: ",",
                lengthMenu: "Mostrar _MENU_ entradas",
                loadingRecords: "Cargando...",
                processing: "Procesando...",
                search: "Buscar:",
                zeroRecords: "No se encontraron registros coincidentes",
                paginate: {
                    first: "Primero",
                    last: "Ultimo",
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive: true,
            autoWidth: false
        });
    }
});

   document.addEventListener("DOMContentLoaded", function() {
    // GRAFICA DE LINEA - SALIDAS POR MES
    const ctxMes = document.getElementById('graficaSalidasMes');

    if (ctxMes) {
        new Chart(ctxMes, {
            type: 'line',
            data: {
                labels: [
                    'Ene','Feb','Mar','Abr','May','Jun',
                    'Jul','Ago','Sep','Oct','Nov','Dic'
                ],
                datasets: [{
                    label: 'Salidas',
                    data: @json($datosMeses ?? []),
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // GRAFICA DE BARRAS - SALIDAS POR VEHICULO
    const ctxVehiculo = document.getElementById('graficaSalidasVehiculo');
    if (ctxVehiculo) {
        new Chart(ctxVehiculo, {
            type: 'bar',
            data: {
                labels: @json($labelsVehiculos ?? []),
                datasets: [{
                    label: 'Salidas por vehiculo',
                    data: @json($dataVehiculos ?? []),
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
    // GRÁFICA CIRCULAR
    const ctxEstados = document.getElementById('graficaEstados');

    if (ctxEstados) {
        new Chart(ctxEstados, {
            type: 'doughnut',
            data: {
                labels: ['Disponibles', 'Ocupados', 'Inactivos'],
                datasets: [{
                    data: [
                        {{ $disponibles }},
                        {{ $ocupados }},
                        {{ $inactivos }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // GRAFICA DE BARRAS - TOP SOLICITANTES
    const ctxSolicitantes = document.getElementById('graficaSolicitantes');
    if (ctxSolicitantes) {
        new Chart(ctxSolicitantes, {
            type: 'bar',
            data: {
                labels: @json($labelsSolicitantes ?? []),
                datasets: [{
                    label: 'Solicitudes',
                    data: @json($dataSolicitantes ?? []),
                    backgroundColor: '#17a2b8'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // GRAFICA CIRCULAR - CHECKLISTS
    const ctxChecklists = document.getElementById('graficaChecklists');
    if (ctxChecklists) {
        new Chart(ctxChecklists, {
            type: 'doughnut',
            data: {
                labels: ['Completos', 'Incompletos'],
                datasets: [{
                    data: [
                        {{ $checklistsCompletos }},
                        {{ $checklistsIncompletos }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    // GRAFICA DE LINEA - KM POR MES
    const ctxKmMes = document.getElementById('graficaKmMes');
    if (ctxKmMes) {
        new Chart(ctxKmMes, {
            type: 'line',
            data: {
                labels: [
                    'Ene','Feb','Mar','Abr','May','Jun',
                    'Jul','Ago','Sep','Oct','Nov','Dic'
                ],
                datasets: [{
                    label: 'Kilometros',
                    data: @json($datosKmMeses ?? []),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // GRAFICA DE BARRAS - INCIDENCIAS POR VEHICULO
    const ctxIncidenciasVehiculo = document.getElementById('graficaIncidenciasVehiculo');
    if (ctxIncidenciasVehiculo) {
        new Chart(ctxIncidenciasVehiculo, {
            type: 'bar',
            data: {
                labels: @json($labelsIncidencias ?? []),
                datasets: [{
                    label: 'Incidencias',
                    data: @json($dataIncidencias ?? []),
                    backgroundColor: '#6c757d'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }

});
</script>
@stop
