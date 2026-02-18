
@extends('adminlte::page')

@section('title', 'Pre-Manifiesto')

@section('css')
<style>
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
</style>
@endsection

@section('content')
<br>
<br>
<br>
@php use Illuminate\Support\Str; @endphp
    <h2>PreManifiesto de Salida y/o Resguardo</h2>
    <br>
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-info"></i> Importante</h5>
        <p>Llena los datos generales del manifiesto como se muestra en los ejemplos</p>
    </div>
    <!--FORMULARIO -->
    <form id="manifiestoForm" action="{{route('solicitudes.storeManifiesto')}}" method="post" enctype="multipart/form-data">
        @csrf 
            <div class="row">

                <div class="col-sm-4">
                    <div class="form-group">

                        <label class="col-form-label">¿Cliente registrado?</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cliente_tipo" id="cliente_si" value="si" checked>
                            <label class="form-check-label" for="cliente_si">Sí</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="cliente_tipo" id="cliente_no" value="no">
                            <label class="form-check-label" for="cliente_no">No</label>
                        </div>

                        <!-- SELECT -->
                        <select class="form-control inputForm" name="Cliente" id="cliente_select" required>
                            <option value="">Seleccione un cliente</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->Cliente }}">
                                    {{ $cliente->Cliente }}
                                </option>
                            @endforeach
                        </select>

                        <!-- INPUT MANUAL -->
                        <input type="text" 
                            class="form-control inputForm mt-2 d-none" 
                            id="cliente_input"
                            name="Cliente_manual"
                            placeholder="Escriba el nombre del cliente">

                        @error('Cliente')
                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        @enderror

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Folio</label>
                        <input type="text" class="form-control inputForm" name="Folio" id="folio" placeholder="Ejemplo: PROP-001/24" required>
                        @error('Folio')
                                <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Destino</label>
                        <input type="text" class="form-control inputForm" name="Destino" placeholder="Ejemplo: PATIO DE FABRICACIÓN PROTEXA" value="{{old('Destino')}}" required>
                        @error('Destino')
                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Fecha de Salida</label>
                        <input type="date" class="form-control inputForm" name="Fecha_Salida" value="{{ $Solicitud->Fecha }}" required>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Trabajo</label>
                        <input type="text" class="form-control inputForm" name="Trabajo" placeholder="Ejemplo: Dureza" value="{{old('Trabajo')}}" required>
                        @error('Trabajo')
                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Puesto</label>
                        <input type="text" class="form-control inputForm" name="Puesto" placeholder="Ejemplo: TEC. PND" value="{{old('Puesto')}}" required>
                        @error('Puesto')
                                <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Responsable</label>
                        <input type="text" class="form-control inputForm" name="Responsable" placeholder="Ejemplo: ALFREDO MARTINEZ TORRRES" value="{{ $Solicitud->tecnico }}" required>
                        @error('Responsable')
                            <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                        @enderror
                    </div>
                </div>

                <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Persona que Entrega</label>
                                <input type="text" class="form-control inputForm" name="Entrega_Nombre" value="{{ Str::startsWith($Solicitud->tecnico, 'Ing.') ? $Solicitud->tecnico : 'Ing. ' . $Solicitud->tecnico }}"  required>
                                    @error('Entrega_Nombre')
                                        <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                    @enderror
                        </div>
                </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="col-form-label" for="inputSuccess">Técnico que Recibe</label>
                                <input type="text" class="form-control inputForm" name="Recibe_Nombre" value="Ing.{{ $Solicitud->tecnico }}" required>
                                    @error('Recibe_Nombre')
                                        <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                    @enderror
                        </div>
                    </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">En renta</label> <br>
                        <input type="checkbox"  name="Renta">                                          
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">SAT Y BMPRO</label><br>
                        <input type="checkbox"  name="SATBMPRO">                                          
                    </div>
                </div>

            <!--Campo Oculto para pasar el id de Solicitud -->
            <input type="hidden" class="form-control inputForm" name="idSolicitud" placeholder="" value="{{ $Solicitud->idSolicitud }}">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="col-form-label" for="inputSuccess">Observaciones</label>
                        <textarea class="form-control is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{old('Observaciones')}}</textarea>
                    </div>
                </div>      
            
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> ¡Bien hecho!</h5>
                Estos son los elementos que has aprobado
                </div>
                <div class="card-body">
                    <table id="TablaAprobados" class="table table-bordered" >
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>No.ECO</th>
                                <th>Marca</th>
                                <th>Ultima calibración</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($DetallesSolicitud as $detalle)
                                @php
                                    $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                                @endphp
                                <tr id="row-{{ $detalle->idDetalles_Solicitud }}">
                                    <td>{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                    <td>{{ $general->No_economico ?? 'N/A' }}</td>
                                    <td>{{ $general->Marca ?? 'N/A' }}</td>
                                    <td>{{ $general->Ultima_Fecha_calibracion ?? 'N/A' }}</td>
                                    <td>{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                    <td>{{ $detalle->Unidad ?? 'N/A' }}</td>
                                </tr>
                                    @endforeach
                            </tbody>
                    </table>
                </div>
                <p>
                <div class="container">
                    <div class="float-right">
                        <button type="submit" class="btn btn-info bg-primary">Finalizar Manifiesto</button>
                    </div>
                    <div class="float-left">
                        <!--BOTÓN -->
                        <a href="{{ route('solicitud.manifiesto-regresar', ['id' => $Solicitud->idSolicitud]) }}" class="btn btn-success" role="button">Regresar</a>
                    </div>
                </div>
</form>
@stop

@section('js')
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<script>

    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('manifiestoForm');

        const radioSi = document.getElementById('cliente_si');
        const radioNo = document.getElementById('cliente_no');
        const selectCliente = document.getElementById('cliente_select');
        const inputCliente = document.getElementById('cliente_input');
        const folioInput = document.getElementById('folio');

        /* ==============================
        MOSTRAR / OCULTAR SELECT O INPUT
        ============================== */
        function toggleCliente() {

            if (radioSi.checked) {
                selectCliente.classList.remove('d-none');
                inputCliente.classList.add('d-none');
                selectCliente.setAttribute('required', true);
                inputCliente.removeAttribute('required');
            } else {
                selectCliente.classList.add('d-none');
                inputCliente.classList.remove('d-none');
                inputCliente.setAttribute('required', true);
                selectCliente.removeAttribute('required');
            }
        }

        radioSi.addEventListener('change', toggleCliente);
        radioNo.addEventListener('change', toggleCliente);
        toggleCliente();


        /* ==============================
        GENERAR FOLIO
        ============================== */
        function generarFolio(cliente) {

            if (!cliente || cliente.trim() === '') {
                folioInput.value = '';
                return;
            }

            cliente = cliente.trim();

            const clientePrefix = cliente.substring(0, 4).toUpperCase();

            fetch('{{ route("manifiestos.count") }}')
                .then(response => response.json())
                .then(data => {

                    const totalRegistros = data.total + 1;
                    const registroCount = totalRegistros.toString().padStart(3, '0');
                    const year = new Date().getFullYear().toString().slice(-2);

                    const folio = `${clientePrefix}-${registroCount}/${year}`;
                    folioInput.value = folio;
                })
                .catch(error => {
                    console.error('Error al obtener el total de registros:', error);
                });
        }

        // SELECT
        selectCliente.addEventListener('change', function() {
            if (radioSi.checked) {
                generarFolio(selectCliente.value);
            }
        });

        // INPUT manual
        inputCliente.addEventListener('input', function() {
            if (radioNo.checked) {
                generarFolio(inputCliente.value);
            }
        });


        /* ==============================
        VALIDACIÓN AL ENVIAR
        ============================== */
        form.addEventListener('submit', function(event) {

            let clienteFinal = '';

            if (radioSi.checked) {
                clienteFinal = selectCliente.value;
            } else {
                clienteFinal = inputCliente.value.trim();
            }

            if (clienteFinal === '') {
                event.preventDefault();
                alert("Por favor, ingresa o selecciona un cliente.");
                return;
            }
        });

        /* ==============================
        PREVENIR ENTER
        ============================== */
        form.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });


        /* ==============================
        LOCAL STORAGE
        ============================== */
        document.querySelectorAll('#manifiestoForm input, #manifiestoForm textarea, #manifiestoForm select').forEach(function(input) {

            input.addEventListener('input', function() {
                localStorage.setItem('manifiestoForm_' + input.name, input.value);
            });

            let value = localStorage.getItem('manifiestoForm_' + input.name);
            if (value !== null && input.type !== 'file') {
                input.value = value;
            }
        });

        form.addEventListener('submit', function() {
            document.querySelectorAll('#manifiestoForm input, #manifiestoForm textarea, #manifiestoForm select').forEach(function(input) {
                localStorage.removeItem('manifiestoForm_' + input.name);
            });
        });

    });


    /* ==============================
    BOOTSTRAP SWITCH
    ============================== */
    $("[name='Renta']").bootstrapSwitch({
        onText: 'Sí',
        offText: 'No'
    });

    $("[name='SATBMPRO']").bootstrapSwitch({
        onText: 'Sí',
        offText: 'No'
    });

</script>

@endsection