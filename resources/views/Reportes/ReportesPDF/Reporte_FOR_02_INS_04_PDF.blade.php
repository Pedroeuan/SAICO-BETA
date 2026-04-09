<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FORMATO FOR-INS-04/02</title>
    <style>
        @page {
            margin:
            3.0cm
            1.2cm
            2.1cm
            2.2cm;
        }

        body {
            margin-top: 44px;
            padding-top: 0;
            padding-bottom: 0;
            font-family: Arial, sans-serif;
        }

        header {
            position: fixed;
            top: -38px;
            left: 0;
            right: 0;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        footer {
            position: fixed;
            bottom: -24px;
            left: 0;
            right: 0;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .tablaheader {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            text-align: center;
            font-size: 9px;
        }

        .tablaheader th {
            border: 1px solid #000;
            padding: 2px 3px;
            vertical-align: middle;
        }

        .encabezadoAzul {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 7px;
            background-color: #2F75B5;
            color: #fff;
            outline: 1px double #000;
        }

        .encabezadoAzul th {
            padding: 2px 3px;
        }

        .datosgenerales {
            width: 100%;
            border-collapse: collapse;
            border: 0 !important;
            font-size: 6.3px;
        }

        .datosgenerales th,
        .datosgenerales td {
            padding: 1px 1px;
            vertical-align: middle;
            word-break: break-word;
        }

        .datosgenerales th {
            text-align: left;
            font-weight: bold;
        }

        .datosgenerales td {
            text-align: left;
        }

        .lineaInferior {
            border-bottom: 1px solid #000;
            text-align: center;
            font-size: 6.3px;
        }

        .firmaLinea {
            border-bottom: 1px solid #000;
            text-align: center;
            font-size: 6.3px;
        }

        .datosinspeccion {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            text-align: center;
            font-size: 6.2px;
        }

        .datosinspeccion th,
        .datosinspeccion td {
            border: .6px solid #000;
            padding: 1px 2px;
            vertical-align: middle;
            word-break: break-word;
        }

        .datosinspeccionsinborde {
            width: 100%;
            border-collapse: collapse;
            border: 0 !important;
            font-size: 6.3px;
        }

        .datosinspeccionsinborde th,
        .datosinspeccionsinborde td {
            padding: 1px 2px;
            vertical-align: middle;
        }

        .datosinspeccionsinborde th {
            text-align: left;
            font-weight: bold;
        }

        .datosresultados {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            text-align: center;
            font-size: 6.5px;
        }

        .datosresultados th,
        .datosresultados td {
            border: .6px solid #000;
            padding: 1px 1px;
            line-height: 1.15;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
            text-align: center;
        }

        .datosresultados thead th {
            font-size: 6px;
        }

        .celdaGris {
            background-color: #DBDBDB;
            font-size: 6px;
        }

        .sinBordeth th,
        .sinBordetd td,
        .sinBordetdth th,
        .sinBordetdth td {
            border: 0 !important;
            border-collapse: collapse;
        }

        .titulo-row td {
            font-weight: bold;
            text-align: left;
            padding-left: 6px;
        }

        .juntas {
            font-size: 6.6px;
        }

        .firma-footer {
            table-layout: fixed;
            font-size: 6.2px !important;
            line-height: 1.1;
        }

        .firma-footer th,
        .firma-footer td {
            padding: 0 1px;
            text-align: center;
            vertical-align: top;
        }

        .header-title {
            font-size: 8.2pt;
            line-height: 1.12;
            padding: 5px 4px;
        }
        .firma-contenedor {
    width: 100%;
    text-align: center;
    margin-top: 5px;
}

    .firma-grid {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    .firma-col {
        display: table-cell;
        text-align: center;
        vertical-align: top;
        padding: 0 10px;
    }

    .firma-titulo {
        display: block;
        margin-bottom: 8px; /* separación título - línea */
        font-weight: bold;
    }

    .firma-linea {
        border-bottom: 1px solid #000;
        height: 35px;
        margin: 0 auto 6px auto;
        width: 80%;
    }

    .firma-texto {
        display: block;
        line-height: 1.2;
    }
    </style>
</head>
<body>
    <header>
        <table class="tablaheader">
            <thead>
                <tr>
                    <th style="width: 60%;">FORMATO</th>
                    <th style="width: 12%;">Código:</th>
                    <th style="width: 12%;">FOR-INS-04/02</th>
                    <th rowspan="3" style="width: 16%;"><img src="{{ $Logo }}" alt="Logo" style="width: 62%; max-height: 58px;"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th rowspan="2" class="header-title">INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO, DE ACUERDO CON AWS D1.1 PARA COMPONENTES TUBULARES</th>
                    <th>Versión</th>
                    <th>2</th>
                </tr>
                <tr>
                    <th>Página</th>
                    <th></th>
                </tr>
            </tbody>
        </table>
        <div style="margin-bottom: 2px;"></div>
    </header>

    <footer>
        <br>

        <table class="datosgenerales" style="width: 100%;">                               
            <tr>                                     
                <th style="width: 15%; text-align: left;">OBSERVACIONES:</th>                                         
                <td class="lineaInferior" style="width: 85%;">{{ $Datos_Equipo['Observaciones'] ?? '' }}</td>                            
            </tr>                      
        </table>

        <br>

        <table class="datosgenerales">
            <div class="firma-contenedor">
    <div class="firma-grid">

        @if($numFirmas == 2)

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.' }}</strong></span>
            </div>

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></span>
            </div>

        @elseif($numFirmas == 3)

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.' }}</strong></span>
            </div>

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></span>
            </div>

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Vobo2'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</strong></span>
            </div>

        @elseif($numFirmas == 4)

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.' }}</strong></span>
            </div>

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></span>
            </div>

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Vobo2'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</strong></span>
            </div>

            <div class="firma-col">
                <span class="firma-titulo">{{ $Firmas_Reportes['Vobo3'] ?? '' }}</span>
                <div class="firma-linea"></div>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] ?? '' }}</strong></span>
                <span class="firma-texto"><strong>{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] ?? '' }}</strong></span>
            </div>

        @endif

    </div>
</div>                      
        </table>
    </footer>

    @foreach ($Grupo_Juntas_Detalles_Re as $grupo)
        @php
            $marcaEquipo = $Datos_Equipo['MARCA_EQUIPO'] ?? '---';
            $modeloEquipo = $Datos_Equipo['MODELO_EQUIPO'] ?? '---';
            $serieEquipo = $Datos_Equipo['N_S_EQUIPO'] ?? '---';
            $marcaTransductor = $Datos_Equipo['MARCA_TRANSDUCTOR'] ?? '---';
            $modeloTransductor = $Datos_Equipo['MODELO_TRANSDUCTOR'] ?? '---';
            $serieTransductor = $Datos_Equipo['N_S_TRANSDUCTOR'] ?? '---';
            $frecTransductor = $Datos_Equipo['FREC_TRANSDUCTOR'] ?? '---';
            $marcaBlock = $Datos_Equipo['MARCA_BLOCK'] ?? '---';
            $modeloBlock = $Datos_Equipo['MODELO_BLOCK'] ?? '---';
            $serieBlock = $Datos_Equipo['N_S_BLOCK'] ?? '---';
            $acoplante = $Datos_Equipo['ACOPLANTE'] ?? '---';
            $ganancia = $Datos_Equipo['GANANCIA'] ?? '---';
            $tipoJunta = $Datos_Equipo['TIPO_JUNTA'] ?? '---';
            $rechazo = $Datos_Equipo['RECHAZO'] ?? '---';
            $diametro = $Datos_Equipo['DIAMETRO'] ?? '---';
            $retardo = $Datos_Equipo['RETARDO'] ?? '---';
            $espesor = $Datos_Equipo['ESPESOR'] ?? '---';
        @endphp

        <div class="content">
            <table class="encabezadoAzul">
                <tr>
                    <th colspan="4">DATOS GENERALES</th>
                </tr>
            </table>

            <div style="margin-bottom: 2px;"></div>

            <table class="datosgenerales">
                <tbody>
                    <tr>
                        <th style="width: 12%; text-align: center;">FECHA:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Fecha'] }}</td>
                        <th style="width: 15%; text-align: center;">NO. REPORTE:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['No_Reporte'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">CLIENTE:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Cliente'] }}</td>
                        <th style="text-align: center;">CONTRATO:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Contrato'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">PROYECTO:</th>
                        <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Proyecto'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">ORDEN DE TRABAJO:</th>
                        <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Orden_Trabajo'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">FOLIO:</th>
                        <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Folio'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">PARTIDA:</th>
                        <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Partida'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">LUGAR:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Lugar'] }}</td>
                        <th style="text-align: center;">ISOMETRICO/PLANO:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Isometrico_Plano'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">PIEZA:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Pieza'] }}</td>
                        <th style="text-align: center;">MATERIAL:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Material'] }}</td>
                    </tr>
                    <tr>
                        <th style="text-align: center;">PROCEDIMIENTO:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Procedimiento'] }}</td>
                        <th style="width: 21%; text-align: center;">CRITERIO DE EVALUACIÓN:</th>
                        <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Criterio_Evaluacion'] }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-bottom: 2px;"></div>

            <table class="datosinspeccion">
                <thead class="encabezadoAzul">
                    <tr>
                        <th colspan="9">DATOS DEL EQUIPO</th>
                    </tr>
                </thead>
                <thead>
                    <tr class="sinBordeth">
                        <th colspan="9"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="celdaGris">
                        <th colspan="2">EQUIPO</th>
                        <th colspan="4">TRANSDUCTOR</th>
                        <th colspan="2">BLOCK DE REFERENCIA</th>
                        <th>ACOPLANTE</th>
                    </tr>
                    <tr>
                        <th class="celdaGris" style="width: 10%;">MARCA:</th>
                        <td style="width: 15%;">{{ $marcaEquipo }}</td>
                        <th class="celdaGris" style="width: 10%;">MARCA:</th>
                        <td colspan="3">{{ $marcaTransductor }}</td>
                        <th class="celdaGris" style="width: 10%;">MARCA:</th>
                        <td style="width: 15%;">{{ $marcaBlock }}</td>
                        <th class="celdaGris" style="width: 15%;">MARCA Y TIPO</th>
                    </tr>
                    <tr>
                        <th class="celdaGris">MODELO:</th>
                        <td>{{ $modeloEquipo }}</td>
                        <th class="celdaGris">MODELO:</th>
                        <td colspan="3">{{ $modeloTransductor }}</td>
                        <th class="celdaGris">MODELO:</th>
                        <td>{{ $modeloBlock }}</td>
                        <td rowspan="2">{{ $acoplante }}</td>
                    </tr>
                    <tr>
                        <th class="celdaGris">SERIE:</th>
                        <td>{{ $serieEquipo }}</td>
                        <th class="celdaGris">SERIE:</th>
                        <td>{{ $serieTransductor }}</td>
                        <th class="celdaGris" style="width: 8%;">FREC:</th>
                        <td>{{ $frecTransductor }}</td>
                        <th class="celdaGris">SERIE:</th>
                        <td>{{ $serieBlock }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-bottom: 2px;"></div>

            <table class="encabezadoAzul">
                <tr>
                    <th colspan="4">AJUSTE DEL EQUIPO</th>
                </tr>
            </table>

            <div style="margin-bottom: 2px;"></div>

            <table class="datosgenerales">
                <tbody>
                    <tr>
                        <th style="width: 15%;">GANANCIA:</th>
                        <td class="lineaInferior" style="width: 35%;">{{ $ganancia }} dB</td>
                        <th style="width: 18%;">TIPO DE JUNTA:</th>
                        <td class="lineaInferior">{{ $tipoJunta }}</td>
                    </tr>
                    <tr>
                        <th>RECHAZO:</th>
                        <td class="lineaInferior">{{ $rechazo }}</td>
                        <th>DIAMETRO:</th>
                        <td class="lineaInferior">{{ $diametro }}</td>
                    </tr>
                    <tr>
                        <th>RETARDO:</th>
                        <td class="lineaInferior">{{ $retardo }}</td>
                        <th>ESPESOR:</th>
                        <td class="lineaInferior">{{ $espesor }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-bottom: 2px;"></div>

            <table class="encabezadoAzul">
                <tr>
                    <th colspan="11">RESULTADOS</th>
                </tr>
            </table>

            <table class="datosresultados">
                <colgroup>
                    <col style="width: 8%;">
                    <col style="width: 9%;">
                    <col style="width: 11%;">
                    <col style="width: 6%;">
                    <col style="width: 6%;">
                    <col style="width: 6.5%;">
                    <col style="width: 6.5%;">
                    <col style="width: 7.5%;">
                    <col style="width: 9%;">
                    <col style="width: 9%;">
                    <col style="width: 21.5%;">
                </colgroup>
                <thead>
                    <tr class="celdaGris">
                        <th rowspan="2">No. de Junta</th>
                        <th rowspan="2">No. de Indicación</th>
                        <th rowspan="2">Clasificación</th>
                        <th colspan="4">Ubicación</th>
                        <th rowspan="2">Tamaño</th>
                        <th rowspan="2">% Amplitud</th>
                        <th rowspan="2">Evaluación</th>
                        <th rowspan="2">Comentarios</th>
                    </tr>
                    <tr class="celdaGris">
                        <th>X</th>
                        <th>Y</th>
                        <th>H.T.</th>
                        <th>Prof.</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!str_starts_with($grupo['titulos_juntas'], 'SIN TITULO'))
                        <tr class="titulo-row">
                            <td colspan="11">{{ $grupo['titulos_juntas'] }}</td>
                        </tr>
                    @endif

                    @foreach ($grupo['resultados'] as $junta)
                        <tr class="juntas">
                            <td>{{ $junta['no_junta'] }}</td>
                            <td>{{ $junta['no_indicacion'] }}</td>
                            <td>{{ $junta['clasificacion'] }}</td>
                            <td>{{ $junta['ubi_x'] }}</td>
                            <td>{{ $junta['ubi_y'] }}</td>
                            <td>{{ $junta['ht'] }}</td>
                            <td>{{ $junta['prof'] }}</td>
                            <td>{{ $junta['tamanio'] }}</td>
                            <td>{{ $junta['amplitud'] }}</td>
                            <td>{{ $junta['evaluacion'] }}</td>
                            <td>{{ $junta['comentarios'] }}</td>
                        </tr>
                    @endforeach

                     {{-- 🔹 LONGITUD INSPECCIONADA --}}
                                <tr class="sinBordetd">
                                    <td colspan="8">
                                    <th colspan="2">Longitud inspeccionada:</th>
                                    <th colspan="2">
                                        {{ $grupo['Long_Inspecc'][0] ?? '---' }} m
                                    </th>
                                </tr>

                                {{-- 🔹 SALTO DE PÁGINA POR BLOQUE 
                                <tr style="page-break-after: always;" class="sinBordetd">
                                    <td colspan="11"></td>
                                </tr>--}}
                            </tbody>
                    </table>

                <table>
            </div>

        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach
</body>
</html>

