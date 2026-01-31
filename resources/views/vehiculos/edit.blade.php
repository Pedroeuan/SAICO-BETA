@extends('layouts.simple')

@section('content')
<h4>Editar Vehículo</h4>

<form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST">
    @csrf

    <div class="mb-2">
        <label>Placa</label>
        <input type="text" name="placa" class="form-control"
               value="{{ $vehiculo->placa }}" required>
    </div>

    <div class="mb-2">
        <label>Marca</label>
        <input type="text" name="marca" class="form-control"
               value="{{ $vehiculo->marca }}" required>
    </div>

    <div class="mb-2">
        <label>Modelo</label>
        <input type="text" name="modelo" class="form-control"
               value="{{ $vehiculo->modelo }}" required>
    </div>

    <div class="mb-2">
        <label>Año</label>
        <input type="number" name="anio" class="form-control"
               value="{{ $vehiculo->anio }}">
    </div>

    <button class="btn btn-success mt-3">Actualizar</button>
</form>
@endsection
