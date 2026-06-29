(function (window, $) {
    'use strict';

    if (!$) {
        return;
    }

    function mostrarAlerta(mensaje, icono) {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                icon: icono || 'warning',
                title: 'Atencion',
                text: mensaje,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        window.alert(mensaje);
    }

    function crearAdministrador(opciones) {
        var configuracion = $.extend(true, {
            tbodySelector: '#dynamicTable tbody',
            hiddenSelector: '#tablaCombinacionConfig',
            mergeButtonSelector: '#mergeSelectedCellsBtn',
            splitButtonSelector: '#splitSelectedCellsBtn',
            selectionInfoSelector: '#tablaMergeSelectionInfo',
            dataRowSelector: 'tr:not(.titulo-row):not(.long-row)',
            mergeableCellSelector: '.mergeable-cell',
            columnasCombinables: []
        }, opciones || {});

        var $tbody = $(configuracion.tbodySelector);
        var $hidden = $(configuracion.hiddenSelector);
        var $botonCombinar = $(configuracion.mergeButtonSelector);
        var $botonSeparar = $(configuracion.splitButtonSelector);
        var $infoSeleccion = $(configuracion.selectionInfoSelector);
        var celdaAnclaSeleccion = null;
        var estadoCombinaciones = [];

        function obtenerGrupoFila($fila) {
            return $fila.attr('data-titulo') || 'sin_titulo';
        }

        function obtenerFilasDatos(groupId) {
            var $filas = $tbody.find(configuracion.dataRowSelector);

            if (typeof groupId === 'undefined') {
                return $filas;
            }

            return $filas.filter(function () {
                return obtenerGrupoFila($(this)) === groupId;
            });
        }

        function decorarCeldasCombinables() {
            obtenerFilasDatos().each(function () {
                var $fila = $(this);

                $fila.find('td').removeClass('mergeable-cell').removeAttr('data-merge-field');

                configuracion.columnasCombinables.forEach(function (columna) {
                    $fila.find('td').eq(columna.index).addClass('mergeable-cell').attr('data-merge-field', columna.field);
                });
            });
        }

        function obtenerClaveCombinacion(item) {
            return (item.groupId || 'sin_titulo') + '|' + item.field + '|' + item.startRow;
        }

        function normalizarEstadoCombinaciones(estado) {
            var ocupadas = {};
            var normalizado = [];

            (Array.isArray(estado) ? estado : [])
                .map(function (item) {
                    return {
                        field: item && item.field ? String(item.field) : '',
                        groupId: item && item.groupId ? String(item.groupId) : 'sin_titulo',
                        startRow: Number(item && item.startRow),
                        rowspan: Number(item && item.rowspan)
                    };
                })
                .sort(function (a, b) {
                    var claveA = a.groupId + '|' + a.field;
                    var claveB = b.groupId + '|' + b.field;

                    if (claveA === claveB) {
                        return a.startRow - b.startRow;
                    }

                    return claveA.localeCompare(claveB);
                })
                .forEach(function (item) {
                    var claveGrupo;
                    var totalFilasGrupo;
                    var finCombinacion;
                    var seTraslapa;

                    if (!item.field || item.startRow < 0 || item.rowspan < 2) {
                        return;
                    }

                    totalFilasGrupo = obtenerFilasDatos(item.groupId).length;
                    if ((item.startRow + item.rowspan) > totalFilasGrupo) {
                        return;
                    }

                    if (cruzaSeparador(item.groupId, item.startRow, item.rowspan)) {
                        return;
                    }

                    finCombinacion = item.startRow + item.rowspan - 1;
                    claveGrupo = item.groupId + '|' + item.field;
                    ocupadas[claveGrupo] = ocupadas[claveGrupo] || [];

                    seTraslapa = ocupadas[claveGrupo].some(function (rango) {
                        return item.startRow <= rango.end && finCombinacion >= rango.start;
                    });

                    if (seTraslapa) {
                        return;
                    }

                    ocupadas[claveGrupo].push({ start: item.startRow, end: finCombinacion });
                    normalizado.push(item);
                });

            return normalizado;
        }

        function guardarEstadoCombinaciones() {
            estadoCombinaciones = normalizarEstadoCombinaciones(estadoCombinaciones);
            $hidden.val(JSON.stringify(estadoCombinaciones));
        }

        function leerEstadoCombinaciones() {
            var estadoCrudo = $hidden.val();

            if (!estadoCrudo) {
                estadoCombinaciones = [];
                return;
            }

            try {
                estadoCombinaciones = normalizarEstadoCombinaciones(JSON.parse(estadoCrudo));
            } catch (error) {
                estadoCombinaciones = [];
            }

            guardarEstadoCombinaciones();
        }

        function obtenerCelda(groupId, rowIndex, field) {
            return obtenerFilasDatos(groupId)
                .eq(rowIndex)
                .find(configuracion.mergeableCellSelector + '[data-merge-field="' + field + '"]');
        }

        function obtenerIndiceFilaEnGrupo($fila) {
            return obtenerFilasDatos(obtenerGrupoFila($fila)).index($fila);
        }

        function existeSeparadorEntreFilas($filaInicio, $filaFin) {
            var $cursor = $filaInicio;

            while ($cursor.length && !$cursor.is($filaFin)) {
                $cursor = $cursor.next();

                if ($cursor.hasClass('titulo-row') || $cursor.hasClass('long-row')) {
                    return true;
                }
            }

            return false;
        }

        function cruzaSeparador(groupId, startRow, rowspan) {
            var $filasGrupo = obtenerFilasDatos(groupId);
            var $filaInicio = $filasGrupo.eq(startRow);
            var $filaFin = $filasGrupo.eq(startRow + rowspan - 1);

            if (!$filaInicio.length || !$filaFin.length) {
                return true;
            }

            return existeSeparadorEntreFilas($filaInicio, $filaFin);
        }

        function limpiarSeleccionVisual() {
            $tbody.find(configuracion.mergeableCellSelector).removeClass('selected-merge merge-preview merge-anchor');
            $infoSeleccion.text('');
        }

        function limpiarCombinacionesVisuales() {
            $tbody.find(configuracion.mergeableCellSelector).each(function () {
                $(this).show().removeAttr('rowspan').removeAttr('data-merge-hidden');
            });
        }

        function obtenerCombinacionDeCelda($celda) {
            var $fila;
            var groupId;
            var rowIndex;
            var field;

            if (!$celda.length) {
                return null;
            }

            $fila = $celda.closest('tr');
            groupId = obtenerGrupoFila($fila);
            rowIndex = obtenerIndiceFilaEnGrupo($fila);
            field = $celda.data('merge-field');

            return estadoCombinaciones.find(function (item) {
                return item.groupId === groupId &&
                    item.field === field &&
                    rowIndex >= item.startRow &&
                    rowIndex < (item.startRow + item.rowspan);
            }) || null;
        }

        function sincronizarValorCombinado(itemCombinacion) {
            var $celdaPrincipal;
            var valorPrincipal;
            var offset;

            if (!itemCombinacion) {
                return;
            }

            $celdaPrincipal = obtenerCelda(itemCombinacion.groupId, itemCombinacion.startRow, itemCombinacion.field);
            if (!$celdaPrincipal.length) {
                return;
            }

            valorPrincipal = $celdaPrincipal.find('input').val() || '';

            for (offset = 1; offset < itemCombinacion.rowspan; offset += 1) {
                obtenerCelda(itemCombinacion.groupId, itemCombinacion.startRow + offset, itemCombinacion.field)
                    .find('input')
                    .val(valorPrincipal);
            }
        }

        function aplicarEstadoCombinaciones() {
            var offset;

            decorarCeldasCombinables();
            limpiarCombinacionesVisuales();
            estadoCombinaciones = normalizarEstadoCombinaciones(estadoCombinaciones);

            estadoCombinaciones.forEach(function (item) {
                var $celdaPrincipal = obtenerCelda(item.groupId, item.startRow, item.field);
                var valorPrincipal;

                if (!$celdaPrincipal.length) {
                    return;
                }

                valorPrincipal = $celdaPrincipal.find('input').val() || '';
                $celdaPrincipal.attr('rowspan', item.rowspan);

                for (offset = 1; offset < item.rowspan; offset += 1) {
                    var $celdaHija = obtenerCelda(item.groupId, item.startRow + offset, item.field);

                    if (!$celdaHija.length) {
                        continue;
                    }

                    $celdaHija.attr('data-merge-hidden', 'true').find('input').val(valorPrincipal);
                    $celdaHija.hide();
                }
            });

            guardarEstadoCombinaciones();
        }

        function obtenerRangoSeleccion($celdaInicio, $celdaFin) {
            var $filaInicio = $celdaInicio.closest('tr');
            var $filaFin = $celdaFin.closest('tr');
            var groupId = obtenerGrupoFila($filaInicio);
            var rowIndexStart = obtenerIndiceFilaEnGrupo($filaInicio);
            var rowIndexEnd = obtenerIndiceFilaEnGrupo($filaFin);

            return {
                field: $celdaInicio.data('merge-field'),
                groupId: groupId,
                startRow: Math.min(rowIndexStart, rowIndexEnd),
                endRow: Math.max(rowIndexStart, rowIndexEnd)
            };
        }

        function pintarSeleccion(rango) {
            var rowIndex;

            limpiarSeleccionVisual();

            for (rowIndex = rango.startRow; rowIndex <= rango.endRow; rowIndex += 1) {
                obtenerCelda(rango.groupId, rowIndex, rango.field).addClass('selected-merge merge-preview');
            }

            $infoSeleccion.text('Rango seleccionado: ' + rango.field + ' filas ' + (rango.startRow + 1) + ' a ' + (rango.endRow + 1));
        }

        function existeConflictoEnRango(rango) {
            return estadoCombinaciones.some(function (item) {
                var finCombinacion;

                if (item.groupId !== rango.groupId || item.field !== rango.field) {
                    return false;
                }

                finCombinacion = item.startRow + item.rowspan - 1;
                return rango.startRow <= finCombinacion && rango.endRow >= item.startRow;
            });
        }

        function manejarSeleccionDeCelda($celda) {
            var mismoCampo;
            var mismoGrupo;
            var mismaFila;

            if (!$celda.length || !$celda.is(':visible')) {
                return;
            }

            if (!celdaAnclaSeleccion) {
                limpiarSeleccionVisual();
                $celda.addClass('selected-merge merge-anchor');
                celdaAnclaSeleccion = $celda;
                return;
            }

            mismoCampo = celdaAnclaSeleccion.data('merge-field') === $celda.data('merge-field');
            mismoGrupo = obtenerGrupoFila(celdaAnclaSeleccion.closest('tr')) === obtenerGrupoFila($celda.closest('tr'));
            mismaFila = obtenerIndiceFilaEnGrupo(celdaAnclaSeleccion.closest('tr')) === obtenerIndiceFilaEnGrupo($celda.closest('tr'));

            if (!mismoCampo || !mismoGrupo) {
                limpiarSeleccionVisual();
                celdaAnclaSeleccion = null;
                mostrarAlerta('Solo puedes seleccionar celdas de la misma columna y del mismo bloque.');
                return;
            }

            if (mismaFila) {
                limpiarSeleccionVisual();
                celdaAnclaSeleccion = null;
                return;
            }

            if (existeSeparadorEntreFilas(celdaAnclaSeleccion.closest('tr'), $celda.closest('tr')) ||
                existeSeparadorEntreFilas($celda.closest('tr'), celdaAnclaSeleccion.closest('tr'))) {
                limpiarSeleccionVisual();
                celdaAnclaSeleccion = null;
                mostrarAlerta('No puedes combinar celdas atravesando una fila de titulo o longitud.');
                return;
            }

            pintarSeleccion(obtenerRangoSeleccion(celdaAnclaSeleccion, $celda));
            celdaAnclaSeleccion = null;
        }

        function obtenerCeldasSeleccionadas() {
            return $tbody.find(configuracion.mergeableCellSelector + '.selected-merge:visible');
        }

        function combinarCeldasSeleccionadas() {
            var $seleccionadas = obtenerCeldasSeleccionadas();
            var field;
            var groupId;
            var rowIndexes;
            var consecutivas;
            var rango;

            if ($seleccionadas.length < 2) {
                mostrarAlerta('Selecciona al menos 2 celdas consecutivas de la misma columna.');
                return;
            }

            field = $seleccionadas.first().data('merge-field');
            groupId = obtenerGrupoFila($seleccionadas.first().closest('tr'));
            rowIndexes = $seleccionadas.map(function () {
                return obtenerIndiceFilaEnGrupo($(this).closest('tr'));
            }).get().sort(function (a, b) {
                return a - b;
            });

            consecutivas = rowIndexes.every(function (rowIndex, posicion) {
                return posicion === 0 || rowIndex === rowIndexes[posicion - 1] + 1;
            });

            if (!consecutivas) {
                mostrarAlerta('Solo puedes combinar celdas consecutivas dentro del mismo bloque.');
                return;
            }

            rango = {
                groupId: groupId,
                field: field,
                startRow: rowIndexes[0],
                endRow: rowIndexes[rowIndexes.length - 1]
            };

            if (existeConflictoEnRango(rango)) {
                mostrarAlerta('Primero separa la combinacion actual antes de crear una nueva en ese rango.');
                return;
            }

            estadoCombinaciones.push({
                groupId: groupId,
                field: field,
                startRow: rango.startRow,
                rowspan: rowIndexes.length
            });

            aplicarEstadoCombinaciones();
            limpiarSeleccionVisual();
        }

        function separarCeldasSeleccionadas() {
            var $seleccionadas = obtenerCeldasSeleccionadas();
            var itemCombinacion;

            if ($seleccionadas.length !== 1) {
                mostrarAlerta('Selecciona la celda principal de una combinacion para separarla.');
                return;
            }

            itemCombinacion = obtenerCombinacionDeCelda($seleccionadas.first());

            if (!itemCombinacion || itemCombinacion.startRow !== obtenerIndiceFilaEnGrupo($seleccionadas.first().closest('tr'))) {
                mostrarAlerta('Selecciona la celda principal de una combinacion para separarla.');
                return;
            }

            estadoCombinaciones = estadoCombinaciones.filter(function (item) {
                return obtenerClaveCombinacion(item) !== obtenerClaveCombinacion(itemCombinacion);
            });

            aplicarEstadoCombinaciones();
            limpiarSeleccionVisual();
        }

        function manejarEliminacionFila($fila) {
            var groupId = obtenerGrupoFila($fila);
            var indiceFila = obtenerIndiceFilaEnGrupo($fila);

            if (indiceFila < 0) {
                return;
            }

            estadoCombinaciones = estadoCombinaciones.reduce(function (acumulador, item) {
                var finCombinacion;

                if (item.groupId !== groupId) {
                    acumulador.push(item);
                    return acumulador;
                }

                finCombinacion = item.startRow + item.rowspan - 1;

                if (indiceFila < item.startRow) {
                    acumulador.push({
                        groupId: item.groupId,
                        field: item.field,
                        startRow: item.startRow - 1,
                        rowspan: item.rowspan
                    });
                    return acumulador;
                }

                if (indiceFila > finCombinacion) {
                    acumulador.push(item);
                    return acumulador;
                }

                if (item.rowspan - 1 >= 2) {
                    acumulador.push({
                        groupId: item.groupId,
                        field: item.field,
                        startRow: item.startRow,
                        rowspan: item.rowspan - 1
                    });
                }

                return acumulador;
            }, []);
        }

        function limpiarCombinacionesDeGrupo(groupId) {
            estadoCombinaciones = estadoCombinaciones.filter(function (item) {
                return item.groupId !== groupId;
            });
            guardarEstadoCombinaciones();
        }

        function refrescarTabla() {
            decorarCeldasCombinables();
            aplicarEstadoCombinaciones();
            limpiarSeleccionVisual();
        }

        function enlazarEventos() {
            $tbody.on('click.combinacionAgrupada', configuracion.mergeableCellSelector, function () {
                manejarSeleccionDeCelda($(this));
            });

            $tbody.on('click.combinacionAgrupada', configuracion.mergeableCellSelector + ' input', function (event) {
                event.stopPropagation();
                manejarSeleccionDeCelda($(this).closest(configuracion.mergeableCellSelector));
            });

            $tbody.on('input.combinacionAgrupada', configuracion.mergeableCellSelector + ' input', function () {
                sincronizarValorCombinado(obtenerCombinacionDeCelda($(this).closest(configuracion.mergeableCellSelector)));
                guardarEstadoCombinaciones();
            });

            if ($botonCombinar.length) {
                $botonCombinar.off('.combinacionAgrupadaExterna').on('click.combinacionAgrupadaExterna', combinarCeldasSeleccionadas);
            }

            if ($botonSeparar.length) {
                $botonSeparar.off('.combinacionAgrupadaExterna').on('click.combinacionAgrupadaExterna', separarCeldasSeleccionadas);
            }
        }

        function init() {
            if (!$tbody.length || !$hidden.length) {
                return;
            }

            decorarCeldasCombinables();
            leerEstadoCombinaciones();
            aplicarEstadoCombinaciones();
            enlazarEventos();
        }

        return {
            init: init,
            refresh: refrescarTabla,
            handleDeleteRow: manejarEliminacionFila,
            clearGroup: limpiarCombinacionesDeGrupo,
            guardarEstadoCombinaciones: guardarEstadoCombinaciones
        };
    }

    window.ReportesCombinacionCeldasAgrupadas = {
        crearAdministrador: crearAdministrador
    };
}(window, window.jQuery));
