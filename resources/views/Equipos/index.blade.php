
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
    .toggle-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }

    .toggle-container label {
        font-size: 12px;
        cursor: pointer;
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
        <h3 align="center">Inventario</h3>
        <h6 align="center">Filtro</h6>
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
                <div class="mb-3 text-center toggle-container">
                <label><input type="checkbox" class="toggle-col" data-col="0" checked>Categoria</label>
                <label><input type="checkbox" class="toggle-col" data-col="1" checked>ID</label>
                <label><input type="checkbox" class="toggle-col" data-col="2" checked>Nombre</label>
                <label><input type="checkbox" class="toggle-col" data-col="3" checked>Marca</label>
                <label><input type="checkbox" class="toggle-col" data-col="4" checked>Modelo</label>
                <label><input type="checkbox" class="toggle-col" data-col="5" checked>NS</label>
                <label><input type="checkbox" class="toggle-col" data-col="6" checked>Lote</label>
                <label><input type="checkbox" class="toggle-col" data-col="7" checked>Stock</label>
                <label><input type="checkbox" class="toggle-col" data-col="8" checked>Disponibilidad</label>
                <label><input type="checkbox" class="toggle-col" data-col="9" checked>Ubicación</label>
                <label><input type="checkbox" class="toggle-col" data-col="10" checked>Factura</label>
                <label><input type="checkbox" class="toggle-col" data-col="11" checked>No.Certificado</label>
                <label><input type="checkbox" class="toggle-col" data-col="12" checked>Certificado</label>
                <label><input type="checkbox" class="toggle-col" data-col="13" checked>Prox. Fecha Calibración/Caducidad</label> <!--10 -->
                <label><input type="checkbox" class="toggle-col" data-col="14" checked>Días Restantes Cal</label>
                <label><input type="checkbox" class="toggle-col" data-col="15" checked>Mantenimiento Preventivo</label>
                <label><input type="checkbox" class="toggle-col" data-col="16" checked>Intervalo de Tiempo</label>
                <label><input type="checkbox" class="toggle-col" data-col="17" checked>Numero de Reporte</label>
                <label><input type="checkbox" class="toggle-col" data-col="18" checked>Fecha Mantenimiento</label>
                <label><input type="checkbox" class="toggle-col" data-col="19" checked>Prox. Fecha Mantenimiento</label>
                <label><input type="checkbox" class="toggle-col" data-col="20" checked>Días Restantes Man</label>
                <label><input type="checkbox" class="toggle-col" data-col="21" checked>Presentación</label>
                <label><input type="checkbox" class="toggle-col" data-col="22" checked>Planos</label>
                <label><input type="checkbox" class="toggle-col" data-col="23" checked>Editar</label>
                <label><input type="checkbox" class="toggle-col" data-col="24" checked>Baja</label>
                <br>
                <label><input type="checkbox" id="checkEquipos"> Equipos</label>
                <label style="margin-left:20px;"><input type="checkbox" id="checkBlock"> Block</label>
                <label style="margin-left:20px;"><input type="checkbox" id="checkAccesorios"> Accesorios</label>
                <label style="margin-left:20px;"><input type="checkbox" id="checkHerramientas"> Herramientas</label>
                <label style="margin-left:20px;"><input type="checkbox" id="checkConsumibles"> Consumibles</label>
                <label style="margin-left:20px;"><input type="checkbox" id="checkMes"> Mes Actual</label>

            </div>
            <table id="tablaJs" class="table table-bordered table-striped dt-responsive tablas">
                <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Numero/ID</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>NS</th>
                        <th>Lote</th>
                        <th>Stock</th>
                        <th>Disponibilidad</th>
                        <th>Ubicación</th>
                        <th>Factura</th>
                        <th>No.Certificado</th>
                        <th>Certificado</th>
                        <th>Prox.Fecha Calibración/Caducidad</th>
                        <th>Días Restantes Cal</th>
                        <th>Mantenimiento Preventivo</th>
                        <th>Intervalo de Tiempo</th>
                        <th>Numero de Reporte</th>
                        <th>Fecha Mantenimiento</th>
                        <th>Prox.Fecha Mantenimiento</th>
                        <th>Días Restantes Man</th>
                        <th>Presentación</th>
                        <th>Planos</th>
                        <th>Editar</th>
                        <th>Baja</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($generalConCertificadosConAlmacenConISOConClasificacion as $general_eyc)
                        <tr>
                                <!-- Categoria --> 
                                <td scope="row">@if($general_eyc->Tipo === 'BLOCK Y PROBETA') BLOCK @else {{ $general_eyc->Tipo}} @endif</td>
                                <!-- Numero/ID --> 
                                <td scope="row">{{$general_eyc->No_economico}}</td>
                                <!-- Nombre --> 
                                <td scope="row">{{$general_eyc->Nombre_E_P_BP}}</td>
                                <!-- Marca --> 
                                <td scope="row">{{$general_eyc->Marca}}</td>
                                <!-- Modelo --> 
                                <td scope="row">{{$general_eyc->Modelo}}</td>
                                <!-- Serie --> 
                                <td scope="row">{{$general_eyc->Serie}}</td>
                                <!-- Lote --> 
                                <td scope="row">{{$general_eyc->almacen->Lote}}</td>
                                <!-- Stock --> 
                                <td scope="row">{{$general_eyc->almacen->Stock}}</td>
                                <!-- Disponibilidad --> 
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
                                <!-- Ubicación --> 
                                <td>{{ $general_eyc->lastHistorial->Tierra_Costafuera ?? 'FATIMA' }}</td>
                                <!-- Factura --> 
                                <td scope="row"> 
                                    @if ($general_eyc->Factura != 'ESPERA DE DATO')
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Factura) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>                                              
                                        @elseif($general_eyc->Factura == 'ESPERA DE DATO')
                                            <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
                                    @endif
                                </td>
                                <!-- No Certificado --> 
                                <td scope="row">{{$general_eyc->certificados->No_certificado}}</td>
                                <!-- Certificado Actual --> 
                                <td scope="row"> 
                                    @if ($general_eyc->certificados->Certificados_Actual != 'ESPERA DE DATO')
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->certificados->Certificados_Actual) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>                                              
                                        @elseif($general_eyc->certificados->Certificados_Actual == 'ESPERA DE DATO')
                                            <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
                                    @endif
                                </td> 
                                <!-- Fecha Calibración/Caducidad -->  
                                    @if($general_eyc->Tipo=='EQUIPOS' || $general_eyc->Tipo=='CONSUMIBLES' || $general_eyc->Tipo=='BLOCK Y PROBETA')
                                            @if($general_eyc->certificados->Fecha_calibracion == '2001-01-01')
                                                    <td scope="row" data-fecha="">SIN FECHA ASIGNADA</td>
                                                    <!-- Días Restantes -->  
                                                    <td scope="row">-</td>
                                                @elseif($general_eyc->Tipo=='CONSUMIBLES')
                                                    <!-- Caducidad -->
                                                    <td scope="row" data-fecha="{{$general_eyc->certificados->Fecha_calibracion}}">
                                                        {{$general_eyc->certificados->formatted_date}}
                                                    </td>
                                                    <!-- Días Restantes -->
                                                    <td scope="row">
                                                        {{ 
                                                        ( (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Fecha_calibracion), false) ) 
                                                            <= 0 ? 'CADUCADO' : 
                                                        (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Fecha_calibracion), false) 
                                                        }}
                                                    </td>
                                                @else
                                                    <!-- Prox.Fecha Calibración/Caducidad si no es Fecha_calibracion == '2001-01-01'-->
                                                    @if($general_eyc->certificados->Prox_fecha_calibracion == '2001-01-01')
                                                        <!-- Prox.Fecha Calibración/Caducidad -->
                                                        <td scope="row" data-fecha="">SIN FECHA ASIGNADA</td>
                                                        <!-- Días Restantes -->
                                                        <td scope="row">-</td>
                                                    @else
                                                        <!-- Prox.Fecha Calibración/Caducidad -->
                                                        <td scope="row" data-fecha="{{$general_eyc->certificados->Prox_fecha_calibracion}}">
                                                            {{$general_eyc->certificados->formatted_date2}}
                                                        </td>
                                                        <!-- Días Restantes -->
                                                        <td scope="row">
                                                            {{ 
                                                            ((int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Prox_fecha_calibracion), false)) 
                                                                <= 0 ? 'VENCIDO' : 
                                                            (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Prox_fecha_calibracion), false) 
                                                            }}
                                                        </td>
                                                    @endif
                                            @endif
                                        @else
                                            <!-- Fecha Calibración/Caducidad --> 
                                            <td scope="row" data-fecha="">N/A</td>
                                            <!-- Días Restantes --> 
                                            <td scope="row">N/A</td>
                                    @endif

                                    @if($general_eyc->iso->Frec_Cali_Mant_Prev == 'ESPERA DE DATO')
                                        <!-- Mantenimiento Preventivo --> 
                                        <td scope="row">NO ASIGNADO</td>
                                    @elseif($general_eyc->iso->Frec_Cali_Mant_Prev == 'N/A')
                                        <!-- Mantenimiento Preventivo --> 
                                        <td scope="row">N/A</td>
                                    @else
                                        <!-- Mantenimiento Preventivo -->
                                        <td scope="row">{{$general_eyc->iso->Frec_Cali_Mant_Prev}}</td>
                                    @endif

                                    @if($general_eyc->iso->Frec_Man_Inter_Time == 'N/A')
                                        <!-- INTERVALO DE TIEMPO --> 
                                        <td scope="row">N/A</td>
                                    @elseif($general_eyc->iso->Frec_Man_Inter_Time ==  'ESPERA DE DATO')
                                        <!-- INTERVALO DE TIEMPO --> 
                                        <td scope="row">SIN INTERVALO ASIGNADO</td>
                                    @else
                                        <!-- INTERVALO DE TIEMPO -->
                                        <td scope="row">{{$general_eyc->iso->Frec_Man_Inter_Time}}</td>
                                    @endif

                                    @if($general_eyc->equipos)
                                        @if($general_eyc->equipos->Num_Reporte == 'ESPERA DE DATO')
                                        <td scope="row">SIN REPORTE ASIGNADO</td>
                                        @else
                                            <!-- NUMERO DE REPORTE -->
                                            <td scope="row">{{$general_eyc->equipos->Num_Reporte}}</td>
                                        @endif
                                    @else
                                        <td scope="row">N/A</td>
                                    @endif
                                    @if($general_eyc->certificados->Fecha_mantenimiento == '2001-01-01')
                                        <!-- fecha de mantenimiento --> 
                                        <td scope="row" data-fecha="">SIN FECHA ASIGNADA / N/A</td>
                                    @else
                                        <!-- fecha de mantenimiento -->
                                        <td scope="row" data-fecha="{{$general_eyc->certificados->Fecha_mantenimiento}}">
                                            {{$general_eyc->certificados->formatted_date5}}
                                        </td>
                                    @endif

                                        <!-- Prox Fecha de Mantenimiento -->
                                    @if($general_eyc->certificados->Prox_fecha_mantenimiento == '2001-01-01')
                                        <!-- Proxima fecha de mantenimiento --> 
                                        <td scope="row" data-fecha="">SIN FECHA ASIGNADA / N/A</td>
                                        <!-- Días Restantes --> 
                                        <td scope="row">-</td>
                                    @else
                                        @if($general_eyc->certificados->Prox_fecha_mantenimiento == '2001-01-01')
                                            <!-- Proxima fecha de mantenimiento -->
                                            <td scope="row" data-fecha="">SIN FECHA ASIGNADA / N/A</td>
                                            <!-- Días Restantes --> 
                                            <td scope="row">-</td>
                                        @else
                                            <!-- Proxima fecha de mantenimiento -->
                                            <td scope="row" data-fecha="{{$general_eyc->certificados->Prox_fecha_mantenimiento}}">
                                                {{$general_eyc->certificados->formatted_date6}}
                                            </td>
                                            <!-- Días Restantes --> 
                                            <td scope="row">
                                                {{
                                                    ((int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Prox_fecha_mantenimiento), false) ) <= 0 ? 'VENCIDO' : 
                                                    (int) \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($general_eyc->certificados->Prox_fecha_mantenimiento), false) 
                                                }}
                                            </td>
                                        @endif
                                    @endif
                                    <!-- Presentación --> 
                                    <td scope="row"> 
                                    @if ($general_eyc->Foto != 'ESPERA DE DATO')
                                            <!-- Agrega esto en tu archivo de vista Equipos.edit -->  
                                            <a class="btn btn-primary" href="{{ asset('storage/' . $general_eyc->Foto) }}" role="button" target="_blank"><i class="far fa-file-pdf"></i></a>                                              
                                        @elseif($general_eyc->Foto == 'ESPERA DE DATO')
                                            <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>                                            
                                    @endif
                                    </td>
                                    <!-- Planos --> 
                                    <td scope="row">
                                    @if($general_eyc->Tipo == 'BLOCK Y PROBETA') 
                                        @if ($general_eyc->blocks?->Plano && $general_eyc->blocks?->Plano != 'ESPERA DE DATO')
                                            <a class="btn btn-primary"
                                            href="{{ asset('storage/' . $general_eyc->blocks->Plano) }}"
                                            target="_blank">
                                                <i class="far fa-file-pdf"></i>
                                            </a>
                                        @else
                                            <a target="_blank" class="btn btn-secondary" role="button"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                    </td>
                                    <!-- Editar --> 
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('edicion.editEyC', ['id' => $general_eyc->idGeneral_EyC]) }}" class="btn btn-warning" role="button"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                    </div>
                                </td>
                                    <!-- Baja --> 
                                <td>
                                <div class="btn-group">
                                        <button type="button" class="btn btn-info btnEliminarEquipo" idGeneral_EyC="{{$general_eyc->idGeneral_EyC}}"><i class="far fa-thumbs-down" aria-hidden="true"></i></button>
                                    </div>
                                </td>
                        </tr>
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
        columnDefs: [
        {
            targets: [6, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23], // columnas que quieres ocultar por default
            visible: false
        }
    ],
    layout: {
        topStart: {
            pageLength: true,
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Exportar a Excel',
                    title: 'Inventario',
                    exportOptions: {
                        //columns: [0,1,2,3,4,5,6,7,8,9,10,11,12]
                        //columns:':visible'
                        columns: ':visible',
                        modifier: {
                            search: 'applied',
                            order: 'applied'
                        }
                    }
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    title: 'Inventario',
                        exportOptions: {
                        columns: ':visible'
                    }
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
validarCheckMes();
// Sincronizar estado inicial
$('.toggle-col').each(function () {

    let colIndex = $(this).data('col');
    let isVisible = table.column(colIndex).visible();

    $(this).prop('checked', isVisible);

});

$('#checkMes').on('change', function () {

    let colCalibracion = table.column(13).visible();
    let colFechaMant = table.column(18).visible();
    let colProxMant = table.column(19).visible();

    // Validar si al menos una está visible
    if (!colCalibracion && !colFechaMant && !colProxMant) {

        // Quitar el check automáticamente
        $(this).prop('checked', false);

        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debes seleccionar al menos una columna de fechas (Calibración o Mantenimiento) para usar el filtro de Mes Actual.'
        });

        return;
    }

    // Si pasa la validación, redibuja la tabla
    table.draw();
});
//Detectar cambios en los checkboxes
$('#checkEquipos, #checkBlock, #checkAccesorios , #checkHerramientas , #checkConsumibles').on('change', function () {
    table.draw();
});
// Filtro personalizado
$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {

    let mostrarEquipos = $('#checkEquipos').is(':checked');
    let mostrarBlock = $('#checkBlock').is(':checked');
    let mostrarAccesorios = $('#checkAccesorios').is(':checked');
    let mostrarHerramientas = $('#checkHerramientas').is(':checked');
    let mostrarConsumibles = $('#checkConsumibles').is(':checked');
    let filtrarMes = $('#checkMes').is(':checked');

    let categoria = data[0];

    // FILTRO POR CATEGORIA
    let pasaCategoria = (
        (mostrarEquipos && categoria.includes('EQUIPOS')) ||
        (mostrarBlock && categoria.includes('BLOCK')) ||
        (mostrarAccesorios && categoria.includes('ACCESORIOS')) ||
        (mostrarHerramientas && categoria.includes('HERRAMIENTAS')) ||
        (mostrarConsumibles && categoria.includes('CONSUMIBLES'))
    );

    // Si NO hay ningún checkbox seleccionado → mostrar todo
    if (!mostrarEquipos && !mostrarBlock && !mostrarAccesorios && !mostrarHerramientas && !mostrarConsumibles) {
        pasaCategoria = true;
    }

    if (!pasaCategoria) return false;

    if (filtrarMes) {

        let hoy = new Date();

        // Columnas de fechas
        //let columnasFecha = [10, 15, 16];
        let columnasFecha = [13, 18, 19];

        // Filtrar solo las que estén visibles (opcional pero recomendado)
        columnasFecha = columnasFecha.filter(idx => table.column(idx).visible());

        let coincide = columnasFecha.some(idx => {

            let celda = table.cell(dataIndex, idx).node();
            if (!celda) return false;

            let fechaStr = celda.dataset.fecha;
            if (!fechaStr) return false;

            let fecha = new Date(fechaStr);

            return (
                fecha.getMonth() === hoy.getMonth() &&
                fecha.getFullYear() === hoy.getFullYear()
            );
        });

        return coincide;
    }
    return true;
});

function validarCheckMes() {
    /*let colCalibracion = table.column(10).visible();
    let colFechaMant = table.column(15).visible();
    let colProxMant = table.column(16).visible();*/
    let colCalibracion = table.column(13).visible();
    let colFechaMant = table.column(18).visible();
    let colProxMant = table.column(19).visible();

    let habilitar = colCalibracion || colFechaMant || colProxMant;

    $('#checkMes').prop('disabled', !habilitar);

    if (!habilitar) {
        $('#checkMes').prop('checked', false);
    }
}

// Ejecutar cuando cambian columnas
$(document).on('change', '.toggle-col', function () {
    let column = table.column($(this).data('col'));
    column.visible($(this).is(':checked'));

    validarCheckMes();
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