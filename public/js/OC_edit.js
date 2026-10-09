/*check del contrato, si y no */
document.addEventListener("DOMContentLoaded", function () {

    const radios = document.getElementsByName("TieneContrato");
    const campoContrato = document.getElementById("campoContrato");

    radios.forEach(radio => {
        radio.addEventListener("change", async function () {

            // 💾 Guardar selección
            sessionStorage.setItem("TieneContrato", this.value);

            if (this.value === "si") {
                console.log("Seleccionado: si");
                campoContrato.readOnly = false;
                campoContrato.required = true;
                campoContrato.value = "";
                campoContrato.placeholder = "Ejemplo: 640853841";
                return;
            }

            if (this.value === "no") {
                console.log("Seleccionado: no");
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

    /*Prevenir el Enter*/
    document.getElementById('OC').addEventListener('keydown', function(event) {
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
                <td>
                    <select class="form-control catalogo-select">
                        <option value="">-- Seleccionar --</option>
                        ${catalogoData.map(item => `<option value="${item.idCatalogo_OC}" data-descripcion="${item.Descripcion}" data-unidad="${item.Unidad}" data-imagen="${item.Imagen || ''}">${item.Nombre}</option>`).join('')}
                    </select>
                </td>
                <td><textarea class="form-control descripcion-input" name="descripcion[]" placeholder="Descripcion"></textarea></td>
                <td class="text-center">
                    <img class="catalogo-preview-img" src="" alt="Imagen del catálogo" style="width: 52px; height: 52px; object-fit: contain; border-radius: 8px; border: 1px solid #ddd; display: none;">
                    <span class="catalogo-preview-placeholder text-muted small" style="display: block;">Sin imagen</span>
                </td>
                <td><input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad"></td>
                <td><input type="text" class="form-control unidad-input" name="unidad[]" placeholder="Unidad/Medida"></td>
                <td><input type="number" class="form-control" name="valor_unitario[]" placeholder="Valor Unitario"></td>
                <td><input type="number" class="form-control" name="valor_total[]" placeholder="Valor Total"></td>
                <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
            </tr>`;
            $('#dynamicTable tbody').append(newRow);
        });

        document.getElementById('OC').addEventListener('submit', function(e) {
            const tableBody = document.querySelector("#dynamicTable tbody");
            const rows = tableBody.querySelectorAll("tr");
            const tableData = [];

            rows.forEach(row => {
                const unidad = row.querySelector('td:nth-child(2) input').value;
                const cantidad = row.querySelector('td:nth-child(3) input').value;
                const descripcion = row.querySelector("textarea[placeholder='Descripcion']").value; // Capturar el valor del textarea

                // Añadir los datos de la fila al array
                tableData.push({
                    unidad: unidad,
                    cantidad: cantidad,
                    descripcion: descripcion
                });
            });

            // Convertir el array a JSON y asignarlo al campo oculto
            document.getElementById('dynamicTableData').value = JSON.stringify(tableData);
        });

        //AGREGAR LOCALSTORAGE