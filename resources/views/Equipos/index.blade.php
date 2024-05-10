
@extends('adminlte::page')

@section('title', 'Equipos')

@section('content')
<br>  
<br>
<br>
 <!-- form start -->
 <form role="form">
    <div class="box">
        <h3 align="center">Inventario de equipos</h3>
        
        <br>
        <div class="box-body">
            <table class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Num. Económico</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>NS</th>
                        <th>Destino</th>
                        <th>Fecha calibración</th>
                        <!--<th>Foto</th>-->
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($generalConCertificados as $general_eyc)
                    <tr>
                    @if($generalConCertificados)
                        <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                        <td scope="row">{{$general_eyc->No_economico}}</td>
                        <td scope="row">{{$general_eyc->Marca}}</td>
                        <td scope="row">{{$general_eyc->Modelo}}</td>
                        <td scope="row">{{$general_eyc->Serie}}</td>
                        <td scope="row">{{$general_eyc->Destino}}</td>
                    @else
                        <td scope="row">SIN DATOS</td>
                        <td scope="row">SIN DATOS</td>
                        <td scope="row">SIN DATOS</td>
                        <td scope="row">SIN DATOS</td>
                        <td scope="row">SIN DATOS</td>
                        <td scope="row">SIN DATOS</td>
                @endif
                @if($general_eyc->certificados==null)
                <td scope="row">SIN FECHA ASIGNADA</td>
                @else
                <td scope="row">{{$general_eyc->certificados->Fecha_calibracion}}</td>
                @endif

                        <td>
                            <div class="btn-group">
                                <button class="btn btn-warning btnEditarUsuario" data-toggle="modal" data-target="#modalEditarInventario" idInventario=""><i class="fas fa-pencil-alt" aria-hidden="true"aria-hidden="true"></i></button>
                                <button class="btn btn-danger btnEliminarUsuario" data-toggle="modal" data-target="#modalEliminarInventario" idInventario=""><i class="fa fa-times" aria-hidden="true"></i></button>     
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box -->
      
    <!-- modal agregar usuario -->
    <div id="modalAgregarEquipo" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form role="form" method="post" enctype="multipart/form-data">
                    <div class="modal-header" style="background:#3c8dbc; color:white">
                        <h5 class="modal-title">Agregar datos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <!-- nombre -->
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" class="form-control input-lg" name="nuevoNombre" placeholder="Ingresar nombre" required>
                                </div>             
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


</form>
        @stop

