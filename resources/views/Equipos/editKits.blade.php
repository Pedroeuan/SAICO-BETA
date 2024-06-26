
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
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
                                                    <input type="text" class="form-control inputForm" value="{{ $Kit->Nombre }}" name="Nombre" placeholder="Ejemplo: Kit de Liquidos">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Prueba</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $Kit->Prueba }}" name="Prueba" placeholder="Ejemplo: Liquidos">
                                                </div>
                                            </div>

                                        </div><!--d-flex justify -->
                                    </div><!--box -->

                                    <h5 align="center">Inventario Disponible</h5>
                                    <!-- Tabla de Elementos Disponibles -->
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
                                    <h3 align="center">Kit</h3>
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
                                                    $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                                                @endphp
                                                <tr id="row-{{ $detalle->idDetalles_Kits }}">
                                                    <td>{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                                    <td>{{ $general->No_economico ?? 'N/A' }}</td>
                                                    <td>{{ $general->Marca ?? 'N/A' }}</td>
                                                    <td>{{ $general->Ultima_Fecha_calibracion ?? 'N/A' }}</td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="Cantidad[{{ $detalle->idDetalles_Kits }}]" value="{{ $detalle->Cantidad ?? 'N/A' }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="Unidad[{{ $detalle->idDetalles_Kits }}]" value="{{ $detalle->Unidad ?? 'N/A' }}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btnEliminarDetallesKits" data-id="{{ $detalle->idDetalles_Kits }}">
                                                            <i class="fa fa-times" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    </div>
                                        <br>
                                        <button type="submit" class="btn btn-info bg-primary">Guardar</button>
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
    new DataTable('#tablaJs');

    let dataTable;

    function initializeDataTable() {
       // Destruir el DataTable si ya está inicializado
        if ($.fn.DataTable.isDataTable('#tablaJs')) {
            dataTable.destroy();
        }
       // Inicializar el DataTable
        dataTable = new DataTable('#tablaJs');
    }
    
    /*Prevenir el Enter Kits*/
document.getElementById('kitForm').addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });

    /*Botón de  ELIMINACIÓN */
    $(document).ready(function() {
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

          /*Botón de  AGREGAR */
        document.querySelectorAll('.btnAgregar').forEach(button => {
            button.addEventListener('click', function() {
                // Deshabilitar el botón para evitar múltiples clics
                this.disabled = true;

                let idFila = this.getAttribute('data-id');
                let idKits = this.getAttribute('data-id-kits');
                    
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
                                    <input type="text" class="form-control" name="Cantidad[]" value="0">
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

</script>
@endsection