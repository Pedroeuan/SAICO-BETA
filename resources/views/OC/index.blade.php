
@extends('adminlte::page')

@section('title', 'Orden de Compra')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

<style>
    #tablaJs td {
        text-align: center; /* Centra el contenido horizontalmente */
    }
    #tablaJs th {
        text-align: center; /* Centra el texto del encabezado horizontalmente */
    }
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }

    #tablaJs td {
        text-align: center; /* Centra el contenido horizontalmente */
    }
    #tablaJs th {
        text-align: center; /* Centra el texto del encabezado horizontalmente */
    }    
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }

    
    .tablaheader {
        border-collapse: collapse; 
        border-spacing: 0px;        /* Espacio entre celdas */
        width: 100%;
        text-align: center;
        font-size: 10px;
        }
                    
        /* Aplica el borde a las celdas de la tabla */
        .tablaheader th {
        /*width: 70%;*/
        border: 1px solid black; 
    }

    .dataTables_wrapper {
        overflow-x: auto;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .box {
        display: flex;
        justify-content: center;
    }

    .box-body {
        display: inline-block;
    }

    #tablaJs {
        width: auto !important;
    }
    .toggle-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }

    .toggle-container label {
        font-size: 12px;
        cursor: pointer;
    }

</style>
@endsection

@section('content')
<br>  
<br>
<br>
<!-- form start -->
<form role="form">
    <div class="box ">
            <br>
        <div class="box-body">
        <h3 align="center">Ordenes de Compra Registradas</h3>
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Número de OC</th>
                        <th>Proyecto</th>
                        <th>Lugar/Trabajo</th>
                        <th>Fecha Solicitud</th>
                        <th>Tipo Servicio</th>
                        <th>Estatus</th>
                        <th>OC Original</th>
                        <th>OC AICO</th>
                        <th>Enviar a revisión</th>
                        <th>Revisión con marcas</th>
                        <th>Comentarios de la Revisión</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($OC as $OCS)
                    <tr>
                        <td>{{ $OCS->Contrato }}</td>
                        <td>{{ $OCS->Num_OC }}</td>
                        <td>{{ $OCS->Proyecto}}</td>
                        <td>{{ $OCS->Lugar_trabajo }}</td>
                        @if($OCS->Fecha_solicitud == '2001-01-01')
                                <td scope="row">SIN FECHA ASIGNADA</td>
                            @else
                                <td>{{ $OCS->formatted_date }}</td>                   
                        @endif
                        <td>{{ $OCS->Tipo_servicio }}</td>
                        <td class="estatusOC">{{ $OCS->Estatus }}</td>
                        @if($OCS->OC_archivo == 'ESPERA DE DATO' || $OCS->OC_archivo == 'ESPERA DE DATOS')
                                <td>
                                    <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                </td>
                            @else
                                <td><a class="btn btn-primary" href="{{ asset('storage/' . $OCS->OC_archivo) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a></td>
                            @endif
                        <!--PDF GENERADO-->
                        <td>
                            <a class="btn btn-primary" href="{{ route('OC.PDF', ['id' => $OCS->idOC]) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                        </td>
                        <!--ENVIO A REVISIÓN-->
                        <td>
                            <button type="button" class="btn btn-info btnEnviar" idOC="{{$OCS->idOC}}"><i class="far fa-envelope-open" aria-hidden="true"></i></button>
                        </td>
                        <td>
                            @if (!empty($OCS->Revision_Marcas) && $OCS->Revision_Marcas !== '[]')
                                <a href="{{ route('OC.revisionPDF', ['id' => $OCS->idOC]) }}" target="_blank" class="btn btn-danger" role="button" title="Ver el PDF con las marcas de la revisión">
                                    <i class="fas fa-highlighter" aria-hidden="true"></i>
                                </a>
                            {{--<a href="{{ route('OC.PDFMarcas', ['id' => $OCS->idOC]) }}" class="btn btn-outline-danger long-button" role="button" title="Descargar el PDF con las marcas incrustadas" download="OC_{{ $OCS->idOC }}_marcas.pdf">
                                    <i class="fas fa-download" aria-hidden="true"></i>
                                </a>--}}
                            @else
                                <button type="button" class="btn btn-secondary" disabled title="Esta OC no tiene marcas de revisión">
                                    <i class="fas fa-highlighter" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                        <td>
                            @if($OCS->Revision_OC){{ $OCS->Revision_OC }} @else EN ESPERA . . . @endif
                            @if (!empty($OCS->Comentario_Ventas))
                                <div class="mt-1">
                                    <span class="badge badge-info" title="Ventas agregó un comentario a esta revisión">
                                        <i class="fas fa-comment-dots"></i> Comentario de ventas
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('OC.edit', ['id' => $OCS->idOC]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                        </td>

                        <td>
                            <button type="button" class="btn btn-danger btnEliminarOC" idOC="{{$OCS->idOC}}"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
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
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script>

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


    $(document).on("click", ".btnEnviar", function() {
        var idOC = $(this).attr("idOC");
        var fila = $(this).closest("tr");

        Swal.fire({
            title: "¿Enviar esta OC a revisión?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/OC/enviarRevision/' + idOC,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            fila.find(".estatusOC").text(response.estatus);
                            Swal.fire({
                                title: "Enviado!",
                                text: response.message,
                                icon: "success"
                            });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "No se pudo enviar la OC a revisión, consultar al Administrador.", "error");
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Cancelado", "", "error");
            }
        });
    });

    $(document).on("click", ".btnEliminarOC", function() {
        var idOC = $(this).attr("idOC");
        Swal.fire({
            title: "¿Seguro de eliminar este elemento?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/OC/eliminar/' + idOC,

                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "Eliminado!",
                                text: response.message,
                                icon: "success",
                                didClose: function() {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire("Error!", response.message, "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "No se pudo eliminar la OC puede que este relacionado a un registro, consultar al Administrador.", "error");
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Cancelado", "", "error");
            }
        });
    });
</script>

@endsection