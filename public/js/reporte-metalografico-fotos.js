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
        if (['FOR-PIMP-04_02', 'FOR-PIMP-04_03'].includes(formulario?.id)) {
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

        // Los catálogos y el patrón comparativo alimentan el texto mientras el técnico no lo haya editado.
        document.addEventListener('saico:grain-pattern-updated', function () {
            if (!contenedor.classList.contains('d-none') && !descripcionEditada) {
                descripcion.value = construirDescripcion();
            }
        });
        document.addEventListener('input', function (evento) {
            if (evento.target.matches('[name="Datos_Equipo[FASES_PRESENTES]"]')
                && !contenedor.classList.contains('d-none') && !descripcionEditada) {
                descripcion.value = construirDescripcion();
            }
        });

        const activoInicial = !contenedor.classList.contains('d-none');
        actualizarControlesDistribucion(activoInicial);
        if (activoInicial) mostrarImagen();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-analysis-report-photos]').forEach(iniciar);
    });
})();
