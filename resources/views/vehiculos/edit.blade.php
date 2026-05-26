@extends('adminlte::page')
@section('title', 'Editar Vehículos')
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Editar Vehículo</h4>
        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    <!--  botmn  -->
    <div class="mb-3">
    <a href="{{ route('vehiculos.mantenimientos.index', $vehiculo->id) }}" class="btn btn-info btn-sm mr-2">
        <i class="fas fa-tools"></i> Mantenimientos
    </a>
    <a href="{{ route('vehiculos.pagos.index', $vehiculo->id) }}" class="btn btn-warning btn-sm mr-2">
        <i class="fas fa-file-invoice-dollar"></i> Pagos Vehículo
    </a>
    <a href="{{ route('vehiculos.combustible.index', $vehiculo->id) }}" class="btn btn-primary btn-sm">
        <i class="fas fa-gas-pump"></i> Combustible
    </a>
    <a href="{{ route('vehiculos.llantas.index', $vehiculo->id) }}" class="btn btn-dark btn-sm ml-2">
        <i class="fas fa-dot-circle"></i> Llantas
    </a>
</div>


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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vehiculos.update', $vehiculo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- COLUMNA IZQUIERDA: DATOS BÁSICOS -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-car"></i> Datos del Vehículo</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold">Placa <span class="text-danger">*</span></label>
                            <input type="text" name="placa" class="form-control"
                                value="{{ old('placa', $vehiculo->placa) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Marca <span class="text-danger">*</span></label>
                            <input type="text" name="marca" class="form-control"
                                value="{{ old('marca', $vehiculo->marca) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Modelo <span class="text-danger">*</span></label>
                            <input type="text" name="modelo" class="form-control"
                                value="{{ old('modelo', $vehiculo->modelo) }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Año</label>
                                    <input type="number" name="anio" class="form-control"
                                        value="{{ old('anio', $vehiculo->anio) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Estatus</label>
                                    <select name="estatus" class="form-control">
                                        <option value="disponible" {{ $vehiculo->estatus == 'disponible' ? 'selected' : '' }}>
                                            Disponible
                                        </option>
                                        <option value="ocupado" {{ $vehiculo->estatus == 'ocupado' ? 'selected' : '' }}>
                                            Ocupado
                                        </option>
                                        <option value="inactivo" {{ $vehiculo->estatus == 'inactivo' ? 'selected' : '' }}>
                                            Inactivo
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: DOCUMENTACIÓN -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-file-pdf"></i> Documentación</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="fw-bold">Póliza de Seguro (PDF)</label>
                            @if($vehiculo->poliza_seguro_pdf)
                                <div class="alert alert-info py-2 mb-2">
                                    <small>
                                        <i class="fas fa-check-circle"></i>
                                        <a href="{{ asset('storage/'.$vehiculo->poliza_seguro_pdf) }}" target="_blank">Ver póliza actual</a>
                                    </small>
                                </div>
                            @endif
                            <input type="file" name="poliza_seguro_pdf" accept="application/pdf" class="form-control">
                            <small class="text-muted">Dejar en blanco para mantener actual</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Vencimiento Póliza</label>
                            <input type="date" name="poliza_seguro_vencimiento" class="form-control" value="@if($vehiculo->poliza_seguro_vencimiento == '2001-01-01') {{ '' }} @else {{ old('poliza_seguro_vencimiento', optional($vehiculo->poliza_seguro_vencimiento)->format('Y-m-d')) }}@endif">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Tarjeta de Circulación (PDF)</label>
                            @if($vehiculo->tarjeta_circulacion_pdf)
                                <div class="alert alert-info py-2 mb-2">
                                    <small>
                                        <i class="fas fa-check-circle"></i>
                                        <a href="{{ asset('storage/'.$vehiculo->tarjeta_circulacion_pdf) }}" target="_blank">Ver tarjeta actual</a>
                                    </small>
                                </div>
                            @endif
                            <input type="file" name="tarjeta_circulacion_pdf" accept="application/pdf" class="form-control">
                            <small class="text-muted">Dejar en blanco para mantener actual</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Vencimiento Tarjeta</label>
                            <input type="date" name="tarjeta_circulacion_vencimiento" class="form-control" value="@if($vehiculo->tarjeta_circulacion_vencimiento == '2001-01-01') {{ '' }} @else {{ old('tarjeta_circulacion_vencimiento', optional($vehiculo->tarjeta_circulacion_vencimiento)->format('Y-m-d')) }}@endif">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Foto principal del vehículo</label>
                            @if($vehiculo->foto_principal)
                                <div class="mb-2">
                                    <img src="{{ $vehiculo->foto_url }}" style="max-width: 180px; border:1px solid #ddd; padding:4px;">
                                </div>
                            @endif
                            <input type="file" name="foto_principal" accept="image/*" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Vencimiento tenencia</label>
                            <input type="date" name="tenencia_vencimiento" class="form-control"
                                value="{{ old('tenencia_vencimiento', optional($vehiculo->tenencia_vencimiento)->format('Y-m-d')) }}">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Estatus tenencia</label>
                            <select name="tenencia_estatus" class="form-control">
                                <option value="sin_registro" {{ old('tenencia_estatus', $vehiculo->tenencia_estatus) == 'sin_registro' ? 'selected' : '' }}>Sin registro</option>
                                <option value="vigente" {{ old('tenencia_estatus', $vehiculo->tenencia_estatus) == 'vigente' ? 'selected' : '' }}>Vigente</option>
                                <option value="proxima" {{ old('tenencia_estatus', $vehiculo->tenencia_estatus) == 'proxima' ? 'selected' : '' }}>Próxima</option>
                                <option value="vencida" {{ old('tenencia_estatus', $vehiculo->tenencia_estatus) == 'vencida' ? 'selected' : '' }}>Vencida</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- BOTONES AL FONDO -->
        <div class="mt-3 text-end">
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Actualizar Vehículo
            </button>
        </div>
    </form>
</div>
@stop

@section('js')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">

<!-- DataTables JS -->
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

});

</script>

@endsection
