(function (window, $) {
    'use strict';

    window.__FOR_PIMP_02_B_04_CREATE_EXTERNAL_CELL_MERGE = true;

    // Rellena el formulario principal sin tocar archivos ni campos ya capturados.
    function inicializarRellenoGeneral() {
        var form = document.getElementById('FOR-PIMP-02_B_04');
        var botonRellenoGeneral;
        var inputs;
        var textareas;

        if (!form) {
            return;
        }

        botonRellenoGeneral = form.querySelector('#preFormBtn');
        inputs = form.querySelectorAll('input:not([type="file"]):not([type="hidden"]), select');
        textareas = form.querySelectorAll('textarea');

        if (!botonRellenoGeneral) {
            return;
        }

        botonRellenoGeneral.addEventListener('click', function () {
            inputs.forEach(function (input) {
                if (input.disabled || input.readOnly || input.value.trim() !== '') {
                    return;
                }

                if (input.tagName === 'SELECT') {
                    return;
                }

                if (input.type === 'date') {
                    input.value = new Date().toISOString().split('T')[0];
                    return;
                }

                input.value = '---';
            });

            textareas.forEach(function (textarea) {
                if (textarea.disabled || textarea.readOnly || textarea.value.trim() !== '') {
                    return;
                }

                textarea.value = '---';
            });
        });
    }

    // Controla cuantas secciones de firmas se muestran segun el select.
    function inicializarSelectorFirmas() {
        var selectorNumeroFirmas = document.getElementById('numFirmas');
        var seccionesFirmas;

        if (!selectorNumeroFirmas) {
            return;
        }

        seccionesFirmas = ['1', '2', '3', '4'].map(function (value) {
            return {
                value: value,
                element: document.getElementById('firmas' + value)
            };
        });

        // Aplica la vista correspondiente al numero de firmas seleccionado.
        function renderizarFirmas() {
            seccionesFirmas.forEach(function (section) {
                if (!section.element) {
                    return;
                }

                section.element.style.display = section.value === selectorNumeroFirmas.value ? 'block' : 'none';
            });
        }

        selectorNumeroFirmas.addEventListener('change', renderizarFirmas);
        renderizarFirmas();
    }

    function obtenerValoresPlantillaDureza() {
        var valores = {};

        $('#durezaAutoFillBody [data-auto-fill-field]').each(function () {
            var campo = $(this).data('auto-fill-field');
            valores[campo] = $(this).val() || '';
        });

        return valores;
    }

    function sincronizarCampoPlantilla(campo, valor) {
        $('#durezaBrinellBody tr').each(function () {
            $(this).find('input[name$="[' + campo + ']"]').val(valor);
        });
    }

    function inicializarAutoRellenoDureza() {
        $('#durezaAutoFillBody').on('input', '[data-auto-fill-field]', function () {
            sincronizarCampoPlantilla($(this).data('auto-fill-field'), $(this).val() || '');
        });
    }

    // Genera filas nuevas para la tabla con columnas combinables verticalmente.
    // Si otro formato necesita mas columnas combinables, agrega aqui nuevos
    // <td class="mergeable-cell" data-merge-field="nombre_campo">...</td>.
    function construirFilaTablaCombinable(index, data) {
        var row = $.extend({}, obtenerValoresPlantillaDureza(), data || {});

        return '' +
            '<tr>' +
                '<td class="mergeable-cell" data-merge-field="descripcion"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][descripcion]" value="' + (row.descripcion || '') + '"></td>' +
                '<td class="mergeable-cell" data-merge-field="horario"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][horario]" value="' + (row.horario || '') + '"></td>' +
                '<td class="mergeable-cell" data-merge-field="metal_base_a"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][metal_base_a]" value="' + (row.metal_base_a || '') + '"></td>' +
                '<td class="mergeable-cell" data-merge-field="zac_b"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][zac_b]" value="' + (row.zac_b || '') + '"></td>' +
                '<td class="mergeable-cell" data-merge-field="soldadura_c"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][soldadura_c]" value="' + (row.soldadura_c || '') + '"></td>' +
                '<td class="mergeable-cell" data-merge-field="zac_b1"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][zac_b1]" value="' + (row.zac_b1 || '') + '"></td>' +
                '<td class="mergeable-cell" data-merge-field="metal_base_a1"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][metal_base_a1]" value="' + (row.metal_base_a1 || '') + '"></td>' +
                '<td class="mergeable-cell" data-merge-field="observaciones"><input type="text" class="form-control inputForm" name="Dureza[' + index + '][observaciones]" value="' + (row.observaciones || '') + '"></td>' +
                '<td><button type="button" class="btn btn-danger btn-sm btnEliminarDureza"><i class="fa fa-times" aria-hidden="true"></i></button></td>' +
            '</tr>';
    }

    // Inicializa la tabla con el administrador general de combinacion de celdas.
    function inicializarTablaCombinable() {
        if (!window.ReportesCombinacionCeldas || !$('#durezaBrinellBody').length) {
            return;
        }

        window.__FOR_PIMP_02_B_04_CREATE_CELL_MERGE_MANAGER = window.ReportesCombinacionCeldas.crearAdministrador({
            tbodySelector: '#durezaBrinellBody',
            hiddenSelector: '#durezaMergeConfig',
            addButtonSelector: '#addDurezaRowsBtn',
            mergeButtonSelector: '#mergeSelectedCellsBtn',
            splitButtonSelector: '#splitSelectedCellsBtn',
            fillButtonSelector: '#fillEmptyDurezaBtn',
            numRowsSelector: '#numRows',
            selectionInfoSelector: '#durezaMergeSelectionInfo',
            rowBuilder: construirFilaTablaCombinable,
            modeToggleAfterSelector: '#toggleCombinacionBtn'
        });

        window.__FOR_PIMP_02_B_04_CREATE_CELL_MERGE_MANAGER.init();
    }

    // Conecta los comportamientos del formulario create del formato.
    document.addEventListener('DOMContentLoaded', function () {
        inicializarRellenoGeneral();
        inicializarSelectorFirmas();
        inicializarAutoRellenoDureza();
        inicializarTablaCombinable();
    });
}(window, window.jQuery));
