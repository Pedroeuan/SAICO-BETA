 @php($conteoGranosActual = $conteoGranos ?? [])

{{--
    Contador lineal reutilizable. Recibe la misma micrografía mediante un evento JavaScript y guarda
    coordenadas normalizadas, cruces confirmados y resultados por línea en Conteo_Granos_JSON.
--}}
<div class="col-12 my-3" data-grain-counter>
    <div class="d-flex justify-content-center align-items-center p-2 bg-secondary text-white rounded">
        CONTEO LINEAL DE GRANOS — VERSIÓN SEMIAUTOMÁTICA
    </div>

    <div class="border rounded p-3 mt-2 bg-light">
        {{-- JSON enviado al servidor; el controlador recalcula los totales antes de persistirlos. --}}
        <input type="hidden" name="Conteo_Granos_JSON" data-grain-json value="{{ old('Conteo_Granos_JSON', '') }}">
        {{-- Estado anterior del reporte usado para reconstruir las líneas en la vista Edit. --}}
        <script type="application/json" data-grain-existing>@json($conteoGranosActual)</script>

        <div class="alert alert-info py-2" data-grain-help>
            Se utilizará automáticamente la misma micrografía cargada en Fracción de Fases. Dibuje líneas que no se crucen.
            Cada extremo aporta 0.5; los granos completamente interceptados aportan 1.
        </div>

        {{-- Modos separados para evitar que una corrección de cruce cree otra línea accidentalmente. --}}
        <div class="row align-items-end">
            <div class="col-lg-7 mb-2">
                <button type="button" class="btn btn-primary" data-grain-draw>Dibujar línea</button>
                <button type="button" class="btn btn-outline-primary" data-grain-markers>Editar cruces</button>
                <button type="button" class="btn btn-outline-success" data-grain-suggest disabled>Recalcular cruces</button>
                <button type="button" class="btn btn-outline-danger" data-grain-delete disabled>Eliminar línea</button>
                <button type="button" class="btn btn-outline-secondary" data-grain-clear>Limpiar líneas</button>
            </div>
            <div class="col-lg-5 mb-2">
                <label><strong>Sensibilidad para sugerir límites oscuros: <span data-grain-sensitivity-value>85</span></strong></label>
                <input type="range" class="custom-range" min="0" max="255" value="85" data-grain-sensitivity>
            </div>
        </div>

        <div class="alert alert-secondary py-2 mb-2">
            <strong>Dibujar línea:</strong> arrastre entre dos puntos; los cruces se detectarán automáticamente en toda su trayectoria.
            <strong>Editar cruces:</strong> haga clic sobre la línea para agregar un cruce;
            haga clic cerca de un marcador existente para quitarlo.
        </div>

        {{-- El canvas permanece oculto hasta recibir la micrografía del componente de fases. --}}
        <div class="text-center text-muted border rounded bg-white p-4" data-grain-empty>
            Cargue una micrografía en la herramienta de Fracción de Fases para iniciar el conteo.
        </div>
        <div class="border rounded bg-white p-2 d-none" data-grain-canvas-wrap style="overflow:auto">
            <canvas data-grain-canvas style="max-width:100%;height:auto;cursor:crosshair"></canvas>
        </div>

        <div class="mt-2" data-grain-status></div>

        {{-- Detalle auditable de cada línea: cruces, enteros, medios extremos y conteo final. --}}
        <div class="table-responsive mt-3">
            <table class="table table-sm table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Línea</th>
                        <th>Cruces confirmados</th>
                        <th>Granos completos</th>
                        <th>Extremos parciales</th>
                        <th>Conteo de la línea</th>
                    </tr>
                </thead>
                <tbody data-grain-table>
                    <tr data-grain-empty-row><td colspan="5" class="text-center text-muted">Todavía no hay líneas.</td></tr>
                </tbody>
            </table>
        </div>
        {{-- Resumen dinámico: el promedio se define como suma de conteos dividida entre líneas. --}}
        <div class="row mt-3" data-grain-summary>
            <div class="col-md-4 mb-2">
                <div class="card h-100"><div class="card-body text-center py-2">
                    <div>Líneas analizadas</div><strong class="h4" data-grain-line-count>0</strong>
                </div></div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100"><div class="card-body text-center py-2">
                    <div>Suma de granos</div><strong class="h4" data-grain-total>0.0</strong>
                </div></div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="card h-100 border-primary"><div class="card-body text-center py-2">
                    <div>Promedio por línea</div><strong class="h4" data-grain-average>0.000</strong>
                </div></div>
            </div>
        </div>
    </div>
</div>
