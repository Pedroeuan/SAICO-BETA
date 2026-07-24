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

    /* Imágenes Al seleccionar numero de imagenes a subir*/
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

    // Botón: Recortar y Guardar
    document.getElementById('cropImageBtn').addEventListener('click', function () {
        if (cropper && currentInput) {
            const croppedCanvas = cropper.getCroppedCanvas();
            if (croppedCanvas) {
                const base64data = croppedCanvas.toDataURL();

                // Actualizar la vista previa de la imagen
                const previewDiv = currentInput.closest('.form-group').querySelector('.image-preview');
                previewDiv.innerHTML = `
                    <img src="${base64data}" class="img-fluid img-thumbnail" />
                    <span class="badge bg-success">¡Recortado!</span>
                `;

                // Actualizar el campo oculto con la imagen en base64
                const base64Input = currentInput.closest('.form-group').querySelector('input[type="hidden"][name^="images_base64"]');
                if (base64Input) {
                    base64Input.value = base64data;
                }
            }
            $('#cropperModal').modal('hide');
        } else {
            console.error('Cropper o currentInput no están inicializados.');
        }
    });

    // Botón: Guardar Sin Recortar
    document.getElementById('saveWithoutCropBtn').addEventListener('click', function () {
        if (cropper && currentInput) {
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

                // Actualizar la vista previa de la imagen
                const previewDiv = currentInput.closest('.form-group').querySelector('.image-preview');
                previewDiv.innerHTML = `
                    <img src="${base64data}" class="img-fluid img-thumbnail" />
                    <span class="badge bg-success">¡Guardado!</span>
                `;

                // Actualizar el campo oculto con la imagen en base64
                const base64Input = currentInput.closest('.form-group').querySelector('input[type="hidden"][name^="images_base64"]');
                if (base64Input) {
                    base64Input.value = base64data;
                }

                // Cerrar el modal
                $('#cropperModal').modal('hide');
            } catch (error) {
                console.error('Error al guardar la imagen sin recortar:', error);
            }
        } else {
            console.error('Cropper o currentInput no están inicializados.');
        }
    });

    // Destruir Cropper al cerrar el modal
    $('#cropperModal').on('hidden.bs.modal', function () {
        if (cropper) cropper.destroy();
    });

    function bindImagenHojaCheckboxes() {
        document.querySelectorAll('.imagen-hoja-checkbox').forEach(cb => {
            const index = cb.dataset.index;
            const hidden = document.getElementById(`imagenHojaValue${index}`);

            if (hidden) {
                hidden.value = cb.checked ? 1 : 0;
            }

            if (cb.dataset.imagenHojaBound === '1') {
                return;
            }

            cb.dataset.imagenHojaBound = '1';
            cb.addEventListener('change', function () {
                const hiddenField = document.getElementById(`imagenHojaValue${index}`);
                if (hiddenField) {
                    hiddenField.value = this.checked ? 1 : 0;
                }
            });
        });
    }

    function bindFotosDisparo() {
        document.querySelectorAll('.foto-disparo-checkbox').forEach(cb => {
            const index = cb.dataset.index;
            const hidden = document.getElementById(`esDisparoValue${index}`);
            const select = document.getElementById(`numeroDisparo${index}`);
            const container = document.getElementById(`numeroDisparoContainer${index}`);

            if (!hidden || !select || !container) return;

            const actualizar = function () {
                hidden.value = cb.checked ? 1 : 0;
                container.classList.toggle('d-none', !cb.checked);
                if (!cb.checked) select.value = '';
            };

            actualizar();
            if (cb.dataset.disparoBound !== '1') {
                cb.dataset.disparoBound = '1';
                cb.addEventListener('change', actualizar);
            }
        });
    }


    // Generar campos de imágenes
    document.addEventListener("DOMContentLoaded", function () {
        const imageCountSelect = document.getElementById('imageCount');
        const container = document.getElementById('imageFieldsContainer');
        const cropperImage = document.getElementById('cropperImage');

        bindImagenHojaCheckboxes();
        bindFotosDisparo();

        // Ambos formatos aplican la misma regla: un disparo activo contiene exactamente dos imágenes.
        const formularioDisparos = document.getElementById('FOR-PIMP-06_B_01')
            || document.getElementById('FOR-PIMP-04_03');
        if (formularioDisparos && formularioDisparos.dataset.validacionDisparosBound !== '1') {
            formularioDisparos.dataset.validacionDisparosBound = '1';
            formularioDisparos.addEventListener('submit', function (event) {
                const conteoDisparos = { 1: 0, 2: 0, 3: 0 };
                let mensajeError = '';

                formularioDisparos.querySelectorAll('.foto-disparo-checkbox').forEach(function (checkbox) {
                    const index = checkbox.dataset.index;
                    const contenedor = document.getElementById(`image-container-${index}`);
                    const eliminado = document.getElementById(`deleted_image_${index}`);

                    if (!contenedor || contenedor.style.display === 'none' || (eliminado && eliminado.value !== '')) {
                        return;
                    }

                    if (!checkbox.checked) {
                        return;
                    }

                    const imagenExistente = formularioDisparos.querySelector(`input[name="existing_images[${index}]"]`);
                    const imagenBase64 = formularioDisparos.querySelector(`input[name="images_base64[${index}]"]`);
                    if (!imagenExistente && (!imagenBase64 || !imagenBase64.value)) {
                        mensajeError = `La imagen ${Number(index) + 1} todavía no se ha guardado desde el recortador.`;
                        return;
                    }

                    const selector = document.getElementById(`numeroDisparo${index}`);
                    const numero = selector ? selector.value : '';
                    if (!Object.prototype.hasOwnProperty.call(conteoDisparos, numero)) {
                        mensajeError = `Selecciona el disparo de la imagen ${Number(index) + 1}.`;
                        return;
                    }

                    conteoDisparos[numero]++;
                });

                if (!mensajeError) {
                    Object.keys(conteoDisparos).some(function (numero) {
                        const cantidad = conteoDisparos[numero];
                        if (cantidad !== 0 && cantidad !== 2) {
                            mensajeError = `El ${numero}° disparo tiene ${cantidad} fotografía${cantidad === 1 ? '' : 's'}; debe tener exactamente dos.`;
                            return true;
                        }
                        return false;
                    });
                }

                if (mensajeError) {
                    event.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Revisa los disparos',
                            text: mensajeError,
                        });
                    } else {
                        alert(mensajeError);
                    }
                }
            });
        }

        if (!imageCountSelect || !container) {
            return;
        }


        imageCountSelect.addEventListener('change', function () {
            const count = parseInt(this.value);
            //localStorage.setItem('imageCount', count);
            generateImageFields(count);
        });

            function generateImageFields(count) {
            container.innerHTML = '';
            // Las capacidades se detectan por formulario para no acoplar una vista con otra.
            const permiteDisparos = document.getElementById('FOR-PIMP-06_B_01') !== null
                || document.getElementById('FOR-PIMP-04_03') !== null;
            const usaLayoutManual = document.getElementById('FOR-PIMP-04_03') !== null;

            // Calcular desde qué índice empezar (considerando imágenes del servidor)
            const existingCount = document.querySelectorAll('[id^="image-container-"]').length;
            for (let i = 0; i < count; i++) {
                const index = existingCount + i;
                const displayIndex = index + 1;
                const col = document.createElement('div');
                col.classList.add('col-sm-6');
                col.setAttribute('id', `image-container-${index}`);
                col.innerHTML = `
                    <div class="form-group">
                        <label for="image${index}">Imagen por Subir ${displayIndex}:</label>
                        <input type="file" class="form-control image-input" id="image${index}" accept="image/*">

                        ${!usaLayoutManual ? `<div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input imagen-hoja-checkbox" data-index="${index}" id="imagenHoja${index}">
                            <label class="form-check-label" for="imagenHoja${index}">
                                Imagen en una hoja
                            </label>
                        </div>
                        <input type="hidden" name="imagen_hoja[${index}]" id="imagenHojaValue${index}" value="0">` : ''}
                        ${permiteDisparos ? `
                        <div class="form-check mt-2">
                            <input type="hidden" name="es_disparo[${index}]" id="esDisparoValue${index}" value="0">
                            <input type="checkbox" class="form-check-input foto-disparo-checkbox" data-index="${index}" id="esDisparo${index}">
                            <label class="form-check-label" for="esDisparo${index}">Esta imagen pertenece a un disparo</label>
                        </div>
                        <div class="mt-2 d-none numero-disparo-container" id="numeroDisparoContainer${index}">
                            <label for="numeroDisparo${index}">Asignar al disparo:</label>
                            <select class="form-control" name="numero_disparo[${index}]" id="numeroDisparo${index}">
                                <option value="">Selecciona un disparo</option>
                                <option value="1">1er. disparo</option>
                                <option value="2">2do. disparo</option>
                                <option value="3">3er. disparo</option>
                            </select>
                            <small class="text-muted">Cada disparo se completa con dos fotografías.</small>
                        </div>` : ''}
                        <div class="image-preview mt-2" id="image${index}-preview"></div>
                        <textarea class="form-control mt-2" name="comments[${index}]" id="comment${index}" placeholder="Comentario"></textarea>
                        <input type="hidden" name="images_base64[${index}]" id="image${index}-base64">
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

            bindImagenHojaCheckboxes();
            bindFotosDisparo();
        }

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

    /*Juntas-Resultados */
    function updateRowNumbers() {
        let count = 0;
        $('#dynamicTable tbody tr').each(function () {
            //if (!$(this).hasClass('titulo-row')) {
            if (!$(this).hasClass('titulo-row') && !$(this).hasClass('long-row')) {
                count++;
                $(this).find('td:first').html(`${count} <input type="hidden" value="${count}">`);
            }
        });
        rowCountGlobal = count;
    }
        // Función correcta para serializar títulos como [{id,text}]
        function updateTitulos() {
        var titulos = [];
        $('.titulo-row').each(function() {
            const id = $(this).data('titulo');
            const $row = $(this);
            const text =
                $row.find('.titulo-text').first().val() ||
                $row.find('input[name="titulos[]"]').first().val() ||
                $row.find('input[name^="titulos_text["]').first().val() ||
                '';
            titulos.push({ id: id, text: text });
        });
        $('#titulos_hidden').val(JSON.stringify(titulos));
        }

    // Evento para eliminar un título
        $('#dynamicTable').on('click', '.btnEliminarTitulo', function () {
            let tituloRow = $(this).closest('tr');
            let tituloId = tituloRow.data('titulo');
            
            // Eliminar la fila del título
            tituloRow.remove();
            if (typeof verificarYAgregarLongitud === 'function') {
                verificarYAgregarLongitud();
            }
            // Eliminar todas las filas que tengan el mismo data-titulo
            $(`#dynamicTable tbody tr[data-titulo="${tituloId}"]`).remove();

            updateRowNumbers(); // Si quieres actualizar el contador global
        });

        /*Cambia el data-titulo y guarda en sesionstorage */
        $(document).on('input', '.titulo-row input[name="titulos[]"]', function () {
            updateTitulos();
        });

        document.addEventListener('DOMContentLoaded', function () {
            if (document.getElementById('titulos_hidden')) {
                updateTitulos();
            }
        });

        document.addEventListener('submit', function () {
            if (document.getElementById('titulos_hidden')) {
                updateTitulos();
            }
        });

        $('#dynamicTable').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
            if (typeof verificarYAgregarLongitud === 'function') {
                verificarYAgregarLongitud();
            }
            updateRowNumbers();
        });

        $('#preFillBtn').click(function() {
            $('#dynamicTable tbody tr').each(function() {
                $(this).find('input').each(function() {
                    if ($(this).val() === '') {
                        $(this).val('----');
                    }
                });
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
    const formularios = ["FOR-PINS-04_01", "FOR-PINS-05_01", "FOR-PINS-06_01", "FOR-PINS-07_01", "FOR-PINS-08_01", "FOR-PINS-09_01", "FOR-PINS-10_01", "FOR-PINS-11_01",
        "FOR-PINS-12_01", "FOR-PINS-13_01", "FOR-PINS-14_01", "FOR-PINS-15_01", "FOR-PINS-16_01", "FOR-PINS-17_01", "FOR-PINS-18_01", "FOR-PINS-19_01", "FOR-PINS-20_01",
        "FOR-PINS-21_01", "FOR-PINS-22_01", "FOR-PINS-22_01", "FOR-PINS-23_01", "FOR-PINS-24_01", "FOR-PINS-25_01", "FOR-PINS-03_02", "FOR-PINS-05_02", "FOR-PINS-11_02",
        "FOR-PINS-17_01_01", "FOR-03-PRO-INS-15", "FOR-PIMP-04_02", "FOR-PIMP-04_03"
    ];
    formularios.forEach(formId => {
        const form = document.getElementById(formId);
        if (!form) return; // Saltar si no existe

        const inputs = form.querySelectorAll(".inputForm");
        const textareas = form.querySelectorAll("textarea");

        // Restaurar valores desde localStorage
        inputs.forEach(input => {
            // Nunca se intenta restaurar un input file por seguridad del navegador.
            if (input.type === "file") return;

            const stored = localStorage.getItem(`${formId}_${input.name}`);
            if (stored !== null && input.value.trim() === "") input.value = stored;

            input.addEventListener("input", () => {
                localStorage.setItem(`${formId}_${input.name}`, input.value);
            });
        });


        textareas.forEach(textarea => {
            // Verificar si es textarea de comentarios y usar id cuando exista
            if ((textarea.name === "comments[]" || textarea.name.startsWith("comments[")) && textarea.id) {
                // Guardar usando id como clave
                const stored = localStorage.getItem(`${formId}_${textarea.id}`);
                if (stored !== null && textarea.value.trim() === "") textarea.value = stored;

                textarea.addEventListener("input", () => {
                    localStorage.setItem(`${formId}_${textarea.id}`, textarea.value);
                });
            } else {
                // Para otros textareas (que no son comentarios), usar name
                const stored = localStorage.getItem(`${formId}_${textarea.name}`);
                if (stored !== null && textarea.value.trim() === "") textarea.value = stored;

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
                    // Conserva campos técnicos, de cálculo y valores ya capturados al usar autorrelleno.
                    if (input.type === "hidden" || input.type === "file" || input.type === "number"
                        || input.readOnly || input.tagName === "SELECT"
                        || input.getAttribute("inputmode") === "decimal") return;

                    if (input.value.trim() === "") {
                        if (input.type === "date") {
                            // poner fecha actual
                            input.value = new Date().toISOString().split('T')[0];
                        } else {
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
    const numFirmasSelect = document.getElementById('numFirmas');
    const firmas1 = document.getElementById('firmas1');
    const firmas2 = document.getElementById('firmas2');
    const firmas3 = document.getElementById('firmas3');
    const firmas4 = document.getElementById('firmas4');

    if (!numFirmasSelect || !firmas1 || !firmas2 || !firmas3 || !firmas4) return;

    function mostrarFirmas(valor) {
        firmas1.style.display = valor == '1' ? 'block' : 'none';
        firmas2.style.display = valor == '2' ? 'block' : 'none';
        firmas3.style.display = valor == '3' ? 'block' : 'none';
        firmas4.style.display = valor == '4' ? 'block' : 'none';
    }

    numFirmasSelect.addEventListener('change', function() {
        mostrarFirmas(this.value);
    });

    mostrarFirmas(numFirmasSelect.value || '1');
    });

        /*SELECT DE Firmas*/
        function actualizarTecnicos() {
            var selectedOption = $('#tecnicosSelect').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var id = selectedOption.data('id') || '';
            var name = selectedOption.data('name') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#IDTECNICO').val($('#tecnicosSelect').val() || '');
            $('#NOMBRE_TECNICO').val(name);
        }

            // Toma el formulario real del reporte; la página AdminLTE puede contener formularios auxiliares.
            const reporteForm = document.getElementById('FOR-PIMP-04_03') || document.querySelector('form[id]');
            const reporteFormId = reporteForm ? reporteForm.id : '';
            const selectedOptionLocalT = reporteFormId ? localStorage.getItem(reporteFormId+'_Tecnicos') : null;
            selectedOptionLocalT != null ?  ($('#tecnicosSelect').val(selectedOptionLocalT),actualizarTecnicos()):"";

            // Evento cuando se cambia la selección en el select
            $('#tecnicosSelect').on('change', function() {
                actualizarTecnicos();
            });

            /*2*/
            function actualizarTecnicos2() {
            var selectedOption = $('#tecnicosSelect2').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var id = selectedOption.data('id') || '';
            var name = selectedOption.data('name') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#IDTECNICO2').val($('#tecnicosSelect2').val() || '');
            $('#NOMBRE_TECNICO2').val(name);
        }

            const selectedOptionLocalT2 = reporteFormId ? localStorage.getItem(reporteFormId+'_Tecnicos2') : null;
            selectedOptionLocalT2 != null ?  ($('#tecnicosSelect2').val(selectedOptionLocalT2),actualizarTecnicos2()):"";

            // Evento cuando se cambia la selección en el select
            $('#tecnicosSelect2').on('change', function() {
                actualizarTecnicos2();
            });

            /*3*/
            function actualizarTecnicos3() {
            var selectedOption = $('#tecnicosSelect3').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var id = selectedOption.data('id') || '';
            var name = selectedOption.data('name') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#IDTECNICO3').val($('#tecnicosSelect3').val() || '');
            $('#NOMBRE_TECNICO3').val(name);
        }

            const selectedOptionLocalT3 = reporteFormId ? localStorage.getItem(reporteFormId+'_Tecnicos3') : null;
            selectedOptionLocalT3 != null ?  ($('#tecnicosSelect3').val(selectedOptionLocalT3),actualizarTecnicos3()):"";

            // Evento cuando se cambia la selección en el select
            $('#tecnicosSelect3').on('change', function() {
                actualizarTecnicos3();
            });

            /*4*/
            function actualizarTecnicos4() {
            var selectedOption = $('#tecnicosSelect4').find('option:selected');

            // Extraer los datos de los atributos "data-"
            var id = selectedOption.data('id') || '';
            var name = selectedOption.data('name') || '';

            // Rellenar los inputs con los valores obtenidos
            $('#IDTECNICO4').val($('#tecnicosSelect4').val() || '');
            $('#NOMBRE_TECNICO4').val(name);
        }

            const selectedOptionLocalT4 = reporteFormId ? localStorage.getItem(reporteFormId+'_Tecnicos4') : null;
            selectedOptionLocalT4 != null ?  ($('#tecnicosSelect4').val(selectedOptionLocalT4),actualizarTecnicos4()):"";

            // Evento cuando se cambia la selección en el select
            $('#tecnicosSelect4').on('change', function() {
                actualizarTecnicos4();
            });

            /*Validar seleccion del tecnico 
            function actualizarValidacion() {

            document.getElementById('tecnicosSelect').removeAttribute('required');
            document.getElementById('tecnicosSelect2').removeAttribute('required');
            document.getElementById('tecnicosSelect3').removeAttribute('required');
            document.getElementById('tecnicosSelect4').removeAttribute('required');

            let numFirmas = document.getElementById('numFirmas').value;

            if (numFirmas == '1') {
                document.getElementById('tecnicosSelect').setAttribute('required', true);
            }

            if (numFirmas == '2') {
                document.getElementById('tecnicosSelect2').setAttribute('required', true);
            }

            if (numFirmas == '3') {
                document.getElementById('tecnicosSelect3').setAttribute('required', true);
            }

            if (numFirmas == '4') {
                document.getElementById('tecnicosSelect4').setAttribute('required', true);
            }
        }

        document.getElementById('numFirmas').addEventListener('change', actualizarValidacion);

        actualizarValidacion();*/
