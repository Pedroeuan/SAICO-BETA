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

/* Imágenes - Versión Edición para estructura existente */
let cropper;
let currentInput;
let imagenesEliminadas = [];

// Funciones del modal (iguales)
document.getElementById('rotateLeftBtn').addEventListener('click', function () {
    if (cropper) cropper.rotate(-90);
});

document.getElementById('rotateRightBtn').addEventListener('click', function () {
    if (cropper) cropper.rotate(90);
});

document.getElementById('cancelBtn').addEventListener('click', function () {
    $('#cropperModal').modal('hide');
});

document.getElementById('saveWithoutCropBtn').addEventListener('click', function () {
    try {
        const imageData = cropper.getImageData();
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        if (Math.abs(cropper.getData().rotate) % 180 === 90) {
            canvas.width = imageData.naturalHeight;
            canvas.height = imageData.naturalWidth;
        } else {
            canvas.width = imageData.naturalWidth;
            canvas.height = imageData.naturalHeight;
        }

        ctx.translate(canvas.width / 2, canvas.height / 2);
        ctx.rotate((imageData.rotate * Math.PI) / 180);
        ctx.drawImage(
            cropper.element,
            -imageData.naturalWidth / 2,
            -imageData.naturalHeight / 2,
            imageData.naturalWidth,
            imageData.naturalHeight
        );

        const base64data = canvas.toDataURL();
        const previewDiv = document.getElementById(`${currentInput.id}-preview`);
        if (previewDiv) {
            previewDiv.innerHTML = `
                <img src="${base64data}" class="img-fluid img-thumbnail" />
                <span class="badge bg-success">¡Guardado!</span>
            `;
        }
        document.getElementById(`${currentInput.id}-base64`).value = base64data;

        $('#cropperModal').modal('hide');
    } catch (error) {
        console.error('Error al guardar la imagen sin recortar:', error);
    }
});

document.getElementById('cropImageBtn').addEventListener('click', function () {
    if (cropper && currentInput) {
        const croppedCanvas = cropper.getCroppedCanvas();
        if (croppedCanvas) {
            const base64data = croppedCanvas.toDataURL();
            const previewDiv = document.getElementById(`${currentInput.id}-preview`);
            if (previewDiv) {
                previewDiv.innerHTML = `
                    <img src="${base64data}" class="img-fluid img-thumbnail" />
                    <span class="badge bg-success">¡Recortado!</span>
                `;
            }
            document.getElementById(`${currentInput.id}-base64`).value = base64data;
        }
    }
    $('#cropperModal').modal('hide');
});

$('#cropperModal').on('hidden.bs.modal', function () {
    if (cropper) cropper.destroy();
});

// Eliminar imagen existente
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-existing-image')) {
        const imageId = e.target.getAttribute('data-index');
        const imageContainer = document.querySelector(`.existing-image[data-image-id="${imageId}"]`);
        
        if (imageContainer && confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
            // Agregar a la lista de eliminadas
            imagenesEliminadas.push(imageId);
            document.getElementById('deletedImages').value = imagenesEliminadas.join(',');
            
            // Verificar si es parte de un grupo
            const groupContainer = imageContainer.closest('.existing-image-group');
            if (groupContainer) {
                const groupIds = groupContainer.getAttribute('data-group-ids').split(',');
                // Si solo queda una imagen en el grupo, convertirlo a individual
                const remainingImages = groupContainer.querySelectorAll('.existing-image:not([style*="display: none"])');
                if (remainingImages.length === 1) {
                    convertGroupToIndividual(groupContainer);
                }
            }
            
            // Ocultar el contenedor
            imageContainer.style.display = 'none';
            
            // Recalcular agrupaciones
            recalcularAgrupacionesCompletas();
        }
    }
});

// Convertir grupo a individual cuando solo queda una imagen
function convertGroupToIndividual(groupContainer) {
    const remainingImage = groupContainer.querySelector('.existing-image:not([style*="display: none"])');
    const groupComment = groupContainer.querySelector('.comentario-grupo-existente');
    
    if (remainingImage && groupComment) {
        // Mover la imagen fuera del grupo
        groupContainer.parentNode.insertBefore(remainingImage, groupContainer);
        
        // Mostrar comentario individual
        const individualComment = remainingImage.querySelector('.comment-individual, textarea[name^="comentarios_existentes"]');
        if (individualComment) {
            individualComment.classList.remove('d-none');
            individualComment.value = groupComment.value;
        }
        
        // Eliminar el grupo
        groupContainer.remove();
    }
}

// Generar campos de imágenes NUEVAS
document.addEventListener("DOMContentLoaded", function () {
    const imageCountSelect = document.getElementById('imageCount');
    const container = document.getElementById('imageFieldsContainer');
    const cropperImage = document.getElementById('cropperImage');

    // Inicializar contador basado en imágenes existentes
    const existingImages = document.querySelectorAll('.existing-image:not([style*="display: none"])');
    let nextImageId = existingImages.length > 0 ? 
        Math.max(...Array.from(existingImages).map(img => parseInt(img.getAttribute('data-image-id')))) + 1 : 1;

    // Cargar selección guardada en localStorage si existe
    const formId = document.querySelector('form')?.id || 'editForm';
    const savedImageCount = localStorage.getItem(`${formId}_imageCount`);
    if (savedImageCount) {
        imageCountSelect.value = savedImageCount;
        generateImageFields(parseInt(savedImageCount));
    }

    imageCountSelect.addEventListener('change', function () {
        const count = parseInt(this.value);
        localStorage.setItem(`${formId}_imageCount`, count);
        generateImageFields(count);
    });

    function generateImageFields(count) {
        container.innerHTML = '';
        for (let i = 1; i <= count; i++) {
            const globalIndex = nextImageId++;
            const col = document.createElement('div');
            col.classList.add('col-sm-6', 'mb-3', 'new-image');
            col.setAttribute('id', `image-container-new-${globalIndex}`);
            col.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <label for="imageNew${globalIndex}">Imagen por Subir ${globalIndex}:</label>
                        <input type="file" class="form-control image-input" id="imageNew${globalIndex}" accept="image/*">
                        
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="imagen_hoja_nueva[]" id="imagenHojaNew${globalIndex}" value="${globalIndex}">
                            <label class="form-check-label" for="imagenHojaNew${globalIndex}">
                                Imagen en una hoja
                            </label>
                        </div>

                        <div class="image-preview mt-2" id="imageNew${globalIndex}-preview"></div>
                        <textarea class="form-control mt-2" name="comments_nuevas[]" id="commentNew${globalIndex}" placeholder="Comentario"></textarea>
                        <input type="hidden" name="images_base64_nuevas[]" id="imageNew${globalIndex}-base64">
                        <button type="button" class="btn btn-danger mt-2 remove-new-image" data-index="${globalIndex}">
                            <i class="fas fa-trash-alt"></i> Eliminar
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(col);
        }

        // Agregar event listeners a los nuevos campos
        attachEventListenersToNewFields();
        recalcularAgrupacionesCompletas();
    }

    function attachEventListenersToNewFields() {
        // Eliminar imágenes nuevas
        document.querySelectorAll('.remove-new-image').forEach(button => {
            button.addEventListener('click', function () {
                const index = this.getAttribute('data-index');
                const fieldToRemove = document.getElementById(`image-container-new-${index}`);
                if (fieldToRemove && confirm('¿Estás seguro de que quieres eliminar este campo de imagen?')) {
                    fieldToRemove.remove();
                    imageCountSelect.value = parseInt(imageCountSelect.value) - 1 || 0;
                    localStorage.setItem(`${formId}_imageCount`, imageCountSelect.value);
                    recalcularAgrupacionesCompletas();
                }
            });
        });

        // Subir nuevas imágenes
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

    // Recalcular agrupaciones considerando imágenes existentes y nuevas
    function recalcularAgrupacionesCompletas() {
        const todasLasImagenes = Array.from(document.querySelectorAll('.existing-image:not([style*="display: none"]), .new-image'));

        // Resetear estilos
        todasLasImagenes.forEach(div => {
            div.style.border = '';
            const comentarioIndividual = div.querySelector('.comment-individual, textarea[name^="comentarios_existentes"], textarea[name="comments_nuevas[]"]');
            if (comentarioIndividual) comentarioIndividual.classList.remove('d-none');
        });

        // Limpiar comentarios grupales anteriores de NUEVAS imágenes
        document.querySelectorAll('.comentario-grupo-nuevo').forEach(el => el.remove());
        document.querySelectorAll('input[name="comentario_grupo_ids_nuevo[]"]').forEach(el => el.remove());

        // Obtener todas las seleccionadas
        const seleccionadas = Array.from(document.querySelectorAll(
            'input[name="imagen_hoja_existente[]"]:checked, input[name="imagen_hoja_nueva[]"]:checked'
        )).map(chk => parseInt(chk.value));

        // Marcar seleccionadas con borde verde
        seleccionadas.forEach(id => {
            const div = document.querySelector(`[data-image-id="${id}"], #image-container-new-${id}`);
            if (div) {
                div.style.border = '3px solid #28a745';
            }
        });

        // Filtrar imágenes no seleccionadas
        const restantes = todasLasImagenes.filter(div => {
            let idNum;
            if (div.classList.contains('existing-image')) {
                idNum = parseInt(div.getAttribute('data-image-id'));
            } else {
                idNum = parseInt(div.id.split('-').pop());
            }
            return !seleccionadas.includes(idNum);
        });

        // Agrupar de 2 en 2 las restantes (solo nuevas)
        const restantesNuevas = restantes.filter(div => div.classList.contains('new-image'));
        for (let i = 0; i < restantesNuevas.length; i += 2) {
            const par = restantesNuevas.slice(i, i + 2);

            // Ocultar comentarios individuales y marcar con borde azul
            par.forEach(div => {
                const comentario = div.querySelector('textarea[name="comments_nuevas[]"]');
                if (comentario) comentario.classList.add('d-none');
                div.style.border = '2px dashed #007bff';
            });

            // Obtener IDs
            const nums = par.map(div => div.id.split('-').pop());

            const textoGrupo = nums.length === 2
                ? `Comentario para imágenes ${nums[0]} y ${nums[1]}`
                : `Comentario para imagen ${nums[0]}`;

            // Crear textarea para comentario grupal
            const comentarioGrupo = document.createElement('textarea');
            comentarioGrupo.classList.add('form-control', 'mt-2', 'comentario-grupo-nuevo');
            comentarioGrupo.name = 'comentario_grupo_nuevo[]';
            comentarioGrupo.placeholder = textoGrupo;

            // Input oculto con los IDs
            const inputIds = document.createElement('input');
            inputIds.type = 'hidden';
            inputIds.name = 'comentario_grupo_ids_nuevo[]';
            inputIds.value = nums.join(',');

            // Insertar después del último div del grupo
            const ultimo = par[par.length - 1];
            ultimo.insertAdjacentElement('afterend', comentarioGrupo);
            ultimo.insertAdjacentElement('afterend', inputIds);
        }
    }

    // Escuchar cambios en checkboxes
    document.addEventListener('change', function(e) {
        if (e.target && (
            e.target.matches('input[name="imagen_hoja_existente[]"]') || 
            e.target.matches('input[name="imagen_hoja_nueva[]"]')
        )) {
            recalcularAgrupacionesCompletas();
        }
    });

    // Limpiar localStorage al enviar el formulario
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            localStorage.removeItem(`${formId}_imageCount`);
        });
    }

    // Inicializar agrupaciones al cargar
    recalcularAgrupacionesCompletas();
});

    /*Juntas-Resultados */
    function updateRowNumbers() {
        let count = 0;
        $('#dynamicTable tbody tr').each(function () {
            if (!$(this).hasClass('titulo-row')) {
                count++;
                $(this).find('td:first').html(`${count} <input type="hidden" value="${count}">`);
            }
        });
        rowCountGlobal = count;
    }

    // Función para actualizar los títulos en el campo oculto
        function updateTitulos() {
            var titulos = [];
            // Recolectar todos los títulos en el array
            $('.titulo-row input[type="text"]').each(function() {
                titulos.push($(this).val());
            });

            // Asignar los títulos al campo oculto
            $('#titulos_hidden').val(JSON.stringify(titulos)); // Almacena los títulos como un JSON
        }


        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.titulo-row input[name="titulos[]"]').forEach(function(inputTitulo) {
                inputTitulo.addEventListener('input', function() {
                    const row = inputTitulo.closest('tr');
                    const oldTituloKey = row.getAttribute('data-titulo')?.replace('titulo_', '');

                    // Generar nuevo tituloKey con guiones bajos
                    const nuevoTituloRaw = inputTitulo.value.trim() || 'sin_titulo';
                    const nuevoTituloKey = nuevoTituloRaw.replace(/\s+/g, '_');
                    const nuevoDataTitulo = `titulo_${nuevoTituloKey}`;

                    // Actualizar el data-titulo del row del título
                    row.setAttribute('data-titulo', nuevoDataTitulo);

                    // Actualizar todos los <tr> que tenían el antiguo data-titulo relacionado
                    document.querySelectorAll(`tr[data-titulo="${oldTituloKey}"]`).forEach(function(rowResultado) {
                        rowResultado.setAttribute('data-titulo', nuevoTituloKey);

                        // También puedes actualizar los name de los inputs si lo necesitas:
                        rowResultado.querySelectorAll('input').forEach(function(input) {
                            input.name = input.name.replace(oldTituloKey, nuevoTituloKey);
                        });
                    });
                });
            });
        });


    // Evento para eliminar un título
        $('#dynamicTable').on('click', '.btnEliminarTitulo', function () {
            let tituloRow = $(this).closest('tr');
            let tituloId = tituloRow.data('titulo');
            
            // Eliminar la fila del título
            tituloRow.remove();
            
            // Eliminar todas las filas que tengan el mismo data-titulo
            $(`#dynamicTable tbody tr[data-titulo="${tituloId}"]`).remove();

            updateRowNumbers(); // Si quieres actualizar el contador global
        });

        /*Cambia el data-titulo y guarda en sesionstorage */
        $(document).on('input', '.titulo-row input[name="titulos[]"]', function () {
            const input = $(this);
            const text = input.val().trim();
            const safeTitulo = text !== '' ? text.replace(/\s+/g, '_').toLowerCase() : 'sin_titulo';

            const tr = input.closest('tr');
            const oldTitulo = tr.attr('data-titulo');

            // Cambia el data-titulo del título
            tr.attr('data-titulo', safeTitulo);

            // Cambia el data-titulo de las filas asociadas a este título
            $(`#dynamicTable tbody tr[data-titulo="${oldTitulo}"]:not(.titulo-row)`).each(function () {
                $(this).attr('data-titulo', safeTitulo);

                // Actualiza solo el valor entre corchetes en los names
                $(this).find('input').each(function () {
                    let name = $(this).attr('name');
                    if (name) {
                        // Solo reemplaza el valor entre corchetes que coincide exactamente con oldTitulo
                        name = name.replace(/\[([^\]]+)\]/, function(match, p1) 
                        {
                            return p1 === oldTitulo ? `[${safeTitulo}]` : match;
                        });
                        //name = name.replace(new RegExp(`\\[${oldTitulo}\\]`, 'g'), `[${safeTitulo}]`);
                        $(this).attr('name', name);
                    }
                });
            });

            updateTitulos();
            saveData();
        });

        $('#dynamicTable').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
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

                document.querySelectorAll("#dynamicTable tbody tr:not(.titulo-row)").forEach(row => {
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

        /* selects.forEach(select => {
            const stored = localStorage.getItem(`${formId}_${select.name}`);
            console.log(''+stored);

            if (stored !== null) select.value = stored;

            select.addEventListener("change", () => {
                localStorage.setItem(`${formId}_${select.name}`, select.value);
            });
        });*/

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