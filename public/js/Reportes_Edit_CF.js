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

    /*Imagenes */
    document.addEventListener("DOMContentLoaded", function () {
        const imageCountSelect = document.getElementById("imageCount");
        const imageFieldsContainer = document.getElementById("imageFieldsContainer");
        const cropperModal = document.getElementById("cropperModal");
        const cropperImage = document.getElementById("cropperImage");
        const cropButton = document.getElementById("cropButton");
        const closeModalButton = document.getElementById("closeModalButton");

        let cropper = null;
        let currentImageField = null;

        // 🟢 CAMBIO DE SELECT: CREA LOS CAMPOS DE IMAGEN
        imageCountSelect.addEventListener("change", function () {
            const count = parseInt(this.value);
            imageFieldsContainer.innerHTML = "";

            if (!isNaN(count) && count > 0) {
                for (let i = 1; i <= count; i++) {
                    const fieldHTML = `
                        <div class="col-md-4 mb-3 image-field" id="image-container-${i}">
                            <label>Imagen ${i}</label>
                            <input type="file" class="form-control image-input" id="image${i}" name="images[]" accept="image/*">

                            <img id="preview${i}" class="img-thumbnail mt-2" style="display:none; width:100%; max-height:200px; object-fit:cover;">

                            <textarea name="comments[]" id="comment${i}" class="form-control mt-2" placeholder="Agrega un comentario (opcional)"></textarea>

                            <div class="form-check mt-2">
                                <input type="checkbox" class="form-check-input" id="imagen_hoja${i}" name="imagen_hoja[]" value="${i}">
                                <label class="form-check-label" for="imagen_hoja${i}">Agregar imagen a la hoja</label>
                            </div>

                            <input type="hidden" name="images_base64[]" id="image${i}-base64">
                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-image" data-id="${i}">Eliminar</button>
                        </div>
                    `;
                    imageFieldsContainer.insertAdjacentHTML("beforeend", fieldHTML);
                }
            }
        });

        // 🟢 EVENTO: ABRIR CROP AL ELEGIR IMAGEN
        document.body.addEventListener("change", function (event) {
            if (event.target.classList.contains("image-input")) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        cropperImage.src = e.target.result;
                        currentImageField = event.target.id.replace("image", "");
                        const modal = new bootstrap.Modal(cropperModal);
                        modal.show();

                        cropper = new Cropper(cropperImage, {
                            aspectRatio: 1,
                            viewMode: 2,
                        });
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        // 🟢 CROP Y GUARDADO DE IMAGEN BASE64
        cropButton.addEventListener("click", function () {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                });
                const base64Image = canvas.toDataURL("image/png");

                const preview = document.getElementById("preview" + currentImageField);
                preview.src = base64Image;
                preview.style.display = "block";

                document.getElementById("image" + currentImageField + "-base64").value = base64Image;

                const modalInstance = bootstrap.Modal.getInstance(cropperModal);
                modalInstance.hide();
                cropper.destroy();
                cropper = null;
            }
        });

        // 🟡 CERRAR MODAL SIN GUARDAR
        closeModalButton.addEventListener("click", function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        // 🟢 ELIMINAR IMAGEN (de nuevas o existentes)
        document.body.addEventListener("click", function (event) {
            if (event.target.classList.contains("remove-image")) {
                const container = event.target.closest(".image-field"); // sube hasta el contenedor
                if (container) {
                    container.remove();
                }
            }
        });
        /*document.body.addEventListener("click", function (event) {
            if (event.target.classList.contains("remove-image")) {
                const id = event.target.getAttribute("data-id");
                const container = document.getElementById("image-container-" + id);
                if (container) {
                    container.remove();
                }
            }
        });*/

        // 🟢 OPCIONAL: VALIDAR ANTES DE ENVIAR
        const form = document.querySelector("form");
        form.addEventListener("submit", function (e) {
            const allImages = document.querySelectorAll('input[name="images_base64[]"]');
            const hasImage = Array.from(allImages).some(input => input.value.trim() !== "");

            if (!hasImage) {
                e.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Faltan imágenes",
                    text: "Debes agregar al menos una imagen antes de guardar.",
                });
            }
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