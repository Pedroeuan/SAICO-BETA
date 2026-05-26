@extends('adminlte::page')

@section('title', 'Salida Vehiculos')
@section('css')
<style>
    #my-notification .dropdown-menu {
        max-height: 320px;
        width: 360px;
        max-width: 90vw;
        overflow-y: auto;
    }

    #my-notification .dropdown-item {
        white-space: normal;
        word-break: break-word;
    }
</style>
@endsection
@section('content')
<br>
<br>
<br>
<div class="container mt-4">
    <h4>Salidas de Vehiculos</h4>

    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('salidas.create') }}" class="btn btn-primary mb-3">
        + Nueva salida
    </a>

    @can('vehiculos-admin-access')
        <a href="{{ route('vehiculos.encuestas.index') }}" class="btn btn-dark mb-3 ml-2">
            <i class="fas fa-chart-line"></i> Analitica de encuestas
        </a>
    @endcan

    <div class="table-responsive">
        <table id="tablaJs" class="table table-sm table-hover table-bordered align-middle text-center">
            <thead>
                <tr>
                    <th>Vehiculo</th>
                    <th>Chofer</th>
                    <th>Solicitado por</th>
                    <th>Fecha salida</th>
                    <th>Checklist salida</th>
                    <th>Checklist entrada</th>
                    <th>PDF</th>
                    <th>Ver salida</th>
                    <th>Ver entrada</th>
                    <th>Encuesta</th>
                </tr>
            </thead>

            <tbody>
            @foreach($salidas as $salida)
                <tr>
                    <td>{{ $salida->vehiculo->marca }}</td>
                    <td>{{ $salida->chofer->name }}</td>
                    <td>{{ $salida->solicitante->name ?? 'N/A' }}</td>
                    <td>{{ $salida->fecha_salida }}</td>

                    <td class="text-center">
                        @if($salida->checklistSalida)
                            <span class="badge bg-success">Registrado</span>
                        @else
                            <a href="{{ route('salidas.checklist.salida.create',$salida->id) }}" class="btn btn-sm btn-primary px-3">
                                Registrar
                            </a>
                        @endif
                    </td>

                    <td class="text-center">
                        @if($salida->checklistEntrada)
                            <span class="badge bg-success">Registrado</span>
                        @elseif($salida->checklistSalida)
                            <a href="{{ route('salidas.checklist.entrada.create',$salida->id) }}" class="btn btn-sm btn-warning px-3">
                                Registrar
                            </a>
                        @else
                            <span class="text-muted">Pendiente salida</span>
                        @endif
                    </td>

                    <td>
                        @if($salida->checklistSalida && $salida->checklistEntrada)
                            <a href="{{ route('salidas.checklist.pdf',$salida->id) }}" target="_blank" class="btn btn-sm btn-danger px-3" title="Descargar PDF completo">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                        @else
                            <button class="btn btn-sm btn-secondary px-3" disabled title="Requiere checklist de salida y entrada">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        @endif
                    </td>

                    <td>
                        @if($salida->checklistSalida)
                            <a href="{{ route('salidas.checklist.show',[$salida->id,'salida']) }}" class="btn btn-sm btn-info">Ver salida</a>
                        @endif
                    </td>

                    <td>
                        @if($salida->checklistEntrada)
                            <a href="{{ route('salidas.checklist.show',[$salida->id,'entrada']) }}" class="btn btn-sm btn-secondary px-3">
                                Ver entrada
                            </a>
                        @endif
                    </td>

                    <td>
                        @php
                            $puedeResponderEncuesta = in_array($salida->estatus, ['finalizado', 'finaliizado'], true)
                                && (auth()->id() === (int) $salida->chofer_id || auth()->id() === (int) $salida->solicitado_por);
                            $encuestaUsuarioActual = isset($salida->encuestasSatisfaccion)
                                ? $salida->encuestasSatisfaccion->first()
                                : null;
                        @endphp

                        @if($encuestaUsuarioActual && $puedeResponderEncuesta)
                            <span class="badge bg-success">Respondida</span>
                        @elseif($puedeResponderEncuesta)
                            <a href="{{ route('salidas.encuestas.create', $salida->id) }}" class="btn btn-sm btn-dark">
                                Responder
                            </a>
                        @elseif(in_array($salida->estatus, ['finalizado', 'finaliizado'], true))
                            <span class="text-muted">Disponible al usuario</span>
                        @else
                            <span class="text-muted">Al finalizar</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/session-handler.js') }}"></script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const notificationMenu = document.querySelector('#my-notification .dropdown-menu');
    if (notificationMenu) {
        const normalizeNotificationMenu = () => {
            const items = notificationMenu.querySelectorAll('.dropdown-item');
            items.forEach((item) => {
                const text = (item.textContent || '').trim().toLowerCase();
                if (text === 'todas las notificaciones') {
                    item.textContent = 'Ver todas las notificaciones';
                    item.classList.add('font-weight-bold');
                }
            });
        };

        const observer = new MutationObserver(normalizeNotificationMenu);
        observer.observe(notificationMenu, { childList: true, subtree: true });
        normalizeNotificationMenu();
    }

    let table = new DataTable('#tablaJs', {
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        language: {
            decimal: "",
            emptyTable: "No hay datos disponibles en la tabla",
            info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
            infoEmpty: "Mostrando 0 a 0 de 0 entradas",
            infoFiltered: "(filtrado de _MAX_ entradas totales)",
            thousands: ",",
            lengthMenu: "Mostrar _MENU_ entradas",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
            zeroRecords: "No se encontraron registros coincidentes",
            paginate: {
                first: "Primero",
                last: "Ultimo",
                next: "Siguiente",
                previous: "Anterior"
            }
        }
    });

    table.on('draw', function () {
        document.querySelector('.table-responsive').scrollLeft = 0;
    });
});
</script>
@endsection
