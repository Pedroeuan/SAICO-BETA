<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-12/01</title>
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

                @if ($totalTitulosYFilas <=20)
                header {
                    width: 100%;
                    top: -30px; /* Ajusta para que no interfiera con el margen de la página */
                    height: auto; /* Permite crecer según el contenido */
                    text-align: center;
                    /*background-color: rgb(226, 45, 45);*/
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    /*background-color: rgb(7, 231, 18);*/
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin: -30px, 0; /* Ajusta el margen de la página */
                    padding-bottom: 60px; /* Para que el contenido no se monte en el footer */
                    font-family: 'arial', sans-serif;
                    /*background-color: rgb(45, 78, 226);*/
                }
            @else
                header {
                    position: fixed;
                    top: -30px; /* Ajusta para que no interfiera con el margen de la página */
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
                    /*margin-top: 320px; /* Ajusta para que el contenido no se sobreponga al header */
                    margin: 0;
                    padding-top: 235px; /* Altura del header */
                    padding-bottom: 95px; /* Altura del footer */
                    font-family: 'arial', sans-serif;
                    /*background-color:rgb(45, 78, 226); /* Fondo para que sea visible */
                }
                @endif
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
                            <th style="width: 500%;">FORMATO</th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%;">FOR-INS-12/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE INSPECCIÓN CON CORRIENTES DE EDDY </th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 5px;"></div>
        
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

            </header>

            <footer>
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

                    <br>

                    <table class="datosgenerales">                                
                        <tr>                                     
                            <th>OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 675px;">{{ $Datos_Equipo['Observaciones'] }}</td>                            
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

            <div class="content">
                <div style="margin-bottom: 0px;"></div>

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
                                    @php
                                        $contador = 1;
                                        $filasPorPagina = 15;
                                        $contadorFilas = 0;
                                        $contadorFilasPagina = 0;
                                        $totalMetros = 0;
                                    @endphp

                                    @foreach ($Grupo_Juntas_Detalles_Re as $grupo)
                                        @php
                                            $titulo = $grupo['titulos_juntas'];
                                            $juntasDelGrupo = count($grupo['resultados']);
                                            $filasDelGrupo = $juntasDelGrupo + ($titulo !== 'SIN TITULO' ? 1 : 0); // +1 por el título si aplica
                                        @endphp

                                        @if ($titulo !== 'SIN TITULO')
                                            <!-- Fila del título -->
                                            <tr class="titulo-row">
                                                <td colspan="12" style="border-left: 2px solid black; border-right: 2px solid black;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        {{ $titulo }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $contadorFilasPagina++; @endphp
                                        @endif

                                        @foreach ($grupo['resultados'] as $junta)
                                            @php
                                                $contadorFilas++;
                                                $contadorFilasPagina++;
                                                $totalMetros += floatval($junta['Observaciones']);
                                                $esUltimaFila = $loop->last;
                                            @endphp
                                            <tr class="juntas">
                                                <td style="border-left: 2px solid black; @if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['no_junta'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['ZBarrido'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['no_ind'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Tip_ind'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['la'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['lc'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['ht'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['largo'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['ancho'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Eval'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['fotos'] }}</td>
                                                <td style="border-right: 2px solid black;@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Observaciones'] }}</td>
                                            </tr>

                                                @if ($contadorFilas % $filasPorPagina === 0)
                                                    <!-- Fila de total antes del salto de página -->
                                                    <tr style="page-break-after: always;" class="sinBordetd">
                                                        <td colspan="4" style="border-top: 2px solid black;"></td>
                                                        <th colspan="3" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong>Longitud inspeccionada:</strong></th>
                                                        <th colspan="2" style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m2</th>
                                                    </tr>

                                                    @php
                                                        $totalMetros = 0; // Reinicia el acumulador para la siguiente página
                                                    @endphp

                                                @endif
                                            @php $contador++; @endphp
                                        @endforeach

                                            @if ($contadorFilasPagina + $filasDelGrupo > $filasPorPagina && $titulo != 'SIN TITULO') //detectar si todo el grupo no cabe en la página, y si es así, el título anterior es el último de esa página.  
                                            <!-- Salto de página porque no cabe el grupo completo -->
                                                <tr style="page-break-after: always;" class="sinBordetd">
                                                    <td colspan="4" style="border-top: 2px solid black;"></td>
                                                    <th colspan="3" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong>Longitud inspeccionada:</strong></th>
                                                    <th colspan="2" style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m2</th>
                                                </tr>
                                                @php
                                                    $contadorFilasPagina = 0;
                                                    $totalMetros = 0;
                                                @endphp
                                            @endif

                                    @endforeach
                                    
                                    @if($titulo == 'SIN TITULO' && $totalTitulosYFilas <>20 || $titulo != 'SIN TITULO')
                                    <!-- Total al final si no se llenó la última página -->
                                        @if ($contadorFilasPagina > 0)
                                            <tr style="page-break-after: always;" class="sinBordetd">
                                                <td colspan="4" style="border-top: 2px solid black;"></td>
                                                <th colspan="3" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong>Longitud inspeccionada:</strong></th>
                                                <th colspan="2" style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m2</th>
                                            </tr>
                                        @endif
                                    @endif

                                </tbody>
                    </table>
            </div>
        </body>
    </html>