
let table = new DataTable('#tablaJs', {
    // options
    language: {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                }
});

function actualizarTabla() {
    $.ajax({
        url: '/obtenerDatos/Actualizados', // Ruta que devuelve los datos actualizados
        method: 'GET',
        success: function(response) {
            // Destruye la instancia actual de DataTables si existe
            if ($.fn.DataTable.isDataTable('#tablaJs')) {
                $('#tablaJs').DataTable().clear().destroy();
            }

            // Limpiar la tabla actual
            $('#tablaJs tbody').empty();

            // Iterar sobre los datos en la respuesta y agregar filas a la tabla
            response.forEach(function(item) {
                var disponibilidad = '';
                switch(item.Disponibilidad_Estado) {
                    case 'DISPONIBLE':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-success">Disponible<i class="fa fa-check" aria-hidden="true"></i></button>';
                        break;
                    case 'Equipo Disponible':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-success">Equipo Disponible<i class="fa fa-check" aria-hidden="true"></i></button>';
                        break;
                    case 'NO DISPONIBLE':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-warning">No Disponible<i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>';
                        break;
                    case 'Equipo Fuera de Servicio':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-warning">Equipo Fuera de Servicio<i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>';
                        break;
                    case 'FUERA DE SERVICIO/BAJA':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-danger">Fuera de servicio<i class="fa fa-ban" aria-hidden="true"></i></button>';
                        break;
                    case 'Equipo en Resguardo':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-danger">Equipo en Resguardo<i class="fa fa-ban" aria-hidden="true"></i></button>';
                        break;
                    case 'En Servicio':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-warning" style="color:#ff8800; border:1 px;">Espera de Dato<i class="far fa-clock" aria-hidden="true"></i></button>';
                        break;
                    case 'ESPERA DE DATO':
                        disponibilidad = '<button type="button" class="btn btn-block btn-outline-info">Espera de Dato<i class="far fa-clock" aria-hidden="true"></i></button>';
                        break;
                }

                var hojaPresentacion = item.Foto != 'ESPERA DE DATO'
                    ? '<a class="btn btn-primary" href="/storage/' + item.Foto + '" role="button" target="_blank"><i class="fa fa-eye"></i></a>'
                    : '<a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>';

                var fechaCalibracion = item.certificados && item.certificados.Fecha_calibracion === '2001-01-01'
                    ? 'SIN FECHA ASIGNADA'
                    : (item.certificados ? item.certificados.Fecha_calibracion : 'N/A');

                var row = '<tr data-id="' + item.idGeneral_EyC + '">' +
                        '<td>' + item.Nombre_E_P_BP + '</td>' +
                        '<td>' + item.No_economico + '</td>' +
                        '<td>' + item.Marca + '</td>' +
                        '<td>' + item.Modelo + '</td>' +
                        '<td>' + item.Serie + '</td>' +
                        '<td>' + (item.almacen ? item.almacen.Stock : 'N/A') + '</td>' +
                        '<td>' + disponibilidad + '</td>' +
                        '<td>' + fechaCalibracion + '</td>' +
                        '<td>' + hojaPresentacion + '</td>' +
                        '<td><button type="button" class="btn btn-success btnAgregar" data-id="' + item.idGeneral_EyC + '"><i class="fas fa-plus-circle" aria-hidden="true"></i></button></td>' +
                        '</tr>';

                $('#tablaJs tbody').append(row);
            });

            // Re-inicializa DataTables después de agregar los nuevos datos
            $('#tablaJs').DataTable({
                "pageLength": 10, // Configura la cantidad de filas por página
                "language": {
                    "decimal": "",
                    "emptyTable": "No hay datos disponibles en la tabla",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
                    "infoFiltered": "(filtrado de _MAX_ entradas totales)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros coincidentes",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                    "aria": {
                        "sortAscending": ": activar para ordenar la columna ascendente",
                        "sortDescending": ": activar para ordenar la columna descendente"
                    }
                }
            });

        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los datos:', error);
        }
    });
}
    $(document).ready(function() {
            // Actualizar la tabla cada 10 segundos (10000 milisegundos)
            //setInterval(actualizarTabla, 60000);

            // Llamar a la función una vez al cargar la página
            actualizarTabla();

            // Añade el evento de clic al botón para actualizar manualmente la tabla
            $('#actualizarTablaBtn').on('click', function() {
                actualizarTabla();
            });
        });
    
        function consultarCantidadAlmacen(id, callback) {
            $.ajax({
                url: '/Obtener/CantidadAlmacen/' + id,
                method: 'GET',
                success: function(data) {
                    //callback(null, data.Cantidad); // Asume que la respuesta contiene un campo "Cantidad"
                    //callback(null, data.Unidad); // Asume que la respuesta contiene un campo "Unidad"
                    const cantidad = data.Cantidad || 0;
                    const unidad = data.Unidad || ''; 
                    callback(null, cantidad, unidad); // 👈 Enviamos los 2 valores en una sola llamada
                },
                error: function(error) {
                    callback(error);
                }
            });
        }

$(document).on('click', '.btnAgregar', function() {
    let button = $(this);
    let row = $(this).closest('tr');
    let id = $(this).data('id');
    let nombreElemento = row.find('td').eq(0).text(); // Asume que el nombre del elemento está en la primera columna

    // Deshabilitar el botón
    button.prop('disabled', true);

    // Verificar si el elemento ya está en la tabla de seleccionados
    if ($(`#tablaSeleccionados tr[data-id='${id}']`).length) {
        Swal.fire({
            icon: 'warning',
            title: 'Elemento Duplicado',
            text: `El elemento "${nombreElemento}" ya ha sido agregado.`,
            confirmButtonText: 'Entendido'
        });
        button.prop('disabled', false); // Habilitar el botón
        return; // Si ya está, no hacemos nada
    }

    // Consultar la cantidad en el almacén antes de agregar la fila
    consultarCantidadAlmacen(id, function(error, cantidad, unidad) {
        if (error) {
            alert('Error al obtener cantidad de almacén.');
            button.prop('disabled', false); // Habilitar el botón en caso de error
            return;
        }

        // Clonar la fila y agregar campos de cantidad y unidad
        let cantidadInput = (cantidad === 1) ?
            `<input type="number" class="form-control cantidad" name="cantidad_${id}" value="1" readonly>` :
            `<input type="number" class="form-control cantidad" name="cantidad_${id}" value="1" min="1" max="${cantidad}" required>`;

        let newRow = `
            <tr data-id="${id}">
                <td>${row.find('td').eq(0).text()}</td>
                <td>${row.find('td').eq(1).text()}</td>
                <td>${row.find('td').eq(2).text()}</td>
                <td>${row.find('td').eq(7).text()}</td>
                <td>${cantidadInput}</td>
                <td><input type="text" class="form-control unidad" name="unidad_${id}" value="${unidad}" required></td>
                <td><button type="button" class="btn btn-danger btnEliminar" data-id="${id}"><i class="fas fa-minus-circle" aria-hidden="true"></i></button></td>
            </tr>
        `;

        // Agregar la nueva fila a la tabla de seleccionados
        $('#tablaSeleccionados tbody').append(newRow);


        // Validar la cantidad ingresada para que no exceda el máximo permitido
        $(`input[name="cantidad_${id}"]`).on('input', function() {
            let inputVal = parseInt($(this).val());
            if (inputVal > cantidad) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cantidad excedida',
                    text: `La cantidad máxima permitida es ${cantidad}.`,
                    confirmButtonText: 'Entendido'
                });
                $(this).val(cantidad);
            }
        });

        // Mostrar una alerta de éxito
        Swal.fire({
            icon: 'success',
            title: 'Elemento Agregado',
            text: `El elemento "${nombreElemento}" ha sido agregado exitosamente.`,
            showConfirmButton: true,
            timer: 2000
        });
                // Habilitar el botón después de agregar el elemento
                button.prop('disabled', false);
    });
});

$(document).ready(function() {
    $('#btnFinalizarkit').click(function(event) {
        // Verificar si hay filas en la tabla
        if ($('#tablaSeleccionados tbody tr').length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tabla vacía',
                text: 'Debes agregar al menos un elemento antes de finalizar la solicitud.',
                confirmButtonText: 'Entendido'
            });
            event.preventDefault(); // Prevenir el envío del formulario
        } else {
            // Si hay elementos en la tabla, puedes continuar con el envío del formulario
            // Si usas un formulario real, aquí podrías hacer el submit
        }
    });
});

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('#tablaSeleccionados').addEventListener('click', function(event) {
            if (event.target.classList.contains('btnEliminar') || event.target.closest('.btnEliminar')) {
                let button = event.target.closest('.btnEliminar');
                let row = button.closest('tr');
                let nombreElemento = row.querySelector('td').textContent; // Asume que el nombre del elemento está en la primera celda (primer <td>)

                // Mostrar una alerta de confirmación antes de eliminar
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `¿Deseas eliminar el elemento "${nombreElemento}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Eliminar la fila de la tabla
                        row.remove();

                        // Mostrar una alerta de éxito después de eliminar
                        Swal.fire({
                            icon: 'success',
                            title: 'Elemento Eliminado',
                            text: `El elemento "${nombreElemento}" ha sido eliminado exitosamente.`,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        // Mostrar una alerta de cancelación si el usuario decide no eliminar
                        Swal.fire({
                            icon: 'error',
                            title: 'Cancelado',
                            text: `El elemento "${nombreElemento}" no ha sido eliminado.`,
                            confirmButtonText: 'Entendido'
                        });
                    }
                });
            }
        });
    });

// Manejar el envío del formulario
document.querySelector('#kitForm').addEventListener('submit', function(event) {
    let selectedRows = document.querySelectorAll('#tablaSeleccionados tbody tr');
    let kitData = [];

    selectedRows.forEach(function(row) {
        let id = row.dataset.id;
        let cantidad = row.querySelector('.cantidad').value;
        let unidad = row.querySelector('.unidad').value;

        kitData.push({
            idGeneral_EyC: id,
            cantidad: cantidad,
            unidad: unidad
        });

        // Crear inputs ocultos para enviar los datos de cantidad y unidad
        let inputCantidad = document.createElement('input');
        inputCantidad.type = 'hidden';
        inputCantidad.name = `kitData[${id}][cantidad]`;
        inputCantidad.value = cantidad;
        document.querySelector('#kitForm').appendChild(inputCantidad);

        let inputUnidad = document.createElement('input');
        inputUnidad.type = 'hidden';
        inputUnidad.name = `kitData[${id}][unidad]`;
        inputUnidad.value = unidad;
        document.querySelector('#kitForm').appendChild(inputUnidad);
    });

    // Añadir los datos al formulario como campos ocultos
    kitData.forEach(function(item) {
        let inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = `kitData[${item.idGeneral_EyC}][idGeneral_EyC]`;
        inputId.value = item.idGeneral_EyC;
        document.querySelector('#kitForm').appendChild(inputId);
    });
});

document.querySelector('#kitForm').addEventListener('submit', function(event) {
    // Verifica si el botón que activó el submit es "Guardar y continuar"
    if (event.submitter && event.submitter.dataset.submitType === 'guardar-continuar') {
        if (!validateForm()) {
            event.preventDefault(); // Evitar que el formulario se envíe si no pasa la validación
        }
    }
});

function validateForm() {
    let form = document.getElementById('kitForm');
    let nombre = form.querySelector('[name="Nombre"]').value;
    let prueba = form.querySelector('[name="Prueba"]').value;

    let selectedRows = document.querySelectorAll('#tablaSeleccionados tbody tr');
    let camposVacios = [];

    if (!nombre) {
        camposVacios.push('Nombre');
    }
    if (!prueba) {
        camposVacios.push('Prueba');
    }

    if (selectedRows.length === 0) {
        camposVacios.push('TABLA VACIA Agregue al menos un elemento a la tabla');
    }

    selectedRows.forEach(function(row) {
        let cantidad = row.querySelector('.cantidad').value;
        let unidad = row.querySelector('.unidad').value;

        if (!cantidad) {
            camposVacios.push('Cantidad');
        }
        if (!unidad) {
            camposVacios.push('Unidad');
        }
    });

    if (camposVacios.length > 0) {
        Swal.fire({
            title: 'Error',
            text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return false;
    }
    return true;
}

// Guardar y continuar Kits
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('kitForm');
    const finalizarButton = form.querySelector('button[type="submit"]');
    const guardarContinuarButton = document.getElementById('guardarContinuarKits');

    form.addEventListener('submit', function(event) {
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;
    });

    guardarContinuarButton.addEventListener('click', function(event) {
        event.preventDefault();
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;

        if (!validateForm()) {
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return; // No continuar si la validación falla
        }

        var formData = new FormData(form);

        // Agregar datos de los kits seleccionados
        let selectedRows = document.querySelectorAll('#tablaSeleccionados tbody tr');
        selectedRows.forEach(function(row, index) {
            let id = row.getAttribute('data-id');
            let cantidad = row.querySelector('.cantidad').value;
            let unidad = row.querySelector('.unidad').value;

            formData.append(`kitData[${index}][idGeneral_EyC]`, id);
            formData.append(`kitData[${index}][cantidad]`, cantidad);
            formData.append(`kitData[${index}][unidad]`, unidad);
        });

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar el formulario
                form.reset();

                // Limpiar la tabla de elementos seleccionados
                document.querySelector('#tablaSeleccionados tbody').innerHTML = '';

                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += ' - ' + xhr.responseJSON.message;
                }
                console.error('Error al enviar formulario:', errorMessage);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });

                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            }
        });
    });
});


/*HERRAMIENTAS*/
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('herramientasForm');
    const finalizarButton = form.querySelector('button[type="submit"]');
    const guardarContinuarButton = document.getElementById('guardarContinuarHerramientas');

    form.addEventListener('submit', function(event) {
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;
    });

    guardarContinuarButton.addEventListener('click', function(event) {
        event.preventDefault();
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;

        var formData = new FormData(form);

        // Validaciones
        var nombre = formData.get('Nombre_E_P_BP');
        var numeroEconomico = formData.get('No_economico');
        var marca = formData.get('Marca');
        var modelo = formData.get('Modelo');
        var serie = formData.get('Serie');
        // Validación de disponibilidad
        var disponibilidad = formData.get('Disponibilidad_Estado');
        var iso = formData.get('ISO');

        var camposVacios = [];
        if (!nombre) camposVacios.push('Nombre');
        if (!numeroEconomico) camposVacios.push('Número Económico');
        if (!marca) camposVacios.push('Marca');
        if (!modelo) camposVacios.push('Modelo');
        if (!serie || serie === '') camposVacios.push('Número de Serie');

        if (camposVacios.length > 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!disponibilidad || disponibilidad === 'Elige un Tipo') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "Disponibilidad / Estatus".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!iso || iso === 'Elige el tipo de ISO') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "9001 / 17025".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }
        // Si la serie es exactamente '---', se envía como null para no validarla
        if (serie === '---') {
            serie = null; // También podrías usar '' si prefieres
        }
        // Validación de duplicados en No_economico y Serie
        $.ajax({
            url: '/verificar-duplicado-Herramientas',
            type: 'POST',
            data: {
                No_economico: numeroEconomico,
                Serie: serie,
                _token: formData.get('_token')
            },
            success: function(response) {
                if (response.duplicado) {
                    Swal.fire({
                        title: 'Error',
                        text: response.mensaje,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    finalizarButton.disabled = false;
                    guardarContinuarButton.disabled = false;
                } else {
                    // Enviar el formulario usando AJAX si no hay duplicados
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Datos guardados',
                                text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                            form.reset();
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al guardar los datos.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al verificar los duplicados.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            }
        });
    });
});

/*BLOCKS*/
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('blocksForm');
    const finalizarButton = form.querySelector('button[type="submit"]');
    const guardarContinuarButton = document.getElementById('guardarContinuarBlocks');

    form.addEventListener('submit', function(event) {
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;
    });

    guardarContinuarButton.addEventListener('click', function(event) {
        event.preventDefault();
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;

        var formData = new FormData(form);

        // Validaciones
        var nombre = formData.get('Nombre_E_P_BP');
        var numeroEconomico = formData.get('No_economico');
        var marca = formData.get('Marca');
        var modelo = formData.get('Modelo');
        var serie = formData.get('Serie');
        // Validación de disponibilidad
        var disponibilidad = formData.get('Disponibilidad_Estado');
        var iso = formData.get('ISO');

        var camposVacios = [];
        if (!nombre) camposVacios.push('Nombre');
        if (!numeroEconomico) camposVacios.push('Número Económico');
        if (!marca) camposVacios.push('Marca');
        if (!modelo) camposVacios.push('Modelo');
        if (!serie || serie === '') camposVacios.push('Número de Serie');

        if (camposVacios.length > 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!disponibilidad || disponibilidad === 'Elige un Tipo') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "Disponibilidad / Estatus".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!iso || iso === 'Elige el tipo de ISO') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "9001 / 17025".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }
        // Si la serie es exactamente '---', se envía como null para no validarla
        if (serie === '---') {
            serie = null; // También podrías usar '' si prefieres
        }
        // Validación de duplicados en No_economico y Serie
        $.ajax({
            url: '/verificar-duplicado-BlockyProbeta',
            type: 'POST',
            data: {
                No_economico: numeroEconomico,
                Serie: serie,
                _token: formData.get('_token')
            },
            success: function(response) {
                if (response.duplicado) {
                    Swal.fire({
                        title: 'Error',
                        text: response.mensaje,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    finalizarButton.disabled = false;
                    guardarContinuarButton.disabled = false;
                } else {
                    // Enviar el formulario usando AJAX si no hay duplicados
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Datos guardados',
                                text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                            form.reset();
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al guardar los datos.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al verificar los duplicados.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            }
        });
    });
});


/*ACCESORIOS*/
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('accesoriosForm');
    const finalizarButton = form.querySelector('button[type="submit"]');
    const guardarContinuarButton = document.getElementById('guardarContinuarAccesorios');

    form.addEventListener('submit', function(event) {
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;
    });

    guardarContinuarButton.addEventListener('click', function(event) {
        event.preventDefault();
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;

        var formData = new FormData(form);

        // Validaciones
        var nombre = formData.get('Nombre_E_P_BP');
        var numeroEconomico = formData.get('No_economico');
        var marca = formData.get('Marca');
        var modelo = formData.get('Modelo');
        var serie = formData.get('Serie');
        // Validación de disponibilidad
        var disponibilidad = formData.get('Disponibilidad_Estado');
        var iso = formData.get('ISO');

        var camposVacios = [];
        if (!nombre) camposVacios.push('Nombre');
        if (!numeroEconomico) camposVacios.push('Número Económico');
        if (!marca) camposVacios.push('Marca');
        if (!modelo) camposVacios.push('Modelo');
        if (!serie) camposVacios.push('Número de Serie');

        if (camposVacios.length > 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!iso || iso === 'Elige el tipo de ISO') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "9001 / 17025".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!disponibilidad || disponibilidad === 'Elige un Tipo') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "Disponibilidad / Estatus".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        // Si la serie es exactamente '---', se envía como null para no validarla
        if (serie === '---') {
            serie = null; // También podrías usar '' si prefieres
        }
        // Validación de duplicados en No_economico y Serie
        $.ajax({
            url: '/verificar-duplicado-Accesorios',
            type: 'POST',
            data: {
                No_economico: numeroEconomico,
                Serie: serie,
                _token: formData.get('_token')
            },
            success: function(response) {
                if (response.duplicado) {
                    Swal.fire({
                        title: 'Error',
                        text: response.mensaje,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    finalizarButton.disabled = false;
                    guardarContinuarButton.disabled = false;
                } else {
                    // Enviar el formulario usando AJAX si no hay duplicados
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Datos guardados',
                                text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });
                            form.reset();
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al guardar los datos.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al verificar los duplicados.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            }
        });
    });
});


/*CONSUMIBLES*/
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('consumiblesForm');
    const finalizarButton = form.querySelector('button[type="submit"]');
    const guardarContinuarButton = document.getElementById('guardarContinuarConsumibles');

    form.addEventListener('submit', function(event) {
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;
    });

    guardarContinuarButton.addEventListener('click', function(event) {
        event.preventDefault();
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;

        var formData = new FormData(form);

        // Validaciones
        var nombre = formData.get('Nombre_E_P_BP');
        var marca = formData.get('Marca');
        var modelo = formData.get('Modelo');
        var stock = formData.get('Stock');
        // Validación de disponibilidad
        var disponibilidad = formData.get('Disponibilidad_Estado');
        var iso = formData.get('ISO');

        var camposVacios = [];
        if (!nombre) camposVacios.push('Nombre');
        if (!marca) camposVacios.push('Marca');
        if (!modelo) camposVacios.push('Modelo');
        if (!stock) camposVacios.push('Stock');

        if (camposVacios.length > 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!disponibilidad || disponibilidad === 'Elige un Tipo') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "Disponibilidad / Estatus".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!iso || iso === 'Elige el tipo de ISO') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "9001 / 17025".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar el formulario
                form.reset();
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            }
        });
    });
});


/*Equipos*/
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('equiposForm');
    const finalizarButton = form.querySelector('button[type="submit"]');
    const guardarContinuarButton = document.getElementById('guardarContinuarEquipos');

    form.addEventListener('submit', function(event) {
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;
    });

    guardarContinuarButton.addEventListener('click', function(event) {
        event.preventDefault();
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;

        var formData = new FormData(form);

        // Validaciones
        var nombre = formData.get('Nombre_E_P_BP');
        var numeroEconomico = formData.get('No_economico');
        var marca = formData.get('Marca');
        var modelo = formData.get('Modelo');
        var serie = formData.get('Serie');
        // Validación de disponibilidad
        var disponibilidad = formData.get('Disponibilidad_Estado');
        var iso = formData.get('ISO');

        var camposVacios = [];
        if (!nombre) camposVacios.push('Nombre');
        if (!numeroEconomico) camposVacios.push('Número Económico');
        if (!marca) camposVacios.push('Marca');
        if (!modelo) camposVacios.push('Modelo');
        if (!serie) camposVacios.push('Número de Serie');

        if (camposVacios.length > 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!disponibilidad || disponibilidad === 'Elige un Tipo') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "Disponibilidad / Estatus".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!iso || iso === 'Elige el tipo de ISO') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "9001 / 17025".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }
        // Si la serie es exactamente '---', se envía como null para no validarla
        if (serie === '---') {
            serie = null; // También podrías usar '' si prefieres
        }
        // Validación de duplicados en No_economico y Serie
        $.ajax({
            url: '/verificar-duplicado-Equipos',
            type: 'POST',
            data: {
                No_economico: numeroEconomico,
                Serie: serie,
                _token: formData.get('_token')
            },
            success: function(response) {
                if (response.duplicado) {
                    Swal.fire({
                        title: 'Error',
                        text: response.mensaje,
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    finalizarButton.disabled = false;
                    guardarContinuarButton.disabled = false;
                } else {
                    // Enviar el formulario usando AJAX si no hay duplicados
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Datos guardados',
                                text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            });

                            // Limpiar el formulario
                            form.reset();
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Ocurrió un error al guardar los datos.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                            finalizarButton.disabled = false;
                            guardarContinuarButton.disabled = false;
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al verificar los duplicados.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            }
        });
    });
});

/*TICS*/
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('TICSForm');
    const finalizarButton = form.querySelector('button[type="submit"]');
    const guardarContinuarButton = document.getElementById('guardarContinuarTICS');

    form.addEventListener('submit', function(event) {
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;
    });

    guardarContinuarButton.addEventListener('click', function(event) {
        event.preventDefault();
        finalizarButton.disabled = true;
        guardarContinuarButton.disabled = true;

        var formData = new FormData(form);

        // Validaciones
        var nombre = formData.get('Nombre_E_P_BP');
        var marca = formData.get('Marca');
        var modelo = formData.get('Modelo');
        var stock = formData.get('Stock');

        // Validación de disponibilidad
        var disponibilidad = formData.get('Disponibilidad_Estado');

        var camposVacios = [];
        if (!nombre) camposVacios.push('Nombre');
        if (!marca) camposVacios.push('Marca');
        if (!modelo) camposVacios.push('Modelo');
        if (!stock) camposVacios.push('Stock');

        if (camposVacios.length > 0) {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, complete los siguientes campos: ' + camposVacios.join(', '),
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        if (!disponibilidad || disponibilidad === 'Elige un Tipo') {
            Swal.fire({
                title: 'Error',
                text: 'Por favor, selecciona una opción válida en "Disponibilidad / Estatus".',
                icon: 'error',
                confirmButtonText: 'Aceptar'
            });
            finalizarButton.disabled = false;
            guardarContinuarButton.disabled = false;
            return;
        }

        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.fire({
                    title: 'Datos guardados',
                    text: 'Datos guardados exitosamente. Puedes continuar ingresando más datos.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                });

                // Limpiar el formulario
                form.reset();
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar los datos.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                finalizarButton.disabled = false;
                guardarContinuarButton.disabled = false;
            }
        });
    });
});


   // Espera a que el documento esté listo
    document.addEventListener("DOMContentLoaded", function() {
        // Obtiene el elemento de pestañas
        var tabs = document.querySelectorAll('.nav-pills .nav-link');

        // Itera sobre cada pestaña
        tabs.forEach(function(tab) {
            // Añade un evento de clic a cada pestaña
            tab.addEventListener('click', function() {
                // Obtiene el id de la pestaña activa
                var activeTab = tab.getAttribute('href');

                // Guarda el id de la pestaña activa en localStorage
                localStorage.setItem('activeTab', activeTab);
            });
        });

        // Obtiene el id de la pestaña activa desde localStorage
        var activeTab = localStorage.getItem('activeTab');

        // Si hay una pestaña activa guardada en localStorage, la muestra
        if (activeTab) {
            var tabLink = document.querySelector('.nav-pills .nav-link[href="' + activeTab + '"]');
            if (tabLink) {
                tabLink.click(); // Activa la pestaña guardada
            }
        }
    });

       // Espera a que el documento esté listo
        document.addEventListener("DOMContentLoaded", function() {
        var tabs = document.querySelectorAll('.nav-pills .nav-link');
        var warningMessage = document.getElementById('tab-warning');

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                var activeTab = tab.getAttribute('href');
                localStorage.setItem('activeTab', activeTab);
                warningMessage.style.display = 'none'; // Oculta el mensaje cuando se selecciona una pestaña
            });
        });

        var activeTab = localStorage.getItem('activeTab');

        if (activeTab) {
            var tabLink = document.querySelector('.nav-pills .nav-link[href="' + activeTab + '"]');
            if (tabLink) {
                tabLink.click(); // Activa la pestaña guardada
                warningMessage.style.display = 'none'; // Oculta el mensaje si hay una pestaña seleccionada
            }
        } else {
            warningMessage.style.display = 'block'; // Muestra el mensaje si no hay ninguna pestaña seleccionada
        }
    });

/*PREVENIR Enters*/
/*Prevenir el Enter Equipos*/
document.getElementById('equiposForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Consumibles*/
document.getElementById('consumiblesForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Accesorios*/
document.getElementById('accesoriosForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Blocks*/
document.getElementById('blocksForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Herramientas*/
document.getElementById('herramientasForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Prevenir el Enter Kits*/
document.getElementById('kitForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

/*RESTAURAR DATOS AL RECARGAR*/
/*TICS */
// Guardar datos en localStorage al escribir
document.querySelectorAll('#TICSForm input, #TICSForm textarea, #TICSForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('TICSForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#TICSForm input, #TICSForm textarea, #TICSForm select').forEach(function(input) {
        let value = localStorage.getItem('TICSForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('TICSForm').addEventListener('submit', function() {
    document.querySelectorAll('#TICSForm input, #TICSForm textarea, #TICSForm select').forEach(function(input) {
        localStorage.removeItem('TICSForm_' + input.name);
    });
});

/*EQUIPOS */
// Guardar datos en localStorage al escribir
document.querySelectorAll('#equiposForm input, #equiposForm textarea, #equiposForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('equiposForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#equiposForm input, #equiposForm textarea, #equiposForm select').forEach(function(input) {
        let value = localStorage.getItem('equiposForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('equiposForm').addEventListener('submit', function() {
    document.querySelectorAll('#equiposForm input, #equiposForm textarea, #equiposForm select').forEach(function(input) {
        localStorage.removeItem('equiposForm_' + input.name);
    });
});

/*CONSUMIBLES */
// Guardar datos en localStorage al escribir
document.querySelectorAll('#consumiblesForm input, #consumiblesForm textarea, #consumiblesForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('consumiblesForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#consumiblesForm input, #consumiblesForm textarea, #consumiblesForm select').forEach(function(input) {
        let value = localStorage.getItem('consumiblesForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('consumiblesForm').addEventListener('submit', function() {
    document.querySelectorAll('#consumiblesForm input, #consumiblesForm textarea, #consumiblesForm select').forEach(function(input) {
        localStorage.removeItem('consumiblesForm_' + input.name);
    });
});

/*ACCESORIOS */
// Guardar datos en localStorage al escribir
document.querySelectorAll('#accesoriosForm input, #accesoriosForm textarea, #accesoriosForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('accesoriosForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#accesoriosForm input, #accesoriosForm textarea, #accesoriosForm select').forEach(function(input) {
        let value = localStorage.getItem('accesoriosForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('accesoriosForm').addEventListener('submit', function() {
    document.querySelectorAll('#accesoriosForm input, #accesoriosForm textarea, #accesoriosForm select').forEach(function(input) {
        localStorage.removeItem('accesoriosForm_' + input.name);
    });
});

/*BLOCKS Y PROBETA */
// Guardar datos en localStorage al escribir
document.querySelectorAll('#blocksForm input, #blocksForm textarea, #blocksForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('blocksForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#blocksForm input, #blocksForm textarea, #blocksForm select').forEach(function(input) {
        let value = localStorage.getItem('blocksForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('blocksForm').addEventListener('submit', function() {
    document.querySelectorAll('#blocksForm input, #blocksForm textarea, #blocksForm select').forEach(function(input) {
        localStorage.removeItem('blocksForm_' + input.name);
    });
});

/*HERRAMIENTAS */
// Guardar datos en localStorage al escribir
document.querySelectorAll('#herramientasForm input, #herramientasForm textarea, #herramientasForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('herramientasForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#herramientasForm input, #herramientasForm textarea, #herramientasForm select').forEach(function(input) {
        let value = localStorage.getItem('herramientasForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('herramientasForm').addEventListener('submit', function() {
    document.querySelectorAll('#herramientasForm input, #herramientasForm textarea, #herramientasForm select').forEach(function(input) {
        localStorage.removeItem('herramientasForm_' + input.name);
    });
});

/*KITS */
// Guardar datos en localStorage al escribir
document.querySelectorAll('#kitForm input, #kitForm textarea, #kitForm select').forEach(function(input) {
    input.addEventListener('input', function() {
        localStorage.setItem('kitForm_' + input.name, input.value);
    });
});
// Restaurar datos al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#kitForm input, #kitForm textarea, #kitForm select').forEach(function(input) {
        let value = localStorage.getItem('kitForm_' + input.name);
        if (value !== null && input.type !== 'file') {
            input.value = value;
        }
    });
});
// Limpiar localStorage al enviar el formulario
document.getElementById('kitForm').addEventListener('submit', function() {
    document.querySelectorAll('#kitForm input, #kitForm textarea, #kitForm select').forEach(function(input) {
        localStorage.removeItem('kitForm_' + input.name);
    });
});
$(document).ready(function() {
    function setDisponibilidadBgColor(select) {
        var val = $(select).val();
        var bg = '';
        var color = 'white';
        // Solo para opciones del laboratorio
        if (val === 'Equipo Disponible' || val === 'Nuevo') {
            bg = '#28a745'; // verde
            color = 'white';
        } else if (val === 'Equipo Fuera de Servicio' || val === 'Usado') {
            bg = '#eeff07ff'; // amarillo
            color = 'black';
        } else if (val === 'En Servicio') {
            bg = '#dca735'; // naranja
            color = 'white';
        }else if (val === 'Equipo en Resguardo' || val === 'Terminado') {
            bg = '#dc3545'; // rojo
            color = 'white';
        } else {
            bg = 'white';
            color = 'black';
        }
        $(select).css({
            'background-color': bg,
            'color': color
        });
    }

    $('select[name="Disponibilidad_Estado"]').each(function() {
        setDisponibilidadBgColor(this);
    }).on('change', function() {
        setDisponibilidadBgColor(this);
    });
});

    document.addEventListener('DOMContentLoaded', function() {
        const stockTotal = document.getElementById('stockTotal');
        const stockUsado = document.getElementById('stockUsado');
        const stockNuevo = document.getElementById('stockNuevo');

        function updateStock(source) {
            const total = parseInt(stockTotal.value) || 0;
                                                    
            if (source === 'usado') {
            const usado = parseInt(stockUsado.value) || 0;
            if (usado <= total) {
                stockNuevo.value = total - usado;
            } else {
                stockUsado.value = total;
                stockNuevo.value = 0;
            }
            } else if (source === 'nuevo') {
            const nuevo = parseInt(stockNuevo.value) || 0;
            if (nuevo <= total) {
                stockUsado.value = total - nuevo;
            } else {
                stockNuevo.value = total;
                stockUsado.value = 0;
            }
            } else if (source === 'total') {
            const usado = parseInt(stockUsado.value) || 0;
            if (usado <= total) {
                stockNuevo.value = total - usado;
            } else {
                stockUsado.value = total;
                stockNuevo.value = 0;
            }
            }
            }

            stockTotal.addEventListener('input', () => updateStock('total'));
            stockUsado.addEventListener('input', () => updateStock('usado'));
            stockNuevo.addEventListener('input', () => updateStock('nuevo'));
    });