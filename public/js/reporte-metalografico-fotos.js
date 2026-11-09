(function () {
    'use strict';

    /**
     * Une las herramientas metalográficas con los dos primeros espacios de FOTOS.
     * Solo presenta y edita referencias existentes: no sube otra imagen ni ejecuta Fiji nuevamente.
     */
    function iniciar(contenedor) {
        const imagen = contenedor.querySelector('[data-analysis-report-original]');
        const sinImagen = contenedor.querySelector('[data-analysis-report-no-image]');
        const descripcion = contenedor.querySelector('[data-analysis-report-description]');
        const botonActualizar = contenedor.querySelector('[data-analysis-report-refresh]');
        const datosExistentes = document.querySelector('[data-analysis-report-existing]');
        const entradaConteo = document.querySelector('[data-grain-json]');
        const formulario = contenedor.closest('form');
        if (['FOR-PIMP-04_02', 'FOR-PIMP-04_03', 'FOR-PIMP-03_B_01'].includes(formulario?.id)) {
            // Comparación visual: no crea campos, fotografías ni posiciones adicionales en el PDF.
            const columnaTexto = descripcion.closest('.col-md-6');
            const fila = columnaTexto?.parentElement;
            if (fila) {
                columnaTexto.classList.replace('col-md-6', 'col-12');
                const comparacion = document.createElement('div');
                comparacion.className = 'col-md-6 mb-3';
                comparacion.innerHTML = '<div class="card h-100 border-primary">' +
                    '<div class="card-header"><strong>Patrón de tamaño de grano — Comparación visual</strong></div>' +
                    '<div class="card-body text-center"><label class="font-weight-bold d-block text-left">Seleccionar tamaño de grano</label>' +
                    '<select class="form-control mb-3" data-comparison-select aria-label="Seleccionar tamaño de grano"><option value="" disabled>Selecciona un grano</option></select>' +
                    '<strong class="d-block mb-2" data-comparison-name></strong>' +
                    '<img class="img-fluid d-none" style="max-height:420px" alt="Patrón de grano para comparación" data-comparison-image>' +
                    '<p class="text-muted" data-comparison-empty>Selecciona un grano para compararlo con la micrografía.</p></div></div>';
                fila.insertBefore(comparacion, columnaTexto);
                const vista = comparacion.querySelector('[data-comparison-image]');
                const nombre = comparacion.querySelector('[data-comparison-name]');
                const vacio = comparacion.querySelector('[data-comparison-empty]');
                const selector = comparacion.querySelector('[data-comparison-select]');
                const unificarPatronPdf = ['FOR-PIMP-04_02', 'FOR-PIMP-04_03', 'FOR-PIMP-03_B_01'].includes(formulario.id);
                if (unificarPatronPdf) {
                    selector.options[0].disabled = false;
                    selector.options[0].textContent = 'Sin patrón en el PDF';
                    comparacion.querySelector('.card-header strong').textContent = 'Patrón de tamaño de grano — Comparativa y ubicación en el PDF';
                }
                const configuracion = formulario.querySelector('[data-grain-pattern-config]');
                // Sin patron conserva un editor visible del pie y la ubicacion del recuadro con X.
                let panelSinPatron;
                if (['FOR-PIMP-04_03', 'FOR-PIMP-03_B_01'].includes(formulario.id) && configuracion) {
                    panelSinPatron = document.createElement('div');
                    panelSinPatron.hidden = true;
                    panelSinPatron.className = 'text-left mt-3';
                    panelSinPatron.innerHTML = '<label class="font-weight-bold d-block">Comentario del patrón<textarea class="form-control mt-1" rows="2" data-empty-grain-comment></textarea></label>' +
                        '<details class="mt-3"><summary class="font-weight-bold">Ubicación en el PDF</summary>' +
                        '<div class="border rounded p-2 mt-2 bg-light"><div class="row align-items-center"><div class="col-4">' +
                        '<label class="font-weight-bold mb-1">Número de hoja<input type="number" min="1" class="form-control form-control-sm mt-1" data-empty-grain-page></label></div>' +
                        '<div class="col-8"><div class="font-weight-bold mb-1">Posición en la hoja</div>' +
                        [['arriba_izquierda', 'Arriba izquierda'], ['arriba_derecha', 'Arriba derecha'],
                            ['abajo_izquierda', 'Abajo izquierda'], ['abajo_derecha', 'Abajo derecha'],
                            ['pagina_completa', 'Página completa']].map(function (item) {
                            return '<label class="form-check form-check-inline mb-1"><input type="radio" class="form-check-input" name="ubicacion-sin-patron-0403" data-empty-grain-radio value="' + item[0] + '"><span class="form-check-label">' + item[1] + '</span></label>';
                        }).join('') + '<input type="hidden" data-empty-grain-position></div></div></div></details>';
                    comparacion.querySelector('.card-body').appendChild(panelSinPatron);
                    panelSinPatron.querySelectorAll('[data-empty-grain-radio]').forEach(function (radio) {
                        radio.addEventListener('change', function () {
                            if (!radio.checked) return;
                            configuracion.querySelector('[data-grain-pattern-position]').value = radio.value;
                            panelSinPatron.querySelector('[data-empty-grain-position]').value = radio.value;
                        });
                    });
                    [['[data-empty-grain-comment]', '[data-grain-pattern-description]'],
                        ['[data-empty-grain-page]', '[data-grain-pattern-page]'],
                        ['[data-empty-grain-position]', '[data-grain-pattern-position]']].forEach(function (par) {
                        const visible = panelSinPatron.querySelector(par[0]);
                        const guardado = configuracion.querySelector(par[1]);
                        visible.addEventListener('input', function () { guardado.value = visible.value; });
                        visible.addEventListener('change', function () { guardado.value = visible.value; });
                    });
                }
                let patrones = [];
                let historico = {};
                try {
                    patrones = JSON.parse(configuracion?.querySelector('[data-grain-pattern-catalog]')?.textContent || '[]');
                    historico = JSON.parse(configuracion?.querySelector('[data-grain-pattern-historical]')?.textContent || '{}');
                } catch (error) { patrones = []; }
                if (historico.id && !patrones.some(item => String(item.id) === String(historico.id))) patrones.push(historico);
                patrones.forEach(item => selector.add(new Option(item.nombre || ('Grano ' + item.valor_grano), String(item.id))));
                selector.value = configuracion?.querySelector('[data-grain-pattern-id]')?.value || '';
                selector.addEventListener('change', function () {
                    formulario.dispatchEvent(new CustomEvent('saico:grain-pattern-select', {detail: {id: selector.value}}));
                });
                function actualizarComparacion() {
                    const tarjeta = formulario.querySelector('[data-grain-mode="1"]');
                    // Ambos formatos usan la misma tarjeta para comparar y ubicar el patron, sin duplicar campos.
                    const unificar = unificarPatronPdf && tarjeta?.hasAttribute('data-grain-history-card');
                    const sinPatron = panelSinPatron && !unificar && !configuracion?.querySelector('[data-grain-pattern-id]')?.value;
                    if (panelSinPatron) {
                        panelSinPatron.hidden = !sinPatron;
                        if (sinPatron) {
                            panelSinPatron.querySelector('[data-empty-grain-comment]').value = configuracion.querySelector('[data-grain-pattern-description]').value;
                            panelSinPatron.querySelector('[data-empty-grain-page]').value = configuracion.querySelector('[data-grain-pattern-page]').value;
                            panelSinPatron.querySelector('[data-empty-grain-position]').value = configuracion.querySelector('[data-grain-pattern-position]').value;
                            panelSinPatron.querySelectorAll('[data-empty-grain-radio]').forEach(function (radio) {
                                radio.checked = radio.value === configuracion.querySelector('[data-grain-pattern-position]').value;
                            });
                        }
                    }
                    if (unificar) {
                        const filaGrano = tarjeta.closest('.grain-history-row');
                        const destino = comparacion.querySelector('.card-body');
                        if (filaGrano && filaGrano.parentElement !== destino) {
                            tarjeta.classList.replace('col-sm-6', 'col-12');
                            const etiqueta = tarjeta.querySelector('.form-group > label');
                            if (etiqueta) etiqueta.hidden = true;
                            const checkGrano = tarjeta.querySelector('.foto-grain-checkbox');
                            if (checkGrano) checkGrano.closest('.form-check').hidden = true;
                            const grupo = tarjeta.querySelector('.form-group');
                            const panel = tarjeta.querySelector('[data-grain-card-panel]');
                            const selectorPatron = panel?.querySelector('[data-grain-card-select]');
                            if (selectorPatron?.options[0]) selectorPatron.options[0].textContent = 'Sin patrón en el PDF';
                            const comentario = tarjeta.querySelector('[data-grain-card-description]');
                            const etiquetaComentario = tarjeta.querySelector('[data-grain-description-label]');
                            const layout = tarjeta.querySelector('.foto-layout-manual');
                            if (grupo && panel && comentario && layout) {
                                grupo.classList.remove('border', 'rounded');
                                grupo.style.textAlign = 'left';
                                grupo.appendChild(panel);
                                if (etiquetaComentario) {
                                    etiquetaComentario.textContent = 'Comentario del patrón';
                                    grupo.appendChild(etiquetaComentario);
                                }
                                comentario.rows = 2;
                                grupo.appendChild(comentario);
                                const ubicacion = document.createElement('details');
                                ubicacion.className = 'mt-3';
                                const resumen = document.createElement('summary');
                                resumen.className = 'font-weight-bold';
                                resumen.textContent = 'Ubicación en el PDF: hoja ' + (layout.querySelector('.foto-pagina')?.value || '1');
                                ubicacion.append(resumen, layout);
                                grupo.appendChild(ubicacion);
                                layout.addEventListener('change', function () {
                                    const posicion = layout.querySelector('input[type="radio"]:checked');
                                    const texto = posicion?.parentElement.textContent.trim() || '';
                                    resumen.textContent = 'Ubicación en el PDF: hoja ' + layout.querySelector('.foto-pagina').value + (texto ? ' · ' + texto : '');
                                });
                            }
                            destino.appendChild(filaGrano);
                        }
                    }
                    selector.hidden = Boolean(unificar);
                    selector.previousElementSibling.hidden = Boolean(unificar);
                    if (unificar) {
                        vista.classList.add('d-none');
                        nombre.classList.add('d-none');
                        vacio.classList.add('d-none');
                        return;
                    }
                    nombre.classList.remove('d-none');
                    const original = tarjeta?.querySelector('[data-grain-card-preview]');
                    const ruta = original?.getAttribute('src') || '';
                    if (ruta && vista.getAttribute('src') !== ruta) vista.setAttribute('src', ruta);
                    if (!ruta && vista.hasAttribute('src')) vista.removeAttribute('src');
                    vista.classList.toggle('d-none', !ruta);
                    vacio.classList.toggle('d-none', Boolean(ruta) || Boolean(sinPatron));
                    const titulo = tarjeta?.querySelector('[data-grain-card-name]')?.textContent || '';
                    if (nombre.textContent !== titulo) nombre.textContent = titulo;
                    const id = configuracion?.querySelector('[data-grain-pattern-id]')?.value || '';
                    if (selector.value !== id) selector.value = id;
                }
                new MutationObserver(actualizarComparacion).observe(formulario, {
                    subtree: true, childList: true, attributes: true, attributeFilter: ['src', 'data-grain-mode'],
                });
                actualizarComparacion();
            }
        }
        let analisisActual = {};
        let ultimoToken = contenedor.dataset.analysisReportToken || '';
        // Solo oculta el texto del conteo; el JSON de lineas y mediciones permanece intacto.
        if (formulario?.id === 'FOR-PIMP-04_02') {
            descripcion.value = descripcion.value.replace(/^\s*(?:CONTEO LINEAL DE GRANOS|Conteo lineal de granos:[^\r\n]*|Regla: cada grano completo[^\r\n]*|L\d+: cruces[^\r\n]*|Resumen: \d+ l[i\u00ed]neas; suma[^\r\n]*)[ \t]*\r?\n?/gm, '').trim();
        }
        let descripcionEditada = descripcion.value.trim() !== '';

        /** Activa los controles de distribución únicamente cuando el análisis fue enviado al PDF. */
        function actualizarControlesDistribucion(activo) {
            contenedor.querySelectorAll('[data-auto-report-layout] input').forEach(function (control) {
                // Los checks que explican el tipo imagen/texto son informativos y siempre permanecen bloqueados.
                if (control.type !== 'checkbox') control.disabled = !activo;
            });
        }

        // Restaura los datos de Edit para mantener visible la selección previamente guardada.
        try {
            analisisActual = JSON.parse(datosExistentes?.textContent || '{}');
        } catch (error) {
            analisisActual = {};
        }

        /** Obtiene siempre la versión más reciente del contador lineal. */
        function obtenerConteo() {
            try {
                return JSON.parse(entradaConteo?.value || '{}');
            } catch (error) {
                return {};
            }
        }

        /** Lee un dato del bloque metalográfico aunque visualmente use select + caja nueva. */
        function obtenerDatoEquipo(campo, respaldo) {
            const entrada = document.querySelector('[name="Datos_Equipo[' + campo + ']"]');
            const valor = String(entrada?.value || '').trim();
            return valor || respaldo;
        }

        /** Obtiene el valor ASTM del patrón activo, incluida su copia histórica en Edit. */
        function obtenerTamanoGrano() {
            const configuracion = document.querySelector('[data-grain-pattern-config]');
            const id = String(configuracion?.querySelector('[data-grain-pattern-id]')?.value || '');
            let catalogo = [];
            let historico = {};

            if (!id || !configuracion) return '---';
            try {
                catalogo = JSON.parse(configuracion.querySelector('[data-grain-pattern-catalog]')?.textContent || '[]');
                historico = JSON.parse(configuracion.querySelector('[data-grain-pattern-historical]')?.textContent || '{}');
            } catch (error) {
                return '---';
            }

            const patron = catalogo.find(function (elemento) {
                return String(elemento.id) === id;
            }) || (String(historico.id || '') === id ? historico : {});

            return String(patron.valor_grano || patron.nombre || '').trim() || '---';
        }

        /** Convierte los resultados técnicos en una base editable para la descripción del reporte. */
        function construirDescripcion() {
            const fases = obtenerDatoEquipo('FASES_PRESENTES', '---').toLocaleLowerCase('es');
            const lineas = [
                'RESULTADOS DEL ANÁLISIS METALOGRÁFICO',
                '',
                'Fases presentes: ' + fases.charAt(0).toLocaleUpperCase('es') + fases.slice(1),
                'Morfología de la microestructura: ---',
                '% fracción volumétrica Perlita / zonas oscuras: ' + Number(analisisActual.porcentaje_perlita || 0).toFixed(3) + ' %',
                '% fracción volumétrica Ferrita / zonas claras: ' + Number(analisisActual.porcentaje_ferrita || 0).toFixed(3) + ' %',
                'Método de tamaño de grano ASTM E112: Comparativo',
                'Tamaño de grano: ' + obtenerTamanoGrano(),
                'Bandeamiento: ---',
                'Magnificación: 100 X',
                'Especificación aproximada de la pieza: ' + obtenerDatoEquipo('ESPECIFICACION_MATERIAL', '---'),
                'Analizador: Fiji',
            ];
            if (formulario?.id === 'FOR-PIMP-04_02') return lineas.join('\n');
            const conteo = obtenerConteo();
            const lineasConteo = Array.isArray(conteo.lineas) ? conteo.lineas : [];

            if (lineasConteo.length) {
                lineas.push('', 'CONTEO LINEAL DE GRANOS');
                lineas.push('Regla: cada grano completo = 1; cada extremo = 0.5.');
                lineasConteo.forEach(function (linea, indice) {
                    lineas.push(
                        'L' + Number(linea.id || indice + 1) +
                        ': cruces ' + Number(linea.cruces || 0) +
                        '; completos ' + Number(linea.granos_completos || 0) +
                        '; extremos 0.5 + 0.5; conteo ' + Number(linea.conteo || 0).toFixed(1) + '.'
                    );
                });
                const resumen = conteo.resumen || {};
                lineas.push(
                    'Resumen: ' + Number(resumen.numero_lineas || lineasConteo.length) +
                    ' líneas; suma ' + Number(resumen.suma || 0).toFixed(1) +
                    '; promedio ' + Number(resumen.promedio || 0).toFixed(3) + ' granos por línea.'
                );
            } else {
                lineas.push('', 'Conteo lineal de granos: sin líneas registradas.');
            }

            return lineas.join('\n');
        }

        /** Muestra la ruta original que ya fue almacenada por el análisis. */
        function mostrarImagen() {
            const imagenAnalisis = document.querySelector('[data-imagej-original]');
            const ruta = analisisActual.urls?.imagen_visual || analisisActual.urls?.original || imagenAnalisis?.src || '';
            imagen.src = ruta;
            imagen.classList.toggle('d-none', !ruta);
            sinImagen.classList.toggle('d-none', Boolean(ruta));
        }

        // El técnico puede alterar libremente el texto; después de ello no se sobreescribe automáticamente.
        descripcion.addEventListener('input', function () {
            descripcionEditada = true;
        });

        // Este botón permite recuperar todos los valores actuales si el conteo cambió después de editar.
        botonActualizar.addEventListener('click', function () {
            descripcion.value = construirDescripcion();
            descripcionEditada = false;
        });

        document.addEventListener('saico:image-analysis-report-selection', function (evento) {
            const seleccionado = Boolean(evento.detail?.selected);
            const nuevoAnalisis = evento.detail?.analysis;
            if (nuevoAnalisis && typeof nuevoAnalisis === 'object') analisisActual = nuevoAnalisis;
            contenedor.classList.toggle('d-none', !seleccionado);
            actualizarControlesDistribucion(seleccionado);
            if (!seleccionado) return;

            mostrarImagen();
            const nuevoToken = String(analisisActual.token || '');
            // Un análisis diferente recibe su propia descripción; reactivar el mismo conserva la edición humana.
            if (nuevoToken !== ultimoToken) {
                ultimoToken = nuevoToken;
                descripcionEditada = false;
                descripcion.value = construirDescripcion();
            } else if (!descripcion.value.trim()) {
                descripcion.value = construirDescripcion();
            }
        });

        // Mientras el técnico no edite el texto, los cruces, suma y promedio permanecen sincronizados.
        document.addEventListener('saico:grain-count-updated', function () {
            if (!contenedor.classList.contains('d-none') && !descripcionEditada) {
                descripcion.value = construirDescripcion();
            }
        });

        // El grano es un dato vinculado: actualiza solo su renglon aun si se editaron otras observaciones.
        function actualizarGranoEnDescripcion() {
            descripcion.value = descripcion.value.replace(
                /^([ \t]*Tamaño de grano[ \t]*:)[^\r\n]*/m,
                (_, etiqueta) => etiqueta + ' ' + obtenerTamanoGrano()
            );
        }
        document.addEventListener('saico:grain-pattern-updated', function () {
            if (formulario?.id === 'FOR-PIMP-04_02' && descripcion.value.trim()) {
                actualizarGranoEnDescripcion();
            } else if (!contenedor.classList.contains('d-none') && !descripcionEditada) {
                descripcion.value = construirDescripcion();
            }
        });
        document.addEventListener('input', function (evento) {
            if (evento.target.matches('[name="Datos_Equipo[FASES_PRESENTES]"]')
                && !contenedor.classList.contains('d-none') && !descripcionEditada) {
                descripcion.value = construirDescripcion();
            }
        });

        // Sincroniza solo la especificacion; conserva las demas anotaciones del tecnico.
        const sincronizarEspecificacion = function (evento) {
            if (formulario?.id === 'FOR-PIMP-04_02' && evento.target.name === 'Datos_Equipo[TAMANO_GRANO]') {
                actualizarGranoEnDescripcion();
                return;
            }
            if (!['FOR-PIMP-04_02', 'FOR-PIMP-03_B_01'].includes(formulario?.id)
                || evento.target.name !== 'Datos_Equipo[ESPECIFICACION_MATERIAL]') return;
            if (!descripcionEditada && !contenedor.classList.contains('d-none')) {
                descripcion.value = construirDescripcion();
            } else if (descripcion.value.trim()) {
                descripcion.value = descripcion.value.replace(
                    /^Especificación aproximada de la pieza:.*$/m,
                    () => 'Especificación aproximada de la pieza: ' + obtenerDatoEquipo('ESPECIFICACION_MATERIAL', '---')
                );
            }
        };
        formulario?.addEventListener('change', sincronizarEspecificacion);
        formulario?.addEventListener('input', sincronizarEspecificacion);

        const activoInicial = !contenedor.classList.contains('d-none');
        if (formulario?.id === 'FOR-PIMP-04_02' && activoInicial) actualizarGranoEnDescripcion();
        actualizarControlesDistribucion(activoInicial);
        if (activoInicial) mostrarImagen();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-analysis-report-photos]').forEach(iniciar);
        const form = document.getElementById('FOR-PIMP-03_B_01');
        const bloque = form?.querySelector('[data-datos-metalograficos]');
        const especificacion = bloque?.querySelector('[name="Datos_Equipo[ESPECIFICACION_MATERIAL]"]');
        if (!especificacion || !bloque.dataset.normasUrl) return;
        // Reutiliza el catalogo de los otros formatos; guarda el texto en el campo existente.
        const panel = document.createElement('details');
        panel.open = true;
        panel.className = 'normas-material-0301 my-3';
        panel.innerHTML = '<summary class="normas-material-cabecera"><span class="normas-material-icono" aria-hidden="true">≡</span><div><strong>Normas de referencia</strong>' +
            '<p data-normas-vista>Seleccionar normas del material</p></div><span data-normas-total>0 seleccionadas</span><span class="normas-material-flecha" aria-hidden="true">⌄</span></summary>' +
            '<div class="normas-material-contenido"><input type="search" class="form-control" placeholder="Buscar por norma o grado…" aria-label="Buscar norma o grado">' +
            '<div class="normas-material-seleccion" data-normas-resumen aria-live="polite"></div>' +
            '<div class="normas-material-lista" data-normas-lista></div>' +
            '<small role="status" class="text-muted d-block mt-2">Cargando normas…</small></div>';
        const estilosNormas = document.createElement('style');
        estilosNormas.textContent = `
            .normas-material-0301{border:1px solid #ccdbee;border-left:4px solid #1769dc;border-radius:9px;background:white;overflow:hidden;text-align:left;box-shadow:0 2px 5px rgba(25,60,100,.04)}
            .normas-material-cabecera{display:flex;align-items:center;justify-content:space-between;gap:14px;background:#fff;padding:16px 18px;cursor:pointer;list-style:none}
            .normas-material-cabecera::-webkit-details-marker{display:none}
            .normas-material-cabecera:hover{background:#eaf2ff}
            .normas-material-cabecera:focus-visible{outline:2px solid #2563eb;outline-offset:-3px}
            .normas-material-cabecera>div{flex:1;min-width:0}
            .normas-material-0301[open]>.normas-material-cabecera{border-bottom:1px solid #e1e8f2}
            .normas-material-cabecera .normas-material-flecha{background:transparent;border:0;padding:0;font-size:22px;line-height:1;transition:transform .15s}
            .normas-material-0301[open] .normas-material-flecha{transform:rotate(180deg)}
            .normas-material-cabecera strong{font-size:16px;color:#173b68;line-height:1.4}
            .normas-material-cabecera p{margin:5px 0 0;color:#526b85;font-size:13px;line-height:1.6;overflow-wrap:anywhere}
            .normas-material-cabecera>span{background:#e2edff;color:#1856a1;border-radius:20px;padding:6px 10px;font-size:12px;white-space:nowrap}
            .normas-material-cabecera .normas-material-icono{display:flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:9px;padding:0;flex-shrink:0;background:#edf4ff;color:#1769dc;font-size:26px;font-weight:bold}
            .normas-material-contenido{padding:16px 18px}
            .normas-material-contenido>input{background:#f8fafc;border-radius:7px;margin-bottom:12px}
            .normas-material-lista{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;max-height:240px;overflow:auto;padding:2px 6px 2px 2px}
            .normas-material-opcion{display:flex;align-items:center;gap:10px;min-height:44px;margin:0;padding:10px 12px;border:1px solid #dce4ee;border-radius:7px;cursor:pointer;color:#34495e;font-size:13px;line-height:1.4}
            .normas-material-opcion:hover{background:#f4f8ff;border-color:#9ebde9}
            .normas-material-opcion.is-selected{background:#edf4ff;border-color:#4a89e8;color:#164d94}
            .normas-material-opcion:focus-within{outline:2px solid #2563eb;outline-offset:1px}
            .normas-material-opcion input{width:16px;height:16px;flex-shrink:0;accent-color:#1769dc}
            .normas-material-seleccion{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:12px;font-size:12px;color:#697d90}
            .normas-material-seleccion>span{background:#edf4ff;border:1px solid #cfdef5;border-radius:5px;padding:5px 9px;color:#174f95}
            @media(max-width:600px){.normas-material-lista{grid-template-columns:1fr}.normas-material-cabecera{gap:8px;padding:12px}.normas-material-cabecera .normas-material-icono{display:none}.normas-material-cabecera strong{font-size:14px}.normas-material-cabecera>[data-normas-total]{font-size:11px;padding:5px 7px}}
        `;
        document.head.appendChild(estilosNormas);
        bloque.querySelector('.table-responsive').before(panel);
        const lista = panel.querySelector('[data-normas-lista]');
        const estado = panel.querySelector('[role="status"]');
        const seleccion = new Set();
        function actualizarResumenNormas() {
            const resumen = panel.querySelector('[data-normas-resumen]');
            resumen.replaceChildren();
            panel.querySelector('[data-normas-total]').textContent = seleccion.size + (seleccion.size === 1 ? ' seleccionada' : ' seleccionadas');
            panel.querySelector('[data-normas-vista]').textContent = seleccion.size ? [...seleccion].join(' · ') : 'Seleccionar normas del material';
            if (!seleccion.size) resumen.textContent = 'Ninguna norma seleccionada';
            seleccion.forEach(nombre => {
                const etiqueta = document.createElement('span'); etiqueta.textContent = nombre; resumen.appendChild(etiqueta);
            });
        }
        actualizarResumenNormas();
        panel.querySelector('input[type="search"]').addEventListener('input', function () {
            const busqueda = this.value.toLocaleLowerCase('es');
            lista.querySelectorAll('label').forEach(label => { label.classList.toggle('d-none', !label.textContent.toLocaleLowerCase('es').includes(busqueda)); });
            const visibles = [...lista.querySelectorAll('label')].filter(label => !label.classList.contains('d-none')).length;
            estado.textContent = visibles ? 'Puedes seleccionar varias normas.' : 'No se encontraron normas con esa búsqueda.';
        });
        fetch(bloque.dataset.normasUrl, {
            method: 'POST', headers: {'Content-Type': 'application/json', Accept: 'application/json',
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value}, body: '{}',
        }).then(response => {
            if (!response.ok) throw new Error('No se pudieron cargar las normas. Recarga la página para intentarlo de nuevo.');
            return response.json();
        }).then(datos => {
            const normas = [...new Set((datos.coincidencias || []).map(fila => String(fila.norma || '').replace(/\s*\/\s*/g, ' ').trim()).filter(Boolean))];
            normas.forEach(nombre => {
                const label = document.createElement('label');
                label.className = 'normas-material-opcion';
                const check = document.createElement('input'); check.type = 'checkbox';
                check.checked = especificacion.value.split(' / ').includes(nombre);
                if (check.checked) seleccion.add(nombre);
                label.classList.toggle('is-selected', check.checked);
                const texto = document.createElement('span'); texto.textContent = nombre;
                label.append(check, texto); lista.appendChild(label);
                check.addEventListener('change', function () {
                    if (check.checked) seleccion.add(nombre); else seleccion.delete(nombre);
                    label.classList.toggle('is-selected', check.checked);
                    actualizarResumenNormas();
                    especificacion.value = [...seleccion].join(' / ');
                    especificacion.dispatchEvent(new Event('change', {bubbles: true}));
                });
            });
            actualizarResumenNormas();
            estado.textContent = normas.length ? '' : 'No hay normas disponibles en el catálogo.';
        }).catch(error => { estado.textContent = error.message; });
    });
})();
