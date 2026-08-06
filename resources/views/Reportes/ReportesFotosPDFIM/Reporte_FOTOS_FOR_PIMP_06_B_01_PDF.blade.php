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
            width: 12%;
            font-weight: bold;
            line-height: 10px;
        }

        .valorGeneral {
            border-bottom: 1px solid black;
            text-align: center !important;
            vertical-align: middle !important;
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
            padding-bottom: 0 !important;
        }

        .lineaValorGeneral {
            width: 100%;
            min-height: 10px;
            border-bottom: 1px solid black;
            box-sizing: border-box;
            text-align: center;
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
            border-collapse: separate;
            /* Replica la cuadricula estable del 04_03: dos columnas y dos filas iguales. */
            border-spacing: 8px 10px;
            table-layout: fixed;
        }

        .imagenes-reporte tr {
            height: 201px;
        }

        .foto-container {
            padding: 0;
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            width: 330px;
            height: 201px;
            box-sizing: border-box;
            position: relative;
        }

        /* El lienzo fijo iguala fotografias horizontales, verticales y patrones de grano. */
        .foto-imagen-area {
            width: 100%;
            height: 181px;
            line-height: 181px;
            overflow: hidden;
            text-align: center;
        }

        .foto-imagen-area img {
            display: inline-block;
            max-width: 326px;
            max-height: 181px;
            width: auto;
            height: auto;
            object-fit: contain;
            vertical-align: middle;
        }

        .foto-vacia {
            /* Conserva la celda aunque el usuario deje libre ese cuadrante. */
            background-color: #fff;
        }

        .comment {
            height: 20px;
            line-height: 7px;
            border-top: .6px solid black;
            padding: 2px;
            margin: 0;
            box-sizing: border-box;
            text-align: center;
            font-size: 6px;
            word-wrap: break-word;
            overflow: hidden;
        }

        /* La descripción ocupa la misma celda reservada para una fotografía. */
        .descripcion-reporte {
            box-sizing: border-box;
            width: 100%;
            height: 201px;
            padding: 12px;
            line-height: 11px;
            text-align: left;
            vertical-align: top;
            white-space: pre-wrap;
            overflow-wrap: break-word;
            overflow: hidden;
            font-size: 8px;
        }

        .foto-full .descripcion-reporte {
            height: 412px;
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
            height: 412px !important;
        }

        .foto-full .foto-imagen-area {
            height: 390px;
            line-height: 390px;
        }

        .foto-full .foto-imagen-area img {
            max-width: 100%;
            max-height: 390px;
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
    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO<br>Format</th>
                <th style="width: 70%;">CÓDIGO<br>Code</th>
                <th style="width: 100%;">FOR-PIMP-06_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X (XRF)<br>
                    Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF)</th>
                <th>VERSIÓN<br>Version</th>
                <th>3</th>
            </tr>
            <tr>
                <th>PÁGINA<br>Page</th>
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
        <table class="datosgenerales">
            <thead class="encabezadoAzul">
                <tr>
                    <th colspan="6">DATOS GENERALES<br>General Data</th>
                </tr>
            </thead>
            <tbody>
        <tr>
            <th class="etiquetaGeneral">FECHA<br>Date:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">No. REPORTE<br>No. Report:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">CLIENTE<br>Client:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">No. CONTRATO<br>No. Contract:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">CONTRATO<br>Contract:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">ORDEN DE TRABAJO<br>Work Order:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">FOLIO<br>Folio:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">PARTIDA<br>Lot:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">INSTALACION<br>Location:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada" style="white-space: nowrap;">NUMERO DE ISOMETRICO<br>No. Isometric:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">NOMBRE DE LA PIEZA<br>Name of the Piece:</th>
            <td class="valorGeneral" colspan="3">{!! str_replace('⌀', '<span class="simboloDiametro">⌀</span>', e($Detalles_Generales['Nom_Pieza'] ?? '')) !!}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">MATERIAL<br>Material:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">PROCEDIMIENTO<br>Procedure:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada" style="white-space: nowrap;">CRITERIO DE EVALUACION<br>Evaluation Criteria:</th>
            <td class="valorGeneral valorGeneralConLinea"><div class="lineaValorGeneral">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</div></td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">TRAZABILIDAD<br>Traceability:</th>
            <td class="valorGeneral valorGeneralConLinea"><div class="lineaValorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</div></td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">No. JUNTA<br>No. Joint:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Junta'] ?? '' }}</td>
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

        <table class="imagenes-reporte">
            @if($esHojaCompleta)
                <tr>
                    <td class="foto-container foto-full" colspan="2">
                        @if(!empty($fotoCompleta['es_cuadro_texto']))
                            <div class="descripcion-reporte">{{ $fotoCompleta['comment'] ?? '' }}</div>
                        @else
                            <div class="foto-imagen-area"><img src="{{ $fotoCompleta['path'] }}" alt="Fotografia"></div>
                            <p class="comment">{{ $fotoCompleta['comment'] }}</p>
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    @foreach(['arriba_izquierda', 'arriba_derecha'] as $posicion)
                        @if(isset($espacios[$posicion]))
                            <td class="foto-container">
                                @if(!empty($espacios[$posicion]['es_cuadro_texto']))
                                    <div class="descripcion-reporte">{{ $espacios[$posicion]['comment'] ?? '' }}</div>
                                @else
                                    <div class="foto-imagen-area"><img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografia"></div>
                                    <p class="comment">{{ $espacios[$posicion]['comment'] }}</p>
                                @endif
                            </td>
                        @else
                            <td class="foto-container foto-vacia">&nbsp;</td>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    @foreach(['abajo_izquierda', 'abajo_derecha'] as $posicion)
                        @if(isset($espacios[$posicion]))
                            <td class="foto-container">
                                @if(!empty($espacios[$posicion]['es_cuadro_texto']))
                                    <div class="descripcion-reporte">{{ $espacios[$posicion]['comment'] ?? '' }}</div>
                                @else
                                    <div class="foto-imagen-area"><img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografia"></div>
                                    <p class="comment">{{ $espacios[$posicion]['comment'] }}</p>
                                @endif
                            </td>
                        @else
                            <td class="foto-container foto-vacia">&nbsp;</td>
                        @endif
                    @endforeach
                </tr>
            @endif
        </table>
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
