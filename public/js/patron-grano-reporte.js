(function (window, document) {
    'use strict';

    /** Escapa contenido antes de construir las opciones del catálogo dentro de una tarjeta. */
    function escapar(valor) {
        return String(valor == null ? '' : valor)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /** Inicializa el único catálogo del reporte y lo ofrece como modo para cada fotografía. */
    function iniciar(configuracion) {
        const formulario = configuracion.closest('form');
        if (!formulario || ['FOR-PIMP-03_B_01', 'FOR-PIMP-04_02', 'FOR-PIMP-04_03', 'FOR-PIMP-06_B_01'].indexOf(formulario.id) === -1) return;

        const raizFotos = formulario.querySelector('[data-layout-fotos-manual="1"]');
        const contenedorDinamico = formulario.querySelector('#imageFieldsContainer');
        const activoInput = configuracion.querySelector('[data-grain-pattern-active]');
        const idInput = configuracion.querySelector('[data-grain-pattern-id]');
        const descripcionInput = configuracion.querySelector('[data-grain-pattern-description]');
        const usarActualInput = configuracion.querySelector('[data-grain-pattern-use-current]');
        const paginaInput = configuracion.querySelector('[data-grain-pattern-page]');
        const posicionInput = configuracion.querySelector('[data-grain-pattern-position]');
        let catalogo = [];
        let historico = {};
        let tarjetaActiva = null;

        try {
            catalogo = JSON.parse(configuracion.querySelector('[data-grain-pattern-catalog]')?.textContent || '[]');
            historico = JSON.parse(configuracion.querySelector('[data-grain-pattern-historical]')?.textContent || '{}');
        } catch (error) {
            catalogo = [];
            historico = {};
        }

        /** Crea el selector y la vista previa que sustituyen la carga normal de una tarjeta. */
        function crearPanelGrano(tarjeta) {
            if (tarjeta.querySelector('[data-grain-card-panel]')) return;
            const idHistorico = String(historico.id || '');
            const historicoFueraCatalogo = idHistorico && !catalogo.some(function (item) {
                return String(item.id) === idHistorico;
            });
            const panel = document.createElement('div');
            panel.className = 'border rounded p-2 mt-2 d-none';
            panel.setAttribute('data-grain-card-panel', '1');
            panel.innerHTML =
                '<label class="font-weight-bold">Seleccionar tamaño de grano</label>' +
                '<select class="form-control" data-grain-card-select>' +
                    '<option value="">Seleccione un grano</option>' +
                    catalogo.map(function (item) {
                        return '<option value="' + escapar(item.id) + '">' + escapar(item.nombre) + '</option>';
                    }).join('') +
                    (historicoFueraCatalogo
                        ? '<option value="' + escapar(idHistorico) + '">' +
                            escapar((historico.nombre || 'Tamaño de grano histórico') + ' (guardado)') +
                          '</option>'
                        : '') +
                '</select>' +
                '<div class="border rounded p-2 mt-2 text-center" data-grain-card-preview-wrap>' +
                    '<strong class="d-block mb-2" data-grain-card-name></strong>' +
                    '<img class="img-fluid img-thumbnail d-none" style="max-height:420px" ' +
                        'data-grain-card-preview alt="Tamaño de grano seleccionado">' +
                    '<div class="text-muted" data-grain-card-empty>Seleccione un grano para mostrar la imagen comparativa.</div>' +
                '</div>';

            const vistaNormal = tarjeta.querySelector('.image-preview');
            const comentario = tarjeta.querySelector('textarea[name^="comments"], [data-grain-card-description]');
            if (vistaNormal?.parentNode) vistaNormal.parentNode.insertBefore(panel, vistaNormal);
            else if (comentario?.parentNode) comentario.parentNode.insertBefore(panel, comentario);
            else tarjeta.querySelector('.form-group')?.appendChild(panel);

            panel.querySelector('[data-grain-card-select]').addEventListener('change', function () {
                // Cambiar el select significa que el técnico eligió conscientemente la versión actual del catálogo.
                usarActualInput.value = this.value ? '1' : '0';
                idInput.value = this.value;
                mostrarSeleccion(tarjeta, false);
                // El cuadro de resultados de Fiji actualiza el tamaño sin esperar a guardar el reporte.
                document.dispatchEvent(new CustomEvent('saico:grain-pattern-updated'));
            });
        }

        /** Agrega a una fotografía el único check solicitado por los técnicos. */
        function prepararTarjeta(tarjeta) {
            if (!tarjeta || tarjeta.dataset.grainPrepared === '1') return;
            tarjeta.dataset.grainPrepared = '1';
            const grupo = tarjeta.querySelector('.form-group');
            if (!grupo) return;

            const checkWrap = document.createElement('div');
            const identificador = (tarjeta.id || ('grano-' + Date.now())).replace(/[^a-zA-Z0-9_-]/g, '');
            checkWrap.className = 'form-check mt-2';
            checkWrap.innerHTML =
                '<input type="checkbox" class="form-check-input foto-grain-checkbox" id="tamanoGrano_' + identificador + '">' +
                '<label class="form-check-label font-weight-bold" for="tamanoGrano_' + identificador + '">' +
                    'Agregar tamaño de grano' +
                '</label>';

            const archivo = tarjeta.querySelector('input[type="file"]');
            grupo.insertBefore(checkWrap, archivo || grupo.firstChild);
            crearPanelGrano(tarjeta);

            // El mismo textarea de la fotografía funciona como descripción, pero se rotula claramente al activar grano.
            const descripcion = tarjeta.querySelector('textarea[name^="comments"]');
            if (descripcion && !tarjeta.querySelector('[data-grain-description-label]')) {
                const etiquetaDescripcion = document.createElement('label');
                etiquetaDescripcion.className = 'font-weight-bold mt-2 d-none';
                etiquetaDescripcion.textContent = 'Descripción para este reporte';
                etiquetaDescripcion.setAttribute('data-grain-description-label', '1');
                descripcion.parentNode.insertBefore(etiquetaDescripcion, descripcion);
            }

            checkWrap.querySelector('.foto-grain-checkbox').addEventListener('change', function () {
                if (this.checked) activarTarjeta(tarjeta, false);
                else desactivarTarjeta(tarjeta, true);
            });
        }

        /** Evita convertir accidentalmente una fotografía ya capturada en patrón de grano. */
        function tarjetaContieneFoto(tarjeta) {
            const base64 = tarjeta.querySelector('input[name^="images_base64"]');
            const existente = tarjeta.querySelector('input[name^="existing_images"]');
            const archivo = tarjeta.querySelector('input[type="file"]');
            return Boolean(base64?.value || existente?.value || archivo?.files?.length);
        }

        /** Pinta el patrón actual usando la copia histórica o el catálogo vigente. */
        function mostrarSeleccion(tarjeta, permitirHistorico) {
            const panel = tarjeta.querySelector('[data-grain-card-panel]');
            if (!panel) return;
            const select = panel.querySelector('[data-grain-card-select]');
            const item = catalogo.find(function (patron) { return String(patron.id) === String(select.value); });
            const usarHistorico = permitirHistorico
                && String(historico.id || '') === String(select.value)
                && historico.url_imagen;
            const url = usarHistorico ? historico.url_imagen : (item?.url_imagen || '');
            const nombre = usarHistorico ? historico.nombre : (item?.nombre || '');
            const imagen = panel.querySelector('[data-grain-card-preview]');
            const vacio = panel.querySelector('[data-grain-card-empty]');

            panel.querySelector('[data-grain-card-name]').textContent = nombre || '';
            imagen.src = url || '';
            imagen.classList.toggle('d-none', !url);
            vacio.classList.toggle('d-none', Boolean(url));
        }

        /** Sincroniza la página y el cuadrante de la tarjeta con los campos únicos enviados al servidor. */
        function sincronizarLayout(tarjeta) {
            if (!tarjeta || tarjeta !== tarjetaActiva) return;
            const pagina = tarjeta.querySelector('.foto-pagina, [data-grain-history-page]');
            const posicion = tarjeta.querySelector('input[data-foto-posicion]:checked, input[data-grain-history-position]:checked');
            if (pagina) paginaInput.value = Math.max(1, parseInt(pagina.value, 10) || 1);
            if (posicion) posicionInput.value = posicion.value;
        }

        /** Cambia visualmente una tarjeta normal al modo tamaño de grano. */
        function actualizarModoTarjeta(tarjeta, activo) {
            const panel = tarjeta.querySelector('[data-grain-card-panel]');
            const archivo = tarjeta.querySelector('input[type="file"]');
            const vistaNormal = tarjeta.querySelector('.image-preview');
            const comentario = tarjeta.querySelector('textarea[name^="comments"], [data-grain-card-description]');
            const etiquetaDescripcion = tarjeta.querySelector('[data-grain-description-label]');
            const texto = tarjeta.querySelector('.foto-texto-checkbox');
            const disparo = tarjeta.querySelector('.foto-disparo-checkbox');
            const etiquetaArchivo = archivo?.id ? tarjeta.querySelector('label[for="' + archivo.id + '"]') : null;

            tarjeta.dataset.grainMode = activo ? '1' : '0';
            panel?.classList.toggle('d-none', !activo);
            if (archivo) {
                archivo.disabled = activo;
                archivo.classList.toggle('d-none', activo);
            }
            if (etiquetaArchivo) etiquetaArchivo.classList.toggle('d-none', activo);
            if (vistaNormal) vistaNormal.classList.toggle('d-none', activo);
            if (comentario) {
                comentario.placeholder = activo
                    ? 'Descripción del tamaño de grano para este reporte'
                    : 'Comentario';
                comentario.rows = activo ? 4 : 3;
                comentario.required = activo;
            }
            if (etiquetaDescripcion) etiquetaDescripcion.classList.toggle('d-none', !activo);

            // Tamaño de grano, cuadro de texto y disparo son tres modos excluyentes de la misma tarjeta.
            [texto, disparo].forEach(function (check) {
                if (!check) return;
                if (activo && check.checked) {
                    check.checked = false;
                    check.dispatchEvent(new Event('change', { bubbles: true }));
                }
                check.disabled = activo;
            });
        }

        /** Activa una sola tarjeta y desactiva cualquier selección anterior. */
        function activarTarjeta(tarjeta, desdeHistorico) {
            const check = tarjeta.querySelector('.foto-grain-checkbox');
            if (!desdeHistorico && tarjetaContieneFoto(tarjeta)) {
                if (check) check.checked = false;
                window.alert('Esta tarjeta ya contiene una fotografía. Seleccione otra tarjeta vacía para agregar el tamaño de grano.');
                return;
            }

            formulario.querySelectorAll('.foto-grain-checkbox:checked').forEach(function (otro) {
                const otraTarjeta = otro.closest('[data-grain-card], [id^="image-container-"]');
                if (otraTarjeta && otraTarjeta !== tarjeta) {
                    otro.checked = false;
                    // También retira el required del selector anterior para no bloquear el envío ocultamente.
                    desactivarTarjeta(otraTarjeta, false);
                }
            });

            tarjetaActiva = tarjeta;
            activoInput.value = '1';
            actualizarModoTarjeta(tarjeta, true);
            const select = tarjeta.querySelector('[data-grain-card-select]');
            if (desdeHistorico) {
                select.value = idInput.value;
                const comentario = tarjeta.querySelector('textarea[name^="comments"], [data-grain-card-description]');
                if (comentario) comentario.value = descripcionInput.value;
            } else {
                idInput.value = select.value || '';
            }
            select.required = true;
            mostrarSeleccion(tarjeta, desdeHistorico && usarActualInput.value !== '1');
            sincronizarLayout(tarjeta);
            document.dispatchEvent(new CustomEvent('saico:report-layout-updated'));
        }

        /** Retira el modo grano sin borrar las fotografías reales de otras tarjetas. */
        function desactivarTarjeta(tarjeta, limpiarGlobal) {
            actualizarModoTarjeta(tarjeta, false);
            const select = tarjeta.querySelector('[data-grain-card-select]');
            if (select) select.required = false;
            if (tarjetaActiva === tarjeta) tarjetaActiva = null;
            if (limpiarGlobal) {
                activoInput.value = '0';
                idInput.value = '';
                descripcionInput.value = '';
                usarActualInput.value = '0';
            }
            document.dispatchEvent(new CustomEvent('saico:report-layout-updated'));
        }

        /** Construye en Edit una tarjeta compacta para el patrón que ya pertenece al histórico. */
        function crearTarjetaHistorica() {
            if (activoInput.value !== '1' || !idInput.value || !raizFotos) return;
            const fila = document.createElement('div');
            const pagina = Math.max(1, parseInt(paginaInput.value, 10) || 1);
            const posicion = posicionInput.value || 'abajo_izquierda';
            const opcionesPosicion = [
                ['arriba_izquierda', 'Arriba izquierda'],
                ['arriba_derecha', 'Arriba derecha'],
                ['abajo_izquierda', 'Abajo izquierda'],
                ['abajo_derecha', 'Abajo derecha'],
                ['pagina_completa', 'Página completa']
            ];
            fila.className = 'row grain-history-row';
            fila.innerHTML =
                '<div class="col-sm-6" data-grain-card data-grain-history-card>' +
                    '<div class="form-group border rounded p-2">' +
                        '<label><strong>Imagen de tamaño de grano guardada:</strong></label>' +
                        '<div class="form-check mt-2">' +
                            '<input type="checkbox" class="form-check-input foto-grain-checkbox" id="tamanoGranoHistorico" checked>' +
                            '<label class="form-check-label font-weight-bold" for="tamanoGranoHistorico">Agregar tamaño de grano</label>' +
                        '</div>' +
                        '<div class="foto-layout-manual border rounded p-2 mt-2 bg-light">' +
                            '<div class="row"><div class="col-md-3">' +
                                '<label class="font-weight-bold">Número de hoja</label>' +
                                '<input type="number" min="1" class="form-control form-control-sm foto-pagina" data-grain-history-page value="' + pagina + '">' +
                            '</div><div class="col-md-9"><div class="font-weight-bold">Posición en la hoja</div>' +
                                opcionesPosicion.map(function (item) {
                                    return '<label class="form-check form-check-inline"><input class="form-check-input" type="radio" ' +
                                        'name="patron_grano_posicion_historico" data-grain-history-position value="' + item[0] + '" ' +
                                        (item[0] === posicion ? 'checked' : '') + '>' +
                                        '<span class="form-check-label">' + item[1] + '</span></label>';
                                }).join('') +
                            '</div></div>' +
                            '<div class="form-check mt-2"><input class="form-check-input" type="checkbox" disabled>' +
                                '<span class="form-check-label font-weight-bold">Usar este espacio como cuadro de texto</span></div>' +
                        '</div>' +
                        '<label class="font-weight-bold mt-2" data-grain-description-label>Descripción para este reporte</label>' +
                        '<textarea class="form-control mt-2" data-grain-card-description ' +
                            'placeholder="Descripción del tamaño de grano para este reporte"></textarea>' +
                    '</div>' +
                '</div>';

            const referencia = contenedorDinamico && contenedorDinamico.parentNode === raizFotos
                ? contenedorDinamico
                : raizFotos.firstChild;
            raizFotos.insertBefore(fila, referencia);
            const tarjeta = fila.querySelector('[data-grain-card]');
            crearPanelGrano(tarjeta);
            const check = tarjeta.querySelector('.foto-grain-checkbox');
            check.addEventListener('change', function () {
                if (this.checked) activarTarjeta(tarjeta, true);
                else {
                    desactivarTarjeta(tarjeta, true);
                    fila.remove();
                }
            });
            activarTarjeta(tarjeta, true);
        }

        /** Recorre tarjetas nuevas y vuelve a aplicar el modo si el layout apareció después del checkbox. */
        function actualizarTarjetas() {
            formulario.querySelectorAll('[id^="image-container-"]').forEach(prepararTarjeta);
            if (tarjetaActiva && !tarjetaActiva.isConnected) desactivarTarjeta(tarjetaActiva, true);
            if (tarjetaActiva) actualizarModoTarjeta(tarjetaActiva, true);
        }

        // El comentario visible funciona como descripción y los radios de la tarjeta como layout del patrón.
        formulario.addEventListener('input', function (evento) {
            if (!tarjetaActiva || !tarjetaActiva.contains(evento.target)) return;
            if (evento.target.matches('textarea[name^="comments"], [data-grain-card-description]')) {
                descripcionInput.value = evento.target.value;
            }
            sincronizarLayout(tarjetaActiva);
        });
        formulario.addEventListener('change', function (evento) {
            if (tarjetaActiva && tarjetaActiva.contains(evento.target)) sincronizarLayout(tarjetaActiva);
        });

        actualizarTarjetas();
        crearTarjetaHistorica();
        const observador = new MutationObserver(actualizarTarjetas);
        observador.observe(raizFotos || formulario, { childList: true, subtree: true });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-grain-pattern-config]').forEach(iniciar);
    });
}(window, document));
