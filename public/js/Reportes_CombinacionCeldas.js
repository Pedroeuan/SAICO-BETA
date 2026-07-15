(function (window, $) {
    'use strict';

    if (!$) {
        return;
    }

    // Centraliza las alertas para usar SweetAlert cuando este disponible.
    function mostrarAlerta(mensaje, icono) {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                icon: icono || 'warning',
                title: 'Atencion',
                text: mensaje,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#3085d6',
                background: '#ffffff',
                width: 420
            });
            return;
        }

        window.alert(mensaje);
    }

    // Solicita confirmacion antes de combinar o separar, igual que FOR-PINS-03_02.
    function mostrarConfirmacion(opciones) {
        var configuracionDialogo = $.extend({
            icon: 'question',
            title: 'Confirmar accion',
            text: '',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d'
        }, opciones || {});

        if (window.Swal && typeof window.Swal.fire === 'function') {
            return window.Swal.fire({
                icon: configuracionDialogo.icon,
                title: configuracionDialogo.title,
                text: configuracionDialogo.text,
                showCancelButton: true,
                confirmButtonText: configuracionDialogo.confirmButtonText,
                cancelButtonText: configuracionDialogo.cancelButtonText,
                confirmButtonColor: configuracionDialogo.confirmButtonColor,
                cancelButtonColor: configuracionDialogo.cancelButtonColor
            }).then(function (resultado) {
                return !!resultado.isConfirmed;
            });
        }

        return Promise.resolve(window.confirm(configuracionDialogo.text || 'Deseas continuar?'));
    }

    // Crea un administrador reutilizable para combinaciones verticales por columna.
    function crearAdministrador(opciones) {
        var configuracion = $.extend(true, {
            tbodySelector: '#durezaBrinellBody',
            hiddenSelector: '#durezaMergeConfig',
            rowBuilder: null,
            addButtonSelector: '#addDurezaRowsBtn',
            mergeButtonSelector: '#mergeSelectedCellsBtn',
            splitButtonSelector: '#splitSelectedCellsBtn',
            fillButtonSelector: '#fillEmptyDurezaBtn',
            numRowsSelector: '#numRows',
            selectionInfoSelector: '#durezaMergeSelectionInfo',
            deleteButtonSelector: '.btnEliminarDureza',
            mergeableCellSelector: '.mergeable-cell',
            inputNamePrefix: 'Dureza',
            automaticSelectionActions: false
        }, opciones || {});

        var $tbody = $(configuracion.tbodySelector);
        var $hidden = $(configuracion.hiddenSelector);
        var $botonAgregar = $(configuracion.addButtonSelector);
        var $botonCombinar = $(configuracion.mergeButtonSelector);
        var $botonSeparar = $(configuracion.splitButtonSelector);
        var $botonRellenar = $(configuracion.fillButtonSelector);
        var $selectorFilas = $(configuracion.numRowsSelector);
        var $infoSeleccion = $(configuracion.selectionInfoSelector);
        var celdaAnclaSeleccion = null;
        var estadoCombinaciones = [];
        var combinacionActiva = !configuracion.modeToggleAfterSelector;
        var $contenedorControlModo = $();
        var $botonModoCombinacion = $();

        // Obtiene la cantidad actual de filas dentro del tbody configurado.
        function obtenerTotalFilas() {
            return $tbody.find('tr').length;
        }

        // Genera una clave estable para ubicar una combinacion especifica.
        function obtenerClaveCombinacion(item) {
            return item.field + '|' + item.startRow;
        }

        // Limpia configuraciones invalidas, traslapadas o fuera de rango antes de aplicar.
        function normalizarConfiguracionCombinaciones(estado) {
            var totalFilas = obtenerTotalFilas();
            var normalizado = [];
            var ocupadas = {};

            (Array.isArray(estado) ? estado : [])
                .map(function (item) {
                    return {
                        field: item && item.field ? item.field : '',
                        startRow: Number(item && (item.startRow != null ? item.startRow : item.row)),
                        rowspan: Number(item && item.rowspan)
                    };
                })
                .sort(function (a, b) {
                    if (a.field === b.field) {
                        return a.startRow - b.startRow;
                    }

                    return a.field.localeCompare(b.field);
                })
                .forEach(function (item) {
                    var finCombinacion;
                    var seTraslapa;

                    if (!item.field || item.startRow < 0 || item.rowspan < 2) {
                        return;
                    }

                    if ((item.startRow + item.rowspan) > totalFilas) {
                        return;
                    }

                    finCombinacion = item.startRow + item.rowspan - 1;
                    ocupadas[item.field] = ocupadas[item.field] || [];

                    seTraslapa = ocupadas[item.field].some(function (rango) {
                        return item.startRow <= rango.end && finCombinacion >= rango.start;
                    });

                    if (seTraslapa) {
                        return;
                    }

                    ocupadas[item.field].push({ start: item.startRow, end: finCombinacion });
                    normalizado.push({
                        field: item.field,
                        startRow: item.startRow,
                        rowspan: item.rowspan
                    });
                });

            return normalizado;
        }

        // Convierte el estado interno al formato JSON persistido en el input hidden.
        function serializarConfiguracionCombinaciones(estado) {
            return normalizarConfiguracionCombinaciones(estado).map(function (item) {
                return {
                    field: item.field,
                    startRow: Number(item.startRow),
                    rowspan: Number(item.rowspan)
                };
            });
        }

        // Guarda el estado saneado para que la tabla pueda reconstruirse al recargar.
        function guardarEstadoCombinaciones() {
            estadoCombinaciones = normalizarConfiguracionCombinaciones(estadoCombinaciones);
            $hidden.val(JSON.stringify(serializarConfiguracionCombinaciones(estadoCombinaciones)));
        }

        // Lee el estado guardado desde Blade, old() o recarga y lo normaliza.
        function leerEstadoCombinaciones() {
            var estadoCrudo = $hidden.val();

            if (!estadoCrudo) {
                estadoCombinaciones = [];
                return;
            }

            try {
                estadoCombinaciones = normalizarConfiguracionCombinaciones(JSON.parse(estadoCrudo));
            } catch (error) {
                estadoCombinaciones = [];
            }

            guardarEstadoCombinaciones();
        }

        // Busca una celda combinable segun fila y nombre de campo.
        function obtenerCelda(filaIndex, campo) {
            return $tbody
                .find('tr')
                .eq(filaIndex)
                .find(configuracion.mergeableCellSelector + '[data-merge-field="' + campo + '"]');
        }

        // Limpia cualquier seleccion visual activa.
        function limpiarSeleccionVisual() {
            $tbody.find(configuracion.mergeableCellSelector).removeClass('selected-merge merge-preview merge-anchor');
            $infoSeleccion.text('');
        }

        function obtenerClaveModoCombinacion() {
            var idFormulario = $tbody.closest('form').attr('id') || 'formulario_sin_id';
            return 'modo_combinacion_celdas_' + idFormulario + '_' + String(configuracion.hiddenSelector || configuracion.tbodySelector || 'tabla').replace(/[^a-z0-9_-]/gi, '_');
        }

        function leerEstadoModoCombinacion() {
            if (!configuracion.modeToggleAfterSelector) {
                combinacionActiva = true;
                return;
            }

            try {
                combinacionActiva = sessionStorage.getItem(obtenerClaveModoCombinacion()) === '1';
            } catch (error) {
                combinacionActiva = false;
            }
        }

        function guardarEstadoModoCombinacion() {
            if (!configuracion.modeToggleAfterSelector) {
                return;
            }

            try {
                sessionStorage.setItem(obtenerClaveModoCombinacion(), combinacionActiva ? '1' : '0');
            } catch (error) {
                // Ignora bloqueos del navegador en sessionStorage.
            }
        }

        function actualizarIndicadorModoCombinacion() {
            if (!$botonModoCombinacion.length) {
                return;
            }

            if (combinacionActiva) {
                $botonModoCombinacion.removeClass('btn-success').addClass('btn-secondary').text('Desactivar combinación');
                return;
            }

            $botonModoCombinacion.removeClass('btn-secondary').addClass('btn-success').text('Activar combinación');
        }

        function crearControlModoCombinacion() {
            var $anclaModo;

            if (!configuracion.modeToggleAfterSelector || $botonModoCombinacion.length) {
                return;
            }

            $botonModoCombinacion = $(configuracion.modeToggleAfterSelector).first();

            if ($botonModoCombinacion.length) {
                $botonModoCombinacion.addClass('merge-mode-toggle');
            } else {
                $anclaModo = $(configuracion.modeToggleAfterSelector).first();
                $contenedorControlModo = $('<span class="merge-mode-toolbar" style="display:inline-flex;align-items:center;gap:10px;margin-left:10px;"></span>');
                $botonModoCombinacion = $('<button type="button" class="btn btn-success custom-btn merge-mode-toggle"></button>');
                $contenedorControlModo.append($botonModoCombinacion);

                if ($anclaModo.length) {
                    $anclaModo.after($contenedorControlModo);
                }
            }

            $botonModoCombinacion.on('click.combinacionCeldasModo', function () {
                combinacionActiva = !combinacionActiva;
                guardarEstadoModoCombinacion();

                if (!combinacionActiva) {
                    celdaAnclaSeleccion = null;
                    limpiarSeleccionVisual();
                }

                actualizarIndicadorModoCombinacion();
            });

            actualizarIndicadorModoCombinacion();
        }

        // Restaura la vista base antes de reaplicar las combinaciones persistidas.
        function limpiarCombinacionesVisuales() {
            $tbody.find(configuracion.mergeableCellSelector).each(function () {
                $(this)
                    .show()
                    .removeAttr('rowspan')
                    .removeAttr('data-merge-hidden');
            });
        }

        // Devuelve la combinacion a la que pertenece la celda indicada.
        function obtenerCombinacionDeCelda($celda) {
            var campo;
            var indiceFila;

            if (!$celda.length) {
                return null;
            }

            campo = $celda.data('merge-field');
            indiceFila = $celda.closest('tr').index();

            return estadoCombinaciones.find(function (item) {
                return item.field === campo &&
                    indiceFila >= item.startRow &&
                    indiceFila < (item.startRow + item.rowspan);
            }) || null;
        }

        // Replica el valor principal hacia las celdas hijas de una combinacion.
        function sincronizarValorCombinado(itemCombinacion) {
            var $celdaPrincipal;
            var valorPrincipal;
            var offset;

            if (!itemCombinacion) {
                return;
            }

            $celdaPrincipal = obtenerCelda(itemCombinacion.startRow, itemCombinacion.field);

            if (!$celdaPrincipal.length) {
                return;
            }

            valorPrincipal = $celdaPrincipal.find('input').val() || '';

            for (offset = 1; offset < itemCombinacion.rowspan; offset += 1) {
                obtenerCelda(itemCombinacion.startRow + offset, itemCombinacion.field)
                    .find('input')
                    .val(valorPrincipal);
            }
        }

        // Reconstruye los rowspans y oculta visualmente las celdas hijas.
        function aplicarEstadoCombinaciones() {
            var offset;

            limpiarCombinacionesVisuales();
            estadoCombinaciones = normalizarConfiguracionCombinaciones(estadoCombinaciones);

            estadoCombinaciones.forEach(function (item) {
                var $celdaPrincipal = obtenerCelda(item.startRow, item.field);
                var valorPrincipal;

                if (!$celdaPrincipal.length) {
                    return;
                }

                valorPrincipal = $celdaPrincipal.find('input').val() || '';
                $celdaPrincipal.attr('rowspan', item.rowspan);

                for (offset = 1; offset < item.rowspan; offset += 1) {
                    var $celdaHija = obtenerCelda(item.startRow + offset, item.field);

                    if (!$celdaHija.length) {
                        continue;
                    }

                    $celdaHija
                        .attr('data-merge-hidden', 'true')
                        .find('input')
                        .val(valorPrincipal);

                    $celdaHija.hide();
                }
            });

            guardarEstadoCombinaciones();
        }

        // Calcula el rango vertical desde la celda ancla hasta la celda destino.
        function obtenerRangoSeleccion($celdaInicio, $celdaFin) {
            var filaInicio = $celdaInicio.closest('tr').index();
            var filaFin = $celdaFin.closest('tr').index();

            return {
                field: $celdaInicio.data('merge-field'),
                startRow: Math.min(filaInicio, filaFin),
                endRow: Math.max(filaInicio, filaFin)
            };
        }

        // Pinta visualmente el rango para que el usuario vea lo que se va a combinar.
        function pintarSeleccion(rango) {
            var indiceFila;

            limpiarSeleccionVisual();

            for (indiceFila = rango.startRow; indiceFila <= rango.endRow; indiceFila += 1) {
                obtenerCelda(indiceFila, rango.field).addClass('selected-merge merge-preview');
            }

            $infoSeleccion.text('Rango seleccionado: ' + rango.field + ' filas ' + (rango.startRow + 1) + ' a ' + (rango.endRow + 1));
        }

        // Valida si el nuevo rango choca con una combinacion existente del mismo campo.
        function existeConflictoEnRango(rango) {
            return estadoCombinaciones.some(function (item) {
                var finCombinacion;

                if (item.field !== rango.field) {
                    return false;
                }

                finCombinacion = item.startRow + item.rowspan - 1;
                return rango.startRow <= finCombinacion && rango.endRow >= item.startRow;
            });
        }

        // Muestra el nombre del campo de forma legible en las confirmaciones.
        function obtenerEtiquetaCampo(campo) {
            return String(campo || '')
                .replace(/_/g, ' ')
                .replace(/\b\w/g, function (letra) {
                    return letra.toUpperCase();
                });
        }

        function confirmarCombinacion(rango) {
            return mostrarConfirmacion({
                icon: 'question',
                title: 'Combinar celdas',
                text: 'Campo: ' + obtenerEtiquetaCampo(rango.field) +
                    '. Filas: ' + (rango.startRow + 1) + ' a ' + (rango.endRow + 1) +
                    '. Deseas combinar este rango?',
                confirmButtonText: 'Combinar',
                confirmButtonColor: '#0d6efd'
            });
        }

        function confirmarSeparacion(itemCombinacion) {
            return mostrarConfirmacion({
                icon: 'warning',
                title: 'Separar celdas',
                text: 'Campo: ' + obtenerEtiquetaCampo(itemCombinacion.field) +
                    '. Filas: ' + (itemCombinacion.startRow + 1) + ' a ' +
                    (itemCombinacion.startRow + itemCombinacion.rowspan) +
                    '. Deseas separar esta combinacion?',
                confirmButtonText: 'Separar',
                confirmButtonColor: '#6c757d'
            });
        }

        // Renumera los names tipo Prefijo[index] luego de agregar o eliminar filas.
        function renumerarFilas() {
            $tbody.find('tr').each(function (indice) {
                $(this).find('input').each(function () {
                    var nombreActual = $(this).attr('name') || '';
                    $(this).attr('name', nombreActual.replace(/Dureza\[\d+\]/, configuracion.inputNamePrefix + '[' + indice + ']'));
                });
            });
        }

        // Ajusta las combinaciones cuando se elimina una fila intermedia o principal.
        function ajustarEstadoTrasEliminarFila(indiceFilaEliminada) {
            estadoCombinaciones = estadoCombinaciones.reduce(function (acumulador, item) {
                var finCombinacion = item.startRow + item.rowspan - 1;

                if (indiceFilaEliminada < item.startRow) {
                    acumulador.push({
                        field: item.field,
                        startRow: item.startRow - 1,
                        rowspan: item.rowspan
                    });
                    return acumulador;
                }

                if (indiceFilaEliminada > finCombinacion) {
                    acumulador.push(item);
                    return acumulador;
                }

                if (item.rowspan - 1 >= 2) {
                    acumulador.push({
                        field: item.field,
                        startRow: item.startRow,
                        rowspan: item.rowspan - 1
                    });
                }

                return acumulador;
            }, []);
        }

        // Controla la seleccion por ancla y evita combinar columnas distintas.
        function manejarSeleccionDeCelda($celda) {
            var mismoCampo;
            var mismaFila;
            var itemCombinacion;
            var indiceFilaActual;
            var rango;

            if (!$celda.length || !$celda.is(':visible')) {
                return;
            }

            if (!combinacionActiva) {
                return;
            }

            itemCombinacion = obtenerCombinacionDeCelda($celda);
            indiceFilaActual = $celda.closest('tr').index();

            // Al seleccionar la celda principal combinada, solicita separarla.
            if (configuracion.automaticSelectionActions &&
                    !celdaAnclaSeleccion &&
                    itemCombinacion &&
                    itemCombinacion.startRow === indiceFilaActual) {
                limpiarSeleccionVisual();
                $celda.addClass('selected-merge merge-preview');

                confirmarSeparacion(itemCombinacion).then(function (confirmado) {
                    if (confirmado) {
                        separarCeldasSeleccionadas();
                        return;
                    }

                    limpiarSeleccionVisual();
                });
                return;
            }

            if (!celdaAnclaSeleccion) {
                limpiarSeleccionVisual();
                $celda.addClass('selected-merge merge-anchor');
                celdaAnclaSeleccion = $celda;
                return;
            }

            mismoCampo = celdaAnclaSeleccion.data('merge-field') === $celda.data('merge-field');
            mismaFila = celdaAnclaSeleccion.closest('tr').index() === $celda.closest('tr').index();

            if (!mismoCampo) {
                limpiarSeleccionVisual();
                celdaAnclaSeleccion = null;
                mostrarAlerta('Solo puedes seleccionar celdas de la misma columna para combinar.');
                return;
            }

            if (mismaFila) {
                limpiarSeleccionVisual();
                celdaAnclaSeleccion = null;
                return;
            }

            rango = obtenerRangoSeleccion(celdaAnclaSeleccion, $celda);
            pintarSeleccion(rango);
            celdaAnclaSeleccion = null;

            // La segunda celda abre directamente la confirmacion de combinacion.
            if (configuracion.automaticSelectionActions) {
                if (existeConflictoEnRango(rango)) {
                    limpiarSeleccionVisual();
                    mostrarAlerta('Primero separa la combinacion actual antes de crear una nueva en ese rango.');
                    return;
                }

                confirmarCombinacion(rango).then(function (confirmado) {
                    if (confirmado) {
                        combinarCeldasSeleccionadas();
                        return;
                    }

                    limpiarSeleccionVisual();
                });
            }
        }

        // Obtiene solo las celdas visibles incluidas en la seleccion actual.
        function obtenerCeldasSeleccionadas() {
            return $tbody.find(configuracion.mergeableCellSelector + '.selected-merge:visible');
        }

        // Convierte la seleccion actual en una combinacion persistente.
        function combinarCeldasSeleccionadas() {
            var $seleccionadas = obtenerCeldasSeleccionadas();
            var campo;
            var indicesFila;
            var mismoCampo;
            var consecutivas;
            var rango;

            if (!combinacionActiva) {
                mostrarAlerta('Activa el modo de combinacion para unir celdas.', 'info');
                return;
            }

            if ($seleccionadas.length < 2) {
                mostrarAlerta('Selecciona al menos 2 celdas consecutivas de la misma columna para combinar.');
                return;
            }

            campo = $seleccionadas.first().data('merge-field');
            indicesFila = $seleccionadas.map(function () {
                return $(this).closest('tr').index();
            }).get().sort(function (a, b) {
                return a - b;
            });

            mismoCampo = $seleccionadas.toArray().every(function (celda) {
                return $(celda).data('merge-field') === campo;
            });

            consecutivas = indicesFila.every(function (indiceFila, posicion) {
                return posicion === 0 || indiceFila === indicesFila[posicion - 1] + 1;
            });

            if (!mismoCampo || !consecutivas) {
                mostrarAlerta('Solo puedes combinar celdas consecutivas de una misma columna.');
                return;
            }

            rango = {
                field: campo,
                startRow: indicesFila[0],
                endRow: indicesFila[indicesFila.length - 1]
            };

            if (existeConflictoEnRango(rango)) {
                mostrarAlerta('Primero separa la combinacion actual antes de crear una nueva en ese rango.');
                return;
            }

            estadoCombinaciones.push({
                field: campo,
                startRow: rango.startRow,
                rowspan: indicesFila.length
            });

            aplicarEstadoCombinaciones();
            limpiarSeleccionVisual();
        }

        // Separa una combinacion usando unicamente su celda principal.
        function separarCeldasSeleccionadas() {
            var $seleccionadas = obtenerCeldasSeleccionadas();
            var itemCombinacion;

            if (!combinacionActiva) {
                mostrarAlerta('Activa el modo de combinacion para separar celdas.', 'info');
                return;
            }

            if ($seleccionadas.length !== 1) {
                mostrarAlerta('Selecciona la celda principal de una combinacion para separarla.');
                return;
            }

            itemCombinacion = obtenerCombinacionDeCelda($seleccionadas.first());

            if (!itemCombinacion || itemCombinacion.startRow !== $seleccionadas.first().closest('tr').index()) {
                mostrarAlerta('Selecciona la celda principal de una combinacion para separarla.');
                return;
            }

            estadoCombinaciones = estadoCombinaciones.filter(function (item) {
                return obtenerClaveCombinacion(item) !== obtenerClaveCombinacion(itemCombinacion);
            });

            aplicarEstadoCombinaciones();
            limpiarSeleccionVisual();
        }

        // Agrega filas nuevas y reaplica las combinaciones existentes.
        function agregarFilas() {
            var cantidad = parseInt($selectorFilas.val(), 10) || 1;
            var indiceInicial = $tbody.find('tr').length;
            var i;

            if (typeof configuracion.rowBuilder !== 'function') {
                return;
            }

            for (i = 0; i < cantidad; i += 1) {
                $tbody.append(configuracion.rowBuilder(indiceInicial + i));
            }

            renumerarFilas();
            celdaAnclaSeleccion = null;
            aplicarEstadoCombinaciones();
            guardarEstadoCombinaciones();
        }

        // Rellena solo los vacios de la tabla que participa en combinaciones.
        function rellenarVaciosTabla() {
            $tbody.find('input[type="text"]').each(function () {
                if (!$.trim($(this).val() || '')) {
                    $(this).val('---');
                }
            });

            aplicarEstadoCombinaciones();
            guardarEstadoCombinaciones();
        }

        // Elimina una fila sin perder consistencia en indices ni combinaciones.
        function eliminarFila($fila) {
            var indiceFila = $fila.index();

            if ($tbody.find('tr').length === 1) {
                $fila.find('input').val('');
                estadoCombinaciones = [];
                aplicarEstadoCombinaciones();
                guardarEstadoCombinaciones();
                return;
            }

            $fila.remove();
            ajustarEstadoTrasEliminarFila(indiceFila);
            renumerarFilas();
            celdaAnclaSeleccion = null;
            aplicarEstadoCombinaciones();
            guardarEstadoCombinaciones();
        }

        // Enlaza eventos del toolbar y de la tabla configurada.
        function enlazarEventos() {
            $tbody.on('click.combinacionCeldas', configuracion.mergeableCellSelector, function () {
                manejarSeleccionDeCelda($(this));
            });

            $tbody.on('click.combinacionCeldas', configuracion.mergeableCellSelector + ' input', function (event) {
                event.stopPropagation();
                manejarSeleccionDeCelda($(this).closest(configuracion.mergeableCellSelector));
            });

            $tbody.on('input.combinacionCeldas', configuracion.mergeableCellSelector + ' input', function () {
                sincronizarValorCombinado(obtenerCombinacionDeCelda($(this).closest(configuracion.mergeableCellSelector)));
                guardarEstadoCombinaciones();
            });

            $tbody.on('click.combinacionCeldas', configuracion.deleteButtonSelector, function () {
                eliminarFila($(this).closest('tr'));
            });

            if ($botonAgregar.length) {
                $botonAgregar.off('.combinacionCeldasExterna').on('click.combinacionCeldasExterna', agregarFilas);
            }

            if ($botonRellenar.length) {
                $botonRellenar.off('.combinacionCeldasExterna').on('click.combinacionCeldasExterna', rellenarVaciosTabla);
            }

            if ($botonCombinar.length) {
                $botonCombinar.off('.combinacionCeldasExterna').on('click.combinacionCeldasExterna', combinarCeldasSeleccionadas);
            }

            if ($botonSeparar.length) {
                $botonSeparar.off('.combinacionCeldasExterna').on('click.combinacionCeldasExterna', separarCeldasSeleccionadas);
            }
        }

        // Inicializa el administrador leyendo estado, aplicando combinaciones y registrando eventos.
        function init() {
            if (!$tbody.length || !$hidden.length) {
                return;
            }

            leerEstadoModoCombinacion();
            leerEstadoCombinaciones();
            aplicarEstadoCombinaciones();
            crearControlModoCombinacion();
            enlazarEventos();
        }

        return {
            init: init,
            guardarEstadoCombinaciones: guardarEstadoCombinaciones,
            aplicarEstadoCombinaciones: aplicarEstadoCombinaciones,
            rellenarVaciosTabla: rellenarVaciosTabla,
            normalizarConfiguracionCombinaciones: normalizarConfiguracionCombinaciones,
            serializarConfiguracionCombinaciones: serializarConfiguracionCombinaciones
        };
    }

    window.ReportesCombinacionCeldas = {
        crearAdministrador: crearAdministrador
    };
}(window, window.jQuery));
