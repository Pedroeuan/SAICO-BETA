    /*Pre-Rellenado del formulario */
    document.addEventListener("DOMContentLoaded", function () {
    const formularios = ["FOR-PIMP-06_B_01"
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

function getMainFormIdIM0206() {
    const forms = document.querySelectorAll("form");
    return (forms[1] || forms[0] || {}).id || "FOR-PIMP-02_B_03";
}

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

        const selImgCountLocal = localStorage.getItem(getMainFormIdIM0206()+'_imageCount');
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
            const permiteDisparos = document.getElementById('FOR-PIMP-06_B_01') !== null;
            for (let i = 1; i <= count; i++) {
                const indiceEnvio = i - 1;
                const sufijoCampo = permiteDisparos ? `[${indiceEnvio}]` : '[]';
                const col = document.createElement('div');
                col.classList.add('col-sm-6');
                col.setAttribute('id', `image-container-${i}`); // ID único para eliminarlo después
                col.innerHTML = `
                    <div class="form-group">
                        <label for="image${i}">Imagen por Subir ${i}:</label>
                        <input type="file" class="form-control image-input" id="image${i}" accept="image/*">
                        
                        <!-- CHECK IMAGEN EN HOJA -->
                        <div class="form-check mt-2">
                            <input type="checkbox"
                                class="form-check-input imagen-hoja-checkbox"
                                data-index="${i}"
                                id="imagenHoja${i}">
                            <label class="form-check-label" for="imagenHoja${i}">
                                Imagen en una hoja
                            </label>
                        </div>
                        <input type="hidden" name="imagen_hoja${sufijoCampo}" id="imagenHojaValue${i}" value="0">

                        ${permiteDisparos ? `
                        <div class="form-check mt-2">
                            <input type="hidden" name="es_disparo${sufijoCampo}" id="esDisparoValue${i}" value="0">
                            <input type="checkbox" class="form-check-input foto-disparo-checkbox" data-index="${i}" id="esDisparo${i}">
                            <label class="form-check-label" for="esDisparo${i}">Esta imagen pertenece a un disparo</label>
                        </div>
                        <div class="mt-2 d-none numero-disparo-container" id="numeroDisparoContainer${i}">
                            <label for="numeroDisparo${i}">Asignar al disparo:</label>
                            <select class="form-control" name="numero_disparo${sufijoCampo}" id="numeroDisparo${i}">
                                <option value="">Selecciona un disparo</option>
                                <option value="1">1er. disparo</option>
                                <option value="2">2do. disparo</option>
                                <option value="3">3er. disparo</option>
                            </select>
                            <small class="text-muted">Cada disparo se completa con dos fotografías.</small>
                        </div>` : ''}

                        <!-- Comentario -->
                        <div class="image-preview mt-2" id="image${i}-preview"></div>
                        <textarea class="form-control mt-2" name="comments${sufijoCampo}" placeholder="Comentario"></textarea>

                        ${!permiteDisparos ? `<!-- El detalle de junta se conserva solo en los formatos que lo requieren. -->
                        <div class="form-check mt-2">
                            <input type="checkbox"
                                class="form-check-input detalles-junta-checkbox"
                                data-index="${i}"
                                id="detallesJunta${i}">
                            <label class="form-check-label" for="detallesJunta${i}">
                                Detalles Junta
                            </label>
                        </div>
                        <input type="hidden" name="detalles_junta_check${sufijoCampo}" id="detallesJuntaValue${i}" value="0">

                        <!-- CAMPOS OCULTOS DETALLES JUNTA -->
                        <div class="detalles-junta-container mt-3 d-none" id="detallesContainer${i}">
                            Junta
                            <input type="text" class="form-control mt-1" name="junta[]" placeholder="Junta">
                            No. Indicación
                            <input type="text" class="form-control mt-1" name="no_indicacion[]" placeholder="No. Indicación">
                            Tipo Indicación
                            <input type="text" class="form-control mt-1" name="tipo_indicacion[]" placeholder="Tipo Indicación">
                            Longitud
                            <input type="text" class="form-control mt-1" name="longitud[]" placeholder="Longitud">
                            Profundidad
                            <input type="text" class="form-control mt-1" name="profundidad[]" placeholder="Profundidad">
                            Nivel de Referencia
                            <input type="text" class="form-control mt-1" name="nivel_referencia[]" placeholder="Nivel de Referencia">
                            Distancia del Nivel de Referencia
                            <input type="text" class="form-control mt-1" name="distancia_nivel[]" placeholder="Distancia del Nivel de Referencia">
                            Dirección de la Sonda
                            <input type="text" class="form-control mt-1" name="direccion_sonda[]" placeholder="Dirección de la Sonda">
                            Recubrimiento
                            <input type="text" class="form-control mt-1" name="recubrimiento[]" placeholder="Recubrimiento">
                        </div>` : ''}
                        <input type="hidden" name="images_base64${sufijoCampo}" id="image${i}-base64">
                        <br>
                        <button type="button" class="btn btn-danger mt-2 remove-image" data-index="${i}">Eliminar</button>
                    </div>
                `;
                container.appendChild(col);
            }

            // 🔁 RESTAURAR CHECKS DESPUÉS DE GENERAR
            const form = document.querySelector("form");
            const formId = form.id;

            document.querySelectorAll('.imagen-hoja-checkbox').forEach(cb => {
                const key = `${formId}_${cb.id}`;
                const stored = localStorage.getItem(key);

                if (stored !== null) {
                    cb.checked = stored === "true";
                    document.getElementById(`imagenHojaValue${cb.dataset.index}`).value = cb.checked ? 1 : 0;
                }
            });

            
            document.querySelectorAll('.detalles-junta-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {

                    const index = this.dataset.index;

                    const container = document.getElementById(`detallesContainer${index}`);
                    const hiddenInput = document.getElementById(`detallesJuntaValue${index}`);

                    // CHECKBOX IMAGEN EN HOJA
                    const imagenHojaCheckbox = document.getElementById(`imagenHoja${index}`);
                    const imagenHojaValue = document.getElementById(`imagenHojaValue${index}`);

                    const form = document.querySelector("form");
                    const formId = form.id;

                    if (this.checked) {

                        container.classList.remove('d-none');
                        hiddenInput.value = 1;

                        // ✅ MARCAR AUTOMÁTICAMENTE
                        imagenHojaCheckbox.checked = true;
                        imagenHojaValue.value = 1;

                        // Guardar en localStorage
                        localStorage.setItem(`${formId}_imagenHoja${index}`, true);

                        // Restaurar valores guardados
                        container.querySelectorAll('input[type="text"]').forEach(input => {
                            const key = `${formId}_${input.name}_${container.id}`;
                            const stored = localStorage.getItem(key);

                            if (stored !== null) {
                                input.value = stored;
                            }
                        });

                    } else {

                        container.classList.add('d-none');
                        hiddenInput.value = 0;

                        // OPCIONAL: desmarcar también
                        imagenHojaCheckbox.checked = false;
                        imagenHojaValue.value = 0;

                        localStorage.setItem(`${formId}_imagenHoja${index}`, false);
                    }

                    // Guardar estado del checkbox
                    localStorage.setItem(`${formId}_${this.id}`, this.checked);

                });
            });
            // 🔁 RESTAURAR INPUTS DE DETALLES JUNTA
            document.querySelectorAll('.detalles-junta-container input').forEach(input => {
                const key = `${formId}_${input.name}_${input.closest('.detalles-junta-container').id}`;
                const stored = localStorage.getItem(key);

                if (stored !== null) {
                    input.value = stored;
                }

                input.addEventListener('input', function() {
                    localStorage.setItem(key, input.value);
                });
            });

            document.querySelectorAll('.imagen-hoja-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    const index = this.dataset.index;
                    document.getElementById(`imagenHojaValue${index}`).value = this.checked ? 1 : 0;
                });
            });

            document.querySelectorAll('.foto-disparo-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    const index = this.dataset.index;
                    const hidden = document.getElementById(`esDisparoValue${index}`);
                    const select = document.getElementById(`numeroDisparo${index}`);
                    const selectContainer = document.getElementById(`numeroDisparoContainer${index}`);

                    hidden.value = this.checked ? 1 : 0;
                    selectContainer.classList.toggle('d-none', !this.checked);
                    if (!this.checked) select.value = '';
                });
            });

            document.querySelectorAll('.detalles-junta-checkbox').forEach(cb => {
                cb.addEventListener('change', function () {
                    const index = this.dataset.index;
                    const container = document.getElementById(`detallesContainer${index}`);
                    const hiddenInput = document.getElementById(`detallesJuntaValue${index}`);
                    const form = document.querySelector("form");
                    const formId = form.id;

                    if (this.checked) {
                        container.classList.remove('d-none');
                        hiddenInput.value = 1;
                        
                        // Restaurar valores guardados cuando se activa
                        container.querySelectorAll('input[type="text"]').forEach(input => {
                            const key = `${formId}_${input.name}_${container.id}`;
                            const stored = localStorage.getItem(key);
                            if (stored !== null) {
                                input.value = stored;
                            }
                        });
                    } else {
                        container.classList.add('d-none');
                        hiddenInput.value = 0;
                    }

                    // Guardar estado del checkbox
                    localStorage.setItem(`${formId}_${this.id}`, this.checked);
                });
            });
            
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
            Object.keys(localStorage).forEach(key => {
            if (key.startsWith(formId)) {
                    localStorage.removeItem(key);
                }
            });
        });
    });

    /*Juntas-Resultados */
    function updateRowNumbers() {
        let count = 0;

        $('#dynamicTable tbody tr').each(function () {

            // ⛔ Ignorar títulos y longitudes
            if ($(this).hasClass('titulo-row') || $(this).hasClass('long-row')) {
                return;
            }

            count++;
            $(this).find('td:first').html(`${count} <input type="hidden" value="${count}">`);
        });

        rowCountGlobal = count;
    }

    // Función para actualizar los títulos en el campo oculto (excluye longitudes)
    function updateTitulos() {
        var titulos = [];
        $('.titulo-row').not('.long-row').each(function() {
            const id = $(this).data('titulo');
            const text = $(this).find('.titulo-text').val() || '';
            titulos.push({ id: id, text: text });
        });
        $('#titulos_hidden').val(JSON.stringify(titulos));
    }

    function saveData(formId) {
    const titles = $('.titulo-row').not('.long-row').map(function() {
        return { id: $(this).data('titulo'), text: $(this).find('.titulo-text').val() };
    }).get();

    const rows = $('#dynamicTable tbody tr')
        .not('.titulo-row, .long-row')
        .map(function() {
            const id = $(this).data('titulo');
            const values = $(this).find('input[type="text"]').map(function(){ 
                return $(this).val(); 
            }).get();
            return { titleId: id, values };
        }).get();

    const longs = $('.long-row').map(function(){
        return { 
            titleId: $(this).data('titulo'),
            text: $(this).find('.long-text').val() 
        };
    }).get();

    // Dedupe by id+text para evitar entradas repetidas en sessionStorage
    function dedupe(arr){
        const seen = new Set();
        return arr.filter(item => {
            const key = (item.id || '') + '||' + (item.text || '');
            if(seen.has(key)) return false; seen.add(key); return true;
        });
    }

    const uniqueTitles = dedupe(titles);
    const uniqueLongs = dedupe(longs);

    sessionStorage.setItem('dynamicTableData', JSON.stringify({
            titles: uniqueTitles,
            rows,
            longs
        }));
    }

    // Escuchar en tiempo real y guarda en el momento que se cambia un input
    $('#dynamicTable').on('input', 'input', function () {
        //console.log('Input changed, saving data...');
        saveData(getMainFormIdIM0206());
    });

    // Evento para eliminar un título
    $(document).on('click', '.btnEliminarTitulo', function () {
        const tr = $(this).closest('tr.titulo-row');
        const id = tr.data('titulo');
        // eliminar filas del mismo id
        $('#dynamicTable tbody tr').filter(function () {
            return $(this).data('titulo') === id;
        }).remove();
        tr.remove();
        updateTitulos();
        saveData($(this).closest('form').attr('id'));
    });

    /*Cambia el data-titulo y guarda en sesionstorage */
    $(document).on('input', '.titulo-row .titulo-text', function () {
        updateTitulos();
        saveData(getMainFormIdIM0206());
    });

    $('#dynamicTable').on('click', '.btnEliminar', function() {

        const $tr = $(this).closest('tr');
        const esLongitud = $tr.hasClass('long-row');

        $tr.remove();
        updateRowNumbers();

        // 👉 Solo recalcular si NO estamos borrando una longitud
        if (!esLongitud) {
            verificarYAgregarLongitud();
        }

        saveData(getMainFormIdIM0206());
    });

    $('#preFillBtn').click(function() {
        $('#dynamicTable tbody tr').each(function() {
            $(this).find('input').each(function() {
                if ($(this).val() === '') {
                    $(this).val('----');
                }
            });
        });
        saveData(getMainFormIdIM0206());
    });

    /*llenado de campos vacios*/
    document.addEventListener("DOMContentLoaded", function () {
        const inputFields = document.querySelectorAll(".default-input");

        inputFields.forEach(input => {
            input.addEventListener("input", function () {
                const column = parseInt(input.getAttribute("data-column")); // Aseguramos que sea número
                if (isNaN(column)) return; // Evitar errores si no es válido

                document.querySelectorAll("#dynamicTable tbody tr:not(.titulo-row):not(.long-row)").forEach(row => {
                    const cellInputs = row.querySelectorAll("td input[type='text']");
                    const cellInput = cellInputs[column - 1]; // data-column inicia en 1
                    if (cellInput) {
                        cellInput.value = input.value;
                    }
                });
            });
        });
    });

    /*Pre-Rellenado del formulario */
    document.addEventListener("DOMContentLoaded", function () {
    const formularios = ["FOR-PIMP-02_B_03", 
    ];
    formularios.forEach(formId => {
        const form = document.getElementById(formId);
        if (!form) return; // Saltar si no existe

        const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="file"]):not([type="checkbox"]):not([type="radio"]):not([type="button"]):not([type="submit"])');
        const textareas = form.querySelectorAll("textarea");

        // Restaurar valores desde localStorage
        inputs.forEach(input => {
            if (input.closest('#dynamicTable') || input.readOnly || input.disabled || !input.name) return;

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
                    if (input.closest('#dynamicTable') || input.readOnly || input.disabled || !input.name) return;

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
            inputs.forEach(input => {
                if (input.closest('#dynamicTable') || input.readOnly || input.disabled || !input.name) return;
                localStorage.removeItem(`${formId}_${input.name}`);
            });

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
    const numFirmasLocal = localStorage.getItem(getMainFormIdIM0206()+'_numFirmas');
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

        // Mantener juntas la imagen y su asignación de disparo.
        if ($(this).attr('id') === 'FOR-PIMP-06_B_01') {
            const conteoDisparos = { 1: 0, 2: 0, 3: 0 };
            let errorDisparo = '';

            $(this).find('.foto-disparo-checkbox:checked').each(function () {
                const indiceVisual = this.dataset.index;
                const numero = $(`#numeroDisparo${indiceVisual}`).val();
                const imagen = $(`#image${indiceVisual}-base64`).val();

                if (!numero) {
                    errorDisparo = `Selecciona el disparo de la imagen ${indiceVisual}.`;
                    return false;
                }
                if (!imagen) {
                    errorDisparo = `La imagen ${indiceVisual} no se terminó de guardar o recortar.`;
                    return false;
                }
                conteoDisparos[numero]++;
            });

            if (!errorDisparo) {
                Object.keys(conteoDisparos).some(function (numero) {
                    const cantidad = conteoDisparos[numero];
                    if (cantidad !== 0 && cantidad !== 2) {
                        errorDisparo = `El ${numero}° disparo debe tener exactamente dos fotografías.`;
                        return true;
                    }
                    return false;
                });
            }

            if (errorDisparo) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Revisa los disparos',
                    text: errorDisparo,
                });
                return;
            }
        }

        // ============================
        // VALIDAR QUE LA TABLA NO ESTE VACIA
        // ============================
        const esFormato0204 = $(this).attr('id') === 'FOR-PIMP-02_B_04';
        const tablaResultados = esFormato0204
            ? $(this).find('#durezaBrinellBody')
            : $(this).find('#dynamicTable');
        const requiereFilas = tablaResultados.length > 0;
        const totalFilasTabla = esFormato0204
            ? tablaResultados.find('tr').length
            : tablaResultados.find('tbody tr').length;

        if (requiereFilas && totalFilasTabla === 0) {
            e.preventDefault();

            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'La tabla no puede estar vacía. Por favor, agregue al menos una fila.',
            });

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
