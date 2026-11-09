
@extends('adminlte::page')

@section('title', 'OC con marcas de revisión')

@section('css')
<style>
    /* Visor de PDF con marcas */
    #pdfMarcasContenedor {
        height: 80vh;
        overflow: auto;
        background: #525659;
        padding: 10px;
        position: relative;
    }
    .pdf-marca-pagina {
        position: relative;
        margin: 0 auto 12px;
        background: #fff;
        box-shadow: 0 0 6px rgba(0, 0, 0, .45);
    }
    .pdf-marca-pagina canvas {
        display: block;
    }
    .pdf-marca-dibujo {
        position: absolute;
        top: 0;
        left: 0;
        cursor: crosshair;
        touch-action: none;
    }
    /* En esta vista el PDF es de solo lectura. */
    #pdfMarcasContenedor.readonly .pdf-marca-dibujo {
        cursor: default;
        pointer-events: none;
    }
    #pdfMarcasMensaje {
        color: #fff;
        padding: 20px;
        text-align: center;
    }
    .comentarios-revision {
        white-space: pre-wrap;
        overflow-wrap: anywhere;
        word-break: break-word;
        margin: 0;
        font-family: inherit;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')
<br>
<br>
<br>
<br>
<h3 align="center">OC con marcas de revisión</h3>
<br>

@if (session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="card card-outline card-danger">
    <div class="card-header">
        <h3 class="card-title mb-0">
            <i class="far fa-file-pdf"></i> Orden de Compra
            @if (!empty($OC->Estatus))
                <span class="badge badge-secondary ml-1">{{ $OC->Estatus }}</span>
            @endif
            @if (stripos((string) $OC->Estatus, 'Revisado') === 0)
                <span class="badge badge-info ml-1"
                    title="{{ !empty($OC->Revision_At) ? 'Fecha de revisión: ' . \Carbon\Carbon::parse($OC->Revision_At)->format('d/m/Y H:i') : '' }}">
                    <i class="fas fa-user-check"></i>
                    Revisado por: {{ $OC->Revision_Autor ?: 'No registrado' }}
                </span>
            @endif
        </h3>
        <div class="card-tools">
            <a href="{{ route('OC.PDFMarcas', ['id' => $id]) }}" class="btn btn-sm btn-danger" download="OC_{{ $id }}_marcas.pdf">
                <i class="fas fa-download"></i> Descargar PDF con marcas
            </a>
            <a href="{{ route('OC.PDF', ['id' => $id]) }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="fas fa-file-pdf"></i> Abrir PDF sin marcas
            </a>
            <a href="{{ route('OC.edit', ['id' => $id]) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-edit"></i> Volver a edición
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        @if (empty($marcas))
            <div class="alert alert-warning m-3 mb-0">
                <i class="fas fa-exclamation-triangle"></i>
                Esta orden de compra no tiene marcas de revisión guardadas.
            </div>
        @endif

        <div id="pdfMarcasContenedor" class="readonly">
            <div id="pdfMarcasMensaje">Cargando vista previa...</div>
        </div>
    </div>
</div>

<div id="oc-comentarios-historial" data-id="{{ $id }}">
@include('OC.Revision.partials.comentarios', [
    'comentariosRevision' => $comentariosRevision ?? collect(),
    'comentariosVentas' => $comentariosVentas ?? collect(),
])
</div>

@can('ventas-access')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="fas fa-comment"></i> Agregar comentario de ventas</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('OC.comentarioVentas', ['id' => $id]) }}" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <label for="Comentario_Ventas">Escribe un nuevo comentario sobre esta revisión</label>
                    <textarea
                        name="Comentario_Ventas"
                        id="Comentario_Ventas"
                        class="form-control @error('Comentario_Ventas') is-invalid @enderror"
                        rows="4"
                        maxlength="4000"
                        required
                        placeholder="Ejemplo: Ya corregí los puntos marcados; favor de revisar...">{{ old('Comentario_Ventas') }}</textarea>
                    @error('Comentario_Ventas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Agregar comentario
                </button>
                <small class="text-muted ml-2">
                    Cada comentario se guarda por separado y el revisor podrá verlo.
                </small>
            </form>
        </div>
    </div>
@endcan
@stop

@section('js')
<script src="{{ asset('js/pdfjs/pdf.min.js') }}"></script>
<script>
    window.OCRevisionMarcas = {
        pdfUrl: @json(route('OC.PDF', ['id' => $id])),
        workerSrc: @json(asset('js/pdfjs/pdf.worker.min.js')),
        standardFontDataUrl: @json(asset('js/pdfjs/standard_fonts/')),
        marcas: @json($marcas ?? []),
        readonly: true
    };
</script>
<script src="{{ asset('js/OC_revision_marcas.js') }}"></script>

<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script>
(function () {
    const contenedor = document.getElementById('oc-comentarios-historial');
    if (!contenedor) return;
    const url = @json(route('OC.comentariosOC', ['id' => $id]));
    let timer = null;
    async function actualizarComentarios() {
        if (document.hidden) return;
        const activo = document.activeElement;
        if (activo && (activo.tagName === 'TEXTAREA' || activo.tagName === 'INPUT')) return;
        try {
            const respuesta = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!respuesta.ok) return;
            const datos = await respuesta.json();
            if (datos.html && datos.html !== contenedor.innerHTML) {
                contenedor.innerHTML = datos.html;
            }
        } catch (e) {}
    }
    timer = setInterval(actualizarComentarios, 30000);
    window.addEventListener('beforeunload', function () { clearInterval(timer); });
})();
</script>
@endsection
