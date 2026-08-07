<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-04/02</title>
    <style>
        @page { 
            margin: 2cm 
            1.2cm 
            2.1cm 
            2.2cm; 
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
            text-align: 
            
            center; }
        footer { 
            position: fixed; 
            bottom: -30px; 
            left: 0; 
            right: 0; 
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
            height: 5px; 
        }
        .metallographic { 
            table-layout: fixed; 
            text-align: center; 
            font-size: 6.5px; 
        }
        .metallographic th, .metallographic td { 
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
            border-spacing: 0 10px;
        }
        .photo-slot { 
            /* El marco de 185 px del PDF principal evita que Dompdf reparta una fila entre hojas. */
            width: 47%;
            height: 201px;
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
        .photo-slot.abajo_derecha { vertical-align: bottom; }
        .photo-content {
            width: 100%;
            max-width: 330px;
            height: 201px;
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
        .photo-text-cell-inner {
            padding: 12px 16px;
            text-align: justify;
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
            height: 185px;
            padding: 2px;
            /* El marco pertenece solo a la fotografía; el comentario queda fuera y sin borde. */
            border: 1px solid #000;
            vertical-align: middle;
            overflow: hidden;
            text-align: center;
        }
        /* El patrón comparativo ASTM se delimita solo arriba y abajo. */
        .photo-content.photo-content-grain .photo-image-cell {
            border-left: 0;
            border-right: 0;
        }
        .photo-slot img { 
            /* Mantiene cualquier proporción dentro del mismo marco, como en el formato 04_03. */
            display: block;
            max-width: 326px;
            max-height: 181px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: 0 auto;
        }
        .photo-comment { 
            height: 16px;
            border-top: 0;
            padding: 2px 2px 0;
            margin: 0;
            box-sizing: border-box;
            font-size: 5.3px;
            line-height: 1.05;
            font-weight: normal;
            text-align: center;
            vertical-align: middle;
            overflow-wrap: break-word;
            word-break: normal;
            white-space: normal;
            overflow: hidden;
        }
        .photo-comment-long {
            font-size: 5.5px;
            line-height: 6px;
        }
        .photo-text-box { 
            width: 100%; 
            height: 185px;
            padding: 12px 16px;
            text-align: justify;
            vertical-align: middle;
            font-size: 8px;
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
            font-size: 5.5px;
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
        .firmas-im { width: 100%; border-collapse: collapse; table-layout: fixed; }
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
    {{-- Encabezado propio del anexo fotográfico 04_03. --}}
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

{{-- Agrupa cada registro por página y cuadrante; página completa sustituye los cuatro espacios. --}}
@php
    $posicionesFoto = ['arriba_izquierda', 'arriba_derecha', 'abajo_izquierda', 'abajo_derecha'];
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
                <tr>
                    <th>CLIENTE:</th>
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
                <tr>
                    <th>PARTIDA:</th>
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
                    <th>NOMBRE DE LA PIEZA:</th>
                    <td class="line">
                        <div class="linea-general">{{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}</div>
                    </td>
                    <th class="etiqueta-larga">MATERIAL:</th>
                    <td class="line"><div class="linea-general">{{ $Detalles_Generales['Material'] ?? '' }}</div>
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
        </table>
        <div class="spacer"></div>
        {{-- La tabla metalográfica aparece una sola vez, en la primera página del anexo. --}}
        @if($loop->first)
            <table class="metallographic">
                <colgroup>
                    <col style="width:12%">
                    <col style="width:10%">
                    <col style="width:12%">
                    <col style="width:10%">
                    <col style="width:12%">
                    <col style="width:10%">
                    <col style="width:12%">
                    <col style="width:11%">
                    <col style="width:11%">
                </colgroup>
                <thead>
                    <tr class="subhead">
                        <th colspan="9">ANÁLISIS METALOGRÁFICO</th>
                    </tr>
                    <tr>
                        <th colspan="3">NÚMERO DE LIJA PARA EL DESBASTE</th>
                        <th colspan="2">MATERIAL PARA EL PULIDO</th>
                        <th colspan="2">DATOS DE ATAQUE QUÍMICO</th>
                        <th>FASES PRESENTES</th>
                        <th>ESPECIFICACIÓN APROXIMADA DEL MATERIAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][0] ?? '240' }}</td>
                        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][1] ?? '320' }}</td>
                        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][2] ?? '400' }}</td>
                        <th class="label">PAÑO</th>
                        <td>{{ $Datos_Equipo['MATERIAL_PANO'] ?? '' }}</td>
                        <th class="label">REACTIVO</th>
                        <td>{{ $Datos_Equipo['REACTIVO'] ?? '' }}</td>
                        <td rowspan="2">{{ $Datos_Equipo['FASES_PRESENTES'] ?? '' }}</td>
                        <td rowspan="2">{{ $Datos_Equipo['ESPECIFICACION_MATERIAL'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][3] ?? '500' }}</td>
                        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][4] ?? '1000' }}</td>
                        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][5] ?? '1500' }}</td>
                        <th class="label">ABRASIVO</th>
                        <td>{{ $Datos_Equipo['MATERIAL_ABRASIVO'] ?? '' }}</td>
                        <th class="label">TIEMPO</th>
                        <td>{{ $Datos_Equipo['TIEMPO_ATAQUE'] ?? '' }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="spacer"></div>
        @endif
        <table>
            <thead class="section-title">
                <tr>
                    <th>REGISTRO FOTOGRÁFICO</th>
                </tr>
            </thead>
        </table>

        {{-- Una posición puede contener una imagen o un cuadro de texto del mismo tamaño. --}}
        <table class="photo-grid">
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
                                    <td class="photo-image-cell">
                                        <img src="{{ $fotoCompleta['path'] }}" alt="Fotografía">
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
                                            <td class="photo-image-cell">
                                                <img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografía">
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
                                    <table class="photo-content">
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
                                    <table class="photo-content">
                                        <tr>
                                            <td class="photo-image-cell">
                                                <img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografía">
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
