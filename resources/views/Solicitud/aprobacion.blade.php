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
    <form id="AprobacionForm" action="{{ route('solicitud.manifiesto', ['id' => $Solicitud->idSolicitud]) }}" method="post" enctype="multipart/form-data" role="form">
    @csrf 
        <div class="box">
            <h3 align="center">Formulario para aprobar solicitud de equipos y consumibles</h3>
            <br>
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
                            @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                <td scope="row"><button type="button" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i></td>
                            @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE')
                                <td scope="row"><button type="button" class="btn btn-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                            @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                <td scope="row"><button type="button" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i></td>
                            @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                <td scope="row"><button type="button" class="btn btn-info"><i class="far fa-clock" aria-hidden="true"></i></td>
                            @endif
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
                                            <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>                                              
                                                @elseif($general_eyc->Foto == 'ESPERA DE DATO')  
                                            <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
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
    <h3 align="center">Equipos y Consumibles por Aprobar</h3>
    <br>
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
                                <input type="text" class="form-control" name="Cantidad[{{ $detalle->idDetalles_Solicitud }}]" value="{{ $detalle->Cantidad ?? '0' }}">
                            </div>
                        </td>
                        <td scope="row">
                            <div class="input-group">
                                <input type="text" class="form-control" name="Unidad[{{ $detalle->idDetalles_Solicitud }}]" value="{{ $detalle->Unidad ?? 'ESPERA DE DATO' }}">
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

                        <!--Campo Oculto para pasar el id de Solicitud /hidden-->
                            @if($Manifiestos)
                            <div class="row">
                                <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Cliente</label>
                                            <input type="text" class="form-control inputForm" name="Cliente"  placeholder="Ejemplo: PROPETROL" value="{{ $Manifiestos->Cliente }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Folio</label>
                                            <input type="text" class="form-control inputForm" name="Folio" placeholder="Ejemplo: PROP-040/24" value="{{ $Manifiestos->Folio }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Destino</label>
                                            <input type="text" class="form-control inputForm" name="Destino" placeholder="Ejemplo: PATIO DE FABRICACIÓN PROTEXA" value="{{ $Manifiestos->Destino }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Fecha de Salida</label>
                                            <input type="date" class="form-control inputForm" name="Fecha_Salida" value="{{ $Solicitud->Fecha }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Trabajo</label>
                                            <input type="text" class="form-control inputForm" name="Trabajo" placeholder="Ejemplo: Dureza" value="{{ $Manifiestos->Trabajo }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Puesto</label>
                                            <input type="text" class="form-control inputForm" name="Puesto" placeholder="Ejemplo: TEC. PND" value="{{ $Manifiestos->Puesto }}" disabled>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Responsable</label>
                                            <input type="text" class="form-control inputForm" name="Responsable" placeholder="Ejemplo: ALFREDO MARTINEZ TORRRES" value="{{ $Manifiestos->Responsable }}" disabled>
                                        </div>
                                    </div>
                                
                                <!--Campo Oculto para pasar el id de Solicitud -->
                                <label class="col-form-label" for="inputSuccess">idSolicitud</label>
                                <input type="text" class="form-control inputForm" name="idSolicitud" placeholder="" value="{{ $Solicitud->idSolicitud }}" disabled>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label" for="inputSuccess">Observaciones</label>
                                            <textarea class="form-control is-waning" id="inputSuccess" name="Observaciones" placeholder="Ejemplo: Equipo con bateria INCLUYE: Cables con puntas de contacto." disabled>{{ $Manifiestos->Observaciones }}</textarea>
                                        </div>
                                    </div>
                                    @endif
<button type="submit" class="btn btn-success">Crear manifiesto</button>
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
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>

<script>
    /*Solicitud*/
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
                            title: 'Elemento Agregado Exitosamente.',
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

