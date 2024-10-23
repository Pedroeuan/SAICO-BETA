@extends('adminlte::page')

@section('title', 'Solicitudes')

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
</style>
@endsection

@section('content')
<br>  
<br>
<br>
<!-- form start -->
<form role="form">
    <div class="box">
        <h3 align="center">Historial de solicitudes de equipos y consumibles</h3>
        <br>
        <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        @if($rol == 'Equipos' || $rol == 'Super Administrador' || $rol == 'Administrador')
                            <th>Técnico</th>
                            <th>Folio</th>
                            <th>Fecha de servicio</th>
                            <th>Estatus</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                            <th>Ver PDF</th>
                            <th>Complementar</th>
                            <th>Devolver</th>
                        @else
                            <th>Técnico</th>
                            <th>Folio</th>
                            <th>Fecha de servicio</th>
                            <th>Estatus</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($Solicitudes as $solicitud)
@php 
//dump($solicitud->idSolicitud);
@endphp
                        <tr>
                            <td scope="row">{{$solicitud->tecnico}}</td>
                            <td scope="row">{{$solicitud->folio}}</td>
                            <td scope="row">{{$solicitud->formatted_date}}</td>
                            <td scope="row">{{$solicitud->Estatus}}</td>
                            @if($rol == 'Equipos' || $rol == 'Super Administrador' || $rol == 'Administrador')
                                @if($solicitud->Estatus == 'PENDIENTE' || $solicitud->Estatus == 'APROBADO')
                                    <div class="btn-group">
                                        <td>
                                            <a href="{{ route('solicitud.edit', ['id' => $solicitud->idSolicitud]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-danger btnEliminarSolicitud" id-Solicitud="{{$solicitud->idSolicitud}}"><i class="fa fa-times" aria-hidden="true"></i></button>
                                        </td>
<!-- -->
                                        <td>
                                            <span class="btn btn-primary" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                <i class="far fa-file-pdf"></i>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="btn btn-primary" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                <i class="fas fa-plus-square"></i>
                                            </span>
                                        </td>

                                        <td>
                                            <a class="btn btn-info" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;"><i class="fas fa-undo-alt" aria-hidden="true"></i></a>
                                        </td>
                                    </div>
                                @elseif($solicitud->Estatus == 'CONCLUIDO')
                                    <div class="btn-group">
                                        <td>
                                            <a class="btn btn-warning" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                <i class="fas fa-pencil-alt"></i></a>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-danger"style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                <i class="fa fa-times"></i></button>
                                        </td>

                                        <td>
                                            <a class="btn btn-primary" href="{{ route('Manifiesto.pdf', ['id' => $solicitud->idSolicitud]) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                                            <a class="btn btn-primary" href="{{ route('Manifiesto.NewFormat.pdf', ['id' => $solicitud->idSolicitud]) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                                            
                                        </td>

                                        <td>
                                            <span class="btn btn-primary" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                <i class="fas fa-plus-square"></i>
                                            </span>
                                        </td>

                                        <td>
                                            <a class="btn btn-info" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;"><i class="fas fa-undo-alt" aria-hidden="true"></i></a>
                                        </td>
                                    </div>
                                @else
                                    <div class="btn-group">
                                        <td>
                                            <a href="{{ route('solicitud.edit', ['id' => $solicitud->idSolicitud]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                        </td>
                                        <td>        
                                            <button type="button" class="btn btn-danger btnEliminarSolicitud" id-Solicitud="{{$solicitud->idSolicitud}}"><i class="fa fa-times" aria-hidden="true"></i></button>          
                                        </td>
                                        <td>
                                            <a class="btn btn-primary" href="{{ route('Manifiesto.pdf', ['id' => $solicitud->idSolicitud]) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                                                @foreach ($solicitud->detalles_solicitud as $detalle)
                                                    @foreach ($detalle->manifiesto as $manifiestos)
                                                        @if($manifiestos->ScanPDF != '')
                                                                <a class="btn btn-primary" href="{{ asset('storage/' . $manifiestos->ScanPDF) }}" role="button" target="_blank">
                                                                    <i class="far fa-file-pdf"></i>
                                                                </a>                                              
                                                            @else
                                                                <span class="btn btn-primary" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                                    <i class="far fa-file-pdf"></i>
                                                                </span>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                        </td>
                                        @if(!$solicitud->hidePlus)
                                            <td>
                                                <a href="{{ route('solicitudplus.edit', ['id' => $solicitud->idSolicitud]) }}" class="btn btn-success" role="button"><i class="fas fa-plus-square" aria-hidden="true"></i></a>
                                            </td>

                                            <td>
                                                <a href="{{ route('devolucion.EyC', ['id' => $solicitud->idSolicitud]) }}" class="btn btn-info" role="button"><i class="fas fa-undo-alt" aria-hidden="true"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <span class="btn btn-primary" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                    <i class="fas fa-plus-square"></i>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="btn btn-primary" style="background-color: gray; border-color: gray; color: white; cursor: not-allowed;">
                                                    <i class="fas fa-undo-alt"></i>
                                                </span>
                                            </td>
                                        @endif 
                                    </div>
                                @endif

                            @endif
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

    $(document).on("click", ".btnEliminarSolicitud", function() {
    //valor del id a eliminar
    var idSolicitud = $(this).attr("id-Solicitud");
    Swal.fire({
        title: "Seguro de eliminar este elemento?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar la solicitud DELETE al servidor
            $.ajax({
                url: '/solicitudes/eliminar/' + idSolicitud, // URL del endpoint de eliminación
                type: 'DELETE', // Método HTTP DELETE
                data: {
                    _token: '{{ csrf_token() }}' // Token CSRF si es necesario
                },
                success: function(response) {
                    // Manejar la respuesta del servidor si es necesario
                    if (response.success) {
                        // Si la eliminación fue exitosa, hacer algo (por ejemplo, recargar la página)
                        location.reload();
                    } else {
                        // Si ocurrió un error durante la eliminación, mostrar un mensaje de error
                        Swal.fire("Error!", "No se pudo eliminar el elemento.", "error");
                    }
                },
                error: function() {
                     // Manejar errores de la solicitud AJAX
                    //Swal.fire("Error!", "No se pudo eliminar el elemento.2", "error");
                    Swal.fire({
                        title: "Confirmado!",
                        text: "Solicitud Eliminado Correctamente!",
                        icon: "success",
                        didClose: function() {
                            location.reload();
                            }
                        });
                    // Esperar 3 segundos (3000 milisegundos) antes de recargar la página
                        /*  setTimeout(function() {
                            location.reload();
                        }, 3000);*/
                }
            });
        } 
        else if (result.isDenied) {
            Swal.fire("Cancelado", "", "error");
        }
    });
});
</script>

@endsection