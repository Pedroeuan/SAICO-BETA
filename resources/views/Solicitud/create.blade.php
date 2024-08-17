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
            <p>Selecciona en el boton de acciones un KIT para solicitarlo</p>
        </div>
            <div class="box-body">
                <table id="tablaKits" class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Prueba</th>
                            <th>Acciones</th>
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
            <p>Selecciona en el boton de acciones un equipo o consumible para solicitarlo</p>
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
                        <th>Hoja de Presentación</th>
                        <th>Acciones</th>
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
                        <td scope="row">
                            @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                    <button type="button" class="btn btn-block btn-outline-success">Disponible</button>
                                @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE')
                                    <button type="button" class="btn btn-block btn-outline-warning">No Disponible</button>
                                @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                    <button type="button" class="btn btn-block btn-outline-danger">Fuera de servicio</button>
                                @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                    <button type="button" class="btn btn-info"><i class="far fa-clock" aria-hidden="true"></i></button>
                            @endif
                        </td>

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
        <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
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
                            <th>Acciones</th>
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
            <p>Elige una fecha de servicio para concluir</p>
        </div>
        <div class="left-align">
            <input type="date" class="form-control inputForm d-inline-block" name="Fecha_Servicio" id="Fecha_Servicio" style="width: auto;">
        </div>
        <br><br>
        <div class="col text-center">
            <button class="btn btn-success" data-toggle="modal" data-target="#modalSolicitarEyC">
                Finalizar solicitud
            </button>
        </div>
        <br>
    </div>
</form>
@stop

@section('js')
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--Ajax-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>

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
    // Agregar elemento de inventario
    $(document).on('click', '.btnAgregarInventario', function() {
        var rowId = $(this).data('id');
        var row = $('#row-' + rowId);
        var nombre = row.find('td:eq(0)').text();
        var numEconomico = row.find('td:eq(1)').text();
        var marca = row.find('td:eq(2)').text();
        var ultimaCalibracion = row.find('td:eq(6)').text();

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
                cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="1" required>`;
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

            // Mostrar mensaje de confirmación
            Swal.fire({
                icon: 'success',
                title: 'Elemento agregado',
                text: 'El elemento ha sido agregado correctamente.',
                confirmButtonText: 'OK'
            });
        });
    });

    // Agregar elemento de kits
    $(document).on('click', '.btnAgregarKit', function() {
        var kitId = $(this).data('id');

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
                                        cantidadInput = `<input type="number" class="form-control" name="cantidad[]" value="${detalle.Cantidad}" required>`;
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
                            $('#tablaAgregados tbody').append(row);
                        });

                        // Mostrar mensaje de confirmación
                        Swal.fire({
                            icon: 'success',
                            title: 'Kit agregado',
                            text: 'El kit ha sido agregado correctamente.',
                            confirmButtonText: 'OK'
                        });
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
        $(this).closest('tr').remove();
    });
});

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
</script>

@endsection
