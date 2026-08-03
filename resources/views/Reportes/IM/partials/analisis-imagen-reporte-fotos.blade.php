@php
    // En Edit se restaura la selección, la misma ruta original y la redacción aprobada por el técnico.
    $analisisReporteFotos = $analisisImagen ?? [];
    $usarAnalisisEnReporte = !empty($analisisReporteFotos['usar_en_reporte']);
    $rutaOriginalReporte = $analisisReporteFotos['rutas']['original'] ?? '';
    $descripcionReporte = old(
        'Analisis_Reporte_Descripcion',
        $analisisReporteFotos['descripcion_reporte'] ?? ''
    );
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
                    <img data-analysis-report-original
                        src="{{ $rutaOriginalReporte !== '' ? asset($rutaOriginalReporte) : '' }}"
                        class="img-fluid img-thumbnail {{ $rutaOriginalReporte !== '' ? '' : 'd-none' }}"
                        alt="Micrografía utilizada en el reporte">
                    <div class="text-muted {{ $rutaOriginalReporte !== '' ? 'd-none' : '' }}" data-analysis-report-no-image>
                        Seleccione un análisis desde la herramienta de fracción de fases.
                    </div>
                </div>
                <div class="card-footer small text-muted">
                    Se utiliza el archivo original almacenado por el análisis de Fiji.
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card h-100 border-primary">
                <div class="card-header"><strong>Imagen 2 — Usar este espacio como cuadro de texto</strong></div>
                <div class="card-body">
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
