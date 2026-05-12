<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-03/01</title>
            <style>
                @page {
                    margin: 3.0cm 1.2cm 2.1cm 2.2cm;
                }

                header {
                    position: fixed;
                    top: -42px; /* Sube el encabezado para que no se monte con "DATOS GENERALES" */
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
                    margin-top: 25px;
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
                            <th style="width: 400%;">FORMATO</th>
                            <th style="width: 70%;">Código:</th>
                            <th style="width: 80%;">FOR-INS-03/01</th>
                            <th rowspan="3" style="width: 80%;"><img src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE INSPECCIÓN CON LÍQUIDOS PENETRANTES </th>
                            <th>Versión</th>
                            <th>3</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 0px;"></div>
            </header>

            <footer>
                    <table class="simbologia">
                        <thead>
                            <tr>
                                <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA</th>
                            </tr>

                            <tr>
                                <td style="width: 20px;" class="celdaGris"><strong>NPIR</strong></td>
                                <td style="width: 110px;">NO PRESENTA INDICACIÓN RELEVANTE</td>
                                <td style="width: 20px;" class="celdaGris"><strong>DM</strong></td>
                                <td style="width: 150px;">DAÑO MECÁNICO</td>
                                <td style="width: 20px;" class="celdaGris"><strong>PT</strong></td>
                                <td style="width: 180px;">POROSIDAD TUBULAR</td>
                            </tr>

                            <tr>
                                <td class="celdaGris"><strong>G</strong></td>
                                <td>GRIETA</td>
                                <td class="celdaGris"><strong>S</strong></td>
                                <td>SOCAVADO</td>
                                <td class="celdaGris"><strong>C</strong></td>
                                <td>CRATER</td>
                            </tr>

                            <tr>
                                <td class="celdaGris"><strong>ZG</strong></td>
                                <td>ZONA DE GRIETAS</td>
                                <td class="celdaGris"><strong>P</strong></td>
                                <td>POROSIDAD</td>
                                <td class="celdaGris"><strong>IL</strong></td>
                                <td>INDICACIÓN LINEAL</td>
                            </tr>

                            <tr>
                                <td class="celdaGris"><strong>FF</strong></td>
                                <td>FALTA DE FUSIÓN</td>
                                <td class="celdaGris"><strong>ZP</strong></td>
                                <td>ZONA DE POROS</td>
                                <td class="celdaGris"><strong>IR</strong></td>
                                <td>INDICACIÓN REDONDEADA</td>
                            </tr>
                        </thead>
                    </table>
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

        @foreach ($Grupo_Juntas_Detalles_Re as $bloque)
            <div class="content">
                <div class="content-separador"></div>
                <table class="datosgenerales">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="4">DATOS GENERALES</th></tr>
                    </thead>   
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
                            <th style="width: 160px;">CÓDIGO APLICABLE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Codigo_Aplicable'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DE LA INSPECCIÓN</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead> <!-- Fila vacia -->

                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 60px;">ITEM</th>
                                <th style="width: 100px;">MARCA</th>
                                <th style="width: 100px;">MODELO</th>
                                <th style="width: 100px;">LOTE</th>
                                <th style="width: 100px;">TIEMPO</th>
                                <th style="width: 100px;">APLICACIÓN</th>
                                <th style="width: 100px;">TIPO/GRUPO</th>
                            </tr>
                            <tr>
                                <th class="celdaGris">PENETRANTE:</th>
                                <td>{{ $Datos_Equipo['MARCA_PENETRANTES'] }}</td>
                                <td>{{ $Datos_Equipo['MODELO_PENETRANTES'] }}</td>
                                <td>{{ $Datos_Equipo['LOTE_PENETRANTES'] }}</td>
                                <td>{{ $Datos_Equipo['TIEMPO_PENETRANTES'] }}</td>
                                <td>{{ $Datos_Equipo['APLICACION_PENETRANTES'] }}</td>
                                <td>{{ $Datos_Equipo['TIPO_GRUPO_PENETRANTES'] }}</td>
                            </tr>
                            <tr>
                                <th class="celdaGris">REMOVEDOR:</th>
                                <td>{{ $Datos_Equipo['MARCA_REMOVEDOR'] }}</td>
                                <td>{{ $Datos_Equipo['MODELO_REMOVEDOR'] }}</td>
                                <td>{{ $Datos_Equipo['LOTE_REMOVEDOR'] }}</td>
                                <td>{{ $Datos_Equipo['TIEMPO_REMOVEDOR'] }}</td>
                                <td>{{ $Datos_Equipo['APLICACION_REMOVEDOR'] }}</td>
                                <td>{{ $Datos_Equipo['TIPO_GRUPO_REMOVEDOR'] }}</td>
                            </tr>
                            <tr>
                                <th class="celdaGris">REVELEADOR:</th>
                                <td>{{ $Datos_Equipo['MARCA_REVELEADOR'] }}</td>
                                <td>{{ $Datos_Equipo['MODELO_REVELEADOR'] }}</td>
                                <td>{{ $Datos_Equipo['LOTE_REVELEADOR'] }}</td>
                                <td>{{ $Datos_Equipo['TIEMPO_REVELEADOR'] }}</td>
                                <td>{{ $Datos_Equipo['APLICACION_REVELEADOR'] }}</td>
                                <td>{{ $Datos_Equipo['TIPO_GRUPO_REVELEADOR'] }}</td>
                            </tr>

                        </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 10%;">TIPO DE LUZ:</th>
                            <td class="lineaInferior">{{$Datos_Equipo['TIPO_LUZ']}}</td>
                            <th style="width: 10%;">INTENCIDAD:</th>
                            <td class="lineaInferior">{{$Datos_Equipo['INTENCIDAD']}}</td> <th style="text-align: left; width: 5%;"> Lx </th>
                            <th style="width: 10%;">CONDICIÓN SUPERFICIAL:</th>
                            <td class="lineaInferior">{{$Datos_Equipo['CONDICION_SUPERFICIAL']}}</td>
                            <th style="width: 10%;">TEMPERATURA DE PRUEBA:</th>
                            <td class="lineaInferior">{{$Datos_Equipo['TEMPERATURA_PRUEBA']}}</td> <th style="text-align: left; width: 5%;"> °C </th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>
            
                    <table class="datosresultados">

                        <thead class="encabezadoAzul">
                            <tr><th colspan="10">RESULTADOS</th></tr>
                        </thead>

                            <thead><tr class="sinBordeth"><th colspan="10"></th></tr></thead> <!-- Fila vacia -->

                            <thead>
                                <tr class="celdaGris">
                                    <th rowspan= "2">No.</th>
                                    <th rowspan= "2" style="width: 10%;">No. De Junta / Componente</th>
                                    <th rowspan= "2">No. Indicación</th>
                                    <th rowspan= "2">Tipo de Indicación</th>
                                    <th colspan= "3">Dim. De Indicación</th>

                                    <th>Localización</th>
                                    <th rowspan= "2">Evaluación</th>
                                    <th rowspan= "2">Longitud Inspeccionada</th>
                                </tr>
                                <tr class="celdaGris">
                                    <th>Largo</th>
                                    <th>Ancho</th>
                                    <th>Ø</th>
                                    <th>H.T.</th>
                                </tr>
                            </thead>
                                <tbody>
                                    @foreach ($bloque as $item)
                                        @php
                                            //dd($bloque);
                                        @endphp
                                        @if (!is_array($item))
                                            @continue
                                        @endif

                                        {{-- TITULO --}}
                                        @if (($item['tipo'] ?? null) == 'titulo')
                                            <tr class="titulo-row">
                                                <td colspan="10" style="border:.5px solid black;">
                                                    {{ $item['texto'] }}
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- FILA --}}
                                        @if (($item['tipo'] ?? null) == 'fila')
                                            <tr class="juntas">
                                                <td>{{ $item['data']['No'] ?? '' }}</td>
                                                <td>{{ $item['data']['componente'] ?? '' }}</td>
                                                <td>{{ $item['data']['no_indicacion'] ?? '' }}</td>
                                                <td>{{ $item['data']['tipo_indicacion'] ?? '' }}</td>
                                                <td>{{ $item['data']['largo'] ?? '' }}</td>
                                                <td>{{ $item['data']['ancho'] ?? '' }}</td>
                                                <td>{{ $item['data']['diametro'] ?? '' }}</td>
                                                <td>{{ $item['data']['ht'] ?? '' }}</td>
                                                <td>{{ $item['data']['evaluacion'] ?? '' }}</td>
                                                <td>{{ $item['data']['long_inspeccionada'] ?? '' }}</td>
                                            </tr>
                                        @endif

                                        {{-- LONGITUD --}}
                                        @if (($item['tipo'] ?? null) == 'longitud')
                                            <tr class="sinBordetd">
                                                <td colspan="2"></td>
                                                <th colspan="3">Longitud inspeccionada:</th>
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
