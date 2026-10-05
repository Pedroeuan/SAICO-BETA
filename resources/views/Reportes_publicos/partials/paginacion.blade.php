<nav aria-label="{{ $etiqueta }}" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin:16px 0">
    <span>{{ $paginador->total() }} resultados · Página {{ $paginador->currentPage() }} de {{ $paginador->lastPage() }}</span>
    @if($paginador->previousPageUrl())<a class="btn-contrato" href="{{ $paginador->previousPageUrl() }}">Anterior</a>@endif
    @if($paginador->nextPageUrl())<a class="btn-contrato" href="{{ $paginador->nextPageUrl() }}">Siguiente</a>@endif
</nav>
