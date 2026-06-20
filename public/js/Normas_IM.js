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
        saveData(document.querySelectorAll("form")[1].id);
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
        saveData(document.querySelectorAll("form")[1].id);
    });

    $('#dynamicTable').on('click', '.btnEliminar', function() {

        const $tr = $(this).closest('tr');
        const esLongitud = $tr.hasClass('long-row');

        $tr.remove();
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
    const formularios = ["NormasIMForm"];
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

