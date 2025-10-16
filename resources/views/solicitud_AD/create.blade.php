@extends('adminlte::page')

@section('title', 'Solicitud AD')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

<style>
    #my-notification .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<br><br><br>

<form id="solicitudForm" role="form" method="POST" action="{{ route('solicitudes.storeSolicitud') }}" enctype="multipart/form-data">
    @csrf

    <h2 class="mb-4 text-primary">Solicitud AD</h2>

    {{-- ================== NUEVOS CAMPOS ================== --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Datos de la Solicitud</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Campo Solicitud -->
                <div class="col-md-4 mb-3">
                    <label for="solicitud" class="form-label fw-bold">Solicitud</label>
                    <input type="text" class="form-control" id="solicitud" name="solicitud" placeholder="Nombre o tipo de solicitud" required>
                </div>

                <!-- Campo Comentario -->
                <div class="col-md-4 mb-3">
                    <label for="comentario" class="form-label fw-bold">Comentario</label>
                    <input type="text" class="form-control" id="comentario" name="comentario" placeholder="Observaciones o detalles">
                </div>

                <!-- Campo Estatus -->
                <div class="col-md-4 mb-3">
                    <label for="estatus" class="form-label fw-bold">Estatus</label>
                    <select class="form-select" id="estatus" name="estatus" required>
                        <option value="">Seleccione...</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
@foreach 

