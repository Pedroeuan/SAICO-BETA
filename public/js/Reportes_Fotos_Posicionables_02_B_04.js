(function (window, document) {
    /*
     * Módulo compartido de presentación fotográfica. Cada regla específica
     * se activa mediante el id del formulario, sin consultar otra plantilla.
     */
    'use strict';

    /*
     * Distribuciones permitidas para cada fotografia dentro de una hoja.
     * Las primeras cuatro opciones ocupan un cuadrante; pagina_completa
     * reserva la hoja entera y no puede combinarse con otra fotografia.
     */
    var posiciones = [
        { value: 'arriba_izquierda', label: 'Arriba izquierda' },
        { value: 'arriba_derecha', label: 'Arriba derecha' },
        { value: 'abajo_izquierda', label: 'Abajo izquierda' },
        { value: 'abajo_derecha', label: 'Abajo derecha' },
        { value: 'pagina_completa', label: 'Página completa' }
    ];
    var reindexarPorOrden = false;

    /* Escapa texto antes de insertarlo en atributos construidos con HTML. */
    function escapar(valor) {
        return String(valor == null ? '' : valor)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /*
     * Obtiene el indice utilizado por los campos del formulario, por ejemplo:
     * images_base64[3] devuelve "3". Las fotos nuevas pueden no tenerlo aun.
     */
    function obtenerIndiceCampo(contenedor) {
        var base64 = contenedor.querySelector('input[name^="images_base64"]');
        var coincidencia;

        if (!base64) {
            return null;
        }

        coincidencia = (base64.getAttribute('name') || '').match(/images_base64\[(\d+)\]/);
        return coincidencia ? coincidencia[1] : null;
    }

    /* Conserva el mismo indice entre los campos de una fotografia. */
    function nombreCampo(base, indice) {
        return indice === null ? base + '[]' : base + '[' + indice + ']';
    }

    /*
     * El 04/03 construye los primeros espacios desde datos que no pertenecen a
     * Fotos_Reporte. Se calculan aquí para sugerir y validar posiciones sin
     * duplicar la micrografía, la descripción ni el patrón del catálogo.
     */
    function posicionesAutomaticas(formulario) {
        var reservadas = [];

        if (!formulario || ['FOR-PIMP-03_B_01', 'FOR-PIMP-04_02', 'FOR-PIMP-04_03'].indexOf(formulario.id) === -1) {
            return reservadas;
        }

        // Los tres elementos automáticos exponen la misma interfaz de página y posición que una foto manual.
        formulario.querySelectorAll('[data-auto-report-layout]').forEach(function (elemento) {
            var paginaInput = elemento.querySelector('[data-report-layout-page]');
            var posicionInput = elemento.querySelector('[data-report-layout-position]:checked');
            var pagina;

            // Un control deshabilitado pertenece a un análisis o patrón que todavía no fue activado.
            if (!paginaInput || paginaInput.matches(':disabled')
                || !posicionInput || posicionInput.matches(':disabled')) return;
            pagina = parseInt(paginaInput.value, 10);
            if (!pagina || pagina < 1) return;

            reservadas.push({
                pagina: pagina,
                posicion: posicionInput.value,
                etiqueta: elemento.getAttribute('data-auto-report-label') || 'Elemento automático'
            });
        });

        // El tamaño de grano usa la distribución de la tarjeta que el técnico marcó en la lista de imágenes.
        formulario.querySelectorAll('.foto-grain-checkbox:checked').forEach(function (checkbox) {
            var tarjeta = checkbox.closest('[data-grain-card], [id^="image-container-"]');
            var paginaInput = tarjeta ? tarjeta.querySelector('.foto-pagina, [data-grain-history-page]') : null;
            var posicionInput = tarjeta
                ? tarjeta.querySelector('input[data-foto-posicion]:checked, input[data-grain-history-position]:checked')
                : null;
            var pagina = paginaInput ? parseInt(paginaInput.value, 10) : 0;
            if (!pagina || !posicionInput) return;

            reservadas.push({
                pagina: pagina,
                posicion: posicionInput.value,
                etiqueta: 'Imagen de tamaño de grano'
            });
        });

        return reservadas;
    }

    /*
     * Recupera la pagina y posicion guardadas en los atributos data-*.
     * Si no existen, distribuye automaticamente cuatro fotos por pagina
     * siguiendo el orden de los cuatro cuadrantes.
     */
    function valoresPredeterminados(contenedor, orden) {
        var pagina = parseInt(contenedor.getAttribute('data-foto-pagina'), 10);
        var posicion = contenedor.getAttribute('data-foto-posicion');
        var esHojaCompleta = contenedor.getAttribute('data-foto-hoja-completa') === '1';
        var formulario = contenedor.closest('form');
        var tieneDistribucionGuardada = pagina >= 1
            && posiciones.some(function (item) { return item.value === posicion; });
        var reservadas;
        var disponiblesPrimeraPagina;
        var indicePosterior;

        // Create y las tarjetas nuevas de Edit comienzan en la primera celda que no es automática.
        if (!tieneDistribucionGuardada && formulario
            && ['FOR-PIMP-03_B_01', 'FOR-PIMP-04_02', 'FOR-PIMP-04_03'].indexOf(formulario.id) !== -1) {
            reservadas = posicionesAutomaticas(formulario)
                .filter(function (item) { return item.pagina === 1; })
                .map(function (item) { return item.posicion; });
            disponiblesPrimeraPagina = posiciones.slice(0, 4).filter(function (item) {
                return reservadas.indexOf(item.value) === -1;
            });

            if (orden < disponiblesPrimeraPagina.length) {
                return { pagina: 1, posicion: disponiblesPrimeraPagina[orden].value };
            }

            indicePosterior = orden - disponiblesPrimeraPagina.length;
            return {
                pagina: Math.floor(indicePosterior / 4) + 2,
                posicion: posiciones[indicePosterior % 4].value
            };
        }

        if (!pagina || pagina < 1) {
            pagina = Math.floor(orden / 4) + 1;
        }

        if (esHojaCompleta) {
            posicion = 'pagina_completa';
        }

        if (!posiciones.some(function (item) { return item.value === posicion; })) {
            posicion = posiciones[orden % 4].value;
        }

        return { pagina: pagina, posicion: posicion };
    }

    /*
     * Mantiene actualizado el campo heredado imagen_hoja. El servidor espera
     * "1" cuando la fotografia ocupa la pagina completa y "0" en otro caso.
     */
    function sincronizarHojaCompleta(contenedor) {
        var seleccion = contenedor.querySelector('input[type="radio"][data-foto-posicion]:checked');
        var hidden = contenedor.querySelector('input[name^="imagen_hoja"]');

        if (hidden) {
            hidden.value = seleccion && seleccion.value === 'pagina_completa' ? '1' : '0';
        }
    }

    /* Crea e inserta los controles de pagina y posicion de una fotografia. */
    function crearControl(contenedor, orden) {
        var indice;
        var valores;
        var bloque;
        var radios;
        var checkboxAnterior;
        var checkboxDisparo;
        var campoDisparo;
        var formulario = contenedor.closest('form');
        // Estos formatos permiten usar el espacio de una foto como cuadro de texto.
        var permiteCuadroTexto = formulario && [
            'FOR-PIMP-03_B_01',
            'FOR-PIMP-04_02',
            'FOR-PIMP-04_03',
            'FOR-PIMP-05_B_01',
            'FOR-PIMP-06_B_01'
        ].indexOf(formulario.id) !== -1;
        var etiquetaCuadroTexto = formulario && formulario.id === 'FOR-PIMP-06_B_01'
            ? 'Usar este espacio como descripción para el reporte'
            : 'Usar este espacio como cuadro de texto';
        var esCuadroTexto = contenedor.getAttribute('data-foto-es-texto') === '1';

        // Evita duplicarlos cuando MutationObserver vuelve a recorrer el DOM.
        if (contenedor.querySelector('.foto-layout-manual')) {
            return;
        }

        indice = obtenerIndiceCampo(contenedor);

        /*
         * En Create, images_base64[] no contiene un indice escrito en el name.
         * Se utiliza el orden de la tarjeta para que cada fotografia tenga su
         * propio grupo de radios: foto_posicion[0], foto_posicion[1], etc.
         */
        if (indice === null) {
            indice = orden;
        }
        valores = valoresPredeterminados(contenedor, orden);

        // Cada opcion comparte el mismo name para funcionar como grupo de radios.
        radios = posiciones.map(function (item) {
            var id = 'fotoPosicion_' + (indice === null ? 'nuevo_' + orden : indice) + '_' + item.value;
            return '<label class="form-check form-check-inline mb-1" for="' + id + '">' +
                '<input class="form-check-input" type="radio" data-foto-posicion ' +
                    'name="' + escapar(nombreCampo('foto_posicion', indice)) + '" ' +
                    'id="' + id + '" value="' + item.value + '" ' +
                    (valores.posicion === item.value ? 'checked' : '') + '>' +
                '<span class="form-check-label">' + item.label + '</span>' +
            '</label>';
        }).join('');

        bloque = document.createElement('div');
        bloque.className = 'foto-layout-manual border rounded p-2 mt-2 bg-light';
        bloque.innerHTML =
            '<div class="row align-items-center">' +
                '<div class="col-md-3 mb-2">' +
                    '<label class="font-weight-bold mb-1">Número de hoja</label>' +
                    '<input type="number" min="1" class="form-control form-control-sm foto-pagina" ' +
                        'name="' + escapar(nombreCampo('foto_pagina', indice)) + '" value="' + valores.pagina + '">' +
                '</div>' +
                '<div class="col-md-9">' +
                    '<div class="font-weight-bold mb-1">Posición en la hoja</div>' + radios +
                '</div>' +
            '</div>' +
            (permiteCuadroTexto ?
                '<div class="form-check mt-2">' +
                    '<input class="form-check-input foto-texto-checkbox" type="checkbox" ' +
                        'name="' + escapar(nombreCampo('foto_es_texto', indice)) + '" ' +
                        'id="fotoEsTexto_' + indice + '" value="1" ' + (esCuadroTexto ? 'checked' : '') + '>' +
                    '<label class="form-check-label font-weight-bold" for="fotoEsTexto_' + indice + '">' +
                        escapar(etiquetaCuadroTexto) +
                    '</label>' +
                    '<small class="form-text text-muted">El texto ocupará el mismo espacio que una fotografía.</small>' +
                '</div>' : '');

        /*
         * La interfaz anterior usaba un checkbox para "imagen de hoja completa".
         * Se oculta, pero su campo hidden se conserva por compatibilidad con el
         * controlador que procesa el formulario.
         */
        checkboxAnterior = contenedor.querySelector('.imagen-hoja-checkbox');
        if (checkboxAnterior) {
            if (checkboxAnterior.closest('.form-check')) {
                checkboxAnterior.closest('.form-check').style.display = 'none';
            } else {
                checkboxAnterior.style.display = 'none';
            }
        }

        // Coloca los controles antes de la vista previa de la fotografia.
        contenedor.querySelector('.form-group').insertBefore(
            bloque,
            contenedor.querySelector('.image-preview') || null
        );

        // Sincroniza imagen_hoja cada vez que cambia la posicion seleccionada.
        bloque.querySelectorAll('input[type="radio"][data-foto-posicion]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                sincronizarHojaCompleta(contenedor);
            });
        });

        // También sincroniza el valor inicial al crear los controles.
        sincronizarHojaCompleta(contenedor);

        // En modo texto se oculta la carga de archivo y el comentario ocupa el espacio completo.
        if (permiteCuadroTexto) {
            var checkboxTexto = bloque.querySelector('.foto-texto-checkbox');
            var archivo = contenedor.querySelector('input[type="file"]');
            var vistaPrevia = contenedor.querySelector('.image-preview');
            var comentario = contenedor.querySelector('textarea[name^="comments"]');
            var etiquetaArchivo = archivo ? contenedor.querySelector('label[for="' + archivo.id + '"]') : null;

            var actualizarModoTexto = function () {
                var activo = checkboxTexto.checked;
                if (archivo) {
                    archivo.disabled = activo;
                    archivo.style.display = activo ? 'none' : '';
                }
                if (etiquetaArchivo) etiquetaArchivo.style.display = activo ? 'none' : '';
                if (vistaPrevia) vistaPrevia.style.display = activo ? 'none' : '';
                if (comentario) {
                    comentario.rows = activo ? 8 : 3;
                    comentario.placeholder = activo
                        ? 'Escriba el contenido que aparecerá dentro del cuadro de texto'
                        : 'Comentario de la fotografía';
                }
            };

            checkboxTexto.addEventListener('change', actualizarModoTexto);
            actualizarModoTexto();
        }

        /*
         * En FOR-PIMP-06_B/01 los disparos tienen una distribución fija dentro
         * del reporte principal. La distribución manual aplica únicamente a las
         * fotografías adicionales.
         */
        checkboxDisparo = contenedor.querySelector('.foto-disparo-checkbox');
        if (checkboxDisparo) {
            var actualizarVisibilidadLayout = function () {
                if (checkboxDisparo.checked && checkboxTexto && checkboxTexto.checked) {
                    checkboxTexto.checked = false;
                    checkboxTexto.dispatchEvent(new Event('change'));
                }
                bloque.style.display = checkboxDisparo.checked ? 'none' : '';
            };

            checkboxDisparo.addEventListener('change', actualizarVisibilidadLayout);
            actualizarVisibilidadLayout();

            if (checkboxTexto) {
                checkboxTexto.addEventListener('change', function () {
                    if (checkboxTexto.checked && checkboxDisparo.checked) {
                        checkboxDisparo.checked = false;
                        checkboxDisparo.dispatchEvent(new Event('change'));
                    }
                });
            }
        }

        campoDisparo = contenedor.querySelector('input[name^="es_disparo"]');
        if (campoDisparo && campoDisparo.value === '1') {
            bloque.style.display = 'none';
        }
    }

    /*
     * Si se elimina una foto en Create, vuelve a numerar los campos restantes
     * para que coincidan con el orden compacto de images_base64[] y comments[].
     */
    function sincronizarIndicePorOrden(contenedor, orden) {
        var bloque;
        var indice;

        if (!reindexarPorOrden) {
            return;
        }

        bloque = contenedor.querySelector('.foto-layout-manual');
        if (!bloque) {
            return;
        }

        // Si la tarjeta ya tiene un índice explícito, se conserva aunque se
        // eliminen otras tarjetas; solo los formularios heredados usan el orden.
        indice = obtenerIndiceCampo(contenedor);
        if (indice === null) {
            indice = orden;
        }

        bloque.querySelectorAll('input[data-foto-posicion]').forEach(function (radio) {
            radio.name = nombreCampo('foto_posicion', indice);
        });

        bloque.querySelectorAll('.foto-pagina').forEach(function (pagina) {
            pagina.name = nombreCampo('foto_pagina', indice);
        });

        bloque.querySelectorAll('.foto-texto-checkbox').forEach(function (checkbox) {
            checkbox.name = nombreCampo('foto_es_texto', indice);
        });
    }

    /* Agrega los controles a todas las tarjetas presentes en el contenedor. */
    function actualizarControles(contenedorRaiz) {
        var tarjetas = Array.prototype.slice.call(
            contenedorRaiz.querySelectorAll('[id^="image-container-"]')
        );

        tarjetas.forEach(function (tarjeta, orden) {
            crearControl(tarjeta, orden);
            sincronizarIndicePorOrden(tarjeta, orden);
        });
    }

    /* Usa SweetAlert si esta disponible; de lo contrario usa alert nativo. */
    function mostrarError(mensaje) {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                icon: 'warning',
                title: 'Distribucion de fotografias',
                text: mensaje,
                confirmButtonText: 'Aceptar'
            });
            return;
        }

        window.alert(mensaje);
    }

    /*
     * Comprueba antes del envio que:
     *  - todas las paginas sean numeros mayores o iguales a 1;
     *  - un cuadrante no se repita dentro de la misma pagina;
     *  - una foto de pagina completa no comparta hoja con otras fotos.
     */
    function validarDistribucion(formulario) {
        var ocupadas = {};
        var error = '';
        var automaticas = posicionesAutomaticas(formulario);

        // Primero registra los elementos generados por Fiji y el tamaño de grano elegido en una tarjeta.
        automaticas.forEach(function (elemento) {
            if (error) return;
            ocupadas[elemento.pagina] = ocupadas[elemento.pagina] || [];

            if (elemento.posicion === 'pagina_completa' && ocupadas[elemento.pagina].length) {
                error = elemento.etiqueta + ' no puede usar la hoja ' + elemento.pagina +
                    ' completa porque esa hoja ya contiene otro elemento.';
                return;
            }
            if (ocupadas[elemento.pagina].indexOf('pagina_completa') !== -1
                || ocupadas[elemento.pagina].indexOf(elemento.posicion) !== -1) {
                error = elemento.etiqueta + ' repite la posición "' +
                    elemento.posicion.replace(/_/g, ' ') + '" en la hoja ' + elemento.pagina + '.';
                return;
            }
            ocupadas[elemento.pagina].push(elemento.posicion);
        });

        formulario.querySelectorAll('[id^="image-container-"]').forEach(function (tarjeta) {
            var paginaInput = tarjeta.querySelector('.foto-pagina');
            var posicionInput = tarjeta.querySelector('input[data-foto-posicion]:checked');
            var checkboxDisparo = tarjeta.querySelector('.foto-disparo-checkbox');
            var checkboxGrano = tarjeta.querySelector('.foto-grain-checkbox');
            var pagina;
            var posicion;

            // Ignora tarjetas eliminadas/ocultas y detiene nuevas comprobaciones al fallar.
            if (error || !paginaInput || !posicionInput || tarjeta.style.display === 'none'
                || (checkboxDisparo && checkboxDisparo.checked)
                || (checkboxGrano && checkboxGrano.checked)) {
                return;
            }

            pagina = parseInt(paginaInput.value, 10);
            posicion = posicionInput.value;

            if (!pagina || pagina < 1) {
                error = 'Todas las fotografias deben tener un numero de hoja mayor o igual a 1.';
                return;
            }

            // Registra las posiciones ya utilizadas agrupadas por numero de pagina.
            ocupadas[pagina] = ocupadas[pagina] || [];

            if (posicion === 'pagina_completa' && ocupadas[pagina].length) {
                error = automaticas.some(function (item) { return item.pagina === pagina; })
                    ? 'La hoja ' + pagina + ' ya contiene elementos de Fiji o del patrón de grano; no puede usarse como página completa.'
                    : 'La hoja ' + pagina + ' esta marcada como pagina completa y tambien contiene otras fotografias.';
                return;
            }

            if (ocupadas[pagina].indexOf('pagina_completa') !== -1) {
                error = 'La hoja ' + pagina + ' ya contiene una fotografia de pagina completa.';
                return;
            }

            if (ocupadas[pagina].indexOf(posicion) !== -1) {
                error = automaticas.some(function (item) {
                    return item.pagina === pagina && item.posicion === posicion;
                })
                    ? 'La posición "' + posicion.replace(/_/g, ' ') + '" de la hoja ' + pagina + ' está reservada por Fiji o por el patrón comparativo.'
                    : 'La posicion "' + posicion.replace(/_/g, ' ') + '" esta repetida en la hoja ' + pagina + '.';
                return;
            }

            ocupadas[pagina].push(posicion);
        });

        if (error) {
            mostrarError(error);
            return false;
        }

        return true;
    }

    /* Valida en navegador la misma regla de pares que el controlador vuelve a comprobar. */
    function validarDisparosManuales(formulario) {
        if (['FOR-PIMP-04_03', 'FOR-PIMP-05_B_01'].indexOf(formulario.id) === -1) {
            return true;
        }

        var conteo = { 1: 0, 2: 0, 3: 0 };
        var error = '';

        formulario.querySelectorAll('.foto-disparo-checkbox:checked').forEach(function (checkbox) {
            var tarjeta = checkbox.closest('[id^="image-container-"]');
            var eliminado = tarjeta ? tarjeta.querySelector('input[name="deleted_images[]"]') : null;
            var base64 = tarjeta ? tarjeta.querySelector('input[name^="images_base64"]') : null;
            var existente = tarjeta ? tarjeta.querySelector('input[name^="existing_images"]') : null;
            var selector = tarjeta ? tarjeta.querySelector('select[name^="numero_disparo"]') : null;
            var numero = selector ? selector.value : '';

            if (error || !tarjeta || tarjeta.style.display === 'none' || (eliminado && eliminado.value !== '')) {
                return;
            }

            if ((!base64 || !base64.value) && (!existente || !existente.value)) {
                error = 'Guarde o recorte la imagen antes de asignarla a un disparo.';
                return;
            }

            if (!Object.prototype.hasOwnProperty.call(conteo, numero)) {
                error = 'Seleccione si la imagen corresponde al disparo 1, 2 o 3.';
                return;
            }

            conteo[numero]++;
        });

        if (!error) {
            Object.keys(conteo).some(function (numero) {
                if (conteo[numero] !== 0 && conteo[numero] !== 2) {
                    error = 'El disparo ' + numero + ' debe contener exactamente dos imágenes.';
                    return true;
                }
                return false;
            });
        }

        if (error) {
            mostrarError(error);
            return false;
        }

        return true;
    }

    /* Calcula el promedio cuando el formato contiene la tabla de dureza. */
    function inicializarPromedioDureza(formulario) {
        var valores = Array.prototype.slice.call(formulario.querySelectorAll('.valor-dureza-medida'));
        var promedio = formulario.querySelector('#promedioDureza');

        if (!valores.length || !promedio || promedio.dataset.promedioInicializado === '1') {
            return;
        }

        function calcular() {
            var numeros = valores
                .map(function (input) { return input.value.trim().replace(',', '.'); })
                .filter(function (valor) { return valor !== '' && /^(?:\d+(?:\.\d*)?|\.\d+)$/.test(valor); })
                .map(Number)
                .filter(Number.isFinite);

            promedio.value = numeros.length
                ? (numeros.reduce(function (total, valor) { return total + valor; }, 0) / numeros.length).toFixed(2)
                : '';
        }

        promedio.dataset.promedioInicializado = '1';
        valores.forEach(function (input) {
            input.addEventListener('input', calcular);
        });
        calcular();
    }

    /* Inicializa el modulo cuando el formulario ya existe en el DOM. */
    document.addEventListener('DOMContentLoaded', function () {
        var formulario = document.getElementById('FOR-PIMP-02_B_04')
            || document.getElementById('FOR-PIMP-03_B_01')
            || document.getElementById('FOR-PIMP-04_02')
            || document.getElementById('FOR-PIMP-04_03')
            || document.getElementById('FOR-PIMP-05_B_01')
            || document.getElementById('FOR-PIMP-06_B_01');
        var raiz;
        var observador;

        // El script solo se activa en el formulario y contenedor correspondientes.
        if (!formulario || !formulario.querySelector('[data-layout-fotos-manual="1"]')) {
            return;
        }

        inicializarPromedioDureza(formulario);
        raiz = formulario.querySelector('[data-layout-fotos-manual="1"]');
        reindexarPorOrden = raiz.id === 'imageFieldsContainer'
            && formulario.id !== 'FOR-PIMP-06_B_01';
        actualizarControles(raiz);

        /*
         * Las tarjetas pueden agregarse despues de cargar la pagina. El observador
         * detecta esos cambios y crea controles solamente para las nuevas.
         */
        observador = new MutationObserver(function () {
            actualizarControles(raiz);
        });
        observador.observe(raiz, { childList: true, subtree: true });

        // Cancela el envio si la distribucion contiene posiciones incompatibles.
        formulario.addEventListener('submit', function (evento) {
            if (!validarDistribucion(formulario) || !validarDisparosManuales(formulario)) {
                evento.preventDefault();
                evento.stopImmediatePropagation();
            }
        }, true);
    });
}(window, document));
