(function () {
    'use strict';

    /**
     * Garantiza que las bajas de FOTOS sobrevivan aunque otro módulo quite la tarjeta del DOM.
     * Los cuadros de texto no tienen ruta de archivo, por eso la identidad confiable es su índice.
     */
    document.addEventListener('DOMContentLoaded', function () {
        const formulario = document.getElementById('FOR-PIMP-04_02');
        if (!formulario) return;

        formulario.addEventListener('click', function (evento) {
            const boton = evento.target.closest('.remove-image');
            if (!boton) return;

            const indice = String(boton.dataset.index || '');
            const tarjeta = boton.closest('[id^="image-container-"]');
            if (!tarjeta || indice === '') return;

            // Evita que los manejadores generales retiren también el hidden que registra la baja.
            evento.preventDefault();
            evento.stopImmediatePropagation();

            const esRegistroExistente = Boolean(tarjeta.querySelector('input[name^="existing_images["]'));
            if (esRegistroExistente) {
                const marca = document.createElement('input');
                marca.type = 'hidden';
                marca.name = 'deleted_images[]';
                marca.value = indice;
                marca.dataset.deletedPhotoIndex = indice;

                // Una sola marca por índice evita solicitudes duplicadas si se pulsa dos veces.
                if (!formulario.querySelector('[data-deleted-photo-index="' + indice + '"]')) {
                    formulario.appendChild(marca);
                }
            }

            // Se limpian borradores locales para que un comentario eliminado no reaparezca al recargar Edit.
            tarjeta.querySelectorAll('input, textarea, select').forEach(function (campo) {
                if (campo.name) localStorage.removeItem('FOR-PIMP-04_02_Form_' + campo.name);
            });

            tarjeta.remove();
        }, true);
    });
})();
