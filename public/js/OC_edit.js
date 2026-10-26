$(function () {
    const tableBody = document.querySelector("#dynamicTable tbody");
    const form = document.getElementById("OC");
    const catalogoData = Array.isArray(catalogo) ? catalogo : [];

    function catalogOptions(selectedId = "") {
        const options = catalogoData.map(item => {
            const selected = String(item.idCatalogo_OC) === String(selectedId) ? " selected" : "";
            return `<option value="${item.idCatalogo_OC}" data-descripcion="${item.Descripcion || ""}" data-unidad="${item.Unidad || ""}" data-imagen="${item.Imagen || ""}"${selected}>${item.Nombre}</option>`;
        });

        return ['<option value="">-- Seleccionar --</option>', ...options].join("");
    }

    function showCatalogImage(row) {
        const selectedOption = row.querySelector(".catalogo-select option:checked");
        const image = selectedOption?.dataset.imagen || "";
        const imageElement = row.querySelector(".catalogo-preview-img");
        const placeholder = row.querySelector(".catalogo-preview-placeholder");

        if (image) {
            imageElement.src = `/storage/${image.replace(/^\/+/, "")}`;
            imageElement.style.display = "inline";
            placeholder.style.display = "none";
        } else {
            imageElement.removeAttribute("src");
            imageElement.style.display = "none";
            placeholder.style.display = "block";
        }
    }

    function updateRowNumbers() {
        tableBody.querySelectorAll("tr").forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    }

    function calculateTotals() {
        let subtotalCents = 0;

        tableBody.querySelectorAll("tr").forEach(row => {
            const quantity = Number(row.querySelector(".cantidad-input").value);
            const unitPrice = Number(row.querySelector(".valor-unitario-input").value);
            const lineCents = Number.isFinite(quantity) && Number.isFinite(unitPrice)
                ? Math.round(quantity * unitPrice * 100)
                : 0;

            row.querySelector(".valor-total-input").value = (lineCents / 100).toFixed(2);
            subtotalCents += lineCents;
        });

        const taxCents = Math.round(subtotalCents * 0.16);
        document.querySelector('[name="Subtotal"]').value = (subtotalCents / 100).toFixed(2);
        document.querySelector('[name="IVA"]').value = (taxCents / 100).toFixed(2);
        document.querySelector('[name="Total"]').value = ((subtotalCents + taxCents) / 100).toFixed(2);
    }

    function addRow(detalle = {}) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td></td>
            <td><input type="text" class="form-control item-input" name="item[]" placeholder="1,2,2 . . . 5.4"></td>
            <td><select class="form-control catalogo-select" name="catalogo[]">${catalogOptions(detalle.catalogo_id || "")}</select></td>
            <td><textarea class="form-control descripcion-input" name="descripcion[]" placeholder="Descripción"></textarea></td>
            <td class="text-center">
                <img class="catalogo-preview-img" src="" alt="Imagen del catálogo" style="width: 52px; height: 52px; object-fit: contain; border-radius: 8px; border: 1px solid #ddd; display: none;">
                <span class="catalogo-preview-placeholder text-muted small" style="display: block;">Sin imagen</span>
            </td>
            <td><input type="number" class="form-control cantidad-input" name="cantidad[]" placeholder="Cantidad" min="0" step="any"></td>
            <td><input type="text" class="form-control unidad-input" name="unidad[]" placeholder="Unidad/Medida"></td>
            <td><input type="number" class="form-control valor-unitario-input" name="valor_unitario[]" placeholder="Valor Unitario" min="0" step="0.01"></td>
            <td><input type="number" class="form-control valor-total-input" name="valor_total[]" placeholder="Valor Total" step="0.01" readonly></td>
            <td><button type="button" class="btn btn-danger btnEliminar"><i class="fa fa-times" aria-hidden="true"></i></button></td>
        `;

        row.querySelector(".item-input").value = detalle.item || "";
        row.querySelector(".descripcion-input").value = detalle.descripcion || "";
        row.querySelector(".cantidad-input").value = detalle.cantidad || "";
        row.querySelector(".unidad-input").value = detalle.unidad || "";
        row.querySelector(".valor-unitario-input").value = detalle.valor_unitario || "";
        tableBody.appendChild(row);
        showCatalogImage(row);
        updateRowNumbers();
    }

    tableBody.querySelectorAll("tr").forEach(row => {
        const select = row.querySelector(".catalogo-select");
        const savedCatalogId = select.value;
        select.innerHTML = catalogOptions(savedCatalogId);
        showCatalogImage(row);
    });

    document.getElementById("addRowBtn").addEventListener("click", () => addRow());

    tableBody.addEventListener("input", event => {
        if (event.target.matches(".cantidad-input, .valor-unitario-input")) {
            calculateTotals();
        }
    });

    tableBody.addEventListener("change", event => {
        if (!event.target.matches(".catalogo-select")) {
            return;
        }

        const row = event.target.closest("tr");
        const option = event.target.selectedOptions[0];
        row.querySelector(".descripcion-input").value = option.dataset.descripcion || "";
        row.querySelector(".unidad-input").value = option.dataset.unidad || "";
        showCatalogImage(row);
    });

    tableBody.addEventListener("click", event => {
        if (event.target.closest(".btnEliminar")) {
            event.target.closest("tr").remove();
            updateRowNumbers();
            calculateTotals();
        }
    });

    form.addEventListener("keydown", event => {
        if (event.key === "Enter" && event.target.tagName !== "TEXTAREA") {
            event.preventDefault();
        }
    });

    form.addEventListener("submit", () => {
        const detalles = Array.from(tableBody.querySelectorAll("tr"), row => {
            const catalogoSelect = row.querySelector(".catalogo-select");
            const catalogoOption = catalogoSelect?.querySelector("option:checked");

            return {
                item: row.querySelector(".item-input")?.value || "",
                catalogo_id: catalogoSelect?.value || "",
                imagen: catalogoOption?.dataset.imagen || "",
                descripcion: row.querySelector(".descripcion-input").value,
                cantidad: row.querySelector(".cantidad-input").value,
                unidad: row.querySelector(".unidad-input").value,
                valor_unitario: row.querySelector(".valor-unitario-input").value,
                valor_total: row.querySelector(".valor-total-input").value,
            };
        });

        document.getElementById("dynamicTableData").value = JSON.stringify(detalles);
    });

    updateRowNumbers();
    calculateTotals();
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
