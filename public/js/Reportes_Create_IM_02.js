document.addEventListener('DOMContentLoaded', function () {
    // El módulo es compartido, pero solo se activa cuando encuentra uno de estos formularios IM.
    const form = document.getElementById('FOR-PIMP-02_B_03')
        || document.getElementById('FOR-PIMP-03_B_01')
        || document.getElementById('FOR-PIMP-04_02')
        || document.getElementById('FOR-PIMP-04_03')
        || document.getElementById('FOR-PIMP-05_B_01')
        || document.getElementById('FOR-PIMP-07_B_01');
    if (!form) return;

    const formId = form.id;

    form.querySelectorAll('input, select, button, textarea').forEach(function (element) {
        if (element.tagName !== 'TEXTAREA') {
            element.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });
        }
    });

    function storageKey(element) {
        return formId + '_' + (element.name || element.id);
    }

    const fields = form.querySelectorAll('input:not([type="file"]), textarea, select');

    fields.forEach(function (field) {
        const savedValue = localStorage.getItem(storageKey(field));
        if (savedValue !== null && !field.value) {
            field.value = savedValue;
        }

        field.addEventListener('input', function () {
            localStorage.setItem(storageKey(field), field.value);
        });

        field.addEventListener('change', function () {
            localStorage.setItem(storageKey(field), field.value);
        });
    });

    const clienteRadios = form.querySelectorAll('input[name="TieneCliente"]');
    const campoClienteSelect = document.getElementById('campoClienteSelect');
    const campoClienteInput = document.getElementById('campoClienteInput');

    function actualizarCliente(valor) {
        if (!campoClienteSelect || !campoClienteInput) return;

        sessionStorage.setItem(formId + '_TieneCliente', valor);

        if (valor === 'si') {
            campoClienteSelect.style.display = 'block';
            campoClienteInput.style.display = 'none';
            campoClienteInput.value = '';
            campoClienteSelect.required = true;
            campoClienteInput.required = false;
            localStorage.removeItem(storageKey(campoClienteInput));
            return;
        }

        if (valor === 'no') {
            campoClienteSelect.style.display = 'none';
            campoClienteInput.style.display = 'block';
            campoClienteSelect.value = '';
            campoClienteInput.required = true;
            campoClienteSelect.required = false;
            localStorage.removeItem(storageKey(campoClienteSelect));
            campoClienteInput.focus();
        }
    }

    if (clienteRadios.length && campoClienteSelect && campoClienteInput) {
        clienteRadios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                actualizarCliente(this.value);
            });
        });

        const clienteGuardado = sessionStorage.getItem(formId + '_TieneCliente');
        const radioInicial = clienteGuardado
            ? form.querySelector('input[name="TieneCliente"][value="' + clienteGuardado + '"]')
            : form.querySelector('input[name="TieneCliente"]:checked');

        if (radioInicial) {
            radioInicial.checked = true;
            actualizarCliente(radioInicial.value);
        }
    }

    const contratoRadios = form.querySelectorAll('input[name="TieneContrato"]');
    const campoContrato = document.getElementById('campoContrato');

    async function actualizarContrato(valor) {
        if (!campoContrato) return;

        sessionStorage.setItem(formId + '_TieneContrato', valor);

        if (valor === 'si') {
            campoContrato.readOnly = false;
            campoContrato.required = true;
            campoContrato.placeholder = 'Ejemplo: 640853841';
            return;
        }

        if (valor === 'no') {
            campoContrato.readOnly = true;
            campoContrato.required = false;
            campoContrato.placeholder = 'Generando contrato interno...';

            try {
                const response = await fetch('/api/siguiente-contrato-interno');
                const data = await response.json();

                campoContrato.value = data.siguiente || '';
                localStorage.setItem(storageKey(campoContrato), campoContrato.value);
            } catch (error) {
                console.error('Error al obtener el contrato interno:', error);
                alert('No se pudo generar el contrato interno');
            }
        }
    }

    if (contratoRadios.length && campoContrato) {
        contratoRadios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                actualizarContrato(this.value);
            });
        });

        const contratoGuardado = sessionStorage.getItem(formId + '_TieneContrato');
        const radioInicial = contratoGuardado
            ? form.querySelector('input[name="TieneContrato"][value="' + contratoGuardado + '"]')
            : form.querySelector('input[name="TieneContrato"]:checked');

        if (radioInicial) {
            radioInicial.checked = true;
            actualizarContrato(radioInicial.value);
        }
    }

    const rellenarBtn = form.querySelector('#preFormBtn');
    if (rellenarBtn) {
        rellenarBtn.addEventListener('click', function () {
            fields.forEach(function (field) {
                // Autorrelleno no debe modificar archivos, identificadores, cálculos ni lecturas numéricas.
                if (field.type === 'hidden' || field.type === 'file' || field.type === 'number'
                    || field.readOnly || field.tagName === 'SELECT'
                    || field.getAttribute('inputmode') === 'decimal') return;

                if (field.value.trim() === '') {
                    field.value = field.type === 'date'
                        ? new Date().toISOString().split('T')[0]
                        : '---';
                    localStorage.setItem(storageKey(field), field.value);
                }
            });
        });
    }

    function mostrarFirmas(valor) {
        ['1', '2', '3', '4'].forEach(function (numero) {
            const bloque = document.getElementById('firmas' + numero);
            if (bloque) {
                bloque.style.display = numero === valor ? 'block' : 'none';
            }
        });
    }

    const numFirmasSelect = document.getElementById('numFirmas');
    if (numFirmasSelect) {
        const savedFirmas = localStorage.getItem(formId + '_numFirmas');
        numFirmasSelect.value = savedFirmas || numFirmasSelect.value || '1';
        mostrarFirmas(numFirmasSelect.value);

        numFirmasSelect.addEventListener('change', function () {
            localStorage.setItem(formId + '_numFirmas', this.value);
            mostrarFirmas(this.value);
        });
    }

    function configurarSelectEquipo(selectId, marcaId, modeloId, nsId, idEquipoId, localStorageName) {
        const select = document.getElementById(selectId);
        if (!select) return;

        function actualizarInputs() {
            const selectedOption = select.options[select.selectedIndex];
            if (!selectedOption) return;

            const marcaInput = document.getElementById(marcaId);
            const modeloInput = document.getElementById(modeloId);
            const nsInput = document.getElementById(nsId);
            const idEquipoInput = document.getElementById(idEquipoId);

            if (marcaInput) marcaInput.value = selectedOption.dataset.marca || '';
            if (modeloInput) modeloInput.value = selectedOption.dataset.modelo || '';
            if (nsInput) nsInput.value = selectedOption.dataset.ns || '';
            if (idEquipoInput) idEquipoInput.value = select.value || '';
        }

        const savedEquipo = localStorage.getItem(formId + '_' + localStorageName);
        if (savedEquipo !== null) {
            select.value = savedEquipo;
            actualizarInputs();
        }

        select.addEventListener('change', function () {
            localStorage.setItem(formId + '_' + localStorageName, select.value);
            actualizarInputs();
        });
    }

    configurarSelectEquipo('equiposSelect', 'marcaInputE', 'modeloInputE', 'nsInputE', 'IDInputE', 'equipos');
    configurarSelectEquipo('equiposSelect1', 'marcaInputE1', 'modeloInputE1', 'nsInputE1', 'IDInputE1', 'equipos1');

    let cropper = null;
    let currentInput = null;

    const imageCountSelect = document.getElementById('imageCount');
    const imageFieldsContainer = document.getElementById('imageFieldsContainer');
    const cropperImage = document.getElementById('cropperImage');
    const cropperModal = document.getElementById('cropperModal');

    function hideCropperModal() {
        if (window.jQuery && cropperModal) {
            $('#cropperModal').modal('hide');
        }
    }

    function showCropperModal() {
        if (window.jQuery && cropperModal) {
            $('#cropperModal').modal('show');
        }
    }

    function saveImageFromCanvas(canvas, label) {
        if (!canvas || !currentInput) return;

        const formularioActual = currentInput.closest('form');
        const conservarCalidadOriginal = formularioActual && formularioActual.id === 'FOR-PIMP-05_B_01';
        const conservarAltaCalidad03 = formularioActual && formularioActual.id === 'FOR-PIMP-03_B_01';
        const base64data = conservarCalidadOriginal
            ? canvas.toDataURL('image/png')
            : canvas.toDataURL('image/jpeg', conservarAltaCalidad03 ? 0.98 : 0.9);
        const previewDiv = document.getElementById(currentInput.id + '-preview');
        const base64Input = document.getElementById(currentInput.id + '-base64');

        if (previewDiv) {
            previewDiv.innerHTML = `
                <img src="${base64data}" class="img-fluid img-thumbnail" />
                <span class="badge bg-success">${label}</span>
            `;
        }

        if (base64Input) {
            base64Input.value = base64data;
        }
    }

    function generateImageFields(count) {
        if (!imageFieldsContainer || !imageCountSelect || !cropperImage) return;

        imageFieldsContainer.innerHTML = '';

        for (let i = 1; i <= count; i++) {
            // 04_03 comparte el formulario con recortes XRF indexados desde 1000.
            // Un índice explícito mantiene unidas imagen, comentario, hoja y posición.
            const indiceFoto = i - 1;
            const sufijoCampo = formId === 'FOR-PIMP-04_03' ? `[${indiceFoto}]` : '[]';
            const col = document.createElement('div');
            col.classList.add('col-sm-6');
            col.id = 'image-container-' + i;

            col.innerHTML = `
                <div class="form-group">
                    <label for="image${i}">Imagen por subir ${i}:</label>
                    <input type="file" class="form-control image-input" id="image${i}" accept="image/*">

                    ${formId !== 'FOR-PIMP-04_03' ? `<div class="form-check mt-2">
                        <input type="checkbox"
                            class="form-check-input imagen-hoja-checkbox"
                            data-index="${i}"
                            id="imagenHoja${i}">
                        <label class="form-check-label" for="imagenHoja${i}">
                            Imagen en una hoja
                        </label>
                    </div>
                    <input type="hidden" name="imagen_hoja[]" id="imagenHojaValue${i}" value="0">` : ''}
                    ${formId === 'FOR-PIMP-04_03' ? `
                    <!-- Alternativa manual: dos imágenes forman cada disparo cuando no existe PDF XRF. -->
                    <div class="form-check mt-2">
                        <input type="hidden" name="es_disparo${sufijoCampo}" id="esDisparoValue${i}" value="0">
                        <input type="checkbox" class="form-check-input foto-disparo-checkbox" data-index="${i}" id="esDisparo${i}">
                        <label class="form-check-label" for="esDisparo${i}">Asignar esta imagen a un disparo</label>
                    </div>
                    <div class="mt-2 d-none numero-disparo-container" id="numeroDisparoContainer${i}">
                        <label for="numeroDisparo${i}">Disparo:</label>
                        <select class="form-control" name="numero_disparo${sufijoCampo}" id="numeroDisparo${i}">
                            <option value="">Seleccione un disparo</option>
                            <option value="1">1er. disparo</option>
                            <option value="2">2do. disparo</option>
                            <option value="3">3er. disparo</option>
                        </select>
                        <small class="text-muted">Alternativa cuando no se cuenta con PDF XRF. Cada disparo requiere dos imágenes.</small>
                    </div>` : ''}
                    <div class="image-preview mt-2" id="image${i}-preview"></div>
                    <textarea class="form-control mt-2" name="comments${sufijoCampo}" id="comment${i}" placeholder="Comentario"></textarea>
                    <input type="hidden" name="images_base64${sufijoCampo}" id="image${i}-base64">
                    <button type="button" class="btn btn-danger mt-2 remove-image" data-index="${i}">Eliminar</button>
                </div>
            `;

            imageFieldsContainer.appendChild(col);
        }

        imageFieldsContainer.querySelectorAll('.imagen-hoja-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const hidden = document.getElementById('imagenHojaValue' + this.dataset.index);
                if (hidden) {
                    hidden.value = this.checked ? '1' : '0';
                }
            });
        });

        // Sincroniza el checkbox visible con los valores que recibe el controlador.
        imageFieldsContainer.querySelectorAll('.foto-disparo-checkbox').forEach(function (checkbox) {
            const index = checkbox.dataset.index;
            const hidden = document.getElementById('esDisparoValue' + index);
            const selectorContainer = document.getElementById('numeroDisparoContainer' + index);
            const selector = document.getElementById('numeroDisparo' + index);
            const actualizar = function () {
                if (hidden) hidden.value = checkbox.checked ? '1' : '0';
                if (selectorContainer) selectorContainer.classList.toggle('d-none', !checkbox.checked);
                if (!checkbox.checked && selector) selector.value = '';
            };
            checkbox.addEventListener('change', actualizar);
            actualizar();
        });

        imageFieldsContainer.querySelectorAll('.remove-image').forEach(function (button) {
            button.addEventListener('click', function () {
                const fieldToRemove = document.getElementById('image-container-' + this.dataset.index);
                if (fieldToRemove) {
                    fieldToRemove.remove();
                    imageCountSelect.value = Math.max((parseInt(imageCountSelect.value, 10) || 1) - 1, 0);
                    localStorage.setItem(formId + '_imageCount', imageCountSelect.value);
                }
            });
        });

        imageFieldsContainer.querySelectorAll('.image-input').forEach(function (input) {
            input.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (!file) return;

                if (!file.type.startsWith('image/')) {
                    alert('Por favor, sube solo imagenes.');
                    input.value = '';
                    return;
                }

                currentInput = event.target;

                const reader = new FileReader();
                reader.onload = function (readerEvent) {
                    if (cropper) {
                        cropper.destroy();
                        cropper = null;
                    }

                    cropperImage.src = readerEvent.target.result;
                    showCropperModal();

                    if (typeof Cropper !== 'undefined') {
                        cropper = new Cropper(cropperImage, {
                            aspectRatio: 4 / 3,
                            viewMode: 1,
                            autoCropArea: 1,
                            minContainerWidth: 760,
                            minContainerHeight: 600,
                            responsive: true
                        });
                    }
                };
                reader.readAsDataURL(file);
            });
        });
    }

    if (imageCountSelect && imageFieldsContainer && cropperImage) {
        const savedImageCount = localStorage.getItem(formId + '_imageCount');
        if (savedImageCount !== null) {
            imageCountSelect.value = savedImageCount;
            generateImageFields(parseInt(savedImageCount, 10) || 0);
        }

        imageCountSelect.addEventListener('change', function () {
            const count = parseInt(this.value, 10) || 0;
            localStorage.setItem(formId + '_imageCount', count);
            generateImageFields(count);
        });

        const rotateLeftBtn = document.getElementById('rotateLeftBtn');
        const rotateRightBtn = document.getElementById('rotateRightBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveWithoutCropBtn = document.getElementById('saveWithoutCropBtn');
        const cropImageBtn = document.getElementById('cropImageBtn');

        if (rotateLeftBtn) {
            rotateLeftBtn.addEventListener('click', function () {
                if (cropper) cropper.rotate(-90);
            });
        }

        if (rotateRightBtn) {
            rotateRightBtn.addEventListener('click', function () {
                if (cropper) cropper.rotate(90);
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', hideCropperModal);
        }

        if (saveWithoutCropBtn) {
            saveWithoutCropBtn.addEventListener('click', function () {
                if (!cropper) return;

                const formularioActual = currentInput ? currentInput.closest('form') : null;
                const opciones = formularioActual && formularioActual.id === 'FOR-PIMP-03_B_01'
                    ? { maxWidth: 1600, maxHeight: 1200, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' }
                    : undefined;
                saveImageFromCanvas(cropper.getCroppedCanvas(opciones), 'Guardado');
                hideCropperModal();
            });
        }

        if (cropImageBtn) {
            cropImageBtn.addEventListener('click', function () {
                if (!cropper) return;

                const formularioActual = currentInput ? currentInput.closest('form') : null;
                const opciones = formularioActual && formularioActual.id === 'FOR-PIMP-03_B_01'
                    ? { maxWidth: 1600, maxHeight: 1200, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' }
                    : undefined;
                saveImageFromCanvas(cropper.getCroppedCanvas(opciones), 'Recortado');
                hideCropperModal();
            });
        }

        if (window.jQuery && cropperModal) {
            $('#cropperModal').on('hidden.bs.modal', function () {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
            });
        }
    }

    form.addEventListener('submit', function () {
        fields.forEach(function (field) {
            localStorage.removeItem(storageKey(field));
        });
        localStorage.removeItem(formId + '_numFirmas');
        localStorage.removeItem(formId + '_equipos');
        localStorage.removeItem(formId + '_equipos1');
        localStorage.removeItem(formId + '_imageCount');
        sessionStorage.removeItem(formId + '_TieneCliente');
        sessionStorage.removeItem(formId + '_TieneContrato');
    });
});
