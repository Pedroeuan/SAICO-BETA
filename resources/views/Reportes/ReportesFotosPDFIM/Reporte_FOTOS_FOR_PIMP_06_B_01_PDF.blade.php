<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-06_B/01</title>
    <style>
        @page {
            margin: 2.5cm 1.2cm 2.1cm 2.2cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin-top: 27px;
            padding-top: 0;
            padding-bottom: 0;
        }

        header {
            position: fixed;
            top: -50px;
            left: 0;
            right: 0;
            text-align: center;
        }
        footer {
            position: fixed;
            bottom: -70px;
            left: 0;
            right: 0;
            text-align: center;
        }

        .tablaheader {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 9px;
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
            padding: 3px;
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
            width: 15%;
            padding-left: 2px;
            font-weight: bold;
            line-height: 10px;
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
        }

        .valorGeneral {
            height: 13px;
            border-bottom: 1px solid #000;
            text-align: center;
            vertical-align: middle;
        }

        .tablaEquipos {
            table-layout: fixed;
        }

        .celdaGris {
            background-color: #DBDBDB;
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
            padding-top: 2px;
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
        .tablaGenerales {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8px;
        }

        .tablaGenerales th,
        .tablaGenerales td {
            padding: 1.5px;
            vertical-align: middle;
        }
        .etiquetaGeneralCentrada {
            text-align: center !important;
            vertical-align: middle !important;
        }
        .titulo-es-nowrap {
            display: block;
            text-align: center;
            white-space: nowrap;
        }

        .fotoPiezaTitulo {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .fotoPiezaTitulo th {
            padding: 4px;
            background-color: #305496;
            color: #fff;
            text-align: center;
            font-size: 8px;
            line-height: 9px;
        }

        .fotosExcelLayout {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .fotosExcelLayout td {
            width: 50%;
            height: 180px;
            padding: 8px 10px;
            text-align: center;
            vertical-align: middle;
            font-size: 7px;
            font-weight: bold;
            line-height: 9px;
        }

        .fotoExcelMarco {
            border: .8px solid #000;
            padding: 0 !important;
            overflow: hidden;
        }

        .fotoExcelMarco img {
            display: block;
            width: 100%;
            height: 180px;
            object-fit: contain;
        }

        .fotoExcelComentario {
            border: none !important;
        }

        .cuadriculaFotos {
            width: 100%;
            /*border-collapse: separate;*/
            table-layout: fixed;
            /*border: 1px solid #eb0b0b;*/
        }

        .cuadriculaFotos td {
            width: 50%;
            padding: 0;
            vertical-align:top;
            /*border: 1px solid #ffd500;*/
        }

        .cuadriculaFotos td:first-child{
            text-align:left;
        }

        .cuadriculaFotos td:last-child{
            text-align:right;
        }
        
        .fotoCuadrante {
            display:inline-block;
            width:300px;
            margin:0;
            padding:0;
            border: 0 !important;  
            overflow:hidden;
        }

        .fotoCuadrante img {
            display: block;
            max-width: 298px;
            height: auto;
            object-fit: contain;
            margin: 0 0 0 0;
            border: 1px solid #000;
        }

        /*
         * Control exclusivo para patrones comparativos de tamaño de grano.
         * Las fotos normales conservan su tamaño; el grano se centra y no
         * crece de más para evitar que empuje firmas o desorganice la hoja.
         */
        .fotoCuadrante.fotoCuadranteGrano {
            width: 300px;
            text-align: center;
            overflow: visible;
        }

        .fotoCuadrante.fotoCuadranteGrano .fotoGranoCelda {
            width: 294px;
            height: 215px;
            border: 1px solid #000;
            box-sizing: border-box;
            text-align: center;
            overflow: hidden;
            margin: 0 auto;
            padding-top: 7px;
        }

        .fotoCuadrante.fotoCuadranteGrano img {
            display: inline-block;
            max-width: 280px;
            max-height: 200px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: 0 auto;
            border: 0;
        }

        .fotoCuadranteComentario {
            min-height: 20px;
            padding: 5px 3px 2px;
            border: none !important;
            font-size: 8px;
            line-height: 9px;
            overflow: hidden;

            text-align: center;      /* Centrado horizontal */
            vertical-align: middle;
        }
        /* Tabla interna únicamente para centrar el texto */
        .fotoCuadranteTextoTabla {
            width: 100%;
            height: 170px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .fotoCuadranteTextoTabla td {
            height: 170px;
            padding: 12px;

            border: none !important;

            text-align: left;
            vertical-align: middle;

            font-size: 8px;
            line-height: 10px;
            font-weight: normal;

            word-wrap: break-word;
        }

        .fotoCuadranteTexto {
            width: 270px;
            height: 210px;
            border: 1px solid #000 !important;
            box-sizing: border-box;
            overflow: hidden;
            padding: 12px;
            font-size: 8px;
            line-height: 10px;
            text-align: left !important;
            vertical-align: middle;
        }
        .fotoCuadranteTexto * {
            text-align: left !important;
        }

        .fotoCuadranteVacio {
            border: none;
        }
    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width:390%">FORMATO<br>Format</th>
                <th rowspan="3" style="width:70%">
                    @if(!empty($QR_PDF))
                        <img src="{{ $QR_PDF }}" alt="QR de documentos" style="width:55px; height:55px; display:block; margin:auto; padding:0;">
                    @endif
                </th>
                <th style="width:60%">Código<br>Code</td>
                <th style="width:100%">FOR-PIMP-06_B/01</th>
                <th rowspan="3" style="width:85%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X (XRF)<br>
                    Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF)</th>
                <th>VERSIÓN<br>Version:</td>
                <th>3</th>
            </tr>
            <tr>
                <th>PÁGINA<br>Page:</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<footer>
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
    $ordenPosiciones = [
        'arriba_izquierda',
        'arriba_derecha',
        'abajo_izquierda',
        'abajo_derecha',
    ];
    $paginasFotos = [];

    foreach ($Fotos as $indiceFoto => $foto) {
        $pagina = max(1, (int) ($foto['pagina'] ?? (intdiv($indiceFoto, 4) + 1)));

        if (!empty($foto['una_hoja'])) {
            $paginasFotos[$pagina] = ['foto_completa' => $foto, 'posiciones' => []];
            continue;
        }

        if (!isset($paginasFotos[$pagina])) {
            $paginasFotos[$pagina] = ['foto_completa' => null, 'posiciones' => []];
        }

        $posicion = $foto['posicion'] ?? $ordenPosiciones[$indiceFoto % 4];
        if (!in_array($posicion, $ordenPosiciones, true) || isset($paginasFotos[$pagina]['posiciones'][$posicion])) {
            foreach ($ordenPosiciones as $posicionDisponible) {
                if (!isset($paginasFotos[$pagina]['posiciones'][$posicionDisponible])) {
                    $posicion = $posicionDisponible;
                    break;
                }
            }
        }

        $paginasFotos[$pagina]['posiciones'][$posicion] = $foto;
    }

    ksort($paginasFotos);
@endphp

@foreach($paginasFotos as $paginaFotos)
    @php
        $fotoCompleta = $paginaFotos['foto_completa'] ?? null;
        $esHojaCompleta = !empty($fotoCompleta);
        $fotosPorPosicion = $paginaFotos['posiciones'] ?? [];
    @endphp
    <div class="photo-page">
    <table class="tablaGenerales">

    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">
                DATOS GENERALES<br>
                General Data
            </th>
        </tr>
    </thead>

    <tbody>

        {{-- FECHA / No. REPORTE --}}
        <tr>

            <th class="etiquetaGeneral">
                FECHA<br>
                Date:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="2">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Fecha'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                No. REPORTE<br>
                No. Report:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="2">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['No_Reporte'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- CLIENTE / No. CONTRATO --}}
        <tr>

            <th class="etiquetaGeneral">
                CLIENTE<br>
                Client:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="3">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Cliente'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                No. CONTRATO<br>
                No. Contract:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Contrato'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- CONTRATO --}}
        <tr>

            <th class="etiquetaGeneral" style="white-space: nowrap;">
                CONTRATO<br>
                Contract:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Proyecto'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- ORDEN DE TRABAJO --}}
        <tr>

            <th class="etiquetaGeneral" style="white-space: nowrap;">
                ORDEN DE TRABAJO<br>
                Work Order:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- FOLIO --}}
        <tr>

            <th class="etiquetaGeneral">
                FOLIO<br>
                Folio:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Folio'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- PARTIDA --}}
        <tr>

            <th class="etiquetaGeneral">
                PARTIDA<br>
                Lot:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Partida'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- INSTALACION / NUMERO DE ISOMETRICO --}}
        <tr>

            <th class="etiquetaGeneral">
                INSTALACION<br>
                Location:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="3">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Instalacion'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada"
                style="white-space: nowrap;">
                NUMERO DE ISOMETRICO<br>
                No. Isometric:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['No_Isometrico'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- NOMBRE DE LA PIEZA / MATERIAL --}}
        <tr>

    <th class="etiquetaGeneral" style="white-space: nowrap;">
        NOMBRE DE LA PIEZA<br>
        Name of the Piece:
    </th>

    <td class="valorGeneral valorGeneralConLinea">
        <div class="lineaValorGeneral">
            <span class="textoValorGeneral">
                {!! str_replace(
                    '⌀',
                    '<span class="simboloDiametro">⌀</span>',
                    e($Detalles_Generales['Nom_Pieza'] ?? '')
                ) !!}
            </span>
        </div>
    </td>

    <th class="etiquetaGeneral etiquetaGeneralCentrada">
        No. JUNTA<br>
        No. Joint:
    </th>

    <td class="valorGeneral valorGeneralConLinea">
        <div class="lineaValorGeneral">
            <span class="textoValorGeneral">
                {{ $Detalles_Generales['No_Junta'] ?? '' }}
            </span>
        </div>
    </td>

    <th class="etiquetaGeneral etiquetaGeneralCentrada">
        MATERIAL<br>
        Material:
    </th>

    <td class="valorGeneral valorGeneralConLinea">
        <div class="lineaValorGeneral">
            <span class="textoValorGeneral">
                {{ $Detalles_Generales['Material'] ?? '' }}
            </span>
        </div>
    </td>

</tr>


        {{-- PROCEDIMIENTO / CRITERIO / TRAZABILIDAD --}}
        <tr>

            <th class="etiquetaGeneral">
                PROCEDIMIENTO<br>
                Procedure:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Procedimiento'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada"
                style="white-space: nowrap;">
                CRITERIO DE EVALUACION<br>
                Evaluation Criteria:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                TRAZABILIDAD<br>
                Traceability:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Trazabilidad'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>

    </tbody>

</table>

        <table class="fotoPiezaTitulo">
            <tr>
                <th>FOTO DE LA PIEZA INSPECCIONADA<br>Photo of the inspected part</th>
            </tr>
        </table>

        @if($esHojaCompleta)
            <table class="fotosExcelLayout">
                <tr>
                    <td class="fotoExcelMarco" style="height:435px;">
                        @if(!empty($foto['es_cuadro_texto']))
                            <div class="fotoCuadranteTexto">
                                <table class="fotoCuadranteTextoTabla">
                                    <tr>
                                        <td>
                                            {!! nl2br(e($foto['comment'] ?? '')) !!}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @else
                            <img src="{{ $fotoCompleta['path'] }}" style="height:404px; object-fit:contain;">
                        @endif
                    </td>
                </tr>
                @if(empty($fotoCompleta['es_cuadro_texto']) && !empty($fotoCompleta['comment']))
                    <tr><td class="fotoExcelComentario">{{ $fotoCompleta['comment'] }}</td></tr>
                @endif
            </table>
        @else
            <table class="cuadriculaFotos">
                @foreach([['arriba_izquierda', 'arriba_derecha'], ['abajo_izquierda', 'abajo_derecha']] as $filaPosiciones)
                    <tr>
                        @foreach($filaPosiciones as $posicion)
                            @php($foto = $fotosPorPosicion[$posicion] ?? null)
                            <td class="{{ $foto ? '' : 'fotoCuadranteVacio' }}">
                                @if($foto)
                                    <div class="fotoCuadrante {{ ($foto['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'fotoCuadranteGrano' : '' }}">
                                        @if(!empty($foto['es_cuadro_texto']))
                                            <div class="fotoCuadranteTexto">
                                                <table class="fotoCuadranteTextoTabla">
                                                    <tr>
                                                        <td>
                                                            {!! nl2br(e($foto['comment'] ?? '')) !!}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        @else
                                            @if(($foto['origen_automatico'] ?? '') === 'patron_grano_historico')
                                                <div class="fotoGranoCelda">
                                                    <img src="{{ $foto['path'] }}">
                                                </div>
                                            @else
                                                <img src="{{ $foto['path'] }}">
                                            @endif
                                            <div class="fotoCuadranteComentario">{{ $foto['comment'] ?? '' }}</div>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
