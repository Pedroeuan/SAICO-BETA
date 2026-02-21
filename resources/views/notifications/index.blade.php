@extends('adminlte::page')

@section('title', 'Notificaciones')

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

    .notificacion-link {
        text-decoration: none; /* quita el subrayado */
        color: inherit;        /* toma el color del td */
        cursor: pointer;       /* opcional, indica que es clicable */
    }

    .notificacion-link:hover {
        color: #0d17a0ff;        /* opcional: cambia de color al pasar el mouse */
        text-decoration: underline; /* opcional: solo subraya al hacer hover */
    }

    .notificacion-disabled {
        cursor: default !important;
        opacity: 0.9;
    }
</style>
@endsection

@section('content')
<br>
<br>
<br>
<div class="container">
    @if($notificaciones->isEmpty())
    <div class="text-center my-4">
        <i class="fas fa-bell-slash fa-3x text-muted"></i>
        <p class="mt-2 text-muted">No hay notificaciones para este rol.</p>
        <button onclick="location.reload()" class="btn btn-primary btn-sm">Actualizar</button>
    </div>
    @else
    <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
            <thead>
                <tr>
                    <th>Mensaje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notificaciones as $notificacion)
                @php
                    $urlNotificacion = trim((string) ($notificacion->url ?? ''));
                    $sinAccion = $urlNotificacion === '' || $urlNotificacion === '#' || str_contains($urlNotificacion, '/profile');
                @endphp
                <tr
                    class="notificacion-link {{ $sinAccion ? 'notificacion-disabled' : '' }}"
                    data-url="{{ $urlNotificacion }}"
                    data-no-action="{{ $sinAccion ? '1' : '0' }}"
                >
                    <td>{{ $notificacion->Mensaje_Largo }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
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

document.querySelectorAll('#tablaJs tbody tr.notificacion-link').forEach((row) => {
    row.addEventListener('click', () => {
        if (row.dataset.noAction === '1') {
            return;
        }
        const url = (row.dataset.url || '').trim();
        if (!url || url === '#') {
            return;
        }
        window.open(url, '_blank');
    });
});
</script>

@endsection
