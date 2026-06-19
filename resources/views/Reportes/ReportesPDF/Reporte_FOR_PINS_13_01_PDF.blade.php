<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-13/01</title>
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
                    font-size: 9px !important;
                    font-family: 'arial', sans-serif;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                    font-size: 8px;
                }

                .observacionesFooter {
                    width: 100%;
                    table-layout: auto;
                }

                .observacionesFooter th {
                    width: 90px;
                    white-space: nowrap;
                    text-align: left;
                }

                .observacionesFooter td {
                    width: auto;
                    text-align: left;
                    white-space: normal;
                    word-break: break-word;
                    padding-left: 6px;
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
                            <th rowspan="4" style="width: 450%; font-size: 9pt;">
                                INFORME DE INSPECCIÓN CON CORRIENTES EDDY
                            </th>
                            <th rowspan="4" style="width: 90%;">
                                @if(!empty($QR_PDF))
                                    <img src="{{ $QR_PDF }}" alt="QR" style="width:65px; height:65px; display:block; margin:auto; padding:0;">
                                @endif
                            </th>

                            <th style="width: 70%;">Código:</th>
                            <th style="width: 100%;">FOR-PINS-13/01</th>
                            <th rowspan="4" style="width: 90%;">
                                <img  src="{{ $Logo }}" alt="Logo" style="width: 60%; height: auto;">  
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th>Versión</th>
                            <th>1</th>
                        </tr>
                        <tr>
                            <th style="width: 90%;">Fecha de elaboración</th>
                            <th>18-feb-26</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 6px;"></div>

            </header>

                <footer>
                    <div style="margin-bottom: 5px;"></div>
                        <table class="simbologia">
                            <thead>
                                <tr>
                                    <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA</th>
                                </tr>

                                <tr>
                                    <td style="width: 20px;"><strong>NPIR</strong></td>
                                    <td style="width: 110px;">NO PRESENTA INDICACIÓN RELEVANTE</td>
                                    <td style="width: 20px;"><strong>IR</strong></td>
                                    <td style="width: 150px;">INDICACIÓN REDONDEADA</td>
                                    <td style="width: 20px;"><strong>LA</strong></td>
                                    <td style="width: 180px;">LONGITUD AXIAL</td>
                                </tr>

                                <tr>
                                    <td><strong>IL</strong></td>
                                    <td>INDICACIÓN LINEAL</td>
                                    <td><strong>G</strong></td>
                                    <td>GRIETAS</td>
                                    <td><strong>LC</strong></td>
                                    <td>LONGITUD CIRCUNFERENCIAL</td>
                                </tr>

                                <tr>
                                    <td><strong>CC</strong></td>
                                    <td>CAMBIO DE CONDUCTIVIDAD</td>
                                    <td><strong>ZG</strong></td>
                                    <td>ZONA DE GRIETAS</td>
                                    <td><strong>H.T.</strong></td>
                                    <td>HORARIO TÉCNICO</td>
                                </tr>
                            </thead>
                        </table>

                    <table>
                </div>

                    <br>
                    <table class="datosgenerales observacionesFooter">                                
                        <tr>                                     
                            <th>OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 100%;"> {{ $Datos_Equipo['Observaciones'] }}</td>                            
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
                
                <div style="margin-bottom: 0px;"></div>    
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
                        <tr><th colspan="4">DATOS Y AJUSTES DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th colspan="2">SONDA</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_SONDA'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_SONDA'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_EQUIPO'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_SONDA'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">BLOCK DE CALIBRACIÓN</th>
                            <th colspan="2">ENCODER</th>
                            <th colspan="2">ENCODER</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_BLOCK'] }}</td>
                            <th class="celdaGris" style="width: 12%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_ENCODER1'] }}</td>
                            <th class="celdaGris" style="width: 12%;">MARCA:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['MARCA_ENCODER2'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_ENCODER1'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_ENCODER2'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_BLOCK'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_ENCODER1'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_ENCODER2'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>
                
                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">SOFTWARE</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['SOFTWARE'] }}</td>
                            <th style="width: 15%;">GANANCIA HORIZONTAL</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['GANANCIA_HOR'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">FRECUENCIA</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['FRECUENCIA'] }}</td>
                            <th style="width: 15%;">GANANCIA VERTICAL</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['GANANCIA_VER'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">TEMPERATURA</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMP'] }}</td>
                            <th style="width: 15%;">ESPESOR DE PINTURA</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['ESP_PINT'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">PROBE DRIVE</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRO_DRI'] }}</td>
                            <th style="width: 15%;">SAMPLE RATE</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['SAM_RATE'] }}</td>
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
                            <th style="width: 30px;" colspan="2">DATOS DE INSPECCIÓN</th>
                                <th style="width: 40px;" colspan="5">DATOS DE LA INDICACIÓN</th>
                                <th style="width: 30px;" colspan="2">Área Inspeccionada</th>
                                <th style="width: 30px;" rowspan="2">Evaluación</th>
                                <th style="width: 30px;" rowspan="2">Fotos</th>
                                <th style="width: 30px;" rowspan="2">Observaciones</th>
                            </tr>  
                            <tr class="celdaGris">
                                <th style="width: 30px;">Junta / Elemento</th>
                                <th style="width: 40px;">Zona de Barrido</th>
                                <th style="width: 30px;">No. IndIcación</th>
                                <th style="width: 30px;">Tipo de Indicación</th>
                                <th style="width: 30px;">LA</th>
                                <th style="width: 30px;">LC</th>
                                <th style="width: 30px;">H.T.</th>
                                <th style="width: 30px;">Largo</th>
                                <th style="width: 30px;">Ancho</th>
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
                                                        <td colspan="12" style="border:.5px solid black;">
                                                            {{ $item['texto'] }}
                                                        </td>
                                                    </tr>
                                                @endif

                                                {{-- FILA --}}
                                                @if (($item['tipo'] ?? null) == 'fila')
                                                    <tr class="juntas">
                                                        <td>{{ $item['data']['no_junta'] ?? '' }}</td>
                                                        <td>{{ $item['data']['ZBarrido'] ?? '' }}</td>
                                                        <td>{{ $item['data']['no_ind'] ?? '' }}</td>
                                                        <td>{{ $item['data']['Tip_ind'] ?? '' }}</td>
                                                        <td>{{ $item['data']['la'] ?? '' }}</td>
                                                        <td>{{ $item['data']['lc'] ?? '' }}</td>
                                                        <td>{{ $item['data']['ht'] ?? '' }}</td>
                                                        <td>{{ $item['data']['largo'] ?? '' }}</td>
                                                        <td>{{ $item['data']['ancho'] ?? '' }}</td>
                                                        <td>{{ $item['data']['Eval'] ?? '' }}</td>
                                                        <td>{{ $item['data']['fotos'] ?? '' }}</td>
                                                        <td>{{ $item['data']['Observaciones'] ?? '' }}</td>
                                                    </tr>
                                                @endif

                                                {{-- LONGITUD --}}
                                                @if (($item['tipo'] ?? null) == 'longitud')
                                                    <tr class="sinBordetd">
                                                        <td colspan="8"></td>
                                                        <th colspan="2">Longitud inspeccionada:</th>
                                                        <th colspan="2">{{ $item['valor'] ?? '' }} m</th>
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
