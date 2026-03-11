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
    
    /*Botón eliminar para imagenes subidas */
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.remove-image').forEach(button => {
            button.addEventListener('click', function () {
                const index = this.getAttribute('data-index');
                const fieldToRemove = document.getElementById(`image-container-${index}`);
                if (fieldToRemove) {
                    fieldToRemove.style.display = 'none'; // Oculta el elemento
                    document.getElementById(`deleted_image_${index}`).value = index; // Marca como eliminado
                }
            });
        });
    });

    /*Modal para las imagenes que ya estan subidos */
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('image-input')) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    // Mostrar la imagen seleccionada en el modal
                    const cropperImage = document.getElementById('cropperImage');
                    cropperImage.src = event.target.result;

                    // Mostrar el modal automáticamente
                    $('#cropperModal').modal('show');

                    // Inicializar Cropper.js
                    if (cropper) cropper.destroy(); // Destruir cropper anterior si existe
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1, // Cambia según tus necesidades
                        viewMode: 1,
                        minContainerWidth: 760,
                        minContainerHeight: 600,
                        responsive: true,
                    });
                    // Guardar el input actual para referencia
                    currentInput = e.target;
                };
                reader.readAsDataURL(file);
            }
        }
    });

    /* Imágenes */
    document.addEventListener("DOMContentLoaded", function () {
        let cropper = null;
        let currentInput = null;

        const cropperImage = document.getElementById('cropperImage');

        /* ROTAR */
        document.getElementById('rotateLeftBtn').addEventListener('click', () => {
            if (cropper) cropper.rotate(-90);
        });

        document.getElementById('rotateRightBtn').addEventListener('click', () => {
            if (cropper) cropper.rotate(90);
        });

        /* CANCELAR */
        document.getElementById('cancelBtn').addEventListener('click', () => {
            $('#cropperModal').modal('hide');
        });

        /* GUARDAR SIN RECORTAR */
        document.getElementById('saveWithoutCropBtn').addEventListener('click', () => {

            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: cropper.getImageData().naturalWidth,
                height: cropper.getImageData().naturalHeight
            });

            const base64data = canvas.toDataURL();

            const previewDiv = document.getElementById(`${currentInput.id}-preview`);
            previewDiv.innerHTML = `
                <img src="${base64data}" class="img-fluid img-thumbnail">
                <span class="badge bg-success">Guardado</span>
            `;
            /* ocultar imagen anterior */
            const oldPreview = document.getElementById(`${currentInput.id}-old-preview`);
            if(oldPreview){
                oldPreview.remove();
            }
            document.getElementById(`${currentInput.id}-base64`).value = base64data;

            $('#cropperModal').modal('hide');

        });

        /* RECORTAR */
        document.getElementById('cropImageBtn').addEventListener('click', () => {

            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas();

            const base64data = canvas.toDataURL();

            const previewDiv = document.getElementById(`${currentInput.id}-preview`);

            previewDiv.innerHTML = `
                <img src="${base64data}" class="img-fluid img-thumbnail">
                <span class="badge bg-success">Recortado</span>
            `;
            /* ocultar imagen anterior */
            const oldPreview = document.getElementById(`${currentInput.id}-old-preview`);
            if(oldPreview){
                oldPreview.remove();
            }
            document.getElementById(`${currentInput.id}-base64`).value = base64data;

            $('#cropperModal').modal('hide');

        });


        /* DESTRUIR AL CERRAR */
        $('#cropperModal').on('hidden.bs.modal', function () {

            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

        });


        /* ACTIVAR INPUTS */
        function activateImageEvents(){

            document.querySelectorAll('.image-input').forEach(input => {

                input.addEventListener('change', function(e){

                    const file = e.target.files[0];
                    if(!file) return;

                    currentInput = e.target;

                    const reader = new FileReader();

                    reader.onload = function(event){

                        cropperImage.src = event.target.result;

                        $('#cropperModal').modal('show');

                    };

                    reader.readAsDataURL(file);

                });

            });

        }

        /* CREAR CROPPER CUANDO EL MODAL ABRA */
        $('#cropperModal').on('shown.bs.modal', function () {

            if (cropper) {
                cropper.destroy();
            }

            cropper = new Cropper(cropperImage,{
                aspectRatio: 4 / 3,
                viewMode: 1,
                autoCropArea: 1,
                minContainerWidth: 760,
                minContainerHeight: 600,
                responsive: true
            });

        });
        activateImageEvents();
    });


    // Generar campos de imágenes
    document.addEventListener("DOMContentLoaded", function () {
        const imageCountSelect = document.getElementById('imageCount');
        const container = document.getElementById('imageFieldsContainer');
        const cropperImage = document.getElementById('cropperImage');


        imageCountSelect.addEventListener('change', function () {
            const count = parseInt(this.value);
            //localStorage.setItem('imageCount', count);
            generateImageFields(count);
        });

            function generateImageFields(count) {
            container.innerHTML = '';

            // Calcular desde qué índice empezar (considerando imágenes del servidor)
            const existingCount = document.querySelectorAll('[id^="image-container-"]').length;
            let startIndex = existingCount; // Si tienes 3 imágenes cargadas, aquí vale 3

            for (let i = 1; i <= count; i++) {
                const index = startIndex + i; // Comenzamos desde el consecutivo real
                const col = document.createElement('div');
                col.classList.add('col-sm-6');
                col.setAttribute('id', `image-container-${index}`);
                col.innerHTML = `
                    <div class="form-group">
                        <label for="image${index}">Imagen por Subir ${index}:</label>
                        <input type="file" class="form-control image-input" id="image${index}" accept="image/*">

                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input imagen-hoja-checkbox" data-index="${index}" id="imagenHoja${index}">
                            <label class="form-check-label" for="imagenHoja${index}">
                                Imagen en una hoja
                            </label>
                        </div>
                        <input type="hidden" name="imagen_hoja[${index}]" id="imagenHojaValue${index}" value="0">
                        <div class="image-preview mt-2" id="image${index}-preview"></div>
                        <textarea class="form-control mt-2" name="comments[]" placeholder="Comentario"></textarea>
                        <input type="hidden" name="images_base64[]" id="image${index}-base64">
                        <button type="button" class="btn btn-danger mt-2 remove-image" data-index="${index}">Eliminar</button>
                    </div>
                `;
                container.appendChild(col);

                // Si index es par, agregar un textarea para el par de imágenes
                /*if (index % 2 === 0) {
                    const pairIndex = Math.ceil(index / 2);
                    const textareaCol = document.createElement('div');
                    textareaCol.classList.add('col-12', 'mb-3');
                    textareaCol.setAttribute('id', `images-comments-pair-${pairIndex}`);
                    textareaCol.innerHTML = `
                        <div class="form-group">
                            <label for="images-comments-${pairIndex}">Comentarios para imágenes ${index - 1} y ${index}:</label>
                            <textarea class="form-control images-comments" id="images-comments-${pairIndex}" data-pair-index="${pairIndex}" rows="3" placeholder="Comentarios sobre estas dos imágenes..."></textarea>
                        </div>
                    `;
                    container.appendChild(textareaCol);
                }*/
            }

            // Eventos de eliminación
            document.querySelectorAll('.remove-image').forEach(button => {
                button.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    const fieldToRemove = document.getElementById(`image-container-${index}`);
                    if (fieldToRemove) {
                        fieldToRemove.remove();
                    }
                });
            });

            // Eventos de cambio para inputs
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

            // Asignar eventos a los textareas de pares: sincronizar con los inputs hidden por imagen
            document.querySelectorAll('.images-comments').forEach(textarea => {
                textarea.addEventListener('input', function () {
                    const pairIndex = parseInt(this.getAttribute('data-pair-index'), 10);
                    if (isNaN(pairIndex)) return;
                    const firstIndex = (pairIndex - 1) * 2 + 1; // 1-based indexing
                    const secondIndex = firstIndex + 1;

                    const firstHidden = document.getElementById(`comment_for_image_${firstIndex}`);
                    const secondHidden = document.getElementById(`comment_for_image_${secondIndex}`);
                    if (firstHidden) firstHidden.value = this.value;
                    if (secondHidden) secondHidden.value = this.value;
                });
            });

            // Inicializar valores de los textareas por par a partir de comments existentes
            document.querySelectorAll('.images-comments').forEach(textarea => {
                const pairIndex = parseInt(textarea.getAttribute('data-pair-index'), 10);
                if (isNaN(pairIndex)) return;
                const firstIndex = (pairIndex - 1) * 2 + 1;
                const secondIndex = firstIndex + 1;
                const firstHidden = document.getElementById(`comment_for_image_${firstIndex}`);
                const secondHidden = document.getElementById(`comment_for_image_${secondIndex}`);
                if (firstHidden && firstHidden.value) {
                    textarea.value = firstHidden.value;
                } else if (secondHidden && secondHidden.value) {
                    textarea.value = secondHidden.value;
                }
                // Si ninguno tiene valor, dejar como originalmente en blade
            });
        }
        bindImagenHojaCheckboxes();
        // Limpiar localStorage al enviar el formulario
        document.querySelector("form").addEventListener("submit", function () {
            localStorage.removeItem('imageCount');
        });
    });

    // Inicializar textareas por par existentes en la vista de edición: eventos y valor inicial
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.images-comments').forEach(textarea => {
            // Evento para actualizar ambos hidden inputs
            textarea.addEventListener('input', function () {
                const pairIndex = parseInt(this.getAttribute('data-pair-index'), 10);
                if (isNaN(pairIndex)) return;
                const firstIndex = (pairIndex - 1) * 2 + 1;
                const secondIndex = firstIndex + 1;

                const firstHidden = document.getElementById(`comment_for_image_${firstIndex}`);
                const secondHidden = document.getElementById(`comment_for_image_${secondIndex}`);
                if (firstHidden) firstHidden.value = this.value;
                if (secondHidden) secondHidden.value = this.value;
            });

            // Inicializar valor del textarea desde los hidden existentes
            const pairIndex = parseInt(textarea.getAttribute('data-pair-index'), 10);
            if (!isNaN(pairIndex)) {
                const firstIndex = (pairIndex - 1) * 2 + 1;
                const secondIndex = firstIndex + 1;
                const firstHidden = document.getElementById(`comment_for_image_${firstIndex}`);
                const secondHidden = document.getElementById(`comment_for_image_${secondIndex}`);
                if (firstHidden && firstHidden.value) {
                    textarea.value = firstHidden.value;
                } else if (secondHidden && secondHidden.value) {
                    textarea.value = secondHidden.value;
                }
            }
        });
    });


    /*llenado de campos vacios*/
    document.addEventListener("DOMContentLoaded", function () {
        const inputFields = document.querySelectorAll(".default-input");

        inputFields.forEach(input => {
            input.addEventListener("input", function () {
                const column = parseInt(input.getAttribute("data-column")); // Aseguramos que sea número
                if (isNaN(column)) return; // Evitar errores si no es válido

                //document.querySelectorAll("#dynamicTable tbody tr:not(.titulo-row)").forEach(row => {
                    document.querySelectorAll("#dynamicTable tbody tr:not(.titulo-row):not(.long-row)").forEach(row => {
                    const cellInputs = row.querySelectorAll("td input");
                    const cellInput = cellInputs[column - 0]; // Ajustar al índice base 0
                    if (cellInput) {
                        cellInput.value = input.value;
                    }
                });
            });
        });
    });

    /*Pre-Rellenado del formulario */
    document.addEventListener("DOMContentLoaded", function () {
    const formularios = ["FOR-01-PRO-INS-03", "FOR-01-PRO-INS-04", "FOR-01-PRO-INS-05", "FOR-01-PRO-INS-06", "FOR-01-PRO-INS-07", "FOR-01-PRO-INS-08", "FOR-01-PRO-INS-09", "FOR-01-PRO-INS-10", "FOR-01-PRO-INS-11", "FOR-01-PRO-INS-12", "FOR-01-PRO-INS-13","FOR-01-PRO-INS-14", "FOR-01-PRO-INS-15", "FOR-01-PRO-INS-16", "FOR-01-PRO-INS-17", "FOR-01-PRO-INS-18", "FOR-01-PRO-INS-19","FOR-01-PRO-INS-20","FOR-01-PRO-INS-21","FOR-01-PRO-INS-22", "FOR-02-PRO-INS-02", "FOR-02-PRO-INS-04", "FOR-02-PRO-INS-10", "FOR-02-PRO-INS-15", "FOR-03-PRO-INS-15"];

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
                        if (input.type === "date") {
                            // poner fecha actual
                            input.value = new Date().toISOString().split('T')[0];
                        } else if (input.type !== "file") {
                            input.value = "---";
                        }
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
    //numFirmasLocal ? numFirmasSelect.value = numFirmasLocal : numFirmasSelect.value = '1'; // Valor por defecto si no hay en localStorage
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