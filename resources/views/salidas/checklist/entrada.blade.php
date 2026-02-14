@extends('adminlte::page')

@section('content')

<br><br><br>

<div class="container">
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-lg">
<div class="card-header bg-primary text-white text-center">
    <h4 class="mb-0">Checklist de Entrada</h4>
</div>

<div class="card-body">

<p class="text-center">
    <strong>Vehículo:</strong>
    {{ $salida->vehiculo->placa }} - {{ $salida->vehiculo->marca }}
</p>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST"
      action="{{ route('salidas.checklist.entrada.store', $salida->id) }}"
      enctype="multipart/form-data">
@csrf

<!-- Nivel gasolina -->
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

<!-- Kilometraje -->
<div class="mb-3">
    <label>Kilometraje final</label>
    <input type="number" name="kilometraje" class="form-control" required>
</div>

<hr>

<!-- Switches limpieza -->
<div class="custom-control custom-switch mb-2">
    <input type="checkbox"
           class="custom-control-input"
           id="limpio_exterior"
           name="limpio_exterior"
           value="1">
    <label class="custom-control-label" for="limpio_exterior">
        Limpio Exterior
    </label>
</div>

<div class="custom-control custom-switch mb-3">
    <input type="checkbox"
           class="custom-control-input"
           id="limpio_interior"
           name="limpio_interior"
           value="1">
    <label class="custom-control-label" for="limpio_interior">
        Limpio Interior
    </label>
</div>

<hr>

<!-- Observaciones -->
<div class="mb-3">
    <label>Observaciones</label>
    <textarea name="observaciones"
              class="form-control"
              rows="3"></textarea>
</div>

<hr>

<!-- Evidencias -->
<div class="mb-3">
    <label>Evidencia fotográfica (mínimo 5 imágenes)</label>
    <input type="file"
           name="evidencias[]"
           class="form-control"
           multiple
           accept="image/*"
           required>
</div>

<!-- Botones -->
<div class="text-center mt-4">
    <button class="btn btn-success btn-lg">
        Finalizar salida
    </button>
    <a href="{{ route('salidas.index') }}"
       class="btn btn-secondary btn-lg">
        Cancelar
    </a>
</div>

</form>

</div>
</div>

</div>
</div>
</div>

@endsection
