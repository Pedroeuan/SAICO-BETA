@extends('adminlte::page')

@section('title', 'Asignación Manifiesto')

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

</style>
@endsection

@section('content')
<br>  
<br>
<br>
<!-- form start -->
<form id="AsignaManifiestoForm" action="{{ route('Create.Reporte') }}" method="post" enctype="multipart/form-data" role="form">
    @csrf 
    <div class="card-body row">
        <div class="box">
            <h3 align="center">Asignación del Manifiesto al Reporte</h3>
            <br>
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info"></i> Importante</h5>
                <p>Selecciona el manifiesto CORRESPONDIENTE al Reporte</p>
            </div>
            <div class="box-body">
                <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                        <tr>
                            <th>Técnico</th>
                            <th>Folio</th>
                            <th>Fecha de servicio</th>
                            <th>Estatus</th>
                            <th>PDF Generado</th>
                            <th>Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($Solicitudes as $solicitud)
                            <tr>
                                <td scope="row">{{$solicitud->tecnico}}</td>
                                <td scope="row">{{$solicitud->folio}}</td>
                                <td scope="row">{{$solicitud->formatted_date}}</td>
                                <td scope="row">{{$solicitud->Estatus}}</td>

                                <div class="btn-group">
                                    <!--PDF GENERADO-->
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('Manifiesto.NewFormat.pdf', ['id' => $solicitud->idSolicitud]) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                                    </td>

                                    <td>
                                        <input type="radio" name="selectedSolicitud" value="{{ $solicitud->idSolicitud }}">
                                    </td>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p>
                <p>
                <div class="col-sm-4">
                    <div class="form-group">
                        <!--<label class="col-form-label" for="inputSuccess">Folio</label>-->
                        <input type="hidden" class="form-control inputForm" name="idPrueba" placeholder="Ejemplo: PROP-040/24" value="{{ $idPrueba }}" readonly>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <!--<label class="col-form-label" for="inputSuccess">Folio</label>-->
                        <input type="hidden" class="form-control inputForm" name="idNorma_Codigo" placeholder="Ejemplo: PROP-040/24" value="{{ $idNorma_Codigo }}" readonly>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <!--<label class="col-form-label" for="inputSuccess">Folio</label>-->
                        <input type="hidden" class="form-control inputForm" name="idFormato" placeholder="Ejemplo: PROP-040/24" value="{{ $idFormato }}" readonly>
                    </div>
                </div>
                <div class="container">
                    <div class="float-right">
                        <button type="submit" class="btn btn-info bg-primary">Guardar y Continuar al Reporte</button>
                    </div>

                    <!--<div class="float-left">
                        <button type="button" class="btn btn-info bg-success" id="guardarContinuarEquipos">Guardar y continuar</button>
                    </di>-->
                </div>
            </div>
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