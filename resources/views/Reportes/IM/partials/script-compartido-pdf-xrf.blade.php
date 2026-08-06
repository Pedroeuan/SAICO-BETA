<script src="{{ asset('js/procesamiento-asincrono-saico.js') }}?v={{ filemtime(public_path('js/procesamiento-asincrono-saico.js')) }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Script compartido: usa el formulario activo y la ruta independiente que inyecta cada formato.
    const formulario0403 = document.getElementById('FOR-PIMP-04_03');

    /*
     * El formulario de norma pertenece al 04_03, pero su comportamiento se mantiene
     * aquí porque JavaScript sí se comparte entre Create y Edit.
     */
    if (formulario0403) {
        const catalogoNormas = @json($NormasIM ?? []);
        const normaHistorica = @json($Detalles_Generales['Norma_IM'] ?? null);
        const nombreNormaSelect = formulario0403.querySelector('#normaIMNombre');
        const registroNormaSelect = formulario0403.querySelector('#normaIMRegistro');
        const resultadosNorma = formulario0403.querySelector('#normaIMResultadosContainer');
        const cuerpoNorma = formulario0403.querySelector('#tablaNormaIM tbody');
        const cajaObservacionesNorma = formulario0403.querySelector('#normaIMObservacionesContainer');
        const observacionesNorma = formulario0403.querySelector('#normaIMObservaciones');
        const idNormaAnterior = @json(old('Norma_IM.idnormas_im'));
        const promediosNormaAnteriores = @json(old('Norma_IM.Promedio'));
        let nombreNormaActual = '';
        let registroNormaActual = '';

        if (nombreNormaSelect && registroNormaSelect && cuerpoNorma) {
            const tienePromediosNorma = () => Array.from(cuerpoNorma.querySelectorAll('input'))
                .some(input => input.value.trim() !== '');

            function limpiarResultadosNorma() {
                cuerpoNorma.innerHTML = '';
                resultadosNorma.classList.add('d-none');
                observacionesNorma.textContent = '';
                cajaObservacionesNorma.classList.add('d-none');
            }

            function cargarRegistrosNorma(nombre) {
                registroNormaSelect.innerHTML = '<option value="">Seleccione una tabla o variable</option>';
                const opciones = catalogoNormas.filter(item => item.Nombre_Espe === nombre);
                opciones.forEach(item => registroNormaSelect.add(
                    new Option(item.Variable || 'Sin variable/subtítulo', item.idnormas_im)
                ));
                registroNormaSelect.disabled = opciones.length === 0;
                return opciones;
            }

            function mostrarResultadosNorma(norma, promedios) {
                cuerpoNorma.innerHTML = '';
                (norma.Tabla || []).forEach((fila, index) => {
                    const renglon = document.createElement('tr');
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.className = 'form-control text-center';
                    input.name = 'Norma_IM[Promedio][' + index + ']';
                    input.dataset.elemento = fila.Elemento || '';
                    input.value = promedios?.[index] ?? fila.Promedio ?? '';
                    input.placeholder = 'Capture el promedio';

                    const promedio = document.createElement('td');
                    promedio.appendChild(input);
                    const elemento = document.createElement('td');
                    elemento.textContent = fila.Elemento || '';
                    const composicion = document.createElement('td');
                    composicion.textContent = fila.Composicion || '';
                    renglon.append(elemento, promedio, composicion);
                    cuerpoNorma.appendChild(renglon);
                });
                observacionesNorma.textContent = norma.Observaciones || '';
                cajaObservacionesNorma.classList.toggle('d-none', !norma.Observaciones);
                resultadosNorma.classList.remove('d-none');
            }

            nombreNormaSelect.addEventListener('change', function () {
                if (nombreNormaActual && this.value !== nombreNormaActual && tienePromediosNorma()
                    && !window.confirm('Al cambiar la norma se eliminarán los promedios capturados. ¿Desea continuar?')) {
                    this.value = nombreNormaActual;
                    return;
                }
                nombreNormaActual = this.value;
                registroNormaActual = '';
                limpiarResultadosNorma();
                const opciones = cargarRegistrosNorma(this.value);
                if (opciones.length === 1) {
                    registroNormaSelect.value = opciones[0].idnormas_im;
                    registroNormaActual = String(opciones[0].idnormas_im);
                    mostrarResultadosNorma(opciones[0], []);
                }
            });

            registroNormaSelect.addEventListener('change', function () {
                if (registroNormaActual && this.value !== registroNormaActual && tienePromediosNorma()
                    && !window.confirm('Al cambiar la tabla se eliminarán los promedios capturados. ¿Desea continuar?')) {
                    this.value = registroNormaActual;
                    return;
                }
                registroNormaActual = this.value;
                const norma = catalogoNormas.find(item => String(item.idnormas_im) === String(this.value));
                norma ? mostrarResultadosNorma(norma, []) : limpiarResultadosNorma();
            });

            document.addEventListener('norma-im:creada', function (event) {
                const norma = event.detail;
                if (!norma?.idnormas_im) return;

                if (!catalogoNormas.some(item => String(item.idnormas_im) === String(norma.idnormas_im))) {
                    catalogoNormas.push(norma);
                }
                if (!Array.from(nombreNormaSelect.options)
                    .some(option => option.value === norma.Nombre_Espe)) {
                    nombreNormaSelect.add(new Option(norma.Nombre_Espe, norma.Nombre_Espe));
                }

                nombreNormaSelect.value = norma.Nombre_Espe;
                nombreNormaActual = norma.Nombre_Espe;
                cargarRegistrosNorma(norma.Nombre_Espe);
                registroNormaSelect.value = norma.idnormas_im;
                registroNormaActual = String(norma.idnormas_im);
                mostrarResultadosNorma(norma, []);
            });

            const idNormaInicial = idNormaAnterior || normaHistorica?.idnormas_im;
            if (idNormaInicial) {
                const normaCatalogo = catalogoNormas.find(
                    item => String(item.idnormas_im) === String(idNormaInicial)
                );
                const normaInicial = !idNormaAnterior && normaHistorica ? normaHistorica : normaCatalogo;
                if (normaInicial) {
                    nombreNormaSelect.value = normaInicial.Nombre_Espe;
                    nombreNormaActual = normaInicial.Nombre_Espe;
                    cargarRegistrosNorma(normaInicial.Nombre_Espe);
                    if (!Array.from(registroNormaSelect.options)
                        .some(option => String(option.value) === String(idNormaInicial))) {
                        registroNormaSelect.add(new Option(
                            normaInicial.Variable || 'Tabla guardada', idNormaInicial
                        ));
                        registroNormaSelect.disabled = false;
                    }
                    registroNormaSelect.value = idNormaInicial;
                    registroNormaActual = String(idNormaInicial);
                    const promedios = promediosNormaAnteriores
                        || (normaInicial.Tabla || []).map(fila => fila.Promedio || '');
                    mostrarResultadosNorma(normaInicial, promedios);
                }
            }
        }
    }

    const fileInput = document.getElementById('analisisPdfXrf');
    const extractButton = document.getElementById('extraerAnalisisPdfBtn');
    const standardSelect = document.getElementById('normaIMRegistro');
    const status = document.getElementById('estadoAnalisisPdf');
    const preview = document.getElementById('vistaAnalisisPdf');
    const warningBox = document.getElementById('alertasAnalisisPdf');
    const table = document.getElementById('tablaAnalisisPdf');
    const shotCropsContainer = document.getElementById('recortesXrfDisparos');
    const form = fileInput ? fileInput.closest('form') : null;
    const extractionUrl = @json($xrfExtractionRoute ?? route('Reportes_FOR_PIMP_06_B_01.extraer_analisis'));
    let cropsReady = false;
    let replacedExistingShots = [];

    if (!fileInput || !extractButton || !standardSelect || !table) return;

    const escapeHtml = value => String(value ?? '')
        .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;').replaceAll("'", '&#039;');

    function setStatus(message, isError = false) {
        status.textContent = message;
        status.classList.remove('d-none', 'text-muted', 'text-danger', 'text-success');
        status.classList.add(isError ? 'text-danger' : 'text-success');
    }

    // Solo llena un elemento cuando todos los PDF aportaron un valor numérico válido.
    function applyAverages(averages) {
        document.querySelectorAll('#tablaNormaIM input[data-elemento]').forEach(input => {
            const key = input.dataset.elemento.toLowerCase();
            const entry = Object.entries(averages).find(([element]) => element.toLowerCase() === key);
            const result = entry ? entry[1] : null;
            input.value = result && result.cantidad === result.esperados && result.promedio !== null
                ? Number(result.promedio).toFixed(4)
                : '';
        });
    }

    function restoreExistingShotImages() {
        replacedExistingShots.forEach(item => {
            item.card.style.display = item.previousDisplay;
            item.deletedInput.value = item.previousDeletedValue;
        });
        replacedExistingShots = [];
    }

    // En Edit se ocultan temporalmente los recortes XRF anteriores; se restauran si la extracción falla.
    function markExistingShotImagesForReplacement() {
        restoreExistingShotImages();
        document.querySelectorAll('input[name^="existing_images["]').forEach(existingInput => {
            const card = existingInput.closest('[id^="image-container-"]');
            const shotInput = card?.querySelector('input[name^="es_disparo["]');
            const deletedInput = card?.querySelector('input[name="deleted_images[]"]');
            const indexMatch = existingInput.name.match(/existing_images\[(\d+)\]/);

            if (!card || !deletedInput || !indexMatch || shotInput?.value !== '1') return;
            replacedExistingShots.push({
                card: card,
                deletedInput: deletedInput,
                previousDisplay: card.style.display,
                previousDeletedValue: deletedInput.value
            });
            deletedInput.value = indexMatch[1];
            card.style.display = 'none';
        });
    }

    // Convierte los dos recortes de cada PDF en las imágenes que viajarán con el envío del formulario.
    function renderShotCrops(crops) {
        if (!shotCropsContainer) return;

        markExistingShotImagesForReplacement();
        shotCropsContainer.innerHTML = '<h5>Imágenes automáticas de los disparos</h5>'
            + crops.map((crop, shotIndex) => {
                const imageIndex = 1000 + (shotIndex * 2);
                const graphIndex = imageIndex + 1;
                const shot = Number(crop.disparo);
                const tableImage = crop.tabla_elementos?.data_url || '';
                const graphImage = crop.grafica_espectro?.data_url || '';
                const identity = crop.daily_id ? ' — Daily ID ' + escapeHtml(crop.daily_id) : '';

                return '<div class="card mb-3 border-primary xrf-shot-card">'
                    + '<div class="card-header bg-primary text-white"><strong>Disparo ' + shot + identity + '</strong><br><small>' + escapeHtml(crop.archivo) + '</small></div>'
                    + '<div class="card-body"><div class="row">'
                    + '<div class="col-md-6 text-center"><strong>1ª imagen: tabla de elementos</strong><img src="' + tableImage + '" class="img-fluid img-thumbnail d-block mt-2" alt="Tabla del disparo ' + shot + '"></div>'
                    + '<div class="col-md-6 text-center"><strong>2ª imagen: gráfica del espectro</strong><img src="' + graphImage + '" class="img-fluid img-thumbnail d-block mt-2" alt="Gráfica del disparo ' + shot + '"></div>'
                    + '</div></div>'
                    + '<input type="hidden" name="images_base64[' + imageIndex + ']" value="' + tableImage + '">'
                    + '<input type="hidden" name="es_disparo[' + imageIndex + ']" value="1">'
                    + '<input type="hidden" name="numero_disparo[' + imageIndex + ']" value="' + shot + '">'
                    + '<input type="hidden" name="comments[' + imageIndex + ']" value="Tabla de elementos - Disparo ' + shot + '">'
                    + '<input type="hidden" name="images_base64[' + graphIndex + ']" value="' + graphImage + '">'
                    + '<input type="hidden" name="es_disparo[' + graphIndex + ']" value="1">'
                    + '<input type="hidden" name="numero_disparo[' + graphIndex + ']" value="' + shot + '">'
                    + '<input type="hidden" name="comments[' + graphIndex + ']" value="Gráfica del espectro - Disparo ' + shot + '">'
                    + '</div>';
            }).join('');

        cropsReady = crops.length > 0;
    }

    function renderPreview(payload) {
        const analyses = payload.analisis || [];
        const averages = payload.promedios || {};
        const warnings = Array.isArray(payload.advertencias) ? [...payload.advertencias] : [];
        if (analyses.length > 3) {
            warnings.push('Solo los primeros tres PDF se asignaron a disparos; todos se utilizaron para calcular los promedios.');
        }

        table.querySelector('thead').innerHTML = '<tr><th>Elemento</th>'
            + analyses.map(item => '<th>' + escapeHtml(item.archivo) + '</th>').join('')
            + '<th>Promedio</th><th>Estado</th></tr>';

        table.querySelector('tbody').innerHTML = Object.entries(averages).map(([element, result]) => {
            const cells = analyses.map(item => {
                const reading = item.lecturas ? item.lecturas[element] : null;
                if (!reading) return '<td class="table-warning">No encontrado</td>';
                const qualifier = reading.calificador || '';
                return '<td>' + escapeHtml(qualifier + reading.valor)
                    + '<small class="d-block text-muted">±3σ ' + escapeHtml(reading.incertidumbre_3sigma) + '</small></td>';
            }).join('');
            const complete = result.cantidad === result.esperados && result.promedio !== null;
            if (!complete) warnings.push(element + ': faltan lecturas válidas (' + result.cantidad + ' de ' + result.esperados + ').');

            return '<tr><th>' + escapeHtml(element) + '</th>' + cells
                + '<td><strong>' + (complete ? Number(result.promedio).toFixed(4) : '—') + '</strong></td>'
                + '<td class="' + (complete ? 'table-success' : 'table-warning') + '">'
                + (complete ? 'Calculado' : 'Revisar') + '</td></tr>';
        }).join('');

        const metadataWarnings = analyses.flatMap(item => {
            const serial = item.metadatos?.numero_serie || 'sin serie';
            return item.metadatos?.fecha_hora ? [] : [item.archivo + ': no se detectó fecha/hora (serie ' + serial + ').'];
        });
        warnings.push(...metadataWarnings);
        const serials = [...new Set(analyses.map(item => item.metadatos?.numero_serie).filter(Boolean))];
        const methods = [...new Set(analyses.map(item => item.metadatos?.metodo).filter(Boolean))];
        if (serials.length > 1) warnings.push('Los PDF pertenecen a números de serie diferentes: ' + serials.join(', ') + '.');
        if (methods.length > 1) warnings.push('Los PDF utilizaron métodos diferentes: ' + methods.join(', ') + '.');

        warningBox.innerHTML = warnings.map(message => '<div>• ' + escapeHtml(message) + '</div>').join('');
        warningBox.classList.toggle('d-none', warnings.length === 0);
        preview.classList.remove('d-none');
        applyAverages(averages);
        renderShotCrops(payload.recortes_disparos || []);
    }

    extractButton.addEventListener('click', async function () {
        if (!standardSelect.value) {
            setStatus('Primero seleccione la norma y la tabla.', true);
            return;
        }
        if (!fileInput.files.length) {
            setStatus('Seleccione al menos un PDF.', true);
            return;
        }
        if (fileInput.files.length > 10) {
            setStatus('Solo se permiten hasta 10 PDF.', true);
            return;
        }

        const data = new FormData();
        data.append('idnormas_im', standardSelect.value);
        Array.from(fileInput.files).forEach(file => data.append('Analisis_PDF[]', file));

        extractButton.disabled = true;
        setStatus('Leyendo los PDF y calculando...');

        try {
            const response = await fetch(extractionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        || document.querySelector('input[name="_token"]')?.value,
                    'Accept': 'application/json'
                },
                body: data
            });
            let payload = await response.json();
            if (!response.ok) {
                const validationMessage = payload.errors ? Object.values(payload.errors).flat()[0] : null;
                throw new Error(validationMessage || payload.message || 'No fue posible procesar los PDF.');
            }

            if (!payload.trabajo?.estado_url) throw new Error('No se recibio el identificador del procesamiento.');
            const claveTrabajo = 'saico:xrf:' + window.location.pathname + ':' + form.id;
            localStorage.setItem(claveTrabajo, JSON.stringify(payload.trabajo));
            window.SaicoProcesamiento.asignarTrabajo(form, payload.trabajo.id);
            const trabajo = await window.SaicoProcesamiento.esperar(payload.trabajo.estado_url, {
                alCambiar: function (actual) { setStatus(actual.mensaje || 'Procesando PDF XRF...'); }
            });
            payload = trabajo.resultado;

            renderPreview(payload);
            setStatus(payload.analisis.length + ' PDF procesado(s). Revise los resultados antes de finalizar.');
        } catch (error) {
            setStatus(error.message || 'No fue posible procesar los PDF.', true);
        } finally {
            extractButton.disabled = false;
        }
    });

    fileInput.addEventListener('change', function () {
        localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
        cropsReady = false;
        restoreExistingShotImages();
        if (shotCropsContainer) shotCropsContainer.innerHTML = '';
        preview.classList.add('d-none');
        status.classList.add('d-none');
    });
    standardSelect.addEventListener('change', function () {
        localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
        cropsReady = false;
        restoreExistingShotImages();
        if (shotCropsContainer) shotCropsContainer.innerHTML = '';
        preview.classList.add('d-none');
        status.classList.add('d-none');
    });

    form?.addEventListener('submit', function (event) {
        if (fileInput.files.length && !cropsReady) {
            event.preventDefault();
            setStatus('Presione “Extraer datos y calcular promedio” para generar las imágenes de los disparos antes de finalizar.', true);
            fileInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        if (cropsReady) localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
    });

    // Una recarga no obliga a seleccionar otra vez los PDF mientras el UUID siga vigente.
    const trabajoGuardado = localStorage.getItem('saico:xrf:' + window.location.pathname + ':' + form.id);
    if (trabajoGuardado) {
        try {
            const referencia = JSON.parse(trabajoGuardado);
            window.SaicoProcesamiento.asignarTrabajo(form, referencia.id);
            extractButton.disabled = true;
            setStatus('Procesando PDF XRF...');
            window.SaicoProcesamiento.esperar(referencia.estado_url, {
                alCambiar: function (actual) { setStatus(actual.mensaje || 'Procesando PDF XRF...'); }
            }).then(function (trabajo) {
                renderPreview(trabajo.resultado);
                cropsReady = true;
                setStatus('PDF XRF procesado correctamente.');
            }).catch(function (error) {
                localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
                setStatus(error.message, true);
            }).finally(function () { extractButton.disabled = false; });
        } catch (error) {
            localStorage.removeItem('saico:xrf:' + window.location.pathname + ':' + form.id);
        }
    }
});
</script>
<script>
window.normasIMAltaRapidaUrl = @json(route('Normas_IM.storeRapida'));
</script>
<script src="{{ asset('js/Normas_IM_Alta_Rapida.js') }}?v={{ filemtime(public_path('js/Normas_IM_Alta_Rapida.js')) }}"></script>
