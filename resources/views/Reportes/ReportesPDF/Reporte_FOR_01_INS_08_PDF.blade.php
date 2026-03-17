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
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
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

                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 605px;"></td>                            
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
                @php
                    $chunks = [];
                    $grupoActual = [];
                    foreach ($Fotos as $foto) {
                        // Si la imagen es de hoja completa
                        if (!empty($foto['una_hoja']) && $foto['una_hoja'] == 1) {
                            // Guardar grupo previo (si existe)
                            if (!empty($grupoActual)) {
                                $chunks[] = $grupoActual;
                                $grupoActual = [];
                            }
                            // La imagen va SOLA
                            $chunks[] = [$foto];
                            continue;
                        }
                        // Imagen normal
                        $grupoActual[] = $foto;
                        if (count($grupoActual) == 4) {
                            $chunks[] = $grupoActual;
                            $grupoActual = [];
                        }
                    }
                    if (!empty($grupoActual)) {
                        $chunks[] = $grupoActual;
                    }
                @endphp

            
            @foreach ($Grupo_Juntas_Detalles_Re as $grupo)
            <div class="content">
                  <table class="encabezadoAzul">
                    <tr>
                        <th colspan="4">DATOS GENERALES</th>
                    </tr>
                </table>   
                <div style="margin-bottom: 5px;"></div>         
                <table class="datosgenerales">
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
                            <th class="celdaGris" style="width: 100px;">MARCA Y TIPO</th>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{ $Datos_Equipo['MODELO_TR'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] }}</td>
                            <td rowspan="2">{{ $Datos_Equipo['ACOPLANTE'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_EQUIPO'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td style="width: 60px;">{{ $Datos_Equipo['NS_TR'] }}</td>
                            <th class="celdaGris" style="width: 50px;">FRECC:</th>
                            <td style="width: 50px;">{{ $Datos_Equipo['FREC_TR'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_BLOCK'] }}</td>
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
                    @php
                        $ganancia  = $Datos_Equipo['GANANCIA'] ?? '';
                        $tipoJunta = $Datos_Equipo['TIPO_JUNTA'] ?? ($Datos_Equipo['TIP_JUNTA'] ?? '');
                        $rechazo   = $Datos_Equipo['RECHAZO'] ?? ($Datos_Equipo['RANGO'] ?? '');
                        $diametro  = $Datos_Equipo['DIAMETRO'] ?? '';
                        $retardo   = $Datos_Equipo['RETARDO'] ?? '';
                        $espesor   = $Datos_Equipo['ESPESOR'] ?? '';
                    @endphp
                    <tr>
                        <th style="width: 100px;">GANANCIA:</th>
                        <td class="lineaInferior">{{ $ganancia }}</td><td style="text-align: left; width: 2%;"> dB </td>
                        <th style="width: 100px;">TIPO DE JUNTA:</th>
                        <td class="lineaInferior">{{ $tipoJunta }}</td>
                    </tr>
                    <tr>
                        <th>RECHAZO:</th>
                        <td class="lineaInferior">{{ $rechazo }}</td><td></td>
                        <th>DIAMETRO:</th>
                        <td class="lineaInferior">{{ $diametro }}</td>
                    </tr>
                    <tr>
                        <th>RETARDO:</th>
                        <td class="lineaInferior">{{ $retardo }}</td><td></td>
                        <th>ESPESOR:</th>
                        <td class="lineaInferior">{{ $espesor }}</td>
                    </tr>
                </tbody>

                </table>

                <div style="margin-bottom: 5px;"></div>

                    <table class="datosresultados">
                        <thead class="encabezadoAzul">
                            <tr><th colspan="22">RESULTADOS</th></tr>
                        </thead>

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
                                        {{-- 🔹 TÍTULO --}}
                                         @if (!str_starts_with($grupo['titulos_juntas'], 'SIN TITULO'))
                                            <tr class="titulo-row">
                                                <td colspan="22" style="border:.5px solid black;">
                                                    {{ $grupo['titulos_juntas'] }}
                                                </td>
                                            </tr>
                                            @endif     

                                            {{-- 🔹 FILAS DEL BLOQUE --}}
                                            @foreach ($grupo['resultados'] as $junta)
                                                <tr class="juntas">
                                                    <td>{{ $junta['ID'] }}</td>
                                                    <td>{{ $junta['no_junta'] }}</td>
                                                    <td>{{ $junta['lado_a'] }}</td>
                                                    <td>{{ $junta['lado_b'] }}</td>
                                                    <td>{{ $junta['diametro'] }}</td>
                                                    <td>{{ $junta['no_indicacion'] ?? '' }}</td> 
                                                    <td>{{ $junta['tipo_indicacion'] }}</td>
                                                    <td>{{ $junta['Ang'] }}</td>
                                                    <td>{{ $junta['Gdb'] }}</td>
                                                    <td>{{ $junta['nr'] }}</td>
                                                    <td>{{ $junta['ni'] }}</td>
                                                    <td>{{ $junta['x'] }}</td>
                                                    <td>{{ $junta['y'] }}</td>
                                                    <td>{{ $junta['horario_tecnico'] }}</td>
                                                    <td>{{ $junta['no_pierna'] }}</td>
                                                    <td>{{ $junta['s'] }}</td>
                                                    <td>{{ $junta['l'] }}</td>
                                                    <td>{{ $junta['d'] }}</td>
                                                    <td>{{ $junta['tmin'] }}</td>
                                                    <td>{{ $junta['evaluacion'] }}</td>
                                                    <td>{{ $junta['fotos'] }}</td>
                                                    <td>{{ $junta['observaciones'] }}</td>
                                                </tr>

                                            @endforeach

                                            {{-- 🔹 LONGITUD INSPECCIONADA --}}
                                            <tr>
                                                <td colspan="16" style="border:0 !important;"></td>
                                                <td colspan="4" style="border:.6px solid black; font-weight:bold; text-align:center;">
                                                    Longitud inspeccionada:
                                                </td>
                                                <td colspan="2" style="border:.6px solid black; font-weight:bold; text-align:center;">
                                                    {{ $grupo['Long_Inspecc'][0] ?? '---' }} m
                                                </td>
                                            </tr>

                                            {{-- 🔹 SALTO DE PÁGINA POR BLOQUE 
                                            <tr style="page-break-after: always;" class="sinBordetd">
                                                <td colspan="22"></td>
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