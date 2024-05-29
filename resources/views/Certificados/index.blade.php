@extends('adminlte::page')

@section('title', 'Certificados')

@section('css')
<!-- datatable -->
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
                        <th>Número Economico/Lote</th>
                        <th>Tipo (equipo)</th>
                        <th>Última calibración</th>
                        <th>Ver Certificado</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificadosConHistorialYAlmacen as $general_eyc)
                        @if($general_eyc->certificados)
                            @foreach ($general_eyc->certificados->historial_certificado as $historial)
                                <tr>
                                    <td>{{$general_eyc->No_economico}}</td>
                                    <td>{{$general_eyc->Tipo}}</td>
                                    <td>{{$historial->Ultima_Fecha_calibracion}}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ asset('storage/' . $historial->Certificado_Caducado) }}" role="button" target="_blank">
                                            <i class="fa fa-eye">
                                            </i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>
@stop

@section('js')
<!-- datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!-- sweet alert -->
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
                        // Swal.fire("Error!", "No se pudo eliminar el elemento.2", "error");
                        Swal.fire({
                            title: "Confirmado!",
                            text: "Equipo Eliminado Correctamente!",
                            icon: "success",
                            didClose: function() {
                                location.reload();
                            }
                        });
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Cancelado", "", "error");
            }
        });
    }
</script>
@endsection
