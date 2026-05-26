@extends('adminlte::page')

@section('title', 'Llantas del Vehiculo')

@section('content')
<br>
<br>
<br>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Llantas - {{ $vehiculo->placa }}</h4>
            <small class="text-muted">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</small>
        </div>
        <div>
            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> Volver al vehiculo
            </a>
            <a href="{{ route('vehiculos.llantas.create', $vehiculo->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle"></i> Nueva llanta
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ (int) ($resumen->activas ?? 0) }}</h3>
                    <p>Llantas activas</p>
                </div>
                <div class="icon"><i class="fas fa-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ (int) ($resumen->rotadas ?? 0) }}</h3>
                    <p>Llantas rotadas</p>
                </div>
                <div class="icon"><i class="fas fa-sync"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ (int) ($resumen->bajas ?? 0) }}</h3>
                    <p>Llantas dadas de baja</p>
                </div>
                <div class="icon"><i class="fas fa-times-circle"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>${{ number_format((float) ($resumen->costo_total ?? 0), 2) }}</h3>
                    <p>Costo historico</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Historial tecnico</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Posicion</th>
                            <th>Marca / modelo</th>
                            <th>Medida</th>
                            <th>Instalacion</th>
                            <th>KM instalacion</th>
                            <th>Baja</th>
                            <th>KM baja</th>
                            <th>KM recorridos</th>
                            <th>Costo</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($llantas as $llanta)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $llanta->posicion)) }}</td>
                                <td>
                                    <strong>{{ $llanta->marca }}</strong><br>
                                    <small class="text-muted">{{ $llanta->modelo ?: 'Sin modelo' }}</small>
                                </td>
                                <td>{{ $llanta->medida ?: 'N/A' }}</td>
                                <td>{{ optional($llanta->fecha_instalacion)->format('Y-m-d') }}</td>
                                <td>{{ number_format($llanta->kilometraje_instalacion) }}</td>
                                <td>{{ optional($llanta->fecha_baja)->format('Y-m-d') ?: 'N/A' }}</td>
                                <td>{{ !is_null($llanta->kilometraje_baja) ? number_format($llanta->kilometraje_baja) : 'N/A' }}</td>
                                <td>{{ !is_null($llanta->km_recorridos) ? number_format($llanta->km_recorridos) : 'N/A' }}</td>
                                <td>${{ number_format((float) ($llanta->costo ?? 0), 2) }}</td>
                                <td>
                                    @if($llanta->estado === 'activa')
                                        <span class="badge bg-success">Activa</span>
                                    @elseif($llanta->estado === 'rotada')
                                        <span class="badge bg-warning">Rotada</span>
                                    @else
                                        <span class="badge bg-danger">Baja</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('vehiculos.llantas.edit', [$vehiculo->id, $llanta->id]) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('vehiculos.llantas.destroy', [$vehiculo->id, $llanta->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este registro de llanta?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <i class="fas fa-info-circle text-muted"></i> Sin historial tecnico de llantas todavia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($llantas, 'links'))
            <div class="card-footer">
                {{ $llantas->links() }}
            </div>
        @endif
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <strong>Costo acumulado por posicion</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Posicion</th>
                            <th>Costo acumulado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($costoPorPosicion as $item)
                            <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $item->posicion)) }}</td>
                                <td>${{ number_format((float) $item->total_costo, 2) }}</td>
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
    </div>
</div>
@stop
