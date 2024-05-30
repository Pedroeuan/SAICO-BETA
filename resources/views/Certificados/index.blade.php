@extends('adminlte::page')

@section('title', 'Certificados')

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
        <h3 align="center">Historial de certificados</h3>
        <br>
        <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Número Economico/ Lote</th>
                        <th>Tipo (equipo) </th>
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
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let dataTable;

    function initializeDataTable() {
        if ($.fn.DataTable.isDataTable('#tablaJs')) {
            dataTable.destroy();
        }
        dataTable = new DataTable('#tablaJs');
    }

    $(document).ready(function() {
        initializeDataTable();
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
