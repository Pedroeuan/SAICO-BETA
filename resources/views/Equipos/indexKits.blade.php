
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
    <div class="box ">
        <h3 align="center">KITS</h3>
            <br>
        <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Prueba</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!--Tabla body-->
                    @foreach ($kitsConDetalles as $kits)
                        <tr>
                        @if($kits)
                            <td scope="row">{{$kits->Nombre}}</td>
                            <td scope="row">{{$kits->Prueba}}</td>
                            <td>
                            <div class="btn-group">
                                <a href="{{ route('edicion.editKits', ['id' => $kits->idKits]) }}" class="btn btn-light" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                <button type="button" class="btn btn-light btnEliminarEquipo" idDetallesKits="{{$kits->idKits}}"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    new DataTable('#tablaJs');

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
    $(".btnEliminarEquipo").on("click", function(){
    //valor del id a eliminar
    var idDetallesKits = $(this).attr("idDetallesKits");

    Swal.fire({
        title: "Seguro de ELIMINAR este elemento?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar la solicitud DELETE al servidor
            $.ajax({
                url: '/eliminar/Kits/' + idDetallesKits, // URL del endpoint de eliminación
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
                        Swal.fire("Error!", "No se pudo ELIMINAR el elemento.", "error");
                    }
                },
                error: function() {
                     // Manejar errores de la solicitud AJAX
                    //Swal.fire("Error!", "No se pudo eliminar el elemento.2", "error");
                    Swal.fire({
                        title: "Confirmado!",
                        text: "Elemento ELIMINADO Correctamente!",
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