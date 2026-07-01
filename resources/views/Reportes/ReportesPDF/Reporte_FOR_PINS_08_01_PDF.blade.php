<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-08/01</title> 
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
                    top: -60px; /* Ajusta para que no interfiera con el margen de la página */
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
                                INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES Y TOFD
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
                            <th style="width: 100%;">FOR-PINS-08/01</th>

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
                            <th>Versión</th>
                            <th>2</th>
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

            @php
                // Mantiene el consecutivo real por grupo entre bloques/paginas del PDF.
                $contadorFilasPorGrupo = [];
            @endphp
            @foreach ($Grupo_Juntas_Detalles_Re as $bloque)
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
                            <th colspan="2">BLOCK DE CALIBRACIÓN (SENSIBILIDAD)</th>
                            <th colspan="2">BLOCK DE CALIBRACIÓN (DISTANCIA)</th>
                            <th colspan="4">SONDA #2</th>
                        </tr>
                        <tr>
                            <th style="width: 15%;" class="celdaGris">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_BLOCK_SEN'] ?? '---' }}</td>
                            <th style="width: 15%;" class="celdaGris">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_BLOCK_DIS'] ?? '---' }}</td>
                            <th style="width: 15%;" class="celdaGris">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_SONDA2'] ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK_SEN'] ?? '---' }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK_DIS'] ?? '---' }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{ $Datos_Equipo['MODELO_SONDA2'] ?? '---' }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $Datos_Equipo['N_S_BLOCK_SEN'] ?? '---' }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $Datos_Equipo['N_S_BLOCK_DIS'] ?? '---' }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $Datos_Equipo['N_S_SONDA2'] ?? '---' }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td >{{ $Datos_Equipo['FREC_SONDA2'] ?? '---' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="9">AJUSTE DEL EQUIPO</th>
                    </tr>
                </table>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr class="">
                            <th style="width: 15%;">GANANCIA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['GANANCIA'] ?? '---' }}</td>
                            <th style="width: 15%;">TIPO DE JUNTA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TIPO_JUNTA'] ?? '---' }}</td>
                        </tr>
                        <tr class="">
                            <th>RECHAZO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['RECHAZO'] ?? '---' }}</td>
                            <th>DIAMETRO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['DIAMETRO'] ?? '---' }}</td>
                        </tr>
                        <tr class="">
                            <th>TEMPERATURA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMPERATURA'] ?? '---' }}</td>
                            <th>ESPESOR:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['ESPESOR'] ?? '---' }}</td>
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
                            @php
                                $tablaCombinacionConfig = collect($tablaCombinacionConfig ?? [])->map(function ($item) {
                                    return ['groupId' => $item['groupId'] ?? 'sin_titulo', 'field' => $item['field'] ?? '', 'startRow' => isset($item['startRow']) ? (int) $item['startRow'] : -1, 'rowspan' => isset($item['rowspan']) ? (int) $item['rowspan'] : 1];
                                });
                                $obtenerCombinacionTabla = function ($groupId, $field, $rowIndex) use ($tablaCombinacionConfig) {
                                    return $tablaCombinacionConfig->first(function ($item) use ($groupId, $field, $rowIndex) {
                                        return ($item['groupId'] ?? 'sin_titulo') === ($groupId ?: 'sin_titulo') && ($item['field'] ?? '') === $field && (int) ($item['startRow'] ?? -1) === $rowIndex && (int) ($item['rowspan'] ?? 1) > 1;
                                    });
                                };
                                $esCeldaOcultaPorCombinacion = function ($groupId, $field, $rowIndex) use ($tablaCombinacionConfig) {
                                    return $tablaCombinacionConfig->contains(function ($item) use ($groupId, $field, $rowIndex) {
                                        $inicio = (int) ($item['startRow'] ?? -1);
                                        $rowspan = (int) ($item['rowspan'] ?? 1);
                                        return ($item['groupId'] ?? 'sin_titulo') === ($groupId ?: 'sin_titulo') && ($item['field'] ?? '') === $field && $rowspan > 1 && $rowIndex > $inicio && $rowIndex < ($inicio + $rowspan);
                                    });
                                };
                                $columnasResultadoPdf = ['junta_ele', 'no_indicacion', 'angulo', 'nr', 'ni', 'la', 'lc', 'dist_zapata', 'sa', 'da', 'ht', 'evaluacion', 'fotos'];
                            @endphp
                            <tbody>
                                @foreach ($bloque as $item)
                                            @if (!is_array($item))
                                                @continue
                                            @endif

                                            {{-- TITULO --}}
                                            @if (($item['tipo'] ?? null) == 'titulo')
                                                <tr class="titulo-row">
                                                    <td colspan="13" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                @php
                                                    $groupId = $item['grupo'] ?? 'sin_titulo';
                                                    $rowIndex = $contadorFilasPorGrupo[$groupId] ?? 0;
                                                @endphp
                                                <tr class="juntas">
                                                    @foreach ($columnasResultadoPdf as $campoPdf)
                                                        @php $combinacionCelda = $obtenerCombinacionTabla($groupId, $campoPdf, $rowIndex); @endphp
                                                        @if ($combinacionCelda)
                                                            <td rowspan="{{ $combinacionCelda['rowspan'] }}">{{ $item['data'][$campoPdf] ?? '' }}</td>
                                                        @elseif (!$esCeldaOcultaPorCombinacion($groupId, $campoPdf, $rowIndex))
                                                            <td>{{ $item['data'][$campoPdf] ?? '' }}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                @php $contadorFilasPorGrupo[$groupId] = $rowIndex + 1; @endphp
                                            @endif

                                            {{-- FILA LEGACY --}}
                                            @if (false && (($item['tipo'] ?? null) == 'fila'))
                                                <tr class="juntas">
                                                <td>{{ $item['data']['junta_ele'] }}</td>
                                                <td>{{ $item['data']['no_indicacion'] }}</td>
                                                <td>{{ $item['data']['angulo'] }}</td>
                                                <td>{{ $item['data']['nr'] }}</td>
                                                <td>{{ $item['data']['ni'] }}</td>
                                                <td>{{ $item['data']['la'] }}</td>
                                                <td>{{ $item['data']['lc'] }}</td>
                                                <td>{{ $item['data']['dist_zapata'] }}</td>
                                                <td>{{ $item['data']['sa'] }}</td>
                                                <td>{{ $item['data']['da'] }}</td>
                                                <td>{{ $item['data']['ht'] }}</td>
                                                <td>{{ $item['data']['evaluacion'] }}</td>
                                                <td>{{ $item['data']['fotos'] }}</td>
                                                </tr>
                                            @endif

                                            {{-- LONGITUD --}}
                                            @if (($item['tipo'] ?? null) == 'longitud')
                                                <tr class="">
                                                    <td colspan="9" rowspan="2" style="border:.6px solid black; text-align:left; font-size:8px; padding:4px 6px;">
                                                        <b>NPIR</b>= No Presenta Indicaciones Relevantes, <b>SC</b>= Soldadura Circunferencial,
                                                        <b>SA</b>= Distancia Angular, <b>HT</b>= Horario técnico, <b>SL</b>= Soldadura Longitudinal,
                                                        <b>LA</b>= Largo Axial, <b>LC</b>= Largo Circunferencial, <b>DA</b>= Profundidad
                                                    </td>
                                                    <th colspan="2">Longitud inspeccionada:</th>
                                                    <th colspan="2">{{ $item['valor'] ?? '' }} m</th>
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
