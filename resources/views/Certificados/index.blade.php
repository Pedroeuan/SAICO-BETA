@extends('adminlte::page')

@section('title', 'Certificados Vencidos')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">
@endsection

@section('content')
<br>
<br>
<br>
<form role="form">
    <div class="box">
        <h3 align="center">Certificados Vencidos</h3>
        <br>
        <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <!--<th>Lote / Número Económico</th>-->
                        <th>Número Económico</th>
                        <th>Categoría</th>
                        <th>Última calibración</th>
                        <th>Ver Certificado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificadosConHistorialYAlmacen as $general_eyc)
                        @if($general_eyc->certificados)
                            @foreach ($general_eyc->certificados->historial_certificado as $historial)
                                <tr>
                                    <td>
                                        @if ($general_eyc->Tipo == 'CONSUMIBLES')
                                            {{ $general_eyc->almacen->Lote ?? 'N/A' }}
                                        @else
                                            {{ $general_eyc->No_economico ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td>{{ $general_eyc->Tipo }}</td>
                                    <td>{{ $historial->Ultima_Fecha_calibracion }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ asset('storage/' . $historial->Certificado_Caducado) }}" role="button" target="_blank">
                                            <i class="far fa-file-pdf"></i>
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

    function confirmDelete(id) {
        Swal.fire({
            title: "¿Seguro de eliminar este elemento?",
            showDenyButton: true,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/destroyEquipos/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            Swal.fire("Error!", "No se pudo eliminar el elemento.", "error");
                        }
                    },
                    error: function() {
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
