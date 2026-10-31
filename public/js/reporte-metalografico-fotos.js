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
                if (formulario.id === 'FOR-PIMP-04_02') {
                    selector.options[0].disabled = false;
                    selector.options[0].textContent = 'Sin patrón en el PDF';
                    comparacion.querySelector('.card-header strong').textContent = 'Patrón de tamaño de grano — Comparativa y ubicación en el PDF';
                }
                const configuracion = formulario.querySelector('[data-grain-pattern-config]');
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
                    // En 04/02 la comparativa y su distribucion son la misma tarjeta, sin copiar campos.
                    const unificar = formulario.id === 'FOR-PIMP-04_02' && tarjeta?.hasAttribute('data-grain-history-card');
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
                    vacio.classList.toggle('d-none', Boolean(ruta));
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
            if (formulario?.id !== 'FOR-PIMP-04_02'
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
    });
})();
