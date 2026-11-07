
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
            {{--@if (!empty($OC->Revision_OC))
                <span class="badge badge-info ml-2">{{ $OC->Revision_OC }}</span>
            @endif--}}
            @if (!empty($OC->Estatus))
                <span class="badge badge-secondary ml-1">{{ $OC->Estatus }}</span>
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

@if (!empty($OC->Comentario_Ventas))
    <div class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="fas fa-comment-dots"></i> Comentario de ventas</h3>
            <div class="card-tools">
                <small class="text-muted">
                    {{ $OC->Comentario_Ventas_Autor ?? 'Ventas' }}
                    @if (!empty($OC->Comentario_Ventas_At))
                        · {{ \Carbon\Carbon::parse($OC->Comentario_Ventas_At)->format('d/m/Y H:i') }}
                    @endif
                </small>
            </div>
        </div>
        <div class="card-body">
            <pre class="comentarios-revision">{{ $OC->Comentario_Ventas }}</pre>
        </div>
    </div>
@endif

@can('ventas-access')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="fas fa-comment"></i> Agregar comentario de ventas</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('OC.comentarioVentas', ['id' => $id]) }}" method="POST">
                @csrf
                <div class="form-group mb-2">
                    <label for="Comentario_Ventas">Escribe tu comentario sobre esta revisión</label>
                    <textarea
                        name="Comentario_Ventas"
                        id="Comentario_Ventas"
                        class="form-control @error('Comentario_Ventas') is-invalid @enderror"
                        rows="4"
                        maxlength="4000"
                        required
                        placeholder="Ejemplo: Ya corregí los puntos marcados; favor de revisar...">{{ old('Comentario_Ventas', $OC->Comentario_Ventas) }}</textarea>
                    @error('Comentario_Ventas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Guardar comentario
                </button>
                <small class="text-muted ml-2">El revisor podrá ver este comentario.</small>
            </form>
        </div>
    </div>
@endcan

@if (!empty($OC->Revision_OC))
    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title mb-0"><i class="fas fa-pen"></i> Comentarios de revisión</h3>
        </div>
        <div class="card-body">
            <pre class="comentarios-revision">{{ $OC->Revision_OC }}</pre>
        </div>
    </div>
@endif
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
@endsection
