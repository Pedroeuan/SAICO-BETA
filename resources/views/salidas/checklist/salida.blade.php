@extends('adminlte::page')

@section('title', 'Vehiculos')

@section('content')

<br><br><br>
<h3 align="center">Checklist de Salida</h3>
<br>

<div class="custom-container">

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
<div class="card-body row">

    <!-- COLUMNA IZQUIERDA: FOTO VEHICULO -->
    <div class="col-5 text-center d-flex align-items-center justify-content-center">
        <div>
            <h5 class="mb-3">Vehículo</h5>

            @if(!empty($salida->vehiculo->foto_principal))
                <img src="{{ asset('storage/'.$salida->vehiculo->foto_principal) }}"
                     alt="Foto vehículo"
                     width="340"
                     height="300"
                     style="object-fit: contain;">
            @else
                <img src="{{ asset('images/vehiculo_checklist.png') }}"
                     alt="checklist-vehiculo"
                     width="340"
                     height="300"
                     style="object-fit: contain;">
            @endif

            <div class="mt-2">
                <strong>{{ $salida->vehiculo->placa }} - {{ $salida->vehiculo->marca }}</strong>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: FORMULARIO -->
    <div class="col-7">

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

<!-- TAB 1 -->
<div class="tab-pane fade show active" id="tab1">

    <div class="mb-3">
        <label>Nivel de gasolina</label>
        <select name="nivel_gasolina" class="form-control" required>
            <option value="">Seleccione</option>
            <option value="Lleno" {{ old('nivel_gasolina', $defaultNivel ?? '') == 'Lleno' ? 'selected' : '' }}>Lleno</option>
            <option value="3/4" {{ old('nivel_gasolina', $defaultNivel ?? '') == '3/4' ? 'selected' : '' }}>3/4</option>
            <option value="1/2" {{ old('nivel_gasolina', $defaultNivel ?? '') == '1/2' ? 'selected' : '' }}>1/2</option>
            <option value="1/4" {{ old('nivel_gasolina', $defaultNivel ?? '') == '1/4' ? 'selected' : '' }}>1/4</option>
            <option value="Vacío" {{ old('nivel_gasolina', $defaultNivel ?? '') == 'Vacío' ? 'selected' : '' }}>Vacío</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Kilometraje</label>
        <input type="number" name="kilometraje" class="form-control" required value="{{ old('kilometraje', $defaultKilometraje ?? '') }}">
    </div>

    <div class="custom-control custom-switch mb-2">
        <input type="hidden" name="limpio_exterior" value="0">
        <input type="checkbox"
               class="custom-control-input"
               id="limpio_exterior"
               name="limpio_exterior"
               value="1" {{ old('limpio_exterior') == '1' ? 'checked' : '' }}>
        <label class="custom-control-label" for="limpio_exterior">
            Limpio Exterior
        </label>
    </div>

    <div class="custom-control custom-switch mb-3">
        <input type="hidden" name="limpio_interior" value="0">
        <input type="checkbox"
               class="custom-control-input"
               id="limpio_interior"
               name="limpio_interior"
               value="1" {{ old('limpio_interior') == '1' ? 'checked' : '' }}>
        <label class="custom-control-label" for="limpio_interior">
            Limpio Interior
        </label>
    </div>

    <div class="mb-3">
        <label>Observaciones</label>
        <textarea name="observaciones" class="form-control">{{ old('observaciones') }}</textarea>
    </div>

    <div class="text-end">
        <button type="button" class="btn btn-primary" onclick="nextTab(2)">Siguiente <i class="fas fa-arrow-right"></i></button>
    </div>
</div>

<!-- TAB 2 -->
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
               value="1" {{ old('herramientas.'.$key) ? 'checked' : '' }}>
        <label class="custom-control-label" for="{{ $key }}">
            {{ $label }}
        </label>
    </div>
</div>
@endforeach

<div class="text-end mt-3 col-12">
    <button type="button" class="btn btn-primary" onclick="nextTab(3)">Siguiente <i class="fas fa-arrow-right"></i></button>
</div>
</div>

</div>

<!-- TAB 3 -->
<div class="tab-pane fade" id="tab3">

    <div class="mb-3">
        <label><strong>Licencia de conducir (chofer)</strong></label>
        <div>
            @if(isset($licenciaVigente) && $licenciaVigente)
                <span class="badge bg-success">Vigente</span>
            @else
                <span class="badge bg-danger">Vencida / No registrada</span>
            @endif
        </div>
    </div>

    <div class="mb-3">
        <label><strong>Tarjeta de circulación (vehículo)</strong></label>
        <div>
            @if(isset($tarjetaVigente) && $tarjetaVigente)
                <span class="badge bg-success">Vigente</span>
            @else
                <span class="badge bg-danger">Vencida / No registrada</span>
            @endif
        </div>
    </div>

    <div class="mb-3">
        <label><strong>Póliza de seguro (vehículo)</strong></label>
        <div>
            @if(isset($polizaVigente) && $polizaVigente)
                <span class="badge bg-success">Vigente</span>
            @else
                <span class="badge bg-danger">Vencida / No registrada</span>
            @endif
        </div>
    </div>

    <div class="text-end mt-3">
        <button type="button" class="btn btn-primary" onclick="nextTab(4)">Siguiente <i class="fas fa-arrow-right"></i></button>
    </div>
</div>

<!-- TAB 4 -->
<div class="tab-pane fade" id="tab4">

    <label>Evidencia fotográfica (3 imágenes)</label>
    <input type="file"
           name="evidencias[]"
           class="form-control"
           multiple
           accept="image/*"
           required>

    <div class="text-end mt-3">
        <button type="submit" class="btn btn-success">Guardar Checklist</button>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
</div>

</div>
</div>
</div>

    </div> <!-- col-7 -->

</div> <!-- card-body row -->
</div> <!-- card -->

</form>
</div>
@stop

@section('js')
<script>
function nextTab(tabNum) {
    // Compatible con Bootstrap 4/5
    document.querySelectorAll('.nav-pills .nav-link').forEach(function(el){el.classList.remove('active');});
    document.querySelectorAll('.tab-pane').forEach(function(el){el.classList.remove('show','active');});
    document.querySelector('.nav-pills .nav-link[href="#tab'+tabNum+'"]').classList.add('active');
    document.getElementById('tab'+tabNum).classList.add('show','active');
}
</script>
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

@endsection