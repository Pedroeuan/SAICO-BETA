
@extends('adminlte::page')

@section('title', 'Historial E,S y D')

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
        <h3>Historial de Entradas, Salidas y Devoluciones</h3>
        <br>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-info"></i> Nota</h5>
            Suministro: Activos que ingresaron como nuevo ingreso.<br>
            Salida: Activos que salieron por servicio a través del manifiesto.<br>
            En renta: Activos que salieron en servicio de renta.<br>
            Devolución: Activos que ingresaron por devolución.
            </p>
        </div>
        <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                <tr>
                    <th>Folio</th>
                    <th>Nombre</th>
                    <th>No. Económico</th>
                    <th>Serie</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Fecha Calibración/Caducidad</th>
                    <th>Tierra/Costa Fuera</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historiales as $historial)

                    <tr>
                        <td>{{ $historial->Folio }}</td>
                        <td>{{ $historial->Almacen->General_EyC->Nombre_E_P_BP ?? 'N/A' }}</td>
                        <td>{{ $historial->Almacen->General_EyC->No_economico ?? 'N/A' }}</td>
                        <td>{{ $historial->Almacen->General_EyC->Serie ?? 'N/A' }}</td>
                        <td>{{ $historial->Almacen->General_EyC->Marca ?? 'N/A' }}</td>
                        <td>{{ $historial->Almacen->General_EyC->Modelo ?? 'N/A' }}</td>
                        <td>{{ $historial->Tipo }}</td>
                        <td>{{ $historial->Cantidad }}</td>
                        <td>{{ $historial->formatted_date }}</td>
                        <td>{{ $historial->Tierra_Costafuera }}</td>
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