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
<form id="solicitudForm" role="form" method="POST" action="{{route('solicitudes.storeSolicitud')}}" enctype="multipart/form-data">
    @csrf
    <!-- 
    La clase d-flex se utiliza para hacer que el contenedor use Flexbox, lo que facilita la alineación de elementos hijos.
    justify-content-between se usa para espaciar los elementos (botón y fecha) de manera que estén en los extremos opuestos del contenedor.
    align-items-center alinea verticalmente los elementos al centro.
    mb-3 agrega un margen inferior de 3 unidades para separar este grupo de otros elementos.
    form-group mb-0 asegura que no haya margen inferior en el grupo de formulario para que los elementos estén alineados correctamente.
    col-form-label mr-2 asegura que la etiqueta tenga un margen derecho para espaciarla del campo de fecha.
    d-inline-block y style="width: auto;" aseguran que el campo de fecha no ocupe todo el ancho disponible y se alinee correctamente.
    -->
    <div class="box">
        <br>
        <br>
        <div class="box">
            <h5 align="center">Elige el kit para solicitar</h5>
            <div class="box-body">
                <table id="tablaKits" class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Prueba</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kitsConDetalles as $kits)
                        <tr>
                        @if($kits)
                            <td scope="row">{{$kits->Nombre}}</td>
                            <td scope="row">{{$kits->Prueba}}</td>
                            <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btnAgregarKit" data-id="{{ $kits->idKits }}"><i class="fas fa-plus-circle"></i></button>
                                </div>
                                        </td>
                        @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="box-body">
            <h5 align="center">Elige el equipo o consumible para solicitar</h5>
            <table id="tablaInventario" class="table table-bordered table-striped dt-responsive tablas">
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
                        <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                        <td scope="row">{{$general_eyc->No_economico}}</td>
                        <td scope="row">{{$general_eyc->Marca}}</td>
                        <td scope="row">{{$general_eyc->Modelo}}</td>
                        <td scope="row">{{$general_eyc->Serie}}</td>
                        <td scope="row">
                        @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                        <button type="button" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i></button>
                        @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE')
                        <button type="button" class="btn btn-warning"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i></button>
                        @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                        <button type="button" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i></button>
                        @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                        <button type="button" class="btn btn-info"><i class="far fa-clock" aria-hidden="true"></i></button>
                        @endif
                        </td>
                        <td scope="row">
                        @if($general_eyc->certificados)
                        @if($general_eyc->Tipo =='EQUIPOS' || $general_eyc->Tipo == 'BLOCK Y PROBETA')
                        @if($general_eyc->certificados->Fecha_calibracion == '2001-01-01')
                            SIN FECHA ASIGNADA
                        @else
                        {{$general_eyc->certificados->Fecha_calibracion}}
                        @endif
                        @else
                            N/A
                        @endif
                        @endif
                        </td>
                        <td scope="row">
                        @if ($general_eyc->Foto != 'ESPERA DE DATO')
                        <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>
                        @elseif($general_eyc->Foto == 'ESPERA DE DATO')
                        <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                        @endif
                        </td>
                        <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-success btnAgregarInventario" data-id="{{ $general_eyc->idGeneral_EyC }}"><i class="fas fa-plus-circle"></i></button>
                        </div>
                        </td>
                    </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <div class="left-align">
            <label class="col-form-label mr-2" for="Fecha_Servicio">Fecha de Servicio</label>
            <input type="date" class="form-control inputForm d-inline-block" name="Fecha_Servicio" id="Fecha_Servicio" style="width: auto;">
        </div>
        <div class="box">
            <h5 align="center">Elementos Solicitados</h5>
            <br>
            <div class="box-body">
                <table id="tablaAgregados" class="table table-bordered table-striped dt-responsive tablas">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Num. Económico</th>
                            <th>Marca</th>
                            <th>Ultima Calibración</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- Elementos agregados se mostrarán aquí -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col text-center">
            <button class="btn btn-success" data-toggle="modal" data-target="#modalSolicitarEyC">
                Finalizar solicitud
            </button>
        </div>
        <br>
    </div>
</form>
@stop

@section('js')
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!--Ajax-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>

<script>

let tableKits = new DataTable('#tablaKits', {
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

let tableInventario = new DataTable('#tablaInventario', {
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
        // Agregar elemento de inventario
        $('.btnAgregarInventario').click(function() {
            var rowId = $(this).data('id');
            var row = $('#row-' + rowId);
            var nombre = row.find('td:eq(0)').text();
            var numEconomico = row.find('td:eq(1)').text();
            var marca = row.find('td:eq(2)').text();
            var ultimaCalibracion = row.find('td:eq(6)').text();

            var newRow = `
                <tr>
                    <td>${nombre}</td>
                    <td>${numEconomico}</td>
                    <td>${marca}</td>
                    <td>${ultimaCalibracion}</td>
                    <td><input type="number" class="form-control" name="cantidad[]" required></td>
                    <td><input type="text" class="form-control" name="unidad[]" required></td>
                    <td><input type="hidden" name="general_eyc_id[]" value="${rowId}"></td>
                    <td><button type="button" class="btn btn-danger btnQuitarElemento"><i class="fas fa-minus-circle"></i></button></td>
                </tr>
            `;

            $('#tablaAgregados tbody').append(newRow);
        });

        // Agregar elemento de kits
        $('.btnAgregarKit').click(function() {
            var kitId = $(this).data('id');

            $.ajax({
                url: '/Obtener/Kits/' + kitId,
                method: 'GET',
                success: function(detallesKits) {
                    detallesKits.forEach(function(detalle) {
                        $.ajax({
                            url: '/Obtener/generaleyc/' + detalle.idGeneral_EyC,
                            method: 'GET',
                            success: function(generalEyC) {
                                var newRow = `
                                    <tr>
                                        <td>${generalEyC.Nombre_E_P_BP}</td>
                                        <td>${generalEyC.No_economico}</td>
                                        <td>${generalEyC.Marca}</td>
                                        <td>${generalEyC.certificados.Fecha_calibracion}</td>
                                        <td><input type="number" class="form-control" name="cantidad[]" value="${detalle.Cantidad}" required></td>
                                        <td><input type="text" class="form-control" name="unidad[]" value="${detalle.Unidad}" required></td>
                                        <td><input type="hidden" name="general_eyc_id[]" value="${detalle.idGeneral_EyC}"></td>
                                        <td><button type="button" class="btn btn-danger btnQuitarElemento"><i class="fas fa-minus-circle"></i></button></td>
                                    </tr>
                                `;
                                $('#tablaAgregados tbody').append(newRow);
                            },
                            error: function() {
                                alert('Error al obtener detalles de General_EyC.');
                            }
                        });
                    });
                },
                error: function() {
                    alert('Error al obtener detalles de Kits.');
                }
            });
        });

        // Eliminar elemento
        $(document).on('click', '.btnQuitarElemento', function() {
            $(this).closest('tr').remove();
        });
    });

    $(document).ready(function() {
    $('#solicitudForm').on('submit', function(event) {
        var fechaServicio = $('#Fecha_Servicio').val();

        if (!fechaServicio) {
            event.preventDefault(); // Previene el envío del formulario
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Por favor, completa el campo de Fecha de Servicio antes de enviar la solicitud.'
            });
        }
    });
});
</script>

@endsection
