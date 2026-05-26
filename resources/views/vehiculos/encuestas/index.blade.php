@extends('adminlte::page')

@section('title', 'Analitica de satisfaccion vehicular')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4>Analitica de reputacion interna vehicular</h4>
            <p class="text-muted mb-0">Encuestas integradas, NPS interno y sentimiento del servicio prestado.</p>
        </div>
        <a href="{{ route('vehiculos.index', ['tab' => 'movimientos']) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver a vehiculos
        </a>
    </div>

    <form method="GET" action="{{ route('vehiculos.encuestas.index') }}" class="mb-3">
        <div class="form-row align-items-end">
            <div class="col-md-3">
                <label for="mes">Mes</label>
                <select name="mes" id="mes" class="form-control">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ (int) $mesSeleccionado === $i ? 'selected' : '' }}>
                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label for="anio">Anio</label>
                <input type="number" name="anio" id="anio" min="2000" max="{{ now()->year + 1 }}" value="{{ $anioSeleccionado }}" class="form-control">
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Consultar
                </button>
            </div>
        </div>
    </form>

    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Encuestas</small>
                    <h4 class="mb-1">{{ $resumen['total_encuestas'] }}</h4>
                    <small class="text-muted">Total respondidas en el periodo.</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Satisfaccion promedio</small>
                    <h4 class="mb-1">{{ number_format($resumen['promedio_satisfaccion'], 2) }}/5</h4>
                    <small class="text-muted">Servicio, estado de unidad y tiempo.</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">NPS interno</small>
                    <h4 class="mb-1">{{ number_format($resumen['indice_nps'], 2) }}</h4>
                    <small class="text-muted">Promotores menos detractores.</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <small class="text-muted d-block">Sentimiento dominante</small>
                    <h4 class="mb-1">
                        @php
                            $dominante = collect([
                                'positivo' => $resumen['positivas'],
                                'neutro' => $resumen['neutras'],
                                'negativo' => $resumen['negativas'],
                            ])->sortDesc()->keys()->first();
                        @endphp
                        {{ ucfirst($dominante ?? 'neutro') }}
                    </h4>
                    <small class="text-muted">Clasificacion automatica inicial.</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header"><strong>Distribucion de sentimiento</strong></div>
                <div class="card-body">
                    <p class="mb-2">Positivas: <strong>{{ $resumen['positivas'] }}</strong></p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-success" style="width: {{ $resumen['total_encuestas'] > 0 ? ($resumen['positivas'] * 100 / $resumen['total_encuestas']) : 0 }}%"></div>
                    </div>
                    <p class="mb-2">Neutras: <strong>{{ $resumen['neutras'] }}</strong></p>
                    <div class="progress mb-3">
                        <div class="progress-bar bg-info" style="width: {{ $resumen['total_encuestas'] > 0 ? ($resumen['neutras'] * 100 / $resumen['total_encuestas']) : 0 }}%"></div>
                    </div>
                    <p class="mb-2">Negativas: <strong>{{ $resumen['negativas'] }}</strong></p>
                    <div class="progress">
                        <div class="progress-bar bg-danger" style="width: {{ $resumen['total_encuestas'] > 0 ? ($resumen['negativas'] * 100 / $resumen['total_encuestas']) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header"><strong>Top vehiculos con mas feedback</strong></div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Vehiculo</th>
                                <th class="text-center">Encuestas</th>
                                <th class="text-right">Promedio</th>
                                <th class="text-right">NPS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topVehiculos as $item)
                                <tr>
                                    <td>{{ $item->vehiculo->placa ?? 'Sin placa' }}<br><small class="text-muted">{{ $item->vehiculo->marca ?? '' }} {{ $item->vehiculo->modelo ?? '' }}</small></td>
                                    <td class="text-center">{{ $item->encuestas }}</td>
                                    <td class="text-right">{{ number_format($item->promedio, 2) }}/5</td>
                                    <td class="text-right">{{ number_format($item->nps_promedio, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3">Sin datos en el periodo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header"><strong>Detalle de respuestas</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Vehiculo</th>
                            <th>Usuario</th>
                            <th>Origen</th>
                            <th class="text-center">Promedio</th>
                            <th class="text-center">NPS</th>
                            <th>Sentimiento</th>
                            <th>Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($encuestas as $encuesta)
                            <tr>
                                <td>{{ optional($encuesta->respondida_en)->format('d/m/Y H:i') ?? optional($encuesta->fecha_encuesta)->format('d/m/Y') }}</td>
                                <td>{{ $encuesta->vehiculo->placa ?? 'Sin placa' }}</td>
                                <td>{{ $encuesta->usuario->name ?? 'N/A' }}</td>
                                <td>{{ ucfirst($encuesta->origen_respuesta) }}</td>
                                <td class="text-center">{{ number_format($encuesta->promedio_general, 2) }}/5</td>
                                <td class="text-center">{{ $encuesta->nps }}</td>
                                <td>
                                    <span class="badge {{ $encuesta->sentimiento === 'positivo' ? 'bg-success' : ($encuesta->sentimiento === 'negativo' ? 'bg-danger' : 'bg-info') }}">
                                        {{ ucfirst($encuesta->sentimiento) }}
                                    </span>
                                </td>
                                <td>{{ $encuesta->comentario ?: 'Sin comentario' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-3">Sin encuestas registradas en el periodo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(method_exists($encuestas, 'links'))
        <div class="mt-3">
            {{ $encuestas->links() }}
        </div>
    @endif
</div>
@endsection
