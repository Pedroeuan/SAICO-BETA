
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
    <div class="box-header with-border">
        <button class="btn btn-success" data-toggle="modal" data-target="#modalSolicitarEyC">
        Solicitar
        </button>
    </div>

    <div class="box">
        <h3 align="center">Solicitud</h3>
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
                        <th>Cantidad</th>
                        <th>Unidad</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($generalConCertificados as $general_eyc)
                    <tr>
                        @if($general_eyc)
                        @php
                        //dump($general_eyc->idGeneral_EyC);
                        @endphp
                            <input type="hidden" name="general_eyc_id[]" value="{{$general_eyc->idGeneral_EyC}}">
                            <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                            <td scope="row">{{$general_eyc->No_economico}}</td>
                            <td scope="row">{{$general_eyc->Marca}}</td>
                            <td scope="row">{{$general_eyc->Modelo}}</td>
                            <td scope="row">{{$general_eyc->Serie}}</td>
                            <td scope="row">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Destino[]" value="{{$general_eyc->Disponibilidad_Estado}}">
                                </div>
                            </td>
                        @endif 
                        @if($general_eyc->certificados)
                            @if($general_eyc->Tipo=='EQUIPOS' || $general_eyc->Tipo=='BLOCK Y PROBETA')
                                @if($general_eyc->certificados->Fecha_calibracion=='2001-01-01')
                                    <td scope="row">SIN FECHA ASIGNADA</td>
                                @else
                                    <td scope="row">{{$general_eyc->certificados->Fecha_calibracion}}</td>
                                @endif
                            @else
                                <td scope="row">N/A</td>
                            @endif
                            <td scope="row"> 
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="Cantidad[]" value="">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="Unidad[]" value="">
                                    </div>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>

@stop

@section('js')
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // funcion borrar 
    $(".btnEliminarEquipo").on("click", function(){
        //valor del id a eliminar
        var idGeneral_EyC = $(this).attr("idGeneral_EyC");
            Swal.fire({
                title: "Seguro de eliminar este elemento?",
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: "Sí",
                denyButtonText: "No"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire("Eliminado!", "", "success");
                } else if (result.isDenied) {
                    Swal.fire("Cancelado", "", "error");
                }
            });
    })

    //mostrar datatable
    new DataTable('#tablaJs');
</script>

@endsection

