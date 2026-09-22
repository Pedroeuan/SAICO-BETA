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

        /*Prevenir el Enter*/
    document.getElementById('OT_SForm').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
    });

    $(document).ready(function() {
        var rowCount = $('#dynamicTable tbody tr').length;

        function updateRowNumbers() {
            $('#dynamicTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            rowCount = $('#dynamicTable tbody tr').length;
        }

        $('#addRowBtn').click(function() {
            rowCount++;
            var newRow = `<tr>
                <td>${rowCount}</td>
                <td><textarea class="form-control" name="descripcion[]" placeholder="Descripción/Actividades"></textarea></td>
                <td><input type="text" class="form-control" name="unidad[]" placeholder="Unidad"></td>
                <td><input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad"></td>
                <td><textarea class="form-control" name="procesos[]" placeholder="Procesos"></textarea></td>
                <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
            </tr>`;
            $('#dynamicTable tbody').append(newRow);
        });

        $('#dynamicTable').on('click', '.btnEliminar', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });
    });
        document.getElementById('OT_SForm').addEventListener('submit', function(e) {
            const tableBody = document.querySelector("#dynamicTable tbody");
            const rows = tableBody.querySelectorAll("tr");
            const tableData = [];

            rows.forEach(row => {
                const descripcion = row.querySelector("[name='descripcion[]']").value;
                const unidad = row.querySelector("input[name='unidad[]']").value;
                const cantidad = row.querySelector("input[name='cantidad[]']").value;
                const procesos = row.querySelector("textarea[name='procesos[]']").value;

                // Añadir los datos de la fila al array
                tableData.push({
                    descripcion: descripcion,
                    unidad: unidad,
                    cantidad: cantidad,
                    procesos: procesos
                });
            });

        // Convertir el array a JSON y asignarlo al campo oculto
            document.getElementById('dynamicTableData').value = JSON.stringify(tableData);
        });

    /*Selección de Firmas */
    document.addEventListener('DOMContentLoaded', function() {
    const numFirmasSelect = document.getElementById('numFirmas');
    const firmas1 = document.getElementById('firmas1');
    const firmas2 = document.getElementById('firmas2');
    const firmas3 = document.getElementById('firmas3');
    const firmas4 = document.getElementById('firmas4');

    if (!numFirmasSelect || !firmas1 || !firmas2 || !firmas3 || !firmas4) return;

    function mostrarFirmas(valor) {
        firmas1.style.display = valor == '1' ? 'block' : 'none';
        firmas2.style.display = valor == '2' ? 'block' : 'none';
        firmas3.style.display = valor == '3' ? 'block' : 'none';
        firmas4.style.display = valor == '4' ? 'block' : 'none';
    }

    numFirmasSelect.addEventListener('change', function() {
        mostrarFirmas(this.value);
    });

    mostrarFirmas(numFirmasSelect.value || '1');
    });
