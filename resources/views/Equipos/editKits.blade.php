
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
    <br>
    <br>
    <br>
        <h3 align="center"> Edición de Kits</h3>
    <br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills justify-content-center"> 
                        <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Kits</a></li>
                        <!-- Agrega más tabs según sea necesario -->
                    </ul>
                </div><!-- /.card-header p-2-->
                <div class="card-body">
                    <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <form id="kitForm" method="post" enctype="multipart/form-data" action="{{ route('kits.update', $Kit->idKits) }}">
                                    @csrf
                                    <div class="box">
                                        <div class="d-flex justify-content-between align-items-center mb-3">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Nombre</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $Kit->Nombre }}" name="Nombre" placeholder="Ejemplo: Kit de Liquidos" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Prueba</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $Kit->Prueba }}" name="Prueba" placeholder="Ejemplo: Liquidos" required>
                                                </div>
                                            </div>

                                        </div><!--d-flex justify -->
                                    </div><!--box -->

                                    <h5 align="center">Elige los equipos y consumibles para editar el KIT</h5>
                                    <!-- Tabla de Elementos Disponibles -->
                                    <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Num. Económico</th>
                                                <th>Marca</th>
                                                <th>Modelo</th>
                                                <th>NS</th>
                                                <th>Stock</th>
                                                <th>Disponibilidad</th>
                                                <th>Fecha calibración</th>
                                                <th>Hoja de Presentación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($generalConCertificados as $general_eyc)
                                                <tr id="row-{{ $general_eyc->idGeneral_EyC }}">
                                                    @if($general_eyc)
                                                        <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                                                        <td scope="row">{{$general_eyc->No_economico}}</td>
                                                        <td scope="row">{{$general_eyc->Marca}}</td>
                                                        <td scope="row">{{$general_eyc->Modelo}}</td>
                                                        <td scope="row">{{$general_eyc->Serie}}</td>
                                                        <td scope="row">{{$general_eyc->almacen->Stock}}</td>
                                                        @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                                                <td scope="row"><button type="button" class="btn btn-block btn-outline-success">Disponible <i class="fa fa-check" aria-hidden="true"></i></td>
                                                            @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE')
                                                                <td scope="row"><button type="button" class="btn btn-block btn-outline-warning">No Disponible <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                                            @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                                                <td scope="row"><button type="button" class="btn btn-block btn-outline-danger">Fuera de servicio <i class="fa fa-ban" aria-hidden="true"></i></td>
                                                            @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                                                <td scope="row"><button type="button" class="btn btn-block btn-outline-info">Espera de Dato <i class="far fa-clock" aria-hidden="true"></i></td>
                                                        @endif
                                                    @endif 

                                                    @if($general_eyc->certificados)
                                                        @if($general_eyc->Tipo == 'EQUIPOS' || $general_eyc->Tipo == 'BLOCK Y PROBETA')
                                                                @if($general_eyc->certificados->Fecha_calibracion == '2001-01-01')
                                                                    <td scope="row">SIN FECHA ASIGNADA</td>
                                                                @else
                                                                    <td scope="row">{{$general_eyc->certificados->Fecha_calibracion}}</td>
                                                                @endif
                                                            @else
                                                                <td scope="row">N/A</td>
                                                        @endif
                                                        
                                                            <td scope="row"> 
                                                                @if ($general_eyc->Foto != 'ESPERA DE DATO')
                                                                    <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                                                        <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="fa fa-eye"></i></a>                                              
                                                                        @elseif($general_eyc->Foto == 'ESPERA DE DATO')  
                                                                        <a target="_blank">SIN FOTO/H.P/F.T</a>                                              
                                                                @endif
                                                            </td>
                                                        <td>
                                                    @endif
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-success btnAgregar" data-id="{{ $general_eyc->idGeneral_EyC }}" data-id-kits="{{ $Kit->idKits }}"><i class="fas fa-plus-circle" aria-hidden="true"></i></button>
                                                        </div>
                                                    </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <!-- Tabla de Elementos Seleccionados -->
                                    <div class="container">
                                    <h5 align="center">Nuevo Kit editado</h5>
                                    <br>
                                    <div class="card-body">
                                    <table id="TablaKits" class="table table-bordered" >
                                        <thead>
                                            <tr >
                                                <th>Nombre</th>
                                                <th>No.ECO</th>
                                                <th>Marca</th>
                                                <th>Ultima calibración</th>
                                                <th>Cantidad</th>
                                                <th>Unidad</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($DetallesKits as $detalle)
                                                @php
                                                    $general = $generalEyC;
                                                    $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                                                    $fechaCalibracion = $general->Certificados->Fecha_calibracion;
                                                @endphp
                                                <tr id="row-{{ $detalle->idDetalles_Kits }}">
                                                    <td>{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                                    <td>{{ $general->No_economico ?? 'N/A' }}</td>
                                                    <td>{{ $general->Marca ?? 'N/A' }}</td>
                                                    @if($general->Certificados)
                                                            @if($general->Tipo=='EQUIPOS' || $general->Tipo=='BLOCK Y PROBETA')
                                                                    @if($general->Certificados->Fecha_calibracion=='2001-01-01')
                                                                            <td scope="row">SIN FECHA ASIGNADA</td>
                                                                        @else
                                                                            <td scope="row">{{$general->Certificados->Fecha_calibracion}}</td>
                                                                    @endif
                                                                @else
                                                                    <td scope="row">N/A</td>
                                                            @endif
                                                    @endif
                                                    @if($general->Tipo == 'CONSUMIBLES')
                                                        <td scope="row">
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="Cantidad[{{ $detalle->idDetalles_Kits }}]" value="{{ $detalle->Cantidad ?? 'ESPERA DE DATO' }}" required>
                                                            </div>
                                                        </td>

                                                        <td scope="row">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="Unidad[{{ $detalle->idDetalles_Kits }}]" value="{{ $detalle->Unidad ?? 'ESPERA DE DATO' }}" required>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <input type="hidden" name="general_eyc_id[]" value="{{ $detalle->idGeneral_EyC }}">
                                                            <button type="button" class="btn btn-danger btnEliminarDetallesKits" data-id="{{ $detalle->idDetalles_Kits }}">
                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    @else
                                                        <td scope="row">
                                                            <div class="input-group">
                                                                <input type="number" class="form-control" name="Cantidad[{{ $detalle->idDetalles_Kits }}]" value="{{ $detalle->Cantidad ?? '1' }}" readonly>
                                                            </div>
                                                        </td>

                                                        <td scope="row">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="Unidad[{{ $detalle->idDetalles_Kits }}]" value="{{ $detalle->Unidad ?? 'ESPERA DE DATO' }}" required>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <input type="hidden" name="general_eyc_id[]" value="{{ $detalle->idGeneral_EyC }}">
                                                            <button type="button" class="btn btn-danger btnEliminarDetallesKits" data-id="{{ $detalle->idDetalles_Kits }}">
                                                                <i class="fa fa-times" aria-hidden="true"></i>
                                                            </button>
                                                        </td>
                                                    @endif

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                        <br>
                                        <button type="submit" class="btn btn-info bg-primary" id="btnFinalizarkit">Guardar</button>
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
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>
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

    /*   $(document).ready(function() {
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

        // Función para verificar si un dato ya existe en la segunda tabla
function verificarExistenciaEnTabla(idFila) {
    const filas = document.querySelectorAll('#TablaKits tbody tr');
    return Array.from(filas).some(fila => fila.querySelector('input[name="general_eyc_id[]"]').value === idFila);
}

// Botón de AGREGAR
/*document.querySelectorAll('.btnAgregar').forEach(button => {
    button.addEventListener('click', function() {
        // Deshabilitar el botón para evitar múltiples clics
        const btn = this;
        btn.disabled = true;

        let idFila = btn.getAttribute('data-id');
        let idKits = btn.getAttribute('data-id-kits');

        // Verificar si el dato ya existe en la segunda tabla
        if (verificarExistenciaEnTabla(idFila)) {
            Swal.fire({
                icon: 'warning',
                title: 'Elemento ya agregado',
                text: 'Este elemento ya está en el kit.',
            });
            btn.disabled = false;
            return;
        }

        // Consultar la cantidad en almacén
        consultarCantidadAlmacen(idFila, function(error, cantidadAlmacen) {
            if (error) {
                console.error('Error consultando cantidad en almacén:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un error al consultar la cantidad en almacén.',
                });
                btn.disabled = false;
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
                        title: 'Elemento Agregado Exitosamente.',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // Obtén el ID del detalle agregado desde la respuesta
                    let idDetalles_Kits = data.idDetalles_Kits;

                    // Obtener datos de la fila actual
                    let row = document.getElementById('row-' + idFila);
                    let nombre = row.querySelector('td:nth-child(1)').innerText;
                    let noEco = row.querySelector('td:nth-child(2)').innerText;
                    let marca = row.querySelector('td:nth-child(3)').innerText;
                    let ultimaCalibracion = row.querySelector('td:nth-child(8)').innerText; // Ajustado al índice correcto

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
                                <input type="text" class="form-control" name="Unidad[]" value="EN ESPERA DE DATOS" required>
                            </div>
                        </td>
                        <td>
                            <input type="hidden" name="general_eyc_id[]" value="${idFila}">
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
                btn.disabled = false;
            });
        });
    });
});
});*/

$(document).ready(function() {
    $('#btnFinalizarkit').click(function(event) {
        // Verificar si hay filas en la tabla
        if ($('#TablaKits tbody tr').length === 0) {
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


$(document).ready(function() {
    // Delegación de eventos para el botón "Agregar"
    $('#tablaJs').on('click', '.btnAgregar', function() {
        // Deshabilitar el botón para evitar múltiples clics
        $(this).prop('disabled', true);

        let idFila = $(this).data('id');
        let idKits = $(this).data('id-kits');

        fetch('/kits/agregar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
                    title: 'Elemento Agregado Exitosamente.',
                    showConfirmButton: false,
                    timer: 2000
                });

                // Eliminar la fila de la primera tabla
                let row = $('#row-' + idFila);
                let nombre = row.find('td:nth-child(1)').text();
                let noEco = row.find('td:nth-child(2)').text();
                let marca = row.find('td:nth-child(3)').text();
                let ultimaCalibracion = row.find('td:nth-child(8)').text();

                row.remove();

                // Crear una nueva fila en la segunda tabla
                let newRow = `
                    <tr id="row-${data.idDetalles_Kits}">
                        <td>${nombre}</td>
                        <td>${noEco}</td>
                        <td>${marca}</td>
                        <td>${ultimaCalibracion}</td>
                        <td>
                            <div class="input-group">
                                <input type="number" class="form-control" name="Cantidad[${data.idDetalles_Kits}]" value="1" ${data.stock === 1 ? 'readonly' : ''}>
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input type="text" class="form-control" name="Unidad[${data.idDetalles_Kits}]" value="ESPERA DE DATO">
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btnEliminarDetallesKits" data-id="${data.idDetalles_Kits}"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                `;
                $('#TablaKits tbody').append(newRow);

                // Animar la nueva fila
                $('#row-' + data.idDetalles_Kits).addClass('table-success');
                setTimeout(() => {
                    $('#row-' + data.idDetalles_Kits).removeClass('table-success');
                }, 1500);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Elemento duplicado',
                    text: data.message,
                    confirmButtonText: 'Entendido'
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
            $(this).prop('disabled', false);
        });
    });

    // Delegación de eventos para el botón "Eliminar"
    $('#TablaKits').on('click', '.btnEliminarDetallesKits', function() {
        var idDetalles_Kits = $(this).data('id');
        var token = $('meta[name="csrf-token"]').attr('content');

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
                        var errorMessage = xhr.responseJSON.error || 'Se produjo un error al eliminar el registro.';
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
});


</script>
@endsection