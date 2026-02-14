@extends('adminlte::page')
<br>
<br>
<br>
@section('title', 'Gestión de Vehículos')

@section('content_header')
    <h1>Gestión de Vehículos</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Listado de Vehículos</span>
        <a href="{{ route('vehiculos.create') }}" class="btn btn-primary btn-sm">
            + Nuevo Vehículo
        </a>
    </div>

    <div class="card-body p-0">
        <table class="table table-bordered table-hover table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Vehículo</th>
                    <th>Año</th>
                    <th>Estado</th>
                    <th>Editar Vehículo</th>
                    <th>Eliminar Vehículo</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($vehiculos as $vehiculo)

                @php
                    $salidaActiva = $vehiculo->salidaActiva;
                @endphp

                <tr>
                    <td>{{ $vehiculo->id }}</td>

                    <td>
                        <strong>{{ $vehiculo->placa }}</strong><br>
                        <small class="text-muted">
                            {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                        </small>
                    </td>

                    <td>{{ $vehiculo->anio }}</td>

                    <td>
                        @if($vehiculo->estado === 'disponible')
                            <span class="badge bg-success">Disponible</span>
                        @elseif($vehiculo->estado === 'ocupado')
                            <span class="badge bg-warning">Ocupado</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </td>
                    <!--bton editar -->
                    <td>
                        <a href="{{ route('vehiculos.edit', $vehiculo->id) }}"
                           class="btn btn-sm btn-warning">
                            Editar
                        </a>
                    </td>
                    <!--aqui termina -->
                    <td>
                        <form action="{{ route('vehiculos.destroy', $vehiculo->id) }}"
                              method="POST"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('¿Eliminar vehículo?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                    <!-- bton eliminar -->
                    <td>
                        {{-- ACCIONES --}}
                        

                        @if($vehiculo->estado === 'disponible')
                            <a href="{{ route('salidas.create') }}"
                               class="btn btn-sm btn-success">
                                Nueva salida
                            </a>
                            @else 
                            <span class="text-muted">No disponible</span>
                        @endif
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No hay vehículos registrados.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
