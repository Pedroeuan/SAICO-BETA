@extends('adminlte::page')

@section('title', 'Panel Vehicular')

@section('content_header')
    <h1>Panel de Control Vehicular</h1>
    @php
        $mesFiltro = request('mes', now()->format('Y-m'));
    @endphp
    <div class="mt-2">
        <div class="btn-group mr-2" role="group" aria-label="Exportar PDF">
            <a class="btn btn-sm btn-danger" href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'semana']) }}">PDF Semana</a>
            <a class="btn btn-sm btn-danger" href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'mes']) }}">PDF Mes</a>
            <a class="btn btn-sm btn-danger" href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'mes_pasado']) }}">PDF Mes Pasado</a>
            <a class="btn btn-sm btn-danger" href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'anio']) }}">PDF Anio</a>
        </div>
        <div class="btn-group" role="group" aria-label="Exportar Excel">
            <a class="btn btn-sm btn-success" href="{{ route('salidas.rendimiento.excel', ['periodo' => 'semana']) }}">Excel Semana</a>
            <a class="btn btn-sm btn-success" href="{{ route('salidas.rendimiento.excel', ['periodo' => 'mes']) }}">Excel Mes</a>
            <a class="btn btn-sm btn-success" href="{{ route('salidas.rendimiento.excel', ['periodo' => 'mes_pasado']) }}">Excel Mes Pasado</a>
            <a class="btn btn-sm btn-success" href="{{ route('salidas.rendimiento.excel', ['periodo' => 'anio']) }}">Excel Anio</a>
        </div>
    </div>
    <div class="mt-2">
        <form class="form-inline" method="GET" action="{{ route('salidas.panel') }}">
            <label class="mr-2 mb-1" for="mesFiltroExport">Filtrar exportacion por mes:</label>
            <input id="mesFiltroExport" type="month" name="mes" class="form-control form-control-sm mr-2 mb-1" value="{{ $mesFiltro }}">
            <button type="submit" class="btn btn-sm btn-secondary mr-2 mb-1">Aplicar</button>
            <a class="btn btn-sm btn-outline-danger mr-2 mb-1" href="{{ route('salidas.rendimiento.pdf', ['periodo' => 'mes']) }}?mes={{ $mesFiltro }}">PDF del mes filtrado</a>
            <a class="btn btn-sm btn-outline-success mb-1" href="{{ route('salidas.rendimiento.excel', ['periodo' => 'mes']) }}?mes={{ $mesFiltro }}">Excel del mes filtrado</a>
        </form>
        <div class="text-muted small mt-1">Para mes pasado: usa los botones "Mes Pasado" o selecciona {{ now()->subMonth()->format('Y-m') }}.</div>
    </div>
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
                    $icono = $variacionMensual >= 0 ? '↑' : '↓';
                    @endphp
                    <span class="badge bg-{{ $colorVariacion }} fs-6">
                        {{ $icono }} {{ $variacionMensual }}%
                    </span>

                </p>

                <p><strong>Tiempo promedio:</strong> {{ round($tiempoPromedioUso ?? 0, 2) }} h</p>
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
                    <h3 class="fw-bold">{{ round($tiempoPromedioUso) }}</h3>
                </div>
            </div>

        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body text-center py-4">
                    <h6>Vehículo Más Usado</h6>
                    <h5 class="fw-bold">
                        {{ $vehiculoMasUsado->vehiculo->placa ?? 'N/A' }}</h5>
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
    
            </div>

            <!-- TAB 5 ALERTAS DE DOCUMENTACIÓN -->
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
                            <table class="table table-sm table-hover table-bordered align-middle text-center">
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

        <!-- PRÓXIMO A VENCER (15 DÍAS) -->
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
                                                $diasPoliza = $vehiculo->poliza_seguro_vencimiento ? \Carbon\Carbon::parse($vehiculo->poliza_seguro_vencimiento)->diffInDays(\Carbon\Carbon::now()) : 999;
                                                $diasTarjeta = $vehiculo->tarjeta_circulacion_vencimiento ? \Carbon\Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->diffInDays(\Carbon\Carbon::now()) : 999;
                                                $diasMinimo = min($diasPoliza, $diasTarjeta);
                                            @endphp
                                            <strong>{{ $diasMinimo }} días</strong>
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

        <!-- SIN DOCUMENTACIÓN -->
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
                            <p class="text-success"><strong>✓ Todos los vehículos tienen documentación completa</strong></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    
</div>
        </div>
    </div>
</div>

@stop


@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

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

});
</script>
@stop

