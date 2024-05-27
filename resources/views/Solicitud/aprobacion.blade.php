
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
<form role="form">
    <div class="box">
        <h3 align="center">Aprobar solicitudes</h3>
        <br>
        <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
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
                    <tr>
                        <td>Removedor</td>
                        <td scope="row">Irving alfonso</td>
                        <td scope="row">17/05/24</td>
                        <td scope="row">Pendiente</td>
                        <td>2</td>
                        <td>pieza</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger"><i class="fa fa-times"></i></button>     
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Durometro</td>
                        <td scope="row">mike alfonso</td>
                        <td scope="row">17/05/24</td>
                        <td scope="row">Pendiente</td>
                        <td>1</td>
                        <td>caja</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger"><i class="fa fa-times"></i></button>     
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br><br>
    
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>No.ECO</th>
                    <th>No.Serie</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Comentario</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1.</td>
                    <td>Update software</td>
                    <td>Update software</td>
                    <td>Update software</td>
                    <td>Update software</td>
                    <td>Update software</td>
                    <td>Update software</td>
                    <td>Update software</td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Clean database</td>
                    <td>
                        <div class="progress progress-xs">
                        <div class="progress-bar bg-warning" style="width: 70%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-warning">Clean database</span></td>
                    <td>Clean database</td>
                    <td>Clean database</td>
                    <td>Clean database</td>
                    <td>Clean database</td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>Cron job running</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-primary" style="width: 30%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-primary">30%</span></td>
                    <td>Cron job running</td>
                    <td>Cron job running</td>
                    <td>Cron job running</td>
                    <td>Cron job running</td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>Fix and squish bugs</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 90%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-success">90%</span></td>
                    <td>Fix and squish bugs</td>
                    <td>Fix and squish bugs</td>
                    <td>Fix and squish bugs</td>
                    <td>Fix and squish bugs</td>
                </tr>
            </tbody>
        </table>
    </div>
    <br>
    <button type="button" class="btn btn-success">Crear manifiesto</button>
</form>
<br>
@stop

@section('js')
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let dataTable;

    function initializeDataTable() {
        // Destruir el DataTable si ya está inicializado
        if ($.fn.DataTable.isDataTable('#tablaJs')) {
            dataTable.destroy();
        }
        // Inicializar el DataTable
        dataTable = new DataTable('#tablaJs');
    }

    // Inicializar el DataTable al cargar la página
    $(document).ready(function() {
        initializeDataTable();
    });

    function confirmDelete(id) {
    Swal.fire({
        title: "¿Seguro de eliminar este elemento?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar la solicitud DELETE al servidor
            $.ajax({
                url: '/destroyEquipos/' + id, // URL del endpoint de eliminación
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
                        Swal.fire("Error!", "No se pudo eliminar el elemento.1", "error");
                    }
                },
                error: function() {
                    // Manejar errores de la solicitud AJAX
                    //Swal.fire("Error!", "No se pudo eliminar el elemento.2", "error");
                    Swal.fire({
                        title: "Confirmado!",
                        text: "Equipo Eliminado Correctamente!",
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
        } else if (result.isDenied) {
            Swal.fire("Cancelado", "", "error");
        }
    });
}

</script>
<!--
<script>
    // funcion borrar 
    $(".btnEliminarEquipo").on("click", function(){
        //valor del id a eliminar
        var idGeneral_EyC = $(this).attr("idGeneral_EyC");
        console.log(idGeneral_EyC);
            Swal.fire({
                title: "Seguro de eliminar este elemento?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Sí",
                denyButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Eliminado!", "", "success");
                } else if (result.isDenied) {
                    Swal.fire("Cancelado", "", "error");
                }
            });
    })

    //mostrar datatable
    new DataTable('#tablaJs');
</script>-->

@endsection

