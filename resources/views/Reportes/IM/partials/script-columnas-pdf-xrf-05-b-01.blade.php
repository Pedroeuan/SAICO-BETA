
<script src="{{ asset('js/procesamiento-asincrono-saico.js') }}?v={{ filemtime(public_path('js/procesamiento-asincrono-saico.js')) }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById(@json($xrfFormId ?? 'FOR-PIMP-05_B_01'));
    if (!form) return;
    const catalog = @json($NormasIM ?? []);
    const historical = @json($Detalles_Generales['Norma_IM'] ?? null);
    const oldNormId = @json(old('Norma_IM.idnormas_im'));
    const oldAverages = @json(old('Norma_IM.Promedio'));
    const nameSelect = form.querySelector('#normaIMNombre') || document.createElement('select');
    const recordSelect = form.querySelector('#normaIMRegistro');
    const resultsBox = form.querySelector('#normaIMResultadosContainer');
    const resultsBody = form.querySelector('#tablaNormaIM tbody');
    const observationsBox = form.querySelector('#normaIMObservacionesContainer');
    const observations = form.querySelector('#normaIMObservaciones');
    const file = form.querySelector('#analisisPdfXrf');
    const button = form.querySelector('#extraerAnalisisPdfBtn');
    const status = form.querySelector('#estadoAnalisisPdf');
    const preview = form.querySelector('#vistaAnalisisPdf');
    const previewTable = form.querySelector('#tablaAnalisisPdf');
    const warnings = form.querySelector('#alertasAnalisisPdf');
    const capture = form.querySelector('#capturaXrfUnica');
    const url = @json($xrfExtractionRoute);
    const requirePreviewBeforeSubmit = @json($xrfRequirePreviewBeforeSubmit ?? true);
    // Algunos formatos deben ocultar los checks hasta conocer los disparos que realmente contiene el PDF.
    const detectColumnsOnFileChange = @json($xrfDetectColumnsOnFileChange ?? false);
    let processed = false;

    /*
     * Borrador local del FOR-PIMP-05_B_01.
     *
     * Se integra en este script existente porque Create y Edit comparten el
     * mismo flujo XRF. La URL de action forma parte de la clave para impedir
     * que el borrador de Create se mezcle con un reporte abierto en Edit.
     */
    const reportAction = new URL(form.action, window.location.href).pathname;
    const draftKey = 'SAICO:BORRADOR:FOR-PIMP-05_B_01:' + reportAction;
    const pendingSubmitKey = draftKey + ':ENVIO_PENDIENTE';
    const draftMaxAge = 7 * 24 * 60 * 60 * 1000;
    let restoringDraft = false;
    let draftSaveTimer = null;

    /** Indica si un control puede guardarse sin almacenar archivos ni imagenes pesadas. */
    function shouldPersistField(field) {
        if (!field || !field.matches('input, textarea, select')) return false;
        if (['file', 'submit', 'button'].includes(field.type)) return false;

        const name = field.name || '';
        if (['_token', '_method'].includes(name)) return false;
        if (/^(?:images_base64|existing_images|replace_images|deleted_images|imagen_hoja)/.test(name)) return false;
        if (/^Datos_Equipo\[(?:QR_TOKEN|QR_PDF|PDF_UNIFICADO)\]$/.test(name)) return false;
        if (field.closest('#imageFieldsContainer')) return false;

        return Boolean(name || field.id);
    }

    /** Genera una identidad estable incluso para controles repetidos como XRF_Columnas[]. */
    function fieldStorageIdentity(field, counters) {
        const base = field.name ? 'name:' + field.name : 'id:' + field.id;
        const occurrence = counters[base] || 0;
        counters[base] = occurrence + 1;
        return base + ':' + occurrence;
    }

    /** Captura valores, checks y campos dinamicos que existen en este momento. */
    function createDraftSnapshot() {
        const counters = {};
        const values = {};
        const previousDraft = readLocalDraft();

        form.querySelectorAll('input, textarea, select').forEach(function (field) {
            if (!shouldPersistField(field)) return;

            values[fieldStorageIdentity(field, counters)] = {
                value: field.value,
                checked: ['checkbox', 'radio'].includes(field.type) ? field.checked : null,
            };
        });

        return {
            version: 1,
            savedAt: Date.now(),
            values: values,
            // Solo se conserva el nombre para reconocer el mismo PDF; nunca se almacena su contenido.
            pdfFileName: file.files[0]?.name || previousDraft?.pdfFileName || null,
        };
    }

    /** Lee solamente borradores validos y elimina automaticamente los vencidos. */
    function readLocalDraft() {
        try {
            const draft = JSON.parse(localStorage.getItem(draftKey) || 'null');
            if (!draft || draft.version !== 1 || !draft.values) return null;
            if (!draft.savedAt || Date.now() - Number(draft.savedAt) > draftMaxAge) {
                localStorage.removeItem(draftKey);
                return null;
            }
            return draft;
        } catch (error) {
            localStorage.removeItem(draftKey);
            return null;
        }
    }

    /** Elimina las claves del guardado basico anterior sin afectar otros formatos. */
    function clearLegacyReportStorage() {
        Object.keys(localStorage).forEach(function (key) {
            if (key.startsWith('FOR-PIMP-05_B_01_') || key.startsWith('FOR-PIMP-05_B_01_Form_')) {
                localStorage.removeItem(key);
            }
        });
        Object.keys(sessionStorage).forEach(function (key) {
            if (key.startsWith('FOR-PIMP-05_B_01_')) sessionStorage.removeItem(key);
        });
    }

    /** Guarda inmediatamente; si el navegador bloquea el storage, el reporte sigue funcionando. */
    function saveLocalDraft() {
        if (restoringDraft) return;
        try {
            localStorage.setItem(draftKey, JSON.stringify(createDraftSnapshot()));
        } catch (error) {
            console.warn('No fue posible guardar el borrador local del FOR-PIMP-05_B_01.', error);
        }
    }

    /** Agrupa escrituras consecutivas para no escribir en localStorage en cada pulsacion. */
    function scheduleDraftSave() {
        window.clearTimeout(draftSaveTimer);
        draftSaveTimer = window.setTimeout(saveLocalDraft, 120);
    }

    /** Relaciona cada control actual con su valor guardado. */
    function draftEntriesForCurrentFields(draft) {
        const counters = {};
        const entries = [];
        form.querySelectorAll('input, textarea, select').forEach(function (field) {
            if (!shouldPersistField(field)) return;
            const saved = draft.values[fieldStorageIdentity(field, counters)];
            if (saved) entries.push({ field: field, saved: saved });
        });
        return entries;
    }

    /** Aplica un valor guardado respetando checked y las opciones disponibles de un select. */
    function applyDraftEntry(field, saved) {
        if (['checkbox', 'radio'].includes(field.type)) {
            field.checked = Boolean(saved.checked);
            return;
        }
        if (field.tagName === 'SELECT'
            && !Array.from(field.options).some(option => option.value === String(saved.value))) return;

        field.value = saved.value == null ? '' : String(saved.value);
    }

    function setStatus(message, error) {
        status.textContent = message;
        status.className = error ? 'ml-2 text-danger' : 'ml-2 text-success';
    }
    function selectedColumns() {
        return Array.from(form.querySelectorAll('.columna-xrf:checked')).map(item => Number(item.value));
    }
    function setRowState(input, origin) {
        input.readOnly = origin === 'calculado';
        input.dataset.autoXrf = origin === 'calculado' ? '1' : '0';
        let help = input.parentElement.querySelector('.estado-promedio-xrf');
        if (!help) {
            help = document.createElement('small');
            help.className = 'estado-promedio-xrf d-block';
            input.parentElement.appendChild(help);
        }
        help.className = 'estado-promedio-xrf d-block ' + (origin === 'calculado' ? 'text-success' : 'text-warning');
        help.textContent = origin === 'calculado' ? 'Calculado automáticamente' : 'Capture manualmente solo este valor';
    }
    let ultimaNorma0402 = null;
    const promediosReferencias0402 = new Map();
    const sumasManualesIniciales0402 = @json(old('Norma_IM.Sumas_Manuales', $Detalles_Generales['Norma_IM']['Sumas_Manuales'] ?? []));
    const sumasManuales0402 = new Set(Object.keys(sumasManualesIniciales0402 || {}).filter(clave => Boolean(sumasManualesIniciales0402[clave])));
    const valoresPrevios0402 = @json(old('Norma_IM.Valores_Elementos', $Detalles_Generales['Norma_IM']['Valores_Elementos'] ?? []));
    Object.entries(valoresPrevios0402 || {}).forEach(([elemento, valor]) => promediosReferencias0402.set(elemento.toLowerCase(), { valor: valor ?? '', origen: 'manual' }));
    let resultadosPdf0402 = historical?.Resultados_XRF || {};
    const valorExtraido0402 = elemento => {
        const resultado = Object.values(resultadosPdf0402).find(fila => String(fila.elemento).trim().toLowerCase() === elemento);
        if (resultado?.calculable) return { valor: Number(resultado.promedio).toFixed(4), origen: 'calculado' };
        if (resultado?.valor_repetido) return { valor: resultado.valor_repetido, origen: 'manual' };
        if (resultado?.valor_sugerido != null) return { valor: resultado.valor_sugerido, origen: 'manual' };
        return null;
    };
    function actualizarSumas0402() {
        if (form.id !== 'FOR-PIMP-04_02') return;
        const valores = new Map();
        Object.values(resultadosPdf0402).forEach(resultado => {
            const valor = valorExtraido0402(String(resultado.elemento).trim().toLowerCase());
            if (valor) valores.set(String(resultado.elemento).trim().toLowerCase(), valor.valor);
        });
        promediosReferencias0402.forEach((dato, elemento) => { if (/^[a-z]{1,2}$/i.test(elemento)) valores.set(elemento, dato.valor); });
        resultsBody.querySelectorAll('input[data-elemento]').forEach(input => {
            if (/^[a-z]{1,2}$/i.test(input.dataset.elemento.trim())) valores.set(input.dataset.elemento.trim().toLowerCase(), input.value);
        });
        resultsBody.querySelectorAll('input[data-elemento]').forEach(input => {
            const formula = input.dataset.elemento.trim();
            if (!/^[a-z]{1,2}(?:\s*\+\s*[a-z]{1,2})+$/i.test(formula)) return;
            if (sumasManuales0402.has(formula)) {
                setRowState(input, 'manual');
                input.parentElement.querySelector('.estado-promedio-xrf').textContent = 'Valor ajustado por el técnico';
                return;
            }
            let total = 0, limite = false;
            const completa = formula.split(/\s*\+\s*/).every(elemento => {
                const match = String(valores.get(elemento.toLowerCase()) ?? '').trim().replace(',', '.').match(/^(\[C<\])?(\d+(?:\.\d+)?)$/);
                if (!match || Number(match[2]) > 100) return false;
                total += Math.round(Number(match[2]) * 10000); limite ||= Boolean(match[1]); return true;
            });
            if (completa) {
                input.value = (limite ? '[C<]' : '') + (total / 10000).toFixed(4);
                setRowState(input, 'calculado'); input.dataset.sumaElementos = '1';
                input.readOnly = false;
                input.parentElement.querySelector('.estado-promedio-xrf').textContent = limite ? 'Suma de límites: valor inferior al indicado' : 'Suma calculada de los elementos';
            } else {
                if (input.dataset.sumaElementos === '1' || input.dataset.autoXrf === '1') input.value = '';
                setRowState(input, 'manual'); delete input.dataset.sumaElementos;
            }
        });
        form.querySelectorAll('[data-valores-elementos-0402]').forEach(campo => campo.remove());
        valores.forEach((valor, elemento) => {
            const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.dataset.valoresElementos0402 = '1'; hidden.name = `Norma_IM[Valores_Elementos][${elemento}]`; hidden.value = valor; form.append(hidden);
        });
        const marcaSumas = document.createElement('input'); marcaSumas.type = 'hidden'; marcaSumas.dataset.valoresElementos0402 = '1'; marcaSumas.name = 'Norma_IM[Sumas_Manuales]'; marcaSumas.value = ''; form.append(marcaSumas);
        sumasManuales0402.forEach(formula => {
            const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.dataset.valoresElementos0402 = '1'; hidden.name = `Norma_IM[Sumas_Manuales][${formula}]`; hidden.value = '1'; form.append(hidden);
        });
    }
    resultsBody.addEventListener('input', event => {
        if (!event.target.matches('input[data-elemento]')) return;
        if (event.target.dataset.elemento.includes('+')) sumasManuales0402.add(event.target.dataset.elemento.trim());
        actualizarSumas0402();
    });
    form.addEventListener('submit', actualizarSumas0402, true);
    function renderNorm(norm, averages) {
        ultimaNorma0402 = norm;
        if (form.id === 'FOR-PIMP-04_02' && Array.isArray(window.referenciasQuimicas0402)) {
            resultsBody.querySelectorAll('input[data-elemento]').forEach(input => promediosReferencias0402.set(input.dataset.elemento.trim().toLowerCase(), { valor: input.value, origen: input.dataset.autoXrf === '1' ? 'calculado' : 'manual' }));
            const filas = new Map();
            window.referenciasQuimicas0402.forEach((referencia, indice) => {
                (referencia.composicion_seleccionada?.Tabla || []).forEach(fila => {
                    const clave = (fila.Elemento || '').trim().toLowerCase();
                    if (!clave) return;
                    if (!filas.has(clave)) filas.set(clave, { Elemento: fila.Elemento, Referencias: [] });
                    filas.get(clave).Referencias.push({ indice, norma: referencia.norma, valor: fila.Composicion || '' });
                });
            });
            const historicas = new Map((norm.Tabla || []).map((fila, indice) => [(fila.Elemento || '').toLowerCase(), { ...fila, Promedio: averages?.[indice] ?? fila.Promedio }]));
            norm = { ...norm, Tabla: [...filas.entries()].map(([clave, fila]) => {
                const anterior = promediosReferencias0402.get(clave);
                const extraido = valorExtraido0402(clave);
                const valor = anterior && anterior.valor !== '' ? anterior : (extraido || anterior);
                return { ...fila, Promedio: valor?.valor ?? historicas.get(clave)?.Promedio ?? '', Origen: valor?.origen ?? historicas.get(clave)?.Origen ?? 'manual' };
            }) };
            averages = null;
        }
        resultsBody.innerHTML = '';
        (norm.Tabla || []).forEach((row, index) => {
            const tr = document.createElement('tr');
            const element = document.createElement('td');
            element.textContent = row.Elemento || '';
            const average = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control text-center';
            input.name = 'Norma_IM[Promedio][' + index + ']';
            input.dataset.elemento = row.Elemento || '';
            input.value = averages?.[index] ?? row.Promedio ?? '';
            average.appendChild(input);
            setRowState(input, row.Origen || 'manual');
            const composition = document.createElement('td');
            if (form.id === 'FOR-PIMP-04_02' && row.Referencias?.length) {
                const colores = ['#c62828', '#1565c0', '#111111', '#6a1b9a', '#2e7d32', '#ad5700'];
                row.Referencias.forEach((referencia, indice) => {
                    if (indice) composition.append(document.createTextNode(' / '));
                    const valor = document.createElement('span'); valor.style.color = colores[referencia.indice % colores.length]; valor.style.fontWeight = '600';
                    valor.textContent = referencia.valor || '—'; valor.title = referencia.norma; composition.append(valor);
                });
            } else composition.textContent = row.Composicion || '';
            tr.append(element, average, composition);
            resultsBody.appendChild(tr);
        });
        observations.textContent = norm.Observaciones || '';
        actualizarSumas0402();
        observationsBox.classList.toggle('d-none', !norm.Observaciones);
        if (form.id === 'FOR-PIMP-04_02') {
            const notas = Array.isArray(window.referenciasQuimicas0402)
                ? window.referenciasQuimicas0402.map((referencia, indice) => ({ indice, texto: referencia.composicion_seleccionada?.Notas_Tecnico ?? referencia.composicion_seleccionada?.Observaciones ?? '' })).filter(nota => nota.texto.trim())
                : (norm.Notas_Composicion || []);
            if (Array.isArray(window.referenciasQuimicas0402) || notas.length) {
                observations.replaceChildren();
                const colores = ['#c62828', '#1565c0', '#111111', '#6a1b9a', '#2e7d32', '#ad5700'];
                notas.forEach(nota => {
                    const parrafo = document.createElement('div'); parrafo.className = 'mb-2'; parrafo.style.color = colores[nota.indice % colores.length];
                    parrafo.textContent = `* ${nota.texto}`; observations.append(parrafo);
                });
                observationsBox.classList.toggle('d-none', !notas.length);
            }
        }
        resultsBox.classList.remove('d-none');
    }
    if (form.id === 'FOR-PIMP-04_02') document.addEventListener('referencias-0402:cambio', () => {
        if (Array.isArray(window.referenciasQuimicas0402)) recordSelect.value = window.referenciasQuimicas0402[0]?.idnormas_im || '';
        renderNorm(ultimaNorma0402 || { Tabla: [] }, null);
    });
    if (form.id !== 'FOR-PIMP-04_02') {
    function loadRecords(name) {
        recordSelect.innerHTML = form.id === 'FOR-PIMP-04_02'
            ? '<option value="">Seleccione un grado o variante</option>'
            : '<option value="">Seleccione una tabla o variable</option>';
        const options = catalog.filter(item => item.Nombre_Espe === name);
        options.forEach(item => recordSelect.add(new Option(item.Variable || 'Sin variable', item.idnormas_im)));
        recordSelect.disabled = !options.length;
        return options;
    }
    nameSelect.addEventListener('change', function () {
        const options = loadRecords(this.value);
        resultsBody.innerHTML = '';
        resultsBox.classList.add('d-none');
        if (options.length === 1) {
            recordSelect.value = options[0].idnormas_im;
            renderNorm(options[0], []);
            if (detectColumnsOnFileChange && file.files.length) detectAvailableColumns();
        }
    });
    recordSelect.addEventListener('change', function () {
        const norm = catalog.find(item => String(item.idnormas_im) === String(this.value));
        if (norm) renderNorm(norm, []);
        processed = false;
        if (detectColumnsOnFileChange && file.files.length) detectAvailableColumns();
    });

    document.addEventListener('norma-im:creada', function (event) {
        const norm = event.detail;
        if (!norm?.idnormas_im) return;

        if (!catalog.some(item => String(item.idnormas_im) === String(norm.idnormas_im))) {
            catalog.push(norm);
        }
        if (!Array.from(nameSelect.options).some(option => option.value === norm.Nombre_Espe)) {
            nameSelect.add(new Option(norm.Nombre_Espe, norm.Nombre_Espe));
        }

        nameSelect.value = norm.Nombre_Espe;
        loadRecords(norm.Nombre_Espe);
        recordSelect.value = norm.idnormas_im;
        renderNorm(norm, []);
        processed = false;

        // Si el técnico cargó primero el PDF, la nueva norma completa el dato
        // que faltaba para detectar sus disparos. Se procesa el archivo que ya
        // está seleccionado sin exigir recargar la página ni elegirlo otra vez.
        if (detectColumnsOnFileChange && file.files.length) {
            detectAvailableColumns();
        }
        saveLocalDraft();
    });

    const initialId = oldNormId || historical?.idnormas_im;
    if (initialId) {
        const catalogNorm = catalog.find(item => String(item.idnormas_im) === String(initialId));
        const norm = historical && String(historical.idnormas_im) === String(initialId) ? historical : catalogNorm;
        if (norm) {
            nameSelect.value = norm.Nombre_Espe;
            loadRecords(norm.Nombre_Espe);
            if (!Array.from(recordSelect.options).some(option => String(option.value) === String(initialId))) {
                recordSelect.add(new Option(norm.Variable || 'Tabla guardada', initialId));
                recordSelect.disabled = false;
            }
            recordSelect.value = initialId;
            renderNorm(norm, oldAverages || (norm.Tabla || []).map(row => row.Promedio || ''));
        }
    }

    } else if (historical) {
        renderNorm(historical, oldAverages || (historical.Tabla || []).map(row => row.Promedio || ''));
    }

    /**
     * Restaura primero la norma y la tabla; esos controles crean los inputs
     * de promedio que se restauran en una segunda lectura del formulario.
     */
    function restoreLocalDraft(draft) {
        restoringDraft = true;
        const initialEntries = draftEntriesForCurrentFields(draft);

        initialEntries.forEach(function (entry) {
            const isDynamicAverage = (entry.field.name || '').startsWith('Norma_IM[Promedio]');
            if (entry.field !== nameSelect && entry.field !== recordSelect && !isDynamicAverage) {
                applyDraftEntry(entry.field, entry.saved);
            }
        });

        const savedName = initialEntries.find(entry => entry.field === nameSelect);
        if (savedName) {
            applyDraftEntry(nameSelect, savedName.saved);
            nameSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }

        const entriesAfterName = draftEntriesForCurrentFields(draft);
        const savedRecord = entriesAfterName.find(entry => entry.field === recordSelect);
        if (savedRecord) {
            applyDraftEntry(recordSelect, savedRecord.saved);
            recordSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }

        // La tabla de la norma ya existe; ahora pueden recuperarse todos sus promedios.
        draftEntriesForCurrentFields(draft).forEach(function (entry) {
            applyDraftEntry(entry.field, entry.saved);
        });

        // Estos cambios actualizan secciones dependientes que no reaccionan a asignaciones directas.
        ['TieneCliente', 'TieneContrato'].forEach(function (name) {
            const checked = form.querySelector('input[name="' + name + '"]:checked');
            if (checked) checked.dispatchEvent(new Event('change', { bubbles: true }));
        });
        ['numFirmas', 'equiposSelect', 'equiposSelect1'].forEach(function (id) {
            const field = form.querySelector('#' + id);
            if (field) field.dispatchEvent(new Event('change', { bubbles: true }));
        });

        restoringDraft = false;
    }

    /** Devuelve los disparos marcados en el borrador para aplicarlos al volver a elegir el PDF. */
    function preferredDraftColumns() {
        const draft = readLocalDraft();
        if (!draft) return [];
        // Un PDF distinto comienza sin selecciones para no combinar disparos de dos análisis.
        if (!file.files.length || draft.pdfFileName !== file.files[0].name) return [];

        return draftEntriesForCurrentFields(draft)
            .filter(entry => entry.field.matches('.columna-xrf') && entry.saved.checked)
            .map(entry => Number(entry.field.value))
            .slice(0, 3);
    }

    /** Informa al tecnico que se recuperaron datos y permite descartarlos voluntariamente. */
    function showRecoveredDraftNotice() {
        const notice = document.createElement('div');
        notice.className = 'alert alert-info py-2 mt-2';
        notice.innerHTML = 'Se recuperó el borrador local de este reporte. El PDF y las imágenes deben seleccionarse nuevamente. ' +
            '<button type="button" class="btn btn-sm btn-outline-danger ml-2">Descartar borrador</button>';
        form.insertBefore(notice, form.firstChild);
        notice.querySelector('button').addEventListener('click', function () {
            localStorage.removeItem(draftKey);
            clearLegacyReportStorage();
            window.location.reload();
        });
    }

    function applyResults(results) {
        if (form.id === 'FOR-PIMP-04_02') {
            resultadosPdf0402 = results || {};
            promediosReferencias0402.clear();
            sumasManuales0402.clear();
        }
        const extracted = Object.values(results || {});
        resultsBody.querySelectorAll('input[data-elemento]').forEach(input => {
            const result = extracted.find(row => String(row.elemento).toLowerCase() === input.dataset.elemento.toLowerCase());
            if (result?.calculable) {
                input.value = Number(result.promedio).toFixed(4);
                setRowState(input, 'calculado');
            } else if (result?.valor_repetido) {
                input.value = result.valor_repetido;
                setRowState(input, 'manual');
            } else if (result?.valor_sugerido != null) {
                input.value = result.valor_sugerido;
                setRowState(input, 'manual');
                input.parentElement.querySelector('.estado-promedio-xrf').textContent = 'Promedio sugerido con valores [C<]. Revise o modifique.';
            } else {
                if (input.dataset.autoXrf === '1') input.value = '';
                setRowState(input, 'manual');
            }
        });
        actualizarSumas0402();
    }
    /** Sincroniza visibilidad, disponibilidad y selección con las columnas confirmadas por el servidor. */
    function syncAvailableColumns(availableColumns, selectedColumnsFromPdf) {
        const available = new Set((availableColumns || []).map(Number));
        const selectedFromPdf = (selectedColumnsFromPdf || []).map(Number);
        // Después de una recarga el navegador exige elegir otra vez el archivo,
        // pero el borrador puede recuperar las columnas que el técnico había marcado.
        const preferred = selectedFromPdf.length ? selectedFromPdf : preferredDraftColumns();
        const selected = new Set(preferred.filter(column => available.has(Number(column))).map(Number));
        form.querySelectorAll('.columna-xrf').forEach(input => {
            const isAvailable = available.has(Number(input.value));
            input.disabled = !isAvailable;
            input.closest('label')?.classList.toggle('d-none', !isAvailable);
            input.checked = isAvailable && selected.has(Number(input.value));
        });
    }
    /** Limpia selecciones anteriores para impedir que otro PDF conserve disparos que ya no existen. */
    function resetColumnAvailability(showAll) {
        form.querySelectorAll('.columna-xrf').forEach(input => {
            input.checked = false;
            input.disabled = !showAll;
            input.closest('label')?.classList.toggle('d-none', !showAll);
        });
    }
    function renderPreview(payload) {
        syncAvailableColumns(payload.columnas_disponibles, payload.columnas_seleccionadas);
        const columns = payload.columnas_seleccionadas;
        const rows = Object.values(payload.resultados || {});
        previewTable.querySelector('thead').innerHTML = '<tr><th>Elemento</th>' + columns.map(c => '<th>Col. ' + c + '</th>').join('') + '<th>Promedio</th></tr>';
        const escaparXrf = valor => { const span = document.createElement('span'); span.textContent = String(valor ?? ''); return span.innerHTML; };
        previewTable.querySelector('tbody').innerHTML = rows.map(row => '<tr><th>' + escaparXrf(row.elemento) + '</th>' + columns.map(c => '<td>' + escaparXrf(row.valores[c] || '') + '</td>').join('') + '<td>' + (row.calculable ? Number(row.promedio).toFixed(4) : escaparXrf(row.valor_repetido ?? row.valor_sugerido ?? 'Manual')) + '</td></tr>').join('');
        const pending = rows.filter(row => !row.calculable && !row.valor_repetido && row.valor_sugerido == null).length;
        warnings.textContent = pending ? pending + ' elemento(s) requieren captura manual individual.' : '';
        warnings.classList.toggle('d-none', !pending);
        capture.innerHTML = '<h5>Disparo único — columnas ' + columns.join(', ') + '</h5><img class="img-fluid img-thumbnail" src="' + payload.captura.data_url + '">';
        applyResults(payload.resultados);
        preview.classList.remove('d-none');
        // Los promedios se asignan por código y por eso se guardan explícitamente al terminar.
        saveLocalDraft();
    }

    /** Consulta el PDF sin calcular promedios para mostrar solamente sus disparos numerados. */
    async function detectAvailableColumns() {
        if (!detectColumnsOnFileChange || !file.files.length) return;
        resetColumnAvailability(false);
        preview.classList.add('d-none');
        if (!recordSelect.value) {
            return setStatus('Marca primero una norma de referencia en el apartado superior.', true);
        }

        const data = new FormData();
        data.append('idnormas_im', recordSelect.value);
        if (form.id === 'FOR-PIMP-04_02') data.append('configuracion', document.getElementById('normaIMConfiguracion')?.value || '');
        data.append('Analisis_PDF', file.files[0]);
        button.disabled = true;
        setStatus('Detectando disparos disponibles...', false);
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: data
            });
            let payload = await response.json();
            if (!response.ok) throw new Error(payload.errors ? Object.values(payload.errors).flat()[0] : payload.message);
            if (!payload.trabajo?.estado_url) throw new Error('No se recibio el identificador del procesamiento.');
            const claveTrabajo = 'saico:xrf:' + window.location.pathname + ':' + form.id;
            localStorage.setItem(claveTrabajo, JSON.stringify(payload.trabajo));
            window.SaicoProcesamiento.asignarTrabajo(form, payload.trabajo.id);
            const trabajo = await window.SaicoProcesamiento.esperar(payload.trabajo.estado_url, {
                alCambiar: function (actual) { setStatus(actual.mensaje || 'Procesando PDF XRF...', false); }
            });
            payload = trabajo.resultado;
            syncAvailableColumns(payload.columnas_disponibles, []);
            const total = (payload.columnas_disponibles || []).length;
            setStatus('Se detectaron ' + total + ' disparo(s). Seleccione hasta tres.', false);
        } catch (error) {
            resetColumnAvailability(false);
            setStatus(error.message || 'No fue posible detectar los disparos del PDF.', true);
        } finally {
            button.disabled = false;
        }
    }

    button.addEventListener('click', async function () {
        const columns = selectedColumns();
        if (!recordSelect.value) return setStatus(form.id === 'FOR-PIMP-04_02' ? 'Marca primero una norma de referencia en el apartado superior.' : 'Seleccione la norma y la tabla.', true);
        if (!file.files.length) return setStatus('Seleccione el PDF.', true);
        // El backend y la interfaz aceptan el mismo intervalo: de uno a tres disparos diferentes.
        if (columns.length < 1 || columns.length > 3) return setStatus('Seleccione entre uno y tres disparos.', true);
        const data = new FormData();
        data.append('idnormas_im', recordSelect.value);
        if (form.id === 'FOR-PIMP-04_02') data.append('configuracion', document.getElementById('normaIMConfiguracion')?.value || '');
        data.append('Analisis_PDF', file.files[0]);
        columns.forEach(column => data.append('XRF_Columnas[]', column));
        button.disabled = true;
        setStatus('Procesando PDF...', false);
        try {
            const response = await fetch(url, {method: 'POST', headers: {'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value, 'Accept': 'application/json'}, body: data});
            let payload = await response.json();
            if (!response.ok) throw new Error(payload.errors ? Object.values(payload.errors).flat()[0] : payload.message);
            if (!payload.trabajo?.estado_url) throw new Error('No se recibio el identificador del procesamiento.');
            const claveTrabajo = 'saico:xrf:' + window.location.pathname + ':' + form.id;
            localStorage.setItem(claveTrabajo, JSON.stringify(payload.trabajo));
            window.SaicoProcesamiento.asignarTrabajo(form, payload.trabajo.id);
            const trabajo = await window.SaicoProcesamiento.esperar(payload.trabajo.estado_url, {
                alCambiar: function (actual) { setStatus(actual.mensaje || 'Procesando PDF XRF...', false); }
            });
            payload = trabajo.resultado;
            renderPreview(payload);
            processed = true;
            setStatus('Promedios y disparo generados.', false);
        } catch (error) {
            processed = false;
            setStatus(error.message || 'No fue posible procesar el PDF.', true);
        } finally { button.disabled = false; }
    });
    function invalidate() { processed = false; }
    // En Edit se muestran inicialmente solo los disparos que pertenecen a la captura histórica.
    if (detectColumnsOnFileChange) {
        const savedColumns = (historical?.Columnas_Seleccionadas || []).map(Number);
        if (savedColumns.length) {
            syncAvailableColumns(savedColumns, savedColumns);
        } else {
            resetColumnAvailability(false);
        }
    }
    // Cambiar el archivo invalida la vista previa y obliga a detectar nuevamente sus columnas.
    file.addEventListener('change', function () {
        localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
        invalidate();
        resetColumnAvailability(!detectColumnsOnFileChange);
        preview.classList.add('d-none');
        if (detectColumnsOnFileChange) {
            detectAvailableColumns();
        } else {
            setStatus('PDF cambiado. Seleccione hasta tres disparos y vuelva a generar.', false);
        }
    });
    // Al marcar una cuarta opción se libera la selección anterior para mantener el máximo de tres.
    form.querySelectorAll('.columna-xrf').forEach(item => item.addEventListener('change', function () {
        const checked = Array.from(form.querySelectorAll('.columna-xrf:checked:not(:disabled)'));
        if (this.checked && checked.length > 3) {
            const previous = checked.find(input => input !== this);
            if (previous) previous.checked = false;
        }
        invalidate();
        preview.classList.add('d-none');
        setStatus('Selección cambiada. Presione “Extraer datos y calcular promedio”.', false);
    }));
    form.addEventListener('submit', function (event) {
        if (requirePreviewBeforeSubmit && file.files.length && !processed) {
            event.preventDefault();
            setStatus('Genere el disparo antes de finalizar.', true);
        }
        if (processed) localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
    });

    // Restaura deteccion, promedio y captura si la pagina se recargo durante el worker.
    const trabajoGuardado = localStorage.getItem('saico:xrf:' + window.location.pathname + ':' + form.id);
    if (trabajoGuardado) {
        try {
            const referencia = JSON.parse(trabajoGuardado);
            window.SaicoProcesamiento.asignarTrabajo(form, referencia.id);
            button.disabled = true;
            setStatus('Procesando PDF XRF...', false);
            window.SaicoProcesamiento.esperar(referencia.estado_url, {
                alCambiar: function (actual) { setStatus(actual.mensaje || 'Procesando PDF XRF...', false); }
            }).then(function (trabajo) {
                const salida = trabajo.resultado || {};
                if (salida.solo_deteccion) {
                    syncAvailableColumns(salida.columnas_disponibles, []);
                    setStatus('Disparos detectados. Seleccione hasta tres.', false);
                    return;
                }
                renderPreview(salida);
                processed = true;
                setStatus('PDF XRF procesado correctamente.', false);
            }).catch(function (error) {
                localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
                setStatus(error.message, true);
            }).finally(function () { button.disabled = false; });
        } catch (error) {
            localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
        }
    }

    /*
     * Inicializacion del borrador local.
     * Un envio valido se limpia al volver al formato; si Laravel devuelve
     * errores de validacion, el borrador permanece para no perder el trabajo.
     */
    const pendingSubmit = sessionStorage.getItem(pendingSubmitKey) === '1';
    // Laravel conserva los errores solo para esta respuesta. Una recarga los consume
    // y oculta el motivo por el que Guardar devolvió al formulario.
    const hasValidationErrors = @json($errors->any());
    if (pendingSubmit && !hasValidationErrors) {
        localStorage.removeItem(draftKey);
        clearLegacyReportStorage();
        sessionStorage.removeItem(pendingSubmitKey);
        window.location.reload();
        return;
    }
    sessionStorage.removeItem(pendingSubmitKey);
    if (hasValidationErrors) {
        const aviso = form.querySelector('.alert-danger');
        aviso?.scrollIntoView({behavior: 'smooth', block: 'center'});
    }

    const recoveredDraft = readLocalDraft();
    if (recoveredDraft) {
        restoreLocalDraft(recoveredDraft);
        showRecoveredDraftNotice();
    }

    // La delegacion tambien cubre promedios y controles creados despues de cargar la pagina.
    form.addEventListener('input', function (event) {
        if (shouldPersistField(event.target)) scheduleDraftSave();
    });
    form.addEventListener('change', function (event) {
        if (shouldPersistField(event.target)) scheduleDraftSave();
    });

    // Se conserva el borrador antes de navegar; solo se marca como enviado si nada cancela el submit.
    form.addEventListener('submit', function (event) {
        saveLocalDraft();
        // La microtarea espera a que los demás validadores del submit terminen, pero se ejecuta antes de navegar.
        Promise.resolve().then(function () {
            if (!event.defaultPrevented) sessionStorage.setItem(pendingSubmitKey, '1');
        });
    });
});
</script>
<script>
@if(($xrfFormId ?? 'FOR-PIMP-05_B_01') === 'FOR-PIMP-04_02')
window.normasIMFormularioUrl = @json(route('Normas_IM.Create'));
window.normasIMEdicionUrl = @json(route('Normas_IM.Edit', ['id' => '__NORMA__']));
@else
window.normasIMAltaRapidaUrl = @json(route('Normas_IM.storeRapida'));
@endif
</script>
@if(($xrfFormId ?? 'FOR-PIMP-05_B_01') !== 'FOR-PIMP-04_02')
<script src="{{ asset('js/Normas_IM_Alta_Rapida.js') }}?v={{ filemtime(public_path('js/Normas_IM_Alta_Rapida.js')) }}"></script>
@endif
