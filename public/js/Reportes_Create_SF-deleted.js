    /*check del contrato, si y no */
    document.addEventListener("DOMContentLoaded", function () {

        const radios = document.getElementsByName("TieneContrato");
        const campoContrato = document.getElementById("campoContrato");
        const contratoInternoHidden = document.getElementById("contratoInternoHidden");

        const textoInterno = document.getElementById("contratoInternoTexto");
        const numeroInterno = document.getElementById("numeroInterno");

        radios.forEach(radio => {
            radio.addEventListener("change", async function () {

                if (this.value === "si") {
                    campoContrato.disabled = false;
                    campoContrato.required = true;

                    // Limpiar contrato interno
                    textoInterno.style.display = "none";
                    numeroInterno.textContent = "";
                    contratoInternoHidden.value = "";
                    return;
                }

                if (this.value === "no") {

                    campoContrato.disabled = true;
                    campoContrato.required = false;
                    campoContrato.value = "";

                    try {
                        const response = await fetch('/api/siguiente-contrato-interno');
                        const data = await response.json();

                        const nuevoContrato = data.siguiente;
                        console.log("Contrato interno generado:", nuevoContrato);

                        // Mostrarlo al usuario
                        textoInterno.style.display = "block";
                        numeroInterno.textContent = nuevoContrato;

                        // Guardarlo para enviarlo al backend
                        contratoInternoHidden.value = nuevoContrato;

                    } catch (error) {
                        console.error("Error al obtener el contrato interno:", error);
                    }
                }
            });
        });
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
    /*function saveData() {
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
    }*/



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
    const formularios = ["FOR-PINS-04_01", "FOR-PINS-05_01", "FOR-PINS-06_01", "FOR-PINS-07_01", "FOR-PINS-08_01", "FOR-PINS-09_01", "FOR-PINS-10_01", "FOR-PINS-11_01",
        "FOR-PINS-12_01", "FOR-PINS-13_01", "FOR-PINS-14_01", "FOR-PINS-15_01", "FOR-PINS-16_01", "FOR-PINS-17_01", "FOR-PINS-18_01", "FOR-PINS-19_01", "FOR-PINS-20_01",
        "FOR-PINS-21_01", "FOR-PINS-22_01", "FOR-PINS-22_01", "FOR-PINS-23_01", "FOR-PINS-24_01", "FOR-PINS-25_01", "FOR-PINS-03_02", "FOR-PINS-05_02", "FOR-PINS-11_02",
        "FOR-PINS-17_01_01", "FOR-03-PRO-INS-15"];
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