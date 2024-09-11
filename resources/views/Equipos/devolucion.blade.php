
@extends('adminlte::page')

@section('title', 'Inventario')

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
    .cantidad-input {
            text-align: center; /* Centra el texto dentro del campo de entrada */
        }
</style>
@endsection

@section('content')
<br>  
<br>
<br>
@php 
//dd($datosManifiesto);
@endphp
<h3 align="center">Devoluciones</h3>
<table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
    <thead>
        <tr>
            <th>Folio</th>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Devolver</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datosManifiesto as $dato)
            <tr>
                <td>{{ $dato['Folio'] }}</td>
                <td>{{ $dato['Nombre'] }}</td>
                <td>
                    <!-- Establecer valor máximo con max="{{ $dato['cantidad'] }}" -->
                    <input type="number" name="cantidad[{{ $dato['idGeneral_EyC'] }}]" value="{{ $dato['cantidad'] }}" min="1" max="{{ $dato['cantidad'] }}" class="form-control cantidad-input">
                </td>
                <td>
                    <a href="" class="btn btn-info" role="button"><i class="fas fa-undo-alt" aria-hidden="true"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
<br>
<div class="container d-flex justify-content-center">
    <button type="button" class="btn btn-info bg-success" id="guardarContinuarHerramientas">Concluir Manifiesto</button>
</div>

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
</script>

@endsection