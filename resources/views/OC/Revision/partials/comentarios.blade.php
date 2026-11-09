@php
    $comentariosRevision = $comentariosRevision ?? collect();
    $comentariosVentas = $comentariosVentas ?? collect();
@endphp

@if ($comentariosRevision->isNotEmpty())
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="fas fa-pen"></i> Comentarios de revisión</h3>
        </div>
        <div class="card-body">
            @foreach ($comentariosRevision as $comentario)
                <div class="{{ $loop->last ? '' : 'border-bottom mb-3 pb-3' }}">
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-1">
                        <strong>
                            <i class="fas fa-user"></i>
                            {{ $comentario->autor ?: 'No registrado' }}
                        </strong>
                        <small class="text-muted text-right">
                            @if (!empty($comentario->revision))
                                <span class="badge badge-warning">{{ $comentario->revision }}</span>
                            @endif
                            @if (!empty($comentario->created_at))
                                {{ $comentario->created_at->format('d/m/Y H:i') }}
                            @endif
                        </small>
                    </div>
                    <pre class="comentarios-revision">{{ $comentario->comentario }}</pre>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if ($comentariosVentas->isNotEmpty())
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="fas fa-comment-dots"></i> Comentarios de ventas</h3>
        </div>
        <div class="card-body">
            @foreach ($comentariosVentas as $comentario)
                <div class="{{ $loop->last ? '' : 'border-bottom mb-3 pb-3' }}">
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-1">
                        <strong>
                            <i class="fas fa-user"></i>
                            {{ $comentario->autor ?: 'Ventas' }}
                        </strong>
                        <small class="text-muted text-right">
                            @if (!empty($comentario->revision))
                                <span class="badge badge-info">{{ $comentario->revision }}</span>
                            @endif
                            @if (!empty($comentario->created_at))
                                {{ $comentario->created_at->format('d/m/Y H:i') }}
                            @endif
                        </small>
                    </div>
                    <pre class="comentarios-revision">{{ $comentario->comentario }}</pre>
                </div>
            @endforeach
        </div>
    </div>
@endif
