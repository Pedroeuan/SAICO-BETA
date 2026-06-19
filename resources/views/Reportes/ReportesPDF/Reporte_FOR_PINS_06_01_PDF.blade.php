<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-06/01</title>
            <style>
                @page {
                    margin: 
                    /*3.0cm /* superior */
                    /*2.1cm /* derecho */
                    /*2.1cm /* inferior */
                    /*2.4cm; /* izquierdo */
                    3.0cm /* superior */
                    1.2cm /* derecho */
                    2.1cm /* inferior */
                    2.2cm; /* izquierdo */
                }

                header {
                    position: fixed;
                    top: -55px;
                    left: 0;
                    right: 0;
                    height: auto;
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
                    margin-top: 27px; /* Ajusta para que el contenido no se sobreponga al header */
                    /*margin: 0;*/
                    padding-top: 0px; /* Altura del header */
                    padding-bottom: 0px; /* Altura del footer */
                    font-family: 'arial', sans-serif;
                    /*background-color:rgb(45, 78, 226); /* Fondo para que sea visible */
                }

                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 8px !important;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                }

                .lineaSuperior{
                    border-top: 2px solid black;
                    text-align: center;
                    font-size: 6px;
                }

                .lineaIzquierda{
                    border-left: 1px solid black;
                    text-align: center;
                    font-size: 6px;
                }
                .lineaDerecha{
                    border-right: 1px solid black;
                    text-align: center;
                    font-size: 6px;
                }

                .simbologia {
                    border-collapse: collapse;  /*separate No colapsar bordes */
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 8px;
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
                    font-size: 9px;
                }
                    
                /* Aplica el borde a las celdas de la tabla */
                .tablaheader th {
                    border: 1px solid black;
                }

        .encabezadoAzul{
            text-align: center;
            width: 100%;
            font-size: 8px;
            background-color: #305496;
            color: #ffffff;
            outline: 1px double #000000; /* Contorno externo */
        }
            
        .datosinspeccion{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 8px;
        }

        .datosinspeccion td, .datosinspeccion th {
            border: .6px solid black; 
        }

        .datosinspeccionsinborde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .datosresultados{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 8px;
            table-layout: fixed;
            /*border: 1px solid black;*/
        }

        .datosresultados td, .datosresultados th {
            border: .6px solid black; 
        }
        .celdaGris{
            background-color: #DBDBDB;
        }
        
        .sinBordetdth td, .sinBordetdth th {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 100px;*/
        }
        
        .sinBordetd td {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 100px;*/
        }

        .sinBordeth th {
            border: 0px !important;
            text-align: left;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 10px;*/
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
                            <th rowspan="4" style="width: 400%; font-size: 9pt;">
                                INFORME DE INSPECCIÓN CON ULTRASONIDO DE ACUERDO CON API RP 2X
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
                            <th style="width: 100%;">FOR-PINS-06/01</th>

                            <th rowspan="4" style="width:90%; padding:0; margin:0;">
                                <div style="width:100%; height:7.2%; text-align:center; vertical-align:middle; padding:0; margin:0;">
                                    <img
                                        src="{{ $Logo }}"
                                        alt="Logo"
                                        style="width:65px; height:65px; display:block; margin:auto; padding:0;"
                                    >
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th>Versión:</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th style="width: 90%;">Fecha de elaboración:</th>
                            <th>28-may-26</th>
                        </tr>
                        <tr>
                            <th>Página:</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
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

                <table class="datosinspeccion">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                            <tr class="celdaGris">
                                <th class="celdaGris" rowspan="2">EQUIPO:</th>
                                <th style="width: 100px;">MARCA</th>
                                <th style="width: 100px;">MODELO</th>
                                <th style="width: 100px;">SERIE</th>
                                <th class="celdaGris" rowspan="2">ACOPLANTE:</th>
                                <th style="width: 100px;">MARCA</th>
                                <th style="width: 100px;">TIPO</th>
                            </tr>
                            <tr>
                                <td>{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                                <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                                <td>{{ $Datos_Equipo['N_S_EQUIPO'] }}</td>
                                <td>{{ $Datos_Equipo['AC_MARCA'] }}</td>
                                <td>{{ $Datos_Equipo['AC_TIPO'] }}</td>
                            </tr>
                                <tr class="celdaGris">
                                <th class="celdaGris" rowspan="4">TRANSDUCTORES:</th>
                                <th style="width: 20%;">ÁNGULO</th>
                                <th style="width: 20%;">FRECUENCIA</th>
                                <th style="width: 20%;">TAMAÑO</th>
                                <th class="celdaGris" rowspan="3">BLOQUES:</th>
                                <th style="width: 20%;">CORRIENTE</th>
                                <th style="width: 20%;">DISTANCIA ENTRE PATAS</th>
                            </tr>
                            <tr>
                                <td>{{ $Datos_Equipo['ANGULO1_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['FRECUENCIA1_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['TAMANO1_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['BLOQUES1_MARCA'] }}</td>
                                <td>{{ $Datos_Equipo['BLOQUES1_TIPO'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $Datos_Equipo['ANGULO2_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['FRECUENCIA2_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['TAMANO2_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['BLOQUES2_MARCA'] }}</td>
                                <td>{{ $Datos_Equipo['BLOQUES2_TIPO'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ $Datos_Equipo['ANGULO3_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['FRECUENCIA3_TRANSDUCTORES'] }}</td>
                                <td>{{ $Datos_Equipo['TAMANO3_TRANSDUCTORES'] }}</td>
                                <th class="celdaGris" colspan="2">LONGITUD DEL CABLE:</th>
                                <td>{{ $Datos_Equipo['LONGITUD_CABLE'] }}</td>
                            </tr>
                        </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="7">DATOS DE LA INSPECCIÓN</th>
                    </tr>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">NIVEL DE ESCANEO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['NIVEL_ESCANEO'] }}</td>
                            <th style="width: 15%;">SENSIBILIDAD (Db):</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['SENSIBILIDAD'] }}</td>
                            <th style="width: 15%;">NIVEL DE CALIDAD:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['NIVEL_CALIDAD'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">CONDICIÓN SUPERFICIAL:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['CONDICION_SUPERFICIAL'] }}</td>
                            <th style="width: 15%;">TEMPERATURA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMPERATURA'] }}</td>
                            <th style="width: 15%;"></th>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
                <div style="margin-bottom: 5px;"></div>

                    <table class="datosresultados">
                    
                        <thead class="encabezadoAzul">
                            <tr><th colspan="17">RESULTADOS</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="17"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                    <th style="width: 5%;">No.</th>
                                    <th style="width: 10%;">DIBUJO</th>
                                    <th style="width: 10%;">SOLDADURA</th>
                                    <th style="width: 10%;">EVALUACIÓN</th>
                                    <th style="width: 10%;">FORMA</th>
                                    <th style="width: 10%;">TRANSFER</th>
                                    <th style="width: 10%;">LONGITUD</th>
                                    <th style="width: 10%;">ANCHO</th>
                                    <th style="width: 25%;">OBSERVACIONES</th>
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
                                                    <td colspan="9" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                <tr class="juntas">
                                                    <td>{{ $item['data']['No'] ?? '' }}</td>
                                                    <td>{{ $item['data']['dibujo'] ?? '' }}</td>
                                                    <td>{{ $item['data']['soldadura'] ?? '' }}</td>
                                                    <td>{{ $item['data']['evaluacion'] ?? '' }}</td>
                                                    <td>{{ $item['data']['forma'] ?? '' }}</td>
                                                    <td>{{ $item['data']['transfer'] ?? '' }}</td>
                                                    <td>{{ $item['data']['longitud'] ?? '' }}</td>
                                                    <td>{{ $item['data']['ancho'] ?? '' }}</td>
                                                    <td>{{ $item['data']['observaciones'] ?? '' }}</td>
                                            @endif

                                            {{-- LONGITUD --}}
                                            @if (($item['tipo'] ?? null) == 'longitud')
                                                <tr class="sinBordetd">
                                                    <td colspan="6"></td>
                                                    <th colspan="2">Longitud inspeccionada:</th>
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
