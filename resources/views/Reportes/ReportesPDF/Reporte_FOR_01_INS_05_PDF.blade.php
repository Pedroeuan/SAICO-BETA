<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-05/01</title>
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
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%;">FOR-INS-05/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> Informe de Inspección con Ultrasonido de  Acuerdo con API RP 2X </th>
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

            </header>

            <footer>
            <div style="margin-bottom: 5px;"></div>
                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
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

            <div class="content">
                
                    <table class="datosresultados">
                    
                        <thead class="encabezadoAzul">
                            <tr><th colspan="9">RESULTADOS</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="8"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                                <tr class="celdaGris">
                                    <th style="width: 10%;">No.</th>
                                    <th style="width: 14%;">DIBUJO</th>
                                    <th>SOLDADURA</th>
                                    <th>EVALUACIÓN</th>
                                    <th>FORMA</th>
                                    <th>TRANSFER</th>

                                    <th>LONGITUD</th>
                                    <th>ANCHO</th>
                                    <th style="width: 14%;">OBSERVACIONES</th>
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
                                                <td colspan="9" style="border-left: 2px solid black; border-right: 2px solid black;">
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
                                                $totalMetros += floatval($junta['observaciones']);
                                                $esUltimaFila = $loop->last;
                                            @endphp
                                            <tr class="juntas">
                                                <td style="border-left: 2px solid black; @if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['No'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['dibujo'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['soldadura'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['evaluacion'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['forma'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['transfer'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['longitud'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['ancho'] }}</td>
                                                <td style="border-right: 2px solid black;@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['observaciones'] }}</td>
                                            </tr>

                                                @if ($contadorFilas % $filasPorPagina === 0)
                                                    <!-- Fila de total antes del salto de página -->
                                                    <tr style="page-break-after: always;" class="sinBordetd">
                                                        <td colspan="5" style="border-top: 2px solid black;"></td>
                                                        <th colspan="2" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong>Longitud inspeccionada:</strong></th>
                                                        <th style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m</th>
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
                                                    <td colspan="5" style="border-top: 2px solid black;"></td>
                                                    <th colspan="2" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong>Longitud inspeccionada:</strong></th>
                                                    <th style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m</th>
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
                                                <td colspan="5" style="border-top: 2px solid black;"></td>
                                                <th colspan="2" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong>Longitud inspeccionada:</strong></th>
                                                <th style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m</th>
                                            </tr>
                                        @endif
                                    @endif

                                </tbody>
                    </table>
            </div>
        </body>
    </html>