<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-25-01/01</title>
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
                    font-size: 8px !important;
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
                            <th rowspan="4" style="width: 400%; font-size: 9pt;">
                                INSPECCIÓN VISUAL EN RSP
                            </th>
                            <th rowspan="4" style="width: 80%;">
                                @if(!empty($QR_PDF))
                                    <img src="{{ $QR_PDF }}" alt="QR" style="width:65px; height:65px; display:block; margin:auto; padding:0;">
                                @endif
                            </th>

                            <th style="width: 90%;">Código:</th>
                            <th style="width: 100%;">FOR-PINS-25/01</th>
                            <th rowspan="4" style="width: 90%;">
                                <img  src="{{ $Logo }}" alt="Logo" style="width: 60%; height: auto;">  
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th>Versión</th>
                            <th>0</th>
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

                <div style="margin-bottom: 4px;"></div>
            </header>
            <footer>
                    <table class="datosgenerales">
                        <thead>
                            @if( $numFirmas == 1)
                            <!-- 1 Firmas -->
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 30px; height:40px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                </tr>
                            @elseif( $numFirmas == 2)
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
                $tablaCombinacionConfigComponentes = $tablaCombinacionConfigComponentes ?? ($Datos_Equipo['TABLA_COMBINACION_CONFIG_COMPONENTES'] ?? []);

                if (is_string($tablaCombinacionConfigComponentes)) {
                    $tablaCombinacionConfigComponentes = json_decode($tablaCombinacionConfigComponentes, true);
                }

                if (!is_array($tablaCombinacionConfigComponentes)) {
                    $tablaCombinacionConfigComponentes = [];
                }

                $tablaCombinacionConfigComponentes = array_values(array_filter(array_map(function ($merge) {
                    if (!is_array($merge)) {
                        return null;
                    }

                    $groupId = !empty($merge['groupId']) ? (string) $merge['groupId'] : 'sin_titulo';
                    $field = (string) ($merge['field'] ?? '');
                    $startRow = isset($merge['startRow']) ? (int) $merge['startRow'] : -1;
                    $rowspan = isset($merge['rowspan']) ? (int) $merge['rowspan'] : 1;

                    if ($field === '' || $startRow < 0 || $rowspan < 2) {
                        return null;
                    }

                    return [
                        'groupId' => $groupId,
                        'field' => $field,
                        'startRow' => $startRow,
                        'rowspan' => $rowspan,
                    ];
                }, $tablaCombinacionConfigComponentes)));

                $resolverCombinacionComponentes = function (array $mergeConfig, string $groupId, string $field, int $rowIndex, int $inicioBloque, int $finBloque) {
                    foreach ($mergeConfig as $merge) {
                        $inicio = (int) ($merge['startRow'] ?? -1);
                        $fin = $inicio + (int) ($merge['rowspan'] ?? 1) - 1;

                        if (
                            ($merge['groupId'] ?? 'sin_titulo') === $groupId &&
                            ($merge['field'] ?? '') === $field &&
                            $rowIndex >= $inicio &&
                            $rowIndex <= $fin
                        ) {
                            // Limita la combinacion a las filas de esta hoja para evitar
                            // que un rowspan atraviese el salto y dañe el ancho de la tabla.
                            $inicioSegmento = max($inicio, $inicioBloque);
                            $finSegmento = min($fin, $finBloque);

                            return [
                                'mostrar' => $rowIndex === $inicioSegmento,
                                'ocultar' => $rowIndex > $inicioSegmento,
                                'rowspan' => max(1, $finSegmento - $inicioSegmento + 1),
                            ];
                        }
                    }

                    return null;
                };

                $columnasComponentesPdf = [
                    ['field' => 'Componentes_ID', 'valueKey' => 'ID'],
                    ['field' => 'Componentes_Descripcion_del_Elemento', 'valueKey' => 'Descripcion_del_Elemento'],
                    ['field' => 'Componentes_0', 'valueKey' => '0'],
                    ['field' => 'Componentes_Longitud_in', 'valueKey' => 'Longitud_(in)'],
                    ['field' => 'Componentes_Tipo_conexion', 'valueKey' => 'Tipo_conexion'],
                    ['field' => 'Componentes_Servicio', 'valueKey' => 'servicio'],
                    ['field' => 'Componentes_Clase', 'valueKey' => 'Clase'],
                    ['field' => 'Componentes_Especificacion_material', 'valueKey' => 'Especificación_material'],
                    ['field' => 'Componentes_Observaciones', 'valueKey' => 'Observaciones'],
                ];

                $contadorFilasComponentesPorGrupo = [];
            @endphp
            @foreach ($Grupo_Juntas_Detalles_Re as $bloque)
            @php
                $inicioGrupoComponentesEnBloque = $contadorFilasComponentesPorGrupo;
                $cantidadGrupoComponentesEnBloque = [];
                foreach ($bloque as $elementoBloque) {
                    if (is_array($elementoBloque) && ($elementoBloque['tipo'] ?? null) === 'fila') {
                        $grupoBloque = $elementoBloque['grupo'] ?? 'sin_titulo';
                        $cantidadGrupoComponentesEnBloque[$grupoBloque] = ($cantidadGrupoComponentesEnBloque[$grupoBloque] ?? 0) + 1;
                    }
                }
            @endphp

            {{-- ================= DATOS GENERALES ================= --}}
            <div class="content">
                <table class="datosgenerales">

                    <thead class="encabezadoAzul">

                        <tr><th colspan="4">DATOS GENERALES</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

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
                            <th>PROYECTO:</th>
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
                        <tr>
                            <th style="width: 80px; line-height:1.1;">TIPO E INTENSIDAD<br>DE ILUMINACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Iluminacion'] ?? '' }}</td>
                            <th style="width: 160px;">TIPO DE INSPECCIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Inspeccion'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>

            <div style="margin-bottom: 5px;"></div>

            @include('Reportes.ReportesPDF.partials.equipos_herramientas_pdf')

            {{-- ================= ENCABEZADO RESULTADOS ================= --}}
                    <table class="datosresultados">
                    
                        <thead class="encabezadoAzul">
                            <tr><th colspan="9">RESULTADOS</th></tr>
                        </thead>
            {{-- ================= TABLA RESULTADOS ================= --}}
                        <thead><tr class="sinBordeth"><th colspan="9"></th></tr></thead> <!-- Fila vacia -->

                        <thead>
                            <tr class="celdaGris">
                                <th style="width: 30px;">ID</th>
                                <th style="width: 50px;">DESCRIPCIÓN DEL ELEMENTO</th>
                                <th style="width: 30px;">Ø nom (in)</th>
                                <th style="width: 30px;">Longitud (in)</th>
                                <th style="width: 30px;">Tipo de Conexión</th>
                                <th style="width: 30px;">Servicio</th>
                                <th style="width: 30px;">Clase</th>
                                <th style="width: 50px;">Especificación de material</th>
                                <th style="width: 100px;">Observaciones</th>
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
                                                    <td colspan="9" style="border:.5px solid black;">
                                                        {{ $item['texto'] }}
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- FILA --}}
                                            @if (($item['tipo'] ?? null) == 'fila')
                                                @php
                                                    $grupoActual = $item['grupo'] ?? 'sin_titulo';
                                                    $indiceFilaGrupo = $contadorFilasComponentesPorGrupo[$grupoActual] ?? 0;
                                                @endphp
                                                <tr class="juntas">
                                                    @foreach ($columnasComponentesPdf as $columnaPdf)
                                                        @php
                                                            $inicioBloqueGrupo = $inicioGrupoComponentesEnBloque[$grupoActual] ?? 0;
                                                            $finBloqueGrupo = $inicioBloqueGrupo + ($cantidadGrupoComponentesEnBloque[$grupoActual] ?? 1) - 1;
                                                            $mergeColumna = $resolverCombinacionComponentes($tablaCombinacionConfigComponentes, $grupoActual, $columnaPdf['field'], $indiceFilaGrupo, $inicioBloqueGrupo, $finBloqueGrupo);
                                                            $valorCelda = $item['data'][$columnaPdf['valueKey']] ?? '';
                                                        @endphp
                                                        @if ($mergeColumna && $mergeColumna['mostrar'])
                                                            <td rowspan="{{ $mergeColumna['rowspan'] }}">{{ $valorCelda }}</td>
                                                        @elseif (! $mergeColumna || ! $mergeColumna['ocultar'])
                                                            <td>{{ $valorCelda }}</td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                                @php
                                                    $contadorFilasComponentesPorGrupo[$grupoActual] = $indiceFilaGrupo + 1;
                                                @endphp
                                            @endif

                                            {{-- LONGITUD 
                                            @if (($item['tipo'] ?? null) == 'longitud')
                                                <tr class="sinBordetd">
                                                    <td colspan="12"></td>
                                                    <th colspan="4">Longitud inspeccionada:</th>
                                                    <th>{{ $item['valor'] ?? '' }} m</th>
                                                </tr>
                                            @endif
                                            --}}
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
