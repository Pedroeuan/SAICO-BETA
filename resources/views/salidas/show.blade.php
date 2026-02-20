@extends('adminlte::page')
@section('title', 'Vehículos')
@section('content')
<br>
<br>
<br>
<div class="container mt-4">

<h4>
Checklist de {{ ucfirst($tipo) }}
</h4>

<p>
<strong>Vehículo:</strong>
{{ $salida->vehiculo->placa }} - {{ $salida->vehiculo->marca }}
</p>

<hr>

<ul class="list-group">
    <li class="list-group-item">
        <strong>Nivel gasolina:</strong> {{ $checklist->nivel_gasolina }}
    </li>
    <li class="list-group-item">
        <strong>Kilometraje:</strong> {{ $checklist->kilometraje }}
    </li>
    <li class="list-group-item">
        <strong>Limpio exterior:</strong>
        {{ $checklist->limpio_exterior ? 'Sí' : 'No' }}
    </li>
    <li class="list-group-item">
        <strong>Limpio interior:</strong>
        {{ $checklist->limpio_interior ? 'Sí' : 'No' }}
    </li>
    <li class="list-group-item">
        <strong>Observaciones:</strong>
        {{ $checklist->observaciones ?? '—' }}
    </li>
</ul>

<hr>

<h5>Herramientas</h5>

<table class="table table-sm table-bordered">
<thead>
<tr>
    <th>Herramienta</th>
    <th>Disponible</th>
</tr>
</thead>
<tbody>
@foreach($checklist->herramientas as $herramienta)
<tr>
    <td>{{ ucfirst(str_replace('_',' ',$herramienta->herramienta)) }}</td>
    <td>
        @if($herramienta->disponible)
            <span class="badge bg-success">Sí</span>
        @else
            <span class="badge bg-danger">No</span>
        @endif
    </td>
</tr>
@endforeach
</tbody>
</table>

<a href="{{ route('salidas.index') }}" class="btn btn-secondary">
Volver
</a>

</div>
@endsection
