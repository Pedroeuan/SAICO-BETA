
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
                    top: -58px; /* Sube el encabezado para que no se monte con "DATOS GENERALES" */
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
                            <th rowspan="3" style="width: 80%;">
                                @if(!empty($QR_PDF))
                                    <img src="{{ $QR_PDF }}" alt="QR" style="width:65px; height:65px; display:block; margin:auto; padding:0;">
                                @endif
                            </th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%;">FOR-PINS-17-01/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;"></th>
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
                <table class="datosresultados">

                        <thead class="encabezadoAzul">
                            <tr><th colspan="4">DATOS Y AJUSTES DEL EQUIPO</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                <th colspan="4" style="border: 1px solid black; border-left: 2px solid black; border-top: 2px solid black;">EQUIPO</th>
                            </tr>
                            <tr>
                                <th class="celdaGris" style="width: 80px; border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;">MARCA:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</th>
                                <th class="celdaGris" style="width: 80px; border: 1px solid black;">FECHA DE CALIBRACIÓN:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['FEC_CAL'] }}</th>
                            </tr>
                            <tr>
                                <th class="celdaGris" style="border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;">MODELO:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['MODELO_EQUIPO'] }}</th>
                                <th class="celdaGris" style="border: 1px solid black;">CERTIFICADO POR:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['CER_POR'] }}</th>
                            </tr>
                            <tr>
                                <th class="celdaGris" style="border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;">SERIE:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['NS_EQUIPO'] }}</th>
                                <th class="celdaGris" style="border: 1px solid black;">RANGO DE MEDICIÓN:</th>
                                <th style="border: 1px solid black;">{{ $Datos_Equipo['RAN_MED'] }}</th>
                            </tr>
                        </thead>
                </table>
                <br>
                <br>
                <br>
                <br>
                <!-- IMAGEN DE REFERENCIA 1-->
                <table class="datosresultados">

                        <thead class="encabezadoAzul2">
                            <tr><th colspan="2">{{ $Datos_Equipo['Stndr_refe1'] }}</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="2"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr>
                                <th style="width: 10%;">        
                                    @if(isset($Fotos[1]))
                                        <img src="{{ $Fotos[1] }}" style="width:100%; height:auto;">
                                    @endif
                                </th>

                                <th style="width: 10%;">        
                                    @if(isset($Fotos[2]))
                                        <img src="{{ $Fotos[2] }}" style="width:100%; height:auto;">
                                    @endif
                                </th>

                            </tr>
                            <tr>
                                <th class="celdaGrisOscuro" style="border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;">Termograma:</th>

                                <th class="celdaGrisOscuro" style="border: 1px solid black;">{{ $Datos_Equipo['termograma1'] }}</th>

                            </tr>
                            <tr>
                                <th class="celdaGrisOscuro" style="border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;">Emisividad:</th>

                                <th class="celdaGrisOscuro" style="border: 1px solid black;">{{ $Datos_Equipo['emisividad1'] }}</th>

                            </tr>
                        </thead>
                </table>
        </div>

        </body>
        </html>
        
