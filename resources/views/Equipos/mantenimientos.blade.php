
@extends('adminlte::page')

@section('title', 'Inventario')


@section('css')

<style>
    #tablaJs td {
        text-align: center; /* Centra el contenido horizontalmente */
    }
    #tablaJs th {
        text-align: center; /* Centra el texto del encabezado horizontalmente */
    }    
    #my-notification .dropdown-menu {
    max-height: 200px; /* Ajusta la altura según sea necesario */
    overflow-y: auto;
    }
    
    .tablaheader {
        border-collapse: collapse; 
        border-spacing: 0px;        /* Espacio entre celdas */
        width: 100%;
        text-align: center;
        font-size: 10px;
        }
                    
        /* Aplica el borde a las celdas de la tabla */
        .tablaheader th {
        /*width: 70%;*/
        border: 1px solid black; 
    }

    .dataTables_wrapper {
        overflow-x: auto;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .box {
        display: flex;
        justify-content: center;
    }

    .box-body {
        display: inline-block;
    }

    #tablaJs {
        width: auto !important;
    }
</style>
@endsection

@section('content')
<br>  
<br>
<br>
<!-- form start -->
    <div class="box">
        <div class="box-body d-flex justify-content-center">
        <div style="display: inline-block;">
        <h3 align="center">Mantenimientos</h3>
        <!--<div class="table-responsive">-->
                <!--<table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 70%;">FORMATO</th>
                            <th style="width: 10%;">Código:</th>
                            <th style="width: 10%;">FOR-PCVE-01/04</th>
                            <th rowspan="3"><img src="{{ asset('images/Logo_AICO_R.png') }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE INSPECCIÓN CON LÍQUIDOS PENETRANTES </th>
                            <th>Versión</th>
                            <th>3</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table> -->

            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Nombre</th>
                        <th>Numero/ID</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>NS</th>
                        <th>Lote</th>
                        <th>Stock</th>
                        <th>Disponibilidad</th>
                        <th>Ubicación</th>
                        <th>Prox.Fecha Mantenimiento</th>
                        <th>Días Restantes</th>
                        <th>Presentación</th>
                        <!--<th>Editar</th>
                        <th>Baja</th>-->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificadosConAlmacenConISOConClasificacion as $general_eyc)
                    @if($general_eyc->Tipo === 'EQUIPOS')
                        <tr>
                            @if($general_eyc)
                                <td scope="row">@if($general_eyc->Tipo === 'BLOCK Y PROBETA') BLOCK @else {{ $general_eyc->Tipo}} @endif</td>
                                <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                                <td scope="row">{{$general_eyc->No_economico}}</td>
                                <td scope="row">{{$general_eyc->Marca}}</td>
                                <td scope="row">{{$general_eyc->Modelo}}</td>
                                <td scope="row">{{$general_eyc->Serie}}</td>
                                <td scope="row">{{$general_eyc->almacen->Lote}}</td>
                                <td scope="row">{{$general_eyc->almacen->Stock}}</td>
                                @if($general_eyc->Disponibilidad_Estado=='DISPONIBLE')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-success">Disponible<i class="fa fa-check" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='Equipo Disponible')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-success">Equipo Disponible<i class="fa fa-check" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='NO DISPONIBLE' )
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-warning">No Disponible<i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='Equipo Fuera de Servicio')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-warning">Equipo Fuera de Servicio<i class="fa fa-exclamation-triangle" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='FUERA DE SERVICIO/BAJA')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-danger">Fuera de servicio<i class="fa fa-ban" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='Equipo en Resguardo')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-danger">Equipo en Resguardo<i class="fa fa-ban" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='En Servicio')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-warning" style="color:#ff8800; border:1 px;">En Servicio <i class="far fa-clock" aria-hidden="true"></i></td>
                                    @elseif($general_eyc->Disponibilidad_Estado=='ESPERA DE DATO')
                                        <td scope="row"><button type="button" class="btn btn-block btn-outline-info">Espera de Dato<i class="far fa-clock" aria-hidden="true"></i></td>
                                @endif
                            @endif
                            <!-- Ubicación -->
                            <td>
                                {{ $general_eyc->lastHistorial->Tierra_Costafuera ?? 'FATIMA' }}
                            </td>
                            
                            @if($general_eyc->certificados)
                                    @if($general_eyc->Tipo=='EQUIPOS' || $general_eyc->Tipo=='CONSUMIBLES' || $general_eyc->Tipo=='BLOCK Y PROBETA')
                                            @if($general_eyc->certificados->Fecha_mantenimiento == '2001-01-01')
                                            <!-- Proxima fecha de mantenimiento --> 
                                                    <td scope="row">SIN FECHA ASIGNADA</td>
                                                    <!-- Días Restantes --> 
                                                    <td scope="row">-</td>
                                                @elseif($general_eyc->Tipo=='CONSUMIBLES')
                                                <!-- Proxima fecha de mantenimiento --> 
                                                    <td scope="row">{{$general_eyc->certificados->formatted_date}}</td>
                                                    <!-- Días Restantes --> 
                                                    <td scope="row">
                                                        {{ ( (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Fecha_mantenimiento), false) ) 
                                                            <= 0 ? 'CADUCADO' : 
                                                            (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Fecha_mantenimiento), false) }}
                                                    </td>
                                                @else
                                                    @if($general_eyc->iso->Fre_Cali_Mant_Prev == '2001-01-01')
                                                    <!-- Proxima fecha de mantenimiento -->
                                                    <td scope="row">SIN FECHA ASIGNADA</td>
                                                    <!-- Días Restantes --> 
                                                    <td scope="row">-</td>
                                                    @else
                                                        <td scope="row">{{$general_eyc->iso->formatted_date2}}</td>
                                                        <td scope="row">
                                                            {{ ( (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Prox_fecha_mantenimiento), false) ) 
                                                                <= 0 ? 'VENCIDO' : 
                                                                (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Prox_fecha_mantenimiento), false) }}
                                                        </td>
                                                    @endif
                                            @endif
                                        @else
                                            <td scope="row">N/A</td>
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
                            <!--<td>
                                <div class="btn-group">
                                    <a href="{{ route('edicion.editEyC', ['id' => $general_eyc->idGeneral_EyC]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                </div>
                            </td>
                            
                            <td>
                            <div class="btn-group">
                                    <button type="button" class="btn btn-info btnEliminarEquipo" idGeneral_EyC="{{$general_eyc->idGeneral_EyC}}"><i class="far fa-thumbs-down" aria-hidden="true"></i></button>
                                </div>
                            </td>
                        </tr>-->
                    @endif
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </div>

@stop

@section('js')
<!-- Incluye jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--datatable -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
<!-- Buttons DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.bootstrap5.min.js"></script>
<!-- Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<!-- (Opcional PDF y Print) -->
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
<!--<script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/v/bs5/jqc-1.12.4/dt-2.1.4/datatables.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<!--sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Incluir el script de sesión -->
<script src="{{ asset('js/session-handler.js') }}"></script>
<script>
    const updateNotificationUrl = "{{ url('notificaciones/update') }}";
    const viewAllNotificationsUrl = "{{ url('notificacion/index') }}";
</script>
<script src="{{ asset('js/notificaciones.js') }}"></script>
<script>

$('#tablaJs').on('draw.dt', function() {
    $('.table-responsive').scrollLeft(0);
});

let table = new DataTable('#tablaJs', {
    layout: {
        topStart: {
            pageLength: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Inventario',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
                    }
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    title: 'Inventario'
                }
            ]
        },
        topEnd: {
            search: true
        },
        bottomStart: {
            pageLength: true   // 👈 ESTE ES EL IMPORTANTE
        },
        bottomEnd: {
            paging: true
        }
    },
    language: {
        "decimal": "",
        "emptyTable": "No hay datos disponibles en la tabla",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "infoEmpty": "Mostrando 0 a 0 de 0 entradas",
        "infoFiltered": "(filtrado de _MAX_ entradas totales)",
        "lengthMenu": "Mostrar _MENU_ entradas",
        "search": "Buscar:",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
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