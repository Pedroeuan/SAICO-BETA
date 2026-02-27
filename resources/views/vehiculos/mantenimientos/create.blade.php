@extends('adminlte::page')
@section('title', 'Nuevo Mantenimiento')
<br>
<br>
<br>
@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-tools"></i>
                Nuevo Mantenimiento - {{ $vehiculo->placa }}
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('vehiculos.mantenimientos.store', $vehiculo->id) }}">
                @csrf

                <div class="row g-3">

                    <!-- FECHA -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input name="fecha"
                               type="date"
                               class="form-control"
                               required>
                    </div>

                    <!-- TIPO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo de mantenimiento</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="preventivo">Preventivo</option>
                            <option value="correctivo">Correctivo</option>
                        </select>
                    </div>

                    <!-- KILOMETRAJE -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kilometraje</label>
                        <input name="kilometraje"
                               type="number"
                               class="form-control"
                               placeholder="Ej. 85000">
                    </div>

                    <!-- COSTO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Costo</label>
                        <input name="costo"
                               type="number"
                               step="0.01"
                               class="form-control"
                               placeholder="$0.00">
                    </div>

                    <!-- PRÓXIMA FECHA -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Próxima revisión (fecha)</label>
                        <input name="proxima_revision_fecha"
                               type="date"
                               class="form-control">
                    </div>

                    <!-- PRÓXIMO KM -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Próximo KM</label>
                        <input name="proxima_revision_km"
                               type="number"
                               class="form-control"
                               placeholder="Ej. 95000">
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion"
                                  class="form-control"
                                  rows="3"
                                  placeholder="Detalles del mantenimiento realizado..."></textarea>
                    </div>

                    <!-- FACTURA -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Factura (PDF)</label>
                        <input name="factura_pdf"
                               type="file"
                               class="form-control"
                               accept="application/pdf">
                    </div>

                </div>

                <div class="mt-4 text-end">

                    <button class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Mantenimiento
                    </button>

                    <a href="{{ route('vehiculos.mantenimientos.index', $vehiculo->id) }}"
                       class="btn btn-secondary">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection