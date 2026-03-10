    /*check del cliente, si y no */
document.addEventListener('DOMContentLoaded', function () {

    const radios = document.querySelectorAll('input[name="TieneCliente"]');
    const select = document.getElementById('campoClienteSelect');
    const input  = document.getElementById('campoClienteInput');

    function toggleCliente() {
        const valor = document.querySelector('input[name="TieneCliente"]:checked').value;

        if (valor === 'si') {
            // Mostrar select
            select.style.display = 'block';
            input.style.display  = 'none';

            input.value = '';   // limpiar input

        } else {
            // Mostrar input vacío
            select.style.display = 'none';
            input.style.display  = 'block';

            select.value = '';  // limpiar select
            input.value  = '';  // aseguramos vacío
            input.focus();      // cursor automático
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', toggleCliente);
    });

    toggleCliente(); // ejecutar al cargar
});

    /*check del contrato, si y no */
document.addEventListener("DOMContentLoaded", function () {

    const radios = document.getElementsByName("TieneContrato");
    const campoContrato = document.getElementById("campoContrato");

    radios.forEach(radio => {
        radio.addEventListener("change", async function () {

            // 💾 Guardar selección
            sessionStorage.setItem("TieneContrato", this.value);

            if (this.value === "si") {
                campoContrato.readOnly = false;
                campoContrato.required = true;
                campoContrato.value = "";
                campoContrato.placeholder = "Ejemplo: 640853841";
                return;
            }

            if (this.value === "no") {
                campoContrato.readOnly = true;
                campoContrato.required = false;
                campoContrato.placeholder = "Generando contrato interno...";

                try {
                    const response = await fetch('/api/siguiente-contrato-interno');
                    const data = await response.json();

                    const nuevoContrato = data.siguiente;
                    campoContrato.value = nuevoContrato;

                } catch (error) {
                    console.error("Error al obtener el contrato interno:", error);
                    alert("No se pudo generar el contrato interno");
                }
            }
        });
    });

    // 🔄 Restaurar selección al recargar
    const seleccionado = sessionStorage.getItem("TieneContrato");

    if (seleccionado) {
        const radioGuardado = [...radios].find(r => r.value === seleccionado);

        if (radioGuardado) {
            radioGuardado.checked = true;
            radioGuardado.dispatchEvent(new Event("change"));
        }
    }
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

        //const selImgCountLocal = localStorage.getItem(document.querySelectorAll("form")[1].id+'_imageCount');
        const selImgCountLocal = localStorage.getItem(formId + '_imageCount');
        //selImgCountLocal != null ?  ($('#imageCountSelect').val(selImgCountLocal),generateImageFields(selImgCountLocal),document.getElementById('msgImgNoSave').classList.remove('d-none')):"";

        if (selImgCountLocal != null) {
            $('#imageCount').val(selImgCountLocal);
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

                        <div class="image-preview mt-2" id="image${i}-preview"></div>
                        
                        <input type="hidden" name="images_base64[]" id="image${i}-base64">
                        <button type="button" class="btn btn-danger mt-2 remove-image" data-index="${i}">Eliminar</button>
                    </div>
                `;
                container.appendChild(col);
            }

            // Agregar eventos de eliminación a los botones
            document.querySelectorAll('.remove-image').forEach(button => {
                button.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    const fieldToRemove = document.getElementById(`image-container-${index}`);
                    if (fieldToRemove) {
                        fieldToRemove.remove();
                        imageCountSelect.value = parseInt(imageCountSelect.value) - 1 || 0; // Decrementar el contador
                        
                        const msgImgNoSave = document.getElementById('msgImgNoSave');
                        if (msgImgNoSave) {
                            msgImgNoSave.classList.remove('d-none');
                        }

                        // Actualizar el localStorage
                        //const formId = document.querySelectorAll("form")[1]?.id || document.querySelector("form").id;
                        const formId = document.querySelectorAll("form")[1]?.id || document.querySelector("form").id;
                        localStorage.setItem(formId + '_imageCount', imageCountSelect.value);
                    } else {
                        alert('No se pudo encontrar el campo de imagen para eliminar.');
                    }
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

    /* Imágenes 2 */
    let cropper2;
    let currentInput2;

    // Botón: Rotar -90° (Antihorario)
    document.getElementById('rotateLeftBtn2').addEventListener('click', function () {
        if (cropper2) cropper2.rotate(-90);
    });

    // Botón: Rotar +90° (Horario)
    document.getElementById('rotateRightBtn2').addEventListener('click', function () {
        if (cropper2) cropper2.rotate(90);
    });

    // Botón: Cancelar
    document.getElementById('cancelBtn2').addEventListener('click', function () {
        $('#cropperModal2').modal('hide');
    });

    // Botón: Guardar sin recortar (manteniendo rotación)
    document.getElementById('saveWithoutCropBtn2').addEventListener('click', function () {

        try {
            // Obtener los datos de la imagen original (incluyendo rotación)
            const imageData = cropper2.getImageData();
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Ajustar el tamaño del lienzo según las dimensiones de la imagen rotada
            if (Math.abs(cropper2.getData().rotate) % 180 === 90) {
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
                cropper2.element, // Aquí usamos el elemento de la imagen directamente
                -imageData.naturalWidth / 2,
                -imageData.naturalHeight / 2,
                imageData.naturalWidth,
                imageData.naturalHeight
            );

            // Convertir el lienzo a base64
            const base64data = canvas.toDataURL();
            const previewDiv = document.getElementById(`${currentInput2.id}-preview`);
            previewDiv.innerHTML = `
                <img src="${base64data}" class="img-fluid img-thumbnail" />
                <span class="badge bg-success">¡Guardado!</span>
            `;
            document.getElementById(`${currentInput2.id}-base64`).value = base64data;

            // Cerrar el modal
            $('#cropperModal2').modal('hide');
        } catch (error) {
            console.error('Error al guardar la imagen sin recortar:', error);
        }
    });

    // Botón: Recortar y guardar
    document.getElementById('cropImageBtn2').addEventListener('click', function () {
        if (cropper2 && currentInput2) {
            const croppedCanvas = cropper2.getCroppedCanvas();
            if (croppedCanvas) {
                const base64data = croppedCanvas.toDataURL();
                const previewDiv = document.getElementById(`${currentInput2.id}-preview`);
                previewDiv.innerHTML = `
                    <img src="${base64data}" class="img-fluid img-thumbnail" />
                    <span class="badge bg-success">¡Recortado!</span>
                `;
                document.getElementById(`${currentInput2.id}-base64`).value = base64data;
            }
        }
        $('#cropperModal2').modal('hide');
    });

    // Destruir Cropper al cerrar el modal
    $('#cropperModal2').on('hidden.bs.modal', function () {
        if (cropper2) cropper2.destroy();
    });

    // Generar campos de imágenes
    document.addEventListener("DOMContentLoaded", function () {
        const imageCountSelect = document.getElementById('imageCount2');
        const container = document.getElementById('imageFieldsContainer2');
        const cropperImage = document.getElementById('cropperImage2');

        //const selImgCountLocal = localStorage.getItem(document.querySelectorAll("form")[1].id+'_imageCount2');
        const selImgCountLocal = localStorage.getItem(formId + '_imageCount2');
        //selImgCountLocal != null ?  ($('#imageCountSelect').val(selImgCountLocal),generateImageFields(selImgCountLocal),document.getElementById('msgImgNoSave').classList.remove('d-none')):"";

        if (selImgCountLocal != null) {
            $('#imageCount2').val(selImgCountLocal);
            generateImageFields2(selImgCountLocal);

            const msgImgNoSave = document.getElementById('msgImgNoSave');
            if (msgImgNoSave) {
                msgImgNoSave.classList.remove('d-none');
            }
        }

        imageCountSelect.addEventListener('change', function () {
            const count = parseInt(this.value);
            //localStorage.setItem('imageCount', count);
            generateImageFields2(count);
        });

        function generateImageFields2(count) {
            container.innerHTML = '';
            for (let i = 1; i <= count; i++) {
                const col = document.createElement('div');
                col.classList.add('col-sm-6');
                col.setAttribute('id', `image2-container-${i}`); // ID único para eliminarlo después
                col.innerHTML = `
                    <div class="form-group">
                        <label for="image2${i}">Imagen por Subir ${i}:</label>
                        <input type="file" class="form-control image2-input" id="image2${i}" accept="image/*">

                        <div class="image-preview mt-2" id="image2${i}-preview"></div>
                        
                        <input type="hidden" name="images_base64[]" id="image2${i}-base64">
                        <button type="button" class="btn btn-danger mt-2 remove-image2" data-index="${i}">Eliminar</button>
                    </div>
                `;
                container.appendChild(col);
            }

            // Agregar eventos de eliminación a los botones
            document.querySelectorAll('.remove-image2').forEach(button => {
                button.addEventListener('click', function () {
                    const index = this.getAttribute('data-index');
                    const fieldToRemove = document.getElementById(`image2-container-${index}`);
                    if (fieldToRemove) {
                        fieldToRemove.remove();
                        imageCountSelect.value = parseInt(imageCountSelect.value) - 1 || 0; // Decrementar el contador
                        
                        const msgImgNoSave = document.getElementById('msgImgNoSave');
                        if (msgImgNoSave) {
                            msgImgNoSave.classList.remove('d-none');
                        }

                        // Actualizar el localStorage
                        //const formId = document.querySelectorAll("form")[1]?.id || document.querySelector("form").id;
                        const formId = document.querySelectorAll("form")[1]?.id || document.querySelector("form").id;
                        localStorage.setItem(formId + '_imageCount2', imageCountSelect.value);
                    } else {
                        alert('No se pudo encontrar el campo de imagen para eliminar.');
                    }
                });
            });

            // Asignar eventos a los nuevos inputs
            document.querySelectorAll('.image2-input').forEach(input => {
                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    
                    if (!file.type.startsWith('image/')) {
                        alert('Por favor, sube solo imágenes.');
                        return;
                    }

                    currentInput2 = e.target;
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        if (cropper2) cropper2.destroy();
                        cropperImage.src = event.target.result;
                        $('#cropperModal2').modal('show');
                        cropper2 = new Cropper(cropperImage, {
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
            localStorage.removeItem('imageCount2');
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

    /*Envio de formulario */
/* Envio de formulario */
$(document).ready(function () {

    $('form').submit(function(e) {

        // ============================
        // VALIDAR CLIENTE SELECCIONADO
        // ============================
        let tieneCliente   = $('input[name="TieneCliente"]:checked').val();
        let clienteSelect  = $('#campoClienteSelect').val();
        let clienteInput   = $('#campoClienteInput').val();

        // Si seleccionó "SI", debe elegir un cliente del select
        if (tieneCliente === 'si' && 
            (clienteSelect === null || clienteSelect === 'Seleccione un Cliente')) {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Cliente requerido',
                text: 'Por favor seleccione un cliente válido.',
            });

            $('#campoClienteSelect').focus();
            return;
        }

        // Si seleccionó "NO", debe escribir un cliente
        if (tieneCliente === 'no' && clienteInput.trim() === '') {

            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Cliente requerido',
                text: 'Por favor ingrese el nombre del cliente.',
            });

            $('#campoClienteInput').focus();
            return;
        }

        // ============================
        // CONTINUA ENVIO NORMAL
        // ============================
        updateTitulos();

        sessionStorage.clear();

        let submitButton = $(this).find('button[type="submit"]');
        submitButton.prop('disabled', true).text('Guardando...');
        submitButton.append(' <i class="fa fa-spinner fa-spin"></i>');
    });

});
