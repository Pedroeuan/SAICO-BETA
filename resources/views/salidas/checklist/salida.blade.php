@extends('adminlte::page')

@section('content')

<br><br><br>
<h3 align="center">Checklist de Salida</h3>
<br>

<div class="custom-container">

<p>
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
      action="{{ route('salidas.checklist.salida.store', $salida->id) }}"
      enctype="multipart/form-data">
@csrf

<div class="card">
<div class="card-header p-2">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link active" href="#tab1" data-toggle="tab">
                Datos Generales
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tab2" data-toggle="tab">
                Herramientas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tab3" data-toggle="tab">
                Documentos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tab4" data-toggle="tab">
                Evidencias
            </a>
        </li>
    </ul>
</div>

<div class="card-body">
<div class="tab-content">

<!-- primer tab -->
<div class="tab-pane fade show active" id="tab1">

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

    <div class="mb-3">
        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control"></textarea>
    </div>

</div>

<div class="tab-pane fade" id="tab2">

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

<div class="row">
@foreach($herramientas as $key => $label)
<div class="col-md-6 mb-2">
    <div class="custom-control custom-checkbox">
        <input type="checkbox"
               class="custom-control-input"
               id="{{ $key }}"
               name="herramientas[{{ $key }}]"
               value="1">
        <label class="custom-control-label" for="{{ $key }}">
            {{ $label }}
        </label>
    </div>
</div>
@endforeach
</div>

</div>

<div class="tab-pane fade" id="tab3">

@php
$documentos = [
    'licencia_conducir' => 'Licencia de conducir',
    'tarjeta_circulacion' => 'Tarjeta de circulación',
    'poliza_seguro' => 'Póliza de seguro',
];
@endphp

@foreach($documentos as $key => $label)
<div class="mb-3">
    <label><strong>{{ $label }}</strong></label>

    <div class="custom-control custom-radio">
        <input type="radio"
               id="{{ $key }}_ok"
               name="documentos[{{ $key }}]"
               value="ok"
               class="custom-control-input"
               required>
        <label class="custom-control-label" for="{{ $key }}_ok">
            Vigente
        </label>
    </div>

    <div class="custom-control custom-radio">
        <input type="radio"
               id="{{ $key }}_vencido"
               name="documentos[{{ $key }}]"
               value="vencido"
               class="custom-control-input">
        <label class="custom-control-label" for="{{ $key }}_vencido">
            Vencido
        </label>
    </div>
</div>
@endforeach

</div>

<div class="tab-pane fade" id="tab4">

    <label>Evidencia fotográfica (mínimo 5 imágenes)</label>
    <input type="file"
           name="evidencias[]"
           class="form-control"
           multiple
           accept="image/*"
           required>

</div>

</div>
</div>
</div>

<div class="text-center mt-3">
    <button class="btn btn-success btn-lg">
        Guardar Checklist
    </button>
    <a href="{{ route('salidas.index') }}"
       class="btn btn-secondary btn-lg">
        Cancelar
    </a>
</div>

</form>
</div>

@endsection
