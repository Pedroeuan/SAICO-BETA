@extends('adminlte::page')

@section('content')
<div class="container mt-4">

    <h4>
        Checklist de {{ ucfirst($tipo) }}
    </h4>

    <hr>

    <p><strong>Vehículo:</strong> {{ $salida->vehiculo->placa }}</p>
    <p><strong>Chofer:</strong> {{ $salida->chofer->name }}</p>
    <p><strong>Fecha salida:</strong> {{ $salida->fecha_salida }}</p>

    <hr>

    <h5>Estado del vehículo</h5>
    <ul>
        <li><strong>Nivel gasolina:</strong> {{ $checklist->nivel_gasolina }}</li>
        <li><strong>Kilometraje:</strong> {{ $checklist->kilometraje }}</li>
        <li><strong>Limpio exterior:</strong> {{ $checklist->limpio_exterior ? 'Sí' : 'No' }}</li>
        <li><strong>Limpio interior:</strong> {{ $checklist->limpio_interior ? 'Sí' : 'No' }}</li>
        <li><strong>Observaciones:</strong> {{ $checklist->observaciones ?? 'N/A' }}</li>
    </ul>

    <hr>

    <h5>Herramientas</h5>
    @if($checklist->herramientas->count())
        <ul>
            @foreach($checklist->herramientas as $herramienta)
                <li>
                    {{ ucfirst(str_replace('_',' ', $herramienta->herramienta)) }} :
                    {{ $herramienta->disponible ? 'Sí' : 'No' }}
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">No se registraron herramientas</p>
    @endif
    <hr>
<h5>Documentos</h5>

@if($checklist->documentos->count())
    <ul>
        @foreach($checklist->documentos as $doc)
            <li>
                {{ ucfirst(str_replace('_',' ', $doc->documento)) }} :
                {{ $doc->estatus ? 'Sí' : 'No' }}
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted">No se registraron documentos</p>
@endif


    <a href="{{ route('salidas.index') }}" class="btn btn-secondary mt-3">
        Volver
    </a>

</div>
@endsection
