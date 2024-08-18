
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
    <div class="box ">
        <div class="box-body">
        <h3 align="center">Inventario</h3>
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Num. Económico</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>NS</th>
                        <th>Stock</th>
                        <th>Disponibilidad</th>
                        <th>Fecha calibración</th>
                        <th>Hoja de Presentación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificadosConAlmacen as $general_eyc)
                        <tr>
                            @if($general_eyc)
                                <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                                <td scope="row">{{$general_eyc->No_economico}}</td>
                                <td scope="row">{{$general_eyc->Marca}}</td>
                                <td scope="row">{{$general_eyc->Modelo}}</td>
                                <td scope="row">{{$general_eyc->Serie}}</td>
                                <td scope="row">{{$general_eyc->almacen->Stock}}</td>
                                @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-success">Disponible <i class="fa fa-check" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-warning">No Disponible <i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-danger">Fuera de servicio <i class="fa fa-ban" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-info">Espera de Dato <i class="far fa-clock" aria-hidden="true"></i></td>
                                @endif
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
                                        <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>                                              
                                            @elseif($general_eyc->Foto == 'ESPERA DE DATO')  
                                        <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
                                    @endif
                                </td>
                            @endif
                            <td>
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
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.js"></script>

<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>

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

//$(".btnEliminarEquipo").on("click", function(){
$(document).on("click", ".btnEliminarEquipo", function() {
    //valor del id a eliminar
    var idGeneral_EyC = $(this).attr("idGeneral_EyC");

    Swal.fire({
        title: "Seguro de dar de BAJA este elemento?",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: "Sí",
        denyButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar la solicitud DELETE al servidor
            $.ajax({
                url: '/eliminar/BajaEyC/' + idGeneral_EyC, // URL del endpoint de eliminación
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
                        Swal.fire("Error!", "No se pudo dar de BAJA el elemento.", "error");
                    }
                },
                error: function() {
                     // Manejar errores de la solicitud AJAX
                    //Swal.fire("Error!", "No se pudo eliminar el elemento.2", "error");
                    Swal.fire({
                        title: "Confirmado!",
                        text: "Elemento DE BAJA Correctamente!",
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
        } 
        else if (result.isDenied) {
            Swal.fire("Cancelado", "", "error");
        }
    });
});
</script>

@endsection