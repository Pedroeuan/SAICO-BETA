@extends('adminlte::page')

@section('title', 'Editar Publicacion')

@section('content_header')
<br>
<br>
<br>
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-1">Editar publicacion</h1>
            <small class="text-muted">Actualiza contenido y controla la republicación.</small>
        </div>
        <a href="{{ route('publicaciones.show', $publicacion) }}" class="btn btn-default">
            <i class="fas fa-arrow-left mr-1"></i>Volver al detalle
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-1">Editar publicacion</h1>
                    <p class="text-muted mb-0">Actualiza el contenido y decide si deseas republicarlo.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('publicaciones.update', $publicacion) }}" enctype="multipart/form-data" class="card shadow-sm border-0" id="publicacion-form">
                @csrf
                @method('PUT')
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Titulo</label>
                                <input type="text" class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" maxlength="150" minlength="5" required value="{{ old('titulo', $publicacion->titulo) }}">
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Puedes ajustar el mensaje sin salir del flujo editorial.</small>
                                    <small class="text-muted"><span data-counter-for="titulo">0</span>/150</small>
                                </div>
                                @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contenido" class="form-label">Contenido</label>
                                <textarea class="form-control @error('contenido') is-invalid @enderror" id="contenido" name="contenido" rows="9" maxlength="3000" minlength="20" required>{{ old('contenido', $publicacion->contenido) }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">Recuerda mantener claro el beneficio o llamada a la acción.</small>
                                    <small class="text-muted"><span data-counter-for="contenido">0</span>/3000</small>
                                </div>
                                <div id="twitter-warning" class="alert alert-warning py-2 px-3 mt-2 d-none mb-0">
                                    El texto supera 280 caracteres. X lo truncará automáticamente.
                                </div>
                                @error('contenido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="tipo" class="form-label">Tipo de publicacion</label>
                                    <select class="form-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                                        <option value="">Selecciona...</option>
                                        @foreach ($tipos as $tipo)
                                            <option value="{{ $tipo->value }}" @selected(old('tipo', $publicacion->tipo) === $tipo->value)>{{ $tipo->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="imagen" class="form-label">Cambiar imagen</label>
                                    <input class="form-control @error('imagen') is-invalid @enderror" type="file" id="imagen" name="imagen" accept=".jpg,.jpeg,.png,.webp">
                                    <small class="text-muted d-block mt-1">Solo sube una nueva si deseas reemplazar la actual.</small>
                                    @error('imagen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label mb-0">Redes sociales objetivo</label>
                                    <small class="text-muted">Se conservarán para futuras publicaciones.</small>
                                </div>
                                <div class="row g-3">
                                    @php($redesSeleccionadas = old('redes', $publicacion->redes_objetivo ?? []))
                                    @foreach ($redes as $red)
                                        <div class="col-md-4">
                                            <label class="border rounded-3 p-3 w-100 h-100">
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input @error('redes') is-invalid @enderror" type="checkbox" name="redes[]" value="{{ $red->value }}" id="red-{{ $red->value }}" @checked(in_array($red->value, $redesSeleccionadas, true))>
                                                    <span class="form-check-label ms-1 fw-semibold">
                                                        <i class="{{ $red->icono() }} me-2"></i>{{ $red->label() }}
                                                    </span>
                                                </div>
                                                <small class="text-muted d-block mt-2">
                                                    @if ($red->value === 'linkedin')
                                                        Principal para visibilidad corporativa y servicios.
                                                    @elseif ($red->value === 'facebook')
                                                        Conveniente para mostrar imagen y alcance orgánico.
                                                    @else
                                                        Recomendado para avisos breves y alcance veloz.
                                                    @endif
                                                </small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('redes') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                @error('redes.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            </div>

                            <div class="accordion mt-4" id="accordionAvanzado">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#config-avanzada" aria-expanded="true" aria-controls="config-avanzada">
                                            Configuracion avanzada
                                        </button>
                                    </h2>
                                    <div id="config-avanzada" class="accordion-collapse collapse show" data-bs-parent="#accordionAvanzado">
                                        <div class="accordion-body">
                                            <div class="mb-3">
                                                <label for="url_destino" class="form-label">URL de destino</label>
                                                <input type="url" class="form-control @error('url_destino') is-invalid @enderror" id="url_destino" name="url_destino" maxlength="500" value="{{ old('url_destino', $publicacion->url_destino) }}" placeholder="https://tu-dominio.com/servicio">
                                                @error('url_destino') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="imagen_alt" class="form-label">Texto alternativo de la imagen</label>
                                                <input type="text" class="form-control @error('imagen_alt') is-invalid @enderror" id="imagen_alt" name="imagen_alt" maxlength="200" value="{{ old('imagen_alt', $publicacion->imagen_alt) }}" placeholder="Describe la escena de la imagen">
                                                @error('imagen_alt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="republicar_redes" name="republicar_redes" @checked(old('republicar_redes'))>
                                                <label class="form-check-label" for="republicar_redes">
                                                    Republicar en redes sociales
                                                </label>
                                            </div>
                                            <small class="text-muted">Permanece desmarcado por defecto para evitar publicaciones duplicadas.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="border rounded-3 p-3 bg-light mb-3">
                                <h2 class="h6 mb-3">Imagen actual</h2>
                                <div class="ratio ratio-4x3 rounded overflow-hidden border bg-white">
                                    <img id="imagen-preview" src="{{ $publicacion->imagen_url ?: 'https://placehold.co/1200x900?text=Sin+imagen' }}" alt="{{ $publicacion->imagen_alt ?: $publicacion->titulo }}" class="w-100 h-100 object-fit-cover">
                                </div>
                            </div>

                            <div class="border rounded-3 p-3">
                                <h2 class="h6 mb-3">Resultado previo en redes</h2>
                                @if (collect($publicacion->resultado_publicacion ?? [])->reject(fn ($resultado, $red) => $red === '_general')->isNotEmpty())
                                    <ul class="list-group list-group-flush">
                                        @foreach (collect($publicacion->resultado_publicacion ?? [])->reject(fn ($resultado, $red) => $red === '_general') as $red => $resultado)
                                            <li class="list-group-item px-0">
                                                <div class="fw-semibold text-capitalize">{{ $red }}</div>
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
                </div>
                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <a href="{{ route('publicaciones.show', $publicacion) }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary" id="submit-button">
                        <span class="spinner-border spinner-border-sm me-2 d-none" id="submit-spinner" role="status" aria-hidden="true"></span>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const titulo = document.getElementById('titulo');
    const contenido = document.getElementById('contenido');
    const imagen = document.getElementById('imagen');
    const preview = document.getElementById('imagen-preview');
    const twitterWarning = document.getElementById('twitter-warning');
    const form = document.getElementById('publicacion-form');
    const submitButton = document.getElementById('submit-button');
    const submitSpinner = document.getElementById('submit-spinner');

    function updateCounter(fieldId) {
        const field = document.getElementById(fieldId);
        const counter = document.querySelector('[data-counter-for="' + fieldId + '"]');
        if (field && counter) {
            counter.textContent = field.value.length;
        }
    }

    function updateTwitterWarning() {
        twitterWarning.classList.toggle('d-none', contenido.value.length <= 280);
    }

    titulo.addEventListener('input', function () {
        updateCounter('titulo');
    });

    contenido.addEventListener('input', function () {
        updateCounter('contenido');
        updateTwitterWarning();
    });

    imagen.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) {
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    form.addEventListener('submit', function () {
        submitButton.setAttribute('disabled', 'disabled');
        submitSpinner.classList.remove('d-none');
    });

    updateCounter('titulo');
    updateCounter('contenido');
    updateTwitterWarning();
});
</script>
@endpush
