
    <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-17-01/01</title>
            <style>
                @page {
                    margin: 3.0cm 1.2cm 2.1cm 2.2cm;
                }

                header {
                    position: fixed;
                    top: -40px; /* Sube el encabezado para que no se monte con "DATOS GENERALES" */
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    font-family: 'arial', sans-serif;
                }

                body {
                    /* Deja espacio exacto debajo del encabezado fijo */
                    margin-top: 40px;
                    margin-right: 0;
                    margin-bottom: 0;
                    margin-left: 0;
                    padding-top: 0;
                    padding-bottom: 0;
                    font-family: 'arial', sans-serif;
                }
                .content {
                    margin-top: 0;
                }

                .content-separador {
                    height: 6px;
                }

                .tablaheader th {
                    border: 1px solid black;
                    padding: 4px 6px;
                    vertical-align: middle;
                    line-height: 1.15;
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
            font-size: 14px;
            background-color: #305496;
            color: #ffffff;
            outline: 1px double #000000; /* Contorno externo */
        }
        .encabezadoAzul2{
            text-align: center;
            width: 100%;
            font-size: 14px;
            background-color: #215e99;
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
        .celdaGrisOscuro{
            background-color: #bfbfbf;
            /*font-size: 9px;*/
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
        .page-break {
            page-break-before: always;
        }
            </style>
        </head>
        <body>

        <div class="page">
            {{-- Portada --}}
            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 10%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                            <th style="font-size: 12pt;">  ASESORIA E INSPECCIÓN EN CONTRUCCIÓN COSTA FUERA S.C. </th>
                        </tr>
                    </thead>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <h1> REPORTE DE INSPECCIÓN TERMOGRÁFICA</h1>
                
            </header>

            <div style="margin-bottom: 4px;"></div>
            
            <footer>
                <p style="text-align: left;">FOR-PINS-17-01/01</p>
            </footer>

            <div class="content" style="text-align: center;">
                <div class="content">
                    <br>
                    <br>
                    @if(isset($Fotos[5]))
                    <img src="{{ $Fotos[5] }}" style="width:650px;">
                    @endif
                </div>
                <br>
                <br>
                <table class="portada" align="center">
                    <tbody>
                        <tr>
                            <th >INSTALACIÓN/EMBARCACIÓN: {{ $Detalles_Generales['Lugar'] }}</th>
                        </tr>
                        <tr>
                            <th>EQUIPO: {{ $Detalles_Generales['Equipo'] }}</th>
                        </tr>
                        <tr>
                            <th>FECHA: {{ $Detalles_Generales['Fecha'] }}</th>
                        </tr>
                        <tr>
                            <th>NO. DE REPORTE: {{ $Detalles_Generales['No_Reporte'] }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="page-break"></div>

        {{-- Segunda Hoja --}}
        <div class="page">
                        <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th rowspan="3" style="width: 80%; padding:0; text-align:center; vertical-align:middle; line-height:0;">
                                @if(!empty($QR_PDF))
                                    <img src="{{ $QR_PDF }}" alt="QR" style="width:57px; height:57px; display:inline-block; vertical-align:middle; margin:0; padding:0; border:4px solid #fff;">
                                @endif
                            </th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%; white-space:nowrap;">FOR-PINS-17-01/01</th>
                            <th rowspan="3" style="width:55px; padding:3px; text-align:center; vertical-align:middle;"><img src="{{ $Logo }}" alt="Logo" style="width:45px; height:auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 8pt;"> INSPECCIÓN CON TERMOGRAFÍA A TABLEROS</th>
                            <th>Versión</th>
                            <th>1</th>
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
                <p style="text-align: left;">FOR-PINS-17-01/01</p>
            </footer>

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
                            <td class="lineaInferior">{{ $Detalles_Generales['Contrato_Reporte'] ?? $Detalles_Generales['Contrato'] }}</td>
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
                            <th>EQUIPO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Equipo'] }}</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Partida'] }}</td>
                            <th>UBICACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Ubicacion'] }}</td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Lugar'] }}</td>
                            <th>HORA DE INSPECCIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['H_Inspeccion'] }}</td>
                        </tr>
                        <tr>
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Procedimiento'] }}</td>
                            <th style="width: 160px;">ESTÁNDAR DE REFERENCIA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Stndr_refe'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 6px;"></div>

                <!-- DATOS Y AJUSTES DEL EQUIPO -->
                <table class="datosresultados" style="table-layout:fixed; width:100%;">
                    <colgroup><col style="width:12%"><col style="width:33%"><col style="width:25%"><col style="width:30%"></colgroup>

                        <thead class="encabezadoAzul">
                            <tr><th colspan="4">DATOS Y AJUSTES DEL EQUIPO</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                <th colspan="4" style="border: 1px solid black; border-left: 2px solid black; border-top: 2px solid black;">EQUIPO</th>
                            </tr>
                            <tr>
                                <th class="celdaGris" style="border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black; white-space:nowrap; padding:3px 2px;">MARCA:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</th>
                                <th class="celdaGris" style="border: 1px solid black; white-space:nowrap; padding:3px 2px;">FECHA DE CALIBRACIÓN:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['FEC_CAL'] }}</th>
                            </tr>
                            <tr>
                                <th class="celdaGris" style="border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black; white-space:nowrap; padding:3px 2px;">MODELO:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['MODELO_EQUIPO'] }}</th>
                                <th class="celdaGris" style="border: 1px solid black; white-space:nowrap; padding:3px 2px;">CERTIFICADO POR:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['CER_POR'] }}</th>
                            </tr>
                            <tr>
                                <th class="celdaGris" style="border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black; white-space:nowrap; padding:3px 2px;">SERIE:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['NS_EQUIPO'] }}</th>
                                <th class="celdaGris" style="border: 1px solid black; white-space:nowrap; padding:3px 2px;">RANGO DE MEDICIÓN:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['RAN_MED'] }}</th>
                            </tr>
                        </thead>
                </table>
                <!-- IMAGEN DE REFERENCIA 1-->
                @php
                    $bloquesPdf = \App\Http\Controllers\Reporte\PINS\FOR_PINS_17_01_01Controller::paraPdf($Datos_Equipo, $Fotos ?? []);
                @endphp
                                        @if(count($bloquesPdf))
                                            @php
                    $bloquePdf = $bloquesPdf[0];
                @endphp
                {{-- INICIO BLOQUE PDF --}}
                <div class="bloque-termografia-pdf" style="position:relative; width:100%; height:8.5cm; margin:0; padding:0; page-break-inside:avoid; font-family:Arial,sans-serif; font-size:9px;">
                    <div style="position:absolute; top:0; left:0; width:100%; height:0.7cm; overflow:hidden; background:#215e99; color:white; text-align:center; font-size:14px; font-weight:bold; line-height:0.7cm; outline:0.5pt solid black;">
                        {{ $bloquePdf['titulo'] ?? '' }}
                    </div>
                    @foreach($bloquePdf['fotos'] as $fotoBloque)
                        <div style="position:absolute; top:0.7cm; left:{{ $loop->index * 50 }}%; width:50%; height:7cm; overflow:hidden; text-align:center; line-height:7cm; outline:0.5pt solid black; font-size:0;">
                            @if($fotoBloque)
                                @php
                                    $medidasFoto = @getimagesize($fotoBloque);
                                    $proporcionFoto = $medidasFoto ? $medidasFoto[0] / max(1, $medidasFoto[1]) : 1;
                                    $anchoFoto = min(8.48, 6.98 * $proporcionFoto);
                                    $altoFoto = $anchoFoto / $proporcionFoto;
                                @endphp
                                <img src="{{ $fotoBloque }}" style="position:absolute; left:50%; margin-left:-{{ $anchoFoto / 2 }}cm; top:{{ (7 - $altoFoto) / 2 }}cm; width:{{ $anchoFoto }}cm; height:{{ $altoFoto }}cm;">
                            @endif
                        </div>
                    @endforeach
                    @foreach(['termograma' => 'Termograma:', 'emisividad' => 'Emisividad:'] as $campoBloque => $etiquetaBloque)
                        <div style="position:absolute; top:{{ $loop->first ? '7.7' : '8.1' }}cm; left:0; width:50%; height:0.4cm; overflow:hidden; text-align:center; line-height:0.4cm; background:#bfbfbf; font-weight:bold; outline:0.5pt solid black;">{{ $etiquetaBloque }}</div>
                        <div style="position:absolute; top:{{ $loop->first ? '7.7' : '8.1' }}cm; left:50%; width:50%; height:0.4cm; overflow:hidden; text-align:center; line-height:0.4cm; outline:0.5pt solid black;">{{ $bloquePdf[$campoBloque] ?? '' }}</div>
                    @endforeach
                </div>

                {{-- FIN BLOQUE PDF --}}
                            @if(count($bloquesPdf) === 1)
                                <br>
                <br>
                <!-- DATOS DE OPERACIÓN -->
                <table class="datosresultados" style="width:100%; font-size:10px; margin-top:-5mm; page-break-inside:avoid;">

                        <thead class="encabezadoAzul3" style="background:#0070c0; color:white; font-size:14px;">
                            <tr><th colspan="4">DATOS DE OPERACIÓN</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr>
                                <th class="" style="width: 25%;">VOLTAJE:</th>

                                <th class="" style="width: 25%;">{{ $Datos_Equipo['voltaje'] }}</th>

                                <th class="" style="width: 25%;">CARGA AMP:</th>

                                <th class="" style="width: 25%;">{{ $Datos_Equipo['CARGA_AMP'] }}</th>
                            </tr>
                        </thead>
                </table>

                <br>
                <br>
                <!-- TABLA DE SEVERIDAD -->
                <table class="datosresultados" style="width:100%; font-size:10px; margin-top:-8mm; page-break-inside:avoid;">

                        <thead class="encabezadoAzul3" style="background:#0070c0; color:white; font-size:14px;">
                            <tr><th colspan="6">TABLA DE SEVERIDAD</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="6"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr>
                                <th class="" style="font-weight:normal; width: 20%; color: #00b050;">BUENO:</th>

                                <th class="" style="font-weight:normal; width: 15%; @if($Datos_Equipo['severidad'] == 'bueno') background-color: #00b050; @endif"></th>

                                <th class="" style="font-weight:normal; width: 25%;">El equipo se encuentra en condiciones de operación optimas.</th>

                                <th class="" style="font-weight:normal; width: 20%; color: #ff6600;">PREVENTIVO</th>

                                <th class="" style="font-weight:normal; width: 15%; @if($Datos_Equipo['severidad'] == 'preventivo') background-color: #ff6600; @endif"></th>

                                <th class="" style="font-weight:normal; width: 25%;">Media anomalía programar el paro del equipo para su reparación.</th>
                            </tr>

                            <tr>
                                <th class="" style="font-weight:normal; width: 20%; color: #ffcc00;">MODERADO:</th>

                                <th class="" style="font-weight:normal; width: 15%; @if($Datos_Equipo['severidad'] == 'moderado') background-color: #ffcc00; @endif"></th>

                                <th class="" style="font-weight:normal; width: 25%;">Ligera anomalía, mantener en observación y programar reparación.</th>

                                <th class="" style="font-weight:normal; width: 20%; color: #ff0000;">NO ACEPTABLE:</th>

                                <th class="" style="font-weight:normal; width: 15%; @if($Datos_Equipo['severidad'] == 'no_aceptable') background-color: #ff0000; @endif"></th>

                                <th class="" style="font-weight:normal; width: 25%;">Anomalía severa, se recomienda parar el equipo para su intervención.</th>
                            </tr>
                        </thead>
                </table>

                            @endif
                        @endif
        </div>

        </body>
        </html>
        
