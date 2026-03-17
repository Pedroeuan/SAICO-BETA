@extends('adminlte::page')
@section('title', 'Nuevo Pago Vehículo')
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

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-file-invoice-dollar"></i>
                Nuevo Pago - {{ $vehiculo->placa }}
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('vehiculos.pagos.store', $vehiculo->id) }}">
                @csrf

                <div class="row g-3">

                    <!-- TIPO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo de pago</label>
                        <select name="tipo_pago" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <option value="tenencia">Impuesto Anual</option>
                            <option value="refrendo">Pago de Placas</option>
                            <option value="verificacion">Verificación Vehicular</option>
                        </select>
                    </div>
                    
                    <!-- AÑO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Año</label>
                        <input name="anio"
                            type="number"
                            min="2000"
                            max="{{ date('Y') + 1 }}"
                            class="form-control"
                            value="{{ isset($pago) ? $pago->anio : '' }}"
                            placeholder="Ej. {{ date('Y') }}"
                            required>
                    </div>

                    <!-- MONTO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Monto</label>
                        <input name="monto"
                               type="number"
                               step="0.01"
                               class="form-control"
                               placeholder="$0.00">
                    </div>

                    <!-- FECHA -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de pago</label>
                        <input name="fecha_pago"
                               type="date"
                               class="form-control">
                    </div>

                    <!-- COMPROBANTE -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Comprobante (PDF / JPG / PNG)
                        </label>
                        <input name="comprobante_url"
                               type="file"
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                </div>

                <div class="mt-4 text-end">

                    <button class="btn btn-success">
                        <i class="fas fa-save"></i> Guardar Pago
                    </button>

                    <a href="{{ route('vehiculos.pagos.index', $vehiculo->id) }}"
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
