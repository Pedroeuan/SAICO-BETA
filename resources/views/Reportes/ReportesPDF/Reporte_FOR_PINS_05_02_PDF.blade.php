<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FORMATO FOR-PINS-05/02</title>
    <style>
        @page {
            margin:
            3.0cm
            1.2cm
            2.1cm
            2.2cm;
        }

                header {
                    position: fixed;
                    top: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
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
                    margin-top: 25px;
                    margin-right: 0;
                    margin-bottom: 0;
                    margin-left: 0;
                    padding-top: 0;
                    padding-bottom: 0;
                    font-family: 'arial', sans-serif;
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
                    border-spacing: 5px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 10px;
                }
                    
                /* Aplica el borde a las celdas de la tabla */
                .tablaheader th {
                    border: 1px solid black;
                    padding: 4px 6px;
                    vertical-align: middle;
                    line-height: 1.15;
                }

                /* Igualar encabezado al PDF de fotos */
                header {
                    top: -40px;
                }

                footer {
                    bottom: 30px;
                }

                body {
                    margin-top: 27px;
                }

                .datosgenerales {
                    font-size: 9px !important;
                    font-family: 'arial', sans-serif;
                }

                .lineaInferior {
                    font-size: 8px;
                }

                .tablaheader {
                    font-size: 9px;
                }

                .tablaheader th {
                    padding: 0;
                    vertical-align: middle;
                    line-height: normal;
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
            font-size: 9px;
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

        .observaciones-header {
            display: inline-block;
            text-align: center;
            line-height: 1.1;
            font-size: 7px;
        }

        .longitud-label {
            border: .6px solid black !important;
            font-weight: bold;
            text-align: center;
            font-size: 7px;
            line-height: 1.1;
            padding: 2px;
        }

        .longitud-value {
            border: .6px solid black !important;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
            padding: 2px;
        }
    </style>
</head>
<body>
    <header>
        <table class="tablaheader">
            <thead>
                <tr>
                    <th style="width: 500%;">FORMATO</th>
                    <th rowspan="3" style="width: 80%;">
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
                                    width:65px;
                                    height:65px;
                                    display:block;
                                    margin:auto;
                                    padding:0;
                                "
                            >
                            @endif
                        </div>
                    </th>
                    <th style="width: 60%;">Código:</th>
                    <th style="width: 80%;">FOR-PINS-05/02</th>
                    <th rowspan="3" style="width: 80%;">
                        <div style="
                            width:100%;
                            height:7.2%;
                            text-align:center;
                            vertical-align:middle;
                            padding:0;
                            margin:0;
                        ">
                            <img
                                src="{{ $Logo }}"
                                alt="Logo"
                                style="
                                    width:65px;
                                    height:65px;
                                    display:block;
                                    margin:auto;
                                    padding:0;
                                "
                            >
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th rowspan="2" style="font-size: 9pt;">INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO, DE ACUERDO CON AWS D1.1 PARA COMPONENTES TUBULARES</th>
                    <th>Versión</th>
                    <th>2</th>
                </tr>
                <tr>
                    <th>Página</th>
                    <th></th>
                </tr>
            </tbody>
        </table>
        <div style="margin-bottom: 2px;"></div>
    </header>

    <footer>
        <br>

        <table class="datosgenerales" style="width: 100%;">                               
            <tr>                                     
                <th style="width: 15%; text-align: left;">OBSERVACIONES:</th>                                         
                <td class="lineaInferior" style="width: 85%;">{{ $Datos_Equipo['Observaciones'] ?? '' }}</td>                            
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
            <div style="margin-bottom: 4px;"></div>
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
                    <tr>
                        <th colspan="9">DATOS DEL EQUIPO</th>
                    </tr>
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
                        <th class="celdaGris" style="width: 10%;">MARCA:</th>
                        <td style="width: 15%;">{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '---' }}</td>
                        <th class="celdaGris" style="width: 10%;">MARCA:</th>
                        <td colspan="3">{{ $Datos_Equipo['MARCA_TRANSDUCTOR'] ?? '---' }}</td>
                        <th class="celdaGris" style="width: 10%;">MARCA:</th>
                        <td style="width: 15%;">{{ $Datos_Equipo['MARCA_BLOCK'] ?? '---' }}</td>
                        <th class="celdaGris" style="width: 15%;">MARCA Y TIPO</th>
                    </tr>
                    <tr>
                        <th class="celdaGris">MODELO:</th>
                        <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '---' }}</td>
                        <th class="celdaGris">MODELO:</th>
                        <td colspan="3">{{ $Datos_Equipo['MODELO_TRANSDUCTOR'] ?? '---' }}</td>
                        <th class="celdaGris">MODELO:</th>
                        <td>{{ $Datos_Equipo['MODELO_BLOCK'] ?? '---' }}</td>
                        <td rowspan="2">{{ $Datos_Equipo['ACOPLANTE'] ?? '---' }}</td>
                    </tr>
                    <tr>
                        <th class="celdaGris">SERIE:</th>
                        <td>{{ $Datos_Equipo['N_S_EQUIPO'] ?? '---' }}</td>
                        <th class="celdaGris">SERIE:</th>
                        <td>{{ $Datos_Equipo['N_S_TRANSDUCTOR'] ?? '---' }}</td>
                        <th class="celdaGris" style="width: 8%;">FREC:</th>
                        <td>{{ $Datos_Equipo['FREC_TRANSDUCTOR'] ?? '---' }}</td>
                        <th class="celdaGris">SERIE:</th>
                        <td>{{ $Datos_Equipo['N_S_BLOCK'] ?? '---' }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-bottom: 4px;"></div>

            <table class="encabezadoAzul">
                <tr>
                    <th colspan="4">AJUSTE DEL EQUIPO</th>
                </tr>
            </table>

            <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr class="">
                            <th style="width: 100px;">GANANCIA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['GANANCIA'] }}</td><td style="text-align: left; width: 2%;"> dB </td>
                            <th style="width: 100px;">TIPO DE JUNTA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TIPO_JUNTA'] }}</td>
                        </tr>
                        <tr class="">
                            <th>RECHAZO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['RECHAZO'] }}</td><td></td>
                            <th>DIAMETRO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['DIAMETRO'] }}</td>
                        </tr>
                        <tr class="">
                            <th>RETARDO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['RETARDO'] }}</td><td></td>
                            <th>ESPESOR:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['ESPESOR'] }}</td>
                        </tr>
                    </tbody>
                </table>

            <div style="margin-bottom: 6px;"></div>

                    <table class="datosresultados">
                    
                        <thead class="encabezadoAzul">
                            <tr><th colspan="11">RESULTADOS</th></tr>
                        </thead>
                        
                        <thead><tr class="sinBordeth"><th colspan="11"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                <th rowspan="2">No. de Junta</th>
                                <th rowspan="2">No. de Indicación</th>
                                <th rowspan="2">Clasificación</th>
                                <th colspan="4">Ubicación</th>
                                <th rowspan="2">Tamaño</th>
                                <th rowspan="2">% Amplitud</th>
                                <th rowspan="2">Evaluación</th>
                                <th rowspan="2">Comentarios</th>
                            </tr>
                            <tr class="celdaGris">
                                <th>X</th>
                                <th>Y</th>
                                <th>H.T.</th>
                                <th>Prof.</th>
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
                                                    <td colspan="11" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                <tr class="juntas">
                                                    <td>{{ $item['data']['no_junta'] ?? '' }}</td>
                                                    <td>{{ $item['data']['no_indicacion'] ?? '' }}</td>
                                                    <td>{{ $item['data']['clasificacion'] ?? '' }}</td>
                                                    <td>{{ $item['data']['ubi_x'] ?? '' }}</td>
                                                    <td>{{ $item['data']['ubi_y'] ?? '' }}</td>
                                                    <td>{{ $item['data']['ht'] ?? '' }}</td>
                                                    <td>{{ $item['data']['prof'] ?? '' }}</td>
                                                    <td>{{ $item['data']['tamanio'] ?? '' }}</td>
                                                    <td>{{ $item['data']['amplitud'] ?? '' }}</td>
                                                    <td>{{ $item['data']['evaluacion'] ?? '' }}</td>
                                                    <td>{{ $item['data']['comentarios'] ?? '' }}</td>
                                                </tr>
                                            @endif

                                            {{-- LONGITUD --}}
                                            @if (($item['tipo'] ?? null) == 'longitud')
                                                <tr class="sinBordetd">
                                                    <td colspan="6"></td>
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
