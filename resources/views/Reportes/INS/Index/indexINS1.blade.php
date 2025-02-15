
@extends('adminlte::page')

@section('title', 'Prueba')

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
<form id="SeleccionarContratoProyecto" action="{{ route('index.ReporteProyectoContrato') }}" method="post" enctype="multipart/form-data" role="form">
    @csrf 
    <div class="box ">
            <br>
        <div class="box-body">
        <h3 align="center">Contrato y Proyecto de los Reportes</h3>

        <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info"></i> Importante</h5>
                <p>Selecciona el Contrato o Proyecto CORRESPONDIENTE a los Reportes</p>
            </div>

            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Nombre del Proyecto</th>
                        <th>Fecha</th>
                        <th>Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reportesDetalles_Generales as $detalle)
                        <tr>
                            <td>{{ $detalle['Contrato'] }}</td>
                            <td>{{ $detalle['Proyecto'] }}</td>
                            <td>{{ $detalle['Fecha'] }}</td>
                            <td>
                                <input type="radio" name="selectedContrato_Proyecto" value="{{ $detalle['Contrato'] }}">
                            </td>
                        </tr>
                    @endforeach
            </tbody>
            </table>
            <p>
            <p>
                <div class="container">
                    <div class="float-right">
                        <button type="submit" class="btn btn-info bg-primary">Seleccionar y Continuar a los Reportes</button>
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

document.getElementById('SeleccionarContratoProyecto').addEventListener('submit', function(event) {
            // Verificar si algún radio button está seleccionado
            const selectedContrato_Proyecto = document.querySelector('input[name="selectedContrato_Proyecto"]:checked');
            if (!selectedContrato_Proyecto) {
                // Si no hay ningún radio button seleccionado, mostrar una alerta y prevenir el envío del formulario
                event.preventDefault();
                Swal.fire({
                    title: 'Advertencia',
                    text: 'Por favor, selecciona un Contrato/Proyecto para poder continuar.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
            }
        });

</script>

@endsection