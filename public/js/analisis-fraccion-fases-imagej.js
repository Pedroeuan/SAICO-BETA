(function () {
    'use strict';

    /**
     * Componente reutilizable de fracción de fases.
     * La vista previa se dibuja en el navegador, pero el histograma y la medición oficial provienen de Fiji.
     */

    // Mantiene cualquier valor de umbral dentro del rango válido de una imagen de 8 bits.
    function limitar(valor) {
        return Math.max(0, Math.min(255, Number.parseInt(valor, 10) || 0));
    }

    function iniciar(componente) {
        // Referencias de los controles y áreas visuales pertenecientes a esta instancia del componente.
        const archivo = componente.querySelector('[data-imagej-file]');
        const minimo = componente.querySelector('[data-imagej-min]');
        const maximo = componente.querySelector('[data-imagej-max]');
        const minimoRango = componente.querySelector('[data-imagej-min-range]');
        const maximoRango = componente.querySelector('[data-imagej-max-range]');
        const tipoFase = componente.querySelector('[data-imagej-phase-type]');
        const modoVista = componente.querySelector('[data-imagej-preview-mode]');
        const lienzo = componente.querySelector('[data-imagej-preview]');
        const contenedorLienzo = componente.querySelector('[data-imagej-preview-wrap]');
        const lienzoHistograma = componente.querySelector('[data-imagej-histogram]');
        const contenedorHistograma = componente.querySelector('[data-imagej-histogram-wrap]');
        const porcentajeVivo = componente.querySelector('[data-imagej-live-percent]');
        const boton = componente.querySelector('[data-imagej-process]');
        const botonRestablecer = componente.querySelector('[data-imagej-reset]');
        const estado = componente.querySelector('[data-imagej-status]');
        const token = componente.querySelector('[data-imagej-token]');
        const usarReporte = componente.querySelector('[data-imagej-use-report]');
        const botonUsarReporte = componente.querySelector('[data-imagej-use-report-button]');
        const estadoUsarReporte = componente.querySelector('[data-imagej-use-report-status]');
        const analisisExistente = componente.querySelector('[data-imagej-existing]');
        const resultados = componente.querySelector('[data-imagej-results]');
        const perlita = componente.querySelector('[data-imagej-perlite]');
        const ferrita = componente.querySelector('[data-imagej-ferrite]');
        const original = componente.querySelector('[data-imagej-original]');
        const binaria = componente.querySelector('[data-imagej-binary]');
        const originalWrap = componente.querySelector('[data-imagej-original-wrap]');
        const binariaWrap = componente.querySelector('[data-imagej-binary-wrap]');
        // Canvas oculto que conserva una copia escalada de la imagen para previsualización rápida.
        const fuente = document.createElement('canvas');

        // Estado local: archivo activo, histograma exacto y validez del último resultado guardable.
        let imagenSeleccionada = null;
        let histogramaImageJ = null;
        let resultadoVigente = Boolean(token.value);
        let analisisActual = {};

        // Recupera los metadatos ya guardados para que Edit muestre el mismo contenido en FOTOS.
        try {
            analisisActual = JSON.parse(analisisExistente?.textContent || '{}');
        } catch (error) {
            analisisActual = {};
        }

        /**
         * Mantiene sincronizados la bandera enviada al servidor, el botón y su explicación.
         * La elección solo apunta al análisis existente; nunca vuelve a cargar ni copia la imagen.
         */
        function mostrarSeleccionReporte(seleccionado) {
            usarReporte.value = seleccionado ? '1' : '0';
            botonUsarReporte.className = seleccionado ? 'btn btn-primary' : 'btn btn-outline-primary';
            botonUsarReporte.textContent = seleccionado
                ? 'Análisis seleccionado para el reporte'
                : 'Usar este análisis en el reporte';
            estadoUsarReporte.textContent = seleccionado
                ? 'La imagen original y todos los resultados se colocarán automáticamente en el PDF.'
                : 'El análisis está guardado, pero no se incluirá en el PDF.';
            // La sección FOTOS escucha este evento y presenta la versión editable antes de guardar.
            document.dispatchEvent(new CustomEvent('saico:image-analysis-report-selection', {
                detail: { selected: seleccionado, analysis: analisisActual },
            }));
        }

        // Impide enviar a ImageJ un intervalo invertido.
        function valoresValidos() {
            return limitar(minimo.value) <= limitar(maximo.value);
        }

        // Cualquier cambio posterior a Measure invalida el token para evitar guardar resultados obsoletos.
        function marcarPendiente() {
            if (!imagenSeleccionada) return;
            resultadoVigente = false;
            token.value = '';
            usarReporte.value = '0';
            botonUsarReporte.disabled = true;
            mostrarSeleccionReporte(false);
            estado.textContent = 'Cambios pendientes de aplicar.';
        }

        /**
         * Pinta la selección en rojo o genera la máscara B&W.
         * El umbral siempre delimita la fase oscura; Ferrita muestra el complemento claro.
         */
        function dibujarPrevisualizacion() {
            if (!imagenSeleccionada || !valoresValidos()) return;

            const contextoFuente = fuente.getContext('2d', { willReadFrequently: true });
            const contexto = lienzo.getContext('2d');
            const datos = contextoFuente.getImageData(0, 0, fuente.width, fuente.height);
            const salida = contexto.createImageData(fuente.width, fuente.height);
            const desde = limitar(minimo.value);
            const hasta = limitar(maximo.value);
            const histograma = new Array(256).fill(0);

            // Recorre RGBA de cuatro en cuatro y calcula una luminancia para cada píxel visible.
            for (let i = 0; i < datos.data.length; i += 4) {
                const gris = Math.round(
                    (datos.data[i] * 0.299) +
                    (datos.data[i + 1] * 0.587) +
                    (datos.data[i + 2] * 0.114)
                );
                const enRangoOscuro = gris >= desde && gris <= hasta;
                const seleccionado = tipoFase.value === 'ferrita' ? !enRangoOscuro : enRangoOscuro;
                histograma[gris]++;

                if (modoVista.value === 'bw') {
                    const binario = enRangoOscuro ? 0 : 255;
                    salida.data[i] = binario;
                    salida.data[i + 1] = binario;
                    salida.data[i + 2] = binario;
                } else {
                    salida.data[i] = seleccionado ? 255 : gris;
                    salida.data[i + 1] = seleccionado ? 0 : gris;
                    salida.data[i + 2] = seleccionado ? 0 : gris;
                }
                salida.data[i + 3] = 255;
            }

            contexto.putImageData(salida, 0, 0);
            // Fiji tiene prioridad; el histograma del navegador solo sirve mientras llega la respuesta exacta.
            const histogramaMedicion = histogramaImageJ || histograma;
            const totalMedicion = histogramaMedicion.reduce(function (total, cantidad) { return total + cantidad; }, 0);
            const seleccionMedicion = histogramaMedicion.reduce(function (total, cantidad, nivel) {
                const enRangoOscuro = nivel >= desde && nivel <= hasta;
                const seleccionado = tipoFase.value === 'ferrita' ? !enRangoOscuro : enRangoOscuro;
                return total + (seleccionado ? cantidad : 0);
            }, 0);
            porcentajeVivo.textContent = ((seleccionMedicion * 100) / Math.max(1, totalMedicion)).toFixed(3) + ' %';
            dibujarHistograma(histogramaMedicion, desde, hasta, tipoFase.value === 'ferrita');
        }

        /** Envía la imagen a Fiji para obtener las mismas 256 frecuencias que muestra ImageJ. */
        async function cargarHistogramaImageJ(seleccionado) {
            histogramaImageJ = null;
            estado.classList.remove('text-danger', 'text-success');
            estado.textContent = 'Fiji está preparando el histograma de 8 bits…';
            const datos = new FormData();
            datos.append('imagen', seleccionado);
            const csrf = componente.closest('form')?.querySelector('input[name="_token"]')?.value;

            try {
                const respuesta = await fetch(componente.dataset.histogramUrl, {
                    method: 'POST',
                    headers: csrf ? { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } : { 'Accept': 'application/json' },
                    body: datos,
                });
                const cuerpo = await respuesta.json();
                if (!respuesta.ok || !cuerpo.ok || !Array.isArray(cuerpo.imagen?.histograma)) {
                    throw new Error(cuerpo.message || 'No se pudo obtener el histograma de Fiji.');
                }
                histogramaImageJ = cuerpo.imagen.histograma.map(Number);
                dibujarPrevisualizacion();
                estado.classList.add('text-success');
                estado.textContent = 'Histograma exacto de Fiji listo. Ajuste el umbral.';
            } catch (error) {
                estado.classList.add('text-danger');
                estado.textContent = error.message;
            }
        }

        // Dibuja en rojo el rango oscuro o su complemento, según la fase elegida por el técnico.
        function dibujarHistograma(histograma, desde, hasta, resaltarComplemento) {
            const contexto = lienzoHistograma.getContext('2d');
            const ancho = lienzoHistograma.width;
            const alto = lienzoHistograma.height;
            const maximoConteo = Math.max(...histograma, 1);
            contexto.clearRect(0, 0, ancho, alto);
            contexto.fillStyle = '#ffffff';
            contexto.fillRect(0, 0, ancho, alto);

            for (let nivel = 0; nivel < 256; nivel++) {
                const alturaBarra = Math.max(1, (histograma[nivel] / maximoConteo) * (alto - 8));
                const x = (nivel / 256) * ancho;
                const anchoBarra = Math.ceil(ancho / 256);
                const enRangoOscuro = nivel >= desde && nivel <= hasta;
                const resaltado = resaltarComplemento ? !enRangoOscuro : enRangoOscuro;
                contexto.fillStyle = resaltado ? '#dc3545' : '#6c757d';
                contexto.fillRect(x, alto - alturaBarra, anchoBarra, alturaBarra);
            }

            contexto.strokeStyle = '#212529';
            contexto.strokeRect(0.5, 0.5, ancho - 1, alto - 1);
        }

        // Mantiene sincronizados slider y campo numérico para cada límite.
        function sincronizar(origen, destino) {
            destino.value = limitar(origen.value);
            origen.value = limitar(origen.value);
            marcarPendiente();
            dibujarPrevisualizacion();
            boton.disabled = !imagenSeleccionada || !valoresValidos();
            estado.classList.toggle('text-danger', !valoresValidos());
            if (!valoresValidos()) estado.textContent = 'El umbral mínimo debe ser menor o igual que el máximo.';
        }

        // Carga local de la micrografía: valida tamaño, escala la vista y solicita el histograma a Fiji.
        archivo.addEventListener('change', function () {
            const seleccionado = archivo.files && archivo.files[0];
            if (!seleccionado) {
                imagenSeleccionada = null;
                histogramaImageJ = null;
                boton.disabled = true;
                contenedorLienzo.classList.add('d-none');
                contenedorHistograma.classList.add('d-none');
                return;
            }

            if (seleccionado.size > 25 * 1024 * 1024) {
                archivo.value = '';
                estado.textContent = 'La imagen supera el límite de 25 MB.';
                estado.classList.add('text-danger');
                return;
            }

            const url = URL.createObjectURL(seleccionado);
            const imagen = new Image();
            imagen.onload = function () {
                // La escala solo afecta la vista; Fiji procesa siempre el archivo original completo.
                const escala = Math.min(1, 1000 / imagen.naturalWidth, 800 / imagen.naturalHeight);
                fuente.width = Math.max(1, Math.round(imagen.naturalWidth * escala));
                fuente.height = Math.max(1, Math.round(imagen.naturalHeight * escala));
                lienzo.width = fuente.width;
                lienzo.height = fuente.height;
                fuente.getContext('2d').drawImage(imagen, 0, 0, fuente.width, fuente.height);
                imagenSeleccionada = seleccionado;
                resultadoVigente = false;
                token.value = '';
                botonUsarReporte.disabled = true;
                mostrarSeleccionReporte(false);
                contenedorLienzo.classList.remove('d-none');
                contenedorHistograma.classList.remove('d-none');
                estado.classList.remove('text-danger');
                estado.textContent = 'Ajuste el umbral y revise la selección roja.';
                boton.disabled = !valoresValidos();
                dibujarPrevisualizacion();
                cargarHistogramaImageJ(seleccionado);
                // Comparte el mismo File con el contador lineal para evitar una segunda selección de imagen.
                document.dispatchEvent(new CustomEvent('saico:image-analysis-loaded', { detail: { file: seleccionado } }));
                URL.revokeObjectURL(url);
            };
            imagen.onerror = function () {
                URL.revokeObjectURL(url);
                estado.textContent = 'No se pudo leer la imagen seleccionada.';
                estado.classList.add('text-danger');
            };
            imagen.src = url;
        });

        minimo.addEventListener('input', function () { sincronizar(minimo, minimoRango); });
        maximo.addEventListener('input', function () { sincronizar(maximo, maximoRango); });
        minimoRango.addEventListener('input', function () { sincronizar(minimoRango, minimo); });
        maximoRango.addEventListener('input', function () { sincronizar(maximoRango, maximo); });
        // Al cambiar de fase se fuerza el modo rojo para hacer visible la inversión oscuro/claro.
        tipoFase.addEventListener('change', function () {
            marcarPendiente();
            modoVista.value = 'red';
            dibujarPrevisualizacion();
        });
        modoVista.addEventListener('change', dibujarPrevisualizacion);
        // Recupera el intervalo inicial acordado durante las pruebas con el técnico.
        botonRestablecer.addEventListener('click', function () {
            minimo.value = minimoRango.value = 0;
            maximo.value = maximoRango.value = 85;
            marcarPendiente();
            dibujarPrevisualizacion();
            boton.disabled = !imagenSeleccionada;
        });

        /**
         * Procesamiento definitivo: envía original y parámetros; Fiji ejecuta Apply y Measure.
         * El token recibido será el vínculo seguro entre las evidencias y el reporte.
         */
        boton.addEventListener('click', async function () {
            if (!imagenSeleccionada || !valoresValidos()) return;

            boton.disabled = true;
            estado.classList.remove('text-danger', 'text-success');
            estado.textContent = 'Fiji está convirtiendo y midiendo la imagen…';

            const datos = new FormData();
            datos.append('imagen', imagenSeleccionada);
            datos.append('umbral_minimo', limitar(minimo.value));
            datos.append('umbral_maximo', limitar(maximo.value));
            datos.append('fase_seleccionada', tipoFase.value);
            const csrf = componente.closest('form')?.querySelector('input[name="_token"]')?.value;

            try {
                const respuesta = await fetch(componente.dataset.processUrl, {
                    method: 'POST',
                    headers: csrf ? { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } : { 'Accept': 'application/json' },
                    body: datos,
                });
                const cuerpo = await respuesta.json();
                if (!respuesta.ok || !cuerpo.ok) {
                    const primerError = cuerpo.errors ? Object.values(cuerpo.errors).flat()[0] : null;
                    throw new Error(primerError || cuerpo.message || 'No fue posible completar el análisis.');
                }

                const analisis = cuerpo.analisis;
                analisisActual = analisis;
                token.value = analisis.token;
                perlita.textContent = Number(analisis.porcentaje_perlita).toFixed(3) + ' %';
                ferrita.textContent = Number(analisis.porcentaje_ferrita).toFixed(3) + ' %';
                original.src = analisis.urls.original;
                binaria.src = analisis.urls.imagen_binaria;
                originalWrap.classList.remove('d-none');
                binariaWrap.classList.remove('d-none');
                resultados.classList.remove('d-none');
                resultadoVigente = true;
                // Un resultado nuevo requiere confirmación explícita antes de ocupar el PDF final.
                botonUsarReporte.disabled = false;
                mostrarSeleccionReporte(false);
                estado.classList.add('text-success');
                estado.textContent = 'Análisis completado y listo para guardarse con el reporte.';
            } catch (error) {
                resultadoVigente = false;
                token.value = '';
                estado.classList.add('text-danger');
                estado.textContent = error.message;
            } finally {
                boton.disabled = false;
            }
        });

        // Permite seleccionar o retirar el análisis actual sin repetir el procesamiento en Fiji.
        botonUsarReporte.addEventListener('click', function () {
            if (!resultadoVigente || !token.value) return;
            mostrarSeleccionReporte(usarReporte.value !== '1');
        });

        // Sincroniza el estado inicial de Edit después de que todos los scripts registraron sus listeners.
        if (resultadoVigente) {
            setTimeout(function () {
                mostrarSeleccionReporte(usarReporte.value === '1');
            }, 0);
        }

        // Evita guardar un reporte si el usuario cambió imagen/umbral y no volvió a medir.
        const formulario = componente.closest('form');
        formulario?.addEventListener('submit', function (evento) {
            if (imagenSeleccionada && !resultadoVigente) {
                evento.preventDefault();
                estado.classList.add('text-danger');
                estado.textContent = 'Debe pulsar “Aplicar y medir con Fiji” antes de guardar el reporte.';
                componente.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    // Inicializa todas las instancias para permitir reutilizar el parcial en otros formatos.
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-imagej-phase]').forEach(iniciar);
    });
})();
