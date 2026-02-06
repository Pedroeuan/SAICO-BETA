@extends('adminlte::page')

@section('content')
<br>
<br>
<br>
<div class="container mt-4">
    <h4>Checklist de Salida</h4>

    <p>
        <strong>Vehículo:</strong>
        {{ $salida->vehiculo->placa }} - {{ $salida->vehiculo->marca }}
    </p>

    <form method="POST" action="{{ route('salidas.checklist.salida.store', $salida->id) }}">
        @csrf

        <div class="mb-3">
            <label>Nivel de gasolina</label>
            <select name="nivel_gasolina" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="Lleno">Lleno</option>
                <option value="3/4">3/4</option>
                <option value="1/2">1/2</option>
                <option value="1/4">1/4</option>
                <option value="Vacío">Vacío</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Kilometraje</label>
            <input type="number" name="kilometraje" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Limpio Exterior</label>
            <select name="limpio_exterior" class="form-control">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Limpio Interior</label>
            <select name="limpio_interior" class="form-control">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control"></textarea>
        </div>

        <button class="btn btn-success">Guardar Checklist</button>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary">Cancelar</a>
        
        <hr>
<h5>Herramientas del vehículo</h5>

@php
$herramientas = [
    'llantas' => 'Llantas',
    'extintor' => 'Extintor',
    'cables_corriente' => 'Cables para corriente',
    'gato_hidraulico' => 'Gato hidráulico',
    'llave_cruz' => 'Llave de cruz',
    'llanta_refaccion' => 'Llanta de refacción',
];
@endphp

@foreach($herramientas as $key => $label)
<div class="mb-2">
    <label>{{ $label }}</label>
    <select name="herramientas[{{ $key }}]" class="form-control" required>
        <option value="">Seleccione</option>
        <option value="1">Se tiene</option>
        <option value="0">No se tiene</option>
    </select>
</div>
@endforeach
<hr>
<h5>Documentos del vehículo</h5>

@php
$documentos = [
    'tarjeta_circulacion' => 'Tarjeta de circulación',
    'seguro' => 'Seguro',
    'verificacion' => 'Verificación',
    'tenencia' => 'Tenencia',
];
@endphp

@foreach($documentos as $key => $label)
<div class="mb-2">
    <label>{{ $label }}</label>
    <select name="documentos[{{ $key }}]" class="form-control" required>
        <option value="">Seleccione</option>
        <option value="1">Se tiene</option>
        <option value="0">No se tiene</option>
    </select>
</div>
@endforeach



    </form>
</div>
@endsection
