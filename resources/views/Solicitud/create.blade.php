@extends('adminlte::page')

@section('title', 'Solicitud')

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
<form id="solicitudForm" role="form" method="POST" action="{{route('solicitudes.storeSolicitud')}}" enctype="multipart/form-data">
    @csrf
    <h2>Solicitud de equipos y consumibles</h2>
    <div class="box">
        <br>
        <br>
        <div class="box">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Importante</h5>
            <p>Selecciona el boton de "Solicitar" <button type="button" class="btn btn-success"><i class="fas fa-plus-circle"></i></button> para agregar un KIT
            </p>
        </div>
            <div class="box-body">
                <table id="tablaKits" class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Prueba</th>
                            <th>Solicitar KIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kitsConDetalles as $kits)
                        <tr>
                            @if($kits)
                                <td scope="row">{{$kits->Nombre}}</td>
                                <td scope="row">{{$kits->Prueba}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btnAgregarKit" data-id="{{ $kits->idKits }}"><i class="fas fa-plus-circle"></i></button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br><br>
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Importante</h5>
            <p>Selecciona en el boton de "Solicitar" <button type="button" class="btn btn-success"><i class="fas fa-plus-circle"></i></button> para agregar un equipo o consumible</p>
        </div>
        <div class="box-body">
            <table id="tablaInventario" class="table table-bordered table-striped dt-responsive tablas">
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
                        <th>Ver Presentación</th>
                        <th>Solicitar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificados as $general_eyc)
                    <tr id="row-{{ $general_eyc->idGeneral_EyC }}">
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

                        <td scope="row">
                        @if($general_eyc->certificados)
                            @if($general_eyc->Tipo =='EQUIPOS' || $general_eyc->Tipo == 'BLOCK Y PROBETA')
                                @if($general_eyc->certificados->Fecha_calibracion == '2001-01-01')
                                        SIN FECHA ASIGNADA
                                    @else
                                        {{$general_eyc->certificados->Fecha_calibracion}}
                                @endif
                            @else
                                N/A
                            @endif
                        @endif
                        </td>

                        <td scope="row">
                            @if ($general_eyc->Foto != 'ESPERA DE DATO')
                                    <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                                @elseif($general_eyc->Foto == 'ESPERA DE DATO')
                                    <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                            @endif
                        </td>

                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btnAgregarInventario" data-id="{{ $general_eyc->idGeneral_EyC }}"><i class="fas fa-plus-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br><br>
        <!-- Alerta (oculta por defecto) -->
        <div id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h5><i class="icon fas fa-check"></i> ¡Bien hecho!</h5>
            Estos son los elementos que has solicitado
        </div>
        <div class="box">
            <br>
            <div class="box-body">
                <table id="tablaAgregados" class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Num. Económico</th>
                            <th>Marca</th>
                            <th>Ultima Calibración</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- Elementos agregados se mostrarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
        <br><br>
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Importante</h5>
            <p>Elige una fecha de servicio para concluir la solicitud</p>
        </div>
        <div class="left-align">
            <input type="date" class="form-control inputForm d-inline-block" name="Fecha_Servicio" id="Fecha_Servicio" style="width: auto;">
        </div>
        <br><br>
        <div class="col text-center">
            <button class="btn btn-success" data-toggle="modal" data-target="#modalSolicitarEyC" id="btnFinalizarSolicitud">
                Finalizar solicitud
            </button>
        </div>
        <br>
    </div>
</form>
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
<!--Ajax-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let tableKits = new DataTable('#tablaKits', {
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

let tableInventario = new DataTable('#tablaInventario', {
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

$(document).on('input', 'input[name="cantidad[]"]', function() {
    var max = $(this).attr('max');
    var value = $(this).val();

    if (parseInt(value) > parseInt(max)) {
        $(this).val(max);

        // Mostrar mensaje de advertencia
        Swal.fire({
            icon: 'warning',
            title: 'Cantidad excedida',
            text: `La cantidad máxima permitida es ${max}.`,
            confirmButtonText: 'Entendido'
        }); 
    }
});

$(document).ready(function() {
    $('#btnFinalizarSolicitud').click(function(event) {
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
    $('#solicitudForm').on('submit', function(event) {
        var fechaServicio = $('#Fecha_Servicio').val();

        if (!fechaServicio) {
            event.preventDefault(); // Previene el envío del formulario
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, completa el campo de Fecha de Servicio antes de enviar la solicitud.'
            });
        }
    });
});

$(document).ready(function() {
    let kitDuplicadoDetectado = false; // Bandera para detectar duplicados

    // Agregar elemento de inventario
    $(document).on('click', '.btnAgregarInventario', function() {
        var rowId = $(this).data('id');
        var row = $('#row-' + rowId);
        var nombre = row.find('td:eq(0)').text();
        var numEconomico = row.find('td:eq(1)').text();
        var marca = row.find('td:eq(2)').text();
        var ultimaCalibracion = row.find('td:eq(7)').text();
        var nombresDuplicados = []; // Array para almacenar los nombres de los elementos duplicados

        // Verificar si el elemento ya está agregado
        if ($('#tablaAgregados tbody tr').find(`input[name="general_eyc_id[]"][value="${rowId}"]`).length > 0) {
            nombresDuplicados.push(nombre); // Agregar el nombre del elemento duplicado al array

            Swal.fire({
                icon: 'warning',
                title: 'Elemento duplicado',
                text: `El elemento "${nombre}" ya está agregado.`,
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
                cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="1" max="1" readonly>`;
            } else {
                cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="1" max="${Cantidad}" required>`;
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
            // Mostrar la alerta
            alertBox.style.display = 'block';
            // Mostrar mensaje de confirmación
            Swal.fire({
                icon: 'success',
                title: `Elemento "${nombre}" agregado`,
                text: `El elemento "${nombre}" ha sido agregado correctamente.`,
                confirmButtonText: 'OK'
            });
        });
    });

    // Agregar elemento de kits
    $(document).on('click', '.btnAgregarKit', function() {
        var kitId = $(this).data('id');
        var nombresDuplicados = []; // Array para almacenar los nombres de los elementos duplicados

        $.ajax({
            url: '/Obtener/Kits/' + kitId,
            method: 'GET',
            success: function(detallesKits) {
                var promises = detallesKits.map(function(detalle) {
                    return new Promise(function(resolve, reject) {
                        consultarCantidadAlmacen(detalle.idGeneral_EyC, function(error, cantidad) {
                            if (error || cantidad <= 0) {
                                reject('Kit Sin Stock suficiente en el Almacen');
                                return;
                            }

                            // Verificar duplicados antes de agregar
                            var elementoExistente = $('#tablaAgregados tbody').find(`input[name="general_eyc_id[]"][value="${detalle.idGeneral_EyC}"]`).length > 0;
                            if (elementoExistente) {
                                // Obtener el nombre del elemento duplicado
                                $.ajax({
                                    url: '/Obtener/generaleyc/' + detalle.idGeneral_EyC,
                                    method: 'GET',
                                    success: function(generalEyC) {
                                        nombresDuplicados.push(generalEyC.Nombre_E_P_BP); // Agregar el nombre al array
                                        resolve(null); // Resolver con null para no agregar el elemento duplicado
                                    },
                                    error: function() {
                                        reject('Error al obtener detalles de General_EyC.');
                                    }
                                });
                                return;
                            }

                            $.ajax({
                                url: '/Obtener/generaleyc/' + detalle.idGeneral_EyC,
                                method: 'GET',
                                success: function(generalEyC) {
                                    var Fecha_calibracion = generalEyC.certificados.Fecha_calibracion;
                                    if (Fecha_calibracion === '2001-01-01') {
                                        Fecha_calibracion = 'SIN FECHA ASIGNADA';
                                    }

                                    var cantidadInput;
                                    if (cantidad === 1) {
                                        cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="${detalle.Cantidad}" readonly>`;
                                    } else {
                                        cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="${detalle.Cantidad}" max="${cantidad}"  required>`;
                                    }

                                    var newRow = `
                                        <tr>
                                            <td>${generalEyC.Nombre_E_P_BP}</td>
                                            <td>${generalEyC.No_economico}</td>
                                            <td>${generalEyC.Marca}</td>
                                            <td>${Fecha_calibracion}</td>
                                            <td>${cantidadInput}</td>
                                            <td><input type="text" class="form-control" name="unidad[]" value="${detalle.Unidad}" required></td>
                                            <td>
                                                <input type="hidden" name="general_eyc_id[]" value="${detalle.idGeneral_EyC}">
                                                <button type="button" class="btn btn-danger btnQuitarElemento"><i class="fas fa-minus-circle"></i></button>
                                            </td>
                                        </tr>
                                    `;
                                    resolve(newRow);
                                },
                                error: function() {
                                    reject('Error al obtener detalles de General_EyC.');
                                }
                            });
                        });
                    });
                });

                Promise.all(promises)
                    .then(function(rows) {
                        rows.forEach(function(row) {
                            if (row) {
                                $('#tablaAgregados tbody').append(row);
                            }
                        });
                        // Mostrar la alerta
                        alertBox.style.display = 'block';
                        // Mostrar mensaje de confirmación
                        Swal.fire({
                            icon: 'success',
                            title: 'Kit agregado',
                            text: 'El kit ha sido agregado correctamente.',
                            confirmButtonText: 'OK'
                        });

                        // Mostrar mensaje de duplicados si existen
                        if (nombresDuplicados.length > 0) {
                            var mensajeDuplicados = nombresDuplicados.join(', ');
                            Swal.fire({
                                icon: 'warning',
                                title: 'Elementos duplicados',
                                text: `Los siguientes elementos ya estaban agregados: ${mensajeDuplicados}`,
                                confirmButtonText: 'Entendido'
                            });
                        }
                    })
                    .catch(function(errorMessage) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al obtener detalles de Kits.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Eliminar elemento
    $(document).on('click', '.btnQuitarElemento', function() {
        var row = $(this).closest('tr');
        var nombreElemento = row.find('td').eq(0).text(); // Asume que el nombre del elemento está en la primera celda

        Swal.fire({
            title: "¿Seguro de eliminar este elemento?",
            text: `¿Deseas eliminar el elemento "${nombreElemento}"?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                row.remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Elemento Eliminado',
                    text: `El elemento "${nombreElemento}" ha sido eliminado correctamente.`,
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cancelado',
                    text: `El elemento "${nombreElemento}" no ha sido eliminado.`,
                });
            }
        });
    });
});


</script>

@endsection
