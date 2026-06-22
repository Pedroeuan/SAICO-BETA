    /*check del contrato, si y no */
        document.addEventListener("DOMContentLoaded", function () {

            const radios = document.getElementsByName("TieneContrato");
            const campoContrato = document.getElementById("campoContrato");
            const contratoInternoHidden = document.getElementById("contratoInternoHidden");

            const textoInterno = document.getElementById("contratoInternoTexto");
            const numeroInterno = document.getElementById("numeroInterno");

            // clave por formulario para localStorage
            const formId_forContrato = document.querySelectorAll("form")[1]?.id || document.querySelector("form").id;
            const keyTiene = formId_forContrato + '_TieneContrato';
            const keyContratoInterno = formId_forContrato + '_ContratoInterno';
            const keyCampoContrato = formId_forContrato + '_CampoContrato';

            // Restaurar selección si existe
            const storedSelection = localStorage.getItem(keyTiene);
            const storedContratoInterno = localStorage.getItem(keyContratoInterno);
            const storedCampoContrato = localStorage.getItem(keyCampoContrato);

            function applySiState() {
                // Mostrar campo editable
                campoContrato.disabled = false;
                campoContrato.required = true;
                textoInterno.style.display = "none";
                numeroInterno.textContent = "";
                contratoInternoHidden.value = "";
                if (storedCampoContrato) campoContrato.value = storedCampoContrato;
            }

            async function applyNoState(fetchIfMissing = true) {
                campoContrato.disabled = true;
                campoContrato.required = false;
                campoContrato.value = "";

                // Si ya guardamos un contrato interno en localStorage, usarlo
                if (storedContratoInterno) {
                    textoInterno.style.display = "block";
                    numeroInterno.textContent = storedContratoInterno;
                    contratoInternoHidden.value = storedContratoInterno;
                    return;
                }

                // Si no hay contrato en localStorage y se permite obtener uno, solicitarlo
                if (fetchIfMissing) {
                    try {
                        const response = await fetch('/api/siguiente-contrato-interno');
                        const data = await response.json();
                        const nuevoContrato = data.siguiente;
                        textoInterno.style.display = "block";
                        numeroInterno.textContent = nuevoContrato;
                        contratoInternoHidden.value = nuevoContrato;
                        localStorage.setItem(keyContratoInterno, nuevoContrato);
                    } catch (error) {
                        console.error("Error al obtener el contrato interno:", error);
                    }
                }
            }

            // Restaurar UI en base a lo guardado
            if (storedSelection === 'no') {
                // marcar radio correspondiente
                radios.forEach(r => { if (r.value === 'no') r.checked = true; });
                applyNoState(false).then(() => {});
            } else if (storedSelection === 'si') {
                radios.forEach(r => { if (r.value === 'si') r.checked = true; });
                applySiState();
            }

            // guardar input campoContrato en localStorage al escribir
            if (campoContrato) {
                campoContrato.addEventListener('input', function() {
                    localStorage.setItem(keyCampoContrato, campoContrato.value);
                });
            }

            // listeners para cambio de radio
            radios.forEach(radio => {
                radio.addEventListener("change", async function () {
                    // Guardar selección
                    localStorage.setItem(keyTiene, this.value);

                    if (this.value === "si") {
                        applySiState();
                        // remover contrato interno guardado (opcional)
                        localStorage.removeItem(keyContratoInterno);
                        return;
                    }

                    if (this.value === "no") {
                        await applyNoState(true);
                    }
                });
            });
        });

    /*Prevenir el Enter*/
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input, select, button, textarea').forEach(function (element) {
        if (element.tagName !== 'TEXTAREA') {
            element.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    }
                });
            }
        });
    });
    
    $('#dynamicTable').on('keydown', 'input', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    /* Imágenes */
    let cropper;
    let currentInput;

    // Botón: Rotar -90° (Antihorario)
    document.getElementById('rotateLeftBtn').addEventListener('click', function () {
        if (cropper) cropper.rotate(-90);
    });

    // Botón: Rotar +90° (Horario)
    document.getElementById('rotateRightBtn').addEventListener('click', function () {
        if (cropper) cropper.rotate(90);
    });

    // Botón: Cancelar
    document.getElementById('cancelBtn').addEventListener('click', function () {
        $('#cropperModal').modal('hide');
    });

    // Botón: Guardar sin recortar (manteniendo rotación)
    document.getElementById('saveWithoutCropBtn').addEventListener('click', function () {

        try {
            // Obtener los datos de la imagen original (incluyendo rotación)
            const imageData = cropper.getImageData();
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Ajustar el tamaño del lienzo según las dimensiones de la imagen rotada
            if (Math.abs(cropper.getData().rotate) % 180 === 90) {
                canvas.width = imageData.naturalHeight;
                canvas.height = imageData.naturalWidth;
            } else {
                canvas.width = imageData.naturalWidth;
                canvas.height = imageData.naturalHeight;
            }

            // Dibujar la imagen rotada en el lienzo
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate((imageData.rotate * Math.PI) / 180);
            ctx.drawImage(
                cropper.element, // Aquí usamos el elemento de la imagen directamente
                -imageData.naturalWidth / 2,
                -imageData.naturalHeight / 2,
                imageData.naturalWidth,
                imageData.naturalHeight
            );

            // Convertir el lienzo a base64
            const base64data = canvas.toDataURL();
            const previewDiv = document.getElementById(`${currentInput.id}-preview`);
            previewDiv.innerHTML = `
                <img src="${base64data}" class="img-fluid img-thumbnail" />
                <span class="badge bg-success">¡Guardado!</span>
            `;
            document.getElementById(`${currentInput.id}-base64`).value = base64data;

            // Cerrar el modal
            $('#cropperModal').modal('hide');
        } catch (error) {
            console.error('Error al guardar la imagen sin recortar:', error);
        }
    });

    // Botón: Recortar y guardar
    document.getElementById('cropImageBtn').addEventListener('click', function () {
        if (cropper && currentInput) {
            const croppedCanvas = cropper.getCroppedCanvas();
            if (croppedCanvas) {
                const base64data = croppedCanvas.toDataURL();
                const previewDiv = document.getElementById(`${currentInput.id}-preview`);
                previewDiv.innerHTML = `
                    <img src="${base64data}" class="img-fluid img-thumbnail" />
                    <span class="badge bg-success">¡Recortado!</span>
                `;
                document.getElementById(`${currentInput.id}-base64`).value = base64data;
            }
        }
        $('#cropperModal').modal('hide');
    });

    // Destruir Cropper al cerrar el modal
    $('#cropperModal').on('hidden.bs.modal', function () {
        if (cropper) cropper.destroy();
    });

    // Generar campos de imágenes
    document.addEventListener("DOMContentLoaded", function () {
        const imageCountSelect = document.getElementById('imageCount');
        const container = document.getElementById('imageFieldsContainer');
        const cropperImage = document.getElementById('cropperImage');

        const selImgCountLocal = localStorage.getItem(document.querySelectorAll("form")[1].id+'_imageCount');
        //selImgCountLocal != null ?  ($('#imageCountSelect').val(selImgCountLocal),generateImageFields(selImgCountLocal),document.getElementById('msgImgNoSave').classList.remove('d-none')):"";

        if (selImgCountLocal != null) {
            $('#imageCountSelect').val(selImgCountLocal);
            generateImageFields(selImgCountLocal);

            const msgImgNoSave = document.getElementById('msgImgNoSave');
            if (msgImgNoSave) {
                msgImgNoSave.classList.remove('d-none');
            }
        }

        imageCountSelect.addEventListener('change', function () {
            const count = parseInt(this.value);
            //localStorage.setItem('imageCount', count);
            generateImageFields(count);
        });

        function generateImageFields(count) {
            container.innerHTML = '';

            for (let i = 1; i <= count; i++) {
                const col = document.createElement('div');
                col.classList.add('col-sm-6');
                col.setAttribute('id', `image-container-${i}`); // ID único para eliminarlo después

                col.innerHTML = `
                    <div class="form-group">
                        <label for="image${i}">Imagen por Subir ${i}:</label>
                        <input type="file" class="form-control image-input" id="image${i}" accept="image/*">

                            <input type="hidden" name="images_base64[]" id="image${i}-base64">
                            <input type="hidden" name="comments[${i}]" id="comment_for_image_${i}">
                        <div id="image${i}-preview" class="mt-2"></div>
                        <button type="button" class="btn btn-danger mt-2 remove-image" data-index="${i}">Eliminar</button>
                    </div>
                `;

                container.appendChild(col);

                // Después de cada 2 imágenes agregar un textarea (comentarios para ese par)
                if (i % 2 === 0) {
                    const pairIndex = Math.ceil(i / 2);

                    const textareaCol = document.createElement('div');
                    textareaCol.classList.add('col-12', 'mb-3');
                    textareaCol.innerHTML = `
                        <div class="form-group">
                            <label>Comentarios para imágenes ${i - 1} y ${i}:</label>
                            <textarea
                                class="form-control"
                                name="comments_pairs[${pairIndex}]"
                                rows="3"
                                placeholder="Comentarios sobre estas dos imágenes..."
                            ></textarea>
                        </div>
                    `;
                    container.appendChild(textareaCol);
                }

            }

            // Agregar eventos de eliminación a los botones: reconstruir campos para mantener consistencia
            document.querySelectorAll('.remove-image').forEach(button => {
                button.addEventListener('click', function () {
                    let currentCount = parseInt(imageCountSelect.value, 10) || 0;
                    if (currentCount > 0) currentCount = currentCount - 1;
                    imageCountSelect.value = currentCount;

                    const msgImgNoSave = document.getElementById('msgImgNoSave');
                    if (msgImgNoSave) {
                        msgImgNoSave.classList.remove('d-none');
                    }

                    // Actualizar el localStorage
                    const formId = document.querySelectorAll("form")[1]?.id || document.querySelector("form").id;
                    localStorage.setItem(formId + '_imageCount', imageCountSelect.value);

                    // Regenerar los campos con el nuevo conteo para mantener índices y textareas en orden
                    generateImageFields(currentCount);
                });
            });

                // Asignar eventos a los textareas de pares: sincronizar con los inputs hidden por imagen
                document.querySelectorAll('.images-comments').forEach(textarea => {
                    textarea.addEventListener('input', function () {
                        const pairIndex = parseInt(this.getAttribute('data-pair-index'), 10);
                        if (isNaN(pairIndex)) return;
                        const firstIndex = (pairIndex - 1) * 2 + 1; // matches our 1-based indexing used in IDs
                        const secondIndex = firstIndex + 1;

                        const firstHidden = document.getElementById(`comment_for_image_${firstIndex}`);
                        const secondHidden = document.getElementById(`comment_for_image_${secondIndex}`);
                        if (firstHidden) firstHidden.value = this.value;
                        if (secondHidden) secondHidden.value = this.value;
                    });
                });

            // Asignar eventos a los nuevos inputs
            document.querySelectorAll('.image-input').forEach(input => {
                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    if (!file.type.startsWith('image/')) {
                        alert('Por favor, sube solo imágenes.');
                        return;
                    }

                    currentInput = e.target;
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        if (cropper) cropper.destroy();
                        cropperImage.src = event.target.result;
                        $('#cropperModal').modal('show');
                        cropper = new Cropper(cropperImage, {
                            aspectRatio: 4 / 3,
                            viewMode: 1,
                            autoCropArea: 1,
                            minContainerWidth: 760,
                            minContainerHeight: 600,
                            responsive: true
                        });
                    };
                    reader.readAsDataURL(file);
                });
            });
        }

        // Limpiar localStorage al enviar el formulario
        document.querySelector("form").addEventListener("submit", function () {
            localStorage.removeItem('imageCount');
        });
    });

    /*Pre-Rellenado del formulario */
    document.addEventListener("DOMContentLoaded", function () {
    const formularios = ["FOR-PIMP-02_B_03","FOR-PIMP-02_B_04","FOR-PIMP-03_B_01","FOR-PIMP-04_02","FOR-PIMP-04_03","FOR-PIMP-05_01","FOR-PIMP-05_B_01","FOR-PIMP-06_B_01","FOR-PIMP-07_B/01"]; // Agrega aquí los IDs de tus formularios

    formularios.forEach(formId => {
        const form = document.getElementById(formId);
        if (!form) return; // Saltar si no existe

        const inputs = form.querySelectorAll(".inputForm");
        const textareas = form.querySelectorAll("textarea");

        // Restaurar valores desde localStorage
        inputs.forEach(input => {
            const stored = localStorage.getItem(`${formId}_${input.name}`);
            if (stored !== null) input.value = stored;

            input.addEventListener("input", () => {
                localStorage.setItem(`${formId}_${input.name}`, input.value);
            });
        });


        textareas.forEach(textarea => {
            // Verificar si es textarea de comentarios (tiene name="comments[]" y id)
            if (textarea.name === "comments[]" && textarea.id) {
                // Guardar usando id como clave
                const stored = localStorage.getItem(`${formId}_${textarea.id}`);
                if (stored !== null) textarea.value = stored;

                textarea.addEventListener("input", () => {
                    localStorage.setItem(`${formId}_${textarea.id}`, textarea.value);
                });
            } else {
                // Para otros textareas (que no son comentarios), usar name
                const stored = localStorage.getItem(`${formId}_${textarea.name}`);
                if (stored !== null) textarea.value = stored;

                textarea.addEventListener("input", () => {
                    localStorage.setItem(`${formId}_${textarea.name}`, textarea.value);
                });
            }
        });

        // Botón rellenar campos vacíos
        const rellenarBtn = form.querySelector("#preFormBtn");
        if (rellenarBtn) {
            rellenarBtn.addEventListener("click", function () {
                inputs.forEach(input => {
                    if (input.value.trim() === "") {
                        input.value = "---";
                        localStorage.setItem(`${formId}_${input.name}`, input.value);
                    }
                });
                textareas.forEach(textarea => {
                    if (textarea.value.trim() === "") {
                        textarea.value = "---";
                        localStorage.setItem(`${formId}_${textarea.name}`, textarea.value);
                    }
                });
            });
        }

        const checkboxes = form.querySelectorAll('input[type="checkbox"]');

        checkboxes.length > 0 ? checkboxes.forEach(checkbox => {
            const key = checkbox.id ? `${formId}_${checkbox.id}` : `${formId}_${checkbox.name}`;

            const stored = localStorage.getItem(key);
            if (stored !== null) {
                checkbox.checked = stored === "true";
            }

            checkbox.addEventListener("change", () => {
                localStorage.setItem(key, checkbox.checked);
            });
        }) : null;

        // Limpiar localStorage al enviar el formulario
        form.addEventListener("submit", function () {
            inputs.forEach(input => localStorage.removeItem(`${formId}_${input.name}`));
            textareas.forEach(textarea => localStorage.removeItem(`${formId}_${textarea.name}`));
            
            checkboxes.length > 0 ? checkboxes.forEach(checkbox => {
                const key = checkbox.id ? `${formId}_${checkbox.id}` : `${formId}_${checkbox.name}`;
                localStorage.removeItem(key);
            }) : null;

        });
    });
});

    /*Selección de Firmas */
    document.addEventListener('DOMContentLoaded', function() {
    const numFirmasLocal = localStorage.getItem(document.querySelectorAll("form")[1].id+'_numFirmas');
    const numFirmasSelect = document.getElementById('numFirmas');
    const firmas1 = document.getElementById('firmas1');
    const firmas2 = document.getElementById('firmas2');
    const firmas3 = document.getElementById('firmas3');
    const firmas4 = document.getElementById('firmas4');


    //numFirmasSelect.value = numFirmasLocal;

    numFirmasLocal ? numFirmasSelect.value = numFirmasLocal : numFirmasSelect.value = '1'; // Valor por defecto si no hay en localStorage
    

    numFirmasSelect.addEventListener('change', function() {
        if (this.value == '1') {
            firmas1.style.display = 'block';
            firmas2.style.display = 'none';
            firmas3.style.display = 'none';
            firmas4.style.display = 'none';
        }
        else if (this.value == '2') {
            firmas1.style.display = 'none';
            firmas2.style.display = 'block';
            firmas3.style.display = 'none';
            firmas4.style.display = 'none';
        }
        else if (this.value == '3') {
            firmas1.style.display = 'none';
            firmas2.style.display = 'none';
            firmas3.style.display = 'block';
            firmas4.style.display = 'none';
        } else if (this.value == '4') {
            firmas1.style.display = 'none';
            firmas2.style.display = 'none';
            firmas3.style.display = 'none';
            firmas4.style.display = 'block';
        }
    });

    // Inicializar la visibilidad de las secciones de firmas
    if (numFirmasSelect.value == '1') {
        firmas1.style.display = 'block';
        firmas2.style.display = 'none';
        firmas3.style.display = 'none';
        firmas4.style.display = 'none';
    }
    else if (numFirmasSelect.value == '2') {
        firmas1.style.display = 'none';
        firmas2.style.display = 'block';
        firmas3.style.display = 'none';
        firmas4.style.display = 'none';
    }
    else if (numFirmasSelect.value == '3') {
        firmas1.style.display = 'none';
        firmas2.style.display = 'none';
        firmas3.style.display = 'block';
        firmas4.style.display = 'none';
    } else if (numFirmasSelect.value == '4') {
        firmas1.style.display = 'none';
        firmas2.style.display = 'none';
        firmas3.style.display = 'none';
        firmas4.style.display = 'block';
    }
    });