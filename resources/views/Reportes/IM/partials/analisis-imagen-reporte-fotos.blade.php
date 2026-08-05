@php
    // En Edit se restaura la selección, la misma ruta original y la redacción aprobada por el técnico.
    $analisisReporteFotos = $analisisImagen ?? [];
    $usarAnalisisEnReporte = !empty($analisisReporteFotos['usar_en_reporte']);
    // La vista usa PNG para originales TIFF y mantiene compatibilidad con análisis anteriores.
    $rutaOriginalReporte = $analisisReporteFotos['rutas']['imagen_visual']
        ?? $analisisReporteFotos['rutas']['original']
        ?? '';
    $descripcionReporte = old(
        'Analisis_Reporte_Descripcion',
        $analisisReporteFotos['descripcion_reporte'] ?? ''
    );
    // La Imagen 1 conserva un pie de fotografía propio, separado del cuadro de resultados de la Imagen 2.
    $comentarioImagenReporte = old(
        'Analisis_Reporte_Comentario_Imagen',
        $analisisReporteFotos['comentario_imagen_reporte'] ?? 'FOTOMICROGRAFÍA ANALIZADA'
    );
    // Cada elemento conserva su propia celda; los valores antiguos reciben la distribución histórica original.
    $layoutAnalisis = old('Analisis_Reporte_Layout', $analisisReporteFotos['layout_reporte'] ?? []);
    $layoutImagen = array_merge(
        ['pagina' => 1, 'posicion' => 'arriba_izquierda'],
        is_array($layoutAnalisis['imagen'] ?? null) ? $layoutAnalisis['imagen'] : []
    );
    $layoutResultados = array_merge(
        ['pagina' => 1, 'posicion' => 'arriba_derecha'],
        is_array($layoutAnalisis['resultados'] ?? null) ? $layoutAnalisis['resultados'] : []
    );
    $posicionesReporte = [
        'arriba_izquierda' => 'Arriba izquierda',
        'arriba_derecha' => 'Arriba derecha',
        'abajo_izquierda' => 'Abajo izquierda',
        'abajo_derecha' => 'Abajo derecha',
        'pagina_completa' => 'Página completa',
    ];
@endphp

{{--
    Representación editable de los espacios automáticos del anexo:
    1) micrografía original reutilizada; 2) resultados que el técnico puede corregir antes de guardar.
--}}
<div class="col-12 mb-3 {{ $usarAnalisisEnReporte ? '' : 'd-none' }}" data-analysis-report-photos
    data-analysis-report-token="{{ $analisisReporteFotos['token'] ?? '' }}">
    <div class="alert alert-primary py-2">
        Estos dos elementos ocuparán los espacios 1 y 2 del PDF. La imagen no se carga nuevamente y el
        texto puede modificarse antes de guardar el reporte.
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-header"><strong>Imagen 1 — Micrografía seleccionada</strong></div>
                <div class="card-body text-center">
                    {{-- La micrografía se activa junto con el análisis; no necesita otro checkbox de inclusión. --}}
                    <div class="foto-layout-manual border rounded p-2 mb-2 bg-light text-left"
                        data-auto-report-layout data-auto-report-label="Imagen 1 — Micrografía">
                        <div class="row align-items-center">
                            <div class="col-md-3 mb-2">
                                <label class="font-weight-bold mb-1">Número de hoja</label>
                                <input type="number" min="1" class="form-control form-control-sm"
                                    name="Analisis_Reporte_Layout[imagen][pagina]"
                                    value="{{ max(1, (int) ($layoutImagen['pagina'] ?? 1)) }}"
                                    data-report-layout-page @disabled(!$usarAnalisisEnReporte)>
                            </div>
                            <div class="col-md-9">
                                <div class="font-weight-bold mb-1">Posición en la hoja</div>
                                @foreach($posicionesReporte as $valorPosicion => $textoPosicion)
                                    <label class="form-check form-check-inline mb-1">
                                        <input class="form-check-input" type="radio"
                                            name="Analisis_Reporte_Layout[imagen][posicion]"
                                            value="{{ $valorPosicion }}"
                                            data-report-layout-position
                                            @disabled(!$usarAnalisisEnReporte)
                                            @checked(($layoutImagen['posicion'] ?? '') === $valorPosicion)>
                                        <span class="form-check-label">{{ $textoPosicion }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" disabled>
                            <span class="form-check-label font-weight-bold">Usar este espacio como cuadro de texto</span>
                        </div>
                    </div>
                    <img data-analysis-report-original
                        src="{{ $rutaOriginalReporte !== '' ? asset($rutaOriginalReporte) : '' }}"
                        class="img-fluid img-thumbnail {{ $rutaOriginalReporte !== '' ? '' : 'd-none' }}"
                        alt="Micrografía utilizada en el reporte">
                    <div class="text-muted {{ $rutaOriginalReporte !== '' ? 'd-none' : '' }}" data-analysis-report-no-image>
                        Seleccione un análisis desde la herramienta de fracción de fases.
                    </div>
                    {{-- Este campo funciona como el comentario o pie visible debajo de la Imagen 1 en el PDF. --}}
                    <label class="font-weight-bold d-block text-left mt-2 mb-1"
                        for="Analisis_Reporte_Comentario_Imagen">Comentario de la fotografía</label>
                    <textarea class="form-control" rows="2" maxlength="500"
                        id="Analisis_Reporte_Comentario_Imagen"
                        name="Analisis_Reporte_Comentario_Imagen"
                        placeholder="Escriba el comentario que aparecerá debajo de la micrografía.">{{ $comentarioImagenReporte }}</textarea>
                    <small class="form-text text-muted text-left">
                        Este texto aparecerá como pie de la Imagen 1 en el PDF.
                    </small>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-header"><strong>Imagen 2 — Usar este espacio como cuadro de texto</strong></div>
                <div class="card-body">
                    {{-- El tipo texto está definido por el sistema; el técnico solamente decide hoja y posición. --}}
                    <div class="foto-layout-manual border rounded p-2 mb-2 bg-light"
                        data-auto-report-layout data-auto-report-label="Imagen 2 — Resultados del análisis">
                        <div class="row align-items-center">
                            <div class="col-md-3 mb-2">
                                <label class="font-weight-bold mb-1">Número de hoja</label>
                                <input type="number" min="1" class="form-control form-control-sm"
                                    name="Analisis_Reporte_Layout[resultados][pagina]"
                                    value="{{ max(1, (int) ($layoutResultados['pagina'] ?? 1)) }}"
                                    data-report-layout-page @disabled(!$usarAnalisisEnReporte)>
                            </div>
                            <div class="col-md-9">
                                <div class="font-weight-bold mb-1">Posición en la hoja</div>
                                @foreach($posicionesReporte as $valorPosicion => $textoPosicion)
                                    <label class="form-check form-check-inline mb-1">
                                        <input class="form-check-input" type="radio"
                                            name="Analisis_Reporte_Layout[resultados][posicion]"
                                            value="{{ $valorPosicion }}"
                                            data-report-layout-position
                                            @disabled(!$usarAnalisisEnReporte)
                                            @checked(($layoutResultados['posicion'] ?? '') === $valorPosicion)>
                                        <span class="form-check-label">{{ $textoPosicion }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" checked disabled>
                            <span class="form-check-label font-weight-bold">Usar este espacio como cuadro de texto</span>
                        </div>
                    </div>
                    {{-- El servidor guarda esta redacción dentro del análisis asociado al reporte. --}}
                    <textarea class="form-control" rows="15" maxlength="20000"
                        name="Analisis_Reporte_Descripcion"
                        data-analysis-report-description
                        placeholder="Los resultados del análisis aparecerán aquí.">{{ $descripcionReporte }}</textarea>
                    <small class="form-text text-muted">
                        Puede corregir, completar o reescribir el contenido. El PDF mostrará exactamente este texto.
                    </small>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" data-analysis-report-refresh>
                        Volver a cargar los datos automáticos
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Datos iniciales para reconstruir el cuadro en Edit sin ejecutar otra vez Fiji. --}}
<script type="application/json" data-analysis-report-existing>@json($analisisReporteFotos)</script>
