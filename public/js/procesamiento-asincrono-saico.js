(function () {
    'use strict';

    /** Espera un UUID hasta obtener una salida terminal del servidor. */
    async function esperar(estadoUrl, opciones) {
        const configuracion = opciones || {};
        while (true) {
            const respuesta = await fetch(estadoUrl, {
                headers: { 'Accept': 'application/json' },
                signal: configuracion.signal,
            });
            const cuerpo = await respuesta.json();
            const trabajo = cuerpo.trabajo || {};

            if (trabajo.estado === 'completado') return trabajo;
            if (!respuesta.ok || trabajo.estado === 'error') {
                throw new Error(trabajo.mensaje || 'No fue posible procesar.');
            }
            if (typeof configuracion.alCambiar === 'function') {
                configuracion.alCambiar(trabajo);
            }
            await new Promise(function (resolver) {
                window.setTimeout(resolver, configuracion.intervalo || 1500);
            });
        }
    }

    /** Crea o actualiza el campo que vincula el reporte con sus temporales privados. */
    function asignarTrabajo(formulario, trabajoId) {
        let campo = formulario.querySelector('input[name="XRF_Trabajo_ID"]');
        if (!campo) {
            campo = document.createElement('input');
            campo.type = 'hidden';
            campo.name = 'XRF_Trabajo_ID';
            formulario.appendChild(campo);
        }
        campo.value = trabajoId || '';
    }

    window.SaicoProcesamiento = { esperar: esperar, asignarTrabajo: asignarTrabajo };
})();
