@extends('adminlte::page')

@section('title', 'Equipos')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

@endsection

@section('content')
<br>  
<br>
<br>
<!-- form start -->
    <form id="AprobacionForm" action="{{ route('solicitudplus.manifiestoplus', ['id' => $Solicitud->idSolicitud]) }}" method="post" enctype="multipart/form-data" role="form">
    @csrf 
    <h3 >Formulario para aprobar solicitud de equipos y consumibles</h3>
    <br>
    <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Importante</h5>
            <p>Selecciona en el boton de acciones para agregar un equipo o consumible a la solicitud</p>
        </div>
        <div class="box">
            <br>
            <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Num. Económico</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>NS</th>
                        <th>Stock</th>
                        <th>Disponibilidad</th>
                        <th>Fecha calibración</th>
                        <th>Hoja de Presentación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificados as $general_eyc)
                        <tr id="row-{{ $general_eyc->idGeneral_EyC }}">
                        @if($general_eyc)
                            <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                            <td scope="row">{{$general_eyc->No_economico}}</td>
                            <td scope="row">{{$general_eyc->Marca}}</td>
                            <td scope="row">{{$general_eyc->Modelo}}</td>
                            <td scope="row">{{$general_eyc->Serie}}</td>
                            <td scope="row">{{$general_eyc->almacen->Stock}}</td>
                            @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                    <td scope="row"><button type="button" class="btn btn-block btn-outline-success">Disponible <i class="fa fa-check" aria-hidden="true"></i></td>
                                @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE')
                                    <td scope="row"><button type="button" class="btn btn-block btn-outline-warning">No Disponible <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                    <td scope="row"><button type="button" class="btn btn-block btn-outline-danger">Fuera de servicio <i class="fa fa-ban" aria-hidden="true"></i></td>
                                @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                    <td scope="row"><button type="button" class="btn btn-block btn-outline-info">Espera de Dato <i class="far fa-clock" aria-hidden="true"></i></td>
                            @endif
                        @endif 
                            @if($general_eyc->certificados)
                                @if($general_eyc->Tipo =='EQUIPOS' || $general_eyc->Tipo == 'BLOCK Y PROBETA')
                                        @if($general_eyc->certificados->Fecha_calibracion == '2001-01-01')
                                            <td scope="row">SIN FECHA ASIGNADA</td>
                                        @else
                                            <td scope="row">{{$general_eyc->certificados->Fecha_calibracion}}</td>
                                        @endif
                                    @else
                                        <td scope="row">N/A</td>
                                @endif
                                <td scope="row"> 
                                    @if ($general_eyc->Foto != 'ESPERA DE DATO')
                                        <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>                                              
                                                @elseif($general_eyc->Foto == 'ESPERA DE DATO')  
                                            <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
                                    @endif
                                </td>
                                <td>
                            @endif
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btnAgregar" data-id="{{ $general_eyc->idGeneral_EyC }}" data-id-solicitud="{{ $Solicitud->idSolicitud }}"><i class="fas fa-plus-circle" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <br>
    <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> ¡Elementos Previos!</h5>
        Estos son los elementos que te han solicitado Previamente, NO apareceran en el formato final
        </div>
    <br>
    <div class="card-body">
        <table id="TablaSolicitudPrevia" class="table table-bordered" >
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
                        $general = $generalEyC;
                        $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                        $fechaCalibracion = $general->Certificados->Fecha_calibracion;
                        //dump($general->idGeneral_EyC);
                    @endphp
                    <tr id="row-{{ $detalle->idDetalles_Solicitud }}">
                    <input type="hidden" name="idDetalles_Solicitud[]" value="{{ $detalle->idDetalles_Solicitud }}" readonly>
                        <td>{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                        <td>{{ $general->No_economico ?? 'N/A' }}</td>
                        <td>{{ $general->Marca ?? 'N/A' }}</td>
                        @if($general->Certificados)
                                    @if($general->Tipo=='EQUIPOS' || $general->Tipo=='BLOCK Y PROBETA')
                                            @if($general->Certificados->Fecha_calibracion=='2001-01-01')
                                                    <td scope="row">SIN FECHA ASIGNADA</td>
                                                @else
                                                    <td scope="row">{{$general->Certificados->Fecha_calibracion}}</td>
                                            @endif
                                        @else
                                            <td scope="row">N/A</td>
                                    @endif
                            @endif

                                <td scope="row">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="Cantidad[{{ $detalle->idDetalles_Solicitud }}]" value="{{ $detalle->Cantidad ?? '1' }}" readonly>
                                    </div>
                                </td>
                                <td scope="row">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="Unidad[{{ $detalle->idDetalles_Solicitud }}]" value="{{ $detalle->Unidad ?? 'ESPERA DE DATO' }}" readonly>
                                    </div>
                                </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <br>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> ¡Bien hecho!</h5>
            Estos son los elementos que te han solicitado
            </div>
        <br>
        <div class="card-body">
            <table id="tablaAgregados" class="table table-bordered" >
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>No.ECO</th>
                        <th>Marca</th>
                        <th>Ultima calibración</th>
                        <th>Cantidad</th>
                        <th>Unidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    <br>

                        <!--Campo Oculto para pasar el id de Solicitud /hidden-->
                            @if($Manifiestos)
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Cliente</label>-->
                                            <input type="hidden" class="form-control inputForm" name="Cliente"  placeholder="Ejemplo: PROPETROL" value="{{ $Manifiestos->Cliente }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Folio</label>-->
                                            <input type="hidden" class="form-control inputForm" name="Folio" placeholder="Ejemplo: PROP-040/24" value="{{ $Manifiestos->Folio }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Destino</label>-->
                                            <input type="hidden" class="form-control inputForm" name="Destino" placeholder="Ejemplo: PATIO DE FABRICACIÓN PROTEXA" value="{{ $Manifiestos->Destino }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Fecha de Salida</label>-->
                                            <input type="hidden" class="form-control inputForm" name="Fecha_Salida" value="{{ $Solicitud->Fecha }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Trabajo</label>-->
                                            <input type="hidden" class="form-control inputForm" name="Trabajo" placeholder="Ejemplo: Dureza" value="{{ $Manifiestos->Trabajo }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Puesto</label>-->
                                            <input type="hidden" class="form-control inputForm" name="Puesto" placeholder="Ejemplo: TEC. PND" value="{{ $Manifiestos->Puesto }}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <!--<label class="col-form-label" for="inputSuccess">Responsable</label>-->
                                            <input type="hidden" class="form-control inputForm" name="Responsable" placeholder="Ejemplo: ALFREDO MARTINEZ TORRRES" value="{{ $Manifiestos->Responsable }}" readonly>
                                        </div>
                                    </div>
                                    
                                    <!--Campo Oculto para pasar el id de Solicitud -->
                                    <!--<label class="col-form-label" for="inputSuccess">idSolicitud</label>-->
                                    <input type="hidden" class="form-control inputForm" name="idSolicitud" placeholder="" value="{{ $Solicitud->idSolicitud }}" readonly>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                        <!--<label class="col-form-label" for="inputSuccess">Observaciones</label>-->
                                        <textarea class="form-control is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo Equipo con bateria INCLUYE: Cables con puntas de contacto." style="display: none;" readonly>{{ $Manifiestos->Observaciones }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
        <button type="submit" class="btn btn-success" id="btnFinalizaraprobacion">Crear manifiesto</button>
    </form>
<br>
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
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>

<script>
    /*Solicitud*/
    let table = new DataTable('#tablaJs', {
    // options
    language: {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                }
});

$(document).ready(function() {
    $('#btnFinalizaraprobacion').click(function(event) {
        // Verificar si hay filas en la tabla
        if ($('#tablaAgregados tbody tr').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tabla vacía',
                text: 'Debes agregar al menos un elemento antes de finalizar la solicitud.',
                confirmButtonText: 'Entendido'
            });
            event.preventDefault(); // Prevenir el envío del formulario
        } else {
            // Si hay elementos en la tabla, puedes continuar con el envío del formulario
            // Si usas un formulario real, aquí podrías hacer el submit
        }
    });
});

function consultarCantidadAlmacen(id, callback) {
    $.ajax({
        url: '/Obtener/CantidadAlmacen/' + id,
        method: 'GET',
        success: function(data) {
            callback(null, data.Cantidad); // Asume que la respuesta contiene un campo "Cantidad"
        },
        error: function(error) {
            callback(error);
        }
    });
}

$(document).ready(function() {
    // Delegación de eventos para el botón "Agregar"
    $('#tablaJs').on('click', '.btnAgregar', function() {
        var rowId = $(this).data('id');
        var row = $('#row-' + rowId);
        var nombre = row.find('td:eq(0)').text();
        var numEconomico = row.find('td:eq(1)').text();
        var marca = row.find('td:eq(2)').text();
        var ultimaCalibracion = row.find('td:eq(7)').text();

        if ($('#tablaAgregados tbody tr').find(`input[name="general_eyc_id[]"][value="${rowId}"]`).length > 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Elemento duplicado',
                text: 'El elemento ya está agregado.',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        consultarCantidadAlmacen(rowId, function(error, Cantidad) {
            if (error || Cantidad <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Sin Stock en Almacen',
                    confirmButtonText: 'OK'
                });
                return;
            }

            if (ultimaCalibracion === '2001-01-01') {
                ultimaCalibracion = 'SIN FECHA ASIGNADA';
            }

            var cantidadInput;
            if (Cantidad === 1) {
                cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="1" readonly>`;
            } else {
                cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="1" min="1" max="${Cantidad}" required>`;
            }

            var newRow = `
                <tr>
                    <td>${nombre}</td>
                    <td>${numEconomico}</td>
                    <td>${marca}</td>
                    <td>${ultimaCalibracion}</td>
                    <td>${cantidadInput}</td>
                    <td><input type="text" class="form-control" name="unidad[]" value="EN ESPERA DE DATOS" required></td>
                    <td>
                        <input type="hidden" name="general_eyc_id[]" value="${rowId}">
                        <button type="button" class="btn btn-danger btnQuitarElemento"><i class="fas fa-minus-circle"></i></button>
                    </td>
                </tr>
            `;

            $('#tablaAgregados tbody').append(newRow);

            // Validar la cantidad al cambiar su valor
            $('input[name="cantidad[]"]').last().on('input', function() {
                var inputValue = parseInt($(this).val());
                if (inputValue > Cantidad) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cantidad excedida',
                        text: `La cantidad máxima permitida es ${Cantidad}.`,
                        confirmButtonText: 'Entendido'
                    });
                    $(this).val(Cantidad);
                }
            });

            // Mostrar mensaje de confirmación
            Swal.fire({
                icon: 'success',
                title: 'Elemento agregado',
                text: 'El elemento ha sido agregado correctamente.',
                confirmButtonText: 'OK'
            });
        });
    });

    // Delegación de eventos para el botón "Quitar"
    $('#tablaAgregados').on('click', '.btnQuitarElemento', function() {
        $(this).closest('tr').remove();
    });
});



</script>
@endsection

