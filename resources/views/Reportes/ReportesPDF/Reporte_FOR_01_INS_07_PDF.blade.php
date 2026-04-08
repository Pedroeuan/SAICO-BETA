<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-07/01</title>
            <style>
                @page {
                    margin: 
                    3.0cm /* superior */
                    2.1cm /* derecho */
                    2.1cm /* inferior */
                    2.4cm; /* izquierdo */
                }
                .content { margin-top: 6px; }

                header {
                    position: fixed;
                    top: -40px; /* Ajusta para que no interfiera con el margen de la página */
                    left: 0;
                    right: 0;
                    height: auto; /* Permite que el header crezca dinámicamente */
                    text-align: center;
                    /*background-color:rgb(226, 45, 45); /* Fondo para que sea visible */
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px; /* Ajusta la posición */
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    /*background-color: rgb(7, 231, 18)/* Fondo para que sea visible */
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin-top: 2.5px;
                    padding-top: 0;
                    padding-bottom: 0;
                    font-family: 'arial', sans-serif;;
                    /*background-color:rgb(45, 78, 226); /* Fondo para que sea visible */
                }
                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 6px !important;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                    font-size: 6px;
                }
                    
                .simbologia {
                    border-collapse: collapse;  /*separate No colapsar bordes */
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 5px;
                }

                .simbologia td, .simbologia th {
                    border: .6px solid black; 
                }
                .celdaAmarillo{
                    background-color: #FFF2CC;
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

        .encabezadoAzul{
            text-align: center;
            width: 100%;
            font-size: 7px;
            background-color: #2F75B5;
            color: #ffffff;
            outline: 1px double #000000; /* Contorno externo */
        }

        .border {
            border: 1px solid black; 
        }
            
        .datosinspeccion{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 6px;
        }

        .datosinspeccion td, .datosinspeccion th {
            border: .6px solid black; 
        }
        

        .datosinspeccionsinborde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 6px;
        }

        .datosresultados{
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 10px;
            }
        .datosresultados td, .datosresultados th {
            border: .6px solid black;
        }
        .datosresultados .sinBordeth th{
            border: 0 !important;
        }
        .datosresultados td.long-wrap{
            border: 0 !important;
            padding: 0 !important;
        }

        .long-wrap{
            border: none !important;
            padding: 0 !important;
        }

        .long-box{
            width: 36%;
            margin-left: auto;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .long-box td{
            border: .6px solid black !important;
            font-weight: bold;
            text-align: center;
        }


        .celdaGris{
            background-color: #DBDBDB;
            font-size: 6px;
        }

        .celdaGrisResultados{
            background-color: #DBDBDB;
            font-size: 9px;
        }

        .juntas{
            font-size: 9px;
        }
        
        .sinBordetdth td, .sinBordetdth th {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
        }
        
        .sinBordetd td {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
        }

        .sinBordeth th {
            border: 0px !important;
            text-align: left;
            border-collapse: collapse;
            width: 100%;
        }
        .rotar-texto-dividido {
            text-align: center; /* Centra el texto horizontalmente */
            padding: 0;
            display: inline-block; /* Necesario para la rotación */
            transform: rotate(270deg); /* Rota solo el texto */
            white-space: normal;
        }

        .rotar-texto-sin-dividir {
            text-align: center; /* Centra el texto horizontalmente */
            padding: 0;
            display: inline-block; /* Necesario para la rotación */
            transform: rotate(270deg); /* Rota solo el texto */
            white-space: nowrap; /* Evita que el texto se divida en varias líneas */
            max-width: 20px; /* Ajusta al ancho máximo deseado */
        }
        /* agrega/ajusta en tu <style> */
        .tablaheader th{
            border: 1px solid black;
            text-align: center;
            vertical-align: middle;
        }
        .pagina-cell{
            text-align: center !important;
            vertical-align: middle !important;
            white-space: nowrap;
            padding: 0;
        }
        .logo-cell{
            padding: 0;
        }
        .logo-cell img{
            display: block;
            margin: 0 auto;
            width: 80px;
            height: auto;
        }

            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th style="width: 70%;">Código:</th>
                            <th style="width: 90%;">FOR-INS-07/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES </th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th class="pagina-cell">{{ $TextoPagina ?? '' }}</th>

                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 0px;"></div>
            </header>
                          
            <footer>
                    <br>

                    <table class="datosgenerales">                               
                        <tr>                                     
                            <th>OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 606.5px;">{{ $Datos_Equipo['Observaciones'] }}</td>                            
                        </tr>                      
                    </table>

                    <br>
                                                
                    <table class="datosgenerales">
                        <thead>
                            @if( $numFirmas == 2)
                            <!-- 2 Firmas -->
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 30px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                </tr>
                            @elseif( $numFirmas == 3)
                            <!-- 3 Firmas -->
                                <tr>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                                    <td style="width: 20px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 200px; height:20px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:20px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:20px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                                </tr>
                            @else
                            <!-- 4 Firmas -->
                                <tr>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo3'] }}</th>
                                    <td style="width: 15px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 150px; height:40px" class="lineaInferior"></td>
                                    <th></th>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] }}</strong></td>
                                    <th></th>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] }}</strong></td>
                                    <th></th>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] }}</strong></td>
                                    <th></th>
                                </tr>
                            @endif
                        </thead>                            
                    </table>
            </footer>

            @foreach ($Grupo_Juntas_Detalles_Re as $grupo)
            <div class="content">
                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="4">DATOS GENERALES</th>
                    </tr>
                </table>   
                <div style="margin-bottom: 1px;"></div>         
                <table class="datosgenerales">
                    <tbody>
                        <tr>
                            <th style="width: 12%;">FECHA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Fecha'] }}</td>
                            <th style="width: 15%;">NO. REPORTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['No_Reporte'] }}</td>
                        </tr>
                        <tr>
                            <th>CLIENTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Cliente'] }}</td>
                            <th>CONTRATO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Contrato'] }}</td>
                        </tr>
                        <tr>
                            <th>PROYECTO: </th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Proyecto'] }}</td>
                        </tr>
                        <tr>
                            <th>ORDEN DE TRABAJO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Orden_Trabajo'] }}</td>
                        </tr>
                        <tr>
                            <th>FOLIO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Folio'] }}</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Partida'] }}</td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Lugar'] }}</td>
                            <th>ISOMETRICO/PLANO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Isometrico_Plano'] }}</td>
                        </tr>
                        <tr>
                            <th>PIEZA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Pieza'] }}</td>
                            <th>MATERIAL:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Material'] }}</td>
                        </tr>
                        <tr>
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Procedimiento'] }}</td>
                            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Criterio_Evaluacion'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                @php
                    $accesorioSonda2 = $Datos_Equipo['ACCESORIO_SONDA2'] ?? '---';
                    $marcaSonda2 = $Datos_Equipo['MARCA_SONDA2'] ?? '---';
                    $modeloSonda2 = $Datos_Equipo['MODELO_SONDA2'] ?? '---';
                    $serieSonda2 = $Datos_Equipo['N_S_SONDA2'] ?? '---';
                    $frecSonda2 = $Datos_Equipo['FREC_SONDA2'] ?? '---';

                    $blockSensibilidad = $Datos_Equipo['BLOCK_SENSIBILIDAD'] ?? '---';
                    $marcaBlockSen = $Datos_Equipo['MARCA_BLOCK_SEN'] ?? '---';
                    $modeloBlockSen = $Datos_Equipo['MODELO_BLOCK_SEN'] ?? '---';
                    $serieBlockSen = $Datos_Equipo['N_S_BLOCK_SEN'] ?? '---';

                    $blockDistancia = $Datos_Equipo['BLOCK_DISTANCIA'] ?? '---';
                    $marcaBlockDis = $Datos_Equipo['MARCA_BLOCK_DIS'] ?? '---';
                    $modeloBlockDis = $Datos_Equipo['MODELO_BLOCK_DIS'] ?? '---';
                    $serieBlockDis = $Datos_Equipo['N_S_BLOCK_DIS'] ?? '---';
                @endphp

                <table class="datosinspeccion">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DEL EQUIPO</th></tr>
                    </thead>

                    <thead>
                        <tr class="sinBordeth"><th colspan="7"></th></tr>
                    </thead> <!-- Fila vacia -->

                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th style="width: 25%;">ACOPLANTE</th>
                            <th colspan="4">SONDA #1</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                            <td>{{ $Datos_Equipo['ACOPLANTE'] }}</td>
                            <th class="celdaGris" style="width: 12%;">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_SONDA1'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris">LONGITUD DEL CABLE</th>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{ $Datos_Equipo['MODELO_SONDA1'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['N_S_EQUIPO'] }}</td>
                            <td>{{ $Datos_Equipo['LONGITUD_CABLE'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['N_S_SONDA1'] }}</td>
                            <th class="celdaGris">FRECC:</th>
                            <td>{{ $Datos_Equipo['FREC_SONDA1'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="4">SONDA #2</th>
                            <th colspan="4">BLOCK DE CALIBRACIÓN (SENSIBILIDAD)</th>
                            <th colspan="4">BLOCK DE CALIBRACIÓN (DISTANCIA)</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 8%;">ACCESORIOS:</th>
                            <td colspan="3">{{ $accesorioSonda2 }}</td>
                            <th class="celdaGris" style="width: 8%;">BLOCK:</th>
                            <td colspan="3">{{ $blockSensibilidad }}</td>
                            <th class="celdaGris" style="width: 8%;">BLOCK:</th>
                            <td colspan="3">{{ $blockDistancia }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MARCA:</th>
                            <td colspan="3">{{ $marcaSonda2 }}</td>
                            <th class="celdaGris">MARCA:</th>
                            <td colspan="3">{{ $marcaBlockSen }}</td>
                            <th class="celdaGris">MARCA:</th>
                            <td colspan="3">{{ $marcaBlockDis }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $modeloSonda2 }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $serieSonda2 }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $modeloBlockSen }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $serieBlockSen }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $modeloBlockDis }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $serieBlockDis }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">FREC:</th>
                            <td colspan="3">{{ $frecSonda2 }}</td>
                            <td colspan="8"></td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="9">AJUSTE DEL EQUIPO</th>
                    </tr>
                </table>

               {{-- FOR-01-INS-07 | AJUSTE DEL EQUIPO (versión robusta como en 08) --}}
                @php
                    $ganancia  = $Datos_Equipo['GANANCIA'] ?? '---';
                    $tipoJunta = $Datos_Equipo['TIPO_JUNTA'] ?? ($Datos_Equipo['TIP_JUNTA'] ?? '---');
                    $rechazo   = $Datos_Equipo['RECHAZO'] ?? ($Datos_Equipo['RANGO'] ?? '---');
                    $diametro  = $Datos_Equipo['DIAMETRO'] ?? '---';
                    $retardo   = $Datos_Equipo['RETARDO'] ?? '---';
                    $espesor   = $Datos_Equipo['ESPESOR'] ?? '---';
                @endphp

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr class="">
                            <th style="width: 100px;">GANANCIA:</th>
                            <td class="lineaInferior">{{ $ganancia }}</td><td style="text-align: left; width: 2%;"> dB </td>
                            <th style="width: 100px;">TIPO DE JUNTA:</th>
                            <td class="lineaInferior">{{ $tipoJunta }}</td>
                            <th rowspan="3" style="width: 100px;">
                                <img class=""
                                    src="{{ $FOR_01_INS_07 ?? public_path('images/FOR-01-INS-07.png') }}"
                                    alt="FOR_01_INS_07"
                                    style="width: 80px; height: auto;">
                            </th>
                        </tr>
                        <tr class="">
                            <th>RECHAZO:</th>
                            <td class="lineaInferior">{{ $rechazo }}</td><td></td>
                            <th>DIAMETRO:</th>
                            <td class="lineaInferior">{{ $diametro }}</td>
                        </tr>
                        <tr class="">
                            <th>RETARDO:</th>
                            <td class="lineaInferior">{{ $retardo }}</td><td></td>
                            <th>ESPESOR:</th>
                            <td class="lineaInferior">{{ $espesor }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="encabezadoAzul">
                        <tr>
                            <th colspan="9">RESULTADOS</th>
                        </tr>
                </table>
                    <table class="datosresultados">
                        <thead>
                            <tr class="celdaGris">
                                <th style="width: 30px;">Junta / Elemento</th>
                                <th style="width: 40px;">No. IndIcación</th>
                                <th style="width: 30px;">Ang(°)</th>
                                <th style="width: 30px;">NR (%)</th>
                                <th style="width: 30px;">NI (%)</th>
                                <th style="width: 30px;">LA (in)</th>
                                <th style="width: 30px;">LC (in)</th>
                                <th style="width: 30px;">PA <br>Distancia frente a la zapata</th>
                                <th style="width: 30px;">SA (in)</th>
                                <th style="width: 30px;">DA(prof.)</th>
                                <th style="width: 30px;">HT</th>
                                <th style="width: 30px;">Evaluación</th>
                                <th style="width: 30px;">FOTOS</th>
                            </tr>
                        
                        </thead>

                                <tbody>
                                    {{-- 🔹 TÍTULO --}}
                                @if (!str_starts_with($grupo['titulos_juntas'], 'SIN TITULO'))
                                    <tr class="titulo-row">
                                        <td colspan="13" style="border:.5px solid black;">
                                            {{ $grupo['titulos_juntas'] }}
                                        </td>
                                    </tr>
                                @endif

                                {{-- 🔹 FILAS DEL BLOQUE --}}
                                @foreach ($grupo['resultados'] as $junta)
                                    <tr class="juntas">
                                        <td>{{ $junta['junta_ele'] }}</td>
                                        <td>{{ $junta['no_indicacion'] }}</td>
                                        <td>{{ $junta['angulo'] }}</td>
                                        <td>{{ $junta['nr'] }}</td>
                                        <td>{{ $junta['ni'] }}</td>
                                        <td>{{ $junta['la'] }}</td>
                                        <td>{{ $junta['lc'] }}</td>
                                        <td>{{ $junta['dist_zapata'] }}</td>
                                        <td>{{ $junta['sa'] }}</td>
                                        <td>{{ $junta['da'] }}</td>
                                        <td>{{ $junta['ht'] }}</td>
                                        <td>{{ $junta['evaluacion'] }}</td>
                                        <td>{{ $junta['fotos'] }}</td>
                                    </tr>
                                @endforeach

                                {{-- 🔹 LONGITUD INSPECCIONADA --}}
                               {{-- DESPUÉS --}}
                                <tr>
                                    <td colspan="9" style="border:.6px solid black; text-align:left; font-size:8px; padding:4px 6px;">
                                        <b>NPIR</b>= No Presenta Indicaciones Relevantes, <b>SC</b>= Soldadura Circunferencial,
                                        <b>SA</b>= Distancia Angular, <b>HT</b>= Horario técnico, <b>SL</b>= Soldadura Longitudinal,
                                        <b>LA</b>= Largo Axial, <b>LC</b>= Largo Circunferencial, <b>DA</b>= Profundidad
                                    </td>
                                    <th colspan="3" style="border:.6px solid black; font-weight:bold; text-align:center;">
                                        Longitud inspeccionada:
                                    </th>
                                    <th colspan="1" style="border:.6px solid black; font-weight:bold; text-align:center;">
                                        {{ $grupo['Long_Inspecc'][0] ?? '---' }} m
                                    </th>
                                </tr>

                                {{-- 🔹 SALTO DE PÁGINA POR BLOQUE 
                                <tr style="page-break-after: always;" class="sinBordetd">
                                    <td colspan="13"></td>
                                </tr>--}}
                            </tbody>
                    </table>
            </div>
            @if (!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        </body>
    </html>
