@extends('adminlte::page')

@section('title', 'Registrar Llanta')

@section('content')
<br>
<br>
<br>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Registrar llanta</h4>
        <a href="{{ route('vehiculos.llantas.index', $vehiculo->id) }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <strong>{{ $vehiculo->placa }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('vehiculos.llantas.store', $vehiculo->id) }}" method="POST">
                @csrf
                @include('vehiculos.llantas._form')
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar llanta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
