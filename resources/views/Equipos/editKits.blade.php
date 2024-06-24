
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
                                <form id="kitForm" method="post" enctype="multipart/form-data" action="">
                                    @csrf

                                    <div class="box">
                                        <div class="d-flex justify-content-between align-items-center mb-3">

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Nombre</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $kitsConDetalles->Nombre }}" name="Nombre" placeholder="Ejemplo: Kit de Liquidos">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label class="col-form-label" for="inputSuccess">Prueba</label>
                                                    <input type="text" class="form-control inputForm" value="{{ $kitsConDetalles->Prueba }}" name="Prueba" placeholder="Ejemplo: Liquidos">
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
                                            <tr data-id="{{ $general_eyc->idGeneral_EyC }}">
                                                <td>{{ $general_eyc->Nombre_E_P_BP }}</td>
                                                <td>{{ $general_eyc->No_economico }}</td>
                                                <td>{{ $general_eyc->Marca }}</td>
                                                <td>{{ $general_eyc->Modelo }}</td>
                                                <td>{{ $general_eyc->Serie }}</td>
                                                <td>
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
                                                <td>{{ $general_eyc->certificados ? $general_eyc->certificados->Fecha_calibracion : 'N/A' }}</td>
                                                    <td>
                                                        @if ($general_eyc->Foto != 'ESPERA DE DATO')
                                                        <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="fa fa-eye"></i></a>
                                                        @else
                                                        <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                                        @endif
                                                    </td>
                                                <td>
                                                    <button type="button" class="btn btn-success btnAgregar" data-id="{{ $general_eyc->idGeneral_EyC }}"><i class="fas fa-plus-circle" aria-hidden="true"></i></button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <!-- Tabla de Elementos Seleccionados -->
                                    <table id="tablaSeleccionados" class="table table-bordered table-striped dt-responsive tablas">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Num. Económico</th>
                                                <th>Marca</th>
                                                <th>Ultima calibración</th>
                                                <th>Cantidad</th>
                                                <th>Unidad</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>

                                    <div class="container">
                                        <div class="float-right">
                                            <button type="submit" class="btn btn-info bg-primary">Finalizar</button>
                                        </div>

                                        <div class="float-left">
                                            <button type="button" class="btn btn-info bg-success" id="guardarContinuarKits">Guardar y continuar</button>
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
</script>
@endsection