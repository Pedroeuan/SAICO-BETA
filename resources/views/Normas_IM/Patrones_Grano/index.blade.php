@extends('adminlte::page')

@section('title', 'Patrones comparativos de grano')

@section('content')
<br>
<br>
<br>
<div class="container-fluid pt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">Patrones comparativos de tamaño de grano</h3>
            <a href="{{ route('Patrones_Grano_IM.create') }}" class="btn btn-primary ml-auto">
                <i class="fas fa-plus mr-1"></i> Registrar patrón
            </a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Vista previa</th>
                        <th style="width:100px">Editar</th>
                        <th style="width:110px">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patrones as $patron)
                        <tr>
                            <td class="align-middle"><strong>{{ $patron->nombre }}</strong></td>
                            <td class="align-middle">
                                <img src="{{ asset('storage/' . $patron->ruta_imagen) }}"
                                    style="max-width:180px; max-height:140px"
                                    class="img-thumbnail" alt="{{ $patron->nombre }}">
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('Patrones_Grano_IM.edit', $patron) }}" class="btn btn-warning" aria-label="Editar {{ $patron->nombre }}">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                            <td class="align-middle">
                                <form method="post" action="{{ route('Patrones_Grano_IM.destroy', $patron) }}"
                                    onsubmit="return confirm('¿Desea eliminar este patrón? Los reportes históricos conservarán su copia.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" aria-label="Eliminar {{ $patron->nombre }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">No hay patrones registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop
