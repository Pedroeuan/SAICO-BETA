
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
    <br>
        <h3 align="center"> Formulario Alta de Clientes</h3>
    <br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center"> 
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Clientes</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->

                <div class="card-body">
                    <div class="tab-content">

                            <div class="tab-pane active" id="tab_1">
                                <form id="kitForm" method="post" enctype="multipart/form-data" action="{{route('registro.storeClientes')}}">
                                    @csrf
                                        <div class="row">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Cliente</label>
                                                    <input type="text" class="form-control inputForm" value="" name="Cliente" placeholder="Ejemplo: PROTEXA" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">RFC</label>
                                                    <input type="text" class="form-control inputForm" value="" name="RFC" placeholder="Ejemplo: PROP56512458">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Telefono</label>
                                                    <input type="text" class="form-control inputForm" value="" name="Telefono" placeholder="Ejemplo: 81 8399 2828">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Correo</label>
                                                    <input type="text" class="form-control inputForm" value="" name="Correo" placeholder="Ejemplo: hola@protexa.mx">
                                                </div>
                                            </div>
                                            <div class="container">
                                                <div class="float-right">
                                                    <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                                </div>
                                                <div class="float-left">
                                                    <button type="button" class="btn btn-info bg-success" id="guardarContinuarBlocks">Guardar y continuar</button>
                                                </div>
                                            </div>

                                        </div>
                                </form>
                        </div><!--"class="tab-pane active" id="tab_1"-->
                    </div><!-- /.tab-content -->
                </div><!-- class="card-body" -->
                        <!-- Agrega más paneles de tabs según sea necesario -->
            </div><!-- /.card -->       
        </div><!-- class="col-sm-12" -->
    </div><!-- class="row justify-content-center" -->
</div><!--class="container" -->     
@stop


@section('js')
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
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
    
    /*Prevenir el Enter Kits*/
document.getElementById('kitForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    $(document).ready(function() {

// Función para consultar la cantidad en almacén
function consultarCantidadAlmacen(id, callback) {
    $.ajax({
        url: '/Obtener/CantidadAlmacen/' + id,
        method: 'GET',
        success: function(data) {
            callback(null, data.Cantidad); // Asume que la respuesta contiene un campo "Cantidad"
        },
        error: function(error) {
            callback(error);
        }
    });
}

// Delegación de eventos para los botones de eliminación
$(document).on('click', '.btnEliminarDetallesKits', function() {
    var idDetalles_Kits = $(this).data('id');
    var token = '{{ csrf_token() }}';

    Swal.fire({
        title: "¿Seguro de eliminar este elemento?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/Detalles_Kits/eliminar/' + idDetalles_Kits,
                type: 'DELETE',
                data: {
                    "_token": token,
                },
                success: function(response) {
                    if (response.success) {
                        $('#row-' + idDetalles_Kits).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Confirmado!',
                            text: "Elemento Eliminado Correctamente!",
                        });
                    }
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON.error || 'Error occurred while deleting the record.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                    });
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Cancelado", "", "error");
        }
    });
});

// Botón de  AGREGAR
document.querySelectorAll('.btnAgregar').forEach(button => {
    button.addEventListener('click', function() {
        // Deshabilitar el botón para evitar múltiples clics
        this.disabled = true;

        let idFila = this.getAttribute('data-id');
        let idKits = this.getAttribute('data-id-kits');

        // Consultar la cantidad en almacén
        consultarCantidadAlmacen(idFila, function(error, cantidadAlmacen) {
            if (error) {
                console.error('Error consultando cantidad en almacén:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un error al consultar la cantidad en almacén.',
                });
                return;
            }

            // Lógica para decidir si el campo de cantidad es editable o no
            let cantidadEditable = (cantidadAlmacen > 1);
            let cantidadInput = `<input type="number" class="form-control" name="Cantidad[]" value="${cantidadEditable ? 1 : cantidadAlmacen}" ${cantidadEditable ? '' : 'readonly'}>`;

            fetch('/kits/agregar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    idFila: idFila,
                    idKits: idKits
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Detalle agregado exitosamente.',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // Obtén el ID del detalle agregado desde la respuesta
                    let idDetalles_Kits = data.idDetalles_Kits;

                    // Eliminar la fila de la primera tabla
                    let row = document.getElementById('row-' + idFila);
                    let nombre = row.querySelector('td:nth-child(1)').innerText;
                    let noEco = row.querySelector('td:nth-child(2)').innerText;
                    let marca = row.querySelector('td:nth-child(3)').innerText;
                    let ultimaCalibracion = row.querySelector('td:nth-child(7)').innerText;

                    row.remove();

                    // Crear una nueva fila en la segunda tabla
                    let newRow = document.createElement('tr');
                    newRow.setAttribute('id', 'row-' + idDetalles_Kits);
                    newRow.innerHTML = `
                        <td>${nombre}</td>
                        <td>${noEco}</td>
                        <td>${marca}</td>
                        <td>${ultimaCalibracion}</td>
                        <td>
                            <div class="input-group">
                                ${cantidadInput}
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" name="Unidad[]" value="ESPERA DE DATO">
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btnEliminarDetallesKits" data-id="${idDetalles_Kits}"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </td>
                    `;
                    document.querySelector('#TablaKits tbody').appendChild(newRow);

                    // Animar la nueva fila
                    newRow.classList.add('table-success');
                    setTimeout(() => {
                        newRow.classList.remove('table-success');
                    }, 1500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Hubo un error al agregar el detalle.',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un error al agregar el detalle.',
                });
            })
            .finally(() => {
                // Habilitar el botón nuevamente
                this.disabled = false;
            });
        });
    });
});

});


</script>
@endsection