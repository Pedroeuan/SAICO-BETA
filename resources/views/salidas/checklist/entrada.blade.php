@extends('adminlte::page')
@section('title', 'Vehículos')
@section('content')

<br><br><br>

<div class="container">
<div class="row justify-content-center">
<div class="col-md-10">

<div class="card shadow-lg">
<div class="card-header bg-primary text-white text-center">
    <h4 class="mb-0">Checklist de Entrada</h4>
</div>

<div class="card-body row">

    <!-- COLUMNA IZQUIERDA: FOTO VEHICULO -->
    <div class="col-md-5 text-center d-flex align-items-center justify-content-center">
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
    <div class="col-md-7">

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

        <hr>

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

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3"></textarea>
        </div>

        <hr>

        <div class="mb-3">
            <label>Evidencia fotográfica (exactamente 3 imágenes)</label>
            <input type="file"
                   name="evidencias[]"
                   class="form-control"
                   multiple
                   accept="image/*"
                   required>
        </div>

        <div class="text-center mt-4">
            <button class="btn btn-success btn-lg">
                Finalizar salida
            </button>
            <a href="{{ route('salidas.index') }}" class="btn btn-secondary btn-lg">
                Cancelar
            </a>
        </div>

        </form>
    </div>

</div>
</div>

</div>
</div>
</div>

@endsection