<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-08/01</title>
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
                    top: -38px; /* Ajusta para que no interfiera con el margen de la página */
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
                    font-size: 5px;
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

        .datosEquipos{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 6px;
        }

        .datosEquipos td, .datosEquipos th {
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
            border-collapse: separate;  /*separate; No colapsar bordes */ /*collapse; Fusiona los bordes de las celdas */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 10px;
            /*border : 1px solid black;*/
        }

        .datosresultados td, .datosresultados th {
            border: .1px solid black; /* Borde grueso de 2px */
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
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th rowspan="3" style="width:80%; padding:0; margin:0;">

                                <div style="
                                    width:100%;
                                    height:7.2%;
                                    text-align:center;
                                    vertical-align:middle;
                                    padding:0;
                                    margin:0;
                                ">
                                    @if(!empty($QR_PDF))
                                    <img
                                        src="{{ $QR_PDF }}"
                                        alt="QR"
                                        style="
                                            width:50px;
                                            height:50px;
                                            display:block;
                                            margin:auto;
                                            padding:0;
                                        "
                                    >
                                    @endif
                                </div>

                            </th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%;">FOR-INS-08/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">   INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ ANGULAR </th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 4px;"></div>

            </header>

            <footer> 

                    <div style="margin-bottom: 8px;"></div>

                        <table class="datosgenerales" style="width:100%;">
                            <tr>
                                <td style="width: 43%; vertical-align: top;">
                                    <table class="simbologia">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="celdaAmarillo" style="font-size:8px;">INDICACIONES Y HALLAZGOS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>NPIR:</strong></td>
                                                <td style="text-align:left;">NO PRESENTA INDICACIONES RELEVANTES</td>
                                                <td><strong>FP:</strong></td>
                                                <td style="text-align:left;">FALTA DE PENETRACIÓN</td>
                                            </tr>
                                            <tr>
                                                <td><strong>FF:</strong></td>
                                                <td style="text-align:left;">FALTA DE FUSIÓN</td>
                                                <td><strong>G:</strong></td>
                                                <td style="text-align:left;">GRIETAS</td>
                                            </tr>
                                            <tr>
                                                <td><strong>IE:</strong></td>
                                                <td style="text-align:left;">INCLUSIÓN DE ESCORIA</td>
                                                <td><strong>IVL:</strong></td>
                                                <td style="text-align:left;">INDICACIÓN VOLUMÉTRICA</td>
                                            </tr>
                                            <tr>
                                                <td><strong>IL:</strong></td>
                                                <td style="text-align:left;">INDICACIÓN LINEAL</td>
                                                <td><strong>IT:</strong></td>
                                                <td style="text-align:left;">INDICACIÓN TRANSVERSAL</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="width: 4%; border:0 !important;"></td>
                                <td style="width: 53%; vertical-align: top;">
                                    <table class="simbologia">
                                        <thead>
                                            <tr>
                                                <th colspan="6" class="celdaAmarillo" style="font-size:8px;">SIMBOLOGÍA DEL REPORTE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>A:</strong></td>
                                                <td style="text-align:left;">ÁNGULO (°)</td>
                                                <td><strong>NI:</strong></td>
                                                <td style="text-align:left;">NIVEL DE INDICACIÓN (%)</td>
                                                <td><strong>d</strong></td>
                                                <td style="text-align:left;">PROFUNDIDAD (PULG)</td>
                                            </tr>
                                            <tr>
                                                <td><strong>G:</strong></td>
                                                <td style="text-align:left;">GANANCIA (dB)</td>
                                                <td><strong>L:</strong></td>
                                                <td style="text-align:left;">LONGITUD DE LA INDICACIÓN (PULG)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><strong>NR:</strong></td>
                                                <td style="text-align:left;">NIVEL DE REFERENCIA (%)</td>
                                                <td><strong>DSR:</strong></td>
                                                <td style="text-align:left;">DISTANCIA DE LA INDICACIÓN</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><strong>S:</strong></td>
                                                <td style="text-align:left;">DISTANCIA ANGULAR (Pulg)</td>
                                                <td><strong>Tmin:</strong></td>
                                                <td style="text-align:left;">ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 814px;">{{ $Datos_Equipo['OBS'] }}</td>                           
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

                <table class="datosEquipos">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="9">DATOS DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="9"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th colspan="4">TRANSDUCTOR</th>
                            <th colspan="2">BLOCK DE REFERENCIA</th>
                            <th>ACOPLANTE</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_TR'] }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_BLOCK'] }}</td>
                            <td>{{ $Datos_Equipo['ACOPLANTE'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{ $Datos_Equipo['MODELO_TR'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] }}</td>
                            <th class="celdaGris" style="width: 100px;">LONGITUD DEL CABLE</th>
                        </tr>
                        <tr>
                            <th class="celdaGris">N.S. </th>
                            <td>{{ $Datos_Equipo['NS_EQUIPO'] }}</td>
                            <th class="celdaGris">N.S. </th>
                            <td style="width: 60px;">{{ $Datos_Equipo['NS_TR'] }}</td>
                            <th class="celdaGris" style="width: 50px;">FRECC:</th>
                            <td style="width: 50px;">{{ $Datos_Equipo['FREC_TR'] }}</td>
                            <th class="celdaGris">N.S. </th>
                            <td>{{ $Datos_Equipo['NS_BLOCK'] }}</td>
                            <td>{{ $Datos_Equipo['LONG_CABLE'] }}</td>
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
                            <th style="width: 11%; text-align:left;">GANANCIA:</th>
                            <td class="lineaInferior" style="width: 22%;">{{ $Datos_Equipo['GANANCIA'] ?? '---' }}</td>
                            <th style="width: 8%; text-align:left;">RANGO:</th>
                            <td class="lineaInferior" style="width: 23%;">{{ $Datos_Equipo['RANGO'] ?? '---' }}</td>
                            <th style="width: 9%; text-align:left;">RECHAZO:</th>
                            <td class="lineaInferior" style="width: 27%;">{{ $Datos_Equipo['RECHAZO'] ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">PRESIÓN DE OPERACIÓN:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRESION_OPERACION'] ?? '---' }}</td>
                            <th style="text-align:left;">PRESIÓN MÁXIMA DE OPERACIÓN:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRES_MAX_OPE'] ?? '---' }}</td>
                            <th style="text-align:left;">TEMPERATURA MAX. DE OPERACIÓN:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMP_MAX_OP'] ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">CONDICIÓN SUPERFICIAL:</th>
                            <td class="lineaInferior" colspan="2">{{ $Datos_Equipo['CONDICION_SUPERFICIAL'] ?? '---' }}</td>
                            <th style="text-align:left;">ESTADO DE PINTURA:</th>
                            <td class="lineaInferior" colspan="2">{{ $Datos_Equipo['ESTADO_PINTURA'] ?? '---' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                    <table class="datosresultados">
                        <thead class="encabezadoAzul">
                            <tr><th colspan="22">RESULTADOS</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="17"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                <th style="width: 30px;" rowspan="2">ID</th>
                                <th style="width: 40px;" rowspan="2">No. De Junta</th>
                                <th style="width: 30px;" colspan="2">Elemento de referencia</th>
                                <th style="width: 30px;" rowspan="2">Ø</th>
                                <th style="width: 30px;" rowspan="2">No.Ind.</th>
                                <th style="width: 30px;" rowspan="2">Tipo de Indicación</th>
                                <th style="width: 30px;" rowspan="2">A (°)</th>
                                <th style="width: 30px;" rowspan="2">G (dB)</th>
                                <th style="width: 30px;" rowspan="2">NR (%)</th>
                                <th style="width: 30px;" rowspan="2">NI (%)</th>
                                <th style="width: 30px;" colspan="2">DSR</th>
                                <th style="width: 30px;" rowspan="2">Horario Técnico</th>
                                <th style="width: 30px;" rowspan="2">No. De Pierna</th>
                                <th style="width: 30px;" rowspan="2">S</th>
                                <th style="width: 30px;" rowspan="2">L</th>
                                <th style="width: 30px;" rowspan="2">d</th>
                                <th style="width: 30px;" rowspan="2">tmin</th>
                                <th style="width: 30px;" rowspan="2">Evaluación</th>
                                <th style="width: 30px;" rowspan="2">Fotos No.</th>
                                <th style="width: 30px;" rowspan="2">Observaciones</th>
                            </tr>
                            <tr class="celdaGris">
                                <th style="width: 30px;">Lado A</th>
                                <th style="width: 30px;">Lado B</th>
                                <th style="width: 30px;">X</th>
                                <th style="width: 30px;">Y</th>
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
                                                    <td colspan="22" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                <tr class="juntas">
                                                    <td>{{ $item['data']['ID'] }}</td>
                                                    <td>{{ $item['data']['no_junta'] }}</td>
                                                    <td>{{ $item['data']['lado_a'] }}</td>
                                                    <td>{{ $item['data']['lado_b'] }}</td>
                                                    <td>{{ $item['data']['diametro'] }}</td>
                                                    <td>{{ $item['data']['no_indicacion'] ?? '' }}</td> 
                                                    <td>{{ $item['data']['tipo_indicacion'] }}</td>
                                                    <td>{{ $item['data']['Ang'] }}</td>
                                                    <td>{{ $item['data']['Gdb'] }}</td>
                                                    <td>{{ $item['data']['nr'] }}</td>
                                                    <td>{{ $item['data']['ni'] }}</td>
                                                    <td>{{ $item['data']['x'] }}</td>
                                                    <td>{{ $item['data']['y'] }}</td>
                                                    <td>{{ $item['data']['horario_tecnico'] }}</td>
                                                    <td>{{ $item['data']['no_pierna'] }}</td>
                                                    <td>{{ $item['data']['s'] }}</td>
                                                    <td>{{ $item['data']['l'] }}</td>
                                                    <td>{{ $item['data']['d'] }}</td>
                                                    <td>{{ $item['data']['tmin'] }}</td>
                                                    <td>{{ $item['data']['evaluacion'] }}</td>
                                                    <td>{{ $item['data']['fotos'] }}</td>
                                                    <td>{{ $item['data']['observaciones'] }}</td>
                                                </tr>
                                            @endif

                                            {{-- LONGITUD --}}
                                            @if (($item['tipo'] ?? null) == 'longitud')
                                                <tr class="sinBordetd">
                                                    <td colspan="17"></td>
                                                    <th colspan="4" style="font-size:8px;">Longitud inspeccionada:</th>
                                                    <th style="font-size:8px;">{{ $item['valor'] ?? '' }} m</th>
                                                </tr>
                                            @endif

                                @endforeach
                            </tbody>
                        </table>

                    @if (!$loop->last)
                        <div style="page-break-after: always;"></div>
                    @endif
            @endforeach
        </body>
    </html>
