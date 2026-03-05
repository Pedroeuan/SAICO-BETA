@extends('adminlte::page')
@section('title', 'Mantenimientos')
<br>
<br>
<br>
@section('css')
<style>
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
</style>
@endsection
@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-tools"></i>
                Mantenimientos - {{ $vehiculo->placa }}
            </h5>

            <div>
                <a href="{{ route('vehiculos.index', $vehiculo->id) }}"
                   class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>

                <a href="{{ route('vehiculos.mantenimientos.create', $vehiculo->id) }}"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nuevo
                </a>
            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="tablaMantenimientos"
                       class="table table-bordered table-hover table-striped align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>KM</th>
                            <th>Costo</th>
                            <th class="text-center" style="width:100px;">Editar</th>
                            <th class="text-center" style="width:110px;">Eliminar</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($mantenimientos as $m)
                        <tr>
                            <td>{{ optional($m->fecha)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge 
                                    {{ $m->tipo == 'preventivo' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($m->tipo) }}
                                </span>
                            </td>
                            <td>{{ $m->kilometraje ?? 'N/A' }}</td>
                            <td>
                                ${{ number_format($m->costo ?? 0, 2) }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('vehiculos.mantenimientos.edit', [$vehiculo->id, $m->id]) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                <form method="POST"
                                      action="{{ route('vehiculos.mantenimientos.destroy', [$vehiculo->id, $m->id]) }}"
                                      onsubmit="return confirm('¿Eliminar mantenimiento?');">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</div>

@endsection


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
$(document).ready(function() {
    $('#tablaMantenimientos').DataTable({
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            emptyTable: "No hay mantenimientos registrados",
            zeroRecords: "No se encontraron resultados",
            paginate: {
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        responsive: true,
        autoWidth: false,
        pageLength: 10
    });
});
</script>
@endsection

