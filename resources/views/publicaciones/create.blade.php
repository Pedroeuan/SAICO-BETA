@extends('adminlte::page')

@section('title', 'Nueva Publicacion')

@section('css')
<style>
    .publicacion-form-card,
    .publicacion-side-card {
        box-shadow: none;
    }

    .publicacion-block {
        border: 1px solid #dee2e6;
        border-radius: .5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #fff;
    }

    .publicacion-block__title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: .35rem;
        color: #343a40;
    }

    .publicacion-block__hint {
        font-size: .85rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .publicacion-red-card {
        border: 1px solid #dee2e6;
        border-radius: .5rem;
        padding: .85rem 1rem;
        background: #fff;
        height: 100%;
        cursor: pointer;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    .publicacion-red-card:hover {
        border-color: #adb5bd;
    }

    .publicacion-preview {
        min-height: 230px;
        border: 1px solid #dee2e6;
        border-radius: .5rem;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .publicacion-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .publicacion-counter {
        font-size: .8rem;
        color: #6c757d;
    }

    .publicacion-form-footer .btn {
        min-width: 150px;
    }
</style>
@endsection

@section('content_header')
<br>
<br>
<br>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Nueva publicacion</h1>
            <small class="text-muted">Captura contenido corporativo listo para difundirse desde SAICO.</small>
        </div>
        <a href="{{ route('publicaciones.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i>Volver
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="font-weight-bold mb-1">No fue posible guardar la publicacion.</div>
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('publicaciones.store') }}" enctype="multipart/form-data" class="row" id="publicacion-form">
        @csrf

        <div class="col-lg-8">
            <div class="card card-primary card-outline publicacion-form-card">
                <div class="card-header">
                    <h3 class="card-title">Datos principales de la publicacion</h3>
                </div>
                <div class="card-body">
                    <div class="publicacion-block">
                        <div class="publicacion-block__title">Contenido editorial</div>
                        <div class="publicacion-block__hint">Define el mensaje principal, el tipo de publicacion y el contenido que se enviara al sitio y a redes.</div>

                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" maxlength="150" minlength="5" required value="{{ old('titulo') }}">
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Describe el mensaje principal de la publicacion.</small>
                                <span class="publicacion-counter"><span data-counter-for="titulo">0</span>/150</span>
                            </div>
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="contenido">Contenido</label>
                            <textarea class="form-control @error('contenido') is-invalid @enderror" id="contenido" name="contenido" rows="9" maxlength="3000" minlength="20" required>{{ old('contenido') }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Incluye contexto, beneficios o llamada a la accion.</small>
                                <span class="publicacion-counter"><span data-counter-for="contenido">0</span>/3000</span>
                            </div>
                            @error('contenido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipo">Tipo de publicacion</label>
                                    <select class="form-control @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                        <option value="">Selecciona...</option>
                                        @foreach ($tipos as $tipo)
                                            <option value="{{ $tipo->value }}" @selected(old('tipo') === $tipo->value)>{{ $tipo->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imagen">Imagen principal</label>
                                    <input class="form-control @error('imagen') is-invalid @enderror" type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp" required>
                                    <small class="text-muted d-block mt-1">JPG, PNG o WEBP. Maximo 5 MB.</small>
                                    @error('imagen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="publicacion-block">
                        <div class="publicacion-block__title">Redes sociales objetivo</div>
                        <div class="publicacion-block__hint">Por ahora el modulo opera solo con Facebook en produccion. Instagram y LinkedIn quedan fuera hasta nueva habilitacion.</div>

                        <div class="row">
                            @foreach ($redes as $red)
                                <div class="col-md-4 mb-3">
                                    <label class="publicacion-red-card mb-0 w-100">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input @error('redes') is-invalid @enderror" type="checkbox" name="redes[]" value="{{ $red->value }}" id="red-{{ $red->value }}" @checked(in_array($red->value, old('redes', ['facebook']), true))>
                                            <span class="form-check-label font-weight-bold">
                                                <i class="{{ $red->icono() }} mr-2"></i>{{ $red->label() }}
                                            </span>
                                        </div>
                                        <small class="text-muted d-block">{{ $red->descripcion() }}</small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('redes') <div class="text-danger small">{{ $message }}</div> @enderror
                        @error('redes.*') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="publicacion-block mb-0">
                        <div class="publicacion-block__title">Configuracion avanzada</div>
                        <div class="publicacion-block__hint">Campos opcionales para enriquecer la publicacion o programarla para otra fecha y hora en Facebook.</div>

                        <div class="form-group">
                            <label for="url_destino">URL de destino</label>
                            <input type="url" class="form-control @error('url_destino') is-invalid @enderror" id="url_destino" name="url_destino" maxlength="500" value="{{ old('url_destino') }}" placeholder="https://tu-dominio.com/servicio">
                            @error('url_destino') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="imagen_alt">Texto alternativo de la imagen</label>
                            <input type="text" class="form-control @error('imagen_alt') is-invalid @enderror" id="imagen_alt" name="imagen_alt" maxlength="200" value="{{ old('imagen_alt') }}" placeholder="Describe la escena de la imagen">
                            @error('imagen_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="programar_publicacion" name="programar_publicacion" @checked(old('programar_publicacion'))>
                            <label class="form-check-label" for="programar_publicacion">
                                Programar publicacion automatica
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">Activalo si quieres que el sistema publique solo, por ejemplo el domingo a cierta hora.</small>

                        <div class="form-group mt-3 mb-0" id="programado-wrapper">
                            <label for="programado_at">Fecha y hora programada</label>
                            <input type="datetime-local" class="form-control @error('programado_at') is-invalid @enderror" id="programado_at" name="programado_at" value="{{ old('programado_at') }}">
                            <small class="text-muted d-block mt-1">Usa la hora local del sistema para definir cuando debe publicarse.</small>
                            @error('programado_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white text-right publicacion-form-footer">
                    <a href="{{ route('publicaciones.index') }}" class="btn btn-default mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary" id="submit-button">
                        <span class="spinner-border spinner-border-sm mr-2 d-none" id="submit-spinner" role="status" aria-hidden="true"></span>
                        <span id="submit-label">Guardar y publicar</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card publicacion-side-card">
                <div class="card-header">
                    <h3 class="card-title">Vista previa</h3>
                </div>
                <div class="card-body">
                    <div class="publicacion-preview mb-3">
                        <img id="imagen-preview" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 900'><rect width='1200' height='900' fill='%23f8f9fa'/><text x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' fill='%236c757d' font-family='Arial, sans-serif' font-size='48'>Vista previa</text></svg>" alt="Vista previa de la imagen">
                    </div>
                    <p class="text-muted small mb-0">La imagen es obligatoria y tambien se utilizara para Instagram cuando esa red este seleccionada.</p>
                </div>
            </div>

            <div class="card publicacion-side-card">
                <div class="card-header">
                    <h3 class="card-title">Recomendaciones</h3>
                </div>
                <div class="card-body">
                    <ul class="mb-0 pl-3 text-muted">
                        <li>Usa un titulo claro y directo.</li>
                        <li>Manten el contenido enfocado al objetivo del mensaje.</li>
                        <li>Agrega URL solo cuando dirija a un recurso util.</li>
                        <li>La autopublicacion productiva actual esta habilitada solo para Facebook.</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const titulo = document.getElementById('titulo');
    const contenido = document.getElementById('contenido');
    const imagen = document.getElementById('imagen');
    const preview = document.getElementById('imagen-preview');
    const form = document.getElementById('publicacion-form');
    const submitButton = document.getElementById('submit-button');
    const submitLabel = document.getElementById('submit-label');
    const submitSpinner = document.getElementById('submit-spinner');
    const programarPublicacion = document.getElementById('programar_publicacion');
    const programadoWrapper = document.getElementById('programado-wrapper');
    const programadoAt = document.getElementById('programado_at');

    function updateCounter(fieldId) {
        const field = document.getElementById(fieldId);
        const counter = document.querySelector('[data-counter-for="' + fieldId + '"]');
        if (field && counter) {
            counter.textContent = field.value.length;
        }
    }

    function updateProgramacion() {
        const programada = programarPublicacion.checked;
        programadoWrapper.classList.toggle('d-none', !programada);
        programadoAt.required = programada;
        submitLabel.textContent = programada ? 'Guardar y programar' : 'Guardar y publicar';
    }

    titulo.addEventListener('input', function () {
        updateCounter('titulo');
    });

    contenido.addEventListener('input', function () {
        updateCounter('contenido');
    });

    imagen.addEventListener('change', function (event) {
        const file = event.target.files && event.target.files[0];
        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            return;
        }

        preview.src = URL.createObjectURL(file);
    });

    programarPublicacion.addEventListener('change', updateProgramacion);

    form.addEventListener('submit', function () {
        submitButton.setAttribute('disabled', 'disabled');
        submitSpinner.classList.remove('d-none');
    });

    updateCounter('titulo');
    updateCounter('contenido');
    updateProgramacion();
});
</script>
@endsection
