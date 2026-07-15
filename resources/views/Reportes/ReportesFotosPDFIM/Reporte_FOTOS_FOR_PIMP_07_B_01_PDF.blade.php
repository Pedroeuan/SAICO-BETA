<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-07_B/01</title>
    <style>
        @page {
            margin: 3cm 1.2cm 2.1cm 2.2cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin-top: 27px;
            padding-top: 0;
            padding-bottom: 0;
        }

        header {
            position: fixed;
            top: -58px;
            left: 0;
            right: 0;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
        }

        .tablaheader {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }

        .tablaheader th {
            border: 1px solid black;
        }

        .encabezadoAzul {
            text-align: center;
            background-color: #305496;
            color: #fff;
            font-size: 8px;
        }

        .datosgenerales,
        .datosinspeccion {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .datosgenerales th,
        .datosgenerales td {
            padding: 1px;
            text-align: center;
            vertical-align: bottom;
        }

        .datosinspeccion th,
        .datosinspeccion td {
            border: .6px solid black;
            padding: 3px;
            text-align: center;
            vertical-align: middle;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
        }

        .etiquetaGeneral {
            width: 12%;
            font-weight: bold;
            line-height: 10px;
        }

        .valorGeneral {
            border-bottom: 1px solid black;
            height: 13px;
        }

        .tablaEquipos {
            table-layout: fixed;
        }

        .celdaGris {
            font-weight: bold;
            text-align: left !important;
        }

        .imagenes-reporte {
            margin-left: -15px;
            width: 106%;
            border-collapse: separate;
            border-spacing: 20px 14px;
            table-layout: fixed;
        }

        .foto-container {
            width: 312px;
            height: 170px;
            border: 1px solid black;
            padding: 0;
            vertical-align: middle;
            text-align: center;
        }

        .foto-container img {
            width: 312px;
            height: 170px;
            object-fit: cover;
            display: block;
        }

        .comment {
            border-top: 1px solid black;
            padding-top: 5px;
            margin-top: 0;
            text-align: center;
            font-size: 8px;
            word-wrap: break-word;
        }

        .empty-box {
            background-color: #fff;
        }

        .empty-comment {
            margin-top: 170px;
            border-top: 1px solid black;
            padding-top: 32px;
        }

        .cross-line {
            width: 74%;
            height: 0;
            position: relative;
        }

        .cross-line::before,
        .cross-line::after {
            content: "";
            position: absolute;
            top: 84px;
            left: -21px;
            width: 152.5%;
            height: 100%;
            border-top: 2px solid black;
            transform: rotate(27deg);
        }

        .cross-line::after {
            transform: rotate(-27deg);
        }

        .foto-container[colspan="2"] img {
            width: 100%;
            height: 170px;
        }

        .foto-full {
            width: 100% !important;
            height: 300px !important;
        }

        .foto-full img {
            width: 100% !important;
            height: 272px !important;
            object-fit: contain;
        }

        .photo-page {
            page-break-inside: avoid;
        }

        footer table {
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        footer th,
        footer td {
            text-align: center;
            vertical-align: middle;
        }
        .observacionesBox {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
            position: relative;
            top: -10px;
        }

        .observacionesBox td {
            padding: 1px 1px;
            text-align: left;
            vertical-align: top;
            font-size: 8px;
        }
        .alinearIzquierda {
            text-align: left !important;
            padding-left: 4px !important;
        }

        .alinearCentro {
            text-align: center !important;
        }
        .textoNegrita {
            font-weight: bold !important;
        }
    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO<br>FORMAT</th>
                <th style="width: 70%;">CÓDIGO<br>CODE</th>
                <th style="width: 100%;">FOR-PIMP-07_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">INFORME DE RELEVADO DE ESFUERZOS<br>RELIEVED OF STRESS INFORM</th>
                <th>VERSIÓN<br>VERSION</th>
                <th>1</th>
            </tr>
            <tr>
                <th>PÁGINA<br>PAGE</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<footer>
    <table class="observacionesBox">
            <tr>
                <td>
                    <div class="observacionesTitulo textoNegrita">OBSERVATIONS/OBSERVACIONES:</div>
                    <div class="observacionesLineas">{{ $Datos_Equipo['Observaciones'] ?? '' }}</div>
                </td>
            </tr>
        </table>

    @include('Reportes.partials.firmas_im_pdf')
    <table class="datosgenerales" style="display: none;">
        <thead>
            @if($numFirmas == 1)
                <tr>
                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                </tr>
                <tr>
                    <td style="width: 260px; height:40px" class="lineaInferior"></td>
                </tr>
                <tr>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                </tr>
            @elseif($numFirmas == 2)
                <tr>
                    <td style="width: 30px;"></td>
                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                    <td style="width: 30px;"></td>
                    <th>{{ $Firmas_Reportes['Vobo1'] ?? '' }}</th>
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
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
            @elseif($numFirmas == 3)
                <tr>
                    <td style="width: 20px;"></td>
                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                    <td style="width: 20px;"></td>
                    <th>{{ $Firmas_Reportes['Vobo1'] ?? '' }}</th>
                    <td style="width: 20px;"></td>
                    <th>{{ $Firmas_Reportes['Vobo2'] ?? '' }}</th>
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
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
            @elseif($numFirmas == 4)
                <tr>
                    <td style="width: 15px;"></td>
                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                    <td style="width: 15px;"></td>
                    <th>{{ $Firmas_Reportes['Vobo1'] ?? '' }}</th>
                    <td style="width: 15px;"></td>
                    <th>{{ $Firmas_Reportes['Vobo2'] ?? '' }}</th>
                    <td style="width: 15px;"></td>
                    <th>{{ $Firmas_Reportes['Vobo3'] ?? '' }}</th>
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
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] ?? '' }}</strong></td>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] ?? '' }}</strong></td>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] ?? '' }}</strong></td>
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
        if (!empty($foto['una_hoja']) && $foto['una_hoja'] == 1) {
            if (!empty($grupoActual)) {
                $chunks[] = $grupoActual;
                $grupoActual = [];
            }
            $chunks[] = [$foto];
            continue;
        }

        $grupoActual[] = $foto;

        if (count($grupoActual) == 2) {
            $chunks[] = $grupoActual;
            $grupoActual = [];
        }
    }

    if (!empty($grupoActual)) {
        $chunks[] = $grupoActual;
    }
@endphp

@foreach($chunks as $fotosGrupo)
    @php
        $esHojaCompleta = (
            count($fotosGrupo) == 1 &&
            !empty($fotosGrupo[0]['una_hoja']) &&
            $fotosGrupo[0]['una_hoja'] == 1
        );
    @endphp
    <div class="content photo-page">
        <table class="datosgenerales">
            <thead class="encabezadoAzul">
                <tr><th colspan="6">DATOS GENERALES<br>GENERAL DATA</th></tr>
            </thead>
            <tbody>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">FECHA<br>DATE:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. REPORTE<br>No. REPORT:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">CLIENTE<br>CLIENT:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. CONTRATO<br>No. CONTRACT:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">PROYECTO<br>PROJECT:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda" style="white-space: nowrap;">ORDEN DE TRABAJO<br>WORK ORDER:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">FOLIO<br>FOLIO:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">PARTIDA<br>LOT:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">INSTALACION<br>LOCATION:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. ISOMETRICO<br>No. ISOMETRIC:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda" style="white-space: nowrap;">ELEMENTOS SOLDADOS<br>WELDINGS:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Elementos_Soldados'] ?? '' }}</td>
            <th class="etiquetaGeneral">MATERIAL<br>MATERIAL:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">No. JUNTA<br>No. JOINT:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Junta'] ?? '' }}</td>
            <th class="etiquetaGeneral">TRAZABILIDAD<br>TRACEABILITY:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
            <th class="etiquetaGeneral">ESPESORES<br>THICKNESSES:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Espesores'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">PROCEDIMIENTO<br>PROCEDURE:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
            <th class="etiquetaGeneral">CODIGO DE DISENO<br>DESIGN CODE:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Codigo_Diseno'] ?? '' }}</td>
            <th class="etiquetaGeneral">DIAM. NOMINAL<br>NOMINAL DIAMETER:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Diam_Nominal'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" colspan="2" style="width: 28%;">REPORTE DE DUREZA ANTES<br>DEL RELEVADO<br>HARDNESS REPORT BEFORE THE<br>RELIEVED OF STRESS:</th>
                    <td class="valorGeneral" style="width: 22%;">{{ $Detalles_Generales['Reporte_Antes_Relevado'] ?? '' }}</td>
                    <th class="etiquetaGeneral" colspan="2" style="width: 28%;">REPORTE DE DUREZA<br>DESPUES DEL RELEVADO<br>HARDNESS REPORT AFTER THE<br>RELIEVED OF STRESS:</th>
                    <td class="valorGeneral" style="width: 22%;">{{ $Detalles_Generales['Reporte_Despues_Relevado'] ?? '' }}</td>
        </tr>
            </tbody>
        </table>

        @if(!$esHojaCompleta)
        <div style="margin-bottom: 6px;"></div>

        <table class="datosinspeccion tablaEquipos">
            <colgroup>
                <col style="width: 40%;">
                <col style="width: 20%;">
                <col style="width: 20%;">
                <col style="width: 20%;">
            </colgroup>
            <thead>
                <tr class="encabezadoAzul"><th colspan="4">DATOS DEL EQUIPO<br>EQUIPMENT DATA</th></tr>
                <tr>
                    <th>EQUIPO<br>EQUIPMENT</th>
                    <th>MARCA<br>BRAND</th>
                    <th>MODELO<br>MODEL</th>
                    <th>No. SERIE<br>SERIAL NUMBER</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="celdaGris">MAQUINA DE RELEVADO<br>STRESS RELIEF MACHINE:</td>
                    <td>{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="celdaGris">GRAFICADOR<br>GRAPHIER:</td>
                    <td>{{ $Datos_Equipo['MARCA_EQUIPO1'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['MODELO_EQUIPO1'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['NS_EQUIPO1'] ?? '' }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-bottom: 6px;"></div>
        @endif

        <table class="datosgenerales">
            <thead class="encabezadoAzul">
                <tr><th>REGISTRO FOTOGRAFICO<br>PHOTOGRAPHIC RECORD</th></tr>
            </thead>
        </table>

        <table class="imagenes-reporte">
            <tr>
                @foreach($fotosGrupo as $index => $foto)
                    @if(!empty($foto['una_hoja']) && $foto['una_hoja'] == 1)
                        <td class="foto-container foto-full" colspan="2">
                            <img src="{{ $foto['path'] }}">
                            <p class="comment">{{ $foto['comment'] }}</p>
                        </td>
                    @else
                        <td class="foto-container">
                            <img src="{{ $foto['path'] }}">
                            <p class="comment">{{ $foto['comment'] }}</p>
                        </td>
                        @if(($index + 1) % 2 == 0)
                            </tr><tr>
                        @endif
                    @endif
                @endforeach

            </tr>
        </table>
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
