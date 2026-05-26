@extends('adminlte::page')

@section('title', 'Combustible del Vehiculo')

@section('content')
<br>
<br>
<br>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Combustible - {{ $vehiculo->placa }}</h4>
            <small class="text-muted">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</small>
        </div>
        <div>
            <a href="{{ route('vehiculos.edit', $vehiculo->id) }}" class="btn btn-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> Volver al vehiculo
            </a>
            <a href="{{ route('vehiculos.combustible.create', $vehiculo->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-gas-pump"></i> Nueva carga
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ (int) ($resumen->total_cargas ?? 0) }}</h3>
                    <p>Total de cargas</p>
                </div>
                <div class="icon"><i class="fas fa-list-ol"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format((float) ($resumen->litros_total ?? 0), 1) }}</h3>
                    <p>Litros acumulados</p>
                </div>
                <div class="icon"><i class="fas fa-tint"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>${{ number_format((float) ($resumen->costo_total ?? 0), 2) }}</h3>
                    <p>Gasto acumulado</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>${{ number_format((float) ($resumen->precio_promedio ?? 0), 2) }}</h3>
                    <p>Precio promedio por litro</p>
                </div>
                <div class="icon"><i class="fas fa-chart-line"></i></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Historial de cargas</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>KM</th>
                            <th>Litros</th>
                            <th>Costo</th>
                            <th>$/L</th>
                            <th>Tipo</th>
                            <th>Proveedor</th>
                            <th>Tanque</th>
                            <th>Comprobante</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cargas as $carga)
                            <tr>
                                <td>{{ optional($carga->fecha_carga)->format('Y-m-d') }}</td>
                                <td>{{ number_format($carga->kilometraje) }}</td>
                                <td>{{ number_format((float) $carga->litros, 3) }}</td>
                                <td>${{ number_format((float) $carga->costo_total, 2) }}</td>
                                <td>${{ number_format((float) ($carga->precio_por_litro ?? 0), 2) }}</td>
                                <td>{{ ucfirst($carga->tipo_combustible) }}</td>
                                <td>{{ $carga->proveedor ?: 'N/A' }}</td>
                                <td>
                                    @if($carga->tanque_lleno)
                                        <span class="badge bg-success">Lleno</span>
                                    @else
                                        <span class="badge bg-secondary">Parcial</span>
                                    @endif
                                </td>
                                <td>
                                    @if($carga->ticket_url)
                                        <a href="{{ asset('storage/'.$carga->ticket_url) }}" target="_blank" rel="noopener">Ver</a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('vehiculos.combustible.edit', [$vehiculo->id, $carga->id]) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('vehiculos.combustible.destroy', [$vehiculo->id, $carga->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta carga de combustible?');">
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
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-info-circle text-muted"></i> Sin cargas registradas todavia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($cargas, 'links'))
            <div class="card-footer">
                {{ $cargas->links() }}
            </div>
        @endif
    </div>
</div>
@stop
