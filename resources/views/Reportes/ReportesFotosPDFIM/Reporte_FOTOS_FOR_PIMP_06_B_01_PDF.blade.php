<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-06_B/01</title>
    <style>
        @page {
            margin: 2cm 1.2cm 1.1cm 2.2cm;
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

        /* Replica el estilo de DATOS GENERALES del reporte PDF principal. */
        .tablaGenerales {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
            table-layout: fixed;
        }

        .tablaGenerales th,
        .tablaGenerales td {
            padding: 1.5px;
            vertical-align: middle;
        }

        .datosgenerales th,
        .datosgenerales td {
            padding: 1.5px 1.5px;
            vertical-align: middle;
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
            font-weight: bold;
            white-space: nowrap !important;
            line-height: 10px;
            text-align: left;
            padding-left: 2px;
            vertical-align: middle;
        }

        .etiquetaGeneralCentrada {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .valorGeneral {
            text-align: center !important;
            vertical-align: bottom !important;
            padding: 0 !important;
            height: 10px;
        }

        /* Solo el glifo ⌀ usa una fuente Unicode; el resto conserva las métricas de Arial. */
        .simboloDiametro {
            font-family: "DejaVu Sans", sans-serif;
        }

        .valorGeneralAlto {
            height: 15px;
        }

        .valorGeneralConLinea {
            border-bottom: none !important;
            padding: 0 !important;
            vertical-align: bottom !important;
        }

        .lineaValorGeneral {
            position: relative;
            width: 100%;
            height: 11px;
            border-bottom: .5px solid black;
            padding: 0 !important;
            margin: 0 !important;
        }

        .textoValorGeneral {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 2px;
            text-align: center;
            line-height: 8px;
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
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .imagenes-reporte td {
            width: 50%;
            padding: 0 0 4px;
            vertical-align: top;
        }

        .imagenes-reporte td:first-child {
            text-align: left;
            padding-right: 10px;
        }

        .imagenes-reporte td:last-child {
            text-align: right;
            padding-left: 10px;
        }

        .foto-container {
            padding: 0;
            border: none;
            text-align: center;
            vertical-align: top;
            overflow: hidden;
        }

        .foto-cuadrante {
            display: inline-block;
            width: 312px;
            margin: 0;
            padding: 0;
            border: none;
            overflow: visible;
        }

        /* El borde visual pertenece a la imagen, no al comentario. */
        .foto-imagen-area {
            width: 310px;
            height: 240px;
            overflow: hidden;
            text-align: center;
            border: 1px solid #000;
            box-sizing: border-box;
            display: table;
        }

        .foto-imagen-area img {
            display: inline-block;
            max-width: 308px;
            max-height: 238px;
            overflow: hidden;
            width: auto;
            height: auto;
            object-fit: contain;
            vertical-align: middle;
        }

        .foto-vacia {
            /*
             * Conserva la posicion de la cuadricula (arriba/abajo, izquierda/derecha)
             * sin dibujar una celda visual cuando el usuario no cargo imagen en ese espacio.
             */
            border: none !important;
            background-color: transparent;
        }

        .comment {
            min-height: 20px;
            line-height: 9px;
            border: none !important;
            padding: 5px 3px 2px;
            margin: 0;
            box-sizing: border-box;
            text-align: center;
            font-size: 8px;
            word-wrap: break-word;
            overflow: hidden;
        }

        /* La descripción ocupa la misma celda reservada para una fotografía. */
        .descripcion-reporte {
            box-sizing: border-box;
            width: 312px;
            height: 240px;
            padding: 8px;
            line-height: 10px;
            text-align: left;
            vertical-align: top;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            overflow: hidden;
            font-size: 8px;
            border: 1px solid #000;
            margin: 0 auto;
        }

        .foto-full .descripcion-reporte {
            width: 100%;
            height: 435px;
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

        .foto-full {
            width: 100% !important;
            height: 435px !important;
        }

        .foto-full .foto-imagen-area {
            width: 100%;
            height: 404px;
            line-height: 404px;
        }

        .foto-full .foto-imagen-area img {
            max-width: 100%;
            max-height: 402px;
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
        .etiquetaGeneral {
            width: 15%;
            font-weight: bold;
            white-space: nowrap !important;
            line-height: 10px;
            text-align: left;
            padding-left: 2px;
            vertical-align: middle;
        }

        .etiquetaGeneralCentrada {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .etiquetaGeneralCentrada .titulo-es-nowrap {
            display: block;
            white-space: nowrap;
            text-align: center;
        }
        .foto-full .foto-imagen-centro {
            width: 100%;
            height: 402px;
        }
        .foto-imagen-centro {
            display: table-cell;
            width: 308px;
            height: 238px;
            text-align: center;
            vertical-align: middle;
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
    $posicionesFoto = [
        'arriba_izquierda',
        'arriba_derecha',
        'abajo_izquierda',
        'abajo_derecha',
    ];
    $paginasFotos = [];

    foreach ($Fotos as $indiceFoto => $foto) {
        $pagina = max(1, (int) ($foto['pagina'] ?? (intdiv($indiceFoto, 4) + 1)));
        $posicion = $foto['posicion']
            ?? (!empty($foto['una_hoja']) ? 'pagina_completa' : $posicionesFoto[$indiceFoto % 4]);

        if (!isset($paginasFotos[$pagina])) {
            $paginasFotos[$pagina] = ['completa' => null, 'espacios' => []];
        }

        if ($posicion === 'pagina_completa') {
            $paginasFotos[$pagina]['completa'] = $foto;
        } elseif (in_array($posicion, $posicionesFoto, true)) {
            $paginasFotos[$pagina]['espacios'][$posicion] = $foto;
        }
    }

    ksort($paginasFotos);
@endphp

@foreach($paginasFotos as $numeroPaginaFotos => $configuracionPagina)
    @php
        $fotoCompleta = $configuracionPagina['completa'];
        $esHojaCompleta = !empty($fotoCompleta);
        $espacios = $configuracionPagina['espacios'];
    @endphp
    <div class="content photo-page">
        <table class="tablaGenerales">
            <thead class="encabezadoAzul">
                <tr>
                    <th colspan="6">DATOS GENERALES<br>General Data</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="etiquetaGeneral">FECHA<br>Date:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="2">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Fecha'] ?? '' }}</span></div>
                    </td>
                    <th class="etiquetaGeneral etiquetaGeneralCentrada">No. REPORTE<br>No. Report:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="2">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">CLIENTE<br>Client:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="3">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Cliente'] ?? '' }}</span></div>
                    </td>
                    <th class="etiquetaGeneral etiquetaGeneralCentrada">No. CONTRATO<br>No. Contract:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Contrato'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">CONTRATO<br>Contract:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="5">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Proyecto'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">ORDEN DE TRABAJO<br>Work Order:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="5">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">FOLIO<br>Folio:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="5">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Folio'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">PARTIDA<br>Lot:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="5">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Partida'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">INSTALACION<br>Location:</th>
                    <td class="valorGeneral valorGeneralConLinea" colspan="3">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Instalacion'] ?? '' }}</span></div>
                    </td>
                    <th class="etiquetaGeneral etiquetaGeneralCentrada">NUMERO DE ISOMETRICO<br>No. Isometric:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">NOMBRE DE LA PIEZA<br>Name of the Piece:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{!! str_replace('⌀', '<span class="simboloDiametro">⌀</span>', e($Detalles_Generales['Nom_Pieza'] ?? '')) !!}</span></div>
                    </td>
                    <th class="etiquetaGeneral etiquetaGeneralCentrada">No. JUNTA<br>No. Joint:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['No_Junta'] ?? '' }}</span></div>
                    </td>
                    <th class="etiquetaGeneral etiquetaGeneralCentrada">MATERIAL<br>Material:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</span></div>
                    </td>
                </tr>
                <tr>
                    <th class="etiquetaGeneral">PROCEDIMIENTO<br>Procedure:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</span></div>
                    </td>
                    <th class="etiquetaGeneral etiquetaGeneralCentrada">CRITERIO DE EVALUACION<br>Evaluation Criteria:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</span></div>
                    </td>
                    <th class="etiquetaGeneral etiquetaGeneralCentrada">TRAZABILIDAD<br>Traceability:</th>
                    <td class="valorGeneral valorGeneralConLinea">
                        <div class="lineaValorGeneral"><span class="textoValorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</span></div>
                    </td>
                </tr>
            </tbody>
        </table>

        @if(!$esHojaCompleta)
        <div style="margin-bottom: 6px;"></div>
        @endif

        <table class="datosgenerales">
            <thead class="encabezadoAzul">
                <tr>
                    <th>EVIDENCIA FOTOGRÁFICA<br>Photographic  Evidence</th>
                </tr>
            </thead>
        </table>
        <div style="margin-bottom: 4px;"></div>
        <table class="imagenes-reporte">
            @if($esHojaCompleta)
                <tr>
                    <td class="foto-container foto-full" colspan="2">
                        @if(!empty($fotoCompleta['es_cuadro_texto']))
                            <div class="descripcion-reporte">{!! nl2br(e($fotoCompleta['comment'] ?? '')) !!}</div>
                        @else
                            <div class="foto-imagen-area">
                                <div class="foto-imagen-centro">
                                    <img src="{{ $fotoCompleta['path'] }}" alt="Fotografia">
                                </div>
                            </div>
                            <p class="comment">{!! nl2br(e($fotoCompleta['comment'] ?? '')) !!}</p>
                        @endif
                    </td>
                </tr>
            @else
                @php
                    // La fila solo existe si tiene al menos una foto/texto, pero dentro
                    // de la fila se conservan las dos columnas para respetar la posicion.
                    $hayFotosArriba = isset($espacios['arriba_izquierda']) || isset($espacios['arriba_derecha']);
                    $hayFotosAbajo = isset($espacios['abajo_izquierda']) || isset($espacios['abajo_derecha']);
                @endphp
                @if($hayFotosArriba)
                <tr>
                    @foreach(['arriba_izquierda', 'arriba_derecha'] as $posicion)
                        @if(isset($espacios[$posicion]))
                            <td class="foto-container">
                                <div class="foto-cuadrante">
                                    @if(!empty($espacios[$posicion]['es_cuadro_texto']))
                                        <div class="descripcion-reporte">{!! nl2br(e($espacios[$posicion]['comment'] ?? '')) !!}</div>
                                    @else
                                        <div class="foto-imagen-area">
                                            <div class="foto-imagen-centro">
                                                <img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografia">
                                            </div>
                                        </div>
                                        <p class="comment">{!! nl2br(e($espacios[$posicion]['comment'] ?? '')) !!}</p>
                                    @endif
                                </div>
                            </td>
                        @else
                            <td class="foto-container foto-vacia">&nbsp;</td>
                        @endif
                    @endforeach
                </tr>
                @endif
                @if($hayFotosAbajo)
                <tr>
                    @foreach(['abajo_izquierda', 'abajo_derecha'] as $posicion)
                        @if(isset($espacios[$posicion]))
                            <td class="foto-container">
                                <div class="foto-cuadrante">
                                    @if(!empty($espacios[$posicion]['es_cuadro_texto']))
                                        <div class="descripcion-reporte">{!! nl2br(e($espacios[$posicion]['comment'] ?? '')) !!}</div>
                                    @else
                                        <div class="foto-imagen-area">
                                            <div class="foto-imagen-centro">
                                                <img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografia">
                                            </div>
                                        </div>
                                        <p class="comment">{!! nl2br(e($espacios[$posicion]['comment'] ?? '')) !!}</p>
                                    @endif
                                </div>
                            </td>
                        @else
                            <td class="foto-container foto-vacia">&nbsp;</td>
                        @endif
                    @endforeach
                </tr>
                @endif
            @endif
        </table>
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
