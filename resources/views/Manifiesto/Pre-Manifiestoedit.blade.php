
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>
<br>
<br>
    <h2>PreManifiesto de Salida y/o Resguardo</h2>
    <br>
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-info"></i> Importante</h5>
        <p>Llena los datos generales del manifiesto como se muestra en los ejemplos</p>
    </div>
                    <!--Formulario-->
                    <form id="manifiestoForm" action="{{ route('solicitudes.updateSolicitud', ['id' => $Solicitud->idSolicitud]) }}" method="post" enctype="multipart/form-data">
                                @csrf 
                            <div class="row">

                                    <div class="col-sm-4">
                                        <div class="form-group">

                                            <label class="col-form-label" for="inputSuccess">Cliente</label>

                                            <select class="form-control inputForm" name="Cliente" required>
                                                <option value="">Seleccione un cliente</option>
                                                @foreach ($clientes as $cliente)
                                                    <option value="{{ $cliente->Cliente }}" {{ $cliente->Cliente == $Manifiestos->Cliente ? 'selected' : '' }}>
                                                        {{ $cliente->Cliente }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('Cliente')
                                                <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Folio</label>
                                            <input type="text" class="form-control inputForm" name="Folio" id="folio" placeholder="Ejemplo: PROP-001/24" value="{{ $Manifiestos->Folio }}" required>
                                            @error('Folio')
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Destino</label>
                                            <input type="text" class="form-control inputForm" name="Destino" placeholder="Ejemplo: PATIO DE FABRICACIÓN PROTEXA" value="{{ $Manifiestos->Destino }}" required>
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
                                            <input type="text" class="form-control inputForm" name="Trabajo" placeholder="Ejemplo: Dureza" value="{{ $Manifiestos->Trabajo }}" required>
                                            @error('Trabajo')
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Puesto</label>
                                            <input type="text" class="form-control inputForm" name="Puesto" placeholder="Ejemplo: TEC. PND" value="{{ $Manifiestos->Puesto }}" required>
                                            @error('Puesto')
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Responsable</label>
                                            <input type="text" class="form-control inputForm" name="Responsable" placeholder="Ejemplo: ALFREDO MARTINEZ TORRRES" value="{{ $Manifiestos->Responsable }}" required>
                                            @error('Responsable')
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Persona que Entrega</label>
                                            <input type="text" class="form-control inputForm" name="Entrega_Nombre" value="{{ $Nombre }}" readonly>
                                            @error('Entrega_Nombre')
                                                    <div class="alert alert-danger"><span>*{{ $message }}</span></div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Técnico que Recibe</label>
                                            <input type="text" class="form-control inputForm" name="Recibe_Nombre" value="{{ $Solicitud->tecnico }}" required>
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
                                    
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">SUBIR MANIFIESTO FIRMADO</label>
                                            <input type="file" class="form-control-file inputForm" name="ScanPDF"></input>
                                            @if ($errors->any())
                                                <div class="invalid-feedback">Por favor, vuelva a cargar el archivo de ser necesario.</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                </div>
                                <!--Campo Oculto para pasar el id de Solicitud -->
                                <input type="hidden" class="form-control inputForm" name="idSolicitud" placeholder="" value="{{ $Solicitud->idSolicitud }}">

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Observaciones</label>
                                            <textarea class="form-control is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto.">{{ $Manifiestos->Observaciones }}</textarea>
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
                                <!--Botón de Regresar -->
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
    document.querySelector('form').addEventListener('submit', function(event) {
        const clienteSelect = document.querySelector('select[name="Cliente"]');
        
        if (clienteSelect.value === "") {
            event.preventDefault(); // Evita que se envíe el formulario
            alert("Por favor, selecciona un cliente."); // Muestra una alerta
            clienteSelect.focus(); // Coloca el foco en el campo de selección
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const clienteSelect = document.querySelector('select[name="Cliente"]');
        const folioInput = document.getElementById('folio');

        clienteSelect.addEventListener('change', function() {
            const cliente = clienteSelect.value;

            if (cliente) {
                // 1. Obtener las primeras 4 letras del nombre del cliente
                const clientePrefix = cliente.substring(0, 4).toUpperCase();

                // 2. Realizar la solicitud AJAX para obtener el conteo actual de registros en la tabla Solicitud
                fetch('{{ route("manifiestos.count") }}')
                    .then(response => response.json())
                    .then(data => {
                        const totalRegistros = data.total; // Sumar 1 para el próximo folio original:const totalRegistros = data.total + 1;
                        const registroCount = totalRegistros.toString().padStart(3, '0'); // Convertir a formato 001, 002, etc.

                        // 3. Obtener el año actual (últimos dos dígitos)
                        const year = new Date().getFullYear().toString().slice(-2);

                        // 4. Crear el folio con el formato PROP-001/24
                        const folio = `${clientePrefix}-${registroCount}/${year}`;
                        folioInput.value = folio;
                    })
                    .catch(error => {
                        console.error('Error al obtener el total de registros:', error);
                    });
            } else {
                folioInput.value = ''; // Resetear el valor si no hay cliente seleccionado
            }
        });
    });

    $(document).ready(function() {
        // Inicializa el switch con las etiquetas personalizadas
        $("[name='SATBMPRO']").bootstrapSwitch({
            onText: 'Sí',
            offText: 'No'
        });

        // Obtiene el valor desde PHP y lo pasa a JavaScript
        var valorDevuelto = "{{ $Manifiestos->SATBMPRO }}"; // "Sí" o "No" desde PHP

        // Ajusta el estado del switch según el valor devuelto
        if (valorDevuelto === "SI") {
            $("[name='SATBMPRO']").bootstrapSwitch('state', true); // Activa el switch
        } else if (valorDevuelto === "NO") {
            $("[name='SATBMPRO']").bootstrapSwitch('state', false); // Desactiva el switch
        }
    });

    // llamar switch boostrap
    $("[name='Renta']").bootstrapSwitch({
        onText: 'Sí',
        offText: 'No'
    });
    /*$("[name='SATBMPRO']").bootstrapSwitch({
        onText: 'Sí',
        offText: 'No'
    });*/
</script>
<script>
/*Prevenir el Enter*/
document.getElementById('manifiestoForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });
</script>

@endsection