(function (window, document) {
    'use strict';

    /*
     * Relaciona cada columna de mediciones con el sufijo de su campo resumen.
     * La etapa del formulario determina si alimenta ANTES o DESPUES.
     */
    var columnas = {
        metal_base_a: 'A',
        zac_b: 'B',
        soldadura_c: 'C',
        zac_b1: 'B1',
        metal_base_a1: 'BM'
    };

    /*
     * Cantidad de decimales que se muestran en el promedio.
     * 0 = numero entero; 2 = dos decimales.
     */
    var decimalesPromedio = 0;

    /*
     * Convierte una entrada a numero solamente si todo su contenido tiene un
     * formato numerico valido. Tambien admite coma como separador decimal.
     * Devuelve null para campos vacios, texto o valores no finitos.
     */
    function obtenerNumero(valor) {
        var normalizado = String(valor == null ? '' : valor).trim().replace(',', '.');

        // Evita que Number convierta entradas parciales o vacias de forma silenciosa.
        if (!/^[+-]?(?:\d+(?:\.\d*)?|\.\d+)$/.test(normalizado)) {
            return null;
        }

        normalizado = Number(normalizado);
        return Number.isFinite(normalizado) ? normalizado : null;
    }

    /*
     * Redondea el resultado con la cantidad configurada en decimalesPromedio.
     * Number.EPSILON ayuda a reducir errores de precision de punto flotante.
     */
    function formatearPromedio(valor) {
        var factor = Math.pow(10, decimalesPromedio);
        return String(Math.round((valor + Number.EPSILON) * factor) / factor);
    }

    /* Calcula y escribe el promedio de cada columna de dureza. */
    function calcularPromedios(formulario) {
        var cuerpo = formulario.querySelector('#durezaBrinellBody');
        var etapa = formulario.getAttribute('data-dureza-etapa') === 'DESPUES'
            ? 'DESPUES'
            : 'ANTES';
        var etapaInactiva = etapa === 'DESPUES' ? 'ANTES' : 'DESPUES';

        // Sin la tabla de mediciones no hay valores que procesar.
        if (!cuerpo) {
            return;
        }

        Object.keys(columnas).forEach(function (columna) {
            var valores = [];
            var salida = formulario.querySelector('input[name="Dureza[' + etapa + '_' + columnas[columna] + ']"]');

            // Reune unicamente los valores numericos validos de la columna actual.
            cuerpo.querySelectorAll('input[name$="[' + columna + ']"]').forEach(function (input) {
                var numero = obtenerNumero(input.value);

                if (numero !== null) {
                    valores.push(numero);
                }
            });

            if (!salida) {
                return;
            }

            // El promedio es calculado; el usuario no debe editarlo manualmente.
            salida.readOnly = true;

            // Suma los valores, divide entre su cantidad y deja vacio si no existen.
            salida.value = valores.length
                ? formatearPromedio(valores.reduce(function (total, numero) {
                    return total + numero;
                }, 0) / valores.length)
                : '';
        });

        // La etapa no seleccionada conserva sus datos y usa guiones cuando aun no existe medicion.
        formulario.querySelectorAll('input[name^="Dureza[' + etapaInactiva + '_"]').forEach(function (entradaInactiva) {
            entradaInactiva.readOnly = true;
            if (String(entradaInactiva.value || '').trim() === '') {
                entradaInactiva.value = '---';
            }
        });
    }

    /** Mantiene sincronizados el select, el calculo y el titulo que se guardara. */
    function mostrarFlechaSelector(selector) {
            // Identifica visualmente los campos que permiten elegir o escribir una opción nueva.
            selector.style.appearance = 'none';
            selector.style.backgroundImage = 'url("data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2712%27 height=%278%27 viewBox=%270 0 12 8%27%3E%3Cpath d=%27M1 1l5 5 5-5%27 fill=%27none%27 stroke=%27%233674b5%27 stroke-width=%272%27/%3E%3C/svg%3E")';
            selector.style.backgroundRepeat = 'no-repeat';
            selector.style.backgroundPosition = 'right 8px center';
            selector.style.paddingRight = '28px';
            selector.style.cursor = 'pointer';
    }

    function inicializarEtapa(formulario) {
        var selector = formulario.querySelector('[data-dureza-etapa-select]');

        if (!selector) {
            return;
        }

        mostrarFlechaSelector(selector);
        formulario.setAttribute('data-dureza-etapa', selector.value === 'DESPUES' ? 'DESPUES' : 'ANTES');
        selector.addEventListener('change', function () {
            formulario.setAttribute('data-dureza-etapa', selector.value === 'DESPUES' ? 'DESPUES' : 'ANTES');
            calcularPromedios(formulario);
        });
    }

    /**
     * Convierte cada catalogo en un control claro: permite seleccionar un valor
     * guardado o habilita una caja independiente cuando el usuario elige crear uno.
     * El valor final se copia al campo oculto que ya conoce el backend.
     */
    function inicializarCatalogosEditables(formulario) {
        formulario.querySelectorAll('[data-catalogo-selector]').forEach(function (selector) {
            mostrarFlechaSelector(selector);
            var clave = selector.getAttribute('data-catalogo-selector');
            var valor = formulario.querySelector('[data-catalogo-valor="' + clave + '"]');
            var entradaNueva = formulario.querySelector('[data-catalogo-nuevo="' + clave + '"]');
            var valorActual;
            var opcionExistente;

            if (!valor || !entradaNueva) {
                return;
            }

            valorActual = String(valor.value || '').trim();
            opcionExistente = Array.prototype.find.call(selector.options, function (opcion) {
                return opcion.value !== ''
                    && opcion.value !== '__nuevo__'
                    && opcion.value.toLocaleLowerCase() === valorActual.toLocaleLowerCase();
            });

            // En Edit conserva valores antiguos aunque todavia no formen parte del catalogo.
            if (opcionExistente) {
                selector.value = opcionExistente.value;
            } else if (valorActual !== '') {
                selector.value = '__nuevo__';
                entradaNueva.value = valorActual;
            } else {
                selector.value = '';
            }

            var notificarCambio = function () {
                valor.dispatchEvent(new Event('input', { bubbles: true }));
            };

            var sincronizar = function () {
                if (selector.value === '__nuevo__') {
                    entradaNueva.classList.remove('d-none');
                    valor.value = String(entradaNueva.value || '').trim();
                } else {
                    entradaNueva.classList.add('d-none');
                    entradaNueva.value = '';
                    valor.value = selector.value;
                }

                notificarCambio();
            };

            selector.addEventListener('change', function () {
                sincronizar();
                if (selector.value === '__nuevo__') {
                    entradaNueva.focus();
                }
            });

            entradaNueva.addEventListener('input', function () {
                valor.value = String(entradaNueva.value || '').trim();
                notificarCambio();
            });

            sincronizar();
        });
    }

    /** Refleja en ambas tablas el nombre libre elegido para los materiales A y A1. */
    function inicializarEtiquetasMaterial(formulario) {
        formulario.querySelectorAll('[data-etiqueta-material]').forEach(function (entrada) {
            var clave = entrada.getAttribute('data-etiqueta-material');
            var actualizar = function () {
                var texto = String(entrada.value || '').trim() || 'Base Metal';
                formulario.querySelectorAll('[data-etiqueta-material-vista="' + clave + '"]').forEach(function (vista) {
                    vista.textContent = texto;
                });
            };

            entrada.addEventListener('input', actualizar);
            entrada.addEventListener('change', actualizar);
            actualizar();
        });
    }

    /** Muestra la escala capturada en todos los encabezados español/ingles del formulario. */
    function inicializarEscalaDureza(formulario) {
        var entrada = formulario.querySelector('[data-escala-dureza]');

        if (!entrada) {
            return;
        }

        var actualizar = function () {
            var escala = String(entrada.value || '').trim() || '---';
            formulario.querySelectorAll('[data-escala-dureza-vista]').forEach(function (vista) {
                vista.textContent = escala;
            });
        };

        entrada.addEventListener('input', actualizar);
        entrada.addEventListener('change', actualizar);
        actualizar();
    }

    /* Inicializa el calculo cuando el formulario ya esta disponible en el DOM. */
    document.addEventListener('DOMContentLoaded', function () {
        var formulario = document.getElementById('FOR-PIMP-02_B_04');
        var cuerpo;
        var observador;

        // Este archivo solo actua sobre el formulario FOR-PIMP-02_B_04.
        if (!formulario) {
            return;
        }

        cuerpo = formulario.querySelector('#durezaBrinellBody');
        if (!cuerpo) {
            return;
        }

        // Completa la descripción desde el nombre, manteniendo las modificaciones manuales por fila.
        var nombrePieza = formulario.querySelector('[name="Detalles_Generales[Nom_Pieza]"]');
        var descripciones = new WeakMap();
        function actualizarDescripciones() {
            if (!nombrePieza) return;
            formulario.querySelectorAll('#durezaBrinellBody input[name$="[descripcion]"]').forEach(function (entrada) {
                var anterior = descripciones.get(entrada);
                if (!anterior) {
                    // Los guiones del autorrelleno son marcadores, no descripciones escritas por el técnico.
                    var vacia = /^(?:\s*|-{1,3})$/.test(entrada.value.trim());
                    anterior = { manual: !vacia && entrada.value !== nombrePieza.value, ultimo: entrada.value };
                    descripciones.set(entrada, anterior);
                }
                // Combinar celdas también cambia valores por código; eso no debe cortar el enlace.
                if (!anterior.manual) {
                    entrada.value = nombrePieza.value;
                    anterior.ultimo = entrada.value;
                }
            });
        }
        formulario.addEventListener('input', function (evento) {
            if (evento.target === nombrePieza) actualizarDescripciones();
            else if (evento.target.matches('#durezaBrinellBody input[name$="[descripcion]"]')) {
                descripciones.set(evento.target, {manual: true, ultimo: evento.target.value});
            }
        });
        if (nombrePieza) nombrePieza.addEventListener('change', actualizarDescripciones);
        actualizarDescripciones();

        inicializarCatalogosEditables(formulario);
        inicializarEtapa(formulario);
        inicializarEtiquetasMaterial(formulario);
        inicializarEscalaDureza(formulario);

        /*
         * Recalcula cuando cambia una medicion directa de la tabla o un campo
         * utilizado por el sistema de llenado automatico.
         */
        formulario.addEventListener('input', function (evento) {
            var campoPlantilla = evento.target.getAttribute('data-auto-fill-field');

            if (evento.target.matches('#durezaBrinellBody input[name^="Dureza["]') || columnas[campoPlantilla]) {
                /*
                 * Se calcula al terminar el evento para permitir que el relleno
                 * automatico y las celdas combinadas repliquen primero su valor.
                 */
                window.setTimeout(function () {
                    calcularPromedios(formulario);
                }, 0);
            }
        });

        /*
         * Las filas de dureza pueden agregarse o eliminarse dinamicamente.
         * MutationObserver recalcula cuando cambia la estructura de la tabla.
         */
        observador = new MutationObserver(function () {
            actualizarDescripciones();
            calcularPromedios(formulario);
        });
        observador.observe(cuerpo, { childList: true, subtree: true });

        // Realiza un ultimo calculo antes de enviar los datos al servidor.
        formulario.addEventListener('submit', function () {
            actualizarDescripciones();
            calcularPromedios(formulario);
        }, true);

        // Calcula los datos que ya estaban presentes al abrir la pantalla.
        calcularPromedios(formulario);

        /*
         * Repite el calculo al terminar la ejecucion actual para recoger cambios
         * efectuados durante la inicializacion por otros scripts del formulario.
         */
        window.setTimeout(function () {
            actualizarDescripciones();
            calcularPromedios(formulario);
        }, 0);
    });
}(window, document));
