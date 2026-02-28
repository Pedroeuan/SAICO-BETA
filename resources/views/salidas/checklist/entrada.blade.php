@extends('adminlte::page')
@section('title', 'Vehículos')

@section('css')
<style>

html, body {
    height: 100%;
    overflow: hidden;
}

.content-wrapper {
    height: 100vh;
    overflow: hidden;
}

.container {
    margin-top: 0 !important;
}

.card {
    height: 96vh;
}

.card-header {
    padding: 6px 10px;
}

.card-body {
    padding: 8px 12px;
    font-size: 13px;
}

/* Quitar espacios bootstrap */
.mb-3 { margin-bottom: 6px !important; }
.mb-2 { margin-bottom: 4px !important; }
.mt-4 { margin-top: 8px !important; }
hr { margin: 6px 0 !important; }

/* Labels */
label {
    font-size: 12px;
    margin-bottom: 2px;
}

/* Inputs compactos */
.form-control {
    height: 30px;
    font-size: 12px;
    padding: 2px 6px;
}

/* Textarea pequeño */
textarea.form-control {
    height: 50px !important;
}

/* Imagen más pequeña */
img {
    max-height: 200px;
}

/* Botones más chicos */
.btn-lg {
    padding: 5px 12px;
    font-size: 14px;
}

</style>
@endsection
@section('css')
<style>

html, body {
    height: 100%;
}

.content-wrapper {
    padding-top: 10px !important; /* sube el contenido */
}

.container {
    margin-top: 0 !important;
}

.card {
    margin-top: 10px !important; /* reduce espacio arriba */
}

/* 🔠 Aumentar tamaño de letra */
.card-body {
    font-size: 17px; /* +4 aprox */
}

/* Labels más grandes */
label {
    font-size: 17px;
}

/* Selects e inputs más grandes */
.form-control {
    font-size: 17px;
}

/* Texto del header más grande */
.card-header h4 {
    font-size: 22px;
}

</style>
@endsection
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
                <option value="Lleno" {{ old('nivel_gasolina') == 'Lleno' ? 'selected' : '' }}>Lleno</option>
                <option value="3/4" {{ old('nivel_gasolina') == '3/4' ? 'selected' : '' }}>3/4</option>
                <option value="1/2" {{ old('nivel_gasolina') == '1/2' ? 'selected' : '' }}>1/2</option>
                <option value="1/4" {{ old('nivel_gasolina') == '1/4' ? 'selected' : '' }}>1/4</option>
                <option value="Vacío" {{ old('nivel_gasolina') == 'Vacío' ? 'selected' : '' }}>Vacío</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Kilometraje final</label>
            <input type="number" name="kilometraje" class="form-control" required value="{{ old('kilometraje') }}">
        </div>

        <div class="alert alert-info py-2">
            Captura de llantas por tanteo: registra si la calibracion se percibe <strong>Baja</strong>, <strong>Normal</strong> o <strong>Alta</strong> en cada llanta.
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Liquido limpia parabrisas</label>
                <select name="liquido_limpiaparabrisas" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="suficiente" {{ old('liquido_limpiaparabrisas') === 'suficiente' ? 'selected' : '' }}>Suficiente</option>
                    <option value="escaso" {{ old('liquido_limpiaparabrisas') === 'escaso' ? 'selected' : '' }}>Escaso</option>
                    <option value="no_hay" {{ old('liquido_limpiaparabrisas') === 'no_hay' ? 'selected' : '' }}>No hay</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Aceite</label>
                <select name="aceite" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="suficiente" {{ old('aceite') === 'suficiente' ? 'selected' : '' }}>Suficiente</option>
                    <option value="escaso" {{ old('aceite') === 'escaso' ? 'selected' : '' }}>Escaso</option>
                    <option value="no_hay" {{ old('aceite') === 'no_hay' ? 'selected' : '' }}>No hay</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Anticongelante</label>
                <select name="anticongelante" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="suficiente" {{ old('anticongelante') === 'suficiente' ? 'selected' : '' }}>Suficiente</option>
                    <option value="escaso" {{ old('anticongelante') === 'escaso' ? 'selected' : '' }}>Escaso</option>
                    <option value="no_hay" {{ old('anticongelante') === 'no_hay' ? 'selected' : '' }}>No hay</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Estado general de llantas</label>
            <select name="estado_llantas" class="form-control" required>
                <option value="">Seleccione</option>
                <option value="buen_estado" {{ old('estado_llantas') === 'buen_estado' ? 'selected' : '' }}>Buen estado</option>
                <option value="regular" {{ old('estado_llantas') === 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="malo" {{ old('estado_llantas') === 'malo' ? 'selected' : '' }}>Malo</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Delantera izquierda (calibracion)</label>
                <select name="llanta_delantera_izq_calibracion" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="baja" {{ old('llanta_delantera_izq_calibracion') === 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="normal" {{ old('llanta_delantera_izq_calibracion') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="alta" {{ old('llanta_delantera_izq_calibracion') === 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Delantera derecha (calibracion)</label>
                <select name="llanta_delantera_der_calibracion" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="baja" {{ old('llanta_delantera_der_calibracion') === 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="normal" {{ old('llanta_delantera_der_calibracion') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="alta" {{ old('llanta_delantera_der_calibracion') === 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Trasera izquierda (calibracion)</label>
                <select name="llanta_trasera_izq_calibracion" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="baja" {{ old('llanta_trasera_izq_calibracion') === 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="normal" {{ old('llanta_trasera_izq_calibracion') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="alta" {{ old('llanta_trasera_izq_calibracion') === 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Trasera derecha (calibracion)</label>
                <select name="llanta_trasera_der_calibracion" class="form-control" required>
                    <option value="">Seleccione</option>
                    <option value="baja" {{ old('llanta_trasera_der_calibracion') === 'baja' ? 'selected' : '' }}>Baja</option>
                    <option value="normal" {{ old('llanta_trasera_der_calibracion') === 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="alta" {{ old('llanta_trasera_der_calibracion') === 'alta' ? 'selected' : '' }}>Alta</option>
                </select>
            </div>
        </div>

        <hr>

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

        <hr>

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
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
