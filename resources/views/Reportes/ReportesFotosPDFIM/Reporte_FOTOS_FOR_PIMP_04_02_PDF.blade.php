<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-04/02</title>
    <style>
        @page {
            margin: 
            2.2cm
            1.2cm
            2.1cm
            1.2cm;
        }
        body {
            font-family: Arial, sans-serif;
            margin-top: 27px;
            padding-top: 0;
            padding-bottom: 0;
            color: #000;
        }
        header {
            position: fixed;
            top: -52px;
            left: 0;
            right: 0;
            text-align:

            center; }
        footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
        }
        /* En el anexo fotográfico el bloque completo queda 18 px más abajo. */
        footer .firmas-im {
            top: 0;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .tablaheader {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 9.5px;
        }
        .tablaheader th {
            border: 1px solid #000;
        }
        .section-title, 
        .encabezadoAzul {
            background: #305496;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 8px;
        }
        .section-title th {
            border: .7px solid #000;
            padding: 2px;
        }
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
        .tablaGenerales tbody th {
            width: 15%;
            font-weight: bold;
            white-space: nowrap;
            line-height: 7px;
            text-align: left;
            padding-left: 2px;
        }
        .tablaGenerales tbody th.etiqueta-larga {
            text-align: center;
        }
        .tablaGenerales .line {
            padding: 1.5px 0 0 5px;
            text-align: center;
            vertical-align: middle;
        }
        .linea-general {
            min-height: 10px;
            line-height: 10px;
            border-bottom: 1px solid #000;
            box-sizing: border-box;
            text-align: center;
        }
        .texto-multilinea-0402 { white-space: normal; overflow-wrap: break-word; word-wrap: break-word; line-height: 11.5px; padding: 1px 0; }
        .linea-desplazada {
            margin-left: 7mm;
        }
        .spacer {
            height: 5px;
        }
        .metallographic {
            table-layout: fixed;
            text-align: center;
            font-size: 6.5px;
        }
        .metallographic th, 
        .metallographic td {
            border: .6px solid #000;
            padding: 1.5px;
            line-height: 7px;
            vertical-align: middle;
            overflow-wrap: break-word;
        }
        .metallographic .subhead {
            background: #305496;
            color: #fff;
            font-weight: bold;
        }
        .metallographic .label {
            background: #e7e6e6;
            font-weight: bold;
        }
        .photo-grid {
            /* Distribución tomada del PDF principal FOR-PIMP-03_B_01. */
            margin: 0;
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            /* Diez píxeles verticales impiden que una celda de texto toque la imagen de la fila siguiente. */
            border-spacing: 0 6px;
        }
        .photo-slot {
            width: 47%;
            height: 230px;
            border: 0;
            padding: 0;
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
        }
        .photo-gap {
            width: 6%;
            border: 0;
            padding: 0;
        }
        .photo-slot.arriba_izquierda,
        .photo-slot.abajo_izquierda {
            text-align: left;
            vertical-align: top;
        }
        .photo-slot.arriba_derecha,
        .photo-slot.abajo_derecha {
            text-align: right;
        }
        .photo-slot.arriba_derecha { vertical-align: top; }
        .photo-slot.abajo_izquierda,
        .photo-slot.abajo_derecha { 
            vertical-align: top !important;
            overflow: visible !important;
        }
        .photo-slot.abajo_izquierda > table,
        .photo-slot.abajo_derecha > table {
            position: relative;
            top: -15px;
        }
        .photo-content {
            width: 100%;
            max-width: 300px;
            height: 230px;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .photo-text-table {
            width: 100%;
            max-width: 330px;
            /* El texto usa solo el área de imagen; los 16 px restantes equivalen al comentario. */
            height: 185px;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .photo-slot.photo-slot-text {
            /* La franja equivalente al comentario siempre queda debajo del cuadro. */
            vertical-align: top !important;
        }
        .photo-text-comment-space {
            /* Reserva exterior equivalente al comentario de una fotografía, pero sin dibujar borde. */
            width: 100%;
            max-width: 330px;
            height: 16px;
            border: 0;
        }
        .photo-text-cell {
            /* Usa exactamente la misma celda estructural de 185 px que una fotografía. */
            padding: 0 !important;
            text-align: left !important;
        }
        /* BORDE SOLO PARA EL CUADRO DE DESCRIPCIÓN */
        .photo-content .photo-image-cell.photo-text-cell {
            height: 202px;
            border: 1px solid #000 !important;
            padding: 0 !important;
            vertical-align: middle !important;
            text-align: left !important;
            box-sizing: border-box;
            overflow: hidden;
        }
        .photo-text-cell-inner {
            padding: 12px 16px;
            text-align: left;
            vertical-align: middle;
            font-size: 8px;
            line-height: 11px;
            overflow-wrap: break-word;
            word-break: normal;
            white-space: pre-line;
            overflow: hidden;
        }
        .photo-text-cell-inner.photo-text-box-analysis {
            padding: 6px 8px;
        }
        /* Reproduce la alineación por cuadrante del PDF principal 03_B_01. */
        .photo-slot.arriba_izquierda > table,
        .photo-slot.abajo_izquierda > table {
            margin-left: 0;
            margin-right: auto;
        }
        .photo-slot.arriba_derecha > table,
        .photo-slot.abajo_derecha > table {
            margin-left: auto;
            margin-right: 0;
        }
        .photo-slot.arriba_derecha > .photo-text-box,
        .photo-slot.arriba_derecha > .photo-text-comment-space,
        .photo-slot.abajo_derecha > .photo-text-box,
        .photo-slot.abajo_derecha > .photo-text-comment-space {
            margin-left: auto;
            margin-right: 0;
        }
        .photo-content .photo-image-cell {
            width: 100%;
            height: auto;
            padding: 0;
            border: none;
            vertical-align: top;
            text-align: center;
            overflow: visible;
        }
        /* El patrón comparativo ASTM se delimita solo arriba y abajo. */
        .photo-content.photo-content-grain 
        .photo-image-cell {
            height: 208px;
            border: 1px solid #000;
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
        }
        .photo-slot img {
            display: block;
            max-width: 298px;
            max-height: 208px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #000;
        }
        /*
         * Control exclusivo para patrones de tamaño de grano.
         * No toca fotografías normales: solo aplica cuando el servicio marca
         * la imagen como patron_grano_historico y la tabla recibe photo-content-grain.
         */
        .photo-content.photo-content-grain img {
            max-width: 280px;
            max-height: 210px;
            width: auto;
            height: auto;
            object-fit: contain;
            border: 0;
            margin: auto;
        }
        .photo-full .photo-content.photo-content-grain img {
            max-width: 280px !important;
            max-height: 210px !important;
            width: auto !important;
            height: auto !important;
        }
        .photo-comment {
            height: 16px;
            border: 0 !important;
            border-top: 0;
            background: transparent;
            padding: 2px 2px 0;
            margin: 0;
            box-sizing: border-box;
            font-size: 7.2px;
            line-height: 1.08;
            font-weight: normal;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: break-word;
            word-break: normal;
            white-space: normal;
            overflow: hidden;
        }
        .photo-comment-long {
            font-size: 6.9px;
            line-height: 6.4px;
        }
        .photo-content .photo-comment {
            /* El pie de foto no es una celda visible; solo conserva el texto centrado bajo la imagen. */
            border: 0 !important;
            outline: 0;
            background: transparent;
        }
        .photo-text-box {
            width: 100%;
            height: 185px;
            padding: 12px 16px;
            text-align: left;
            vertical-align: middle;
            font-size: 7px;
            line-height: 11px;
            overflow-wrap: break-word;
            word-break: normal;
            /* Conserva los renglones del resumen automático y de los textos escritos por el técnico. */
            white-space: pre-line;
            overflow: hidden;
            box-sizing: border-box;
            /* Los cuadros de texto sí muestran su propia celda completa. */
            border: 1px solid #000;
        }
        /* El cuadro automático puede contener una línea por cada medición del contador. */
        .photo-text-box-analysis {
            padding: 6px 8px;
            font-size: 7.5px;
            line-height: 6.5px;
            text-align: left;
        }
        .photo-full .photo-content,
        .photo-full .photo-text-table,
        .photo-full .photo-text-box {
            max-width: 100%;
            height: 406px;
        }
        .photo-empty {
            background: #fff;
        }
        .photo-full {
            width: 100% !important;
            height: 406px !important;
        }
        .photo-full img {
            width: auto !important;
            height: auto !important;
            max-width: 100% !important;
            max-height: 386px !important;
            object-fit: contain;
        }
        .photo-full .photo-content .photo-image-cell {
            width: 100%;
            height: 390px;
        }
        .photo-page {
            page-break-inside: avoid;
        }

        /* Medidas del formato impreso: 8 x 5.5 cm y 6.5 mm exclusivos para el pie. */
        .photo-grid-cuadrantes {
            width: 18cm;
            margin-left: auto;
            margin-right: auto;
            table-layout: auto;
            border-spacing: 0;
        }
        .photo-grid-cuadrantes .photo-slot {
            width: 8cm;
            height: 6.15cm;
            padding: 0;
            vertical-align: top;
        }
        .photo-grid-cuadrantes 
        .photo-gap { 
            width: 2cm; 
        }
        .photo-grid-cuadrantes 
        .photo-slot > table {
            position: static;
            top: 0;
        }
        .photo-grid-cuadrantes 
        .photo-content 
        {
            width: 8cm;
            max-width: 8cm;
            height: 6.15cm;
        }
        .photo-grid-cuadrantes 
        .photo-content 
        .photo-image-cell 
        {
            height: 5.5cm;
            padding: 0;
            vertical-align: middle;
            text-align: center;
            overflow: hidden;
        }
        .photo-grid-cuadrantes 
        .photo-slot img {
            /* Reserva los bordes dentro del recuadro, sin recortar ni remuestrear el archivo. */
            max-width: 7.94cm;
            max-height: 5.44cm;
            width: auto;
            height: auto;
            margin: auto;
        }
        .photo-grid-cuadrantes 
        .photo-comment {
            height: 6.5mm;
            padding: 1mm 0.5mm 0;
            vertical-align: top;
            box-sizing: border-box;
        }
        /* Sube imagen y comentario inferiores, conservando las medidas de la fotografia. */
        .photo-grid-cuadrantes 
        .photo-slot.abajo_izquierda > table,
        .photo-grid-cuadrantes 
        .photo-slot.abajo_derecha > table {
            position: relative;
            top: -1.5mm;
        }
        /* El texto comparte los limites del recuadro de la micrografia contigua. */
        .photo-grid-cuadrantes 
        .photo-slot-text > 
        .photo-content {
            position: relative;
            top: 0.3mm;
            height: 6.09cm;
        }
        .photo-grid-cuadrantes 
        .photo-slot-text 
        .photo-image-cell.photo-text-cell {
            height: 5.44cm;
        }
        .photo-grid-cuadrantes 
        .photo-slot-text.abajo_izquierda > .photo-content,
        .photo-grid-cuadrantes .photo-slot-text.abajo_derecha > .photo-content {
            position: relative;
            top: -1.2mm;
        }
        /* La foto derecha conserva su proporcion dentro de un marco igual al izquierdo. */
        .photo-grid-cuadrantes .photo-slot.abajo_derecha .photo-image-cell {
            border: 1px solid #000;
            box-sizing: border-box;
        }
        .photo-grid-cuadrantes .photo-slot.abajo_derecha .photo-image-cell img {
            border: 0;
        }
        .photo-cross { 
            width: 100%; 
            height: 5.5cm; 
        }
        .photo-full .photo-cross { 
            height: 390px; 
        }
        .photo-grid-cuadrantes .photo-slot.abajo_derecha .photo-image-cell.photo-cross-cell { border: 0; }
    </style>
</head>
<body>
<header>
    {{-- Encabezado propio del anexo fotográfico 04_03. --}}
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width:360%">
                    FORMATO<br>Format
                </th>
                <th rowspan="3" style="width:70%">
                    @if(!empty($QR_PDF))
                        <img src="{{ $QR_PDF }}" alt="QR de documentos" style="width:55px; height:55px; display:block; margin:auto; padding:0;">
                    @endif
                </th>
                <th style="width:65%">
                    CÓDIGO<br>Code
                </th>
                <th style="width:85%">
                    FOR-PIMP-04/02
                </th>
                <th rowspan="3" style="width:80%">
                    <img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Caracterización de Materiales Mediante la Técnica de Espectrometría de Emisión Óptica (OES)<br>
                    Material Characterization Report Using Optical Emission Spectrometry (OES)
                </th>
                <th>
                    VERSIÓN<br>Version
                </th>
                <th>2</th>
            </tr>
            <tr>
                <th>
                    PÁGINA<br>Page
                </th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<footer>
    @include('Reportes.partials.firmas_im_pdf')
</footer>

{{-- Agrupa cada registro por página y cuadrante; página completa sustituye los cuatro espacios. --}}
@php
    $posicionesFoto = [
        'arriba_izquierda', 
        'arriba_derecha', 
        'abajo_izquierda', 
        'abajo_derecha'
        ];
    $paginasFotos = [];

    foreach ($Fotos as $indiceFoto => $foto) {
        $pagina = max(1, (int) ($foto['pagina'] ?? (intdiv($indiceFoto, 4) + 1)));
        $posicion = $foto['posicion'] ?? (!empty($foto['una_hoja']) ? 'pagina_completa' : $posicionesFoto[$indiceFoto % 4]);
        $paginasFotos[$pagina] = $paginasFotos[$pagina] ?? ['completa' => null, 'espacios' => []];

        if ($posicion === 'pagina_completa') {
            $paginasFotos[$pagina]['completa'] = $foto;
        } elseif (in_array($posicion, $posicionesFoto, true)) {
            $paginasFotos[$pagina]['espacios'][$posicion] = $foto;
        }
    }
    ksort($paginasFotos);
@endphp

@foreach($paginasFotos as $configuracionPagina)
    @php
        $fotoCompleta = $configuracionPagina['completa'];
        $espacios = $configuracionPagina['espacios'];
    @endphp
    <div class="photo-page">
        <div style="margin-bottom:2px"></div>
        <table class="tablaGenerales">
            <thead class="encabezadoAzul">
                <tr>
                    <th colspan="6">
                        DATOS GENERALES<br>General Data
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>FECHA:<br>Date:</th>
                    <td class="line" colspan="2">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Fecha'] ?? '' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        No. REPORTE:<br>Report No.:
                    </th>
                    <td class="line" colspan="2">
                        <div class="linea-general">
                            {{ $Detalles_Generales['No_Reporte'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>CLIENTE:<br>Client:</th>
                    <td class="line" colspan="3">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Cliente'] ?? '' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        CONTRATO:<br>Contract:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Contrato'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>PROYECTO:<br>Project:</th>
                    <td class="line" colspan="5">
                        <div class="linea-general texto-multilinea-0402">
                            {!! nl2br(e(str_replace(["\r\n", "\r"], "\n", (string) ($Detalles_Generales['Proyecto'] ?? '')))) !!}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        ORDEN DE TRABAJO:<br>Work Order:
                    </th>
                    <td class="line" colspan="5">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>FOLIO:<br>Reference:</th>
                    <td class="line" colspan="5">
                        <div class="linea-general texto-multilinea-0402">
                            {!! nl2br(e(str_replace(["\r\n", "\r"], "\n", (string) ($Detalles_Generales['Folio'] ?? '')))) !!}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>PARTIDA:<br>Item:</th>
                    <td class="line" colspan="5">
                        <div class="linea-general texto-multilinea-0402">
                            {!! nl2br(e(str_replace(["\r\n", "\r"], "\n", (string) ($Detalles_Generales['Partida'] ?? '')))) !!}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        INSTALACIÓN:<br>Facility:
                    </th>
                    <td class="line" colspan="3">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Instalacion'] ?? '' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        No. DE ISOMÉTRICO:<br>Isometric No.:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['No_Isometrico'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        NOMBRE DE LA PIEZA:<br>Part Name:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        MATERIAL:<br>Material:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Material'] ?? '' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        TRAZABILIDAD:<br>Traceability:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Trazabilidad'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        PROCEDIMIENTO:<br>Procedure:
                    </th>
                    <td class="line" colspan="2">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Procedimiento'] ?? 'PRO-PIMP-04' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        CRITERIO DE EVALUACIÓN:<br>Evaluation Criteria:
                    </th>
                    <td class="line" colspan="2">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        ACCESORIO:<br>Fitting:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Accesorio'] ?? '' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        TUBERÍA:<br>Pipe:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Tuberia'] ?? '' }}
                        </div>
                    </td>

                    <th class="etiqueta-larga">
                        ESTRUCTURAL:<br>Structural:
                    </th>
                    <td class="line">
                        <div class="linea-general">
                            {{ $Detalles_Generales['Estructural'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        No. DE ISOMÉTRICO Y/O PLANO:<br>Isometric and/or Drawing No.:
                    </th>
                    <td class="line" colspan="5">
                        <div class="linea-general linea-desplazada">
                            {{ $Detalles_Generales['No_Isometrico_Plano'] ?? '' }}
                        </div>
                    </td>
                </tr>

                <tr>
                    <th>
                        OBSERVACIONES Y NOTAS:<br>Remarks and Notes:
                    </th>
                    <td class="line" colspan="5">
                        <div class="linea-general linea-desplazada texto-multilinea-0402">
                            {!! nl2br(e(str_replace(["\r\n", "\r"], "\n", (string) ($Detalles_Generales['Observaciones_Notas'] ?? '')))) !!}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- La tabla metalográfica aparece una sola vez, en la primera página del anexo. --}}
        @if($loop->first)
            <table class="metallographic">
                <colgroup>
                    <col style="width:5%">
                    <col style="width:5%">
                    <col style="width:5%">
                    <!-- MATERIAL PARA EL PULIDO -->
                    <col style="width:9%">
                    <col style="width:10%">
                    <!-- DATOS DE ATAQUE QUÍMICO -->
                    <col style="width:9%">
                    <col style="width:10%">
                    <!-- FASES PRESENTES -->
                    <col style="width:22%">
                    <!-- ESPECIFICACIÓN -->
                    <col style="width:25%">
                </colgroup>
                <thead>
                    <tr class="subhead">
                        <th colspan="9">
                            ANÁLISIS METALOGRÁFICO / Metallographic Analysis
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" style="width:18%;">
                            NÚMERO DE LIJA PARA EL DESBASTE<br>Grinding Sandpaper Number
                        </th>

                        <th colspan="2" style="width:29%;">
                            MATERIAL PARA EL PULIDO<br>Polishing Material
                        </th>

                        <th colspan="2" style="width:27%;">
                            DATOS DE ATAQUE QUÍMICO<br>Chemical Etching Data
                        </th>

                        <th style="width:16%;">
                            FASES PRESENTES<br>Phases Present
                        </th>

                        <th style="width:16%;">
                            ESPEC. APROX. DEL MATERIAL<br>Approx. Material Spec.
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width:6%;">
                            {{ $Datos_Equipo['LIJAS_DESBASTE'][0] ?? '240' }}
                        </td>
                        <td style="width:6%;">
                            {{ $Datos_Equipo['LIJAS_DESBASTE'][1] ?? '320' }}
                        </td>
                        <td style="width:6%;">
                            {{ $Datos_Equipo['LIJAS_DESBASTE'][2] ?? '400' }}
                        </td>

                        <th class="label">
                            PAÑO / Cloth
                        </th>
                        <td>{{ $Datos_Equipo['MATERIAL_PANO'] ?? '' }}</td>

                        <th class="label">
                            REACTIVO / Reagent
                        </th>
                        <td>{{ $Datos_Equipo['REACTIVO'] ?? '' }}</td>

                        <td rowspan="2">
                            {{ $Datos_Equipo['FASES_PRESENTES'] ?? '' }}
                        </td>

                        <td rowspan="2">
                            {{ $Datos_Equipo['ESPECIFICACION_MATERIAL'] ?? '' }}
                        </td>
                    </tr>

                    <tr>
                        <td style="width:6%;">
                            {{ $Datos_Equipo['LIJAS_DESBASTE'][3] ?? '500' }}
                        </td>
                        <td style="width:6%;">
                            {{ $Datos_Equipo['LIJAS_DESBASTE'][4] ?? '1000' }}
                        </td>
                        <td style="width:6%;">
                            {{ $Datos_Equipo['LIJAS_DESBASTE'][5] ?? '1500' }}
                        </td>

                        <th class="label">
                            ABRASIVO / Abrasive
                        </th>
                        <td>
                            {{ $Datos_Equipo['MATERIAL_ABRASIVO'] ?? '' }}
                        </td>

                        <th class="label">
                            TIEMPO / Time
                        </th>

                        <td>
                            {{ $Datos_Equipo['TIEMPO_ATAQUE'] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
        <table>
            <thead class="section-title">
                <tr>
                    <th>
                        REGISTRO FOTOGRÁFICO / PHOTOGRAPHIC RECORD
                    </th>
                </tr>
            </thead>
        </table>

        {{-- Una posición puede contener una imagen o un cuadro de texto del mismo tamaño. --}}
        <table class="photo-grid {{ $fotoCompleta ? 'photo-grid-full' : 'photo-grid-cuadrantes' }}">
            @unless($fotoCompleta)
                <colgroup><col style="width:8cm"><col style="width:2cm"><col style="width:8cm"></colgroup>
            @endunless
            @if($fotoCompleta)
                <tr>
                    <td class="photo-slot photo-full {{ !empty($fotoCompleta['es_cuadro_texto']) ? 'photo-slot-text' : '' }}" colspan="3">
                        @if(!empty($fotoCompleta['es_cuadro_texto']))
                            <table class="photo-text-table">
                                <tr>
                                    <td class="photo-text-box {{ ($fotoCompleta['origen_automatico'] ?? '') === 'resultados_analisis_imagen' ? 'photo-text-box-analysis' : '' }}">{{ $fotoCompleta['comment'] ?? '' }}</td>
                                </tr>
                            </table>
                        @else
                            <table class="photo-content {{ ($fotoCompleta['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'photo-content-grain' : '' }}">
                                <tr>
                                    <td class="photo-image-cell {{ !empty($fotoCompleta['sin_fotografia']) ? 'photo-cross-cell' : '' }}">
                                        @if(!empty($fotoCompleta['sin_fotografia']))
                                            <div class="photo-cross" data-photo-cross="1"></div>
                                        @else
                                            <img src="{{ $fotoCompleta['path'] }}" alt="Fotografía">
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="photo-comment {{ mb_strlen($fotoCompleta['comment'] ?? '') > 120 ? 'photo-comment-long' : '' }}">{{ $fotoCompleta['comment'] ?? '' }}</td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    @foreach(['arriba_izquierda', 'arriba_derecha'] as $posicion)
                        @if(isset($espacios[$posicion]))
                            <td class="photo-slot {{ $posicion }} {{ !empty($espacios[$posicion]['es_cuadro_texto']) ? 'photo-slot-text' : '' }}">
                                @if(!empty($espacios[$posicion]['es_cuadro_texto']))
                                    {{-- Reutiliza la estructura de la foto: marco de 185 px y franja inferior de 16 px. --}}
                                    <table class="photo-content {{ ($espacios[$posicion]['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'photo-content-grain' : '' }}">
                                        <tr>
                                            <td class="photo-image-cell photo-text-cell">
                                                <div class="photo-text-cell-inner {{ ($espacios[$posicion]['origen_automatico'] ?? '') === 'resultados_analisis_imagen' ? 'photo-text-box-analysis' : '' }}">{{ $espacios[$posicion]['comment'] ?? '' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="photo-comment">&nbsp;</td>
                                        </tr>
                                    </table>
                                @else
                                    <table class="photo-content {{ ($espacios[$posicion]['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'photo-content-grain' : '' }}">
                                        <tr>
                                            <td class="photo-image-cell {{ !empty($espacios[$posicion]['sin_fotografia']) ? 'photo-cross-cell' : '' }}">
                                        @if(!empty($espacios[$posicion]['sin_fotografia']))
                                            <div class="photo-cross" data-photo-cross="1"></div>
                                        @else
                                            <img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografía">
                                        @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="photo-comment {{ mb_strlen($espacios[$posicion]['comment'] ?? '') > 120 ? 'photo-comment-long' : '' }}">{{ $espacios[$posicion]['comment'] ?? '' }}</td>
                                        </tr>
                                    </table>
                                @endif
                            </td>
                        @else
                            <td class="photo-slot photo-empty {{ $posicion }}">&nbsp;</td>
                        @endif
                        {{-- El PDF principal 03_B_01 reserva 6% entre la columna izquierda y la derecha. --}}
                        @if($posicion === 'arriba_izquierda')
                            <td class="photo-gap">&nbsp;</td>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    @foreach(['abajo_izquierda', 'abajo_derecha'] as $posicion)
                        @if(isset($espacios[$posicion]))
                            <td class="photo-slot {{ $posicion }} {{ !empty($espacios[$posicion]['es_cuadro_texto']) ? 'photo-slot-text' : '' }}">
                                @if(!empty($espacios[$posicion]['es_cuadro_texto']))
                                    {{-- Mantiene el cuadro y su espacio inferior simétricos con cualquier fotografía. --}}
                                    <table class="photo-content {{ ($espacios[$posicion]['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'photo-content-grain' : '' }}">
                                        <tr>
                                            <td class="photo-image-cell photo-text-cell">
                                                <div class="photo-text-cell-inner {{ ($espacios[$posicion]['origen_automatico'] ?? '') === 'resultados_analisis_imagen' ? 'photo-text-box-analysis' : '' }}">{{ $espacios[$posicion]['comment'] ?? '' }}</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="photo-comment">&nbsp;</td>
                                        </tr>
                                    </table>
                                @else
                                    <table class="photo-content {{ ($espacios[$posicion]['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'photo-content-grain' : '' }}">
                                        <tr>
                                            <td class="photo-image-cell {{ !empty($espacios[$posicion]['sin_fotografia']) ? 'photo-cross-cell' : '' }}">
                                        @if(!empty($espacios[$posicion]['sin_fotografia']))
                                            <div class="photo-cross" data-photo-cross="1"></div>
                                        @else
                                            <img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografía">
                                        @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="photo-comment {{ mb_strlen($espacios[$posicion]['comment'] ?? '') > 120 ? 'photo-comment-long' : '' }}">{{ $espacios[$posicion]['comment'] ?? '' }}</td>
                                        </tr>
                                    </table>
                                @endif
                            </td>
                        @else
                            <td class="photo-slot photo-empty {{ $posicion }}">&nbsp;</td>
                        @endif
                        @if($posicion === 'abajo_izquierda')
                            <td class="photo-gap">&nbsp;</td>
                        @endif
                    @endforeach
                </tr>
            @endif
        </table>
    </div>
    @if(!$loop->last)<div style="page-break-after: always"></div>@endif
@endforeach
</body>
</html>
