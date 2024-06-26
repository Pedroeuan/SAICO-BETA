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
<form role="form" method="POST" action="{{route('solicitudes.storeSolicitud')}}" enctype="multipart/form-data">
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
            <h3 align="center">Formulario para solicitar equipos y consumibles</h3>
            <br>
        <div class="left-align">
            <label class="col-form-label mr-2" for="inputSuccess">Fecha de Servicio</label>
            <input type="date" class="form-control inputForm d-inline-block" name="Fecha_Servicio" style="width: auto;">
        </div>
            <br>
            <div class="box ">
                <h3 align="center">Kits</h3>
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
                            <!--Tabla body-->
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
                    <h3 align="center">Inventario</h3>
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

                <div class="box">
                    <h3 align="center">Elementos Solicitados</h3>
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


    //mostrar datatableKits
    new DataTable('#tablaKits');

    let dataTableK;

    function initializeDataTable() {
    // Destruir el DataTable si ya está inicializado
        if ($.fn.DataTable.isDataTable('#tablaKits')) {
            dataTableK.destroy();
        }
    // Inicializar el DataTable
        dataTableK = new DataTable('#tablaKits');
    }

    //mostrar datatableInventario
    new DataTable('#tablaInventario');

    let dataTableI;

    function initializeDataTable() {
    // Destruir el DataTable si ya está inicializado
        if ($.fn.DataTable.isDataTable('#tablaInventario')) {
            dataTableI.destroy();
        }
    // Inicializar el DataTable
        dataTableI = new DataTable('#tablaInventario');
    }

    //Agregar-INVENTARIO
    $(document).ready(function() {
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
                    <td><input type="number" class="form-control" name="cantidad"></td>
                    <td><input type="text" class="form-control" name="unidad"></td>
                    <td><button type="button" class="btn btn-danger btnQuitarElemento"><i class="fas fa-minus-circle"></i></button></td>
                </tr>
            `;

            $('#tablaAgregados tbody').append(newRow);
        });

        $(document).on('click', '.btnQuitarElemento', function() {
            $(this).closest('tr').remove();
        });
    });
</script>

@endsection
