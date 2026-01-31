@extends('layouts.simple')

@section('content')
<h4>Registrar Vehículo</h4>

<form method="POST" action="{{ route('vehiculos.store') }}">
    @csrf

    <div class="mb-2">
        <label>Placa</label>
        <input type="text" name="placa" class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Marca</label>
        <input type="text" name="marca" class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Modelo</label>
        <input type="text" name="modelo" class="form-control" required>
    </div>

    <div class="mb-2">
        <label>Año</label>
        <input type="number" name="anio" class="form-control">
    </div>

    <button class="btn btn-success mt-3">Guardar</button>
</form>
@endsection
