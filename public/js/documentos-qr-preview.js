/**
 * Vista previa de los C.V. y certificados elegidos para el QR, en Crear y Editar.
 * Lee la selección del formulario y consulta la ruta data-documentos-preview.
 * El servidor decide qué archivos corresponden al técnico, formato y procedimiento.
 * Esta consulta no guarda el reporte ni genera el PDF unificado del QR.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Cada bloque utiliza únicamente los campos del formulario que lo contiene.
    document.querySelectorAll('[data-documentos-preview]').forEach(section => {
        const form = section.closest('form');
        if (!form) return;
        const botones = [...section.querySelectorAll('[data-preview-categoria]')];
        // Invalida las respuestas pendientes cuando el usuario cambia el formulario.
        let revision = 0;
        // La cantidad de firmas identifica el grupo que contiene al técnico activo.
        const tecnico = () => {
            const numero = form.querySelector('[name="numFirmas"]')?.value || '1';
            return form.querySelector(`[name="Firmas_Reportes${numero}[ID_TECNICO]"]`)?.value || '';
        };
        // Muestra el ojo solo en categorías marcadas y retira los resultados anteriores.
        const actualizar = () => {
            revision++;
            botones.forEach(boton => {
                const categoria = boton.dataset.previewCategoria;
                boton.hidden = !section.querySelector(`input[type="checkbox"][name="Datos_Equipo[Documentos_Usuario][${categoria}]"]`)?.checked;
                section.querySelector(`[data-preview-resultado="${categoria}"]`).replaceChildren();
            });
        };
        form.addEventListener('change', actualizar);
        // Recupera la visibilidad inicial de las opciones guardadas al editar.
        actualizar();
        botones.forEach(boton => boton.addEventListener('click', async () => {
            const resultado = section.querySelector(`[data-preview-resultado="${boton.dataset.previewCategoria}"]`);
            resultado.replaceChildren();
            // La consulta necesita el técnico seleccionado; no supone que sea el usuario de la sesión.
            if (!tecnico()) {
                resultado.textContent = 'Seleccione primero al técnico que realiza el reporte.';
                return;
            }
            // Envía la categoría del botón y la versión general o por servicio del C.V.
            const parametros = new URLSearchParams({
                tecnico: tecnico(), formato: section.dataset.formato,
                procedimiento: form.querySelector('[name="Detalles_Generales[idProcedimiento]"]')?.value || '',
                categoria: boton.dataset.previewCategoria,
                tipo_cv_reporte: section.querySelector('[name="Datos_Equipo[Documentos_Usuario][tipo_cv_reporte]"]')?.value || 'general'
            });
            // Conserva la revisión de esta consulta y evita clics repetidos mientras carga.
            const actual = revision;
            boton.disabled = true;
            resultado.textContent = 'Buscando documentos…';
            try {
                // Consulta autenticada, sin reutilizar resultados de la caché del navegador.
                const respuesta = await fetch(`${section.dataset.documentosPreview}?${parametros}`, {
                    headers: { Accept: 'application/json' }, credentials: 'same-origin', cache: 'no-store'
                });
                if (!respuesta.ok) throw new Error('No se pudo consultar la documentación. Verifique su sesión y la selección.');
                const datos = await respuesta.json();
                // Descarta el resultado si la selección cambió mientras respondía el servidor.
                if (actual !== revision) return;
                resultado.replaceChildren();
                if (!datos.archivos.length) {
                    resultado.textContent = 'El técnico no tiene documentos registrados para esta selección.';
                    return;
                }
                // Usa textContent para mostrar nombres como texto, sin interpretarlos como HTML.
                const titulo = document.createElement('p');
                titulo.className = 'documentos-qr-preview-tecnico';
                titulo.textContent = datos.tecnico;
                resultado.append(titulo);
                datos.archivos.forEach(archivo => {
                    const fila = document.createElement('div');
                    fila.className = 'documentos-qr-preview-archivo';
                    const nombre = document.createElement('span');
                    nombre.textContent = archivo.nombre;
                    fila.append(nombre);
                    // Solo ofrece abrir archivos disponibles; los faltantes muestran un aviso.
                    const enlace = document.createElement(archivo.disponible ? 'a' : 'span');
                    enlace.className = archivo.disponible ? 'documentos-qr-abrir-pdf' : 'documentos-qr-archivo-ausente';
                    if (archivo.disponible) {
                        enlace.href = archivo.url; enlace.target = '_blank'; enlace.rel = 'noopener';
                        enlace.setAttribute('aria-label', `Ver PDF: ${archivo.nombre} (nueva pestaña)`);
                        const icono = document.createElement('i');
                        icono.className = 'far fa-eye';
                        icono.setAttribute('aria-hidden', 'true');
                        enlace.append(icono, document.createTextNode(' Ver PDF'));
                    } else enlace.textContent = 'No disponible';
                    fila.append(enlace);
                    resultado.append(fila);
                });
            } catch (error) {
                // Un error de una selección anterior tampoco debe reemplazar la vista actual.
                if (actual === revision) resultado.textContent = error.message;
            } finally {
                // Permite consultar nuevamente, tanto si terminó bien como si ocurrió un error.
                boton.disabled = false;
            }
        }));
    });
});
