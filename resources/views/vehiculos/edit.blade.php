@extends('adminlte::page')

@section('content')
<br>
<br>
<br>
<div class="container mt-4">
    <h4>Editar Vehículo</h4>

    <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-2">
            <label>Placa</label>
            <input type="text" name="placa" class="form-control"
                   value="{{ old('placa', $vehiculo->placa) }}" required>
        </div>

        <div class="mb-2">
            <label>Marca</label>
            <input type="text" name="marca" class="form-control"
                   value="{{ old('marca', $vehiculo->marca) }}" required>
        </div>

        <div class="mb-2">
            <label>Modelo</label>
            <input type="text" name="modelo" class="form-control"
                   value="{{ old('modelo', $vehiculo->modelo) }}" required>
        </div>

        <div class="mb-2">
            <label>Año</label>
            <input type="number" name="anio" class="form-control"
                   value="{{ old('anio', $vehiculo->anio) }}" required>
        </div>

        <div class="mb-2">
            <label>Estatus</label>
            <select name="estatus" class="form-control">
                <option value="disponible" {{ $vehiculo->estatus == 'disponible' ? 'selected' : '' }}>
                    Disponible
                </option>
                <option value="ocupado" {{ $vehiculo->estatus == 'ocupado' ? 'selected' : '' }}>
                    Ocupado
                </option>
                <option value="inactivo" {{ $vehiculo->estatus == 'inactivo' ? 'selected' : '' }}>
                    Inactivo
                </option>
            </select>
        </div>

        <button class="btn btn-success mt-3">Actualizar</button>
        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
</div>
@endsection
