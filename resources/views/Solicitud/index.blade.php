
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
<div class="box-header with-border">
    <button class="btn btn-success" data-toggle="modal" data-target="#modalSolicitarEyC">
    Solicitar
    </button>
</div>
<!-- form start -->
<form role="form">
    <div class="box">
        <h3 align="center">Inventario de equipos</h3>
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
                        <th>Destino</th>
                        <th>Fecha calibración</th>
                        <!--<th>Foto</th>-->
                        <th>Cantidad</th>
                        <th>Unidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">as</td>
                        <td scope="row">as</td>
                        <td scope="row">as</td>
                        <td scope="row">as</td>
                        <td scope="row">as</td>
                        <td scope="row">as</td>
                        <td scope="row">SIN DATOS</td>
                        <td>
                            <div class="row">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Cantidad">
                                </div>
                            </div>
                        </td>
                        <td>
                        <div class="row">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="Cantidad">
                                </div>
                            </div>
                        </td>
                    </tr>
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

