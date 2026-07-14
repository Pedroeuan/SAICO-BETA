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

    function mostrarConfirmacion(opciones) {
        var configuracionDialogo = $.extend({
            icon: 'question',
            title: 'Confirmar accion',
            text: '',
            html: '',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d'
        }, opciones || {});

        if (window.Swal && typeof window.Swal.fire === 'function') {
            return window.Swal.fire({
                icon: configuracionDialogo.icon,
                title: configuracionDialogo.title,
                text: configuracionDialogo.html ? undefined : configuracionDialogo.text,
                html: configuracionDialogo.html || undefined,
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

    function crearAdministrador(opciones) {
        var configuracion = $.extend(true, {
            tbodySelector: '#dynamicTable tbody',
            hiddenSelector: '#tablaCombinacionConfig',
            mergeButtonSelector: '#mergeSelectedCellsBtn',
            splitButtonSelector: '#splitSelectedCellsBtn',
            selectionInfoSelector: '#tablaMergeSelectionInfo',
            modeToggleAfterSelector: '#preFillBtn',
            dataRowSelector: 'tr:not(.titulo-row):not(.long-row)',
            mergeableCellSelector: '.mergeable-cell',
            inferirColumnasCombinables: false,
            columnasCombinables: []
        }, opciones || {});

        var $tbody = $(configuracion.tbodySelector);
        var $hidden = $(configuracion.hiddenSelector);
        var $botonCombinar = $(configuracion.mergeButtonSelector);
        var $botonSeparar = $(configuracion.splitButtonSelector);
        var $infoSeleccion = $(configuracion.selectionInfoSelector);
        var celdaAnclaSeleccion = null;
        var estadoCombinaciones = [];
        var $contenedorControlModo = $();
        var $botonModoCombinacion = $();
        var combinacionActiva = false;

        function obtenerIdentificadorAdministrador() {
            return String(configuracion.hiddenSelector || configuracion.tbodySelector || 'tabla')
                .replace(/[^a-z0-9_-]/gi, '_');
        }

        function obtenerClaveModoCombinacion() {
            var idFormulario = $tbody.closest('form').attr('id') || 'formulario_sin_id';
            return 'modo_combinacion_celdas_' + idFormulario + '_' + obtenerIdentificadorAdministrador();
        }

        function leerEstadoModoCombinacion() {
            try {
                combinacionActiva = sessionStorage.getItem(obtenerClaveModoCombinacion()) === '1';
            } catch (error) {
                combinacionActiva = false;
            }
        }

        function guardarEstadoModoCombinacion() {
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
                $botonModoCombinacion
                    .removeClass('btn-success')
                    .addClass('btn-secondary')
                    .text('Desactivar combinacion');
                return;
            }

            $botonModoCombinacion
                .removeClass('btn-secondary')
                .addClass('btn-success')
                .text('Activar combinacion');
        }

        function actualizarAyudaModoCombinacion() {
            if ($infoSeleccion.length) {
                $infoSeleccion.html('').hide();
            }
        }

        function crearControlModoCombinacion() {
            var $contenedorAcciones;
            var $anclaModo;

            if ($contenedorControlModo.length) {
                return;
            }

            $anclaModo = $(configuracion.modeToggleAfterSelector).first();
            $contenedorAcciones = $anclaModo.parent();

            if (!$contenedorAcciones.length) {
                $contenedorAcciones = $anclaModo.closest('.d-flex, .toolbar-actions, .tabla-toolbar, .tabla-toolbar-actions');
            }

            if (!$contenedorAcciones.length) {
                $contenedorAcciones = $anclaModo.parent();
            }

            $contenedorControlModo = $(
                '<div class="merge-mode-toolbar" style="display:inline-flex;align-items:center;gap:10px;">' +
                    '<button type="button" class="btn btn-sm btn-success merge-mode-toggle"></button>' +
                '</div>'
            );

            if ($anclaModo.length) {
                $anclaModo.after($contenedorControlModo);
            } else if ($contenedorAcciones.length) {
                $contenedorAcciones.append($contenedorControlModo);
            }

            $botonModoCombinacion = $contenedorControlModo.find('.merge-mode-toggle');

            $botonModoCombinacion.on('click.combinacionAgrupada', function () {
                combinacionActiva = !combinacionActiva;
                guardarEstadoModoCombinacion();

                if (!combinacionActiva) {
                    celdaAnclaSeleccion = null;
                    limpiarSeleccionVisual();
                }

                actualizarIndicadorModoCombinacion();
                actualizarAyudaModoCombinacion();
            });

            actualizarIndicadorModoCombinacion();
            actualizarAyudaModoCombinacion();

            if ($infoSeleccion.length) {
                $infoSeleccion.closest('.merge-help-box').hide();
            }
        }

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

        function obtenerCampoCombinacionDesdeCelda($celda, indiceColumna) {
            var $control = $celda.find('input:not([type="hidden"]), textarea, select').first();
            var nombreControl = ($control.attr('name') || '').trim();
            var campoBase = nombreControl.split('[')[0];

            if (campoBase) {
                return campoBase;
            }

            return 'columna_' + indiceColumna;
        }

        function inferirColumnasCombinables() {
            var $filaBase = obtenerFilasDatos().first();
            var columnas = [];

            if (!$filaBase.length) {
                return [];
            }

            $filaBase.find('td').each(function (indiceColumna) {
                var $celda = $(this);
                var $controlVisible = $celda.find('input:not([type="hidden"]), textarea, select').first();

                if (!$controlVisible.length) {
                    return;
                }

                columnas.push({
                    index: indiceColumna,
                    field: obtenerCampoCombinacionDesdeCelda($celda, indiceColumna)
                });
            });

            return columnas;
        }

        function decorarCeldasCombinables() {
            if (configuracion.inferirColumnasCombinables) {
                configuracion.columnasCombinables = inferirColumnasCombinables();
            }

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

        function normalizarNombreCampo(field) {
            var aliases = {
                numero_junta: 'no_junta',
                angulo_inspeccion: 'ang_inspeccion'
            };

            return aliases[field] || field;
        }

        function obtenerEtiquetaCampo(field) {
            var etiquetas = {
                no_junta: 'No. de junta',
                no_indicacion: 'No. indicacion',
                ang_inspeccion: 'Angulo de inspeccion',
                dsd_cara: 'Desde la cara',
                pierna: 'Pierna',
                decibel_a: 'Decibel A',
                decibel_b: 'Decibel B',
                decibel_c: 'Decibel C ',
                decibel_d: 'Decibel D',
                longitud: 'Longitud',
                dis_angular: 'Distancia angular',
                profundidad_a: 'Profundidad desde A',
                pos_x: 'Posicion X',
                pos_y: 'Posicion Y',
                discontinuidad: 'Clase de la discontinuidad',
                evaluacion: 'Evaluacion',
                observaciones: 'Observaciones'
            };
            var fieldNormalizado = normalizarNombreCampo(field || '');

            if (etiquetas[fieldNormalizado]) {
                return etiquetas[fieldNormalizado];
            }

            return fieldNormalizado
                .replace(/_/g, ' ')
                .replace(/\b\w/g, function (letra) {
                    return letra.toUpperCase();
                });
        }

        function normalizarEstadoCombinaciones(estado) {
            var ocupadas = {};
            var normalizado = [];

            (Array.isArray(estado) ? estado : [])
                .map(function (item) {
                    return {
                        field: normalizarNombreCampo(item && item.field ? String(item.field) : ''),
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

        function obtenerIndiceFilaEnDom($fila) {
            return $tbody.children('tr').index($fila);
        }

        function existeSeparadorEntreFilas($filaInicio, $filaFin) {
            var $cursor = $filaInicio;
            var $filaTemporal;

            if (obtenerIndiceFilaEnDom($filaInicio) > obtenerIndiceFilaEnDom($filaFin)) {
                $filaTemporal = $filaInicio;
                $filaInicio = $filaFin;
                $filaFin = $filaTemporal;
                $cursor = $filaInicio;
            }

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
            $infoSeleccion.html('');
            actualizarAyudaModoCombinacion();
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
            var itemCombinacion;
            var indiceFilaActual;

            if (!combinacionActiva) {
                return;
            }

            if (!$celda.length || !$celda.is(':visible')) {
                return;
            }

            itemCombinacion = obtenerCombinacionDeCelda($celda);
            indiceFilaActual = obtenerIndiceFilaEnGrupo($celda.closest('tr'));

            if (!celdaAnclaSeleccion && itemCombinacion && itemCombinacion.startRow === indiceFilaActual) {
                pintarSeleccion({
                    groupId: itemCombinacion.groupId,
                    field: itemCombinacion.field,
                    startRow: itemCombinacion.startRow,
                    endRow: itemCombinacion.startRow + itemCombinacion.rowspan - 1
                });

                confirmarSeparacion(itemCombinacion).then(function (confirmado) {
                    if (confirmado) {
                        ejecutarSeparacion(itemCombinacion);
                    }
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

            if (existeSeparadorEntreFilas(celdaAnclaSeleccion.closest('tr'), $celda.closest('tr'))) {
                limpiarSeleccionVisual();
                celdaAnclaSeleccion = null;
                mostrarAlerta('No puedes combinar celdas atravesando una fila de titulo o longitud.');
                return;
            }

            (function () {
                var rango = obtenerRangoSeleccion(celdaAnclaSeleccion, $celda);

                pintarSeleccion(rango);
                confirmarCombinacion(rango).then(function (confirmado) {
                    if (confirmado) {
                        ejecutarCombinacionEnRango(rango);
                    }
                });
            }());
            celdaAnclaSeleccion = null;
        }

        function obtenerCeldasSeleccionadas() {
            return $tbody.find(configuracion.mergeableCellSelector + '.selected-merge:visible');
        }

        function construirHtmlConfirmacion(etiquetaCampo, textoFilas, mensajeAccion) {
            return '<div style="display:grid;gap:10px;text-align:center;">' +
                '<div style="font-size:14px;color:#5d6b7b;">' + mensajeAccion + '</div>' +
                '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px;">' +
                    '<span class="merge-selection-badge"><strong>Campo:</strong> ' + etiquetaCampo + '</span>' +
                    '<span class="merge-selection-badge"><strong>Filas:</strong> ' + textoFilas + '</span>' +
                '</div>' +
            '</div>';
        }

        function confirmarCombinacion(rango) {
            return mostrarConfirmacion({
                icon: 'question',
                title: 'Combinar celdas',
                html: construirHtmlConfirmacion(
                    obtenerEtiquetaCampo(rango.field),
                    (rango.startRow + 1) + ' a ' + (rango.endRow + 1),
                    'Deseas combinar este rango?'
                ),
                confirmButtonText: 'Combinar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#0d6efd'
            });
        }

        function confirmarSeparacion(itemCombinacion) {
            return mostrarConfirmacion({
                icon: 'warning',
                title: 'Separar celdas',
                html: construirHtmlConfirmacion(
                    obtenerEtiquetaCampo(itemCombinacion.field),
                    (itemCombinacion.startRow + 1) + ' a ' + (itemCombinacion.startRow + itemCombinacion.rowspan),
                    'Deseas separar esta combinacion?'
                ),
                confirmButtonText: 'Separar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#6c757d'
            });
        }

        function ejecutarCombinacionEnRango(rango) {
            estadoCombinaciones.push({
                groupId: rango.groupId,
                field: rango.field,
                startRow: rango.startRow,
                rowspan: (rango.endRow - rango.startRow) + 1
            });

            aplicarEstadoCombinaciones();
            limpiarSeleccionVisual();
        }

        function ejecutarSeparacion(itemCombinacion) {
            estadoCombinaciones = estadoCombinaciones.filter(function (item) {
                return obtenerClaveCombinacion(item) !== obtenerClaveCombinacion(itemCombinacion);
            });

            aplicarEstadoCombinaciones();
            limpiarSeleccionVisual();
        }

        function combinarCeldasSeleccionadas() {
            var $seleccionadas = obtenerCeldasSeleccionadas();
            var field;
            var groupId;
            var rowIndexes;
            var consecutivas;
            var rango;

            if (!combinacionActiva) {
                mostrarAlerta('Activa el modo de combinacion para unir celdas.', 'info');
                return;
            }

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

            confirmarCombinacion(rango).then(function (confirmado) {
                if (!confirmado) {
                    return;
                }

                ejecutarCombinacionEnRango(rango);
            });
        }

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

            if (!itemCombinacion || itemCombinacion.startRow !== obtenerIndiceFilaEnGrupo($seleccionadas.first().closest('tr'))) {
                mostrarAlerta('Selecciona la celda principal de una combinacion para separarla.');
                return;
            }

            confirmarSeparacion(itemCombinacion).then(function (confirmado) {
                if (!confirmado) {
                    return;
                }

                ejecutarSeparacion(itemCombinacion);
            });
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

            leerEstadoModoCombinacion();
            decorarCeldasCombinables();
            leerEstadoCombinaciones();
            aplicarEstadoCombinaciones();
            crearControlModoCombinacion();
            enlazarEventos();
        }

        return {
            init: init,
            refresh: refrescarTabla,
            handleDeleteRow: manejarEliminacionFila,
            clearGroup: limpiarCombinacionesDeGrupo,
            guardarEstadoCombinaciones: guardarEstadoCombinaciones,
            isEnabled: function () {
                return combinacionActiva;
            },
            setEnabled: function (valor) {
                combinacionActiva = !!valor;
                guardarEstadoModoCombinacion();

                if (!combinacionActiva) {
                    celdaAnclaSeleccion = null;
                    limpiarSeleccionVisual();
                }

                actualizarIndicadorModoCombinacion();
                actualizarAyudaModoCombinacion();
            }
        };
    }

    window.ReportesCombinacionCeldasAgrupadas = {
        crearAdministrador: crearAdministrador
    };
}(window, window.jQuery));
