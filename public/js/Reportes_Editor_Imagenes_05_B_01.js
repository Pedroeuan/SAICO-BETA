(function (window, document) {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Este módulo se activa únicamente en 05_B_01 para no interferir con editores de otros formatos.
        const formulario = document.getElementById('FOR-PIMP-05_B_01');
        const modalElemento = document.getElementById('cropperModal');
        const imagenRecorte = document.getElementById('cropperImage');
        const botonCancelar = document.getElementById('cancelBtn');
        const botonRotarIzquierda = document.getElementById('rotateLeftBtn');
        const botonRotarDerecha = document.getElementById('rotateRightBtn');
        const botonRecortar = document.getElementById('cropImageBtn');
        const botonGuardarOriginal = document.getElementById('saveWithoutCropBtn');

        // Sin formulario, modal o Cropper disponible no se registran manejadores parciales.
        if (!formulario || !modalElemento || !imagenRecorte || typeof window.Cropper !== 'function') {
            return;
        }

        // Estado compartido de la imagen que se encuentra abierta actualmente en el modal.
        let recortador = null;
        let inputActivo = null;
        let imagenOriginal = '';
        let modalBootstrap = null;

        /** Devuelve el siguiente indice libre sin asumir que las fotos existentes son consecutivas. */
        function siguienteIndiceImagen() {
            const indices = Array.from(formulario.querySelectorAll('[id^="image-container-"]'))
                .map(function (tarjeta) {
                    const coincidencia = tarjeta.id.match(/image-container-(\d+)/);
                    return coincidencia ? Number(coincidencia[1]) : -1;
                });

            return (indices.length ? Math.max.apply(null, indices) : -1) + 1;
        }

        /** Crea una tarjeta compatible con el controlador y el modulo de distribucion de fotos. */
        function crearTarjetaImagen(index, numeroVisible) {
            const tarjeta = document.createElement('div');
            tarjeta.className = 'col-sm-6';
            tarjeta.id = 'image-container-' + index;
            tarjeta.innerHTML = `
                <div class="form-group">
                    <label for="image${index}">Imagen nueva ${numeroVisible}:</label>
                    <input type="file" class="form-control image-input" id="image${index}" accept="image/*">
                    <div class="form-check mt-2">
                        <input type="hidden" name="es_disparo[${index}]" id="esDisparoValue${index}" value="0">
                        <input type="checkbox" class="form-check-input foto-disparo-checkbox" data-index="${index}" id="esDisparo${index}">
                        <label class="form-check-label" for="esDisparo${index}">Asignar esta imagen a un disparo</label>
                    </div>
                    <div class="mt-2 d-none numero-disparo-container" id="numeroDisparoContainer${index}">
                        <label for="numeroDisparo${index}">Disparo:</label>
                        <select class="form-control" name="numero_disparo[${index}]" id="numeroDisparo${index}">
                            <option value="">Seleccione un disparo</option>
                            <option value="1">1er. disparo</option>
                            <option value="2">2do. disparo</option>
                            <option value="3">3er. disparo</option>
                        </select>
                    </div>
                    <div class="image-preview mt-2" id="image${index}-preview"></div>
                    <textarea class="form-control mt-2" name="comments[${index}]" placeholder="Comentario"></textarea>
                    <input type="hidden" name="images_base64[${index}]" id="image${index}-base64">
                    <button type="button" class="btn btn-danger mt-2 remove-new-image">Eliminar</button>
                </div>`;

            // Una tarjeta nueva todavía no existe en servidor, por eso eliminarla solo requiere retirarla del DOM.
            tarjeta.querySelector('.remove-new-image').addEventListener('click', function () {
                tarjeta.remove();
            });

            // La marca de disparo controla tanto el valor enviado como la visibilidad de su selector numérico.
            tarjeta.querySelector('.foto-disparo-checkbox').addEventListener('change', function () {
                tarjeta.querySelector('#esDisparoValue' + index).value = this.checked ? '1' : '0';
                tarjeta.querySelector('#numeroDisparoContainer' + index)
                    .classList.toggle('d-none', !this.checked);
                if (!this.checked) tarjeta.querySelector('#numeroDisparo' + index).value = '';
            });

            return tarjeta;
        }

        /**
         * Sustituye el selector para retirar el manejador heredado de Reportes_Edit.js.
         * La cantidad representa fotos nuevas y por eso nunca se restaura desde localStorage.
         */
        function inicializarSelectorImagenes() {
            const selectorAnterior = formulario.querySelector('#imageCount');
            const contenedor = formulario.querySelector('#imageFieldsContainer');
            if (!selectorAnterior || !contenedor) return;

            const selector = selectorAnterior.cloneNode(true);
            selector.value = '';
            selectorAnterior.replaceWith(selector);
            window.localStorage.removeItem('FOR-PIMP-05_B_01_Form_imageCount');

            // Cada cambio reconstruye únicamente las tarjetas nuevas sin alterar fotografías históricas del reporte.
            selector.addEventListener('change', function () {
                const cantidad = Number.parseInt(selector.value, 10) || 0;
                const indiceInicial = siguienteIndiceImagen();
                contenedor.replaceChildren();

                for (let i = 0; i < cantidad; i++) {
                    contenedor.appendChild(crearTarjetaImagen(indiceInicial + i, i + 1));
                }
            });
        }

        /** Obtiene la instancia del modal cuando la página utiliza Bootstrap 5. */
        function obtenerModal() {
            if (window.bootstrap && typeof window.bootstrap.Modal === 'function') {
                modalBootstrap = modalBootstrap || window.bootstrap.Modal.getOrCreateInstance(modalElemento);
                return modalBootstrap;
            }

            return null;
        }

        /** Abre el modal con Bootstrap 5 y conserva compatibilidad con páginas que todavía usan jQuery. */
        function mostrarModal() {
            const modal = obtenerModal();
            if (modal) {
                modal.show();
                return;
            }

            if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                window.jQuery(modalElemento).modal('show');
            }
        }

        /** Cierra el modal mediante la misma implementación disponible en la página. */
        function ocultarModal() {
            const modal = obtenerModal();
            if (modal) {
                modal.hide();
                return;
            }

            if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                window.jQuery(modalElemento).modal('hide');
            }
        }

        /** Localiza la vista previa y el campo base64 asociados al input que originó el recorte. */
        function camposImagen(input) {
            const grupo = input ? input.closest('.form-group') : null;
            return {
                preview: grupo ? grupo.querySelector('.image-preview') : null,
                base64: grupo ? grupo.querySelector('input[type="hidden"][name^="images_base64"]') : null
            };
        }

        /** Actualiza simultáneamente la vista visible y el valor que recibirá el controlador. */
        function actualizarImagen(dataUrl, estado) {
            const campos = camposImagen(inputActivo);
            if (!campos.preview || !campos.base64) return;

            const imagen = document.createElement('img');
            imagen.src = dataUrl;
            imagen.alt = 'Vista previa de la imagen';
            imagen.className = 'img-fluid img-thumbnail';

            const insignia = document.createElement('span');
            insignia.className = 'badge ' + (estado === 'Recortada' ? 'bg-success' : 'bg-info');
            insignia.textContent = estado;

            campos.preview.replaceChildren(imagen, insignia);
            campos.base64.value = dataUrl;
        }

        /** Libera la instancia anterior para evitar controles duplicados al abrir otra fotografía. */
        function destruirRecortador() {
            if (recortador) {
                recortador.destroy();
                recortador = null;
            }
        }

        /** Inicializa Cropper después de que el modal sea visible y tenga dimensiones calculables. */
        function iniciarRecortador(dataUrl) {
            destruirRecortador();
            imagenRecorte.src = dataUrl;
            modalElemento.addEventListener('shown.bs.modal', function inicializar() {
                recortador = new window.Cropper(imagenRecorte, {
                    aspectRatio: 4 / 3,
                    viewMode: 1,
                    autoCropArea: 1,
                    minContainerWidth: 760,
                    minContainerHeight: 600,
                    responsive: true
                });
            }, { once: true });
            mostrarModal();
        }

        // La escucha delegada también cubre inputs de imagen creados dinámicamente por el selector.
        formulario.addEventListener('change', function (evento) {
            const input = evento.target.closest('.image-input');
            const archivo = input && input.files ? input.files[0] : null;
            if (!input || !archivo) return;

            if (!archivo.type.startsWith('image/')) {
                input.value = '';
                window.alert('Seleccione un archivo de imagen valido.');
                return;
            }

            // FileReader conserva la imagen original para permitir guardarla sin aplicar el recorte.
            const lector = new FileReader();
            lector.addEventListener('load', function () {
                inputActivo = input;
                imagenOriginal = String(lector.result || '');
                actualizarImagen(imagenOriginal, 'Vista previa');
                iniciarRecortador(imagenOriginal);
            });
            lector.readAsDataURL(archivo);
        });

        // Los giros se aplican sobre la instancia activa y se ignoran cuando todavía no existe una imagen.
        botonRotarIzquierda?.addEventListener('click', function () {
            recortador?.rotate(-90);
        });

        botonRotarDerecha?.addEventListener('click', function () {
            recortador?.rotate(90);
        });

        // El recorte se comprime como JPEG antes de enviarse para reducir el tamaño del formulario.
        botonRecortar?.addEventListener('click', function () {
            if (!recortador || !inputActivo) return;
            const canvas = recortador.getCroppedCanvas();
            if (!canvas) return;

            actualizarImagen(canvas.toDataURL('image/jpeg', 0.92), 'Recortada');
            ocultarModal();
        });

        // Esta ruta conserva exactamente el archivo leído cuando el usuario decide omitir el recorte.
        botonGuardarOriginal?.addEventListener('click', function () {
            if (!inputActivo || !imagenOriginal) return;
            actualizarImagen(imagenOriginal, 'Sin recortar');
            ocultarModal();
        });

        /** Cancelar limpia archivo, vista previa y base64 para no enviar una selección descartada. */
        function cancelarSeleccion() {
            if (inputActivo) {
                const campos = camposImagen(inputActivo);
                inputActivo.value = '';
                if (campos.preview) campos.preview.replaceChildren();
                if (campos.base64) campos.base64.value = '';
            }
            ocultarModal();
        }

        botonCancelar?.addEventListener('click', cancelarSeleccion);
        modalElemento.querySelector('.close')?.addEventListener('click', cancelarSeleccion);

        // Al cerrar se destruye Cropper y después se habilita el generador de nuevas tarjetas.
        modalElemento.addEventListener('hidden.bs.modal', destruirRecortador);
        inicializarSelectorImagenes();
    });
}(window, document));
