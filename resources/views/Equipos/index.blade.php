
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
                        <th>Disponibilidad</th>
                        <th>Fecha calibración</th>
                        <th>Foto/F.T/H.P</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificados as $general_eyc)
                        <tr>
                        @if($general_eyc)
                            <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                            <td scope="row">{{$general_eyc->No_economico}}</td>
                            <td scope="row">{{$general_eyc->Marca}}</td>
                            <td scope="row">{{$general_eyc->Modelo}}</td>
                            <td scope="row">{{$general_eyc->Serie}}</td>
                            <td scope="row">{{$general_eyc->Disponibilidad_Estado}}</td>
                        @else
                            <td scope="row">SIN DATOS</td>
                            <td scope="row">SIN DATOS</td>
                            <td scope="row">SIN DATOS</td>
                            <td scope="row">SIN DATOS</td>
                            <td scope="row">SIN DATOS</td>
                            <td scope="row">SIN DATOS</td>
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
                        @if ($general_eyc->Foto != 'ESPERA DE DATO')
                        <!-- Agrega esto en tu archivo de vista Equipos.edit -->                                                
                            <a href="{{ asset('storage/' . $general_eyc->Foto) }}" target="_blank">VER FOTO</a> 
                        @elseif($general_eyc->Foto == 'ESPERA DE DATO')  
                            <a target="_blank">SIN FOTO/H.P/F.T</a>                                              
                        @endif
                            </td>
                            <td>
                    @endif
                        <div class="btn-group">
                            <a href="{{ route('edicion.editEyC', ['id' => $general_eyc->idGeneral_EyC]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                            <button type="button" class="btn btn-danger btnEliminarEquipo" idGeneral_EyC="{{$general_eyc->idGeneral_EyC}}"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </div>
                    </td>

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

   // Inicializar el DataTable al cargar la página
   $(document).ready(function() {
       initializeDataTable();
   });

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
            // Enviar la solicitud DELETE al servidor
            $.ajax({
                url: '/eliminar/destroyEquipos/' + idGeneral_EyC, // URL del endpoint de eliminación
                type: 'DELETE', // Método HTTP DELETE
                data: {
                    _token: '{{ csrf_token() }}' // Token CSRF si es necesario
                },
                success: function(response) {
                    // Manejar la respuesta del servidor si es necesario
                    if (response.success) {
                        // Si la eliminación fue exitosa, hacer algo (por ejemplo, recargar la página)
                        location.reload();
                    } else {
                        // Si ocurrió un error durante la eliminación, mostrar un mensaje de error
                        Swal.fire("Error!", "No se pudo eliminar el elemento.", "error");
                    }
                },
                error: function() {
                     // Manejar errores de la solicitud AJAX
                    //Swal.fire("Error!", "No se pudo eliminar el elemento.2", "error");
                    Swal.fire({
                        title: "Confirmado!",
                        text: "Equipo Eliminado Correctamente!",
                        icon: "success",
                        didClose: function() {
                            location.reload();
                            }
                        });
                    // Esperar 3 segundos (3000 milisegundos) antes de recargar la página
                      /*  setTimeout(function() {
                            location.reload();
                        }, 3000);*/
                }
            });
        } else if (result.isDenied) {
            Swal.fire("Cancelado", "", "error");
        }
    });
});

</script>

@endsection