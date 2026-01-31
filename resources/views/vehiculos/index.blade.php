@extends('layouts.simple') 

@section('content')
<div class="container">
    <h4>Listado de Vehiculos</h4>

    <a href="{{ route('vehiculos.create') }}" class="btn btn-primary mb-3">+ Nuevo Vehiculo </a>
<table class="table table-bordered" table-sm>
    <thead>
        <tr>
            <th>ID</th>
            <th>Placa</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Año</th>
            <th>Estatus</th>
            <th>Acciones</th>
</tr>
</thead>
    <tbody>
        @forelse ($vehiculos as $vehiculo)
        <tr>
            <td>{{ $vehiculo->id}}</td>
            <td>{{ $vehiculo->placa}}</td>
            <td>{{ $vehiculo->marca}}</td>
            <td>{{ $vehiculo->modelo}}</td>
            <td>{{ $vehiculo->anio}}</td>
            <td>{{ $vehiculo->estatus}}</td>
              <td>
            <a href="{{route('vehiculos.edit',$vehiculo->id)}}" class="btn btn-sm btn-warning"> Editar</a>
            <form action="{{ route('vehiculos.destroy', $vehiculo->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar vehiculo?')">Eliminar</button>
        </form>
        </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No hay vehiculos registrados.</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection
