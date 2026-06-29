(function (window, $) {
    'use strict';

    function obtenerIdFormulario() {
        return $('#dynamicTable').closest('form').attr('id') || (document.querySelectorAll('form')[1] && document.querySelectorAll('form')[1].id) || '';
    }

    function reemplazarEventosEliminacion(administradorTabla) {
        $('#dynamicTable').off('click', '.btnEliminar');
        $(document).off('click', '.btnEliminarTitulo');

        $('#dynamicTable').on('click.combinacionPins0501', '.btnEliminar', function () {
            var $fila = $(this).closest('tr');
            var esLongitud = $fila.hasClass('long-row');

            if (!$fila.hasClass('titulo-row') && !esLongitud) {
                administradorTabla.handleDeleteRow($fila);
            }

            $fila.remove();

            if (typeof window.updateRowNumbers === 'function') {
                window.updateRowNumbers();
            }

            if (!esLongitud && typeof window.verificarYAgregarLongitud === 'function') {
                window.verificarYAgregarLongitud();
            }

            administradorTabla.refresh();

            if (typeof window.saveData === 'function') {
                window.saveData(obtenerIdFormulario());
            }
        });

        $(document).on('click.combinacionPins0501', '.btnEliminarTitulo', function () {
            var groupId = $(this).closest('tr.titulo-row').data('titulo');

            administradorTabla.clearGroup(groupId);
            $('#dynamicTable tbody tr').filter(function () {
                return ($(this).data('titulo') || 'sin_titulo') === groupId;
            }).remove();

            if (typeof window.updateTitulos === 'function') {
                window.updateTitulos();
            }

            if (typeof window.updateRowNumbers === 'function') {
                window.updateRowNumbers();
            }

            administradorTabla.refresh();

            if (typeof window.saveData === 'function') {
                window.saveData(obtenerIdFormulario());
            }
        });
    }

    function enlazarRefrescosEstructura(administradorTabla) {
        $('#addBtn, #addTituloBtn, #addLongBtn, #preFillBtn').off('.combinacionPins0501Refresh').on('click.combinacionPins0501Refresh', function () {
            window.setTimeout(function () {
                administradorTabla.refresh();

                if (typeof window.saveData === 'function') {
                    window.saveData(obtenerIdFormulario());
                }
            }, 0);
        });
    }

    $(document).ready(function () {
        var administradorTabla;

        if (!window.ReportesCombinacionCeldasAgrupadas || !$('#dynamicTable tbody').length) {
            return;
        }

        administradorTabla = window.ReportesCombinacionCeldasAgrupadas.crearAdministrador({
            tbodySelector: '#dynamicTable tbody',
            hiddenSelector: '#tablaCombinacionConfig',
            mergeButtonSelector: '#mergeSelectedCellsBtn',
            splitButtonSelector: '#splitSelectedCellsBtn',
            selectionInfoSelector: '#tablaMergeSelectionInfo',
            columnasCombinables: [
                { index: 1, field: 'numero_junta' },
                { index: 3, field: 'angulo_inspeccion' },
                { index: 17, field: 'observaciones' }
            ]
        });

        administradorTabla.init();
        reemplazarEventosEliminacion(administradorTabla);
        enlazarRefrescosEstructura(administradorTabla);
        administradorTabla.refresh();
    });
}(window, window.jQuery));
