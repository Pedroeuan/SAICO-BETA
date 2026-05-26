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

    .publicaciones-folio {
        display: inline-flex;
        align-items: center;
        padding: .2rem .5rem;
        border-radius: 999px;
        background: #eef2f7;
        color: #334155;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .03em;
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

    .publicaciones-impacto {
        min-width: 210px;
    }

    .publicaciones-impacto-badge {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .25rem .55rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
    }

    .publicaciones-impacto-badge.is-active {
        background: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }

    .publicaciones-impacto-badge.is-idle {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .publicaciones-analitica-card {
        border: 1px solid #e9ecef;
        border-radius: .75rem;
        padding: 1rem;
        height: 100%;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }

    .publicaciones-analitica-kicker {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
    }

    .publicaciones-analitica-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2d3d;
        line-height: 1;
    }

    .publicaciones-analitica-note {
        font-size: .85rem;
        color: #6c757d;
    }

    .publicaciones-destacada {
        border: 1px solid #dbe7f3;
        border-radius: .75rem;
        background: #f8fbff;
        padding: 1rem;
    }

    .publicaciones-chart-wrap {
        position: relative;
        min-height: 290px;
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
    $publicacionesImportadas = $publicacionesColeccion->filter(fn ($item) => $item->esImportadaDesdeFacebook())->count();
@endphp

@php
    $graficaTopPublicaciones = $panelAnalitica['graficaTop'] ?? [
        'labels' => [],
        'reacciones' => [],
        'comentarios' => [],
        'compartidos' => [],
    ];
    $graficaTimelinePublicaciones = $panelAnalitica['graficaLinea'] ?? [
        'labels' => [],
        'interacciones' => [],
    ];
    $graficaEstadoInteraccion = $panelAnalitica['graficaEstadoInteraccion'] ?? [
        'labels' => ['Con interacción', 'Sin interacción'],
        'values' => [0, 0],
    ];
    $graficaTipoPublicacion = $panelAnalitica['graficaTipoPublicacion'] ?? [
        'labels' => [],
        'values' => [],
    ];
    $graficaComentarios = $panelAnalitica['graficaComentarios'] ?? [
        'labels' => [],
        'values' => [],
    ];
    $graficaReacciones = $panelAnalitica['graficaReacciones'] ?? [
        'labels' => [],
        'values' => [],
    ];
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

    <div class="row publicaciones-summary-card mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $publicacionesImportadas }}</h3>
                    <p>Importadas de Facebook</p>
                </div>
                <div class="icon">
                    <i class="fab fa-facebook"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-teal">
                <div class="inner">
                    <h3>{{ number_format((int) ($resumenMetricas->alcance ?? 0)) }}</h3>
                    <p>Alcance acumulado hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-navy">
                <div class="inner">
                    <h3>{{ number_format((int) (($resumenMetricas->reacciones ?? 0) + ($resumenMetricas->comentarios ?? 0) + ($resumenMetricas->compartidos ?? 0))) }}</h3>
                    <p>Interacciones hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ number_format((float) ($resumenMetricas->engagement_promedio ?? 0), 2) }}%</h3>
                    <p>Engagement promedio</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
    </div>

    @if(false)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar mr-1"></i>Panel de rendimiento en publicaciones
            </h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="publicaciones-analitica-card">
                        <div class="publicaciones-analitica-kicker">Interacciones visibles</div>
                        <div class="publicaciones-analitica-value">{{ number_format((int) ($panelAnalitica['interaccionesVisibles'] ?? 0)) }}</div>
                        <div class="publicaciones-analitica-note">Suma de reacciones, comentarios y compartidos disponibles.</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="publicaciones-analitica-card">
                        <div class="publicaciones-analitica-kicker">Publicaciones con respuesta</div>
                        <div class="publicaciones-analitica-value">{{ number_format((int) ($panelAnalitica['totalConInteraccion'] ?? 0)) }}</div>
                        <div class="publicaciones-analitica-note">Contenido que ya registró actividad visible del público.</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="publicaciones-analitica-card">
                        <div class="publicaciones-analitica-kicker">Sin interacción visible</div>
                        <div class="publicaciones-analitica-value">{{ number_format((int) ($panelAnalitica['totalSinInteraccion'] ?? 0)) }}</div>
                        <div class="publicaciones-analitica-note">Publicaciones importadas o sincronizadas sin reacción observable.</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="publicaciones-destacada h-100">
                        <div class="publicaciones-analitica-kicker mb-2">Publicación destacada</div>
                        @if (!empty($panelAnalitica['publicacionDestacada']))
                            <div class="font-weight-bold mb-2">{{ $panelAnalitica['publicacionDestacada']['titulo'] }}</div>
                            <div class="small text-muted mb-2">
                                Según interacciones visibles sincronizadas.
                            </div>
                            <div class="small"><strong>Total:</strong> {{ number_format((int) $panelAnalitica['publicacionDestacada']['interacciones']) }}</div>
                            <div class="small"><strong>Reacciones:</strong> {{ number_format((int) $panelAnalitica['publicacionDestacada']['reacciones']) }}</div>
                            <div class="small"><strong>Comentarios:</strong> {{ number_format((int) $panelAnalitica['publicacionDestacada']['comentarios']) }}</div>
                            <div class="small"><strong>Último sync:</strong> {{ $panelAnalitica['publicacionDestacada']['sincronizado_metricas_at'] ?: 'Pendiente' }}</div>
                        @else
                            <div class="small text-muted">
                                Todavía no hay publicaciones con actividad suficiente para destacar una pieza.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-column mr-1"></i>Gráfica de publicaciones destacadas
            </h3>
        </div>
        <div class="card-body">
            <div class="publicaciones-chart-wrap">
                <canvas id="publicacionesTopChart"></canvas>
            </div>
            <div class="small text-muted mt-3">
                Comparativo visible por reacciones, comentarios y compartidos.
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-line mr-1"></i>Evolución reciente de interacciones
            </h3>
        </div>
        <div class="card-body">
            <div class="publicaciones-chart-wrap">
                <canvas id="publicacionesTimelineChart"></canvas>
            </div>
            <div class="small text-muted mt-3">
                Alcance, impresiones y engagement siguen sujetos al nivel de acceso habilitado por Meta.
            </div>
        </div>
    </div>

    @endif
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
                    <a class="nav-link active" href="#tab-dashboard" data-toggle="tab" role="tab">
                        <i class="fas fa-chart-pie mr-1"></i>Dashboard de publicaciones
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab-listado" data-toggle="tab" role="tab">
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
                <div class="tab-pane fade show active" id="tab-dashboard" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="publicaciones-analitica-card">
                                <div class="publicaciones-analitica-kicker">Interacciones visibles</div>
                                <div class="publicaciones-analitica-value">{{ number_format((int) ($panelAnalitica['interaccionesVisibles'] ?? 0)) }}</div>
                                <div class="publicaciones-analitica-note">Suma de reacciones, comentarios y compartidos disponibles.</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="publicaciones-analitica-card">
                                <div class="publicaciones-analitica-kicker">Publicaciones con respuesta</div>
                                <div class="publicaciones-analitica-value">{{ number_format((int) ($panelAnalitica['totalConInteraccion'] ?? 0)) }}</div>
                                <div class="publicaciones-analitica-note">Contenido que ya registró actividad visible del público.</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="publicaciones-analitica-card">
                                <div class="publicaciones-analitica-kicker">Sin interacción visible</div>
                                <div class="publicaciones-analitica-value">{{ number_format((int) ($panelAnalitica['totalSinInteraccion'] ?? 0)) }}</div>
                                <div class="publicaciones-analitica-note">Publicaciones importadas o sincronizadas sin reacción observable.</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="publicaciones-destacada h-100">
                                <div class="publicaciones-analitica-kicker mb-2">Publicación destacada</div>
                                @if (!empty($panelAnalitica['publicacionDestacada']))
                                    <div class="mb-2">
                                        <span class="publicaciones-folio">{{ $panelAnalitica['publicacionDestacada']['folio'] }}</span>
                                    </div>
                                    <div class="font-weight-bold mb-2" title="{{ $panelAnalitica['publicacionDestacada']['titulo'] }}">
                                        <a href="{{ route('publicaciones.show', $panelAnalitica['publicacionDestacada']['id']) }}" class="text-dark">
                                            {{ $panelAnalitica['publicacionDestacada']['titulo_corto'] }}
                                        </a>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        Según interacciones visibles sincronizadas.
                                    </div>
                                    <div class="small"><strong>Total:</strong> {{ number_format((int) $panelAnalitica['publicacionDestacada']['interacciones']) }}</div>
                                    <div class="small"><strong>Reacciones:</strong> {{ number_format((int) $panelAnalitica['publicacionDestacada']['reacciones']) }}</div>
                                    <div class="small"><strong>Comentarios:</strong> {{ number_format((int) $panelAnalitica['publicacionDestacada']['comentarios']) }}</div>
                                    <div class="small"><strong>Último sync:</strong> {{ $panelAnalitica['publicacionDestacada']['sincronizado_metricas_at'] ?: 'Pendiente' }}</div>
                                @else
                                    <div class="small text-muted">
                                        Todavía no hay publicaciones con actividad suficiente para destacar una pieza.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Gráfica de publicaciones destacadas</h3>
                        </div>
                        <div class="card-body">
                            <div class="publicaciones-chart-wrap">
                                <canvas id="publicacionesTopChart"></canvas>
                            </div>
                            <div class="small text-muted mt-3">
                                Comparativo visible por reacciones, comentarios y compartidos.
                            </div>
                        </div>
                    </div>

                    <div class="card card-outline card-info mb-0">
                        <div class="card-header">
                            <h3 class="card-title">Evolución reciente de interacciones</h3>
                        </div>
                        <div class="card-body">
                            <div class="publicaciones-chart-wrap">
                                <canvas id="publicacionesTimelineChart"></canvas>
                            </div>
                            <div class="small text-muted mt-3">
                                Alcance, impresiones y engagement siguen sujetos al nivel de acceso habilitado por Meta.
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-6 mb-4">
                            <div class="card card-outline card-secondary h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Pastel: interacción visible</h3>
                                </div>
                                <div class="card-body">
                                    <div class="publicaciones-chart-wrap">
                                        <canvas id="publicacionesEstadoChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card card-outline card-secondary h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Pastel: interacciones por tipo</h3>
                                </div>
                                <div class="card-body">
                                    <div class="publicaciones-chart-wrap">
                                        <canvas id="publicacionesTipoChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-0">
                            <div class="card card-outline card-success h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Ranking de publicaciones más comentadas</h3>
                                </div>
                                <div class="card-body">
                                    <div class="publicaciones-chart-wrap">
                                        <canvas id="publicacionesComentariosChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-0">
                            <div class="card card-outline card-primary h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Ranking de publicaciones más reaccionadas</h3>
                                </div>
                                <div class="card-body">
                                    <div class="publicaciones-chart-wrap">
                                        <canvas id="publicacionesReaccionesChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-listado" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered publicaciones-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Imagen</th>
                                    <th>Publicación</th>
                                    <th>Origen</th>
                                    <th>Tipo</th>
                                    <th>Redes</th>
                                    <th>Impacto Facebook</th>
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
                                        $folioPublicacion = 'PUB-' . $publicacion->id;
                                        $tituloCorto = \Illuminate\Support\Str::limit($publicacion->titulo, 58);
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
                                            <div class="mb-2">
                                                <span class="publicaciones-folio">{{ $folioPublicacion }}</span>
                                            </div>
                                            <div class="publicaciones-title" title="{{ $publicacion->titulo }}">
                                                <a href="{{ route('publicaciones.show', $publicacion) }}" class="text-dark">
                                                    {{ $tituloCorto }}
                                                </a>
                                            </div>
                                            <div class="publicaciones-slug mb-1" title="{{ $publicacion->slug }}">{{ \Illuminate\Support\Str::limit($publicacion->slug, 70) }}</div>
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
                                            <span class="badge bg-{{ $publicacion->esImportadaDesdeFacebook() ? 'primary' : 'dark' }}">
                                                {{ $publicacion->esImportadaDesdeFacebook() ? 'Facebook importada' : 'Sistema web' }}
                                            </span>
                                            @if ($publicacion->facebook_post_id)
                                                <div class="small text-muted mt-1">{{ $publicacion->facebook_post_id }}</div>
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
                                            @php($metricas = $publicacion->metricas_facebook_json ?? [])
                                            @php($interacciones = (int) ($metricas['reacciones'] ?? 0) + (int) ($metricas['comentarios'] ?? 0) + (int) ($metricas['compartidos'] ?? 0))
                                            <div class="publicaciones-impacto">
                                                <div class="mb-2">
                                                    <span class="publicaciones-impacto-badge {{ $interacciones > 0 ? 'is-active' : 'is-idle' }}">
                                                        <i class="fas {{ $interacciones > 0 ? 'fa-bolt' : 'fa-minus-circle' }}"></i>
                                                        {{ $interacciones > 0 ? 'Con interaccion real' : 'Sin interaccion visible' }}
                                                    </span>
                                                </div>
                                                <div class="small"><strong>Total:</strong> {{ number_format($interacciones) }}</div>
                                                <div class="small"><strong>Reacciones:</strong> {{ number_format((int) ($metricas['reacciones'] ?? 0)) }}</div>
                                                <div class="small"><strong>Comentarios:</strong> {{ number_format((int) ($metricas['comentarios'] ?? 0)) }}</div>
                                                <div class="small"><strong>Compartidos:</strong> {{ number_format((int) ($metricas['compartidos'] ?? 0)) }}</div>
                                            </div>
                                            @if (($metricas['insights_pendientes_meta'] ?? false) === true)
                                                <div class="small text-muted mt-2">
                                                    Alcance e impresiones pendientes por acceso adicional de Meta.
                                                </div>
                                            @endif
                                            <div class="small text-muted mt-1">
                                                Sync:
                                                @if ($publicacion->estado_sync_metricas === 'ok')
                                                    {{ optional($publicacion->sincronizado_metricas_at)->format('d/m H:i') ?: 'OK' }}
                                                @elseif ($publicacion->estado_sync_metricas === 'error')
                                                    Con error
                                                @else
                                                    Pendiente
                                                @endif
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
                                        <td colspan="11" class="text-center py-4 text-muted">
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
                {{ $publicaciones->links('pagination::bootstrap-4') }}
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
    const publicacionesTopData = @json($graficaTopPublicaciones);
    const publicacionesTimelineData = @json($graficaTimelinePublicaciones);
    const publicacionesEstadoData = @json($graficaEstadoInteraccion);
    const publicacionesTipoData = @json($graficaTipoPublicacion);
    const publicacionesComentariosData = @json($graficaComentarios);
    const publicacionesReaccionesData = @json($graficaReacciones);

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

        if (typeof Chart !== 'undefined') {
            const topChart = document.getElementById('publicacionesTopChart');
            if (topChart) {
                new Chart(topChart, {
                    type: 'bar',
                    data: {
                        labels: publicacionesTopData.labels,
                        datasets: [
                            {
                                label: 'Reacciones',
                                data: publicacionesTopData.reacciones,
                                backgroundColor: '#1877f2',
                                borderRadius: 6,
                            },
                            {
                                label: 'Comentarios',
                                data: publicacionesTopData.comentarios,
                                backgroundColor: '#20c997',
                                borderRadius: 6,
                            },
                            {
                                label: 'Compartidos',
                                data: publicacionesTopData.compartidos,
                                backgroundColor: '#f59f00',
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    maxRotation: 0,
                                    autoSkip: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            const timelineChart = document.getElementById('publicacionesTimelineChart');
            if (timelineChart) {
                new Chart(timelineChart, {
                    type: 'line',
                    data: {
                        labels: publicacionesTimelineData.labels,
                        datasets: [
                            {
                                label: 'Interacciones visibles',
                                data: publicacionesTimelineData.interacciones,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.12)',
                                fill: true,
                                tension: 0.35,
                                pointRadius: 4,
                                pointHoverRadius: 5,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            const estadoChart = document.getElementById('publicacionesEstadoChart');
            if (estadoChart) {
                new Chart(estadoChart, {
                    type: 'pie',
                    data: {
                        labels: publicacionesEstadoData.labels,
                        datasets: [{
                            data: publicacionesEstadoData.values,
                            backgroundColor: ['#198754', '#adb5bd'],
                            borderColor: '#ffffff',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }

            const tipoChart = document.getElementById('publicacionesTipoChart');
            if (tipoChart) {
                new Chart(tipoChart, {
                    type: 'pie',
                    data: {
                        labels: publicacionesTipoData.labels,
                        datasets: [{
                            data: publicacionesTipoData.values,
                            backgroundColor: ['#0d6efd', '#20c997', '#ffc107', '#dc3545', '#6f42c1'],
                            borderColor: '#ffffff',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }

            const comentariosChart = document.getElementById('publicacionesComentariosChart');
            if (comentariosChart) {
                new Chart(comentariosChart, {
                    type: 'bar',
                    data: {
                        labels: publicacionesComentariosData.labels,
                        datasets: [{
                            label: 'Comentarios',
                            data: publicacionesComentariosData.values,
                            backgroundColor: '#20c997',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }

            const reaccionesChart = document.getElementById('publicacionesReaccionesChart');
            if (reaccionesChart) {
                new Chart(reaccionesChart, {
                    type: 'bar',
                    data: {
                        labels: publicacionesReaccionesData.labels,
                        datasets: [{
                            label: 'Reacciones',
                            data: publicacionesReaccionesData.values,
                            backgroundColor: '#1877f2',
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }
        }
    });
</script>
@endsection
