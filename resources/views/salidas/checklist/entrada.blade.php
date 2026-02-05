@extends('adminlte::page')

@section('content')
<br>
<br>
<br>
<div class="container mt-4">
    <h4>Checklist de Entrada</h4>

    <p>
        <strong>Vehículo:</strong>
        {{ $salida->vehiculo->placa }} - {{ $salida->vehiculo->marca }}
    </p>

    <form method="POST" action="{{ route('salidas.checklist.entrada.store', $salida->id) }}">
        @csrf

        <div class="mb-3">
            <label>Nivel de gasolina al regresar</label>
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
            <label>Kilometraje final</label>
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

        <button class="btn btn-success">Finalizar salida</button>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
