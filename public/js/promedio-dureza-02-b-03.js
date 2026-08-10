(function (document) {
    'use strict';

    /*
     * Calcula exclusivamente el promedio general del FOR-PIMP-02_B_03.
     * Se mantiene separado de fotografias, XRF y otros formatos para evitar acoplamientos.
     */
    function iniciarPromedioDureza0203() {
        var formulario = document.getElementById('FOR-PIMP-02_B_03');
        var salida;

        if (!formulario) {
            return;
        }

        salida = formulario.querySelector('input[name="Datos_Equipo[DUREZA_PROMEDIO_MEDIDO]"]');
        if (!salida) {
            return;
        }

        salida.readOnly = true;

        function esLecturaDureza(input) {
            return /^valor_dureza[1-5]\[[^\]]+\]\[\]$/.test(input.name || '');
        }

        function recalcular() {
            var valores = [];

            formulario.querySelectorAll('input[name^="valor_dureza"]').forEach(function (input) {
                var texto;
                var numero;

                if (!esLecturaDureza(input)) {
                    return;
                }

                texto = input.value.trim().replace(',', '.');
                if (texto === '' || /^-{1,3}$/.test(texto)) {
                    return;
                }

                if (!/^(?:\d+(?:\.\d*)?|\.\d+)$/.test(texto)) {
                    return;
                }

                numero = Number(texto);
                if (Number.isFinite(numero)) {
                    valores.push(numero);
                }
            });

            salida.value = valores.length
                ? String(Math.round(valores.reduce(function (total, valor) {
                    return total + valor;
                }, 0) / valores.length))
                : '';
        }

        /* La delegacion tambien cubre las filas que el tecnico agregue despues de abrir la pagina. */
        formulario.addEventListener('input', function (evento) {
            if (evento.target instanceof HTMLInputElement && esLecturaDureza(evento.target)) {
                recalcular();
            }
        });

        formulario.addEventListener('click', function () {
            // Los botones pueden agregar o eliminar filas; se espera a que termine su manejador actual.
            window.setTimeout(recalcular, 0);
        });

        /* Edit reconstruye sus filas desde datos guardados; el observador calcula al terminar esa carga. */
        var cuerpoTabla = formulario.querySelector('#dynamicTable tbody');
        if (cuerpoTabla && typeof MutationObserver !== 'undefined') {
            new MutationObserver(recalcular).observe(cuerpoTabla, {
                childList: true,
                subtree: true
            });
        }

        recalcular();
    }

    document.addEventListener('DOMContentLoaded', iniciarPromedioDureza0203);
})(document);
