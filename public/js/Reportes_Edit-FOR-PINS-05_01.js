(function (window, $) {
    'use strict';

    function reemplazarEventosEliminacion(administradorTabla) {
        $('#dynamicTable').off('click', '.btnEliminar');
        $('#dynamicTable').off('click', '.btnEliminarTitulo');

        $('#dynamicTable').on('click.combinacionPins0501Edit', '.btnEliminar', function () {
            var $fila = $(this).closest('tr');
            var esLongitud = $fila.hasClass('long-row');

            if (!$fila.hasClass('titulo-row') && !esLongitud) {
                administradorTabla.handleDeleteRow($fila);
            }

            $fila.remove();

            if (typeof window.verificarYAgregarLongitud === 'function') {
                window.verificarYAgregarLongitud();
            }

            if (typeof window.updateRowNumbers === 'function') {
                window.updateRowNumbers();
            }

            if (typeof window.updateTitulos === 'function') {
                window.updateTitulos();
            }

            administradorTabla.refresh();
        });

        $('#dynamicTable').on('click.combinacionPins0501Edit', '.btnEliminarTitulo', function () {
            var groupId = $(this).closest('tr.titulo-row').data('titulo');

            administradorTabla.clearGroup(groupId);
            $('#dynamicTable tbody tr').filter(function () {
                return ($(this).data('titulo') || 'sin_titulo') === groupId;
            }).remove();

            if (typeof window.verificarYAgregarLongitud === 'function') {
                window.verificarYAgregarLongitud();
            }

            if (typeof window.updateRowNumbers === 'function') {
                window.updateRowNumbers();
            }

            if (typeof window.updateTitulos === 'function') {
                window.updateTitulos();
            }

            administradorTabla.refresh();
        });
    }

    function enlazarRefrescosEstructura(administradorTabla) {
        $('#addBtn, #addTituloBtn, #addLongBtn, #preFillBtn').off('.combinacionPins0501EditRefresh').on('click.combinacionPins0501EditRefresh', function () {
            window.setTimeout(function () {
                administradorTabla.refresh();
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
