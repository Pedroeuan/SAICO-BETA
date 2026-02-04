@extends('adminlte::page')

@section('content')
<br>
<br>
<br>
<div class="container mt-4">
    <h4>Salidas Activas</h4>

    <a href="{{ route('salidas.create') }}" class="btn btn-primary mb-3">+ Nueva salida</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehículo</th>
                <th>Chofer</th>
                <th>Fecha Salida</th>
                <th>Fecha Regreso</th>
                <th>Estatus</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($salidas as $salida)
            <tr>
                <td>{{ $salida->id }}</td>
                <td>{{ $salida->vehiculo->placa ?? 'N/A' }}</td>
                <td>{{ $salida->chofer->name ?? 'N/A' }}</td>
                <td>{{ $salida->fecha_salida }}</td>
                <td>{{ $salida->fecha_regreso ?? '-' }}</td>
                <td>{{ ucfirst($salida->estatus) }}</td>
                <td>
                    @if($salida->estatus === 'activo')
                    <form action="{{ route('salidas.finalizar', $salida->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-sm btn-success" onclick="return confirm('¿Finalizar salida?')">Finalizar</button>
                    @else
                    -
                    @endif                   
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No hay salidas registradas.</td>
            </tr>
        @endforelse   
        </tbody>
    </table>
</div>
@endsection
