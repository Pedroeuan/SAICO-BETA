@extends('adminlte::page')
@section('title', 'Editar Mantenimiento')
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

        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-tools"></i>
                Editar Mantenimiento - {{ $vehiculo->placa }}
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('vehiculos.mantenimientos.update', [$vehiculo->id, $mantenimiento->id]) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- FECHA -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha</label>
                        <input name="fecha"
                               type="date"
                               class="form-control"
                               value="{{ optional($mantenimiento->fecha)->format('Y-m-d') }}"
                               required>
                    </div>

                    <!-- TIPO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo de mantenimiento</label>
                        <select name="tipo" class="form-control" required>
                            <option value="preventivo" {{ $mantenimiento->tipo=='preventivo'?'selected':'' }}>
                                Preventivo
                            </option>
                            <option value="correctivo" {{ $mantenimiento->tipo=='correctivo'?'selected':'' }}>
                                Correctivo
                            </option>
                        </select>
                    </div>

                    <!-- KILOMETRAJE -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Kilometraje</label>
                        <input name="kilometraje"
                               type="number"
                               class="form-control"
                               value="{{ $mantenimiento->kilometraje }}">
                    </div>

                    <!-- COSTO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Costo</label>
                        <input name="costo"
                               type="number"
                               step="0.01"
                               class="form-control"
                               value="{{ $mantenimiento->costo }}">
                    </div>

                    <!-- PRÓXIMA FECHA -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Próxima revisión (fecha)</label>
                        <input name="proxima_revision_fecha"
                               type="date"
                               class="form-control"
                               value="{{ optional($mantenimiento->proxima_revision_fecha)->format('Y-m-d') }}">
                    </div>

                    <!-- PRÓXIMO KM -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Próximo KM</label>
                        <input name="proxima_revision_km"
                               type="number"
                               class="form-control"
                               value="{{ $mantenimiento->proxima_revision_km }}">
                    </div>

                    <!-- DESCRIPCIÓN -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Descripción</label>
                        <textarea name="descripcion"
                                  class="form-control"
                                  rows="3">{{ $mantenimiento->descripcion }}</textarea>
                    </div>

                    <!-- FACTURA -->
                    <div class="col-12">
                        <label class="form-label fw-bold">Factura (PDF)</label>

                        @if($mantenimiento->factura_pdf)
                            <div class="mb-2">
                                <a href="{{ asset('storage/'.$mantenimiento->factura_pdf) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Ver factura actual
                                </a>
                            </div>
                        @endif

                        <input name="factura_pdf"
                               type="file"
                               class="form-control"
                               accept="application/pdf">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Numero de factura</label>
                        <input name="factura_numero"
                               type="text"
                               class="form-control"
                               maxlength="100"
                               value="{{ $mantenimiento->factura_numero }}"
                               placeholder="Ej. FAC-2026-001">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Fecha de factura</label>
                        <input name="factura_fecha"
                               type="date"
                               class="form-control"
                               value="{{ optional($mantenimiento->factura_fecha)->format('Y-m-d') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Monto de factura</label>
                        <input name="factura_monto"
                               type="number"
                               step="0.01"
                               min="0"
                               class="form-control"
                               value="{{ $mantenimiento->factura_monto }}"
                               placeholder="$0.00">
                    </div>

                </div>

                <div class="mt-4 text-end">
                    <button class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Mantenimiento
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

