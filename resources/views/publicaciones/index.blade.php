@extends('adminlte::page')

@section('title', 'Publicaciones')

@section('css')
<style>
    #my-notification .dropdown-menu {
        max-height: 320px;
        width: 360px;
        max-width: 90vw;
        overflow-y: auto;
    }

    #my-notification .dropdown-item {
        white-space: normal;
        word-break: break-word;
    }

    .publicaciones-summary-card .small-box {
        margin-bottom: 0;
        border-radius: .5rem;
        box-shadow: none;
    }

    .publicaciones-thumb,
    .publicaciones-thumb-empty {
        width: 58px;
        height: 58px;
        border-radius: .35rem;
    }

    .publicaciones-thumb {
        object-fit: cover;
        border: 1px solid #dee2e6;
    }

    .publicaciones-thumb-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4f6f9;
        border: 1px dashed #ced4da;
        color: #6c757d;
    }

    .publicaciones-title {
        font-weight: 600;
        color: #212529;
        line-height: 1.2;
    }

    .publicaciones-slug {
        font-size: .8rem;
        color: #6c757d;
        word-break: break-word;
    }

    .publicaciones-redes {
        display: flex;
        flex-wrap: wrap;
        gap: .35rem;
    }

    .publicaciones-red {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .55rem;
        border-radius: 999px;
        border: 1px solid #dee2e6;
        background: #fff;
        font-size: .75rem;
        color: #495057;
    }

    .publicaciones-red.is-ok {
        background: #d1e7dd;
        border-color: #badbcc;
        color: #0f5132;
    }

    .publicaciones-red.is-idle {
        background: #f8f9fa;
        color: #6c757d;
    }

    .publicaciones-actions {
        width: 82px;
        text-align: center;
    }

    .publicaciones-table th,
    .publicaciones-table td {
        vertical-align: middle;
    }
</style>
@endsection

@section('content_header')
<br>
<br>
<br>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Publicaciones</h1>
        </div>
        <a href="{{ route('publicaciones.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i>Nueva publicación
        </a>
    </div>
@stop

@section('content')
@php
    $publicacionesColeccion = $publicaciones->getCollection();
    $totalPublicaciones = method_exists($publicaciones, 'total') ? $publicaciones->total() : $publicacionesColeccion->count();
    $publicadasExito = $publicacionesColeccion->filter(fn ($item) => $item->estadoRedes() === 'exito')->count();
    $publicacionesParciales = $publicacionesColeccion->filter(fn ($item) => $item->estadoRedes() === 'parcial')->count();
    $publicacionesPendientes = $publicacionesColeccion->filter(fn ($item) => $item->estadoRedes() === 'pendiente')->count();
@endphp

<div class="container-fluid">

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-warning">
            <div class="font-weight-bold mb-1">
                <i class="fas fa-exclamation-triangle mr-1"></i>Se detectaron detalles por revisar
            </div>
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row publicaciones-summary-card mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalPublicaciones }}</h3>
                    <p>Total de publicaciones</p>
                </div>
                <div class="icon">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $publicadasExito }}</h3>
                    <p>Publicadas correctamente</p>
                </div>
                <div class="icon">
                    <i class="fas fa-share-alt"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $publicacionesParciales }}</h3>
                    <p>Publicación parcial</p>
                </div>
                <div class="icon">
                    <i class="fas fa-stream"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>{{ $publicacionesPendientes }}</h3>
                    <p>Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter mr-1"></i>Filtros de consulta
            </h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('publicaciones.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="tipo">Tipo de publicación</label>
                        <select name="tipo" id="tipo" class="form-control">
                            <option value="">Todos</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->value }}" @selected($tipoSeleccionado === $tipo->value)>{{ $tipo->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="estado">Estado en redes</label>
                        <select name="estado" id="estado" class="form-control">
                            <option value="">Todos</option>
                            <option value="publicados" @selected($estadoSeleccionado === 'publicados')>Publicados</option>
                            <option value="pendientes" @selected($estadoSeleccionado === 'pendientes')>Pendientes</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-search mr-1"></i>Filtrar
                        </button>
                        <a href="{{ route('publicaciones.index') }}" class="btn btn-default">
                            <i class="fas fa-undo-alt mr-1"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#tab-listado" data-toggle="tab" role="tab">
                        <i class="fas fa-list mr-1"></i>Listado de publicaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab-alertas" data-toggle="tab" role="tab">
                        <i class="fas fa-bell mr-1"></i>Alertas editoriales
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-listado" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered publicaciones-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Imagen</th>
                                    <th>Publicación</th>
                                    <th>Tipo</th>
                                    <th>Redes</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
                                    <th class="text-center publicaciones-actions">Ver</th>
                                    <th class="text-center publicaciones-actions">Editar</th>
                                    <th class="text-center publicaciones-actions">Eliminar / Restaurar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($publicaciones as $publicacion)
                                    @php
                                        $estadoRedes = $publicacion->estadoRedes();
                                        $badgeEstado = match ($estadoRedes) {
                                            'exito' => 'success',
                                            'parcial' => 'warning',
                                            'error' => 'danger',
                                            default => 'secondary',
                                        };
                                        $labelEstado = match ($estadoRedes) {
                                            'exito' => 'Publicado',
                                            'parcial' => 'Parcial',
                                            'error' => 'Con error',
                                            default => 'Pendiente',
                                        };
                                        $resultado = collect($publicacion->resultado_publicacion ?? []);
                                    @endphp
                                    <tr class="{{ $publicacion->trashed() ? 'table-danger' : '' }}">
                                        <td>
                                            @if ($publicacion->imagen_url)
                                                <img
                                                    src="{{ $publicacion->imagen_url }}"
                                                    alt="{{ $publicacion->imagen_alt ?: $publicacion->titulo }}"
                                                    class="publicaciones-thumb">
                                            @else
                                                <div class="publicaciones-thumb-empty">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="publicaciones-title">{{ $publicacion->titulo }}</div>
                                            <div class="publicaciones-slug mb-1">{{ $publicacion->slug }}</div>
                                            <small class="text-muted">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($publicacion->contenido), 110) }}
                                            </small>
                                            @if ($publicacion->trashed())
                                                <div class="small text-danger mt-1">
                                                    Eliminada el {{ optional($publicacion->deleted_at)->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @php($tipoEnum = \App\Enums\TipoPublicacion::from($publicacion->tipo))
                                            <span class="badge bg-{{ $tipoEnum->color() }}">{{ $tipoEnum->label() }}</span>
                                        </td>
                                        <td>
                                            <div class="publicaciones-redes">
                                                @foreach ($redesCatalogo as $red)
                                                    @php($item = $resultado->get($red->value))
                                                    @php($esObjetivo = in_array($red->value, $publicacion->redes_objetivo ?? [], true))
                                                    <span class="publicaciones-red {{ ($item['exito'] ?? false) ? 'is-ok' : ($esObjetivo ? '' : 'is-idle') }}">
                                                        <i class="{{ $red->icono() }}"></i>
                                                        <span>{{ $red->label() }}</span>
                                                        @if ($esObjetivo)
                                                            <i class="fas {{ ($item['exito'] ?? false) ? 'fa-check-circle' : 'fa-minus-circle' }}"></i>
                                                        @else
                                                            <i class="fas fa-ban"></i>
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
                                        <td class="text-center">
                                            <a href="{{ route('publicaciones.show', $publicacion) }}" class="btn btn-sm btn-info" title="Ver publicación">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('publicaciones.edit', $publicacion) }}" class="btn btn-sm btn-warning" title="Editar publicación">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @if ($publicacion->trashed())
                                                <form method="POST" action="{{ route('publicaciones.restaurar', $publicacion->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Restaurar publicación">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('publicaciones.destroy', $publicacion) }}" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta publicación?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar publicación">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>No hay publicaciones registradas todavía.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-alertas" role="tabpanel">
                    @if ($publicacionesPendientes > 0)
                        <div class="alert alert-secondary">
                            <h6 class="mb-2">Publicaciones pendientes ({{ $publicacionesPendientes }})</h6>
                            <p class="mb-0">Existen publicaciones que aún no registran envío exitoso a redes sociales.</p>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle mr-1"></i>No hay publicaciones pendientes en esta vista.
                        </div>
                    @endif

                    @if ($publicacionesParciales > 0)
                        <div class="alert alert-warning">
                            <h6 class="mb-2">Publicaciones con seguimiento parcial ({{ $publicacionesParciales }})</h6>
                            <p class="mb-0">Algunas publicaciones tienen resultados mixtos entre redes objetivo configuradas.</p>
                        </div>
                    @endif

                    @if ($publicacionesColeccion->whereNotNull('deleted_at')->count() > 0)
                        <div class="alert alert-danger mb-0">
                            <h6 class="mb-2">Publicaciones eliminadas lógicamente</h6>
                            <p class="mb-0">En esta vista hay registros eliminados que pueden restaurarse desde la columna de acciones.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($publicaciones->hasPages())
            <div class="card-footer">
                {{ $publicaciones->links() }}
            </div>
        @endif
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('js/session-handler.js') }}"></script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";

    document.addEventListener('DOMContentLoaded', function () {
        const notificationMenu = document.querySelector('#my-notification .dropdown-menu');
        if (!notificationMenu) {
            return;
        }

        const normalizeNotificationMenu = () => {
            const items = notificationMenu.querySelectorAll('.dropdown-item');
            items.forEach((item) => {
                const text = (item.textContent || '').trim().toLowerCase();
                if (text === 'todas las notificaciones') {
                    item.textContent = 'Ver todas las notificaciones';
                    item.classList.add('font-weight-bold');
                }
            });
        };

        const observer = new MutationObserver(normalizeNotificationMenu);
        observer.observe(notificationMenu, { childList: true, subtree: true });
        normalizeNotificationMenu();
    });
</script>
@endsection
