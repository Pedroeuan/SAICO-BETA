<!DOCTYPE html>
<html>
<head>
    <title>Manifiesto</title>
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
            font-family: Arial;
            font-size: 11px;
            text-align: center;
        }
        .respuestasGenerales{
            font-family: Arial;
            font-size: 11px;
            text-align: center;
            font-weight: bold; /* Negritas */
        }
        .respuestasGenerales2{
            font-family: Arial !important;
            font-size: 16px !important; 
            text-align: center;
            font-weight: bold; /* Negritas */
        }
        /* borde para tabla-yacziry*/
        /*.tablaManifiesto{
            border: 0px solid black; 
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 11px;
        }*/
        .Comentarios {
            border-collapse: separate;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: justify;
            font-size: 11px;
        }
        /* Aplica el borde a las celdas de la tabla */
        .Comentarios td, .Comentarios th {
            border: .6px solid black; 
            font-size: 11px;
        }
        .tablaheader {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
            font-family: Arial !important;
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
        .Firmas {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        .DentroFirmas {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .DentroFirmas td, .Firmas th {
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
            background-color: #9BC2E6;
        }
        .letraBlanca{
            color: #ffffff;
            font-weight: bold;
        }
        .letraNegra{
            color: #000000;
            font-weight: bold;
        }
        .celdaCrema{
            background-color: #FFF2CC;
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
            font-family: "Arial", sans-serif; /* Cambiar a Arial */
        }
        /*muestra solo la linea inferior de la celda*/
        .lineaInferior{
            border-bottom: 1px solid black;
            font-weight: bold;
            font-family: Arial;
            font-size: 11px;
            text-align: center;
        }
        .Contenido{
            font-family: Arial;
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

        <TR>
            <TD class="respuestasGenerales2" ALIGN=center ROWSPAN=3> Manifiesto / Resguardo de equipos,<br> herramientas y materiales.</TD>
            <TD class="respuestasGenerales">Código:</TD>
            <TD>FOR-01-PRO-EQ-01</TD>
            <TD rowspan="3"><img class="logo" src="{{ $Logo }}" alt="Logo" style="width: 50px; height: auto;"></TD>
        </TR>
        <TR>
            <TD class="respuestasGenerales">Versión:</TD>
            <TD>0</TD>
            
        </TR>
        <TR>
            <TD class="respuestasGenerales">Fecha de emisión:</TD>
            <TD>00/00/0000</TD>
        </TR>

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
                <td class="datosGeneralesCortos">Sitio de Trabajo:</td>
                <td class="lineaInferior">{{ $Manifiesto->Destino }}</td>
                <td class="datosGeneralesCortos"></td>
                <td></td>
            </tr>
            <tr>
                <td class="datosGeneralesCortos">Servicio: </td>
                <td class="lineaInferior">{{ $Manifiesto->Trabajo }}</td>
                <td class="datosGeneralesCortos"></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<br>
<div class="">
    <table class="tablaManifiesto">
        <thead>
            @if($Manifiesto->Cliente == 'PROTEXA' || $Manifiesto->Cliente == 'PERMADUCTO' || $Manifiesto->Cliente == 'PROPETROL')
                    <tr class="celdaAzul">
                        <th class="especial">No.</th>
                        <th class="">Cantidad</th>
                        <th class="">Unidad</th>
                        <th class="">Descripción</th>
                        <th class="">No. ECO</th>
                        <th class="">No. De Serie</th>
                        <th class="">Marca</th>
                        <th class="">Modelo</th>
                        <th class="">SAT</th>
                        <th class="">BMPRO</th>
                    </tr>
                @else
                    <tr class="celdaAzul">
                    <th class="especial">No.</th>
                        <th class="">Cantidad</th>
                        <th class="">Unidad</th>
                        <th class="">Descripción</th>
                        <th class="">No. ECO</th>
                        <th class="">No. De Serie</th>
                        <th class="">Marca</th>
                        <th class="">Modelo</th>
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
                        @if($Manifiesto->Cliente == 'PROTEXA' || $Manifiesto->Cliente == 'PERMADUCTO' || $Manifiesto->Cliente == 'PROPETROL')
                                    <tr>
                                        <td class="Contenido">{{ $contador }}</td>
                                        <td class="Contenido">{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $detalle->Unidad ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->No_economico ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Serie ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Marca ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Modelo ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->SAT ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->BMPRO ?? 'N/A' }}</td>
                                    </tr>
                                @else
                                    <tr>
                                        <td class="Contenido">{{ $contador }}</td>
                                        <td class="Contenido">{{ $detalle->Cantidad ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $detalle->Unidad ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Nombre_E_P_BP ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->No_economico ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Serie ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Marca ?? 'N/A' }}</td>
                                        <td class="Contenido">{{ $general->Modelo ?? 'N/A' }}</td>
                                    </tr>
                        @endif

                        @php
                            $contador++; // Incrementa el contador
                        @endphp
                    @endif
            @endforeach

            @for($i = $contador; $i <= $minFilas; $i++)
                <tr>
                    @if($Manifiesto->Cliente == 'PROTEXA' || $Manifiesto->Cliente == 'PERMADUCTO' || $Manifiesto->Cliente == 'PROPETROL')
                        <td class="Contenido">----</td>
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
                        <td class="Contenido">----</td>
                    @endif
                </tr>
            @endfor
        </tbody>
    </table>
<br>

@if ( $Manifiesto->Observaciones == '' && $Devolucion->Observaciones == '')
        <table class="Comentarios">
            <tr>
                <td >Comentarios:     
            </td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
            <tr>
                <td><br></td>
            </tr>
        </table>
    @else
        <table class="Comentarios">
            <tr>
                <td >Comentarios:
                    @if ($Manifiesto->Observaciones != '' )
                            Salida: {{$Manifiesto->Observaciones}}
                        @else
                    @endif

                    @if($Devolucion->Observaciones != '')
                            Devolución: {{ $Devolucion->Observaciones }}
                        @else
                    @endif
                </td>
            </tr>     
        </table>
@endif
    <br>

</div>
    <table class="tablaManifiesto">
        <tr>
            <td class="notas" colspan="4">Nota a): Los Equipos se entregan en las siguientes condiciones: limpios,  operables para su uso.<br>
            y quedan al resguardo del firmante, siendo su responsabilidad de cada uno de los equipos aquí mencionados, excepto de los consumibles. Se deberá mantener en buen estado y que NO sea deteriorado por condiciones ajenas a su fin establecido. En caso de extravío o daño injustificado se tendrá que justificar el percance ocurrido a través de un reporte  dirigido al  PCVE, para determinar  la Reposición  del Equipo/ y/o accesorio.<br>
            Nota b): El responsable y/o la persona que recibe el equipo y adicionales de este manifiesto se compromete con el cuidado del mismo.<br>
            Nota c): Si se requiere adjuntar más información en el campo de obsevaciones se puede agregar otra página adicional o escribir en la parte de atrás del formato.
            </td>
        </tr>
    </table>
<br>
        <footer>
            
            <div class="">

                <table class="Firmas">
                    <tr>
                        <td>

                            <table class="DentroFirmas">
                                <tr class="celdaAzul">
                                    <td COLSPAN=2 class="letraNegra">Entrega</td>
                                </tr>
                                <tr class="celdaAzul">
                                    <td class="letraNegra">Nombre</td>
                                    <td>{{ $nombre }}</td>
                                </tr>
                                <tr class="celdaAzul">
                                    <td class="letraNegra">Firma</td>
                                    <td></td>
                                </tr>
                            </table>

                        </td>
                        <td>
                            
                        <table class="DentroFirmas">
                                <tr class="celdaAzul">
                                    <td COLSPAN=2 class="letraNegra">Autoriza</td>
                                </tr>
                                <tr class="celdaAzul">
                                    <td class="letraNegra">Nombre</td>
                                    <td>{{ $Manifiesto->Responsable }} </td>
                                </tr>
                                <tr class="celdaAzul">
                                    <td class="letraNegra">Firma</td>
                                    <td></td>
                                </tr>
                        </table>

                        </td>
                    </tr>
                    <br>
                        <tr>
                            <td>

                            <table class="DentroFirmas">
                                <tr class="celdaCrema">
                                    <td COLSPAN=2 class="letraNegra">Fecha de Recepción</td>
                                    <td> {{ $Solicitud->formatted_date }} </td>
                                </tr>
                                <tr class="celdaCrema">
                                    <td class="letraNegra">Nombre</td>
                                    <td COLSPAN=2>{{ $Solicitud->tecnico }}</td>
                                </tr>
                                <tr class="celdaCrema">
                                    <td class="letraNegra">Firma</td>
                                    <td COLSPAN=2></td>
                                </tr>
                            </table>
                            
                            </td>
                            <td>

                            <table class="DentroFirmas">
                                <tr class="celdaCrema">
                                    <td COLSPAN=2 class="letraNegra">Fecha Devolución</td>
                                    <td> {{ $Devolucion->formatted_date }} </td>
                                </tr>
                                <tr class="celdaCrema">
                                    <td class="letraNegra">Nombre</td>
                                    <td COLSPAN=2>{{ $Devolucion->Recibe }}</td>
                                </tr>
                                <tr class="celdaCrema">
                                    <td class="letraNegra">Firma</td>
                                    <td COLSPAN=2></td>
                                </tr>
                            </table>

                            </td>
                        </tr>
                </table>

            </div>

        </footer>
    </body>
</html>