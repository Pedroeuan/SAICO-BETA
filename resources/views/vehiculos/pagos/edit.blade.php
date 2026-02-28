@extends('adminlte::page')
@section('title', 'Editar Pago Vehículo')
<br>
<br>
<br>
@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="fas fa-edit"></i>
                Editar Pago - {{ $vehiculo->placa }}
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('vehiculos.pagos.update', [$vehiculo->id, $pago->id]) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <!-- TIPO -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Tipo de pago</label>
                        <select name="tipo_pago" class="form-control" required>
                            <option value="tenencia" {{ $pago->tipo_pago == 'tenencia' ? 'selected' : '' }}>
                                Impuesto anual 
                            </option>
                            <option value="refrendo" {{ $pago->tipo_pago == 'refrendo' ? 'selected' : '' }}>
                                Pago de placas
                            </option>
                            <option value="verificacion" {{ $pago->tipo_pago == 'verificacion' ? 'selected' : '' }}>
                                Verificación Vehicular
                            </option>
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
                               value="{{ $pago->monto }}">
                    </div>

                    <!-- FECHA -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fecha de pago</label>
                        <input name="fecha_pago"
                               type="date"
                               class="form-control"
                               value="{{ optional($pago->fecha_pago)->format('Y-m-d') }}">
                    </div>

                    <!-- COMPROBANTE -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            Comprobante (PDF / JPG / PNG)
                        </label>

                        @if($pago->comprobante_url)
                            <div class="mb-2">
                                <a href="{{ asset('storage/'.$pago->comprobante_url) }}"
                                   target="_blank"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Ver comprobante actual
                                </a>
                            </div>
                        @endif

                        <input name="comprobante_url"
                               type="file"
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                </div>

                <div class="mt-4 text-end">

                    <button class="btn btn-success">
                        <i class="fas fa-save"></i> Actualizar Pago
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
