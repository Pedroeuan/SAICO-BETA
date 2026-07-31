<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById(@json($xrfFormId ?? 'FOR-PIMP-05_B_01'));
    if (!form) return;
    const catalog = @json($NormasIM ?? []);
    const historical = @json($Detalles_Generales['Norma_IM'] ?? null);
    const oldNormId = @json(old('Norma_IM.idnormas_im'));
    const oldAverages = @json(old('Norma_IM.Promedio'));
    const nameSelect = form.querySelector('#normaIMNombre');
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
    function renderNorm(norm, averages) {
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
            composition.textContent = row.Composicion || '';
            tr.append(element, average, composition);
            resultsBody.appendChild(tr);
        });
        observations.textContent = norm.Observaciones || '';
        observationsBox.classList.toggle('d-none', !norm.Observaciones);
        resultsBox.classList.remove('d-none');
    }
    function loadRecords(name) {
        recordSelect.innerHTML = '<option value="">Seleccione una tabla o variable</option>';
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

    function applyResults(results) {
        const extracted = Object.values(results || {});
        resultsBody.querySelectorAll('input[data-elemento]').forEach(input => {
            const result = extracted.find(row => String(row.elemento).toLowerCase() === input.dataset.elemento.toLowerCase());
            if (result?.calculable) {
                input.value = Number(result.promedio).toFixed(4);
                setRowState(input, 'calculado');
            } else {
                if (input.dataset.autoXrf === '1') input.value = '';
                setRowState(input, 'manual');
            }
        });
    }
    /** Sincroniza visibilidad, disponibilidad y selección con las columnas confirmadas por el servidor. */
    function syncAvailableColumns(availableColumns, selectedColumnsFromPdf) {
        const available = new Set((availableColumns || []).map(Number));
        const selected = new Set((selectedColumnsFromPdf || []).map(Number));
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
        previewTable.querySelector('tbody').innerHTML = rows.map(row => '<tr><th>' + row.elemento + '</th>' + columns.map(c => '<td>' + (row.valores[c] || '') + '</td>').join('') + '<td>' + (row.calculable ? Number(row.promedio).toFixed(4) : 'Manual') + '</td></tr>').join('');
        const pending = rows.filter(row => !row.calculable).length;
        warnings.textContent = pending ? pending + ' elemento(s) requieren captura manual individual.' : '';
        warnings.classList.toggle('d-none', !pending);
        capture.innerHTML = '<h5>Disparo único — columnas ' + columns.join(', ') + '</h5><img class="img-fluid img-thumbnail" src="' + payload.captura.data_url + '">';
        applyResults(payload.resultados);
        preview.classList.remove('d-none');
    }

    /** Consulta el PDF sin calcular promedios para mostrar solamente sus disparos numerados. */
    async function detectAvailableColumns() {
        if (!detectColumnsOnFileChange || !file.files.length) return;
        resetColumnAvailability(false);
        preview.classList.add('d-none');
        if (!recordSelect.value) {
            return setStatus('Seleccione primero la norma y la tabla para detectar los disparos.', true);
        }

        const data = new FormData();
        data.append('idnormas_im', recordSelect.value);
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
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.errors ? Object.values(payload.errors).flat()[0] : payload.message);
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
        if (!recordSelect.value) return setStatus('Seleccione la norma y la tabla.', true);
        if (!file.files.length) return setStatus('Seleccione el PDF.', true);
        // El backend y la interfaz aceptan el mismo intervalo: de uno a tres disparos diferentes.
        if (columns.length < 1 || columns.length > 3) return setStatus('Seleccione entre uno y tres disparos.', true);
        const data = new FormData();
        data.append('idnormas_im', recordSelect.value);
        data.append('Analisis_PDF', file.files[0]);
        columns.forEach(column => data.append('XRF_Columnas[]', column));
        button.disabled = true;
        setStatus('Procesando PDF...', false);
        try {
            const response = await fetch(url, {method: 'POST', headers: {'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value, 'Accept': 'application/json'}, body: data});
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.errors ? Object.values(payload.errors).flat()[0] : payload.message);
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
    });
});
</script>
<script>
window.normasIMAltaRapidaUrl = @json(route('Normas_IM.storeRapida'));
</script>
<script src="{{ asset('js/Normas_IM_Alta_Rapida.js') }}?v={{ filemtime(public_path('js/Normas_IM_Alta_Rapida.js')) }}"></script>
