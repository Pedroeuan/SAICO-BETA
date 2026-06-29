<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-18/01</title>
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
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                    font-size: 8px;
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
                            <th rowspan="4" style="width: 450%; font-size: 9pt;">
                                INFORME DE DETECCIÓN DE DISCONTINUIDADES CON CORRIENTES DE EDDY
                            </th>
                            <th rowspan="4" style="width: 90%;">
                                @if(!empty($QR_PDF))
                                    <img src="{{ $QR_PDF }}" alt="QR" style="width:65px; height:65px; display:block; margin:auto; padding:0;">
                                @endif
                            </th>

                            <th style="width: 70%;">Código:</th>
                            <th style="width: 100%;">FOR-PINS-18/01</th>
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
                            <th style="width: 90%;">Fecha de Emisión</th>
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
                    
                    <table class="simbologia">
                        <thead>
                            <tr>
                                <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA</th>
                            </tr>

                            <tr>
                                <td style="width: 20px;"><strong>GT</strong></td>
                                <td style="width: 110px;">GRIETA LONGITUDINAL</td>
                                <td style="width: 20px;"><strong>IR</strong></td>
                                <td style="width: 150px;">INDICACIÓN REDONDEADA</td>
                                <td style="width: 20px;"><strong>LA</strong></td>
                                <td style="width: 180px;">LONGITUD AXIAL</td>
                            </tr>

                            <tr>
                                <td><strong>IL</strong></td>
                                <td>INDICACIÓN LINEAL</td>
                                <td><strong>GT</strong></td>
                                <td>GRIETAS TRANSVERSAL</td>
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
                            <th style="width: 160px;">CODIGO APLICABLE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Codigo_Aplicable'] }}</td>
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
                            <th colspan="2">CABLE</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_BLOCK'] }}</td>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_CABLE'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_CABLE'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">No.serie:</th>
                            <td>{{ $Datos_Equipo['NS_BLOCK'] }}</td>
                            <th class="celdaGris">No.serie:</th>
                            <td>{{ $Datos_Equipo['NS_CABLE'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>
                
                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">FRECUENCIA</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['FREC'] }}</td>
                            <th style="width: 15%;">GANANCIA HORIZONTAL</th>?
                            <td class="lineaInferior">{{ $Datos_Equipo['GAN_HZ'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">ESPESOR DE PINTURA</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['ESP_PINT'] }}</td>
                            <th style="width: 15%;">GANANCIA VERTICAL</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['GAN_VERT'] }}</td>
                        </tr>

                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                    <table class="datosresultados">
                    
                        <thead class="encabezadoAzul">
                            <tr><th colspan="13">RESULTADOS</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="13"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                <th  colspan="2">DATOS DE INSPECCIÓN</th>
                                <th  colspan="6">DATOS DE LA INDICACIÓN</th>
                                <th  colspan="2">Área Inspeccionada</th>
                                <th  rowspan="2">Evaluación</th>
                                <th  style="width: 6%;" rowspan="2">Fotos</th>
                                <th  style="width: 10%;" rowspan="2">Observaciones</th>
                            </tr>  
                            <tr class="celdaGris">
                                <th>Junta / Elemento</th>
                                <th>Zona de Barrido</th>
                                <th>No. IndIcación</th>
                                <th>Tipo de Indicación</th>
                                <th style="width: 6%;">LA</th>
                                <th style="width: 6%;">LC</th>
                                <th style="width: 6%;">H.T.</th>
                                <th>AMP</th>
                                <th>Largo</th>
                                <th>Ancho</th>
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
                                                    <td colspan="13" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                <tr class="juntas">
                                                    <td>{{ $item['data']['Junta'] }}</td>
                                                    <td>{{ $item['data']['Zona_barrido'] }}</td>
                                                    <td>{{ $item['data']['No_Ind'] }}</td>
                                                    <td>{{ $item['data']['Tipo_Ind'] }}</td>
                                                    <td>{{ $item['data']['LA'] }}</td>
                                                    <td>{{ $item['data']['LC'] }}</td>
                                                    <td>{{ $item['data']['HT'] }}</td>
                                                    <td>{{ $item['data']['AMP'] }}</td>
                                                    <td>{{ $item['data']['Largo'] }}</td>
                                                    <td>{{ $item['data']['Ancho'] }}</td>
                                                    <td>{{ $item['data']['Evaluacion'] }}</td>
                                                    <td>{{ $item['data']['Fotos'] }}</td>
                                                    <td>{{ $item['data']['Observaciones'] }}</td>
                                                </tr>
                                            @endif

                                            {{-- LONGITUD --}}
                                            @if (($item['tipo'] ?? null) == 'longitud')
                                                <tr class="sinBordetd">
                                                    <td colspan="8"></td>
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
