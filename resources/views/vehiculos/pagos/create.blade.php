@extends('adminlte::page')
@section('title', 'Nuevo Pago Vehículo')
<br>
<br>
<br>

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
                            <option value="tenencia">Tenencia</option>
                            <option value="refrendo">Refrendo</option>
                            <option value="verificacion">Verificación</option>
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

@endsection