<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-07/01</title>
            <style>
                @page {
                    margin: 
                    3.0cm /* superior */ 
                    2.1cm /* derecho */
                    2.1cm /* inferior */
                    2.4cm; /* izquierdo */
                }
                header {
                    position: fixed;
                    top: -55px; /* Ajusta para que no interfiera con el margen de la página */
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
                    margin-top: 25px; /* Ajusta para que el contenido no se sobreponga al header */
                    /*margin: 0;*/
                    padding-top: 0px; /* Altura del header */
                    padding-bottom: 0; /* Altura del footer */
                    font-family: 'arial', sans-serif;
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
            border: 5px !important;
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
        .marca-tipo-cell{
            height: 14px;
            padding-top: 3px;
            vertical-align: middle;
        }
        .acoplante-cell{
            min-height: 28px;
            padding-top: 8px;
            vertical-align: middle;
        }

            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th rowspan="4" style="width: 400%; font-size: 9pt;">
                                INFORME DE MEDICION DE ESPESORES DE PARED EN LA TUBERIA Y ELEMENTOS ESTRUCTURALES
                            </th>

                            <th rowspan="4" style="width:90%; padding:0; margin:0;">
                                @if(!empty($QR_PDF))
                                    <div style="width:100%; height:7.2%; text-align:center; vertical-align:middle; padding:0; margin:0;">
                                        <img
                                            src="{{ $QR_PDF }}"
                                            alt="QR"
                                            style="width:70px; height:70px; display:block; margin:auto; padding:0;"
                                        >
                                    </div>
                                @endif
                            </th>

                            <th style="width: 60%;">Código:</th>
                            <th style="width: 100%;">FOR-PINS-07/01</th>

                            <th rowspan="4" style="width:80%; padding:0; margin:0;">
                                <div style="width:100%; height:7.2%; text-align:center; vertical-align:middle; padding:0; margin:0;">
                                    <img
                                        src="{{ $Logo }}"
                                        alt="Logo"
                                        style="width:50px; height:50px; display:block; margin:auto; padding:0;"
                                    >
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th>Versión</th>
                            <th>3</th>
                        </tr>
                        <tr>
                            <th style="width: 90%;">Fecha de elaboración</th>
                            <th>28-may-26</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </header>

            <footer>
                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 605px;">{{ $Datos_Equipo['Observaciones'] }}</td>                            
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
                            @elseif( $numFirmas == 4)
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
            
            @foreach ($Grupo_Juntas_Detalles_Re as $bloque)
            <div class="content">
                <table class="datosgenerales">

                    <thead class="encabezadoAzul">

                        <tr><th colspan="4">DATOS GENERALES</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr>
                            <th style="width: 12%;">FECHA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
                            <th style="width: 15%;">NO. REPORTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>CLIENTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
                            <th>CONTRATO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>PROYECTO: </th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>ORDEN DE TRABAJO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>FOLIO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Lugar'] ?? '' }}</td>
                            <th>ISOMETRICO/PLANO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Isometrico_Plano'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>PIEZA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Pieza'] ?? '' }}</td>
                            <th>MATERIAL:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Material'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
                            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</td>
                        </tr>
                    </tbody>

                </table>
                
                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="9">DATOS DEL EQUIPO</th></tr>
                    </thead>

                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>

                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th colspan="4">TRANSDUCTOR</th>
                            <th colspan="2">BLOCK DE REFERENCIA</th>
                            <th>ACOPLANTE:</th>
                        </tr>

                        <tr>
                            <th class="celdaGris">MARCA:</th>
                            <td>{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '---' }}</td>

                            <th class="celdaGris">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_TRANSDUCTOR'] ?? '---' }}</td>

                            <th class="celdaGris">MARCA:</th>
                            <td>{{ $Datos_Equipo['MARCA_BLOCK'] ?? '---' }}</td>

                            <td>{{ $Datos_Equipo['ACOPLANTE'] ?? '---' }}</td>
                        </tr>

                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '---' }}</td>

                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{$Datos_Equipo['MODELO_TRANSDUCTOR'] ?? '---' }}</td>

                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] ?? '---' }}</td>
                            <th class="celdaGris">LOGITUD DEL CABLE:</th>
                        </tr>

                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['N_S_EQUIPO'] ?? '---' }}</td>

                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['N_S_TRANSDUCTOR'] ?? '---' }}</td>

                            <th class="celdaGris">FRECC:</th>
                            <td>{{ $Datos_Equipo['FREC_TRANSDUCTOR'] ?? '---' }}</td>

                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['N_S_BLOCK'] ?? '---' }}</td>

                            <td>{{ $Datos_Equipo['LONGITUD'] ?? ($Datos_Equipo['LONG_CABLE'] ?? '---') }}</td>

                        </tr>                
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccionsinborde">

                    <thead class="encabezadoAzul">

                        <tr><th colspan="9">DATOS DE LA INSPECCIÓN</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="9"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr>
                            <th style="width: 90px;">GANANCIA:</th>
                            <td class="lineaInferior" style="width: 184px;">{{ $Datos_Equipo['GANANCIA'] }}</td>
                            <th style="width: 110px;">RANGO:</th>
                            <td class="lineaInferior" style="width: 184px;">{{ $Datos_Equipo['RANGO'] }}</td>
                            <th style="width: 120px;">RECHAZO:</th>
                            <td class="lineaInferior" style="width: 184px;">{{ $Datos_Equipo['RECHAZO'] }}</td>
                        </tr>
                        <tr>
                            <th>PRESION DE OPERACIÓN:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRES_OPE'] }}</td>
                            <th>PRESION MAXIMA DE OPERACION:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRES_MX_OPE'] }}</td>
                            <th>TEMPERATURA MAX. DE OPERACION:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMP_MX_OPE'] }}</td>
                        </tr>
                        <tr>
                            <th>CONDICION SUPERFICIAL:</th>
                            <td colspan="2" class="lineaInferior">{{ $Datos_Equipo['COND_SUPER'] }}</td>
                            <th>ESTADO DE PINTURA:</th>
                            <td colspan="2" class="lineaInferior">{{ $Datos_Equipo['PINTURA'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                    <table class="datosresultados">
                    
                        <thead class="encabezadoAzul">
                            <tr><th colspan="25">RESULTADOS</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="25"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                                <tr class="celdaGris">
                                    <th style="width: 20px;">ID</th>
                                    <th style="width: 50px;">Descripción del Elemento</th>
                                    <th style="width: 20px;">Ønom</th>
                                    <th style="width: 20px;">Øext.</th>
                                    <th style="width: 20px;">Nivel</th>
                                    <th style="width: 20px;">12:00</th>
                                    <th style="width: 20px;">01:00</th>
                                    <th style="width: 20px;">01:30</th>
                                    <th style="width: 20px;">02:00</th>
                                    <th style="width: 20px;">03:00</th>
                                    <th style="width: 20px;">04:00</th>
                                    <th style="width: 20px;">04:30</th>
                                    <th style="width: 20px;">05:00</th>
                                    <th style="width: 20px;">06:00</th>
                                    <th style="width: 20px;">07:00</th>
                                    <th style="width: 20px;">07:30</th>
                                    <th style="width: 20px;">08:00</th>
                                    <th style="width: 20px;">09:00</th>
                                    <th style="width: 20px;">10:00</th>
                                    <th style="width: 20px;">10:30</th>
                                    <th style="width: 20px;">11:00</th>
                                    <th style="width: 40px;">Tmin</th>
                                    <th style="width: 40px;">Tmax</th>
                                    <th style="width: 40px;">Tprom</th>
                                    <th style="width: 50px;">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bloque as $item)
                                            @if (!is_array($item))
                                                @continue
                                            @endif

                                            {{-- TITULO --}}
                                            @if (($item['tipo'] ?? null) == 'titulo')
                                                <tr class="titulo-row">
                                                    <td colspan="25" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                <tr class="juntas">
                                                    <td>{{ $item['data']['ID'] ?? '' }}</td>
                                                    <td>{{ $item['data']['elemento'] ?? '' }}</td>
                                                    <td>{{ $item['data']['Ønom'] ?? '' }}</td>
                                                    <td>{{ $item['data']['Øext'] ?? '' }}</td>
                                                    <td>{{ $item['data']['nivel'] ?? '' }}</td>
                                                    <td>{{ $item['data']['12_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['01_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['01_30'] ?? '' }}</td>
                                                    <td>{{ $item['data']['02_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['03_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['04_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['04_30'] ?? '' }}</td>
                                                    <td>{{ $item['data']['05_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['06_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['07_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['07_30'] ?? '' }}</td>
                                                    <td>{{ $item['data']['08_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['09_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['10_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['10_30'] ?? '' }}</td>
                                                    <td>{{ $item['data']['11_00'] ?? '' }}</td>
                                                    <td>{{ $item['data']['tmin'] ?? '' }}</td>
                                                    <td>{{ $item['data']['tmax'] ?? '' }}</td>
                                                    <td>{{ $item['data']['tprom'] ?? '' }}</td>
                                                    <td>{{ $item['data']['observaciones'] ?? '' }}</td>
                                                </tr>
                                            @endif

                                            {{-- LONGITUD --}}
                                            @if (($item['tipo'] ?? null) == 'longitud')
                                                <tr class="sinBordetd">
                                                    <td colspan="20"></td>
                                                    <th colspan="4">Longitud inspeccionada:</th>
                                                    <th>{{ $item['valor'] ?? '' }} m</th>
                                                </tr>
                                            @endif

                                @endforeach
                            </tbody>
                        </table>    
                    </div>
                    @if (!$loop->last)
                        <div style="page-break-after: always;"></div>
                    @endif
            @endforeach
        </body>
    </html>
