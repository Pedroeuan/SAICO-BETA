@extends('adminlte::page')

@section('title', 'Detalle Checklist')

@section('content')
<br>
<br>
<br>
<div class="container-fluid mt-2">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="mb-2 mb-md-0">
                Checklist de {{ ucfirst($tipo) }}
                <span class="badge badge-{{ $tipo === 'salida' ? 'info' : 'secondary' }}">{{ strtoupper($tipo) }}</span>
            </h5>
            <a href="{{ route('salidas.index') }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 mb-2">
                    <div class="small-box bg-primary mb-0">
                        <div class="inner">
                            <h6 class="mb-1">Vehiculo</h6>
                            <p class="mb-0">{{ $salida->vehiculo->placa ?? 'N/A' }}</p>
                        </div>
                        <div class="icon"><i class="fas fa-car"></i></div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="small-box bg-success mb-0">
                        <div class="inner">
                            <h6 class="mb-1">Chofer</h6>
                            <p class="mb-0">{{ $salida->chofer->name ?? 'N/A' }}</p>
                        </div>
                        <div class="icon"><i class="fas fa-user"></i></div>
                    </div>
                </div>
                <div class="col-md-4 mb-2">
                    <div class="small-box bg-warning mb-0">
                        <div class="inner">
                            <h6 class="mb-1">Fecha salida</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($salida->fecha_salida)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs" id="checklistTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="estado-tab" data-toggle="tab" href="#estado" role="tab">Estado del vehiculo</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="herramientas-tab" data-toggle="tab" href="#herramientas" role="tab">Herramientas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="documentos-tab" data-toggle="tab" href="#documentos" role="tab">Documentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="evidencias-tab" data-toggle="tab" href="#evidencias" role="tab">Evidencias</a>
                </li>
            </ul>

            <div class="tab-content border border-top-0 p-3" id="checklistTabsContent">
                <div class="tab-pane fade show active" id="estado" role="tabpanel">
                    @if($checklist->condicion)
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-bordered mb-0">
                                    <tr><th style="width: 45%;">Nivel gasolina</th><td>{{ $checklist->condicion->nivel_gasolina }}</td></tr>
                                    <tr><th>Kilometraje</th><td>{{ $checklist->condicion->kilometraje }}</td></tr>
                                    <tr><th>Liquido limpia parabrisas</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->liquido_limpiaparabrisas ?? 'n/a')) }}</td></tr>
                                    <tr><th>Aceite</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->aceite ?? 'n/a')) }}</td></tr>
                                    <tr><th>Anticongelante</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->anticongelante ?? 'n/a')) }}</td></tr>
                                    <tr><th>Estado general llantas</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->estado_llantas ?? 'n/a')) }}</td></tr>
                                    <tr><th>Delantera izquierda (calibracion)</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->llanta_delantera_izq_calibracion ?? 'n/a')) }}</td></tr>
                                    <tr><th>Delantera derecha (calibracion)</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->llanta_delantera_der_calibracion ?? 'n/a')) }}</td></tr>
                                    <tr><th>Trasera izquierda (calibracion)</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->llanta_trasera_izq_calibracion ?? 'n/a')) }}</td></tr>
                                    <tr><th>Trasera derecha (calibracion)</th><td>{{ ucfirst(str_replace('_', ' ', $checklist->condicion->llanta_trasera_der_calibracion ?? 'n/a')) }}</td></tr>
                                    <tr><th>Limpio exterior</th><td>{!! $checklist->condicion->limpio_exterior ? '<span class="badge badge-success">Si</span>' : '<span class="badge badge-danger">No</span>' !!}</td></tr>
                                    <tr><th>Limpio interior</th><td>{!! $checklist->condicion->limpio_interior ? '<span class="badge badge-success">Si</span>' : '<span class="badge badge-danger">No</span>' !!}</td></tr>
                                    <tr><th>Observaciones</th><td>{{ $checklist->condicion->observaciones ?? 'N/A' }}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="w-100 alert alert-light border mb-0">
                                    <strong>Estado general:</strong>
                                    @php
                                        $okLimpieza = $checklist->condicion->limpio_exterior && $checklist->condicion->limpio_interior;
                                    @endphp
                                    <span class="badge badge-{{ $okLimpieza ? 'success' : 'warning' }}">
                                        {{ $okLimpieza ? 'Condicion aceptable' : 'Revisar limpieza' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-danger mb-0">No se registro condicion del vehiculo.</p>
                    @endif
                </div>

                <div class="tab-pane fade" id="herramientas" role="tabpanel">
                    @if($checklist->herramientas->count())
                        <div class="row">
                            @foreach($checklist->herramientas as $herr)
                                <div class="col-md-4 mb-2">
                                    <div class="card card-outline {{ $herr->disponible ? 'card-success' : 'card-danger' }} mb-0">
                                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                            <span>{{ ucwords(str_replace('_', ' ', $herr->herramienta)) }}</span>
                                            <span class="badge badge-{{ $herr->disponible ? 'success' : 'danger' }}">{{ $herr->disponible ? 'Si' : 'No' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No se registraron herramientas para este checklist.</p>
                    @endif
                </div>

                <div class="tab-pane fade" id="documentos" role="tabpanel">
                    @if($checklist->documentos->count())
                        <div class="row">
                            @foreach($checklist->documentos as $doc)
                                @php
                                    $ok = strtolower((string) $doc->estatus) === 'ok';
                                @endphp
                                <div class="col-md-4 mb-2">
                                    <div class="card card-outline {{ $ok ? 'card-success' : 'card-danger' }} mb-0">
                                        <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                            <span>{{ ucwords(str_replace('_', ' ', $doc->documento)) }}</span>
                                            <span class="badge badge-{{ $ok ? 'success' : 'danger' }}">{{ $ok ? 'Si' : 'No' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No se registraron documentos para este checklist.</p>
                    @endif
                </div>

                <div class="tab-pane fade" id="evidencias" role="tabpanel">
                    @if($checklist->evidencias->count())
                        <div class="row">
                            @foreach ($checklist->evidencias as $idx => $evidencia)
                                <div class="col-md-3 col-sm-4 col-6 mb-3">
                                    <a href="#" class="d-block evidence-trigger" data-img="{{ asset('storage/'.$evidencia->foto) }}" data-index="{{ $idx + 1 }}">
                                        <img src="{{ asset('storage/'.$evidencia->foto) }}" class="img-fluid rounded border evidence-thumb" alt="evidencia {{ $idx + 1 }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No hay evidencias fotograficas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="evidenceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Evidencia <span id="evidenceNumber"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-2">
                <img id="evidencePreview" src="" alt="Evidencia" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
    .evidence-thumb {
        width: 100%;
        height: 140px;
        object-fit: cover;
        transition: transform .2s ease;
    }
    .evidence-trigger:hover .evidence-thumb {
        transform: scale(1.03);
    }
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.evidence-trigger').forEach(function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            var img = this.getAttribute('data-img');
            var index = this.getAttribute('data-index');
            document.getElementById('evidencePreview').setAttribute('src', img);
            document.getElementById('evidenceNumber').textContent = '#' + index;
            $('#evidenceModal').modal('show');
        });
    });
});
</script>
@endsection
