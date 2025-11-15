
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
                previewDiv.innerHTML = `
                    <img src="${base64data}" class="img-fluid img-thumbnail" />
                    <span class="badge bg-success">¡Guardado!</span>
                `;
                document.getElementById(`${currentInput.id}-base64`).value = base64data;

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

            const selImgCountLocal = localStorage.getItem(document.querySelectorAll("form")[1]?.id + '_imageCount');
            if (selImgCountLocal != null) {
                $('#imageCountSelect').val(selImgCountLocal);
                generateImageFields(selImgCountLocal);
                const msgImgNoSave = document.getElementById('msgImgNoSave');
                if (msgImgNoSave) msgImgNoSave.classList.remove('d-none');
            }

            imageCountSelect.addEventListener('change', function () {
                const count = parseInt(this.value);
                generateImageFields(count);
            });

            function generateImageFields(count) {
                container.innerHTML = '';
                for (let i = 1; i <= count; i++) {
                    const col = document.createElement('div');
                    col.classList.add('col-sm-6');
                    col.setAttribute('id', `image-container-${i}`);
                    col.innerHTML = `
                        <div class="form-group">
                            <label for="image${i}">Imagen por Subir ${i}:</label>
                            <input type="file" class="form-control image-input" id="image${i}" accept="image/*">
                            
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="imagen_hoja[]" id="imagenHoja${i}" value="${i}">
                                <label class="form-check-label" for="imagenHoja${i}">
                                    Imagen en una hoja
                                </label>
                            </div>

                            <div class="image-preview mt-2" id="image${i}-preview"></div>
                            <textarea class="form-control mt-2" name="comments[]" id="comment${i}" placeholder="Comentario"></textarea>
                            <input type="hidden" name="images_base64[]" id="image${i}-base64">
                            <button type="button" class="btn btn-danger mt-2 remove-image" data-index="${i}">Eliminar</button>
                        </div>
                    `;
                    container.appendChild(col);
                }

                document.querySelectorAll('.remove-image').forEach(button => {
                    button.addEventListener('click', function () {
                        const index = this.getAttribute('data-index');
                        const fieldToRemove = document.getElementById(`image-container-${index}`);
                        if (fieldToRemove) {
                            fieldToRemove.remove();
                            imageCountSelect.value = parseInt(imageCountSelect.value) - 1 || 0;
                            recalcularAgrupaciones();
                            const msgImgNoSave = document.getElementById('msgImgNoSave');
                            if (msgImgNoSave) msgImgNoSave.classList.remove('d-none');
                        }
                    });
                });

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

                // Escuchar cambios en "Imagen en una hoja"
                container.addEventListener('change', function (e) {
                    if (e.target && e.target.matches('input[name="imagen_hoja[]"]')) {
                        recalcularAgrupaciones();
                    }
                });

                // 🔹 MODIFICADO
                function recalcularAgrupaciones() {
                    const todasLasImagenes = Array.from(container.querySelectorAll('[id^="image-container-"]'));

                    todasLasImagenes.forEach(div => {
                        div.classList.remove('d-none');
                        div.style.border = '';
                        const comentario = div.querySelector('textarea[name="comments[]"]');
                        if (comentario) comentario.classList.remove('d-none');
                    });

                    container.querySelectorAll('.comentario-grupo').forEach(el => el.remove());

                    const seleccionadas = Array.from(container.querySelectorAll('input[name="imagen_hoja[]"]:checked'))
                        .map(chk => parseInt(chk.value));

                    seleccionadas.forEach(id => {
                        const div = document.getElementById(`image-container-${id}`);
                        if (div) div.style.border = '3px solid #28a745';
                    });

                    const restantes = todasLasImagenes.filter(div => {
                        const idNum = parseInt(div.id.split('-').pop());
                        return !seleccionadas.includes(idNum);
                    });

                        // Agrupar de 2 en 2 las restantes
                        for (let i = 0; i < restantes.length; i += 2) {
                            const par = restantes.slice(i, i + 2);

                            // Ocultar comentarios individuales
                            par.forEach(div => {
                                const comentario = div.querySelector('textarea[name="comments[]"]');
                                if (comentario) comentario.classList.add('d-none');
                                div.style.border = '2px dashed #007bff'; // borde azul para agrupadas
                            });

                            // Identificar los números de las imágenes agrupadas
                            const nums = par.map(div => div.id.split('-').pop());
                            const textoGrupo = nums.length === 2
                                ? `Comentario para imágenes ${nums[0]} y ${nums[1]}`
                                : `Comentario para imagen ${nums[0]}`;

                            // 🟢 Crear textarea para comentario grupal
                            const comentarioGrupo = document.createElement('textarea');
                            comentarioGrupo.classList.add('form-control', 'mt-2', 'comentario-grupo');
                            comentarioGrupo.name = 'comentario_grupo[]';
                            comentarioGrupo.placeholder = textoGrupo;

                            // 🟢 Crear input oculto con los IDs de las imágenes agrupadas
                            const inputIds = document.createElement('input');
                            inputIds.type = 'hidden';
                            inputIds.name = 'comentario_grupo_ids[]';
                            inputIds.value = nums.join(',');

                            // Insertar ambos debajo del último div del grupo
                            const ultimo = par[par.length - 1];
                            ultimo.insertAdjacentElement('afterend', comentarioGrupo);
                            ultimo.insertAdjacentElement('afterend', inputIds);
                        }

                }
            }

            document.querySelector("form").addEventListener("submit", function () {
                localStorage.removeItem('imageCount');
            });
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

    function saveData(formKey) {
    const data = [];
    console.log('Saving data for form:', formKey);
    
    $('#dynamicTable tbody tr').each(function () {
        const tr = $(this);
        const isTitulo = tr.hasClass('titulo-row');
        const tituloId = tr.attr('data-titulo');
        
        if (isTitulo) {
            const tituloText = tr.find('input[name="titulos[]"]').val().trim();
            data.push({
                type: 'titulo',
                id: tituloId,
                text: tituloText
            });
        } else {
            const inputs = tr.find('input').map(function () {
                return $(this).val();
            }).get();

            data.push({
                type: 'fila',
                titulo: tituloId,
                rowNumber: tr.index() + 1,
                inputs: inputs
            });
        }
    });

    // Usa la clave dinámica
    sessionStorage.setItem(`dynamicTableData_${formKey}`, JSON.stringify(data));
}

    // Escuchar en tiempo real y guarda en el momento que se cambia un input
    $('#dynamicTable').on('input', 'input', function () {
        //console.log('Input changed, saving data...');
        saveData(document.querySelectorAll("form")[1].id);
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
        saveData(document.querySelectorAll("form")[1].id);
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
                    name = name.replace(/\[([^\]]+)\]/, function(match, p1) {
                        return p1 === oldTitulo ? `[${safeTitulo}]` : match;
                    });
                    $(this).attr('name', name);
                }
            });
        });

        updateTitulos();
        saveData(document.querySelectorAll("form")[1].id);
    });

    $('#dynamicTable').on('click', '.btnEliminar', function() {
        $(this).closest('tr').remove();
        updateRowNumbers();
        saveData(document.querySelectorAll("form")[1].id);
    });

    $('#preFillBtn').click(function() {
        $('#dynamicTable tbody tr').each(function() {
            $(this).find('input').each(function() {
                if ($(this).val() === '') {
                    $(this).val('----');
                }
            });
        });
        saveData(document.querySelectorAll("form")[1].id);
    });

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