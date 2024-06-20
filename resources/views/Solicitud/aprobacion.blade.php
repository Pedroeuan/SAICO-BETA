@extends('adminlte::page')

@section('title', 'Equipos')

@section('css')
<!--datatable -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css">

@endsection

@section('content')
<br>  
<br>
<br>
<!-- form start -->
    <form role="form">
        <div class="box">
            <h3 align="center">Aprobación de manifiesto</h3>
            <br>
            <h5 align="center">Inventario</h5>
            <div class="box-body">
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Num. Económico</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>NS</th>
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
                            <td scope="row">{{$general_eyc->Disponibilidad_Estado}}</td>
                        @endif 
                            @if($general_eyc->certificados)
                                @if($general_eyc->Tipo =='EQUIPOS' || $general_eyc->Tipo == 'BLOCK Y PROBETA')
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
                                <button type="button" class="btn btn-success btnAgregar" data-id="{{ $general_eyc->idGeneral_EyC }}" data-id-solicitud="{{ $Solicitud->idSolicitud }}"><i class="fas fa-plus-circle" aria-hidden="true"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <h3 align="center">Pre Manifiesto</h3>
    <br>
    <h5 align="center">Solicitud</h5>
    <div class="card-body">
        <table id="TablaSolicitud" class="table table-bordered" >
            <thead>
                <tr>
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
                @foreach ($DetallesSolicitud as $detalle)
                    @php
                        $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                    @endphp
                    <tr id="row-{{ $detalle->idDetalles_Solicitud }}">
                        <td>{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                        <td>{{ $general->No_economico ?? 'N/A' }}</td>
                        <td>{{ $general->Marca ?? 'N/A' }}</td>
                        <td>{{ $general->Ultima_Fecha_calibracion ?? 'N/A' }}</td>
                        <td scope="row">
                            <div class="input-group">
                                <input type="text" class="form-control" name="Cantidad[]" value="{{ $detalle->Cantidad ?? 'N/A' }}">
                            </div>
                        </td>
                        <td scope="row">
                            <div class="input-group">
                                <input type="text" class="form-control" name="Unidad[]" value="{{ $detalle->Unidad ?? 'N/A' }}">
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btnEliminarDetallesSolicitud" data-id="{{ $detalle->idDetalles_Solicitud }}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
<br>
<button type="button" class="btn btn-success">Crear manifiesto</button>
</form>
<br>
@stop

@section('js')
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /*Solicitud*/
    new DataTable('#tablaJs');

    let dataTable;

    function initializeDataTable() {
        // Destruir el DataTable si ya está inicializado
        if ($.fn.DataTable.isDataTable('#tablaJs')) {
            dataTable.destroy();
        }
        //Inicializar el DataTable
        dataTable = new DataTable('#tablaJs');
    }

    $(document).ready(function() {
        // Delegación de eventos para los botones de eliminación
        $(document).on('click', '.btnEliminarDetallesSolicitud', function() {
            var idDetalles_Solicitud = $(this).data('id');
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
                        url: '/Detalles_solicitudes/eliminar/' + idDetalles_Solicitud,
                        type: 'DELETE',
                        data: {
                            "_token": token,
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#row-' + idDetalles_Solicitud).remove();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Confirmado!',
                                    text: "Equipo Eliminado Correctamente!",
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

          /* AGREGAR */
        document.querySelectorAll('.btnAgregar').forEach(button => {
            button.addEventListener('click', function() {
                // Deshabilitar el botón para evitar múltiples clics
                this.disabled = true;

                // Mostrar un indicador de carga
                /* Swal.fire({
                    title: 'Agregando...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });*/

                let idFila = this.getAttribute('data-id');
                let idSolicitud = this.getAttribute('data-id-solicitud');

                fetch('/solicitudes/agregar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        idFila: idFila,
                        idSolicitud: idSolicitud
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
                        let idDetalles_Solicitud = data.idDetalles_Solicitud;

                        // Eliminar la fila de la primera tabla
                        let row = document.getElementById('row-' + idFila);
                        let nombre = row.querySelector('td:nth-child(1)').innerText;
                        let noEco = row.querySelector('td:nth-child(2)').innerText;
                        let marca = row.querySelector('td:nth-child(3)').innerText;
                        let ultimaCalibracion = row.querySelector('td:nth-child(7)').innerText;

                        row.remove();

                        // Crear una nueva fila en la segunda tabla
                        let newRow = document.createElement('tr');
                        newRow.setAttribute('id', 'row-' + idDetalles_Solicitud);
                        newRow.innerHTML = `
                            <td>${nombre}</td>
                            <td>${noEco}</td>
                            <td>${marca}</td>
                            <td>${ultimaCalibracion}</td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Cantidad[]" value="0">
                                </div>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Unidad[]" value="ESPERA DE DATO">
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btnEliminarDetallesSolicitud" data-id="${idDetalles_Solicitud}"><i class="fa fa-times" aria-hidden="true"></i></button>
                            </td>
                        `;
                        document.querySelector('#TablaSolicitud tbody').appendChild(newRow);

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
</script>
@endsection

