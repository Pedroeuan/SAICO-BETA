<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-16/01</title>
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
                    top: -65px; /* Ajusta para que no interfiera con el margen de la página */
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
                                INFORME DE INSPECCIÓN VISUAL A ELEMENTOS DE TUBERÍAS DE PROCESO
                            </th>
                            <th rowspan="4" style="width: 90%;">
                                @if(!empty($QR_PDF))
                                    <img src="{{ $QR_PDF }}" alt="QR" style="width:65px; height:65px; display:block; margin:auto; padding:0;">
                                @endif
                            </th>
                            <th style="width: 70%;">Código:</th>
                            <th style="width: 100%;">FOR-PINS-16/01</th>
                            <th rowspan="4" style="width: 90%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 60%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th>Versión</th>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th style="width: 90%;">Fecha de elaboración</th>
                            <th>18-may-26</th>
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
                <div style="margin-bottom: 5px;"></div>

                <table class="simbologia">
                    <thead>
                        <tr>
                            <th colspan="10<<" class="celdaAmarillo">SIMBOLOGÍA</th>
                        </tr>

                        <tr>
                            <td style="width: 20px;"><strong>DNR:</strong></td>
                            <td style="width: 100px;">DISTANCIA DE NIVEL DE REFERENCIA</td>
                            <td style="width: 20px;"><strong>d:</strong></td>
                            <td style="width: 100px;">PROFUNDIDAD DE LA INDICACION</td>
                            <td style="width: 30px;"><strong><span style="font-size: 15px; position: relative; top: 3px;"><sup>t</sup></span>a(in)</strong></td>
                            <td style="width: 100px;">ESPESOR DE LA PARED EN ZONA SANA ADYACENTE</td>
                            <td style="width: 40px;"><strong>C.E. GEN.:</strong></td>
                            <td style="width: 100px;">CORROSIÓN EXTERNA GENERALIZADA</td> 
                            <td style="width: 20px;"><strong>SIR: </strong></td>
                            <td style="width: 70px;">SIN INDICACIONES RELEVANTES</td>
                        </tr>
                    </thead>
                </table>

                    <div style="margin-bottom: 3px;"></div>

                    <table class="datosgenerales">                               
                        <tr>                                     
                            <th  >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 805px;">{{ $Datos_Equipo['Observaciones'] }}</td>                            
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
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                                    <td style="width: 30px;"></td>
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
                                    <td style="width: 10px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo3'] }}</th>
                                    <td style="width: 30px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
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
            $tablaCombinacionConfig = $tablaCombinacionConfig ?? ($Datos_Equipo['TABLA_COMBINACION_CONFIG'] ?? []);
            if (is_string($tablaCombinacionConfig)) {
                $tablaCombinacionConfig = json_decode($tablaCombinacionConfig, true);
            }
            $tablaCombinacionConfig = is_array($tablaCombinacionConfig) ? $tablaCombinacionConfig : [];

            $resolverCombinacionTabla = function (array $config, string $grupo, string $campo, int $fila, int $inicioBloque, int $finBloque) {
                foreach ($config as $merge) {
                    if (!is_array($merge)) continue;
                    $inicio = (int) ($merge['startRow'] ?? -1);
                    $fin = $inicio + (int) ($merge['rowspan'] ?? 1) - 1;
                    if (($merge['groupId'] ?? 'sin_titulo') === $grupo && ($merge['field'] ?? '') === $campo && $fila >= $inicio && $fila <= $fin) {
                        $inicioSegmento = max($inicio, $inicioBloque);
                        $finSegmento = min($fin, $finBloque);
                        return [
                            'mostrar' => $fila === $inicioSegmento,
                            'ocultar' => $fila > $inicioSegmento,
                            'rowspan' => max(1, $finSegmento - $inicioSegmento + 1),
                        ];
                    }
                }
                return null;
            };

            $columnasResultadoPdf = [
                ['field'=>'ID','valueKey'=>'ID'], ['field'=>'Elemento','valueKey'=>'Elemento'],
                ['field'=>'No_Indicacion','valueKey'=>'No_Indicacion'], ['field'=>'Tipo_Indicacion','valueKey'=>'Tipo_Indicacion'],
                ['field'=>'Referencia','valueKey'=>'Referencia'], ['field'=>'DNR','valueKey'=>'DNR'],
                ['field'=>'HT','valueKey'=>'HT'], ['field'=>'Long_Axial','valueKey'=>'Long_Axial'],
                ['field'=>'Long_Circ','valueKey'=>'Long_Circ'], ['field'=>'d','valueKey'=>'d'],
                ['field'=>'ta','valueKey'=>'ta'], ['field'=>'Perdida','valueKey'=>'Perdida'],
                ['field'=>'Espesor_remanente','valueKey'=>'Espesor_remanente'], ['field'=>'Observaciones','valueKey'=>'Observaciones'],
            ];
            $contadorFilasPorGrupo = [];
        @endphp
        @foreach ($Grupo_Juntas_Detalles_Re as $bloque)
            @php
                $inicioGrupoEnBloque = $contadorFilasPorGrupo;
                $cantidadGrupoEnBloque = [];
                foreach ($bloque as $elementoBloque) {
                    if (is_array($elementoBloque) && ($elementoBloque['tipo'] ?? null) === 'fila') {
                        $grupoBloque = $elementoBloque['grupo'] ?? 'sin_titulo';
                        $cantidadGrupoEnBloque[$grupoBloque] = ($cantidadGrupoEnBloque[$grupoBloque] ?? 0) + 1;
                    }
                }
            @endphp
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
                            <th>PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Procedimiento'] }}</td>
                            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Criterio_Evaluacion'] }}</td>
                        </tr>
                        <tr>
                            <th>TIPO E INTENSIDAD DE ILUMINACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Iluminacion'] ?? '' }}</td>
                            <th style="width: 160px;">TIPO DE INSPECCIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Tipo_Inspeccion'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                @include('Reportes.ReportesPDF.partials.equipos_herramientas_pdf')

                    <table class="datosresultados">
                    
                        <thead class="encabezadoAzul">
                            <tr><th colspan="14">RESULTADOS</th></tr>
                        </thead>

                        <thead><tr class="sinBordeth"><th colspan="14"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                <th style="width: 50px; border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;">ID</th>
                                <th style="width: 50px; border: 1px solid black;">Elemento</th>
                                <th style="width: 50px; border: 1px solid black;">No. Indicación</th>
                                <th style="width: 50px; border: 1px solid black;">Tipo de Indicación</th>
                                <th style="width: 50px; border: 1px solid black;">Referencia</th>
                                <th style="width: 50px; border: 1px solid black;">DNR (m)</th>
                                <th style="width: 50px; border: 1px solid black;">H.T.</th>
                                <th style="width: 50px; border: 1px solid black;">Long. Axial  (in)</th>
                                <th style="width: 50px; border: 1px solid black;">Long. Circ. (in)</th>
                                <th style="width: 50px; border: 1px solid black;">d(in)</th>
                                <th style="width: 50px;"><span style="font-size: 15px; position: relative; top: 3px;"><sup>t</sup></span>a(in)</th>
                                <th style="width: 50px;">%Perdida</th>
                                <th style="width: 50px; border: 1px solid black;">Espesor remanente (in)</th>
                                <th style="width: 50px; border: 1px solid black;">Observaciones</th>
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
                                                    <td colspan="14" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                @php
                                                    $grupoActual = $item['grupo'] ?? 'sin_titulo';
                                                    $indiceFilaGrupo = $contadorFilasPorGrupo[$grupoActual] ?? 0;
                                                    $inicioBloqueGrupo = $inicioGrupoEnBloque[$grupoActual] ?? 0;
                                                    $finBloqueGrupo = $inicioBloqueGrupo + ($cantidadGrupoEnBloque[$grupoActual] ?? 1) - 1;
                                                @endphp
                                                <tr class="juntas">
                                                    @foreach ($columnasResultadoPdf as $columnaPdf)
                                                        @php
                                                            $merge = $resolverCombinacionTabla($tablaCombinacionConfig, $grupoActual, $columnaPdf['field'], $indiceFilaGrupo, $inicioBloqueGrupo, $finBloqueGrupo);
                                                            $valor = $item['data'][$columnaPdf['valueKey']] ?? '';
                                                        @endphp
                                                        @if ($merge && $merge['mostrar'])
                                                            <td rowspan="{{ $merge['rowspan'] }}">{{ $valor }}</td>
                                                        @elseif (!$merge || !$merge['ocultar'])
                                                            <td>{{ $valor }}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                @php $contadorFilasPorGrupo[$grupoActual] = $indiceFilaGrupo + 1; @endphp
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
