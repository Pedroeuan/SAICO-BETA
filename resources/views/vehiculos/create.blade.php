@extends('adminlte::page')

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
@endsection
