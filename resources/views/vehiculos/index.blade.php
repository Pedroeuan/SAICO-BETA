@extends('adminlte::page')

@section('title', 'Gestión de Vehículos')
<br>
<br>
<br>
@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Gestión de Vehículos</h1>
        <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Vehículo
        </a>
    </div>
@stop

@section('content')

<div class="card">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="listado-tab" data-toggle="tab" href="#listado" role="tab">
                    <i class="fas fa-list"></i>Listado de Vehículos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="documentos-tab" data-toggle="tab" href="#documentos" role="tab">
                    <i class="fas fa-file-pdf"></i>Documentación (<span class="badge bg-danger">{{ $vencidosCount ?? 0 }}</span>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="estadisticas-tab" data-toggle="tab" href="#estadisticas" role="tab">
                    <i class="fas fa-chart-bar"></i>Estadísticas
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">

            <!-- TAB 1: LISTADO -->
            <div class="tab-pane fade show active" id="listado" role="tabpanel">
                <table id="tablaVehiculos" class="table table-sm table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>Vehículo</th>
                            <th>Año</th>
                            <th>Estado</th>
                            <th>Documentación</th>
                            <th style="width: 90px;">Editar</th>
                            <th style="width: 90px;">Salida</th>
                            <th style="width: 90px;">Eliminar</th>

                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($vehiculos as $vehiculo)
                        <tr>
                            <td>{{ $vehiculo->id }}</td>
                            <td>
                                <strong>{{ $vehiculo->placa }}</strong><br>
                                <small class="text-muted">{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</small>
                            </td>
                            <td>{{ $vehiculo->anio }}</td>
                            <td>
                                @if($vehiculo->estatus === 'disponible')
                                    <span class="badge bg-success">Disponible</span>
                                @elseif($vehiculo->estatus === 'ocupado')
                                    <span class="badge bg-warning">Ocupado</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                @if($vehiculo->documentacion_estatus === 'completa')
                                    <span class="badge bg-success">Completa</span>
                                @elseif($vehiculo->documentacion_estatus === 'vencida')
                                    <span class="badge bg-danger">Vencida</span>
                                @else
                                    <span class="badge bg-warning">Incompleta</span>
                                @endif
                            </td>
                            <td class="text-center">
    <a href="{{ route('vehiculos.edit', $vehiculo->id) }}"
       class="btn btn-sm btn-warning"
       title="Editar">
        <i class="fas fa-edit"></i>
    </a>
</td>

<td class="text-center">
    @if($vehiculo->estatus === 'disponible')
        <a href="{{ route('salidas.create') }}"
           class="btn btn-sm btn-success"
           title="Nueva salida">
            <i class="fas fa-door-open"></i>
        </a>
    @else
        <span class="text-muted">—</span>
    @endif
</td>

<td class="text-center">
    <form action="{{ route('vehiculos.destroy', $vehiculo->id) }}"
          method="POST"
          onsubmit="return confirm('¿Eliminar vehículo?')">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-outline-danger"
                title="Eliminar">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No hay vehículos registrados
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <!-- TAB 2: DOCUMENTACIÓN -->
            <div class="tab-pane fade" id="documentos" role="tabpanel">
                <p class="mb-3"><strong>Alertas de Documentación</strong></p>
                
                <!-- VENCIDOS -->
                @if(isset($documentosVencidos) && $documentosVencidos->count() > 0)
                    <div class="alert alert-danger">
                        <h6>Documentación Vencida ({{ $documentosVencidos->count() }})</h6>
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($documentosVencidos as $v)
                                <tr>
                                    <td><strong>{{ $v->placa }}</strong> - {{ $v->marca }}</td>
                                    <td><a href="{{ route('vehiculos.edit', $v->id) }}" class="btn btn-sm btn-warning">Actualizar</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-success">No hay documentos vencidos</div>
                @endif

                <!-- PRÓXIMO A VENCER -->
                @if(isset($documentosProximoVencer) && $documentosProximoVencer->count() > 0)
                    <div class="alert alert-warning">
                        <h6>Próximos a Vencer (< 15 días) ({{ $documentosProximoVencer->count() }})</h6>
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($documentosProximoVencer as $v)
                                <tr>
                                    <td><strong>{{ $v->placa }}</strong> - {{ $v->marca }}</td>
                                    <td><a href="{{ route('vehiculos.edit', $v->id) }}" class="btn btn-sm btn-warning">Renovar</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- INCOMPLETOS -->
                @if(isset($documentosSinRegistrar) && $documentosSinRegistrar->count() > 0)
                    <div class="alert alert-info">
                        <h6>Documentación Incompleta ({{ $documentosSinRegistrar->count() }})</h6>
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($documentosSinRegistrar as $v)
                                <tr>
                                    <td><strong>{{ $v->placa }}</strong> - {{ $v->marca }}</td>
                                    <td><a href="{{ route('vehiculos.edit', $v->id) }}" class="btn btn-sm btn-info">Completar</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- TAB 3: ESTADÍSTICAS -->
            <div class="tab-pane fade" id="estadisticas" role="tabpanel">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center bg-light">
                            <div class="card-body">
                                <h3>{{ $totalVehiculos ?? 0 }}</h3>
                                <small>Total Vehículos</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-success text-white">
                            <div class="card-body">
                                <h3>{{ $disponibles ?? 0 }}</h3>
                                <small>Disponibles</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-warning text-white">
                            <div class="card-body">
                                <h3>{{ $ocupados ?? 0 }}</h3>
                                <small>Ocupados</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-danger text-white">
                            <div class="card-body">
                                <h3>{{ $vencidos ?? 0 }}</h3>
                                <small>Doc. Vencida</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@section('js')

<!-- jQuery (solo si no lo carga AdminLTE) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts personalizados -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script src="{{ asset('js/notificaciones.js') }}"></script>

<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>

<script>
$(document).ready(function() {

    // Inicializar SOLO la tabla principal
    $('#tablaVehiculos').DataTable({
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
                last: "Último",
                next: "Siguiente",
                previous: "Anterior"
            }
        },
        responsive: true,
        autoWidth: false
    });

});
</script>

@endsection
@endsection
