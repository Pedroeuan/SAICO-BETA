@extends('adminlte::page')

@section('title', 'Gestión de Vehículos')
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

<div class="card">
    <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ $tabActiva === 'listado' ? 'active' : '' }}" id="listado-tab" data-toggle="tab" href="#listado" role="tab">
                    <i class="fas fa-list"></i>Listado de Vehículos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tabActiva === 'documentos' ? 'active' : '' }}" id="documentos-tab" data-toggle="tab" href="#documentos" role="tab">
                    <i class="fas fa-file-pdf"></i>Documentación (<span class="badge bg-danger">{{ $vencidosCount ?? 0 }}</span>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tabActiva === 'estadisticas' ? 'active' : '' }}" id="estadisticas-tab" data-toggle="tab" href="#estadisticas" role="tab">
                    <i class="fas fa-chart-bar"></i>Estadísticas
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $tabActiva === 'movimientos' ? 'active' : '' }}" id="movimientos-tab" data-toggle="tab" href="#movimientos" role="tab">
                    <i class="fas fa-calendar-alt"></i>Movimientos del Mes
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body">
        <div class="tab-content">

            <!-- TAB 1: LISTADO -->
            <div class="tab-pane fade {{ $tabActiva === 'listado' ? 'show active' : '' }}" id="listado" role="tabpanel">
                <table id="tablaVehiculos" class="table table-sm table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <!--<th style="width: 80px;">#</th> 
                            <th style="width: 80px;">ID</th>-- IGNORE -->
                            <th>Vehículo</th>
                            <th>Año</th>
                            <th>Estado</th>
                            <th>Documentación</th>
                            <th style="width: 90px;">Editar</th>
                            <th style="width: 90px;">Salida</th>
                            <th style="width: 90px;">Baja</th>
                            <th style="width: 120px;">Mantenimientos</th>
                            <th style="width: 100px;">Pagos</th>
                            <th style="width: 120px;">Combustible</th>
                            <th style="width: 100px;">Llantas</th>

                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($vehiculos as $vehiculo)
                        <tr>
                            <!--<td>{{ $vehiculo->id }}</td>-- IGNORE -->
                            
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
                                @elseif($vehiculo->estatus === 'inactivo')
                                    <span class="badge bg-secondary">Inactivo</span>
                                @elseif($vehiculo->estatus === 'baja')
                                    <span class="badge bg-danger">Baja</span>
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
                                    onsubmit="return confirm('¿Dar de baja el vehículo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"
                                            title="Dar de baja">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('vehiculos.mantenimientos.index', $vehiculo->id) }}"
                                class="btn btn-info btn-sm"
                                title="Mantenimientos">
                                    <i class="fas fa-tools"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('vehiculos.pagos.index', $vehiculo->id) }}"
                                class="btn btn-warning btn-sm"
                                title="Pagos">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('vehiculos.combustible.index', $vehiculo->id) }}"
                                class="btn btn-primary btn-sm"
                                title="Combustible">
                                    <i class="fas fa-gas-pump"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('vehiculos.llantas.index', $vehiculo->id) }}"
                                class="btn btn-dark btn-sm"
                                title="Llantas">
                                    <i class="fas fa-dot-circle"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB 2: DOCUMENTACIÓN -->
            <div class="tab-pane fade {{ $tabActiva === 'documentos' ? 'show active' : '' }}" id="documentos" role="tabpanel">
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
            <div class="tab-pane fade {{ $tabActiva === 'estadisticas' ? 'show active' : '' }}" id="estadisticas" role="tabpanel">
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

            <!-- TAB 4: MOVIMIENTOS MENSUALES -->
            <div class="tab-pane fade {{ $tabActiva === 'movimientos' ? 'show active' : '' }}" id="movimientos" role="tabpanel">
                <form method="GET" action="{{ route('vehiculos.index') }}" class="mb-3">
                    <input type="hidden" name="tab" value="movimientos">
                    <div class="form-row align-items-end">
                        <div class="col-md-3">
                            <label for="mes">Mes</label>
                            <select name="mes" id="mes" class="form-control">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ (int) $mesSeleccionado === $i ? 'selected' : '' }}>
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="anio">Anio</label>
                            <input type="number"
                                   name="anio"
                                   id="anio"
                                   min="2000"
                                   max="{{ now()->year + 1 }}"
                                   class="form-control"
                                   value="{{ $anioSeleccionado }}">
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Consultar
                            </button>
                            <a href="{{ route('vehiculos.reportes.movimientos.pdf', ['mes' => $mesSeleccionado, 'anio' => $anioSeleccionado]) }}"
                               target="_blank"
                               class="btn btn-danger ml-2">
                                <i class="fas fa-file-pdf"></i> Exportar PDF
                            </a>
                        </div>
                    </div>
                </form>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <small class="text-muted d-block">Vehiculos con actividad</small>
                                <h4 class="mb-1">{{ $resumenMovimientos['vehiculos_con_movimientos'] ?? 0 }}</h4>
                                <small class="text-muted">Con costos o uso efectivo en el periodo.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <small class="text-muted d-block">Uso efectivo</small>
                                <h4 class="mb-1">{{ $resumenMovimientos['salidas_count'] ?? 0 }} salidas</h4>
                                <small class="text-muted">
                                    {{ $resumenMovimientos['salidas_finalizadas'] ?? 0 }} finalizadas |
                                    {{ number_format((float) ($resumenMovimientos['km_recorridos_total'] ?? 0), 2) }} km
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <small class="text-muted d-block">Costo total del mes</small>
                                <h4 class="mb-1">${{ number_format((float) ($resumenMovimientos['total_general'] ?? 0), 2) }}</h4>
                                <small class="text-muted">
                                    Mantto., pagos, combustible y llantas.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <small class="text-muted d-block">Costo promedio por km</small>
                                <h4 class="mb-1">${{ number_format((float) ($resumenMovimientos['costo_promedio_km'] ?? 0), 2) }}</h4>
                                <small class="text-muted">Costo total dividido entre km recorridos.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Vehiculo</th>
                                <th class="text-center">Uso efectivo</th>
                                <th class="text-right">Monto Mantto.</th>
                                <th class="text-right">Monto Pagos</th>
                                <th class="text-right">Combustible</th>
                                <th class="text-right">Llantas</th>
                                <th class="text-right">Total Mes</th>
                                <th class="text-right">Costo / km</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movimientosMensuales as $mov)
                                <tr>
                                    <td>
                                        <strong>{{ $mov->placa }}</strong><br>
                                        <small class="text-muted">{{ $mov->marca }} {{ $mov->modelo }}</small>
                                    </td>
                                    <td class="text-center">
                                        <strong>{{ $mov->salidas_count }}</strong> salidas<br>
                                        <small class="text-muted">
                                            {{ $mov->salidas_finalizadas }} finalizadas |
                                            {{ number_format((float) $mov->km_recorridos_total, 2) }} km
                                        </small>
                                    </td>
                                    <td class="text-right">${{ number_format($mov->mantenimientos_total, 2) }}</td>
                                    <td class="text-right">${{ number_format($mov->pagos_total, 2) }}</td>
                                    <td class="text-right">${{ number_format($mov->combustible_total, 2) }}</td>
                                    <td class="text-right">${{ number_format($mov->llantas_total, 2) }}</td>
                                    <td class="text-right font-weight-bold">${{ number_format($mov->total_general, 2) }}</td>
                                    <td class="text-right">
                                        ${{ number_format($mov->km_recorridos_total > 0 ? ($mov->total_general / $mov->km_recorridos_total) : 0, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted"></i>
                                        Sin movimientos en el periodo seleccionado
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($movimientosMensuales, 'links'))
                    <div class="mt-2">
                        {{ $movimientosMensuales->links() }}
                    </div>
                @endif
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
document.addEventListener('DOMContentLoaded', function () {
    const notificationMenu = document.querySelector('#my-notification .dropdown-menu');
    if (!notificationMenu) return;

    function normalizeNotificationMenu() {
        const items = notificationMenu.querySelectorAll('.dropdown-item');
        items.forEach((item) => {
            const text = (item.textContent || '').trim().toLowerCase();
            if (text === 'todas las notificaciones') {
                item.textContent = 'Ver todas las notificaciones';
                item.classList.add('font-weight-bold');
            }
        });
    }

    const observer = new MutationObserver(normalizeNotificationMenu);
    observer.observe(notificationMenu, { childList: true, subtree: true });
    normalizeNotificationMenu();
});

$(document).ready(function() {

    // Inicializar SOLO la tabla principal
    $('#tablaVehiculos').DataTable({
        language: {
            decimal: "",
            emptyTable: '<i class="fas fa-inbox"></i> No hay vehículos registrados',
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

    const params = new URLSearchParams(window.location.search);
    if (params.get('tab') === 'movimientos') {
        $('#movimientos-tab').tab('show');
    }

});
</script>

@endsection
@endsection
