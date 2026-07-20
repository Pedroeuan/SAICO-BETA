(function (window, document) {
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
        { value: 'pagina_completa', label: 'Pagina completa' }
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
     * Recupera la pagina y posicion guardadas en los atributos data-*.
     * Si no existen, distribuye automaticamente cuatro fotos por pagina
     * siguiendo el orden de los cuatro cuadrantes.
     */
    function valoresPredeterminados(contenedor, orden) {
        var pagina = parseInt(contenedor.getAttribute('data-foto-pagina'), 10);
        var posicion = contenedor.getAttribute('data-foto-posicion');
        var esHojaCompleta = contenedor.getAttribute('data-foto-hoja-completa') === '1';

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
                    '<label class="font-weight-bold mb-1">Numero de hoja</label>' +
                    '<input type="number" min="1" class="form-control form-control-sm foto-pagina" ' +
                        'name="' + escapar(nombreCampo('foto_pagina', indice)) + '" value="' + valores.pagina + '">' +
                '</div>' +
                '<div class="col-md-9">' +
                    '<div class="font-weight-bold mb-1">Posicion en la hoja</div>' + radios +
                '</div>' +
            '</div>';

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
    }

    /*
     * Si se elimina una foto en Create, vuelve a numerar los campos restantes
     * para que coincidan con el orden compacto de images_base64[] y comments[].
     */
    function sincronizarIndicePorOrden(contenedor, orden) {
        var bloque;

        if (!reindexarPorOrden) {
            return;
        }

        bloque = contenedor.querySelector('.foto-layout-manual');
        if (!bloque) {
            return;
        }

        bloque.querySelectorAll('input[data-foto-posicion]').forEach(function (radio) {
            radio.name = nombreCampo('foto_posicion', orden);
        });

        bloque.querySelectorAll('.foto-pagina').forEach(function (pagina) {
            pagina.name = nombreCampo('foto_pagina', orden);
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

        formulario.querySelectorAll('[id^="image-container-"]').forEach(function (tarjeta) {
            var paginaInput = tarjeta.querySelector('.foto-pagina');
            var posicionInput = tarjeta.querySelector('input[data-foto-posicion]:checked');
            var pagina;
            var posicion;

            // Ignora tarjetas eliminadas/ocultas y detiene nuevas comprobaciones al fallar.
            if (error || !paginaInput || !posicionInput || tarjeta.style.display === 'none') {
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
                error = 'La hoja ' + pagina + ' esta marcada como pagina completa y tambien contiene otras fotografias.';
                return;
            }

            if (ocupadas[pagina].indexOf('pagina_completa') !== -1) {
                error = 'La hoja ' + pagina + ' ya contiene una fotografia de pagina completa.';
                return;
            }

            if (ocupadas[pagina].indexOf(posicion) !== -1) {
                error = 'La posicion "' + posicion.replace(/_/g, ' ') + '" esta repetida en la hoja ' + pagina + '.';
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

    /* Inicializa el modulo cuando el formulario ya existe en el DOM. */
    document.addEventListener('DOMContentLoaded', function () {
        var formulario = document.getElementById('FOR-PIMP-02_B_04')
            || document.getElementById('FOR-PIMP-03_B_01');
        var raiz;
        var observador;

        // El script solo se activa en el formulario y contenedor correspondientes.
        if (!formulario || !formulario.querySelector('[data-layout-fotos-manual="1"]')) {
            return;
        }

        raiz = formulario.querySelector('[data-layout-fotos-manual="1"]');
        reindexarPorOrden = raiz.id === 'imageFieldsContainer';
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
            if (!validarDistribucion(formulario)) {
                evento.preventDefault();
                evento.stopImmediatePropagation();
            }
        }, true);
    });
}(window, document));
