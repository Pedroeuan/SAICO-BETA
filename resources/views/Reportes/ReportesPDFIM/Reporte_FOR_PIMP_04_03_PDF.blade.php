<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-04/03</title>
    <style>
        @page {
            margin: 
            2.5cm /* superior */
            1.5cm /* derecho */
            2.4cm /* inferior */
            1.5cm; /* izquierdo */
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
            bottom: -30px;
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
            font-size: 7px;
        }
        .etiquetaGeneral {
            width: 15%;
            font-weight: bold;
            white-space: nowrap;
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
        .valorGeneral {
            border-bottom: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            height: 10px;
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
        .shots {
            table-layout: fixed;
        }
        .shot-cell {
            width: 50%;
            /* Menos margen interno para que los disparos ocupen mejor el espacio disponible del formato. */
            padding: 1px 3px 1px;
            vertical-align: top;
        }
        .shot-title {
            background: #305496;
            color: #fff;
            border: .6px solid #000;
            text-align: center;
            font-weight: bold;
            padding: 2px;
            font-size: 6.8px;
        }
        .shot-images {
            table-layout: fixed;
        }
        .shot-images td {
            width: 50%;
            padding: 1px;
        }
        .shot-image {
            /* Crecimiento moderado: 04_03 tiene mas tablas arriba que 06_B/01. */
            height: 4.7cm;
            border: .6px solid #000;

            text-align: center; }
        .shot-image img {
            width: 100%;
            height: 4.65cm;
            object-fit: contain;
        }
        .chemical {
            table-layout: fixed;
            text-align: center;
            font-size: 5.2px;
            /* Tabla comparativa mas angosta y centrada dentro de la celda derecha del 04_03. */
            width: 86%;
            margin-left: auto;
            margin-right: auto;
        }
        .chemical th, .chemical td {
            border: .6px solid #000;
            padding: 1px;
            line-height: 5.8px;
            overflow-wrap: break-word;
        }
        .chemical thead th {
            background: #e7e6e6;
            font-weight: bold;
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
                <th style="width:60%">CÓDIGO</th>
                <th style="width:100%">FOR-PIMP-04_B/03</th>
                <th rowspan="3" style="width:80%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Caracterización de Materiales Mediante la Técnica de Fluorescencia de Rx (XRF)</th>
                <th>VERSIÓN</th>
                <th>0</th>
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
            <th class="etiquetaGeneralCentrada">No. REPORTE:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>CLIENTE:</th>
            <td class="line" colspan="3">
                <div class="linea-general">{{ $Detalles_Generales['Cliente'] ?? '' }}</div>
            </td>
            <th class="etiquetaGeneralCentrada">CONTRATO:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Contrato'] ?? '' }}</div>
            </td>
        </tr>
        <tr><th>PROYECTO:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Proyecto'] ?? '' }}</div>
            </td>
        </tr>
        <tr><th>ORDEN DE TRABAJO:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</div>
            </td>
        </tr>
        <tr><th>FOLIO:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Folio'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>PARTIDA:</th>
            <td class="line" colspan="5">
                <div class="linea-general">{{ $Detalles_Generales['Partida'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>INSTALACIÓN:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['Instalacion'] ?? '' }}</div>
            </td>
            <th class="etiquetaGeneralCentrada">No. DE ISOMÉTRICO:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>NOMBRE DE LA PIEZA:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}</div>
            </td>
            <th class="etiquetaGeneralCentrada">MATERIAL:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Material'] ?? '' }}</div>
            </td>
            <th class="etiquetaGeneralCentrada">TRAZABILIDAD:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>PROCEDIMIENTO:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['Procedimiento'] ?? 'PRO-PIMP-04' }}</div>
            </td>
            <th class="etiquetaGeneralCentrada">CRITERIO DE EVALUACIÓN:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th>ACCESORIO:</th>
            <td class="line"><div class="linea-general">{{ $Detalles_Generales['Accesorio'] ?? '' }}</div>
            </td>
            <th class="etiquetaGeneralCentrada">TUBERÍA:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Tuberia'] ?? '' }}</div>
            </td>
            <th class="etiquetaGeneralCentrada">ESTRUCTURAL:</th>
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
        <tr><th>OBSERVACIONES Y NOTAS:</th>
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
            @foreach(array_slice($valoresDurezaPdf, 0, 5) as $valor)
            <td>{{ $valor }}</td>
            @endforeach
            <th class="label" rowspan="2">PROMEDIO</th>
            <td rowspan="2">{{ $promedioDurezaPdf }}</td>
        </tr>
        <tr>
            @foreach(array_slice($valoresDurezaPdf, 5, 5) as $valor)
            <td>{{ $valor }}</td>
            @endforeach
        </tr>
    </tbody>
</table>

<table class="grid">
    <thead>
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
        <tr>
            <td>{{ $Datos_Equipo['DESCRIPCION_MATERIAL'] ?? '' }}</td>
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

{{-- Distribución fija: disparos 1 y 2 arriba; disparo 3 y composición química abajo. --}}
@php
    $ordinalesDisparoPdf = [1 => '1er.', 2 => '2do.', 3 => '3er.'];
    $ordinalesDisparoIngles = [1 => '1st', 2 => '2nd', 3 => '3rd'];
    $distribucionDisparosPdf = [[1, 2], [3, 'tabla_quimica']];
@endphp
<table class="shots">
    @foreach($distribucionDisparosPdf as $filaDisparos)
        <tr>
            @foreach($filaDisparos as $celdaDisparo)
                <td class="shot-cell">
                    @if($celdaDisparo === 'tabla_quimica')
                        <table class="chemical">
                            <colgroup>
                                <col style="width:25%">
                                <col style="width:34%">
                                <col style="width:41%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th colspan="3">COMPOSICIÓN QUÍMICA TEÓRICA<br>VS<br>PROMEDIO DE VALORES EN LA PIEZA ANALIZADA</th>
                                </tr>
                                <tr>
                                    <th>ELEMENTO</th>
                                    <th>PROMEDIOS DE LA PIEZA ANALIZADA</th>
                                    <th>COMPOSICIÓN QUÍMICA TEÓRICA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(($NormaIM['Tabla'] ?? []) as $filaNorma)
                                    <tr>
                                        <th>{{ $filaNorma['Elemento'] ?? '' }}</th>
                                        <td>{{ $filaNorma['Promedio'] ?? '' }}</td>
                                        <td>{{ $filaNorma['Composicion'] ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">SIN DATOS / NO DATA</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        <div class="shot-title">{{ $ordinalesDisparoPdf[$celdaDisparo] }} DISPARO<br>VALORES OBTENIDOS EN LA PIEZA ANALIZADA</div>
                        <table class="shot-images"><tr>
                            @for($indiceImagen = 0; $indiceImagen < 2; $indiceImagen++)
                                <td><div class="shot-image">
                                    @if(!empty($Disparos[$celdaDisparo][$indiceImagen]))<img src="{{ $Disparos[$celdaDisparo][$indiceImagen] }}" alt="Disparo {{ $celdaDisparo }}">@endif
                                </div>
                            </td>
                            @endfor
                        </tr>
                    </table>
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
</table>
</body>
</html>
