@extends('adminlte::page')
@section('title', 'Registro')
@section('css')
<style>
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
</style>
@endsection
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Registrar Vehículo</h4>
        <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('vehiculos.store') }}" enctype="multipart/form-data">
        @csrf

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
                            <input type="text" name="placa" class="form-control" value="{{ old('placa') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Marca <span class="text-danger">*</span></label>
                            <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Modelo <span class="text-danger">*</span></label>
                            <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Año</label>
                                    <input type="number" name="anio" class="form-control" value="{{ old('anio') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="fw-bold">Estatus</label>
                                    <select name="estatus" class="form-control" required>
                                        <option value="disponible" {{ old('estatus') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                        <option value="ocupado" {{ old('estatus') == 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                                        <option value="inactivo" {{ old('estatus') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
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
                            <input type="file" name="poliza_seguro_pdf" accept="application/pdf" class="form-control">
                            <small class="text-muted">Cargar documento de póliza</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Vencimiento Póliza</label>
                            <input type="date" name="poliza_seguro_vencimiento" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Tarjeta de Circulación (PDF)</label>
                            <input type="file" name="tarjeta_circulacion_pdf" accept="application/pdf" class="form-control">
                            <small class="text-muted">Cargar documento de circulación</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Vencimiento Tarjeta</label>
                            <input type="date" name="tarjeta_circulacion_vencimiento" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Foto principal del vehículo</label>
                            <input type="file" name="foto_principal" accept="image/*" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Vencimiento tenencia</label>
                            <input type="date" name="tenencia_vencimiento" class="form-control" value="{{ old('tenencia_vencimiento') }}">
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Estatus tenencia</label>
                            <select name="tenencia_estatus" class="form-control">
                                <option value="sin_registro" {{ old('tenencia_estatus') == 'sin_registro' ? 'selected' : '' }}>Sin registro</option>
                                <option value="vigente" {{ old('tenencia_estatus') == 'vigente' ? 'selected' : '' }}>Vigente</option>
                                <option value="proxima" {{ old('tenencia_estatus') == 'proxima' ? 'selected' : '' }}>Próxima</option>
                                <option value="vencida" {{ old('tenencia_estatus') == 'vencida' ? 'selected' : '' }}>Vencida</option>
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
                <i class="fas fa-save"></i> Guardar Vehículo
            </button>
        </div>
    </form>
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
@endsection


