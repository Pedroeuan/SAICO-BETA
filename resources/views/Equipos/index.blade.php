
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
                        <th>Foto</th>
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

                <td scope="row"> 
                    @if ($general_eyc->Foto != 'N/A')
                  <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                    <a href="{{ asset('storage/' . $general_eyc->Foto) }}" target="_blank">VER FOTO</a> 
                    @elseif($general_eyc->Foto == 'N/A')  
                    <a target="_blank">SIN FOTO</a>                                              
                    @endif
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('editEquipos', ['general_eyc' => $general_eyc->idGeneral_EyC]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                        <form id="delete-form-{{ $general_eyc->idGeneral_EyC }}" action="{{ route('equipos.destroy', ['id' => $general_eyc->idGeneral_EyC]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                        </form>

                        <a href="#" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $general_eyc->idGeneral_EyC }}').submit();" class="btn btn-danger" role="button">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        
                        
                        <!-Yacziry-->
                          @php
                          //Pedro
                          //<a href="{{ route('equipos.destroy', ['id' => $general_eyc->idGeneral_EyC]) }}" class="btn btn-danger" role="button"><i class="fa fa-times" aria-hidden="true"></i></a>
                        //<button type="button" class="btn btn-danger btnEliminarEquipo" idGeneral_EyC="{{$general_eyc->idGeneral_EyC}}"><i class="fa fa-times" aria-hidden="true"></i></button>
                        @endphp
                    </div>
                </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</form>


</a>

@stop

@section('js')
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let dataTable;

    function initializeDataTable() {
        // Destruir el DataTable si ya está inicializado
        if ($.fn.DataTable.isDataTable('#tablaJs')) {
            dataTable.destroy();
        }
        // Inicializar el DataTable
        dataTable = new DataTable('#tablaJs');
    }

    // Inicializar el DataTable al cargar la página
    $(document).ready(function() {
        initializeDataTable();
    });

    // Función borrar 
    $(document).on("click", ".btnEliminarEquipo", function(){
        // Valor del id a eliminar
        var idGeneral_EyC = $(this).attr("idGeneral_EyC");
        console.log(idGeneral_EyC);
        Swal.fire({
            title: "¿Seguro de eliminar este elemento?",
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: "Sí",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/equipos/${idGeneral_EyC}`, // Ruta del endpoint
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}' // Token CSRF
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire("Eliminado!", "", "success").then(() => {
                                // Destruir y reinicializar el DataTable después de la eliminación
                                initializeDataTable();
                            });
                        } else {
                            Swal.fire("Error!", "No se pudo eliminar el elemento.", "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error!", "No se pudo eliminar el elemento.", "error");
                    }
                });
            } else if (result.isDenied) {
                Swal.fire("Cancelado", "", "error");
            }
        });
    });
</script>

<!--
<script>
    // funcion borrar 
    $(".btnEliminarEquipo").on("click", function(){
        //valor del id a eliminar
        var idGeneral_EyC = $(this).attr("idGeneral_EyC");
        console.log(idGeneral_EyC);
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
</script>-->

@endsection

