<!DOCTYPE html>
<html>
<head>
    <title>FOR-PCVE-01/05</title>
    <style>
        @page {
            margin: 45px 25px;
            counter-reset: page; /* Inicializa el contador de páginas */
        }
        .page-num-container {
            position: fixed; /* Fija la posición del contenedor */
            bottom: 20px;   /* Ajusta la posición vertical */
            right: 20px;    /* Ajusta la posición horizontal */
            font-size: 10px;
            text-align: right;
        }
        /* Ajusta la celda al texto en los datos generales */
        .datosGeneralesCortos{
            font-weight: bold;
            width: 100px;
            /*font-family: Arial;*/
            font-size: 11px;
            text-align: center;
        }
        .respuestasGenerales{
            /*font-family: Arial;*/
            font-size: 11px;
            text-align: center;
        }
        /* borde para tabla-yacziry*/
        /*.tablaManifiesto{
            border: 0px solid black; 
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 11px;
        }*/
        .tablaheader {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaheader td, .tablaheader th {
            border: 1px solid black; 
            font-size: 11px;
        }
        .tablaManifiesto {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaManifiesto td, .tablaManifiesto th {
            border: .6px solid black; 
            font-size: 11px;
        }
        .tablaManifiesto2 {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaManifiesto2 td, .tablaManifiesto2 th {
            border: .6px solid black; 
            font-size: 11px;
        }
        .notas{
            text-align: left;
            font-size: 11px;
        }
        .letraRoja{
            color: #c02302;
            font-weight: bold;
        }
        .celdaAzul{
            background-color: #00356d;
        }
        .letraBlanca{
            color: #ffffff;
            font-weight: bold;
        }
        .celdaCrema{
            background-color: #F8CBAD;
        }
        /*oculta todo el borde de la tabla*/
        .sinBorde{
            border: 0px;
            text-align: center;
            border-collapse: collapse;
            text-align: center;
            width: 100%;
        }
        .tablaContainer {
            margin-top: 10px; /* Ajusta el valor según la altura de tu header */
        }
        body {
            padding-top: 60px; /* Ajusta el valor según la altura de tu header */
            font-family: 'arial', sans-serif;
        }
        /*muestra solo la linea inferior de la celda*/
        .lineaInferior{
            border-bottom: 1px solid black;
            font-weight: bold;
            /*font-family: Arial;*/
            font-size: 11px;
            text-align: center;
        }
        .Contenido{
            /*font-family: Arial;*/
            font-size: 10px !important; /* Forzar el tamaño de letra */
            text-align: center;
        }
        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 60px;
            display: flex;
            align-items: center;
            padding: 0 0px;
            z-index: 1000; /* Asegura que el header esté por encima */
        }
        footer {
            position: sticky;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 100px; /*400*/
        }
        .altoCelda{
            height: 70px;
        }
        .especial {
            width: 20px; /* Ajusta el ancho de esta celda específica */
        }
        .saltoBlanco{
            height: 15px;
        }
        .logo{
            width: 20px;
            height: 15;
        }
    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th class="">Formato</th>
                <th class="respuestasGenerales">Código:</th>
                <th class="respuestasGenerales">FOR-PCVE-01/05</th>
                <th rowspan="3"><img class="logo" src="{{ $Logo }}" alt="Logo" style="width: 50px; height: auto;"></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td rowspan="2"> Manifiesto de Salida y/o Resguardo</td>
                <td class="respuestasGenerales">Versión</td>
                <td class="respuestasGenerales">3</td>
            </tr>
            <tr>
                <td class="respuestasGenerales">Página</td>
                <td class="page-num-container">
                </td>
            </tr>
        </tbody>
    </table>
</header>

<div class="tablaContainer">
    <table class="sinBorde">
        <tbody>
            <tr>
                <td class="datosGeneralesCortos">Cliente:</td>
                <td class="lineaInferior"><label for="">{{ $Manifiesto->Cliente }}</label></td>
                <td class="datosGeneralesCortos">Folio:</td>
                <td class="lineaInferior">{{ $Manifiesto->Folio }}</td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">Destino:</td>
                <td class="lineaInferior">{{ $Manifiesto->Destino }}</td>
                <td class="datosGeneralesCortos">Fecha de salida:</td>
                <td class="lineaInferior">{{ $Solicitud->formatted_date }}</td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">Trabajo: </td>
                <td class="lineaInferior">{{ $Manifiesto->Trabajo }}</td>
                <td class="datosGeneralesCortos">Puesto:</td>
                <td class="lineaInferior">{{ $Manifiesto->Puesto }}</td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">Responsable:</td>
                <td class="lineaInferior" colspan="3">{{ $Manifiesto->Responsable }}</td>
            </tr>
        </tbody>
    </table>
</div>
<br>
<div class="">
    <table class="tablaManifiesto">
        <thead>
            <tr>
                @if($Manifiesto->SATBMPRO == 'SI')
                    <th class="celdaAzul letraBlanca" colspan="9">Equipos</th>
                    @else
                    <th class="celdaAzul letraBlanca" colspan="7">Equipos</th>
                @endif
            </tr>
            @if($Manifiesto->SATBMPRO == 'SI')
                    <tr class="celdaCrema">
                        <th class="especial">No.</th>
                        <th class="">Descripción</th>
                        <th class="" style="width: 60px;">No. ECO</th>
                        <th class="">No. De Serie</th>
                        <th class="">SAT</th>
                        <th class="">BMPRO</th>
                        <th class="">Marca</th>
                        <th class="">Modelo</th>
                        <th class="">Comentarios</th>
                    </tr>
                @else
                    <tr class="celdaCrema">
                        <th class="especial">No.</th>
                        <th class="">Descripción</th>
                        <th class="" style="width: 60px;">No. ECO</th>
                        <th class="">No. De Serie</th>
                        <th class="">Marca</th>
                        <th class="">Modelo</th>
                        <th class="">Comentarios</th>
                    </tr>
            @endif         
        </thead>
    <tbody>
        @php
            $contador = 1; // Inicializa el contador
            $minFilas = 5; // Define el número mínimo de filas
        @endphp
            @foreach ($DetallesSolicitud as $detalle)
                    @php
                        $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                    @endphp
                    @if($general->Tipo != 'CONSUMIBLES')
                        @if($Manifiesto->SATBMPRO == 'SI')
                                    <tr>
                                        <td class="Contenido">{{ $contador }}</td>
                                        <td class="Contenido">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->No_economico ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Serie ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->SAT ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->BMPRO ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Marca ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Modelo ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Comentario ?? 'N/A' }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="Contenido">{{ $contador }}</td>
                                        <td class="Contenido">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->No_economico ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Serie ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Marca ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Modelo ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Comentario ?? 'N/A' }}</td>
                                    </tr>
                        @endif

                        @php
                            $contador++; // Incrementa el contador
                        @endphp
                    @endif
            @endforeach

            @for($i = $contador; $i <= $minFilas; $i++)
                <tr>
                    @if($Manifiesto->SATBMPRO == 'SI')
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                    @else
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                        <td class="Contenido">----</td>
                    @endif
                </tr>
            @endfor
        </tbody>
    </table>
<br>
    <table class="tablaManifiesto2">
        <thead>
            <tr>
                @if($Manifiesto->SATBMPRO == 'SI')
                    <th class="celdaAzul letraBlanca" colspan="7">ADICIONAL (accesorio, consumible, y/o herramientas)</th>
                    @else
                    <th class="celdaAzul letraBlanca" colspan="5">ADICIONAL (accesorio, consumible, y/o herramientas)</th>
                @endif
            </tr>
            @if($Manifiesto->SATBMPRO == 'SI')
                    <tr class="celdaCrema">
                        <th class="especial">No.</th>
                        <th class="">Cantidad</th>
                        <th class="">Unidad</th>
                        <th class="">SAT</th>
                        <th class="">BMPRO</th>
                        <th class="" colspan="7">Descripción</th>
                    </tr>
                @else
                    <tr class="celdaCrema">
                        <th class="especial">No.</th>
                        <th class="">Cantidad</th>
                        <th class="">Unidad</th>
                        <th class="" colspan="5">Descripción</th>
                    </tr>
            @endif
        </thead>
        <tbody>
            @php
                $contador = 1; // Inicializa el contador
                $minFilas = 5; // Define el número mínimo de filas
                $hayConsumibles = $DetallesSolicitud->contains(function($detalle) use ($generalEyC) {
                    $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                    return $general && $general->Tipo == 'CONSUMIBLES';
                });
            @endphp

            @if($hayConsumibles)
                @foreach ($DetallesSolicitud as $detalle)

                    @php
                        $general = $generalEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                    @endphp

                    @if($general && $general->Tipo == 'CONSUMIBLES')
                        @if($Manifiesto->SATBMPRO == 'SI')
                            <tr>
                                <td class="Contenido">{{ $contador }}</td>
                                <td class="Contenido">{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                <td class="Contenido">{{ $detalle->Unidad ?? 'N/A' }}</td>
                                <td class="Contenido">{{ $general->SAT ?? 'N/A' }}</td>
                                <td class="Contenido">{{ $general->BMPRO ?? 'N/A' }}</td>
                                <td class="Contenido" colspan="7">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="Contenido">{{ $contador }}</td>
                                <td class="Contenido">{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                <td class="Contenido">{{ $detalle->Unidad ?? 'N/A' }}</td>
                                <td class="Contenido" colspan="4">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                            </tr>
                        @endif

                        @php
                            $contador++; // Incrementa el contador
                        @endphp

                    @endif

                @endforeach

                @for($i = $contador; $i <= $minFilas; $i++)
                    <tr>
                        @if($Manifiesto->SATBMPRO == 'SI')
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido" colspan="7">----</td>
                        @else
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido">----</td>
                            <td class="Contenido" colspan="4">----</td>
                        @endif
                    </tr>
                @endfor
            @else
                @for($i=0;$i<=5;$i=$i+1)
                    <tr>
                        @if($Manifiesto->SATBMPRO == 'SI')
                            
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido" colspan="7">----</td>
                            @else
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido">----</td>
                                <td class="Contenido" colspan="4">----</td>
                        @endif
                    </tr>
                @endfor
            @endif
        </tbody>

    </table>
</div>
    <table class="tablaManifiesto">
        <tr>
            <td class="notas" colspan="4"><strong class="letraRoja">Nota a):</strong> Los Equipos se entregan en las siguientes condiciones: limpios,  operables para su uso y quedan al resguardo del firmante, siendo su responsabilidad de cada uno de los equipos aquí mencionados, excepto de los consumibles. Se deberá mantener en buen estado y que NO sea deteriorado por condiciones ajenas a su fin establecido. En caso de extravío o daño injustificado se tendrá que justificar el percance ocurrido a través de un reporte  dirigido al  PCVE, para determinar  la Reposición  del Equipo/ y/o accesorio. <br>
                <strong class="letraRoja">Nota b):</strong> El responsable y/o la persona que recibe el equipo y adicionales de este manifiesto se compromete con el cuidado del mismo. <br>
                <strong class="letraRoja">Nota c):</strong> Si se requiere adjuntar más información en el campo de obsevaciones se puede agregar otra página adicional o escribir en la parte de atrás del formato.
            </td>
        </tr>
    </table>
        <footer>
            <div class="">
                <table class="tablaManifiesto">
                    <thead>
                        <tr>
                            <th class="celdaAzul letraBlanca" colspan="3">SALIDA DE EQUIPOS Y ADICIONALES</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="saltoBlanco"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="2" class="Contenido" class="altoCelda">{{ $nombre }}</td>
                            <td rowspan="2" class="Contenido" class="altoCelda">{{ $Solicitud->tecnico }}</td>
                            <td class="celdaAzul letraBlanca">Obsevaciones</td>
                        </tr>
                        <tr>
                            <td class="altoCelda">{{ $Manifiesto->Observaciones }}</td>
                        </tr>
                        <tr>
                            <td>Entrega: Nombre/cargo/firma</td>
                            <td>Recibe: Nombre/cargo/firma </td>
                            <td rowspan="2"></td>
                        </tr>
                        
                        <tr>
                            <td class="celdaAzul letraBlanca" colspan="3">RETORNO DE EQUIPOS Y ADICIONALES</td>
                        </tr>
                        <tr>
                        <td colspan="2">Fecha de devolución a las instalaciones de AICO S.C. :
                            @if($Devolucion && $Devolucion->formatted_date != '')
                                {{ $Devolucion->formatted_date }}
                            @else
                                
                            @endif
                        </td>

                            <td></td>
                        </tr>
                        <tr>
                            <td class="celdaAzul letraBlanca">¿Los equipos retornan en optimas condiciones?</td>
                                <td>
                                @if($Devolucion && $Devolucion->Condiciones != '')
                                        @if($Devolucion->Condiciones == 'SI')
                                            SI <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp; X &nbsp;&nbsp;</span>&nbsp;
                                            NO <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                            N/A <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                @elseif($Devolucion->Condiciones == 'NO')
                                            SI <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                            NO <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp; X &nbsp;&nbsp;</span>&nbsp; 
                                            N/A <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                @else
                                            SI <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                            NO <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp; 
                                            N/A <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp; X &nbsp;&nbsp;</span>
                                        @endif
                                    @else
                                    SI <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
                                    NO <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp; 
                                    N/A <span style="border-bottom: 1px solid black; padding-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                @endif
                                </td>
                            <td class="celdaAzul letraBlanca">Observaciones</td>
                        </tr>
                        <tr>
                            @if($Devolucion && $Devolucion->Entrega != '' && $Devolucion->Recibe != '' && $Devolucion->Observaciones != '')
                                    <td td class="Contenido" class="altoCelda">{{ $Devolucion->Entrega }}</td>
                                    <td td class="Contenido" class="altoCelda">{{ $Devolucion->Recibe }}</td>
                                    <td td class="Contenido" class="altoCelda" rowspan="2">{{ $Devolucion->Observaciones }}</td>
                                @else
                                    <td td class="Contenido" class="altoCelda"></td>
                                    <td td class="Contenido" class="altoCelda"></td>
                                    <td td class="Contenido" class="altoCelda" rowspan="2"></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Entrega: Nombre/cargo/firma</td>
                            <td>Recibe: Nombre/cargo/firma </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </footer>
    </body>
</html>