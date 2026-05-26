@extends('adminlte::page')

@section('title', 'Encuesta de satisfaccion vehicular')

@section('content')
<br>
<br>
<br>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4>Encuesta de satisfaccion del servicio vehicular</h4>
            <p class="text-muted mb-0">
                Servicio {{ $salida->Num_Reporte ?? ('#' . $salida->id) }} |
                {{ $salida->vehiculo->placa ?? 'Sin placa' }} -
                {{ $salida->vehiculo->marca ?? 'Vehiculo' }} {{ $salida->vehiculo->modelo ?? '' }}
            </p>
        </div>
        <a href="{{ route('salidas.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="alert alert-info">
                Esta encuesta alimenta los indicadores de reputacion interna del servicio vehicular. Responde con enfoque operativo y de mejora.
            </div>

            <form action="{{ route('salidas.encuestas.store', $salida->id) }}" method="POST">
                @csrf
                @include('vehiculos.encuestas._form')

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Registrar encuesta
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
