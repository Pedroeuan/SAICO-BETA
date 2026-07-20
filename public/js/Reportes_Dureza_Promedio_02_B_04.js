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

        // En el consecutivo, los promedios ANTES pertenecen al reporte
        // original y nunca deben ser modificados por las mediciones nuevas.
        if (etapa === 'DESPUES') {
            formulario.querySelectorAll('input[name^="Dureza[ANTES_"]').forEach(function (entradaAnterior) {
                entradaAnterior.readOnly = true;
            });
        }
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
            calcularPromedios(formulario);
        });
        observador.observe(cuerpo, { childList: true, subtree: true });

        // Realiza un ultimo calculo antes de enviar los datos al servidor.
        formulario.addEventListener('submit', function () {
            calcularPromedios(formulario);
        }, true);

        // Calcula los datos que ya estaban presentes al abrir la pantalla.
        calcularPromedios(formulario);

        /*
         * Repite el calculo al terminar la ejecucion actual para recoger cambios
         * efectuados durante la inicializacion por otros scripts del formulario.
         */
        window.setTimeout(function () {
            calcularPromedios(formulario);
        }, 0);
    });
}(window, document));
