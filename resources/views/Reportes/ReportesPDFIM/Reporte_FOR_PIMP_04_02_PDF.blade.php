<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-04/02</title>

    <style>
        @page {
            margin: 2cm 1.2cm 2.1cm 2.2cm;
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
            top: -38px;
            left: 0;
            right: 0;
            height: auto;
            text-align: center;
        }
        footer {
            position: fixed;
            /* Baja ligeramente las firmas sin acercarlas al contenido del análisis químico. */
            bottom: -38px;
            left: 0;
            right: 0;
            height: auto;
            text-align: center;
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
        .section-title, .encabezadoAzul {
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
        .tablaGenerales th, .tablaGenerales td {
            padding: 1.5px;
            vertical-align: middle;
        }
        .tablaGenerales tbody th {
            width: 15%;
            font-weight: bold;
            white-space: nowrap;
            line-height: 10px;
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
        .linea-desplazada {
            margin-left: 7mm;
        }
        .spacer {
            height: 3px;
        }
        .grid {
            table-layout: fixed;
            text-align: center;
            font-size: 8px;
        }
        .grid th, .grid td {
            border: .6px solid #000;
            padding: 2px;
            line-height: 7px;
        }
        .grid .label {
            background: #e7e6e6;
            font-weight: bold;
        }
        .hardness-values tbody td,
        .hardness-values tbody th {
            height: 8px;
            padding: .5px 1px;
            line-height: 7px;
            font-size: 7px;
        }
        .paginaComposicion {
            page-break-before: auto;
        }
        .layoutComposicion {
            width: 92%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: auto; /* centra ambas tablas como conjunto */
        }
        .layoutComposicion > tbody > tr > td {
            padding: 0;
            vertical-align: top;
        }
        .resultadoComposicion,
        .capturaComposicion {
            width: 50%;
        }
        .resultadoComposicion {
            padding-right: 5px !important;
        }
        .capturaComposicion {
            padding-left: 5px !important;
            /* Alinea la captura XRF con el inicio de la tabla de composición teórica. */
            padding-top: 20px !important;
        }
        .tablaResultadosQuimicos {
            /* Tabla comparativa mas compacta y centrada dentro de su columna. */
            width: 78%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 5.7px;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
        }
        .tablaResultadosQuimicos th,
        .tablaResultadosQuimicos td {
            border: .6px solid #000;
            height: 10px;
            padding: 0 2px;
            text-align: center;
            vertical-align: middle;
            line-height: 7px;
            overflow-wrap: break-word;
        }
        .tablaResultadosQuimicos thead th {
            height: 32px;
            font-size: 5.2px;
            line-height: 6px;
            background: #e7e6e6;
        }
        .tablaResultadosQuimicos thead th.tituloComparacion {
            height: 25px;
            padding: 2px 4px;
            font-size: 5.5px;
            line-height: 7px;
            font-weight: bold;
            text-align: center;
            background: #e7e6e6;
        }
        .tituloCapturaQuimica {
            border: 1px solid #000;
            box-sizing: border-box;
            padding: 2px;
            font-weight: bold;
            font-size: 6px;
            line-height: 8px;
            text-align: center;
        }
        .bloqueCapturaQuimica {
            /* El contenedor manda: encabezado e imagen respetan el mismo ancho real en dompdf. */
            max-width: 100%;
            margin: 0 auto;
            margin-right: auto;
        }
        .tituloCapturaQuimica {
            width: 97.5%;
            margin: 0;
        }
        .imagenCapturaQuimica {
            display: block;
            /* Conserva la proporción original y limita la captura para respetar las firmas. */
            width: 100%;
            height: auto;
            max-width: 100%;
            max-height: 235px;
            margin: 0;
            object-fit: contain;
            object-position: center top;
        }
        .firmas-im {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .firmas-im td {
            text-align: center;
            vertical-align: top;
            padding: 0 12px;
            font-size: 8px;
        }
        .firmas-im .firma-titulo {
            font-weight: bold;
            line-height: 11px;
            min-height: 8px;
        }
        .firmas-im .firma-linea {
            border-bottom: 1px solid #000;
            height: 10px;
            margin-top: 0;
            line-height: 10px;
            padding-top: 10px;
            box-sizing: border-box;
            font-weight: bold;
        }
        .firmas-im .firma-dato {
            margin-top: 2px;
            line-height: 10px;
            font-weight: bold;
        }
        .firmas-im .firma-ficha {
            margin-top: 2px;
            line-height: 10px;
            font-weight: bold;
        }
        .firmas-im-4 td {
            padding: 2px 12px 0 12px;
        }
        .firmas-im .firma-separacion-tres td {
            padding-top: 0px;
        }
        .firmas-im .firma-separacion-cuatro td {
            padding-top: 16px;
        }
        .centro{
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    {{-- Encabezado propio del PDF principal 04_03, ajustado al formato de referencia en español. --}}
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width:360%">FORMATO</th>
                <th rowspan="3" style="width:70%">
                    @if(!empty($QR_PDF))
                        <img src="{{ $QR_PDF }}" alt="QR de documentos" style="width:55px; height:55px; display:block; margin:auto; padding:0;">
                    @endif
                </th>
                <th style="width:70%">CÓDIGO</th>
                <th style="width:100%">FOR-PIMP-04/02</th>
                <th rowspan="3" style="width:80%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Caracterización de Materiales Mediante la Técnica de Espectrometría de Emisión Óptica (OES)</th>
                <th>VERSIÓN</th>
                <th>2</th>
            </tr>
            <tr>
                <th>PÁGINA</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<footer>
    @include('Reportes.partials.firmas_im_pdf')
</footer>

{{-- Datos generales conservan líneas independientes para evitar que las etiquetas se superpongan. --}}
<div style="margin-bottom:2px"></div>
<table class="tablaGenerales">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">DATOS GENERALES</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th>FECHA:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['Fecha'] ?? '' }}</div>
            </td>
            <th class="etiqueta-larga">No. REPORTE:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</div>
            </td>
        </tr>
        <tr><th>CLIENTE:</th>
            <td class="line" colspan="3">
                <div class="linea-general">{{ $Detalles_Generales['Cliente'] ?? '' }}</div>
            </td>
            <th class="etiqueta-larga">CONTRATO:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Contrato'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>PROYECTO:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Proyecto'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>ORDEN DE TRABAJO:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>FOLIO:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Folio'] ?? '' }}</div>
            </td>
        </tr>
        <tr><th>PARTIDA:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Partida'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>INSTALACIÓN:</th>
            <td class="line" colspan="3">
                <div class="linea-general">{{ $Detalles_Generales['Instalacion'] ?? '' }}</div>
            </td>
            <th  class="etiqueta-larga">No. DE ISOMÉTRICO:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>NOMBRE DE LA PIEZA:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}</div>
            </td>
            <th class="etiqueta-larga">MATERIAL:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Material'] ?? '' }}</div>
            </td>
            <th class="etiqueta-larga">TRAZABILIDAD:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>PROCEDIMIENTO:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['Procedimiento'] ?? 'PRO-PIMP-04' }}</div>
            </td>
            <th class="etiqueta-larga">CRITERIO DE EVALUACIÓN:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>ACCESORIO:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Accesorio'] ?? '' }}</div>
            </td>
            <th class="etiqueta-larga">TUBERÍA:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Tuberia'] ?? '' }}</div>
            </td>
            <th class="etiqueta-larga">ESTRUCTURAL:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Estructural'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>No. DE ISOMÉTRICO Y/O PLANO:</th>
            <td class="line" colspan="5">
                <div class="linea-general linea-desplazada">{{ $Detalles_Generales['No_Isometrico_Plano'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>OBSERVACIONES Y NOTAS:</th>
            <td class="line" colspan="5">
                <div class="linea-general linea-desplazada">{{ $Detalles_Generales['Observaciones_Notas'] ?? '' }}</div>
            </td>
        </tr>
    </tbody>
</table>

<div class="spacer"></div>
<table class="grid">
    <thead>
        <tr class="section-title">
            <th colspan="6">ENSAYO DE DUREZA</th>
        </tr>
        <tr>
            <th colspan="6">DATOS DEL EQUIPO</th>
        </tr>
        <tr>
            <th class="label">MARCA</th>
            <td>{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
            <th class="label">MODELO</th>
            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}</td>
            <th class="label">No. DE SERIE</th>
            <td>{{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}</td>
        </tr>
    </thead>
</table>

{{-- Siempre se imprimen diez celdas de dureza, aun cuando algunas lecturas estén vacías. --}}
@php
    $valoresDurezaPdf = array_values(array_pad(array_slice($Datos_Equipo['VALORES_DUREZA'] ?? [], 0, 10), 10, ''));
    // El promedio de dureza se presenta redondeado sin decimales, aunque venga guardado con decimal.
    $promedioDurezaPdf = $Datos_Equipo['PROMEDIO_DUREZA'] ?? '';
    $todosValoresDurezaGuiones = count($valoresDurezaPdf) > 0;
    foreach ($valoresDurezaPdf as $valorDurezaPdf) {
        if (!preg_match('/^-+$/', trim((string) $valorDurezaPdf))) {
            $todosValoresDurezaGuiones = false;
            break;
        }
    }
    if (trim((string) $promedioDurezaPdf) === '' && $todosValoresDurezaGuiones) {
        $promedioDurezaPdf = '---';
    }
    if ($promedioDurezaPdf !== '' && is_numeric(str_replace(',', '.', $promedioDurezaPdf))) {
        $promedioDurezaPdf = (string) round((float) str_replace(',', '.', $promedioDurezaPdf));
    }
@endphp
<table class="grid hardness-values">
    <colgroup>
        <col style="width:10.5%">
        <col style="width:10.5%">
        <col style="width:10.5%">
        <col style="width:10.5%">
        <col style="width:10.5%">
        <col style="width:27.5%">
        <col style="width:20%">
    </colgroup>
    <thead>
        <tr class="section-title">
            <th colspan="7">VALORES DE DUREZA MEDIDOS EN {{ $Datos_Equipo['ESCALA_DUREZA'] ?? 'XXX' }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach(array_slice($valoresDurezaPdf, 0, 5) as $valor)<td>{{ $valor }}</td>@endforeach
            <th class="label" rowspan="2">PROMEDIO</th>
            <td rowspan="2">{{ $promedioDurezaPdf }}</td>
        </tr>
        <tr>@foreach(array_slice($valoresDurezaPdf, 5, 5) as $valor)<td>{{ $valor }}</td>@endforeach</tr>
    </tbody>
</table>

<table class="grid">  <thead>
        <tr class="section-title">
            <th colspan="5">DATOS OBTENIDOS DEL MATERIAL</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th class="label">DESCRIPCIÓN DEL MATERIAL</th>
            <th class="label">DUREZA BRINELL</th>
            <th class="label">RESISTENCIA A LA TENSIÓN (KSI)</th>
            <th class="label">RESISTENCIA A LA CEDENCIA (KSI)</th>
            <th class="label">TAMAÑO DE GRANO</th>
        </tr>

        <tr><td>{{ $Datos_Equipo['DESCRIPCION_MATERIAL'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['DUREZA_BRINELL'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['RESISTENCIA_TENSION'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['RESISTENCIA_CEDENCIA'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['TAMANO_GRANO'] ?? '' }}</td>
        </tr>
    </tbody>
</table>

<table class="grid">
    <thead>
        <tr class="section-title">
            <th colspan="5">DATOS DE LA NORMA DE REFERENCIA</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th class="label">NORMA DE REFERENCIA</th>
            <th class="label">DUREZA BRINELL, MAX.</th>
            <th class="label">TENSIÓN MÍNIMA (KSI)</th>
            <th class="label">CEDENCIA ESPECIFICADA (KSI)</th>
            <th class="label">TENSIÓN MÁXIMA (KSI)</th>
        </tr>

        <tr>
            <td>{{ $Datos_Equipo['NORMA_REFERENCIA'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['DUREZA_BRINELL_MAX'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['RESISTENCIA_TENSION_MIN'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['RESISTENCIA_CEDENCIA_ESPECIFICADA'] ?? '' }}</td>
            <td>{{ $Datos_Equipo['RESISTENCIA_TENSION_MAX'] ?? '' }}</td>
        </tr>
    </tbody>
</table>

<div class="spacer"></div>
<table class="grid">
    <thead>
        <tr class="section-title">
            <th colspan="6">ANÁLISIS QUÍMICO</th>
        </tr>
        <tr>
            <th colspan="6">DATOS DEL EQUIPO</th>
        </tr>
        <tr>
            <th class="label">MARCA</th>
            <td>{{ $Datos_Equipo['MARCA_EQUIPO1'] ?? '' }}</td>
            <th class="label">MODELO</th>
            <td>{{ $Datos_Equipo['MODELO_EQUIPO1'] ?? '' }}</td>
            <th class="label">No. DE SERIE</th>
            <td>{{ $Datos_Equipo['NS_EQUIPO1'] ?? '' }}</td>
        </tr>
    </thead>
</table>

<div style="margin-bottom: 2px;"></div>

@if(!empty($NormaIM['Tabla']))
@php
    // Calcula una sola escala para que la captura XRF y su título terminen con el mismo ancho.
    $capturaAnchoOriginal = max(0, (int) ($NormaIM['Captura_XRF']['ancho'] ?? 0));
    $capturaAltoOriginal = max(0, (int) ($NormaIM['Captura_XRF']['alto'] ?? 0));
    $capturaAnchoPdf = null;
    $capturaAltoPdf = null;
    $capturaAnchoTituloPdf = null;

    if ($capturaAnchoOriginal > 0 && $capturaAltoOriginal > 0) {
        $escalaCaptura = min(320 / $capturaAnchoOriginal, 270 / $capturaAltoOriginal);
        $capturaAnchoPdf = max(1, (int) round($capturaAnchoOriginal * $escalaCaptura));
        $capturaAltoPdf = max(1, (int) round($capturaAltoOriginal * $escalaCaptura));
        // El encabezado debe abarcar exactamente el mismo ancho que el recorte XRF visible en el PDF.
        $capturaAnchoTituloPdf = $capturaAnchoPdf;
    }
@endphp
<div class="paginaComposicion">

{{-- El bloque inicia inmediatamente para reservar una separación segura antes de las firmas. --}}
<div style="margin-bottom: 0;"></div>

    <table class="layoutComposicion">
        <tbody>
            <tr>
                <td class="resultadoComposicion">
                    <table class="tablaResultadosQuimicos">
                        <thead>
                            <tr>
                                <th class="tituloComparacion" colspan="3">COMPOSICIÓN QUÍMICA TEÓRICA VS PROMEDIO DE VALORES<br>EN LA PIEZA ANALIZADA</th>
                            </tr>
                            <tr>
                                <th>ELEMENTO QUÍMICO</th>
                                <th>PROMEDIOS DE LA PIEZA ANALIZADA</th>
                                <th>COMPOSICIÓN QUÍMICA TEÓRICA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($NormaIM['Tabla'] as $filaNorma)
                                <tr>
                                    <th>{{ $filaNorma['Elemento'] ?? '' }}</th>
                                    <td>{{ $filaNorma['Promedio'] ?? '' }}</td>
                                    <td>{{ $filaNorma['Composicion'] ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
                <td class="capturaComposicion">
                    <div class="bloqueCapturaQuimica" @if($capturaAnchoPdf) style="width: {{ $capturaAnchoPdf }}px;" @endif>
                        <div class="tituloCapturaQuimica">
                            VALORES OBTENIDOS DE LA PIEZA ANALIZADA<br>
                        </div>
                        @if(!empty($CapturaXrf))
                            <img class="imagenCapturaQuimica"
                                src="{{ $CapturaXrf }}"
                                alt="Captura del análisis químico">
                        @endif
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endif

</body>
</html>
