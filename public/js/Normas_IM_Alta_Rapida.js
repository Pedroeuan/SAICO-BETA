  (function (window, document) {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var nombreSelect = document.getElementById('normaIMNombre');
        var url = window.normasIMAltaRapidaUrl;
        var modal;
        var cuerpo;
        var botonGuardar;
        var mensaje;
        var elementosIniciales = [
            'C', 'Mn', 'P', 'S', 'Si', 'Cu', 'Ni', 'Cr', 'Mo', 'V', 'Al',
            'Co', 'Nb', 'Ti', 'W', 'Pb', 'Sn', 'Mg', 'As', 'Zr', 'B', 'Fe'
        ];

        if (!nombreSelect || !url || document.getElementById('abrirAltaRapidaNormaIM')) {
            return;
        }

        var botonAbrir = document.createElement('button');
        botonAbrir.type = 'button';
        botonAbrir.id = 'abrirAltaRapidaNormaIM';
        botonAbrir.className = 'btn btn-outline-success btn-sm mt-2';
        botonAbrir.textContent = '+ Crear nueva norma/especificación';
        nombreSelect.insertAdjacentElement('afterend', botonAbrir);

        modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'modalAltaRapidaNormaIM';
        modal.tabIndex = -1;
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-hidden', 'true');
        modal.innerHTML =
            '<div class="modal-dialog modal-xl" role="document">' +
                '<div class="modal-content">' +
                    '<div class="modal-header bg-primary text-white">' +
                        '<h5 class="modal-title">Crear norma o especificación</h5>' +
                        '<button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>' +
                    '</div>' +
                    '<div class="modal-body">' +
                        '<div id="mensajeAltaRapidaNormaIM" class="alert alert-danger d-none"></div>' +
                        '<div class="row">' +
                            '<div class="col-md-6 form-group"><label for="altaNormaNombre">Nombre/Especificación</label><input id="altaNormaNombre" type="text" class="form-control" maxlength="255" placeholder="Ejemplo: ASTM A105"></div>' +
                            '<div class="col-md-6 form-group"><label for="altaNormaVariable">Variable/Subtítulo</label><input id="altaNormaVariable" type="text" class="form-control" maxlength="255" placeholder="Ejemplo: Grado o intervalo"></div>' +
                        '</div>' +
                        '<div class="table-responsive"><table class="table table-bordered table-sm" id="tablaAltaRapidaNormaIM">' +
                            '<thead class="thead-light"><tr><th style="width:35%">Elemento químico</th><th>Composición química teórica</th><th style="width:70px">Quitar</th></tr></thead><tbody></tbody>' +
                        '</table></div>' +
                        '<button type="button" class="btn btn-outline-primary btn-sm" id="agregarFilaAltaNormaIM">Agregar elemento</button>' +
                        '<div class="form-group mt-3"><label for="altaNormaObservaciones">Observaciones</label><textarea id="altaNormaObservaciones" class="form-control" rows="3" maxlength="5000"></textarea></div>' +
                    '</div>' +
                    '<div class="modal-footer">' +
                        '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>' +
                        '<button type="button" class="btn btn-primary" id="guardarAltaRapidaNormaIM">Guardar y seleccionar</button>' +
                    '</div>' +
                '</div>' +
            '</div>';
        document.body.appendChild(modal);

        cuerpo = modal.querySelector('tbody');
        botonGuardar = modal.querySelector('#guardarAltaRapidaNormaIM');
        mensaje = modal.querySelector('#mensajeAltaRapidaNormaIM');

        function agregarFila(elemento, composicion) {
            var fila = document.createElement('tr');
            fila.innerHTML =
                '<td><input type="text" class="form-control form-control-sm alta-norma-elemento" maxlength="100"></td>' +
                '<td><input type="text" class="form-control form-control-sm alta-norma-composicion" maxlength="255"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-danger btn-sm quitar-fila-alta-norma" aria-label="Quitar">&times;</button></td>';
            fila.querySelector('.alta-norma-elemento').value = elemento || '';
            fila.querySelector('.alta-norma-composicion').value = composicion || '';
            cuerpo.appendChild(fila);
        }

        function mostrarModal() {
            mensaje.classList.add('d-none');
            mensaje.textContent = '';
            modal.querySelector('#altaNormaNombre').value = '';
            modal.querySelector('#altaNormaVariable').value = '';
            modal.querySelector('#altaNormaObservaciones').value = '';
            cuerpo.innerHTML = '';
            elementosIniciales.forEach(function (elemento) { agregarFila(elemento, ''); });
            if (window.jQuery && window.jQuery.fn.modal) {
                window.jQuery(modal).modal('show');
            } else if (window.bootstrap && window.bootstrap.Modal) {
                window.bootstrap.Modal.getOrCreateInstance(modal).show();
            } else {
                modal.style.display = 'block';
                modal.classList.add('show');
                modal.removeAttribute('aria-hidden');
            }
        }

        function cerrarModal() {
            if (window.jQuery && window.jQuery.fn.modal) {
                window.jQuery(modal).modal('hide');
            } else if (window.bootstrap && window.bootstrap.Modal) {
                window.bootstrap.Modal.getOrCreateInstance(modal).hide();
            } else {
                modal.style.display = 'none';
                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');
            }
        }

        function mostrarError(texto) {
            mensaje.textContent = texto;
            mensaje.classList.remove('d-none');
            mensaje.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        botonAbrir.addEventListener('click', mostrarModal);
        modal.querySelectorAll('[data-dismiss="modal"]').forEach(function (boton) {
            boton.addEventListener('click', cerrarModal);
        });
        modal.querySelector('#agregarFilaAltaNormaIM').addEventListener('click', function () {
            agregarFila('', '');
        });
        cuerpo.addEventListener('click', function (evento) {
            if (evento.target.closest('.quitar-fila-alta-norma')) {
                evento.target.closest('tr').remove();
            }
        });

        botonGuardar.addEventListener('click', async function () {
            var nombre = modal.querySelector('#altaNormaNombre').value.trim();
            var filas = Array.prototype.slice.call(cuerpo.querySelectorAll('tr'))
                .map(function (fila) {
                    return {
                        elemento: fila.querySelector('.alta-norma-elemento').value.trim(),
                        composicion: fila.querySelector('.alta-norma-composicion').value.trim()
                    };
                })
                .filter(function (fila) { return fila.elemento !== ''; });

            if (!nombre) {
                mostrarError('Capture el nombre o especificación de la norma.');
                return;
            }
            if (!filas.length) {
                mostrarError('Agregue al menos un elemento químico.');
                return;
            }

            botonGuardar.disabled = true;
            mensaje.classList.add('d-none');

            try {
                var token = document.querySelector('meta[name="csrf-token"]')?.content
                    || document.querySelector('input[name="_token"]')?.value;
                var respuesta = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token || ''
                    },
                    body: JSON.stringify({
                        NombreESP: nombre,
                        Variable: modal.querySelector('#altaNormaVariable').value.trim(),
                        Elemento: filas.map(function (fila) { return fila.elemento; }),
                        Composicion: filas.map(function (fila) { return fila.composicion; }),
                        Observaciones: modal.querySelector('#altaNormaObservaciones').value.trim()
                    })
                });
                var resultado = await respuesta.json();
                if (!respuesta.ok) {
                    var primerError = resultado.errors
                        ? Object.values(resultado.errors).reduce(function (todos, errores) { return todos.concat(errores); }, [])[0]
                        : null;
                    throw new Error(primerError || resultado.message || 'No fue posible guardar la norma.');
                }

                document.dispatchEvent(new CustomEvent('norma-im:creada', {
                    detail: resultado.norma
                }));
                cerrarModal();

                if (window.Swal) {
                    window.Swal.fire({
                        icon: 'success',
                        title: resultado.existente ? 'Norma existente' : 'Norma creada',
                        text: resultado.message,
                        timer: 1800,
                        showConfirmButton: false
                    });
                }
            } catch (error) {
                mostrarError(error.message || 'No fue posible guardar la norma.');
            } finally {
                botonGuardar.disabled = false;
            }
        });
    });
}(window, document));
