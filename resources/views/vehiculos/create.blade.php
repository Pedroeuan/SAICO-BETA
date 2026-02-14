@extends('adminlte::page')

@section('content')
<br>
<br>
<br>
<h4>Registrar Vehículo</h4>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

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
    <div class="mb-2">
        <label>Estatus</label>
        <select name="estatus" class="form-control" required>
            <option value="disponible">Disponible</option>
            <option value="ocupado">Ocupado</option>
            <option value="inactivo">Inactivo</option>
        </select>
    </div>

    <button class="btn btn-success mt-3">Guardar</button>
</form>
@endsection
