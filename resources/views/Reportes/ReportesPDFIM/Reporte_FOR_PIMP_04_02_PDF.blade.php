<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-04/02</title>

    <style>
        @page { 
            margin: 2cm 1.2cm 1.1cm 2.2cm; 
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
            top: -32px; 
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
            font-size: 10px; 
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
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 0;
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
            padding-right: 7px !important;
        }
        .capturaComposicion {
            padding-left: 7px !important;
        }
        .tablaResultadosQuimicos {
            width: 70%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 5.7px;
            margin-top: 20px;
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
        .tituloCapturaQuimica,
        .imagenCapturaQuimica {
            width: 80%;
            margin: 0;
        }
        .imagenCapturaQuimica {
            display: block;
            max-height: 235px;
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
    </style>
</head>
<body>
<header>
    {{-- Encabezado propio del PDF principal 04_03, ajustado al formato de referencia en español. --}}
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width:400%">FORMATO</th>
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
    <table class="firmas-im firmas-im-{{ $numFirmas }}">
    @if($numFirmas == 1)
        <tr>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
        </tr>
    @elseif($numFirmas == 2)
        <tr>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</div>
            </td>
        </tr>
    @elseif($numFirmas == 3)
        <tr>
            <td></td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</div>
            </td>
            <td></td>
        </tr>
        <tr class="firma-separacion-tres">
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
            <td></td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo2'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-ficha">{{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}</div>
            </td>
        </tr>
    @elseif($numFirmas == 4)
        <tr>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Realizo'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</div>
                <div class="firma-dato">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
            </td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo1'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</div>
            </td>
        </tr>
        <tr class="firma-separacion-cuatro">
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo2'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</div>
            </td>
            <td>
                <div class="firma-titulo">{{ $Firmas_Reportes['Vobo3'] ?? '' }}</div>
                <div class="firma-linea">{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-dato">{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] ?? '' }}</div>
                <div class="firma-ficha">{{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}</div>
            </td>
        </tr>
    @endif
</table>
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
            <th>No. REPORTE:</th>
            <td class="line" colspan="2">
                <div class="linea-general">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</div>
            </td>
        </tr>
        <tr><th>CLIENTE:</th>
            <td class="line" colspan="3">
                <div class="linea-general">{{ $Detalles_Generales['Cliente'] ?? '' }}</div>
            </td>
            <th>CONTRATO:</th>
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
            <th class="etiqueta-larga">No. DE ISOMÉTRICO:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</div>
            </td>
        </tr>
        <tr>
            <th class="etiqueta-larga">NOMBRE DE LA PIEZA:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}</div>
            </td>
            <th>MATERIAL:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Material'] ?? '' }}</div>
            </td>
            <th>TRAZABILIDAD:</th>
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
            <th>TUBERÍA:</th>
            <td class="line">
                <div class="linea-general">{{ $Detalles_Generales['Tuberia'] ?? '' }}</div>
            </td>
            <th>ESTRUCTURAL:</th>
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
            <th colspan="7">VALORES DE DUREZA MEDIDOS (ESCALA {{ $Datos_Equipo['ESCALA_DUREZA'] ?? 'XXX' }})</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            @foreach(array_slice($valoresDurezaPdf, 0, 5) as $valor)<td>{{ $valor }}</td>@endforeach
            <th class="label" rowspan="2">PROMEDIO</th>
            <td rowspan="2">{{ $Datos_Equipo['PROMEDIO_DUREZA'] ?? '' }}</td>
        </tr>
        <tr>@foreach(array_slice($valoresDurezaPdf, 5, 5) as $valor)<td>{{ $valor }}</td>@endforeach</tr>
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
<div class="paginaComposicion">
    
<div style="margin-bottom: 30px;"></div>

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
                    <div class="tituloCapturaQuimica">
                        VALORES OBTENIDOS DE LA PIEZA ANALIZADA<br>
                    </div>
                    @if(!empty($CapturaXrf))
                        <img class="imagenCapturaQuimica" src="{{ $CapturaXrf }}" alt="Captura del análisis químico">
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endif

</body>
</html>
