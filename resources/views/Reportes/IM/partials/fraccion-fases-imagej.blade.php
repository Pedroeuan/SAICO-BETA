@php
    // En Edit se reciben metadatos y rutas del análisis anterior para mostrar sus evidencias.
    $analisisImagenActual = $analisisImagen ?? [];
    $rutasAnalisis = $analisisImagenActual['rutas'] ?? [];
@endphp

{{--
    Componente reutilizable de fracción de fases.
    data-process-url ejecuta Measure; data-histogram-url prepara la previsualización exacta de 8 bits.
--}}
<div class="col-12 my-3" data-imagej-phase
    data-process-url="{{ route('analisis-imagen.fraccion-fases') }}"
    data-histogram-url="{{ route('analisis-imagen.histograma') }}">
    <div class="d-flex justify-content-center align-items-center p-2 bg-primary text-white rounded">
        FRACCIÓN DE FASES POR ANÁLISIS DE IMAGEN
    </div>

    <div class="border rounded p-3 mt-2 bg-light">
        {{-- El reporte guarda el token, no archivos base64 ni rutas manipulables desde el navegador. --}}
        <input type="hidden" name="Analisis_Imagen_Token" data-imagej-token
            value="{{ old('Analisis_Imagen_Token', $analisisImagenActual['token'] ?? '') }}">
        {{--
            Esta bandera indica qué análisis se colocará en el PDF. La micrografía no se vuelve a subir:
            el generador reutiliza la ruta original asociada al token seleccionado.
        --}}
        <input type="hidden" name="Analisis_Imagen_Usar_Reporte" data-imagej-use-report
            value="{{ old('Analisis_Imagen_Usar_Reporte', !empty($analisisImagenActual['usar_en_reporte']) ? 1 : 0) }}">
        {{-- Permite que JavaScript restaure en Edit la selección sin solicitar otro procesamiento. --}}
        <script type="application/json" data-imagej-existing>@json($analisisImagenActual)</script>

        <div class="form-group">
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info"></i> Importante</h5>
                <p>Formatos permitidos: JPG, PNG y TIFF. Tamaño máximo: 25 MB. La imagen original se conservará.</p>
            </div>
            <label><strong>Micrografía o imagen de la muestra</strong></label>
            <input type="file" class="form-control-file" data-imagej-file
                accept="image/jpeg,image/png,image/tiff,.jpg,.jpeg,.png,.tif,.tiff">
        </div>

        <div class="row">
            <div class="col-md-6">
                <label><strong>Umbral mínimo (0–255)</strong></label>
                <input type="range" class="custom-range" min="0" max="255" value="{{ $analisisImagenActual['umbral_minimo'] ?? 0 }}" data-imagej-min-range>
                <input type="number" class="form-control mt-1" min="0" max="255" value="{{ $analisisImagenActual['umbral_minimo'] ?? 0 }}" data-imagej-min>
            </div>
            <div class="col-md-6">
                <label><strong>Umbral máximo (0–255)</strong></label>
                <input type="range" class="custom-range" min="0" max="255" value="{{ $analisisImagenActual['umbral_maximo'] ?? 85 }}" data-imagej-max-range>
                <input type="number" class="form-control mt-1" min="0" max="255" value="{{ $analisisImagenActual['umbral_maximo'] ?? 85 }}" data-imagej-max>
            </div>
        </div>

        {{-- La fase elegida controla el resaltado; el umbral siempre delimita la fase oscura. --}}
        <div class="row mt-3">
            <div class="col-md-6">
                <label><strong>Fase que desea resaltar y revisar</strong></label>
                <select class="form-control" data-imagej-phase-type>
                    <option value="perlita" @selected(($analisisImagenActual['fase_seleccionada'] ?? 'perlita') === 'perlita')>Perlita / fase oscura</option>
                    <option value="ferrita" @selected(($analisisImagenActual['fase_seleccionada'] ?? '') === 'ferrita')>Ferrita / fase clara</option>
                </select>
            </div>
            <div class="col-md-6">
                <label><strong>Modo de previsualización</strong></label>
                <select class="form-control" data-imagej-preview-mode>
                    <option value="red">Rojo sobre escala de grises</option>
                    <option value="bw">Blanco y negro (B&amp;W)</option>
                </select>
            </div>
        </div>

        {{-- Histograma calculado por Fiji para evitar diferencias con la conversión RGB del navegador. --}}
        <div class="border rounded bg-white p-2 mt-3 d-none" data-imagej-histogram-wrap>
            <div class="d-flex justify-content-between align-items-center mb-1">
                <strong>Histograma de intensidad</strong>
                <span>Área seleccionada por Fiji: <strong data-imagej-live-percent>0.000 %</strong></span>
            </div>
            <canvas data-imagej-histogram width="512" height="130" style="width:100%;height:130px"></canvas>
            <div class="d-flex justify-content-between small text-muted"><span>0 (negro)</span><span>255 (blanco)</span></div>
        </div>

        <div class="alert alert-info py-2 mt-3 mb-2">
            El umbral delimita la fase oscura. En <strong>Perlita</strong> se resaltará ese rango; en <strong>Ferrita</strong> se resaltará su complemento claro.
            La previsualización no se guarda hasta pulsar
            <strong>Aplicar y medir con Fiji</strong>.
        </div>

        {{-- Canvas de ajuste manual: rojo para selección y B&W para revisar la máscara. --}}
        <div class="text-center border rounded bg-white p-2 d-none" data-imagej-preview-wrap>
            <canvas data-imagej-preview style="max-width:100%;height:auto;cursor:crosshair"></canvas>
        </div>

        <div class="mt-3">
            <button type="button" class="btn btn-success" data-imagej-process disabled>
                Aplicar y medir con Fiji
            </button>
            <button type="button" class="btn btn-outline-secondary ml-1" data-imagej-reset>
                Restablecer 0–85
            </button>
            <span class="ml-2 text-muted" data-imagej-status></span>
        </div>

        {{-- Selección explícita del resultado que alimentará los espacios 1 y 2 del anexo fotográfico. --}}
        <div class="border rounded bg-white p-3 mt-3">
            <button type="button"
                class="btn {{ !empty($analisisImagenActual['usar_en_reporte']) ? 'btn-primary' : 'btn-outline-primary' }}"
                data-imagej-use-report-button
                @disabled(empty($analisisImagenActual))>
                {{ !empty($analisisImagenActual['usar_en_reporte']) ? 'Análisis seleccionado para el reporte' : 'Usar este análisis en el reporte' }}
            </button>
            <small class="d-block mt-2 text-muted" data-imagej-use-report-status>
                @if(!empty($analisisImagenActual['usar_en_reporte']))
                    La imagen original y todos los resultados se colocarán automáticamente en el PDF.
                @else
                    Primero aplique y mida con Fiji; después seleccione el análisis definitivo.
                @endif
            </small>
        </div>

        {{-- Resultados definitivos devueltos por Area Fraction/Measure y evidencias persistidas. --}}
        <div class="row mt-3 {{ empty($analisisImagenActual) ? 'd-none' : '' }}" data-imagej-results>
            <div class="col-md-4 mb-2">
                <div class="card h-100 border-dark">
                    <div class="card-body text-center py-3">
                        <div>Perlita / fase oscura</div>
                        <strong class="h4" data-imagej-perlite>{{ isset($analisisImagenActual['porcentaje_perlita']) ? number_format((float) $analisisImagenActual['porcentaje_perlita'], 3) . ' %' : '—' }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100 border-light">
                    <div class="card-body text-center py-3">
                        <div>Ferrita / fase clara</div>
                        <strong class="h4" data-imagej-ferrite>{{ isset($analisisImagenActual['porcentaje_ferrita']) ? number_format((float) $analisisImagenActual['porcentaje_ferrita'], 3) . ' %' : '—' }}</strong>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100">
                    <div class="card-body text-center py-3">
                        <div>Total verificado</div>
                        <strong class="h4">100.000 %</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mt-2 {{ empty($rutasAnalisis['original']) ? 'd-none' : '' }}" data-imagej-original-wrap>
                <strong>Imagen original</strong>
                <img class="img-fluid border rounded d-block mt-1" data-imagej-original
                    src="{{ !empty($rutasAnalisis['original']) ? asset($rutasAnalisis['original']) : '' }}" alt="Imagen original analizada">
            </div>
            <div class="col-md-6 mt-2 {{ empty($rutasAnalisis['imagen_binaria']) ? 'd-none' : '' }}" data-imagej-binary-wrap>
                <strong>Evidencia binaria generada por Fiji</strong>
                <img class="img-fluid border rounded d-block mt-1" data-imagej-binary
                    src="{{ !empty($rutasAnalisis['imagen_binaria']) ? asset($rutasAnalisis['imagen_binaria']) : '' }}" alt="Resultado binario del análisis">
            </div>
        </div>
    </div>
</div>
