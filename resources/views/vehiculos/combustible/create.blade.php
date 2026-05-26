@extends('adminlte::page')

@section('title', 'Registrar Combustible')

@section('content')
<br>
<br>
<br>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Registrar carga de combustible</h4>
        <a href="{{ route('vehiculos.combustible.index', $vehiculo->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>{{ $vehiculo->placa }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('vehiculos.combustible.store', $vehiculo->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('vehiculos.combustible._form')
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar carga
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
