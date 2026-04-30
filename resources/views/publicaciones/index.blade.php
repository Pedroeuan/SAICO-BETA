@extends('adminlte::page')

@section('title', 'Publicaciones')

@section('content_header')
<br>
<br>
<br>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Publicaciones</h1>
            <small class="text-muted">Gestiona contenido corporativo y publicaciones en redes.</small>
        </div>
        <a href="{{ route('publicaciones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i>Nueva publicacion
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">

    <form method="GET" action="{{ route('publicaciones.index') }}" class="card shadow-sm border-0 mb-4">
        <div class="card-body row g-3 align-items-end">
            <div class="col-md-4">
                <label for="tipo" class="form-label">Tipo</label>
                <select name="tipo" id="tipo" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($tipos as $tipo)
                        <option value="{{ $tipo->value }}" @selected($tipoSeleccionado === $tipo->value)>{{ $tipo->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="estado" class="form-label">Estado en redes</label>
                <select name="estado" id="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="publicados" @selected($estadoSeleccionado === 'publicados')>Publicados</option>
                    <option value="pendientes" @selected($estadoSeleccionado === 'pendientes')>Pendientes</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary flex-grow-1">
                    <i class="fas fa-filter mr-1"></i>Filtrar
                </button>
                <a href="{{ route('publicaciones.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Imagen</th>
                        <th>Titulo</th>
                        <th>Tipo</th>
                        <th>Redes publicadas</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($publicaciones as $publicacion)
                        @php
                            $estadoRedes = $publicacion->estadoRedes();
                            $badgeEstado = match ($estadoRedes) {
                                'exito' => 'success',
                                'parcial' => 'warning text-dark',
                                'error' => 'danger',
                                default => 'secondary',
                            };
                            $labelEstado = match ($estadoRedes) {
                                'exito' => 'Publicado',
                                'parcial' => 'Parcial',
                                'error' => 'Error',
                                default => 'Pendiente',
                            };
                            $resultado = collect($publicacion->resultado_publicacion ?? []);
                        @endphp
                        <tr class="{{ $publicacion->trashed() ? 'table-danger' : '' }}">
                            <td>
                                @if ($publicacion->imagen_url)
                                    <img src="{{ $publicacion->imagen_url }}" alt="{{ $publicacion->imagen_alt ?: $publicacion->titulo }}" class="rounded object-fit-cover border" width="50" height="50">
                                @else
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $publicacion->titulo }}</div>
                                <div class="small text-muted">{{ $publicacion->slug }}</div>
                                @if ($publicacion->trashed())
                                    <div class="small text-danger">Eliminada el {{ optional($publicacion->deleted_at)->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                            <td>
                                @php($tipoEnum = \App\Enums\TipoPublicacion::from($publicacion->tipo))
                                <span class="badge bg-{{ $tipoEnum->color() }}">{{ $tipoEnum->label() }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach (\App\Enums\RedSocial::cases() as $red)
                                        @php($item = $resultado->get($red->value))
                                        <span class="badge rounded-pill {{ ($item['exito'] ?? false) ? 'text-bg-success' : 'text-bg-light border text-dark' }}">
                                            <i class="{{ $red->icono() }} me-1"></i>
                                            @if (in_array($red->value, $publicacion->redes_objetivo ?? [], true))
                                                <i class="bi {{ ($item['exito'] ?? false) ? 'bi-check-lg' : 'bi-x-lg' }}"></i>
                                            @else
                                                <i class="bi bi-dash"></i>
                                            @endif
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $badgeEstado }}">{{ $labelEstado }}</span>
                            </td>
                            <td>
                                <div>{{ $publicacion->created_at?->format('d/m/Y') }}</div>
                                <div class="small text-muted">{{ $publicacion->created_at?->format('H:i') }}</div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('publicaciones.show', $publicacion) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('publicaciones.edit', $publicacion) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($publicacion->trashed())
                                        <form method="POST" action="{{ route('publicaciones.restaurar', $publicacion->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('publicaciones.destroy', $publicacion) }}" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta publicacion?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No hay publicaciones registradas todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($publicaciones->hasPages())
            <div class="card-footer bg-white">
                {{ $publicaciones->links() }}
            </div>
        @endif
    </div>
</div>
@stop
