@php
    $paginasAsignadasPdf = [1];
    foreach (($Fotos ?? []) as $indiceFotoPdf => $fotoPdf) {
        $paginasAsignadasPdf[] = max(
            1,
            (int) ($fotoPdf['pagina'] ?? (intdiv($indiceFotoPdf, 4) + 1))
        );
    }
    $totalPaginasPdf = count(array_unique($paginasAsignadasPdf));
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-03_B/01</title>
    <style>
        @page { 
            margin-top: 3cm;     /* ARRIBA */
            margin-right: 1.5cm;   /* DERECHA */
            margin-bottom: 2.7cm;  /* ABAJO */
            margin-left: 1.5cm;    /* IZQUIERDA */
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 8px;
            color: #000;
        }

        header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
        }

        footer {
            position: fixed;
            bottom: -68px;
            left: 0;
            right: 0;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; 
        }

        .tabla-header { font-size: 10px; text-align: center; }
        .tabla-header th,
        .tabla-header td {
            border: 1px solid #000;
            padding: 1px 3px;
            height: 16px;
            /*line-height: 9px;*/
            vertical-align: middle;
        }
        .tabla-header .nombre-formato {
            font-size: 10px;
            /*line-height: 13px;*/
            font-weight: normal;
        }
        .tabla-header img { width: auto; height: 52px; }

        .titulo-seccion {
            background: #305496;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }

        .tabla-datos {
            margin-top: 15px;
            font-size: 8px;
        }
        .tabla-datos th,
        .tabla-datos td {
            /*border: 0;
            padding: 1px 3px;
            height: 15px;
            vertical-align: middle;*/
            padding: .5px 1px;
        }
        .tabla-datos th {
            font-weight: bold;
            text-align: left;
            /*line-height: 8px;*/
        }
        .tabla-datos td.valor-general {
            border-bottom: 1px solid #000;
            text-align: center;
            padding-top: 4.5px;    /* BAJA el texto del usuario */
            padding-bottom: 0;
        }
        .tabla-datos .titulo-seccion {
            /*border: 1px solid #000;
            padding: 1px;
            height: 17px;
            line-height: 8px;*/
            text-align: center;
        }
        .etiqueta-centrada { 
            text-align: center !important; 
        }

        .tabla-analisis { 
            /*margin-top: 3px;*/ 
            font-size: 7px;
            line-height: 7px;
            text-align: center; 
        }
        .tabla-analisis th,
        .tabla-analisis td {
            border: 1px solid #9ea7b3;
            padding: 0 1px;
            vertical-align: middle;
        }
        .tabla-analisis th { 
            font-weight: bold; 
            /*line-height: 6px;*/ 
        }
        .tabla-analisis .titulo-seccion {
            padding: 1px 0;
            line-height: 7px;
        }
        .separador-datos-analisis {
            height: 2px;
            line-height: 2px;
        }

        .tabla-fotos { 
            margin-top: 3px; 
            table-layout: fixed; 
        }
        .tabla-fotos td {
            width: 50%;
            border: 0;
            padding: 3px 5px 2px;
            text-align: center;
            vertical-align: top;
        }
        /* Medidas de cada uno de los cuatro espacios disponibles por pagina. */
        .foto-container {
            padding: 0;
            /*border: 1px solid #000;
            display: block;*/
            text-align: center;
            vertical-align: middle;
            width: 47%;
            height: 218px;
            /*line-height: 0;*/
            position: relative;
        }

        .foto-container.arriba_izquierda {
            text-align: left;
            vertical-align: top;
        }

        .foto-container.arriba_derecha {
            text-align: right;
            vertical-align: top;
        }

        .foto-container.abajo_izquierda {
            text-align: left;
            vertical-align: bottom;
        }

        .foto-container.abajo_derecha {
            text-align: right;
            vertical-align: bottom;
        }

        /*
         * Todas las fotografías usan el mismo marco 4:3. La imagen se centra
         * completa y mantiene su proporción; los espacios libres quedan blancos.
         */
        .foto-visual {
            width: 100%;
            height: 200px;
            margin: 0;
            border: 1px solid #000;
            border-collapse: collapse;
            table-layout: fixed;
        }
        /* El patrón comparativo ASTM usa únicamente los límites superior e inferior. */
        .foto-visual.foto-visual-grain,
        .foto-marco.foto-visual-grain {
            border: 1px solid #000;
            overflow: hidden;
        }
        .foto-visual.foto-visual-grain img,
        .foto-marco.foto-visual-grain img {
            max-width: 280px;
            max-height: 180px;
            width: auto;
            height: auto;
            object-fit: contain;
            border: 0;
        }
        .foto-visual td {
            width: 100%;
            height: 200px;
            padding: 0;
            border: 0;
            text-align: center;
            vertical-align: middle;
        }
        .foto-visual img {
            display: inline-block;
            max-width: 99%;
            max-height: 198px;
            width: auto;
            height: auto;
            margin: 0 auto;
        }

        /* Texto descriptivo que se presenta debajo de cada fotografia. */
        .comment {
            margin: 0;
            padding: 3px 2px 2px;
            /*border-top: 1px solid #000;*/
            font-size: 7px;
            line-height: 1.1;
            text-align: center;
            box-sizing: border-box;
            width: 100%;
            max-width: 328px;
            height: 18px;
            overflow: hidden;
            overflow-wrap: anywhere;
            word-break: break-word;
        }
        /*
         * REGISTRO FOTOGRAFICO
         * La tabla usa una cuadricula fija de dos columnas y dos filas.
         * Cada fotografia conserva la posicion elegida por el usuario.
         */
        .imagenes-reporte {
            width: 687.5px;
            margin: 0px 0px;
            border-collapse: separate;
            /* Separacion horizontal y vertical entre fotografias. 
            border-spacing: 85px 10px;
            background: #920404;*/
            table-layout: fixed;
            
        }
        /* Las dos celdas de cada fila comparten exactamente la misma altura. */
        .imagenes-reporte > tbody > tr {
            height: 218px;
        }
        /* La separación forma parte del 100 % disponible y evita desbordar la columna derecha. */
        .foto-separador {
            width: 6%;
            padding: 0;
            border: 0;
        }
        .foto-marco {
            height: 185px;
            border: 1px solid #000;
            padding: 2px;
            box-sizing: border-box;
            text-align: center;
            overflow: hidden;
        }

        .foto-marco img {
            display: block;
            max-width: 100%;
            max-height: 181px;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: 0 auto;
        }

        .tabla-fotos
        .foto-completa 
        .foto-marco { 
            height: 390px; 
        }
        .tabla-fotos
        .foto-completa 
        .foto-marco img { 
            max-height: 386px; 
        }

        .comentario-foto {
            height: 16px;
            box-sizing: border-box;
            padding-top: 2px;
            /*line-height: 8px;*/
            overflow: hidden;
            text-align: center;
        }

        /* La celda conserva la altura total de la fila, pero no dibuja un pie inexistente. */
        .foto-container-texto {
            box-sizing: border-box;
            height: 218px;
            padding: 0;
            text-align: left !important;
            vertical-align: top !important;
        }
        .foto-texto {
            width: 100%;
            height: 200px;
            border: 1px solid #000;
            border-collapse: collapse;
            table-layout: fixed;
            box-sizing: border-box;
        }
        .foto-texto td {
            /* DomPDF suma el padding a la altura: 184 + 8 + 8 = 200 px exactos. */
            height: 184px;
            border: 0;
            padding: 8px;
            box-sizing: border-box;
            text-align: left;
            vertical-align: middle;
            font-size: 7px;
            font-weight: normal;
            line-height: 1.2;
        }
        .foto-texto-completa {
            height: 390px;
        }
        .foto-texto-completa td {
            height: 374px;
        }

        .tabla-observaciones { 
            margin-bottom: 2px; 
            font-size: 8px;
        }
        .tabla-observaciones th { 
            width: 17%; 
            text-align: left; 
        }
        .tabla-observaciones td { 
            border-bottom: 1px solid #000; 
        }
        .numero-pagina-actual::before { 
            content: counter(page); 
        }
        /* Igualación tipográfica con FOR-PIMP-02_B/03 sin modificar el parcial compartido. */
        .firmas-im[class] td {
            font-size: 8px;
        }
        .firmas-im[class] .firma-titulo {
            line-height: 8px;
            min-height: 5px;
        }
        .firmas-im[class] .firma-linea {
            height: 8px;
            line-height: 8px;
            padding-top: 8px;
        }
        .firmas-im[class] .firma-dato,
        .firmas-im[class] .firma-ficha {
            margin-top: 1px;
            line-height: 7px;
        }
        .firmas-im[class] .firma-separacion-cuatro td {
            padding-top: 4px;
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
    </style>
</head>
<body>
<header>
<table class="tablaheader">
        <thead>
            <tr>
                <th style="width:360%">FORMATO<br>Format</th>
                <th rowspan="3" style="width:70%">
                    @if(!empty($QR_PDF))
                        <img src="{{ $QR_PDF }}" alt="QR de documentos" style="width:58px; height:58px; display:block; margin:auto; padding:0;">
                    @endif
                </th>
                <th style="width:60%">CÓDIGO<br>Code:</td>
                <th style="width:100%">FOR-PIMP-03_B/01</th>
                <th rowspan="3" style="width:80%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis Metalográfico<br>Metallographic Analysis Report</th>
                <th>VERSIÓN<br>Version:</td>
                <th>2</th>
            </tr>
            <tr>
                <th>PÁGINA<br>Page:</th>
                <th><span class="numero-pagina-actual"></span> DE {{ $totalPaginasPdf }}<br><span class="numero-pagina-actual"></span> Of {{ $totalPaginasPdf }}</th>
            </tr>
        </thead>
    </table>
</header>

<footer>
    @include('Reportes.partials.firmas_im_pdf')
</footer>

@php
    try {
        $fechaPdf = !empty($Detalles_Generales['Fecha'])
            ? \Carbon\Carbon::parse($Detalles_Generales['Fecha'])->format('d/m/Y')
            : '';
    } catch (\Throwable $e) {
        $fechaPdf = $Detalles_Generales['Fecha'] ?? '';
    }
@endphp
<table class="tabla-datos">
    <colgroup>
        <col style="width: 14%;"><col style="width: 25%;">
        <col style="width: 12%;"><col style="width: 17%;">
        <col style="width: 13%;"><col style="width: 19%;">
    </colgroup>
    <tr>
        <th colspan="6" class="titulo-seccion">DATOS GENERALES<br>General Data</th>
    </tr>
    <tr>
        <th>FECHA:<br>Date:</th>
        <td colspan="2" class="valor-general">{{ $fechaPdf }}</td>
        <th class="etiqueta-centrada">No. REPORTE:<br>No. Report:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
    </tr>
    <tr>
        <th>CLIENTE:<br>Client:</th>
        <td colspan="3" class="valor-general">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
        <th class="etiqueta-centrada">No. CONTRATO:<br>No. Contract:</th>
        <td class="valor-general">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
    </tr>
    <tr>
        <th>CONTRATO:<br>Contract:</th>
        <td colspan="5" class="valor-general">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
    </tr>
    <tr>
        <th>ORDEN DE TRABAJO:<br>Work Order:</th>
        <td colspan="5" class="valor-general">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
    </tr>
    <tr>
        <th>FOLIO:<br>Folio:</th>
        <td colspan="5" class="valor-general">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
    </tr>
    <tr>
        <th>PARTIDA:<br>Lot:</th>
        <td colspan="5" class="valor-general">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
    </tr>
    <tr>
        <th>INSTALACIÓN:<br>Location:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
        <th class="etiqueta-centrada">No. ISOMÉTRICO:<br>No. Isometric:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
    </tr>
    <tr>
        <th>NOMBRE DE LA PIEZA:<br>Name of the Piece:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['Nom_pieza'] ?? '' }}</td>
        <th class="etiqueta-centrada">MATERIAL:<br>Material:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['Material'] ?? '' }}</td>
    </tr>
    <tr>
        <th>PROCEDIMIENTO:<br>Procedure:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
        <th class="etiqueta-centrada">TRAZABILIDAD:<br>Traceability:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
    </tr>
    <tr>
        <th>ACCESORIO:<br>Fitting:</th>
        <td class="valor-general">{{ $Detalles_Generales['Accesorio'] ?? '' }}</td>
        <th class="etiqueta-centrada">TUBERÍA:<br>Tube:</th>
        <td class="valor-general">{{ $Detalles_Generales['Tuberia'] ?? '' }}</td>
        <th class="etiqueta-centrada">ESTRUCTURAL:<br>Structural:</th>
        <td class="valor-general">{{ $Detalles_Generales['Estructural'] ?? '' }}</td>
    </tr>
    <tr>
        <th>OBSERVACIONES:<br>Remarks:</th>
        <td colspan="5" class="valor-general">{{ $Detalles_Generales['Observaciones'] ?? ($Datos_Equipo['Observaciones'] ?? '') }}</td>
    </tr>
</table>
<div class="separador-datos-analisis">&nbsp;</div>
<table class="tabla-analisis">
    <colgroup>
        {{--<col style="width: 14%;"><col style="width: 8%;"><col style="width: 14%;">
        <col style="width: 9%;"><col style="width: 9%;"><col style="width: 11%;">
        <col style="width: 12%;"><col style="width: 10%;"><col style="width: 13%;"> --}}
    </colgroup>
    <tr>
        <th colspan="9" class="titulo-seccion">ANÁLISIS METALOGRÁFICO<br>Metallographic Analysis</th>
    </tr>
    <tr>
        <th colspan="3">NÚMERO DE LIJA PARA EL DESBASTE<br>Number of Sanding Paper for Grinding</th>
        <th colspan="2">MATERIAL PARA EL PULIDO<br>Polishing Material</th>
        <th colspan="2">DATOS DE ATAQUE QUÍMICO<br>Chemical Attack Data</th>
        <th>FASES PRESENTES<br>Present Phases</th>
        <th rowspan="2">ESPECIFICACIÓN APROXIMADA DEL MATERIAL<br>Approximate Material Specification</th>
    </tr>
    <tr>
        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][0] ?? '240' }}</td>
        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][1] ?? '320' }}</td>
        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][2] ?? '400' }}</td>
        <th>PAÑO<br>Cloth</th>
        <td>{{ $Datos_Equipo['MATERIAL_PANO'] ?? '' }}</td>
        <th>REACTIVO<br>Reagent</th>
        <td>{{ $Datos_Equipo['REACTIVO'] ?? '' }}</td>
        <td rowspan="2">{{ $Datos_Equipo['FASES_PRESENTES'] ?? '' }}</td>
    </tr>
    <tr>
        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][3] ?? '500' }}</td>
        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][4] ?? '1000' }}</td>
        <td>{{ $Datos_Equipo['LIJAS_DESBASTE'][5] ?? '1500' }}</td>
        <th>ABRASIVO<br>Abrasive</th>
        <td>{{ $Datos_Equipo['MATERIAL_ABRASIVO'] ?? '' }}</td>
        <th>TIEMPO<br>Time</th>
        <td>{{ $Datos_Equipo['TIEMPO_ATAQUE'] ?? '' }}</td>
        <td>{{ $Datos_Equipo['ESPECIFICACION_MATERIAL'] ?? '' }}</td>
    </tr>
</table>

@php
    $posiciones = [
        'arriba_izquierda',
        'arriba_derecha',
        'abajo_izquierda',
        'abajo_derecha',
        ];
    $paginasFotos = [1 => ['completa' => null, 'posiciones' => []]];

    foreach (($Fotos ?? []) as $indice => $foto) {
        $pagina = max(1, (int) ($foto['pagina'] ?? (intdiv($indice, 4) + 1)));
        $posicion = $foto['posicion']
            ?? (!empty($foto['una_hoja']) ? 'pagina_completa' : $posiciones[$indice % 4]);

        if (!isset($paginasFotos[$pagina])) {
            $paginasFotos[$pagina] = ['completa' => null, 'posiciones' => []];
        }

        if ($posicion === 'pagina_completa') {
            $paginasFotos[$pagina]['completa'] = $foto;
            continue;
        }

        if (in_array($posicion, $posiciones, true)) {
            $paginasFotos[$pagina]['posiciones'][$posicion] = $foto;
        }
    }

    ksort($paginasFotos);
@endphp
@foreach($paginasFotos as $numeroHojaFotos => $configuracionFotos)
    @if(!$loop->first)
        <div style="page-break-before: always;"></div>
    @endif
    <table class="imagenes-reporte" style="width:100%" border="0">
        <colgroup>
            <col style="width:47%">
            <col style="width:6%">
            <col style="width:47%">
        </colgroup>
        @if($configuracionFotos['completa'])
            <tr>
                <td class="foto-completa {{ !empty($configuracionFotos['completa']['es_cuadro_texto']) ? 'foto-container-texto' : '' }}" colspan="3">
                    @if(!empty($configuracionFotos['completa']['es_cuadro_texto']))
                        <table class="foto-texto foto-texto-completa"><tr><td>{!! nl2br(e($configuracionFotos['completa']['comment'] ?? '')) !!}</td></tr></table>
                    @else
                        <div class="foto-marco {{ ($configuracionFotos['completa']['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'foto-visual-grain' : '' }}"><img src="{{ $configuracionFotos['completa']['path'] }}" alt="Fotografía"></div>
                        <div class="comentario-foto">{{ $configuracionFotos['completa']['comment'] ?? '' }}</div>
                    @endif
                </td>
            </tr>
        @else
            @foreach([['arriba_izquierda', 'arriba_derecha'],['abajo_izquierda', 'abajo_derecha'],] as $fila)
                <tr>
                @foreach($fila as $posicion)
                    @php $foto = $configuracionFotos['posiciones'][$posicion] ?? null; @endphp
                    @if($posicion === 'arriba_derecha')
                        <th class="foto-separador">
                            <div>
                                &nbsp;
                            </div>
                        </th>
                    @endif
                    <td class="foto-container {{ $posicion }} {{ !empty($foto['es_cuadro_texto']) ? 'foto-container-texto' : '' }}">
                        @if($foto)
                            @if(!empty($foto['es_cuadro_texto']))
                                <table class="foto-texto"><tr><td>{!! nl2br(e($foto['comment'] ?? '')) !!}</td></tr></table>
                            @else
                                {{-- Una tabla interior conserva el centrado y el tamaño uniforme en DomPDF. --}}
                                <table class="foto-visual {{ ($foto['origen_automatico'] ?? '') === 'patron_grano_historico' ? 'foto-visual-grain' : '' }}"><tr><td><img src="{{ $foto['path'] }}" alt="Fotografía"></td></tr></table>
                                <div class="comment">{{ $foto['comment'] ?? '' }}</div>
                            @endif
                        @endif
                    </td>
                    @if($posicion === 'abajo_izquierda')
                    <th class="foto-separador">
                        <div>
                            &nbsp;
                        </div>
                    </th>
                    @endif
                @endforeach
                </tr>
            @endforeach
        @endif
    </table>
@endforeach
</body>
</html>
