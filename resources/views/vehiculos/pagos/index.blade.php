@extends('adminlte::page')
@section('title', 'Pagos Vehiculo')
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
                <i class="fas fa-file-invoice-dollar"></i>
                Pagos - {{ $vehiculo->placa }}
            </h5>

            <div>
                <a href="{{ route('vehiculos.index', $vehiculo->id) }}"
                   class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>

                <a href="{{ route('vehiculos.pagos.create', $vehiculo->id) }}"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nuevo Pago
                </a>
            </div>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="tablaPagos"
                       class="table table-bordered table-hover table-striped align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Anio</th>
                            <th>Origen</th>
                            <th>Tipo</th>
                            <th>Fecha pago</th>
                            <th>Monto</th>
                            <th class="text-center">Comprobante</th>
                            <th class="text-center" style="width:110px;">Historial</th>
                            <th class="text-center" style="width:100px;">Editar</th>
                            <th class="text-center" style="width:110px;">Eliminar</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($pagosGenerales as $p)
                        <tr>
                            <td>{{ $p['anio'] }}</td>

                            <td>
                                @if($p['origen'] === 'pago')
                                    <span class="badge bg-info">Administrativo</span>
                                @else
                                    <span class="badge bg-secondary">Mantenimiento</span>
                                @endif
                            </td>

                            <td>{{ $p['tipo'] }}</td>

                            <td>{{ optional($p['fecha'])->format('d/m/Y') ?? 'N/A' }}</td>

                            <td>${{ number_format($p['monto'] ?? 0, 2) }}</td>

                            <td class="text-center">
                                @if($p['archivo'])
                                    @php
                                        $extension = strtolower(pathinfo($p['archivo'], PATHINFO_EXTENSION));
                                    @endphp

                                    <a href="{{ asset('storage/'.$p['archivo']) }}"
                                       target="_blank"
                                       class="btn btn-outline-primary btn-sm"
                                       title="Ver comprobante">

                                        @if($extension === 'pdf')
                                            <i class="fas fa-file-pdf text-danger"></i>
                                        @elseif(in_array($extension, ['jpg','jpeg','png']))
                                            <i class="fas fa-file-image text-info"></i>
                                        @else
                                            <i class="fas fa-file"></i>
                                        @endif

                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('vehiculos.pagos.historial', $vehiculo->id) }}"
                                   class="btn btn-info btn-sm"
                                   title="Ver historial">
                                    <i class="fas fa-history"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                @if($p['origen'] === 'pago')
                                    <a href="{{ route('vehiculos.pagos.edit', [$vehiculo->id, $p['id']]) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @else
                                    <a href="{{ route('vehiculos.mantenimientos.edit', [$vehiculo->id, $p['id']]) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Editar mantenimiento">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($p['origen'] === 'pago')
                                    <form method="POST"
                                          action="{{ route('vehiculos.pagos.destroy', [$vehiculo->id, $p['id']]) }}"
                                          onsubmit="return confirm('Eliminar pago?');">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST"
                                          action="{{ route('vehiculos.mantenimientos.destroy', [$vehiculo->id, $p['id']]) }}"
                                          onsubmit="return confirm('Eliminar mantenimiento?');">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm" title="Eliminar mantenimiento">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

    </div>

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
$(document).ready(function() {
    $('#tablaPagos').DataTable({
        language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_ registros",
            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
            emptyTable: "No hay pagos registrados",
            zeroRecords: "No se encontraron resultados",
            infoEmpty: "Mostrando 0 a 0 de 0 registros",
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
