(function (document) {
    'use strict';

    /** Sincroniza un select editable con el campo real que guarda Laravel. */
    function iniciarCatalogo(contenedor) {
        var valor = contenedor.querySelector('[data-catalogo-metalografia-valor]');
        var selector = contenedor.querySelector('[data-catalogo-metalografia-selector]');
        var nuevo = contenedor.querySelector('[data-catalogo-metalografia-nuevo]');
        var actual;
        var opcion;

        if (!valor || !selector || !nuevo) return;

        actual = String(valor.value || '').trim();
        opcion = Array.prototype.find.call(selector.options, function (elemento) {
            return elemento.value !== '' && elemento.value !== '__nuevo__'
                && elemento.value.toLocaleLowerCase() === actual.toLocaleLowerCase();
        });

        if (opcion) {
            selector.value = opcion.value;
        } else if (actual !== '') {
            selector.value = '__nuevo__';
            nuevo.value = actual;
        }

        function notificar() {
            valor.dispatchEvent(new Event('input', { bubbles: true }));
            valor.dispatchEvent(new Event('change', { bubbles: true }));
        }

        function sincronizar() {
            if (selector.value === '__nuevo__') {
                nuevo.classList.remove('d-none');
                valor.value = String(nuevo.value || '').trim();
            } else {
                nuevo.classList.add('d-none');
                nuevo.value = '';
                valor.value = selector.value;
            }
            notificar();
        }

        selector.addEventListener('change', function () {
            sincronizar();
            if (selector.value === '__nuevo__') nuevo.focus();
        });
        nuevo.addEventListener('input', function () {
            valor.value = String(nuevo.value || '').trim();
            notificar();
        });

        sincronizar();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-catalogo-metalografia]').forEach(iniciarCatalogo);
    });
})(document);

