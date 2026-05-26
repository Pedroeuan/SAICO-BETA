@extends('adminlte::page')

@section('title', 'Editar Publicacion')

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
            <h1 class="mb-1">Editar publicacion</h1>
            <small class="text-muted">Actualiza el contenido y administra su continuidad en redes sociales.</small>
        </div>
        <a href="{{ route('publicaciones.show', $publicacion) }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i>Volver al detalle
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    @if (config('publicaciones.solo_lectura_analytics', true))
        <div class="alert alert-warning">
            <i class="fas fa-shield-alt mr-1"></i>La sincronizacion automatica con Facebook esta protegida en este entorno de trabajo. Puedes actualizar la informacion y consultar metricas sin lanzar publicaciones nuevas o republicaciones desde esta vista.
        </div>
    @endif

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

    <form method="POST" action="{{ route('publicaciones.update', $publicacion) }}" enctype="multipart/form-data" class="row" id="publicacion-form">
        @csrf
        @method('PUT')

        <div class="col-lg-8">
            <div class="card card-warning card-outline publicacion-form-card">
                <div class="card-header">
                    <h3 class="card-title">Edicion de la publicacion</h3>
                </div>
                <div class="card-body">
                    <div class="publicacion-block">
                        <div class="publicacion-block__title">Contenido editorial</div>
                        <div class="publicacion-block__hint">Actualiza la informacion principal sin salir del flujo operativo del modulo.</div>

                        <div class="form-group">
                            <label for="titulo">Titulo</label>
                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" maxlength="150" minlength="5" required value="{{ old('titulo', $publicacion->titulo) }}">
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Ajusta el mensaje principal si es necesario.</small>
                                <span class="publicacion-counter"><span data-counter-for="titulo">0</span>/150</span>
                            </div>
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="contenido">Contenido</label>
                            <textarea class="form-control @error('contenido') is-invalid @enderror" id="contenido" name="contenido" rows="9" maxlength="3000" minlength="20" required>{{ old('contenido', $publicacion->contenido) }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Manten claro el beneficio, contexto o llamada a la accion.</small>
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
                                            <option value="{{ $tipo->value }}" @selected(old('tipo', $publicacion->tipo) === $tipo->value)>{{ $tipo->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imagen">Cambiar imagen</label>
                                    <input class="form-control @error('imagen') is-invalid @enderror" type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp">
                                    <small class="text-muted d-block mt-1">Solo sube una nueva si deseas reemplazar la actual.</small>
                                    @error('imagen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="publicacion-block">
                        <div class="publicacion-block__title">Redes sociales objetivo</div>
                        <div class="publicacion-block__hint">Por ahora el modulo opera solo con Facebook en produccion. Instagram y LinkedIn quedan fuera hasta nueva habilitacion.</div>

                        <div class="row">
                            @php($redesSeleccionadas = old('redes', $publicacion->redes_objetivo ?? []))
                            @foreach ($redes as $red)
                                <div class="col-md-4 mb-3">
                                    <label class="publicacion-red-card mb-0 w-100">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input @error('redes') is-invalid @enderror" type="checkbox" name="redes[]" value="{{ $red->value }}" id="red-{{ $red->value }}" @checked(in_array($red->value, $redesSeleccionadas, true))>
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
                        <div class="publicacion-block__hint">Controla enlaces, accesibilidad de imagen y decide si deseas publicar ahora o dejar la salida programada en Facebook.</div>

                        <div class="form-group">
                            <label for="url_destino">URL de destino</label>
                            <input type="url" class="form-control @error('url_destino') is-invalid @enderror" id="url_destino" name="url_destino" maxlength="500" value="{{ old('url_destino', $publicacion->url_destino) }}" placeholder="https://tu-dominio.com/servicio">
                            @error('url_destino') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="imagen_alt">Texto alternativo de la imagen</label>
                            <input type="text" class="form-control @error('imagen_alt') is-invalid @enderror" id="imagen_alt" name="imagen_alt" maxlength="200" value="{{ old('imagen_alt', $publicacion->imagen_alt) }}" placeholder="Describe la escena de la imagen">
                            @error('imagen_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @php($programadaAnterior = optional($publicacion->programado_at)->format('Y-m-d\TH:i'))
                        @php($programadaActiva = old('programar_publicacion', $publicacion->estaProgramada() ? '1' : null))

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="programar_publicacion" name="programar_publicacion" @checked((bool) $programadaActiva)>
                            <label class="form-check-label" for="programar_publicacion">
                                Programar publicacion automatica
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">Si la activas, la publicacion quedara pendiente hasta la fecha configurada.</small>

                        <div class="form-group mt-3" id="programado-wrapper">
                            <label for="programado_at">Fecha y hora programada</label>
                            <input type="datetime-local" class="form-control @error('programado_at') is-invalid @enderror" id="programado_at" name="programado_at" value="{{ old('programado_at', $programadaAnterior) }}">
                            <small class="text-muted d-block mt-1">Ejemplo: programa hoy para que se publique automaticamente el domingo.</small>
                            @error('programado_at') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="republicar_redes" name="republicar_redes" @checked(old('republicar_redes')) @disabled(config('publicaciones.solo_lectura_analytics', true))>
                            <label class="form-check-label" for="republicar_redes">
                                Publicar ahora en redes sociales
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            @if (config('publicaciones.solo_lectura_analytics', true))
                                Esta accion se mantiene protegida en este entorno para evitar envios accidentales a Facebook.
                            @else
                                Si programas la publicacion, esta opcion se desactiva para evitar conflicto.
                            @endif
                        </small>
                    </div>
                </div>
                <div class="card-footer bg-white text-right publicacion-form-footer">
                    <a href="{{ route('publicaciones.show', $publicacion) }}" class="btn btn-default mr-2">Cancelar</a>
                    <button type="submit" class="btn btn-warning" id="submit-button">
                        <span class="spinner-border spinner-border-sm mr-2 d-none" id="submit-spinner" role="status" aria-hidden="true"></span>
                        <span id="submit-label">Guardar cambios</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card publicacion-side-card">
                <div class="card-header">
                    <h3 class="card-title">Imagen actual</h3>
                </div>
                <div class="card-body">
                    <div class="publicacion-preview mb-3">
                        <img id="imagen-preview" src="{{ $publicacion->imagen_url ?: 'https://placehold.co/1200x900?text=Sin+imagen' }}" alt="{{ $publicacion->imagen_alt ?: $publicacion->titulo }}">
                    </div>
                    <p class="text-muted small mb-0">Si cargas una nueva imagen, reemplazara la actual en el sistema y sera la base para Instagram.</p>
                </div>
            </div>

            <div class="card publicacion-side-card">
                <div class="card-header">
                    <h3 class="card-title">Resultado previo en redes</h3>
                </div>
                <div class="card-body">
                    @if (collect($publicacion->resultado_publicacion ?? [])->reject(fn ($resultado, $red) => $red === '_general')->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach (collect($publicacion->resultado_publicacion ?? [])->reject(fn ($resultado, $red) => $red === '_general') as $red => $resultado)
                                <li class="list-group-item px-0">
                                    <div class="font-weight-bold text-capitalize">{{ $red }}</div>
                                    <div class="small {{ ($resultado['exito'] ?? false) ? 'text-success' : 'text-danger' }}">
                                        {{ ($resultado['exito'] ?? false) ? 'Publicado correctamente' : ($resultado['error'] ?? 'Sin respuesta') }}
                                    </div>
                                    @if (!empty($resultado['post_id']))
                                        <div class="small text-muted">Post ID: {{ $resultado['post_id'] }}</div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="small text-muted mt-3">
                            Ultimo intento: {{ optional($publicacion->publicado_at)->format('d/m/Y H:i') ?: 'Sin fecha registrada' }}
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">La publicacion aun no se ha enviado a redes sociales.</div>
                    @endif
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
    const republicarRedes = document.getElementById('republicar_redes');

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

        if (programada) {
            republicarRedes.checked = false;
            republicarRedes.setAttribute('disabled', 'disabled');
            submitLabel.textContent = 'Guardar y programar';
        } else {
            republicarRedes.removeAttribute('disabled');
            submitLabel.textContent = republicarRedes.checked ? 'Guardar y publicar ahora' : 'Guardar cambios';
        }
    }

    function updateAccionEdicion() {
        if (!programarPublicacion.checked) {
            submitLabel.textContent = republicarRedes.checked ? 'Guardar y publicar ahora' : 'Guardar cambios';
        }
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
    republicarRedes.addEventListener('change', updateAccionEdicion);

    form.addEventListener('submit', function () {
        submitButton.setAttribute('disabled', 'disabled');
        submitSpinner.classList.remove('d-none');
    });

    updateCounter('titulo');
    updateCounter('contenido');
    updateProgramacion();
    updateAccionEdicion();
});
</script>
@endsection
