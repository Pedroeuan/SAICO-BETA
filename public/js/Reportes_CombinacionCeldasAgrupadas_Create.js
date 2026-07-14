(function (window, $) {
    'use strict';

    // Obtiene el formulario activo para reutilizar el guardado propio del formato.
    function obtenerIdFormulario() {
        return $('#dynamicTable').closest('form').attr('id') || (document.querySelectorAll('form')[1] && document.querySelectorAll('form')[1].id) || '';
    }

    // Ejecuta el guardado local del formato si existe.
    function guardarTablaActual() {
        if (typeof window.saveData === 'function') {
            window.saveData(obtenerIdFormulario());
        }
    }

    // Renumera filas si el formato expone la funcion global.
    function renumerarFilasSiExiste() {
        if (typeof window.updateRowNumbers === 'function') {
            window.updateRowNumbers();
        }
    }

    // Recalcula la fila de longitud automatica si el formato la utiliza.
    function verificarLongitudSiExiste() {
        if (typeof window.verificarYAgregarLongitud === 'function') {
            window.verificarYAgregarLongitud();
        }
    }

    // Actualiza la lista de titulos del formato si existe.
    function actualizarTitulosSiExiste() {
        if (typeof window.updateTitulos === 'function') {
            window.updateTitulos();
        }
    }

    // Reemplaza la eliminacion nativa para que las combinaciones no se desfasen.
    function reemplazarEventosEliminacion(administradorTabla) {
        $('#dynamicTable').off('click', '.btnEliminar');
        $('#dynamicTable').off('click', '.btnEliminarTitulo');
        $(document).off('click', '.btnEliminarTitulo');

        $('#dynamicTable').on('click.combinacionCeldasCreate', '.btnEliminar', function () {
            var $fila = $(this).closest('tr');
            var esLongitud = $fila.hasClass('long-row');

            if (!$fila.hasClass('titulo-row') && !esLongitud) {
                administradorTabla.handleDeleteRow($fila);
            }

            $fila.remove();
            renumerarFilasSiExiste();

            if (!esLongitud) {
                verificarLongitudSiExiste();
            }

            actualizarTitulosSiExiste();
            administradorTabla.refresh();
            guardarTablaActual();
        });

        $(document).on('click.combinacionCeldasCreate', '.btnEliminarTitulo', function () {
            var groupId = $(this).closest('tr.titulo-row').data('titulo');

            administradorTabla.clearGroup(groupId);
            $('#dynamicTable tbody tr').filter(function () {
                return ($(this).data('titulo') || 'sin_titulo') === groupId;
            }).remove();

            renumerarFilasSiExiste();
            actualizarTitulosSiExiste();
            administradorTabla.refresh();
            guardarTablaActual();
        });
    }

    // Refresca las combinaciones despues de cualquier cambio estructural en la tabla.
    function enlazarRefrescosEstructura(administradorTabla) {
        $('#addBtn, #addTituloBtn, #addLongBtn, #preFillBtn')
            .off('.combinacionCeldasCreateRefresh')
            .on('click.combinacionCeldasCreateRefresh', function () {
                window.setTimeout(function () {
                    administradorTabla.refresh();
                    guardarTablaActual();
                }, 0);
            });
    }

    // Inicializa la combinacion agrupada con configuracion comun para formatos create.
    function inicializarCombinacionCeldasCreate() {
        var administradorTabla;

        if (!window.ReportesCombinacionCeldasAgrupadas || !$('#dynamicTable tbody').length) {
            return;
        }

        administradorTabla = window.ReportesCombinacionCeldasAgrupadas.crearAdministrador({
            tbodySelector: '#dynamicTable tbody',
            hiddenSelector: '#tablaCombinacionConfig',
            selectionInfoSelector: '#tablaMergeSelectionInfo',
            // Detecta cualquier columna de captura visible del renglon de datos.
            inferirColumnasCombinables: true
        });

        administradorTabla.init();
        reemplazarEventosEliminacion(administradorTabla);
        enlazarRefrescosEstructura(administradorTabla);
        administradorTabla.refresh();
    }

    $(document).ready(inicializarCombinacionCeldasCreate);
}(window, window.jQuery));
