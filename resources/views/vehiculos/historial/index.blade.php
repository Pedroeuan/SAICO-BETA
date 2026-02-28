@extends('adminlte::page')
@section('title', 'Historial Vehiculo')
<br>
<br>
<br>
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-history"></i>
                Historial - {{ $vehiculo->placa }}
            </h5>
            <a href="{{ route('vehiculos.mantenimientos.index', $vehiculo->id) }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Volver a Mantenimientos
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">Historial de Mantenimiento (ultimos 5)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>KM</th>
                                    <th>Costo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosMantenimientos as $m)
                                    <tr>
                                        <td>{{ optional($m->fecha)->format('Y-m-d') }}</td>
                                        <td>{{ ucfirst($m->tipo) }}</td>
                                        <td>{{ $m->kilometraje ?? 'N/A' }}</td>
                                        <td>${{ number_format($m->costo ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Sin registros</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">Pagos del Vehiculo (ultimos 5)</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Anio</th>
                                    <th>Tipo</th>
                                    <th>Fecha pago</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosPagos as $p)
                                    <tr>
                                        <td>{{ $p->anio }}</td>
                                        <td>{{ ucfirst($p->tipo_pago) }}</td>
                                        <td>{{ optional($p->fecha_pago)->format('Y-m-d') ?? 'N/A' }}</td>
                                        <td>${{ number_format($p->monto ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Sin registros</td>
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
@endsection
