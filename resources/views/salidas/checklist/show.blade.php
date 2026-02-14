@extends('adminlte::page')

@section('content')
<br>
<br>
<br>
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

@if($checklist->condicion)
    <p><strong>Nivel gasolina:</strong> {{ $checklist->condicion->nivel_gasolina }}</p>
    <p><strong>Kilometraje:</strong> {{ $checklist->condicion->kilometraje }}</p>
    <p><strong>Limpio exterior:</strong>
        {{ $checklist->condicion->limpio_exterior ? 'Sí' : 'No' }}
    </p>
    <p><strong>Limpio interior:</strong>
        {{ $checklist->condicion->limpio_interior ? 'Sí' : 'No' }}
    </p>
    <p><strong>Observaciones:</strong>
        {{ $checklist->condicion->observaciones ?? 'N/A' }}
    </p>
@else
    <p class="text-danger">No se registró condición del vehículo</p>
@endif

    <hr>
    <h5>Evidencias fotográficas</h5>

<div class="row">
@foreach ($checklist->evidencias as $evidencia)
    <div class="col-md-3 mb-3">
        <img src="{{ asset('storage/'.$evidencia->foto) }}"
             class="img-fluid rounded border">
    </div>
@endforeach
</div>



<h5>Herramientas</h5>

@if($checklist->herramientas->count())
    <ul>
        @foreach($checklist->herramientas as $herr)
            <li>
                {{ str_replace('_',' ', ucfirst($herr->herramienta)) }} :
                {{ $herr->disponible ? 'Sí' : 'No' }}
            </li>
        @endforeach
    </ul>
@else
    <p>No se registraron herramientas</p>
@endif

    <hr>
<h5>Documentos</h5>

@if($checklist->documentos->count())
    <ul>
        @foreach($checklist->documentos as $doc)
            <li>
                {{ str_replace('_',' ', ucfirst($doc->documento)) }} :
                {{ $doc->estatus ? 'Sí' : 'No' }}
            </li>
        @endforeach
    </ul>
@else
    <p>No se registraron documentos</p>
@endif

    <a href="{{ route('salidas.index') }}" class="btn btn-secondary mt-3">
        Volver
    </a>

</div>
@endsection
