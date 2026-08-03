(function () {
    'use strict';

    /**
     * Contador lineal semiautomático de granos.
     * Permite múltiples líneas sin intersecciones, propone cruces oscuros y deja al técnico corregirlos.
     */
    function iniciar(componente) {
        // Elementos de dibujo, controles, tabla de resultados y campos de persistencia.
        const canvas = componente.querySelector('[data-grain-canvas]');
        const contexto = canvas.getContext('2d');
        const envoltura = componente.querySelector('[data-grain-canvas-wrap]');
        const vacio = componente.querySelector('[data-grain-empty]');
        const estado = componente.querySelector('[data-grain-status]');
        const tabla = componente.querySelector('[data-grain-table]');
        const entradaJson = componente.querySelector('[data-grain-json]');
        const datosExistentes = componente.querySelector('[data-grain-existing]');
        const botonDibujar = componente.querySelector('[data-grain-draw]');
        const botonMarcadores = componente.querySelector('[data-grain-markers]');
        const botonSugerir = componente.querySelector('[data-grain-suggest]');
        const botonEliminar = componente.querySelector('[data-grain-delete]');
        const botonLimpiar = componente.querySelector('[data-grain-clear]');
        const sensibilidad = componente.querySelector('[data-grain-sensitivity]');
        const sensibilidadValor = componente.querySelector('[data-grain-sensitivity-value]');
        const numeroLineas = componente.querySelector('[data-grain-line-count]');
        const sumaGranos = componente.querySelector('[data-grain-total]');
        const promedioGranos = componente.querySelector('[data-grain-average]');
        // Canvas oculto usado como imagen base; el canvas visible agrega líneas y marcadores encima.
        const fuente = document.createElement('canvas');
        const contextoFuente = fuente.getContext('2d', { willReadFrequently: true });

        // Estado completo del editor. Las líneas usan píxeles mientras se editan y se normalizan al guardar.
        let imagen = null;
        let pixeles = null;
        let lineas = [];
        let seleccionada = null;
        let modo = 'draw';
        let inicioTemporal = null;
        let cursorTemporal = null;
        let siguienteId = 1;
        let pendientesExistentes = [];

        // En Edit se restauran líneas guardadas; tras una validación fallida se prioriza el old input.
        try {
            const guardado = entradaJson.value ? JSON.parse(entradaJson.value) : JSON.parse(datosExistentes.textContent || '{}');
            pendientesExistentes = Array.isArray(guardado?.lineas) ? guardado.lineas : [];
        } catch (error) {
            pendientesExistentes = [];
        }

        // Presenta instrucciones o errores sin usar ventanas emergentes.
        function mensaje(texto, tipo) {
            estado.className = 'mt-2 alert py-2 alert-' + (tipo || 'secondary');
            estado.textContent = texto;
        }

        /** Carga la micrografía compartida y reconstruye coordenadas normalizadas de líneas anteriores. */
        function cargarImagen(origen) {
            const nuevaImagen = new Image();
            nuevaImagen.onload = function () {
                // La escala reduce el costo de dibujo sin perder la proporción de las coordenadas.
                const escala = Math.min(1, 1100 / nuevaImagen.naturalWidth, 850 / nuevaImagen.naturalHeight);
                canvas.width = fuente.width = Math.max(1, Math.round(nuevaImagen.naturalWidth * escala));
                canvas.height = fuente.height = Math.max(1, Math.round(nuevaImagen.naturalHeight * escala));
                contextoFuente.drawImage(nuevaImagen, 0, 0, fuente.width, fuente.height);
                pixeles = contextoFuente.getImageData(0, 0, fuente.width, fuente.height);
                imagen = nuevaImagen;
                lineas = pendientesExistentes.map(function (linea, indice) {
                    const id = Number(linea.id) || indice + 1;
                    siguienteId = Math.max(siguienteId, id + 1);
                    return {
                        id: id,
                        x1: Number(linea.x1) * fuente.width,
                        y1: Number(linea.y1) * fuente.height,
                        x2: Number(linea.x2) * fuente.width,
                        y2: Number(linea.y2) * fuente.height,
                        marcadores: Array.isArray(linea.marcadores) ? linea.marcadores.map(Number).filter(function (t) { return t > 0 && t < 1; }) : [],
                    };
                });
                pendientesExistentes = [];
                vacio.classList.add('d-none');
                envoltura.classList.remove('d-none');
                mensaje('Micrografía lista. Dibuje la primera línea sin cruzar otras líneas.', 'success');
                actualizarTodo();
            };
            nuevaImagen.onerror = function () { mensaje('No se pudo cargar la micrografía para el conteo.', 'danger'); };
            nuevaImagen.src = origen;
        }

        // Recibe el mismo archivo que el usuario cargó en Fracción de Fases.
        document.addEventListener('saico:image-analysis-loaded', function (evento) {
            if (!evento.detail?.file) return;
            const url = URL.createObjectURL(evento.detail.file);
            cargarImagen(url);
            setTimeout(function () { URL.revokeObjectURL(url); }, 5000);
        });

        // En Edit, si no hay File nuevo, se usa la ruta de la evidencia original ya almacenada.
        const originalGuardada = document.querySelector('[data-imagej-original]');
        const rutaOriginalGuardada = originalGuardada?.getAttribute('src');
        if (rutaOriginalGuardada) cargarImagen(rutaOriginalGuardada);

        // Convierte coordenadas CSS del puntero a coordenadas reales del canvas.
        function coordenada(evento) {
            const rect = canvas.getBoundingClientRect();
            return {
                x: (evento.clientX - rect.left) * canvas.width / rect.width,
                y: (evento.clientY - rect.top) * canvas.height / rect.height,
            };
        }

        /** Proyecta un clic sobre una línea; se usa para seleccionarla y editar cruces. */
        function distanciaPuntoSegmento(punto, linea) {
            const dx = linea.x2 - linea.x1;
            const dy = linea.y2 - linea.y1;
            const largo2 = dx * dx + dy * dy;
            const t = largo2 ? Math.max(0, Math.min(1, ((punto.x - linea.x1) * dx + (punto.y - linea.y1) * dy) / largo2)) : 0;
            const x = linea.x1 + t * dx;
            const y = linea.y1 + t * dy;
            return { distancia: Math.hypot(punto.x - x, punto.y - y), t: t, x: x, y: y };
        }

        // Funciones geométricas utilizadas por la prueba robusta de intersección de segmentos.
        function orientacion(a, b, c) {
            const valor = (b.y - a.y) * (c.x - b.x) - (b.x - a.x) * (c.y - b.y);
            return Math.abs(valor) < 0.001 ? 0 : (valor > 0 ? 1 : 2);
        }

        function sobreSegmento(a, b, c) {
            return b.x <= Math.max(a.x, c.x) + 0.001 && b.x >= Math.min(a.x, c.x) - 0.001 &&
                b.y <= Math.max(a.y, c.y) + 0.001 && b.y >= Math.min(a.y, c.y) - 0.001;
        }

        /** Rechaza cruces, contactos y extremos compartidos entre dos líneas de medición. */
        function intersectan(primera, segunda) {
            const p1 = { x: primera.x1, y: primera.y1 }, q1 = { x: primera.x2, y: primera.y2 };
            const p2 = { x: segunda.x1, y: segunda.y1 }, q2 = { x: segunda.x2, y: segunda.y2 };
            const o1 = orientacion(p1, q1, p2), o2 = orientacion(p1, q1, q2);
            const o3 = orientacion(p2, q2, p1), o4 = orientacion(p2, q2, q1);
            if (o1 !== o2 && o3 !== o4) return true;
            return (o1 === 0 && sobreSegmento(p1, p2, q1)) || (o2 === 0 && sobreSegmento(p1, q2, q1)) ||
                (o3 === 0 && sobreSegmento(p2, p1, q2)) || (o4 === 0 && sobreSegmento(p2, q1, q2));
        }

        // Devuelve la línea más cercana dentro de una tolerancia cómoda para mouse o pantalla táctil.
        function lineaCercana(punto) {
            let mejor = null;
            lineas.forEach(function (linea) {
                const proyeccion = distanciaPuntoSegmento(punto, linea);
                if (proyeccion.distancia <= 14 && (!mejor || proyeccion.distancia < mejor.proyeccion.distancia)) {
                    mejor = { linea: linea, proyeccion: proyeccion };
                }
            });
            return mejor;
        }

        /**
         * Regla de conteo confirmada:
         * N cruces producen N-1 granos completos más dos extremos de 0.5.
         */
        function conteo(linea) {
            const cruces = linea.marcadores.length;
            const completos = Math.max(0, cruces - 1);
            return { cruces: cruces, completos: completos, extremos: 1, total: completos + 1 };
        }

        /** Serializa coordenadas 0-1 para que el conteo sobreviva a cambios de resolución. */
        function persistir() {
            const suma = lineas.reduce(function (total, linea) { return total + conteo(linea).total; }, 0);
            // El resumen del cliente es informativo; el servidor lo vuelve a calcular antes de guardar.
            entradaJson.value = JSON.stringify({
                version: 1,
                regla: 'extremos_0.5_completos_1',
                resumen: {
                    numero_lineas: lineas.length,
                    suma: suma,
                    promedio: lineas.length ? suma / lineas.length : 0,
                },
                lineas: lineas.map(function (linea) {
                    const resultado = conteo(linea);
                    return {
                        id: linea.id,
                        x1: linea.x1 / fuente.width,
                        y1: linea.y1 / fuente.height,
                        x2: linea.x2 / fuente.width,
                        y2: linea.y2 / fuente.height,
                        marcadores: linea.marcadores.slice().sort(function (a, b) { return a - b; }),
                        cruces: resultado.cruces,
                        granos_completos: resultado.completos,
                        extremos_parciales: resultado.extremos,
                        conteo: resultado.total,
                    };
                }),
            });
            // Informa al cuadro editable de FOTOS para mantener sus datos sincronizados.
            document.dispatchEvent(new CustomEvent('saico:grain-count-updated'));
        }

        /** Redibuja imagen, líneas, marcadores rojos y etiquetas amarillas de alto contraste. */
        function dibujar() {
            if (!imagen) return;
            contexto.clearRect(0, 0, canvas.width, canvas.height);
            contexto.drawImage(fuente, 0, 0);
            lineas.forEach(function (linea) {
                const activa = linea.id === seleccionada;
                contexto.lineWidth = activa ? 4 : 3;
                contexto.strokeStyle = activa ? '#ffc107' : '#00b7ff';
                contexto.beginPath();
                contexto.moveTo(linea.x1, linea.y1);
                contexto.lineTo(linea.x2, linea.y2);
                contexto.stroke();

                // t representa la posición proporcional del cruce sobre la línea (0=inicio, 1=final).
                linea.marcadores.forEach(function (t, indice) {
                    const x = linea.x1 + (linea.x2 - linea.x1) * t;
                    const y = linea.y1 + (linea.y2 - linea.y1) * t;
                    contexto.fillStyle = '#dc3545';
                    contexto.beginPath();
                    contexto.arc(x, y, 5, 0, Math.PI * 2);
                    contexto.fill();
                    const etiqueta = String(indice + 1);
                    contexto.font = 'bold 10px sans-serif';
                    const anchoEtiqueta = contexto.measureText(etiqueta).width + 6;
                    // Fondo amarillo y texto negro mantienen el número visible sobre cualquier micrografía.
                    contexto.fillStyle = '#ffc107';
                    contexto.fillRect(x + 6, y - 19, anchoEtiqueta, 14);
                    contexto.strokeStyle = '#212529';
                    contexto.lineWidth = 1;
                    contexto.strokeRect(x + 6, y - 19, anchoEtiqueta, 14);
                    contexto.fillStyle = '#000000';
                    contexto.fillText(etiqueta, x + 9, y - 8);
                });

                contexto.fillStyle = activa ? '#212529' : '#004b6b';
                contexto.font = 'bold 14px sans-serif';
                contexto.fillText('L' + linea.id, linea.x1 + 5, linea.y1 - 7);
            });

            // Línea verde discontinua mientras el usuario mantiene presionado y arrastra.
            if (inicioTemporal && cursorTemporal) {
                contexto.setLineDash([8, 6]);
                contexto.strokeStyle = '#28a745';
                contexto.lineWidth = 3;
                contexto.beginPath();
                contexto.moveTo(inicioTemporal.x, inicioTemporal.y);
                contexto.lineTo(cursorTemporal.x, cursorTemporal.y);
                contexto.stroke();
                contexto.setLineDash([]);
            }
        }

        /** Actualiza resultados individuales, suma y promedio después de cada cambio. */
        function actualizarTabla() {
            tabla.innerHTML = '';
            const suma = lineas.reduce(function (total, linea) { return total + conteo(linea).total; }, 0);
            numeroLineas.textContent = String(lineas.length);
            sumaGranos.textContent = suma.toFixed(1);
            promedioGranos.textContent = (lineas.length ? suma / lineas.length : 0).toFixed(3);
            if (!lineas.length) {
                tabla.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Todavía no hay líneas.</td></tr>';
                return;
            }
            lineas.forEach(function (linea) {
                const resultado = conteo(linea);
                const fila = document.createElement('tr');
                if (linea.id === seleccionada) fila.classList.add('table-warning');
                fila.innerHTML = '<th>Línea ' + linea.id + '</th><td>' + resultado.cruces + '</td><td>' + resultado.completos +
                    '</td><td>0.5 + 0.5</td><td><strong>' + resultado.total.toFixed(1) + '</strong></td>';
                fila.addEventListener('click', function () { seleccionada = linea.id; actualizarTodo(); });
                tabla.appendChild(fila);
            });
        }

        // Punto único de refresco para mantener canvas, tabla y JSON siempre sincronizados.
        function actualizarTodo() {
            botonSugerir.disabled = seleccionada === null;
            botonEliminar.disabled = seleccionada === null;
            dibujar();
            actualizarTabla();
            persistir();
        }

        /**
         * Recorre toda la línea píxel por píxel y agrupa corridas oscuras como límites de grano.
         * Es una sugerencia: el técnico conserva la decisión final mediante Editar cruces.
         */
        function sugerirCruces() {
            const linea = lineas.find(function (item) { return item.id === seleccionada; });
            if (!linea || !pixeles) return;
            const largo = Math.hypot(linea.x2 - linea.x1, linea.y2 - linea.y1);
            const pasos = Math.max(2, Math.ceil(largo));
            // La sensibilidad 0-255 se traduce a un umbral seguro 30-245.
            // Así, el valor máximo detecta bordes claros sin convertir el fondo blanco en un solo bloque.
            const limite = Math.round(30 + (Number(sensibilidad.value) / 255) * 215);
            const corridas = [];
            let inicioOscuro = null;

            // Se muestrea desde el primer hasta el último punto, no solo los extremos.
            for (let paso = 0; paso <= pasos; paso++) {
                const t = paso / pasos;
                const x = Math.max(0, Math.min(fuente.width - 1, Math.round(linea.x1 + (linea.x2 - linea.x1) * t)));
                const y = Math.max(0, Math.min(fuente.height - 1, Math.round(linea.y1 + (linea.y2 - linea.y1) * t)));
                const indice = (y * fuente.width + x) * 4;
                const gris = Math.round(pixeles.data[indice] * 0.299 + pixeles.data[indice + 1] * 0.587 + pixeles.data[indice + 2] * 0.114);
                const oscuro = gris <= limite;
                if (oscuro && inicioOscuro === null) inicioOscuro = paso;
                if ((!oscuro || paso === pasos) && inicioOscuro !== null) {
                    const fin = oscuro && paso === pasos ? paso : paso - 1;
                    if (fin - inicioOscuro + 1 >= 1) corridas.push((inicioOscuro + fin) / 2 / pasos);
                    inicioOscuro = null;
                }
            }

            // Ignora extremos y fusiona detecciones demasiado cercanas para no duplicar un borde grueso.
            linea.marcadores = corridas.filter(function (t, indice, todos) {
                if (t <= 0.02 || t >= 0.98) return false;
                return indice === 0 || (t - todos[indice - 1]) * largo >= 4;
            });
            mensaje('Se detectaron ' + linea.marcadores.length + ' cruces a lo largo de toda la línea. Revise y corrija los marcadores.', 'warning');
            actualizarTodo();
        }

        // Pointer Events permite utilizar la misma interacción con mouse, pluma o pantalla táctil.
        canvas.addEventListener('pointerdown', function (evento) {
            if (!imagen) return;
            const punto = coordenada(evento);
            if (modo === 'draw') {
                inicioTemporal = punto;
                cursorTemporal = punto;
                canvas.setPointerCapture(evento.pointerId);
                return;
            }

            const cercana = lineaCercana(punto);
            if (!cercana) {
                mensaje('Haga clic cerca de una línea para editar sus cruces.', 'warning');
                return;
            }
            seleccionada = cercana.linea.id;
            // En modo edición, un clic cercano elimina el marcador; un clic libre agrega uno.
            const existente = cercana.linea.marcadores.findIndex(function (t) {
                return Math.abs(t - cercana.proyeccion.t) * Math.hypot(cercana.linea.x2 - cercana.linea.x1, cercana.linea.y2 - cercana.linea.y1) <= 10;
            });
            if (existente >= 0) cercana.linea.marcadores.splice(existente, 1);
            else if (cercana.proyeccion.t > 0.02 && cercana.proyeccion.t < 0.98) cercana.linea.marcadores.push(cercana.proyeccion.t);
            cercana.linea.marcadores.sort(function (a, b) { return a - b; });
            actualizarTodo();
        });

        canvas.addEventListener('pointermove', function (evento) {
            if (!inicioTemporal) return;
            cursorTemporal = coordenada(evento);
            dibujar();
        });

        // Al soltar se valida longitud, se impiden colisiones y se detectan cruces automáticamente.
        canvas.addEventListener('pointerup', function (evento) {
            if (!inicioTemporal) return;
            const fin = coordenada(evento);
            const candidata = { id: siguienteId, x1: inicioTemporal.x, y1: inicioTemporal.y, x2: fin.x, y2: fin.y, marcadores: [] };
            inicioTemporal = cursorTemporal = null;
            if (Math.hypot(candidata.x2 - candidata.x1, candidata.y2 - candidata.y1) < 30) {
                const cercana = lineaCercana(fin);
                if (cercana) seleccionada = cercana.linea.id;
                actualizarTodo();
                return;
            }
            if (lineas.some(function (linea) { return intersectan(candidata, linea); })) {
                mensaje('La nueva línea cruza o toca otra línea. Cambie su posición.', 'danger');
                dibujar();
                return;
            }
            lineas.push(candidata);
            seleccionada = candidata.id;
            siguienteId++;
            sugerirCruces();
        });

        // Cambios explícitos de modo evitan que un clic de corrección cree una línea por accidente.
        botonDibujar.addEventListener('click', function () {
            modo = 'draw';
            botonDibujar.className = 'btn btn-primary';
            botonMarcadores.className = 'btn btn-outline-primary';
            mensaje('Modo dibujo activo: arrastre para crear una línea que no cruce las existentes.', 'secondary');
        });
        botonMarcadores.addEventListener('click', function () {
            modo = 'markers';
            botonDibujar.className = 'btn btn-outline-primary';
            botonMarcadores.className = 'btn btn-primary';
            mensaje('Modo edición activo: haga clic sobre una línea para agregar o quitar cruces.', 'secondary');
        });
        botonSugerir.addEventListener('click', sugerirCruces);
        botonEliminar.addEventListener('click', function () {
            lineas = lineas.filter(function (linea) { return linea.id !== seleccionada; });
            seleccionada = null;
            actualizarTodo();
        });
        botonLimpiar.addEventListener('click', function () {
            if (!lineas.length || window.confirm('¿Eliminar todas las líneas y cruces del conteo?')) {
                lineas = [];
                seleccionada = null;
                actualizarTodo();
            }
        });
        // El valor se muestra mientras se mueve; al soltar se recalcula solamente la línea activa.
        sensibilidad.addEventListener('input', function () { sensibilidadValor.textContent = sensibilidad.value; });
        sensibilidad.addEventListener('change', function () {
            if (seleccionada !== null) sugerirCruces();
        });
    }

    // Inicialización reutilizable para incluir el contador en futuros formatos.
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-grain-counter]').forEach(iniciar);
    });
})();
