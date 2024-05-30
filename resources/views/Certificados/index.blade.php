
@extends('adminlte::page')

@section('title', 'Certificados')

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
        <h3 align="center">Historial de certificados</h3>
        <br>
        <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>No.ECO</th>
                        <th>Categoría</th>
                        <th>Última calibración</th>
                        <th>Ver Certificado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">23444323</td>
                        <td scope="row">Block</td>
                        <td scope="row">12/05/24</td>
                        <td>
                            <div class="btn-group">
                                <a  class="btn btn-primary" href="{{ route('solicitud.aprobacion') }}" role="button"><i class="fa fa-eye"></i></a>     
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>
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

