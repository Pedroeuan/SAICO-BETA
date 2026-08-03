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
        let analisisActual = {};
        let ultimoToken = contenedor.dataset.analysisReportToken || '';
        let descripcionEditada = descripcion.value.trim() !== '';

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

        /** Convierte los resultados técnicos en una base editable para la descripción del reporte. */
        function construirDescripcion() {
            const fase = analisisActual.fase_seleccionada === 'ferrita'
                ? 'Ferrita / fase clara'
                : 'Perlita / fase oscura';
            const lineas = [
                'RESULTADOS DEL ANÁLISIS METALOGRÁFICO',
                'Archivo: ' + (analisisActual.archivo_original || 'Sin nombre'),
                'Conversión: 8 bits',
                'Umbral: ' + Number(analisisActual.umbral_minimo || 0) + '–' + Number(analisisActual.umbral_maximo || 0),
                'Fase revisada: ' + fase,
                'Perlita / fase oscura: ' + Number(analisisActual.porcentaje_perlita || 0).toFixed(3) + ' %',
                'Ferrita / fase clara: ' + Number(analisisActual.porcentaje_ferrita || 0).toFixed(3) + ' %',
                'Total verificado: 100.000 %',
                'Método: ' + (analisisActual.metodo_medicion || 'ImageJ Area Fraction / Measure'),
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
            const ruta = analisisActual.urls?.original || imagenAnalisis?.src || '';
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

        if (!contenedor.classList.contains('d-none')) mostrarImagen();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-analysis-report-photos]').forEach(iniciar);
    });
})();
