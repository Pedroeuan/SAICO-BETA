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

    // Generar campos de imágenes
    document.addEventListener("DOMContentLoaded", function () {
        const imageCountSelect = document.getElementById('imageCount');
        const container = document.getElementById('imageFieldsContainer');
        const cropperImage = document.getElementById('cropperImage');

        // Cargar valor guardado
        /*const savedCount = localStorage.getItem('imageCount');
        if (savedCount) {
            imageCountSelect.value = savedCount;
            generateImageFields(parseInt(savedCount));
        }*/

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
                        <textarea class="form-control mt-2" name="comments[]" placeholder="Comentario"></textarea>
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

    /*Guarda en sesionstorage */
    function saveData() {
        const data = [];
        
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
                    rowNumber: tr.index() + 1, // o cualquier contador que estés usando
                    inputs: inputs
                });
            }
        });

        sessionStorage.setItem('dynamicTableData', JSON.stringify(data));
    }

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

    /*Selección de Firmas */
        document.addEventListener('DOMContentLoaded', function() {
        //const numFirmasLocal = localStorage.getItem(document.querySelectorAll("form")[1].id+'_numFirmas');
        const numFirmasSelect = document.getElementById('numFirmas');
        const firmas2 = document.getElementById('firmas2');
        const firmas3 = document.getElementById('firmas3');
        const firmas4 = document.getElementById('firmas4');

        //numFirmasLocal ? numFirmasSelect.value = numFirmasLocal : numFirmasSelect.value = '2';

        numFirmasSelect.addEventListener('change', function() {
            if (this.value == '2') {
                firmas2.style.display = 'block';
                firmas3.style.display = 'none';
                firmas4.style.display = 'none';
            }
            else if (this.value == '3') {
                firmas2.style.display = 'none';
                firmas3.style.display = 'block';
                firmas4.style.display = 'none';
            } else if (this.value == '4') {
                firmas2.style.display = 'none';
                firmas3.style.display = 'none';
                firmas4.style.display = 'block';
            }
        });

        // Inicializar la visibilidad de las secciones de firmas
        if (numFirmasSelect.value == '2') {
            firmas2.style.display = 'block';
            firmas3.style.display = 'none';
            firmas4.style.display = 'none';
        }
        else if (numFirmasSelect.value == '3') {
            firmas2.style.display = 'none';
            firmas3.style.display = 'block';
            firmas4.style.display = 'none';
        } else if (numFirmasSelect.value == '4') {
            firmas2.style.display = 'none';
            firmas3.style.display = 'none';
            firmas4.style.display = 'block';
        }
    });