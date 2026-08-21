<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <title>FOR-PIMP-04/02</title>

    <style>
        /* PÁGINA */
        @page {
            margin: 
            2cm 
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
            top: -43px;
            left: 0;
            right: 0;
            height: auto;
            text-align: center;
        }
        footer {
            position: fixed;
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
        /* ESPAÑOL / INGLÉS GENERAL */
        .es {
            display: block;
            font-size: 8px;
            line-height: 7px;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }

        .en {
            display: block;
            font-size: 8px;
            line-height: 7px;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        /* ENCABEZADO PRINCIPAL */
        .tablaheader {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 9.5px;
        }
        .tablaheader th {
            border: 1px solid #000;
            vertical-align: middle;
        }
        .tablaheader .es,
        .tablaheader .en {
            font-size: 9.5px;
            line-height: 10px;
            font-weight: bold;
        }
        .tituloFormatoEs,
        .tituloFormatoEn {
            display: block;
            font-size: 9.5px;
            line-height: 10px;
            font-weight: bold;
            margin: 0;
            padding: 0;
        }
        /* ENCABEZADOS AZULES - ESPAÑOL ARRIBA / INGLÉS ABAJO*/
        .section-title,
        .encabezadoAzul {
            background: #305496;
            color: #fff;
            text-align: center;
            font-weight: bold;
        }
        .section-title th,
        .encabezadoAzul th {
            border: .7px solid #000;
            padding: 2px;
            vertical-align: middle;
        }
        .titulo-azul-es {
            display: block;
            font-size: 8.5px;
            line-height: 8.5px;
            font-weight: bold;
            color: #fff;
            margin: 0;
        }
        .titulo-azul-en {
            display: block;
            font-size: 8px;
            line-height: 8px;
            font-weight: bold;
            color: #fff;
            margin: 0;
        }
        /* DATOS GENERALES */
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
        .tablaGenerales tbody th .es,
        .tablaGenerales tbody th .en {
            font-size: 8px;
            line-height: 7px;
            font-weight: bold;
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
        /* TABLAS INTERNAS */
        .grid {
            table-layout: fixed;
            text-align: center;
            font-size: 8px;
        }
        .grid th,
        .grid td {
            border: .6px solid #000;
            padding: 1px;
            line-height: 8px;
            vertical-align: middle;
        }

        /* ENCABEZADOS GRISES - ESPAÑOL ARRIBA / INGLÉS ABAJO */
        .grid .label {
            background: #e7e6e6;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            padding: 1px;
        }
        .label-es {
            display: block;
            font-size: 7px;
            line-height: 7.5px;
            font-weight: bold;
            margin: 0;
        }
        .label-en {
            display: block;
            font-size: 7px;
            line-height: 7.5px;
            font-weight: bold;
            margin: 0;
        }
        /* SUBTÍTULOS BLANCOS - ESPAÑOL ARRIBA / INGLÉS ABAJO */
        .subtitulo-equipo {
            text-align: center;
            vertical-align: middle;
            padding: 1px !important;
        }
        .subtitulo-equipo-es {
            display: block;
            font-size: 8px;
            line-height: 8px;
            font-weight: bold;
        }
        .subtitulo-equipo-en {
            display: block;
            font-size: 8px;
            line-height: 8px;
            font-weight: bold;
        }
        /* DUREZA */
        .hardness-values tbody td,
        .hardness-values tbody th {
            height: 7px;
            padding: 0 1px;
            line-height: 7px;
            font-size: 7px;
        }
        .hardness-values .label-es,
        .hardness-values .label-en {
            font-size: 7px;
            line-height: 7px;
        }
        /* COMPOSICIÓN */
        .paginaComposicion {
            page-break-before: auto;
        }
        .layoutComposicion {
            width: 92%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 10px;
            position: relative;
            left: 0px;
        }
        .layoutComposicion > tbody > tr > td {
            padding: 0;
            vertical-align: top;
        }
        .resultadoComposicion,
        .capturaComposicion {
            width: 45%;
            padding-right: 5px !important;
        }
        .resultadoComposicion {
            padding-right: 5px !important;
        }
        .capturaComposicion {
            width: 55%;
            padding-left: 5px !important;
            padding-top: 0 !important;
        }
        /* TABLA RESULTADOS QUÍMICOS */
        .tablaResultadosQuimicos {
            width: 78%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 5.7px;
            margin-top: 0;
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
            height: auto;
            min-height: 25px;
            font-size: 5.2px;
            line-height: 6px;
            background: #e7e6e6;
        }
        .tablaResultadosQuimicos thead th.tituloComparacion {
            height: auto;
            min-height: 25px;
            padding: 2px 4px;
            font-size: 5.5px;
            line-height: 7px;
            font-weight: bold;
            text-align: center;
            background: #e7e6e6;
        }
        .tablaResultadosQuimicos .label-es,
        .tablaResultadosQuimicos .label-en {
            font-size: 5.2px;
            line-height: 6px;
            font-weight: bold;
        }
        .tablaResultadosQuimicos .tituloComparacion .label-es,
        .tablaResultadosQuimicos .tituloComparacion .label-en {
            font-size: 5.5px;
            line-height: 7px;
        }
        /* CAPTURA QUÍMICA */
        .tituloCapturaQuimica {
            width: 98%;
            border: 1px solid #000;
            box-sizing: border-box;
            padding: 3px 2px;
            font-weight: bold;
            font-size: 6px;
            line-height: 8px;
            text-align: center;
            margin: 0;
            position: static;
            left: auto;
        }
        .tituloCapturaQuimica .label-es,
        .tituloCapturaQuimica .label-en {
            font-size: 6px;
            line-height: 7px;
            font-weight: bold;
        }
        .bloqueCapturaQuimica {
            width: 60%;
            position: relative;
            left: 20%;     /* (100% - 60%) / 2 = 20% */
            margin: 0;
            padding: 0;
        }
        .imagenCapturaQuimica {
            display: block;
            width: 100%;
            max-width: none;
            height: 260px;
            max-height: 260px;
            margin: 0;
            padding: 0;
            object-fit: fill;
            object-position: center top;
        }
        .centro {
            text-align: center;
        }
    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
        <tr>
            <th style="width:360%">
                <span class="es">
                    FORMATO
                </span>
                <span class="en">
                    Format
                </span>
            </th>

            <th rowspan="3" style="width:70%">
                @if(!empty($QR_PDF))
                    <img src="{{ $QR_PDF }}" alt="QR de documentos" style="width:55px; height:55px; display:block; margin:auto; padding:0;">
                @endif
            </th>

            <th style="width:65%">
                <span class="es">
                    CÓDIGO
                </span>
                <span class="en">
                    Code
                </span>
            </th>

            <th style="width:85%">
                FOR-PIMP-04/02
            </th>

            <th rowspan="3" style="width:80%">
                <img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto" >
            </th>
        </tr>

        <tr>
            <th rowspan="2">
                <span class="tituloFormatoEs">
                    Informe de Caracterización de Materiales Mediante la Técnica de Espectrometría de Emisión Óptica (OES)
                </span>
                <span class="tituloFormatoEn">
                    Material characterization report using optical emission spectrometry (OES)
                </span>
            </th>
            <th>
                <span class="es">
                    VERSIÓN
                </span>
                <span class="en">
                    Version
                </span>
            </th>
            <th>2</th>
        </tr>

        <tr>
            <th>
                <span class="es">
                    PÁGINA
                </span>
                <span class="en">
                    Page
                </span>
            </th>
            <th></th>
        </tr>
        </thead>
    </table>
</header>

<footer>
    @include('Reportes.partials.firmas_im_pdf')
</footer>



{{--DATOS GENERALES --}}

<div style="margin-bottom:2px"></div>
<table class="tablaGenerales">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">
                <span class="titulo-azul-es">
                    DATOS GENERALES
                </span>
                <span class="titulo-azul-en">
                    General data
                </span>
            </th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <th>
            <span class="es">
                FECHA:
            </span>
            <span class="en">
                Date:
            </span>
        </th>
        <td class="line" colspan="2">
            <div class="linea-general">
                {{ $Detalles_Generales['Fecha'] ?? '' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                No. REPORTE:
            </span>
            <span class="en">
                Report No.:
            </span>
        </th>
        <td class="line" colspan="2">
            <div class="linea-general">
                {{ $Detalles_Generales['No_Reporte'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                CLIENTE:
            </span>
            <span class="en">
                Client:
            </span>
        </th>
        <td class="line" colspan="3">
            <div class="linea-general">
                {{ $Detalles_Generales['Cliente'] ?? '' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                CONTRATO:
            </span>
            <span class="en">
                Contract:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['Contrato'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                PROYECTO:
            </span>
            <span class="en">
                Project:
            </span>
        </th>
        <td class="line" colspan="5">
            <div class="linea-general">
                {{ $Detalles_Generales['Proyecto'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                ORDEN DE TRABAJO:
            </span>
            <span class="en">
                Work order:
            </span>
        </th>
        <td class="line" colspan="5">
            <div class="linea-general">
                {{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                FOLIO:
            </span>
            <span class="en">
                Reference:
            </span>
        </th>
        <td class="line" colspan="5">
            <div class="linea-general">
                {{ $Detalles_Generales['Folio'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                PARTIDA:
            </span>
            <span class="en">
                Item:
            </span>
        </th>
        <td class="line" colspan="5">
            <div class="linea-general">
                {{ $Detalles_Generales['Partida'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                INSTALACIÓN:
            </span>
            <span class="en">
                Facility:
            </span>
        </th>
        <td class="line" colspan="3">
            <div class="linea-general">
                {{ $Detalles_Generales['Instalacion'] ?? '' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                No. DE ISOMÉTRICO:
            </span>
            <span class="en">
                Isometric No.:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['No_Isometrico'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                NOMBRE DE LA PIEZA:
            </span>
            <span class="en">
                Part name:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                MATERIAL:
            </span>
            <span class="en">
                Material:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['Material'] ?? '' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                TRAZABILIDAD:
            </span>
            <span class="en">
                Traceability:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['Trazabilidad'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                PROCEDIMIENTO:
            </span>
            <span class="en">
                Procedure:
            </span>
        </th>
        <td class="line" colspan="2">
            <div class="linea-general">
                {{ $Detalles_Generales['Procedimiento'] ?? 'PRO-PIMP-04' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                CRITERIO DE EVALUACIÓN:
            </span>
            <span class="en">
                Evaluation criteria:
            </span>
        </th>
        <td class="line" colspan="2">
            <div class="linea-general">
                {{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                ACCESORIO:
            </span>
            <span class="en">
                Fitting:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['Accesorio'] ?? '' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                TUBERÍA:
            </span>
            <span class="en">
                Pipe:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['Tuberia'] ?? '' }}
            </div>
        </td>
        <th class="etiqueta-larga">
            <span class="es">
                ESTRUCTURAL:
            </span>
            <span class="en">
                Structural:
            </span>
        </th>
        <td class="line">
            <div class="linea-general">
                {{ $Detalles_Generales['Estructural'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                No. DE ISOMÉTRICO Y/O PLANO:
            </span>
            <span class="en">
                Isometric and/or drawing No.:
            </span>
        </th>
        <td class="line" colspan="5">
            <div class="linea-general linea-desplazada">
                {{ $Detalles_Generales['No_Isometrico_Plano'] ?? '' }}
            </div>
        </td>
    </tr>

    <tr>
        <th>
            <span class="es">
                OBSERVACIONES Y NOTAS:
            </span>
            <span class="en">
                Remarks and notes:
            </span>
        </th>
        <td class="line" colspan="5">
            <div class="linea-general linea-desplazada">
                {{ $Detalles_Generales['Observaciones_Notas'] ?? '' }}
            </div>
        </td>
    </tr>
    </tbody>
</table>
<div class="spacer"></div>

<table class="grid">
    <thead>
    <tr class="section-title">
        <th colspan="6">
            <span class="titulo-azul-es">
                ENSAYO DE DUREZA / Hardness Test
            </span>
        </th>
    </tr>

    <tr>
        <th colspan="6" class="subtitulo-equipo">
            <span class="subtitulo-equipo-es">
                DATOS DEL EQUIPO
            </span>
            <span class="subtitulo-equipo-en">
                Equipment Data
            </span>
        </th>
    </tr>

    <tr>
        <th class="label">
            <span class="label-es">
                MARCA
            </span>
            <span class="label-en">
                Brand
            </span>
        </th>
        <td>
            {{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}
        </td>

        <th class="label">
            <span class="label-es">
                MODELO
            </span>
            <span class="label-en">
                Model
            </span>
        </th>
        <td>
            {{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}
        </td>

        <th class="label">
            <span class="label-es">
                No. DE SERIE
            </span>
            <span class="label-en">
                Serial No.
            </span>
        </th>
        <td>
            {{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}
        </td>
    </tr>
    </thead>
</table>

@php
    $valoresDurezaPdf = array_values(array_pad(array_slice($Datos_Equipo['VALORES_DUREZA'] ?? [],0,10), 10, ''));
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
        <th colspan="7">
            <span class="titulo-azul-es">
                VALORES DE DUREZA MEDIDOS EN{{ $Datos_Equipo['ESCALA_DUREZA'] ?? 'XXX' }} / Hardness Values Measured in{{ $Datos_Equipo['ESCALA_DUREZA'] ?? 'XXX' }}
            </span>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach(
            array_slice($valoresDurezaPdf, 0, 5) as $valor
        )
            <td>
                {{ $valor }}
            </td>
        @endforeach
        <th
            class="label"rowspan="2">
            <span class="label-es">
                PROMEDIO
            </span>
            <span class="label-en">
                Average
            </span>
        </th>
        <td rowspan="2">
            {{ $promedioDurezaPdf }}
        </td>
    </tr>
    <tr>
        @foreach(array_slice($valoresDurezaPdf, 5,5) as $valor)
            <td>
                {{ $valor }}
            </td>
        @endforeach
    </tr>
    </tbody>
</table>

<table class="grid">
    <thead>
    <tr class="section-title">
        <th colspan="5">
            <span class="titulo-azul-es">
                DATOS OBTENIDOS DEL MATERIAL / Material Data Obtained
            </span>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th class="label">
            <span class="label-es">
                DESCRIPCIÓN DEL MATERIAL
            </span>
            <span class="label-en">
                Material Description
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                DUREZA BRINELL
            </span>
            <span class="label-en">
                Brinell Hardness
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                RESISTENCIA A LA TENSIÓN (KSI)
            </span>
            <span class="label-en">
                Tensile Strength (KSI)
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                RESISTENCIA A LA CEDENCIA (KSI)
            </span>
            <span class="label-en">
                Yield Strength (KSI)
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                TAMAÑO DE GRANO
            </span>
            <span class="label-en">
                Grain Size
            </span>
        </th>
    </tr>

    <tr>
        <td>
            {{ $Datos_Equipo['DESCRIPCION_MATERIAL'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['DUREZA_BRINELL'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['RESISTENCIA_TENSION'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['RESISTENCIA_CEDENCIA'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['TAMANO_GRANO'] ?? '' }}
        </td>
    </tr>
    </tbody>
</table>

<table class="grid">
    <thead>
    <tr class="section-title">
        <th colspan="5">
            <span class="titulo-azul-es">
                DATOS DE LA NORMA DE REFERENCIA /Reference Standard Data
            </span>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th class="label">
            <span class="label-es">
                NORMA DE REFERENCIA
            </span>
            <span class="label-en">
                Reference Standard
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                DUREZA BRINELL, MAX.
            </span>
            <span class="label-en">
                Brinell Hardness, Max.
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                TENSIÓN MÍNIMA (KSI)
            </span>
            <span class="label-en">
                Minimum Tensile Strength (KSI)
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                CEDENCIA ESPECIFICADA (KSI)
            </span>
            <span class="label-en">
                Specified Yield Strength (KSI)
            </span>
        </th>
        <th class="label">
            <span class="label-es">
                TENSIÓN MÁXIMA (KSI)
            </span>
            <span class="label-en">
                Maximum Tensile Strength (KSI)
            </span>
        </th>
    </tr>

    <tr>
        <td>
            {{ $Datos_Equipo['NORMA_REFERENCIA'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['DUREZA_BRINELL_MAX'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['RESISTENCIA_TENSION_MIN'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['RESISTENCIA_CEDENCIA_ESPECIFICADA'] ?? '' }}
        </td>
        <td>
            {{ $Datos_Equipo['RESISTENCIA_TENSION_MAX'] ?? '' }}
        </td>
    </tr>
    </tbody>
</table>
<div class="spacer"></div>

<table class="grid">
    <thead>
    <tr class="section-title">
        <th colspan="6">
            <span class="titulo-azul-es">
                ANÁLISIS QUÍMICO / Chemical Analysis
            </span>
        </th>
    </tr>

    <tr>
        <th
            colspan="6" class="subtitulo-equipo">
            <span class="subtitulo-equipo-es">
                DATOS DEL EQUIPO / Equipment Data
            </span> 
        </th>
    </tr>

    <tr>
        <th class="label">
            <span class="label-es">
                MARCA
            </span>
            <span class="label-en">
                Brand
            </span>
        </th>
        <td>
            {{ $Datos_Equipo['MARCA_EQUIPO1'] ?? '' }}
        </td>
        <th class="label">
            <span class="label-es">
                MODELO
            </span>
            <span class="label-en">
                Model
            </span>
        </th>
        <td>
            {{ $Datos_Equipo['MODELO_EQUIPO1'] ?? '' }}
        </td>
        <th class="label">
            <span class="label-es">
                No. DE SERIE
            </span>
            <span class="label-en">
                Serial No.
            </span>
        </th>
        <td>
            {{ $Datos_Equipo['NS_EQUIPO1'] ?? '' }}
        </td>
    </tr>
    </thead>
</table>
<div style="margin-bottom:2px;"></div>

@if(!empty($NormaIM['Tabla']))
@php
    $capturaAnchoOriginal = max(0, (int) ($NormaIM['Captura_XRF']['ancho']?? 0));
    $capturaAltoOriginal = max(0,(int) ($NormaIM['Captura_XRF']['alto']?? 0));
    $capturaAnchoPdf = null;
    $capturaAltoPdf = null;
    $capturaAnchoTituloPdf = null;

    if ($capturaAnchoOriginal > 0 && $capturaAltoOriginal > 0) 
        {
        $escalaCaptura = min(410 / $capturaAnchoOriginal, 265 / $capturaAltoOriginal);
        $capturaAnchoPdf = max(1, (int) round($capturaAnchoOriginal * $escalaCaptura));
        $capturaAltoPdf = max(1, (int) round($capturaAltoOriginal * $escalaCaptura));
        $capturaAnchoTituloPdf = $capturaAnchoPdf;
    }
@endphp
<div class="paginaComposicion">
    <div style="margin-bottom:0;"></div>
    <table class="layoutComposicion">
        <tbody>
        <tr>
            <td class="resultadoComposicion">
                <table class="tablaResultadosQuimicos">
                    <thead>
                    <tr>
                        <th
                            class="tituloComparacion" colspan="3">
                            <span class="label-es">
                                COMPOSICIÓN QUÍMICA TEÓRICA VS PROMEDIO DE VALORES EN LA PIEZA ANALIZADA
                            </span>
                            <span class="label-en">
                                Theoretical Chemical Composition vs Average Values of the Analyzed Piece
                            </span>
                        </th>
                    </tr>

                    <tr>
                        <th>
                            <span class="label-es">
                                ELEMENTO QUÍMICO
                            </span>
                            <span class="label-en">
                                Chemical Element
                            </span>
                        </th>
                        <th>
                            <span class="label-es">
                                PROMEDIOS DE LA PIEZA ANALIZADA
                            </span>
                            <span class="label-en">
                                Analyzed Piece Average
                            </span>
                        </th>
                        <th>
                            <span class="label-es">
                                COMPOSICIÓN QUÍMICA TEÓRICA
                            </span>
                            <span class="label-en">
                                Theoretical Chemical Composition
                            </span>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($NormaIM['Tabla'] as $filaNorma)
                        <tr>
                            <th>
                                {{ $filaNorma['Elemento'] ?? '' }}
                            </th>
                            <td>
                                {{ $filaNorma['Promedio'] ?? '' }}
                            </td>
                            <td>
                                {{ $filaNorma['Composicion'] ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </td>
            <td class="capturaComposicion">
                <div class="bloqueCapturaQuimica">
                    <div class="tituloCapturaQuimica">
                        <span class="label-es">
                            VALORES OBTENIDOS DE LA PIEZA ANALIZADA
                        </span>
                        <span class="label-en">
                            Values Obtained From the Analyzed Piece
                        </span>
                    </div>
                    @if(!empty($CapturaXrf))
                        <img class="imagenCapturaQuimica" src="{{ $CapturaXrf }}" alt="Captura del análisis químico">
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