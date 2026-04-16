@extends('adminlte::page')

@section('title', 'Vehiculos')

@section('css')
<style>
    .checklist-fluid-row label {
        min-height: 40px;
        line-height: 1.15;
    }
    .checklist-fluid-row .label-shift {
        position: relative;
        top: 6px;
    }
</style>
@endsection

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

<form id="SalidaChecklist" method="POST"
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
                    <a class="nav-link active" href="#tab1" data-bs-toggle="tab">
                        Datos Generales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab2" data-bs-toggle="tab">
                        Herramientas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab3" data-bs-toggle="tab">
                        Documentos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab4" data-bs-toggle="tab">
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
        <select name="nivel_gasolina" class="form-control @error('nivel_gasolina') is-invalid @enderror" required>
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
        <input type="number" name="kilometraje" class="form-control @error('kilometraje') is-invalid @enderror" required value="{{ old('kilometraje', $defaultKilometraje ?? '') }}">
        @error('kilometraje')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="alert alert-info py-2">
        Captura de llantas por tanteo: registra si la calibracion se percibe <strong>Baja</strong>, <strong>Normal</strong> o <strong>Alta</strong> en cada llanta.
    </div>

    <div class="row checklist-fluid-row">
        <div class="col-md-3 mb-3">
            <label>Liquido limpia parabrisas</label>
            <select name="liquido_limpiaparabrisas" class="form-control @error('liquido_limpiaparabrisas') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                <option value="suficiente" {{ old('liquido_limpiaparabrisas', $defaultLiquidoLimpiaparabrisas ?? '') === 'suficiente' ? 'selected' : '' }}>Suficiente</option>
                <option value="escaso" {{ old('liquido_limpiaparabrisas', $defaultLiquidoLimpiaparabrisas ?? '') === 'escaso' ? 'selected' : '' }}>Escaso</option>
                <option value="no_hay" {{ old('liquido_limpiaparabrisas', $defaultLiquidoLimpiaparabrisas ?? '') === 'no_hay' ? 'selected' : '' }}>No hay</option>
            </select>
            @error('liquido_limpiaparabrisas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="label-shift">Aceite</label>
            <select name="aceite" class="form-control @error('aceite') is-invalid @enderror">
                <option value="">Seleccione</option>
                <option value="suficiente" {{ old('aceite', $defaultAceite ?? '') === 'suficiente' ? 'selected' : '' }}>Suficiente</option>
                <option value="escaso" {{ old('aceite', $defaultAceite ?? '') === 'escaso' ? 'selected' : '' }}>Escaso</option>
                <option value="no_hay" {{ old('aceite', $defaultAceite ?? '') === 'no_hay' ? 'selected' : '' }}>No hay</option>
            </select>
            @error('aceite')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="label-shift">Anticongelante</label>
            <select name="anticongelante" class="form-control @error('anticongelante') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                <option value="suficiente" {{ old('anticongelante', $defaultAnticongelante ?? '') === 'suficiente' ? 'selected' : '' }}>Suficiente</option>
                <option value="escaso" {{ old('anticongelante', $defaultAnticongelante ?? '') === 'escaso' ? 'selected' : '' }}>Escaso</option>
                <option value="no_hay" {{ old('anticongelante', $defaultAnticongelante ?? '') === 'no_hay' ? 'selected' : '' }}>No hay</option>
            </select>
            @error('anticongelante')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="label-shift">Liquido de frenos</label>
            <select name="liquido_frenos" class="form-control @error('liquido_frenos') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                <option value="suficiente" {{ old('liquido_frenos', $defaultLiquidoFrenos ?? '') === 'suficiente' ? 'selected' : '' }}>Suficiente</option>
                <option value="escaso" {{ old('liquido_frenos', $defaultLiquidoFrenos ?? '') === 'escaso' ? 'selected' : '' }}>Escaso</option>
                <option value="no_hay" {{ old('liquido_frenos', $defaultLiquidoFrenos ?? '') === 'no_hay' ? 'selected' : '' }}>No hay</option>
            </select>
            @error('liquido_frenos')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label>Estado general de llantas</label>
        <select name="estado_llantas" class="form-control @error('estado_llantas') is-invalid @enderror" required>
            <option value="">Seleccione</option>
            <option value="buen_estado" {{ old('estado_llantas', $defaultEstadoLlantas ?? '') === 'buen_estado' ? 'selected' : '' }}>Buen estado</option>
            <option value="regular" {{ old('estado_llantas', $defaultEstadoLlantas ?? '') === 'regular' ? 'selected' : '' }}>Regular</option>
            <option value="malo" {{ old('estado_llantas', $defaultEstadoLlantas ?? '') === 'malo' ? 'selected' : '' }}>Malo</option>
        </select>
            @error('estado_llantas')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label>Delantera izquierda (calibracion)</label>
            <select name="llanta_delantera_izq_calibracion" class="form-control @error('llanta_delantera_izq_calibracion') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                <option value="baja" {{ old('llanta_delantera_izq_calibracion', $defaultLlantaDelanteraIzq ?? '') === 'baja' ? 'selected' : '' }}>Baja</option>
                <option value="normal" {{ old('llanta_delantera_izq_calibracion', $defaultLlantaDelanteraIzq ?? '') === 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="alta" {{ old('llanta_delantera_izq_calibracion', $defaultLlantaDelanteraIzq ?? '') === 'alta' ? 'selected' : '' }}>Alta</option>
            </select>
            @error('llanta_delantera_izq_calibracion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label>Delantera derecha (calibracion)</label>
            <select name="llanta_delantera_der_calibracion" class="form-control @error('llanta_delantera_der_calibracion') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                <option value="baja" {{ old('llanta_delantera_der_calibracion', $defaultLlantaDelanteraDer ?? '') === 'baja' ? 'selected' : '' }}>Baja</option>
                <option value="normal" {{ old('llanta_delantera_der_calibracion', $defaultLlantaDelanteraDer ?? '') === 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="alta" {{ old('llanta_delantera_der_calibracion', $defaultLlantaDelanteraDer ?? '') === 'alta' ? 'selected' : '' }}>Alta</option>
            </select>
            @error('llanta_delantera_der_calibracion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label>Trasera izquierda (calibracion)</label>
            <select name="llanta_trasera_izq_calibracion" class="form-control @error('llanta_trasera_izq_calibracion') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                <option value="baja" {{ old('llanta_trasera_izq_calibracion', $defaultLlantaTraseraIzq ?? '') === 'baja' ? 'selected' : '' }}>Baja</option>
                <option value="normal" {{ old('llanta_trasera_izq_calibracion', $defaultLlantaTraseraIzq ?? '') === 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="alta" {{ old('llanta_trasera_izq_calibracion', $defaultLlantaTraseraIzq ?? '') === 'alta' ? 'selected' : '' }}>Alta</option>
            </select>
            @error('llanta_trasera_izq_calibracion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label>Trasera derecha (calibracion)</label>
            <select name="llanta_trasera_der_calibracion" class="form-control @error('llanta_trasera_der_calibracion') is-invalid @enderror" required>
                <option value="">Seleccione</option>
                <option value="baja" {{ old('llanta_trasera_der_calibracion', $defaultLlantaTraseraDer ?? '') === 'baja' ? 'selected' : '' }}>Baja</option>
                <option value="normal" {{ old('llanta_trasera_der_calibracion', $defaultLlantaTraseraDer ?? '') === 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="alta" {{ old('llanta_trasera_der_calibracion', $defaultLlantaTraseraDer ?? '') === 'alta' ? 'selected' : '' }}>Alta</option>
            </select>
            @error('llanta_trasera_der_calibracion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
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

<script>
    function validarTab1() {

        let nivel = document.querySelector('[name="nivel_gasolina"]').value;
        let km = document.querySelector('[name="kilometraje"]').value;
        let liquido = document.querySelector('[name="liquido_limpiaparabrisas"]').value;
        let aceite = document.querySelector('[name="aceite"]').value;
        let anticongelante = document.querySelector('[name="anticongelante"]').value;
        let liquido_frenos = document.querySelector('[name="liquido_frenos"]').value;
        let estado_llantas = document.querySelector('[name="estado_llantas"]').value;
        let d_cal_izq= document.querySelector('[name="llanta_delantera_izq_calibracion"]').value;
        let d_cali_der = document.querySelector('[name="llanta_delantera_der_calibracion"]').value;
        let t_cali_izq = document.querySelector('[name="llanta_trasera_izq_calibracion"]').value;
        let t_cali_der = document.querySelector('[name="llanta_trasera_der_calibracion"]').value;

        let faltantes = [];

        if (!nivel) faltantes.push('Nivel de gasolina');
        if (!km) faltantes.push('Kilometraje');
        if (!liquido) faltantes.push('Líquido limpiaparabrisas');
        if (!aceite) faltantes.push('Aceite');
        if (!anticongelante) faltantes.push('Anticongelante');
        if (!liquido_frenos) faltantes.push('Líquido de frenos');
        if (!estado_llantas) faltantes.push('Estado de llantas');
        if (!d_cal_izq) faltantes.push('Llanta delantera izquierda');
        if (!d_cali_der) faltantes.push('Llanta delantera derecha');
        if (!t_cali_izq) faltantes.push('Llanta trasera izquierda');
        if (!t_cali_der) faltantes.push('Llanta trasera derecha');

        if (faltantes.length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Faltan datos',
                html: `
                    <b>Completa los siguientes campos:</b>
                    <ul style="text-align:left;">
                        ${faltantes.map(campo => `<li>${campo}</li>`).join('')}
                    </ul>
                `
            });
            return false;
        }

        return true;
    }
    function nextTab(tabNum) {
    // Validación para pasar de TAB 1 → TAB 2
    if (tabNum === 2) {
        if (!validarTab1()) return;
    }
        
        // cambiar tab
        document.querySelectorAll('.nav-pills .nav-link').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show','active'));

        document.querySelector('.nav-pills .nav-link[href="#tab'+tabNum+'"]').classList.add('active');
        document.getElementById('tab'+tabNum).classList.add('show','active');
    }
    document.querySelectorAll('.nav-pills .nav-link').forEach(tab => {

        tab.addEventListener('show.bs.tab', function (e) {

            let target = e.target.getAttribute('href');

            if (target === '#tab2') {
                if (!validarTab1()) {
                    e.preventDefault();
                }
            }

            if (target === '#tab3') {
                if (!validarTab1()) {
                    e.preventDefault();
                }
            }

            if (target === '#tab4') {
                if (!validarTab1()) {
                    e.preventDefault();
                }
            }

        });

    });
document.getElementById('SalidaChecklist').addEventListener('submit', function(e) {
    let evidencias = document.querySelector('[name="evidencias[]"]').files.length;

    if (evidencias === 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Evidencia requerida',
            text: 'Debes subir al menos una evidencia'
        });
    }
});
</script>
@endsection
