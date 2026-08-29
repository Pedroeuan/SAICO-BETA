(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const formulario = document.getElementById('FOR-PIMP-06_B_01');
        const modal = document.getElementById('cropperModal');
        const imagenCropper = document.getElementById('cropperImage');

        // Este editor se activa exclusivamente en FOR-PIMP-06_B/01.
        if (!formulario || !modal || !imagenCropper || modal.dataset.anotadorInicializado === '1') return;
        modal.dataset.anotadorInicializado = '1';

        const contenedorImagen = modal.querySelector('.img-container');
        const pieModal = modal.querySelector('.modal-footer');
        const botonRecortar = document.getElementById('cropImageBtn');
        const botonGuardarOriginal = document.getElementById('saveWithoutCropBtn');
        const botonRotarIzquierda = document.getElementById('rotateLeftBtn');
        const botonRotarDerecha = document.getElementById('rotateRightBtn');
        const esFormularioEdicion = formulario.dataset.modo === 'edit';

        let inputImagenActivo = null;
        let lienzoBase = null;
        let operaciones = [];
        let herramienta = 'flecha';
        let inicioFlecha = null;
        let anotando = false;
        let editandoImagenGuardada = false;

        inyectarEstilos();

        const interfaz = crearInterfaz();
        contenedorImagen.insertAdjacentElement('afterend', interfaz.panel);
        pieModal.insertBefore(interfaz.botonPreparar, botonRecortar || pieModal.firstChild);
        // En Edit se conserva la función, pero el botón no se agrega al DOM.
        if (!esFormularioEdicion) {
            pieModal.insertBefore(interfaz.botonPrepararRecorte, botonRecortar || pieModal.firstChild);
        }
        agregarBotonesImagenesGuardadas();

        // La captura delegada identifica correctamente tanto tarjetas nuevas como reemplazos de Edit.
        document.addEventListener('change', function (evento) {
            const input = evento.target;
            if (!input.classList || !input.classList.contains('image-input')) return;
            if (input.closest('form') !== formulario) return;

            inputImagenActivo = input;
            editandoImagenGuardada = input.dataset.anotarImagenGuardada === '1';
            delete input.dataset.anotarImagenGuardada;
            restaurarEditor();
        }, true);

        // La acción principal conserva la fotografía completa. El recorte es una decisión explícita.
        interfaz.botonPreparar.addEventListener('click', function () {
            prepararAnotacion(false);
        });
        interfaz.botonPrepararRecorte.addEventListener('click', function () {
            prepararAnotacion(true);
        });
        interfaz.botonFlecha.addEventListener('click', function () {
            seleccionarHerramienta('flecha');
            mostrarEstado('Arrastre sobre la imagen desde el inicio hasta la punta de la flecha.', 'info');
        });
        interfaz.botonTexto.addEventListener('click', function () {
            if (!interfaz.texto.value.trim()) {
                mostrarEstado('Escriba primero el comentario que desea colocar.', 'warning');
                interfaz.texto.focus();
                return;
            }

            seleccionarHerramienta('texto');
            mostrarEstado('Haga clic en la posición donde desea colocar el comentario.', 'info');
        });
        interfaz.botonDeshacer.addEventListener('click', function () {
            if (operaciones.length > 0) operaciones.pop();
            renderizar();
            mostrarEstado(operaciones.length ? 'Se eliminó la última marca.' : 'No quedan marcas en la imagen.', 'secondary');
        });
        interfaz.botonLimpiar.addEventListener('click', function () {
            operaciones = [];
            renderizar();
            mostrarEstado('Se eliminaron todas las marcas.', 'secondary');
        });
        interfaz.botonVolver.addEventListener('click', restaurarEditor);
        interfaz.botonGuardar.addEventListener('click', guardarImagenAnotada);

        interfaz.canvas.addEventListener('pointerdown', iniciarTrazo);
        interfaz.canvas.addEventListener('pointermove', previsualizarTrazo);
        interfaz.canvas.addEventListener('pointerup', terminarTrazo);
        interfaz.canvas.addEventListener('pointercancel', cancelarTrazo);

        // Siempre se restaura la vista normal para que la siguiente fotografía no herede marcas.
        if (window.jQuery) {
            window.jQuery(modal).on('hidden.bs.modal', function () {
                /*
                 * Al editar una fotografía existente, el File temporal solo sirve para que
                 * Cropper lea el archivo original. La imagen que se enviará es el Base64
                 * aplanado; limpiar el File evita volver a subir el original por duplicado.
                 */
                if (editandoImagenGuardada && inputImagenActivo) {
                    inputImagenActivo.value = '';
                }
                editandoImagenGuardada = false;
                restaurarEditor();
            });
        }

        function agregarBotonesImagenesGuardadas() {
            formulario.querySelectorAll('[id^="image-container-"]').forEach(function (tarjeta) {
                if (tarjeta.dataset.fotoEsTexto === '1') return;
                if (tarjeta.querySelector('[data-anotar-imagen-guardada]')) return;

                const vistaPrevia = tarjeta.querySelector('.image-preview');
                const imagen = vistaPrevia ? vistaPrevia.querySelector('img') : null;
                const input = tarjeta.querySelector('input.image-input');
                if (!vistaPrevia || !imagen || !input) return;

                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'btn btn-sm btn-warning mt-2';
                boton.setAttribute('data-anotar-imagen-guardada', '1');
                boton.textContent = 'Marcar imagen guardada';
                boton.addEventListener('click', function () {
                    cargarImagenGuardada(imagen, input, boton);
                });
                vistaPrevia.insertAdjacentElement('afterend', boton);
            });
        }

        async function cargarImagenGuardada(imagen, input, boton) {
            const url = imagen.currentSrc || imagen.src;
            if (!url) {
                mostrarAviso('No se encontró la fotografía guardada.');
                return;
            }

            const textoOriginal = boton.textContent;
            boton.disabled = true;
            boton.textContent = 'Cargando imagen original...';

            try {
                /*
                 * Se descarga el archivo almacenado, no la miniatura visible. FileReader y
                 * Cropper recibirán sus píxeles naturales, igual que en una carga nueva.
                 */
                const respuesta = await window.fetch(url, {
                    credentials: 'same-origin',
                    cache: 'no-store'
                });
                if (!respuesta.ok) {
                    throw new Error('HTTP ' + respuesta.status);
                }

                const blob = await respuesta.blob();
                if (!blob.type.startsWith('image/')) {
                    throw new Error('El archivo almacenado no es una imagen válida.');
                }

                const transferencia = new DataTransfer();
                const nombre = nombreArchivoDesdeUrl(url);
                transferencia.items.add(new File([blob], nombre, {
                    type: blob.type,
                    lastModified: Date.now()
                }));

                input.dataset.anotarImagenGuardada = '1';
                input.files = transferencia.files;
                input.dispatchEvent(new Event('change', { bubbles: true }));
            } catch (error) {
                console.error('No fue posible abrir la imagen guardada para anotarla:', error);
                mostrarAviso('No fue posible cargar la fotografía original. Recargue la página e inténtelo nuevamente.');
            } finally {
                boton.disabled = false;
                boton.textContent = textoOriginal;
            }
        }

        function nombreArchivoDesdeUrl(url) {
            try {
                const nombre = new URL(url, window.location.href).pathname.split('/').pop();
                return decodeURIComponent(nombre || 'fotografia.png');
            } catch (error) {
                return 'fotografia.png';
            }
        }

        function crearInterfaz() {
            const botonPreparar = document.createElement('button');
            botonPreparar.type = 'button';
            botonPreparar.className = 'btn btn-warning';
            botonPreparar.id = 'prepararAnotacionImagenBtn';
            botonPreparar.textContent = 'Anotar imagen completa';

            const botonPrepararRecorte = document.createElement('button');
            botonPrepararRecorte.type = 'button';
            botonPrepararRecorte.className = 'btn btn-outline-warning';
            botonPrepararRecorte.id = 'prepararAnotacionRecorteBtn';
            botonPrepararRecorte.textContent = 'Anotar solo el recorte';

            /*
             * La función se conserva para Create y para una posible reutilización futura,
             * pero no se ofrece en Edit: una fotografía ya guardada solo puede marcarse
             * completa, evitando que el técnico confunda anotación con un nuevo recorte.
             */
            if (esFormularioEdicion) {
                botonPrepararRecorte.classList.add('d-none');
                botonPrepararRecorte.setAttribute('aria-hidden', 'true');
            }

            const panel = document.createElement('section');
            panel.id = 'panelAnotacionesImagen';
            panel.className = 'saico-anotador d-none';
            panel.setAttribute('aria-label', 'Editor de flechas y comentarios');
            panel.innerHTML = `
                <div class="alert alert-info py-2 mb-2" role="status" data-anotador-estado>
                    Arrastre para dibujar una flecha o escriba un comentario y seleccione “Colocar texto”.
                </div>
                <div class="saico-anotador-herramientas mb-2">
                    <button type="button" class="btn btn-sm btn-danger" data-herramienta-flecha>Flecha</button>
                    <input type="text" class="form-control form-control-sm" maxlength="120"
                        placeholder="Comentario que aparecerá sobre la fotografía" data-texto-anotacion>
                    <button type="button" class="btn btn-sm btn-primary" data-herramienta-texto>Colocar texto</button>
                    <label class="saico-anotador-color mb-0">Color
                        <input type="color" value="#ff0000" data-color-anotacion>
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-deshacer-anotacion>Deshacer</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-limpiar-anotaciones>Limpiar marcas</button>
                </div>
                <div class="saico-anotador-lienzo">
                    <canvas data-lienzo-anotaciones aria-label="Fotografía que se está anotando"></canvas>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <button type="button" class="btn btn-secondary" data-volver-recorte>Volver al recorte</button>
                    <button type="button" class="btn btn-success" data-guardar-anotacion>Guardar imagen anotada</button>
                </div>
            `;

            return {
                panel: panel,
                botonPreparar: botonPreparar,
                botonPrepararRecorte: botonPrepararRecorte,
                canvas: panel.querySelector('[data-lienzo-anotaciones]'),
                estado: panel.querySelector('[data-anotador-estado]'),
                texto: panel.querySelector('[data-texto-anotacion]'),
                color: panel.querySelector('[data-color-anotacion]'),
                botonFlecha: panel.querySelector('[data-herramienta-flecha]'),
                botonTexto: panel.querySelector('[data-herramienta-texto]'),
                botonDeshacer: panel.querySelector('[data-deshacer-anotacion]'),
                botonLimpiar: panel.querySelector('[data-limpiar-anotaciones]'),
                botonVolver: panel.querySelector('[data-volver-recorte]'),
                botonGuardar: panel.querySelector('[data-guardar-anotacion]')
            };
        }

        function prepararAnotacion(usarRecorte) {
            const cropper = imagenCropper.cropper;
            if (!cropper || !inputImagenActivo) {
                mostrarAviso('Primero seleccione una fotografía y espere a que se muestre en el editor.');
                return;
            }

            /*
             * La imagen completa se genera con las dimensiones naturales del archivo, no con
             * las dimensiones visibles del modal. Así no se amplía una miniatura en el PDF.
             * El recorte también conserva sus píxeles naturales, pero solo se usa si el técnico
             * elige expresamente “Anotar solo el recorte”.
             */
            const imagenTrabajo = usarRecorte
                ? obtenerRecorteNatural(cropper)
                : obtenerImagenCompletaNatural(cropper);

            if (!imagenTrabajo || !imagenTrabajo.width || !imagenTrabajo.height) {
                mostrarAviso('No fue posible preparar la imagen. Ajuste el recorte e inténtelo nuevamente.');
                return;
            }

            lienzoBase = document.createElement('canvas');
            lienzoBase.width = imagenTrabajo.width;
            lienzoBase.height = imagenTrabajo.height;
            lienzoBase.getContext('2d').drawImage(imagenTrabajo, 0, 0);

            interfaz.canvas.width = imagenTrabajo.width;
            interfaz.canvas.height = imagenTrabajo.height;
            operaciones = [];
            inicioFlecha = null;
            anotando = true;
            seleccionarHerramienta('flecha');
            contenedorImagen.classList.add('d-none');
            interfaz.panel.classList.remove('d-none');
            alternarBotonesRecorte(false);
            renderizar();
            mostrarEstado(
                usarRecorte
                    ? 'Está anotando únicamente el recorte seleccionado. Arrastre para dibujar una flecha.'
                    : 'Está anotando la fotografía completa a resolución original. Arrastre para dibujar una flecha.',
                'info'
            );
        }

        function obtenerRecorteNatural(cropper) {
            return cropper.getCroppedCanvas({
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });
        }

        function obtenerImagenCompletaNatural(cropper) {
            const datosImagen = cropper.getImageData();
            const rotacion = Number(cropper.getData().rotate || 0);
            const giraVertical = Math.abs(rotacion) % 180 === 90;
            const canvas = document.createElement('canvas');

            canvas.width = giraVertical ? datosImagen.naturalHeight : datosImagen.naturalWidth;
            canvas.height = giraVertical ? datosImagen.naturalWidth : datosImagen.naturalHeight;

            const contexto = canvas.getContext('2d');
            contexto.translate(canvas.width / 2, canvas.height / 2);
            contexto.rotate(rotacion * Math.PI / 180);
            contexto.drawImage(
                cropper.element,
                -datosImagen.naturalWidth / 2,
                -datosImagen.naturalHeight / 2,
                datosImagen.naturalWidth,
                datosImagen.naturalHeight
            );

            return canvas;
        }

        function iniciarTrazo(evento) {
            if (!anotando) return;
            evento.preventDefault();
            interfaz.canvas.setPointerCapture(evento.pointerId);
            const punto = obtenerPunto(evento);

            if (herramienta === 'texto') {
                const texto = interfaz.texto.value.trim();
                if (!texto) {
                    mostrarEstado('Escriba primero el comentario.', 'warning');
                    return;
                }

                operaciones.push({ tipo: 'texto', punto: punto, texto: texto, color: interfaz.color.value });
                seleccionarHerramienta('flecha');
                renderizar();
                mostrarEstado('Comentario agregado. Puede dibujar flechas o colocar otro texto.', 'success');
                return;
            }

            inicioFlecha = punto;
        }

        function previsualizarTrazo(evento) {
            if (!anotando || herramienta !== 'flecha' || !inicioFlecha) return;
            renderizar({
                tipo: 'flecha',
                inicio: inicioFlecha,
                fin: obtenerPunto(evento),
                color: interfaz.color.value
            });
        }

        function terminarTrazo(evento) {
            if (!anotando || herramienta !== 'flecha' || !inicioFlecha) return;
            const fin = obtenerPunto(evento);
            const distancia = Math.hypot(fin.x - inicioFlecha.x, fin.y - inicioFlecha.y);

            if (distancia >= Math.max(8, Math.min(interfaz.canvas.width, interfaz.canvas.height) * 0.01)) {
                operaciones.push({ tipo: 'flecha', inicio: inicioFlecha, fin: fin, color: interfaz.color.value });
                mostrarEstado('Flecha agregada.', 'success');
            }

            inicioFlecha = null;
            renderizar();
        }

        function cancelarTrazo() {
            inicioFlecha = null;
            renderizar();
        }

        function obtenerPunto(evento) {
            const rectangulo = interfaz.canvas.getBoundingClientRect();
            return {
                x: (evento.clientX - rectangulo.left) * (interfaz.canvas.width / rectangulo.width),
                y: (evento.clientY - rectangulo.top) * (interfaz.canvas.height / rectangulo.height)
            };
        }

        function renderizar(operacionTemporal) {
            if (!lienzoBase) return;
            const contexto = interfaz.canvas.getContext('2d');
            contexto.clearRect(0, 0, interfaz.canvas.width, interfaz.canvas.height);
            contexto.drawImage(lienzoBase, 0, 0);
            operaciones.forEach(function (operacion) {
                dibujarOperacion(contexto, operacion);
            });
            if (operacionTemporal) dibujarOperacion(contexto, operacionTemporal);
        }

        function dibujarOperacion(contexto, operacion) {
            if (operacion.tipo === 'flecha') {
                dibujarFlecha(contexto, operacion);
            } else if (operacion.tipo === 'texto') {
                dibujarTexto(contexto, operacion);
            }
        }

        function dibujarFlecha(contexto, operacion) {
            const anchoReferencia = Math.min(interfaz.canvas.width, interfaz.canvas.height);
            const grosor = Math.max(4, anchoReferencia * 0.007);
            const punta = Math.max(18, anchoReferencia * 0.035);
            const angulo = Math.atan2(operacion.fin.y - operacion.inicio.y, operacion.fin.x - operacion.inicio.x);

            contexto.save();
            contexto.strokeStyle = operacion.color;
            contexto.fillStyle = operacion.color;
            contexto.lineWidth = grosor;
            contexto.lineCap = 'round';
            contexto.lineJoin = 'round';
            contexto.beginPath();
            contexto.moveTo(operacion.inicio.x, operacion.inicio.y);
            contexto.lineTo(operacion.fin.x, operacion.fin.y);
            contexto.stroke();
            contexto.beginPath();
            contexto.moveTo(operacion.fin.x, operacion.fin.y);
            contexto.lineTo(
                operacion.fin.x - punta * Math.cos(angulo - Math.PI / 6),
                operacion.fin.y - punta * Math.sin(angulo - Math.PI / 6)
            );
            contexto.lineTo(
                operacion.fin.x - punta * Math.cos(angulo + Math.PI / 6),
                operacion.fin.y - punta * Math.sin(angulo + Math.PI / 6)
            );
            contexto.closePath();
            contexto.fill();
            contexto.restore();
        }

        function dibujarTexto(contexto, operacion) {
            const referencia = Math.min(interfaz.canvas.width, interfaz.canvas.height);
            const tamano = Math.max(22, referencia * 0.04);
            const margenLinea = tamano * 1.18;
            const lineas = envolverTexto(contexto, operacion.texto, interfaz.canvas.width * 0.55, tamano);

            contexto.save();
            contexto.font = 'bold ' + tamano + 'px Arial, sans-serif';
            contexto.textBaseline = 'top';
            contexto.lineJoin = 'round';
            contexto.strokeStyle = '#ffffff';
            contexto.lineWidth = Math.max(3, tamano * 0.16);
            contexto.fillStyle = operacion.color;
            lineas.forEach(function (linea, indice) {
                const y = operacion.punto.y + (indice * margenLinea);
                contexto.strokeText(linea, operacion.punto.x, y);
                contexto.fillText(linea, operacion.punto.x, y);
            });
            contexto.restore();
        }

        function envolverTexto(contexto, texto, anchoMaximo, tamano) {
            contexto.save();
            contexto.font = 'bold ' + tamano + 'px Arial, sans-serif';
            const palabras = texto.split(/\s+/);
            const lineas = [];
            let linea = '';

            palabras.forEach(function (palabra) {
                const candidata = linea ? linea + ' ' + palabra : palabra;
                if (linea && contexto.measureText(candidata).width > anchoMaximo) {
                    lineas.push(linea);
                    linea = palabra;
                } else {
                    linea = candidata;
                }
            });
            if (linea) lineas.push(linea);
            contexto.restore();
            return lineas.slice(0, 5);
        }

        function guardarImagenAnotada() {
            if (!anotando || !inputImagenActivo || !lienzoBase) return;
            renderizar();
            const base64 = interfaz.canvas.toDataURL('image/png');
            const destino = localizarDestino(inputImagenActivo);

            if (!destino.oculto || !destino.vistaPrevia) {
                mostrarEstado('No se encontró el campo donde se almacena esta fotografía.', 'danger');
                return;
            }

            destino.oculto.value = base64;
            destino.vistaPrevia.innerHTML = '';
            const imagen = document.createElement('img');
            imagen.src = base64;
            imagen.className = 'img-fluid img-thumbnail';
            imagen.alt = 'Fotografía anotada';
            const insignia = document.createElement('span');
            insignia.className = 'badge bg-success';
            insignia.textContent = 'Anotación guardada';
            destino.vistaPrevia.appendChild(imagen);
            destino.vistaPrevia.appendChild(insignia);

            cerrarModal();
        }

        function localizarDestino(input) {
            const idSeguro = window.CSS && window.CSS.escape ? window.CSS.escape(input.id) : input.id;
            let vistaPrevia = input.id ? document.getElementById(input.id + '-preview') : null;
            let oculto = input.id ? document.getElementById(input.id + '-base64') : null;
            const grupo = input.closest('.form-group') || input.closest('[id^="image-container-"]');

            if (!vistaPrevia && grupo) vistaPrevia = grupo.querySelector('.image-preview');
            if (!oculto && grupo) oculto = grupo.querySelector('input[type="hidden"][name^="images_base64"]');
            if (!oculto && idSeguro) oculto = formulario.querySelector('#' + idSeguro + '-base64');

            return { vistaPrevia: vistaPrevia, oculto: oculto };
        }

        function restaurarEditor() {
            anotando = false;
            inicioFlecha = null;
            operaciones = [];
            lienzoBase = null;
            interfaz.texto.value = '';
            interfaz.panel.classList.add('d-none');
            contenedorImagen.classList.remove('d-none');
            alternarBotonesRecorte(true);
        }

        function seleccionarHerramienta(nombre) {
            herramienta = nombre;
            interfaz.botonFlecha.classList.toggle('active', nombre === 'flecha');
            interfaz.botonTexto.classList.toggle('active', nombre === 'texto');
            interfaz.canvas.style.cursor = nombre === 'texto' ? 'text' : 'crosshair';
        }

        function alternarBotonesRecorte(visibles) {
            [
                botonRotarIzquierda,
                botonRotarDerecha,
                botonRecortar,
                botonGuardarOriginal,
                interfaz.botonPreparar,
                interfaz.botonPrepararRecorte
            ]
                .filter(Boolean)
                .forEach(function (boton) {
                    boton.classList.toggle('d-none', !visibles);
                });
        }

        function mostrarEstado(mensaje, tipo) {
            interfaz.estado.className = 'alert alert-' + tipo + ' py-2 mb-2';
            interfaz.estado.textContent = mensaje;
        }

        function mostrarAviso(mensaje) {
            if (window.Swal) {
                window.Swal.fire({ icon: 'warning', title: 'Editor de imagen', text: mensaje });
            } else {
                window.alert(mensaje);
            }
        }

        function cerrarModal() {
            if (window.jQuery) {
                window.jQuery(modal).modal('hide');
            } else {
                modal.classList.remove('show');
                modal.style.display = 'none';
                restaurarEditor();
            }
        }

        function inyectarEstilos() {
            if (document.getElementById('estilosAnotadorImagen06B01')) return;
            const estilos = document.createElement('style');
            estilos.id = 'estilosAnotadorImagen06B01';
            estilos.textContent = `
                .saico-anotador-herramientas { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
                .saico-anotador-herramientas [data-texto-anotacion] { flex:1 1 280px; }
                .saico-anotador-color { display:flex; align-items:center; gap:5px; font-weight:600; }
                .saico-anotador-color input { width:42px; height:31px; padding:2px; border:1px solid #ced4da; }
                .saico-anotador-lienzo { max-height:600px; overflow:auto; text-align:center; background:#343a40; padding:8px; }
                .saico-anotador-lienzo canvas { display:block; max-width:100%; height:auto; margin:auto; touch-action:none; background:#fff; }
                #cropperModal .modal-dialog { max-width:900px; }
            `;
            document.head.appendChild(estilos);
        }
    });
})();
