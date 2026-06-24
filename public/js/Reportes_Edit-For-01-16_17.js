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
        const oldPreview = document.getElementById(`${currentInput.id}-old-preview`);
        if(oldPreview){
            oldPreview.style.display = "none";
        }

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
        const oldPreview = document.getElementById(`${currentInput.id}-old-preview`);
        if(oldPreview){
            oldPreview.style.display = "none";
        }
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


function activateImageEvents(){

    const cropperImage = document.getElementById('cropperImage');

    document.querySelectorAll('.image-input').forEach(input => {

        input.addEventListener('change', function(e){

            const file = e.target.files[0];
            if(!file) return;

            currentInput = e.target;

            const reader = new FileReader();

            reader.onload = function(event){

                // ocultar imagen anterior
                const oldPreview = document.getElementById(`${currentInput.id}-old-preview`);
                if(oldPreview){
                    oldPreview.style.display = "none";
                }

                if(cropper) cropper.destroy();

                cropperImage.src = event.target.result;

                $('#cropperModal').modal('show');

                cropper = new Cropper(cropperImage,{
                    aspectRatio: 4/3,
                    viewMode:1,
                    autoCropArea:1,
                    minContainerWidth: 760,
                    minContainerHeight: 600,
                    responsive:true
                });

            };

            reader.readAsDataURL(file);

        });

    });

}

document.addEventListener("DOMContentLoaded", function () {
    activateImageEvents();
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
        "FOR-PINS-17_01_01", "FOR-03-PRO-INS-15"
    ];
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

            const selectedOptionLocalT = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Tecnicos');
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

            const selectedOptionLocalT2 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Tecnicos2');
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

            const selectedOptionLocalT3 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Tecnicos3');
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

            const selectedOptionLocalT4 = localStorage.getItem(document.querySelectorAll("form")[1].id+'_Tecnicos4');
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