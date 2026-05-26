@extends('adminlte::page')

@section('title', 'Detalle Publicacion')

@section('content_header')
<br>
<br>
<br>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">{{ $publicacion->titulo }}</h1>
            <small class="text-muted">Detalle de la publicación y estado por red.</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('publicaciones.index') }}" class="btn btn-default">
                <i class="fas fa-list mr-1"></i>Listado
            </a>
            <a href="{{ route('publicaciones.edit', $publicacion) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-1"></i>Editar
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @php($metricas = $publicacion->metricas_facebook_json ?? [])
    @php($interaccionesTotales = (int) ($metricas['reacciones'] ?? 0) + (int) ($metricas['comentarios'] ?? 0) + (int) ($metricas['compartidos'] ?? 0))
    <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
        <div>
            <div class="mb-2">
                <span class="badge bg-light text-dark border">PUB-{{ $publicacion->id }}</span>
            </div>
            <h1 class="h3 mb-1">{{ $publicacion->titulo }}</h1>
            <div class="d-flex flex-wrap gap-2">
                @php($tipo = \App\Enums\TipoPublicacion::from($publicacion->tipo))
                <span class="badge bg-{{ $tipo->color() }}">{{ $tipo->label() }}</span>
                <span class="badge bg-{{ $publicacion->activo ? 'success' : 'secondary' }}">{{ $publicacion->activo ? 'Activa' : 'Inactiva' }}</span>
                <span class="badge bg-{{ $publicacion->trashed() ? 'danger' : 'light text-dark border' }}">{{ $publicacion->trashed() ? 'Eliminada' : 'Visible' }}</span>
                <span class="badge bg-{{ $publicacion->esImportadaDesdeFacebook() ? 'primary' : 'dark' }}">
                    {{ $publicacion->esImportadaDesdeFacebook() ? 'Facebook importada' : 'Creada en sistema' }}
                </span>
                <span class="badge bg-{{ $interaccionesTotales > 0 ? 'success' : 'secondary' }}">
                    {{ $interaccionesTotales > 0 ? 'Con interaccion' : 'Sin interaccion visible' }}
                </span>
            </div>
        </div>
        <div class="d-flex gap-2">
            @if ($publicacion->trashed())
                <form method="POST" action="{{ route('publicaciones.restaurar', $publicacion->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-success">
                        <i class="fas fa-undo mr-1"></i>Restaurar
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('publicaciones.destroy', $publicacion) }}" onsubmit="return confirm('¿Seguro que deseas eliminar esta publicacion?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash mr-1"></i>Eliminar
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-0">
                    @if ($publicacion->imagen_url)
                        <img src="{{ $publicacion->imagen_url }}" alt="{{ $publicacion->imagen_alt ?: $publicacion->titulo }}" class="img-fluid w-100 rounded-top" style="max-height: 480px; object-fit: cover;">
                    @else
                        <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded-top" style="height: 320px;">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    @endif
                    <div class="p-4">
                        <h2 class="h5">Contenido</h2>
                        <p class="mb-0" style="white-space: pre-line;">{{ $publicacion->contenido }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5 mb-3">Timeline de actividad</h2>
                    <div class="border-start border-3 ps-3">
                        <div class="mb-4">
                            <div class="fw-semibold">Creada</div>
                            <div class="text-muted small">{{ optional($publicacion->created_at)->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-4">
                            <div class="fw-semibold">Publicacion en redes</div>
                            <div class="text-muted small">{{ optional($publicacion->publicado_at)->format('d/m/Y H:i') ?: 'Pendiente' }}</div>
                        </div>
                        <div class="mb-4">
                            <div class="fw-semibold">Programada para</div>
                            <div class="text-muted small">{{ optional($publicacion->programado_at)->format('d/m/Y H:i') ?: 'Sin programacion' }}</div>
                        </div>
                        @if ($publicacion->trashed())
                            <div>
                                <div class="fw-semibold text-danger">Eliminada logicamente</div>
                                <div class="text-muted small">{{ optional($publicacion->deleted_at)->format('d/m/Y H:i') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h2 class="h5 mb-3">Resumen</h2>
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Slug</dt>
                        <dd class="col-sm-7">{{ $publicacion->slug }}</dd>

                        <dt class="col-sm-5">UUID</dt>
                        <dd class="col-sm-7 small">{{ $publicacion->uuid }}</dd>

                        <dt class="col-sm-5">URL destino</dt>
                        <dd class="col-sm-7">
                            @if ($publicacion->url_destino)
                                <a href="{{ $publicacion->url_destino }}" target="_blank" rel="noopener noreferrer">Abrir enlace</a>
                            @else
                                <span class="text-muted">No definida</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5">Alt imagen</dt>
                        <dd class="col-sm-7">{{ $publicacion->imagen_alt ?: 'Sin texto alternativo' }}</dd>

                        <dt class="col-sm-5">Modo</dt>
                        <dd class="col-sm-7">{{ $publicacion->estaProgramada() ? 'Programada' : 'Publicacion inmediata' }}</dd>

                        <dt class="col-sm-5">Facebook Post ID</dt>
                        <dd class="col-sm-7">{{ $publicacion->facebook_post_id ?: 'No vinculado' }}</dd>

                        <dt class="col-sm-5">Ultimo sync</dt>
                        <dd class="col-sm-7">{{ optional($publicacion->sincronizado_metricas_at)->format('d/m/Y H:i') ?: 'Pendiente' }}</dd>
                    </dl>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="alert alert-info">
                        La operacion productiva actual del modulo esta habilitada solo para Facebook. Instagram y LinkedIn permanecen deshabilitados por configuracion.
                    </div>
                    <h2 class="h5 mb-3">Estado por red social</h2>
                    <div class="list-group list-group-flush">
                        @foreach ($redes as $red)
                            @php($resultado = $publicacion->resultado_publicacion[$red->value] ?? null)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold"><i class="{{ $red->icono() }} me-2"></i>{{ $red->label() }}</div>
                                        <div class="small text-muted">
                                            @if (in_array($red->value, $publicacion->redes_objetivo ?? [], true))
                                                Objetivo configurado
                                            @else
                                                No seleccionada para esta publicacion
                                            @endif
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ ($resultado['exito'] ?? false) ? 'success' : 'secondary' }}">
                                        {{ ($resultado['exito'] ?? false) ? 'OK' : 'Pendiente / Error' }}
                                    </span>
                                </div>
                                @if ($resultado)
                                    <div class="small mt-2">
                                        <div><strong>Post ID:</strong> {{ $resultado['post_id'] ?? 'N/D' }}</div>
                                        <div><strong>Error:</strong> {{ $resultado['error'] ?? 'Sin errores' }}</div>
                                        <div><strong>Fecha:</strong> {{ optional($publicacion->publicado_at)->format('d/m/Y H:i') ?: 'Sin fecha' }}</div>
                                    </div>
                                @elseif (in_array($red->value, $publicacion->redes_objetivo ?? [], true))
                                    <div class="small mt-2 text-muted">
                                        Pendiente de configuracion o de envio a redes sociales.
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h2 class="h5 mb-3">Impacto Facebook</h2>
                    @if (($metricas['insights_pendientes_meta'] ?? false) === true)
                        <div class="alert alert-secondary">
                            Las interacciones visibles ya se sincronizan automaticamente. Alcance, impresiones y engagement siguen pendientes por acceso adicional de Meta.
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <div class="small text-muted">Interacciones totales visibles</div>
                                <div class="h4 mb-0">{{ number_format($interaccionesTotales) }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2 h-100">
                                <div class="small text-muted">Reacciones</div>
                                <div class="h5 mb-0">{{ number_format((int) ($metricas['reacciones'] ?? 0)) }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2 h-100">
                                <div class="small text-muted">Comentarios</div>
                                <div class="h5 mb-0">{{ number_format((int) ($metricas['comentarios'] ?? 0)) }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2 h-100">
                                <div class="small text-muted">Compartidos</div>
                                <div class="h5 mb-0">{{ number_format((int) ($metricas['compartidos'] ?? 0)) }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2 h-100">
                                <div class="small text-muted">Alcance</div>
                                <div class="h5 mb-0">{{ $metricas['alcance'] === null ? 'Pendiente' : number_format((int) $metricas['alcance']) }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2 h-100">
                                <div class="small text-muted">Impresiones</div>
                                <div class="h5 mb-0">{{ $metricas['impresiones'] === null ? 'Pendiente' : number_format((int) $metricas['impresiones']) }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2 h-100">
                                <div class="small text-muted">Engagement</div>
                                <div class="h5 mb-0">{{ $metricas['engagement'] === null ? 'Pendiente' : number_format((float) $metricas['engagement'], 2) . '%' }}</div>
                            </div>
                        </div>
                    </div>

                    @if ($publicacion->facebook_permalink)
                        <a href="{{ $publicacion->facebook_permalink }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook mr-1"></i>Ver publicacion real en Facebook
                        </a>
                    @endif

                    @if ($publicacion->metricasHistorial->isNotEmpty())
                        <hr>
                        <h3 class="h6 mb-3">Ultimos cortes sincronizados</h3>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Alcance</th>
                                        <th>Interacciones</th>
                                        <th>Engagement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($publicacion->metricasHistorial as $corte)
                                        <tr>
                                            <td>{{ optional($corte->fecha_corte)->format('d/m/Y') }}</td>
                                            <td>{{ number_format((int) $corte->alcance) }}</td>
                                            <td>{{ number_format((int) ($corte->reacciones + $corte->comentarios + $corte->compartidos)) }}</td>
                                            <td>{{ number_format((float) $corte->engagement, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop
