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

    /*Pre-Rellenado del formulario */
    document.addEventListener("DOMContentLoaded", function () {
    const formularios = ["FOR-PINS-04_01", "FOR-PINS-05_01", "FOR-PINS-06_01", "FOR-PINS-07_01", "FOR-PINS-08_01", "FOR-PINS-09_01", "FOR-PINS-10_01", "FOR-PINS-11_01", "FOR-01-PRO-INS-11", "FOR-01-PRO-INS-12", "FOR-01-PRO-INS-13","FOR-01-PRO-INS-14", "FOR-01-PRO-INS-15", "FOR-01-PRO-INS-16", "FOR-01-PRO-INS-17", "FOR-01-PRO-INS-18", "FOR-01-PRO-INS-19","FOR-01-PRO-INS-20","FOR-01-PRO-INS-21","FOR-01-PRO-INS-22", "FOR-PINS-03_02", "FOR-PINS-05_02", "FOR-02-PRO-INS-10", "FOR-02-PRO-INS-15", "FOR-03-PRO-INS-15"];

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
