<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-05_B/01</title>
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
            width: 106%;
            margin-left: -15.6px;
            border-collapse: separate;
            border-spacing: 20px 20px;
            table-layout: fixed;
        }

        .cuadriculaFotos td {
            width: 312px;
            height: 170px;
            padding: 0;
            text-align: center;
            vertical-align: middle;
        }

        .fotoCuadrante {
            height: auto;
            border: 1px solid #000;
            overflow: hidden;
        }

        .fotoCuadrante img {
            display: block;
            width: 100%;
            height: 170px;
            object-fit: cover;
        }

        .fotoCuadranteComentario {
            min-height: 20px;
            padding: 5px 3px 2px;
            border-top: 1px solid #000;
            font-size: 8px;
            line-height: 9px;
            overflow: hidden;
        }

        .fotoCuadranteTexto {
            padding: 8px;
            font-size: 8px;
            line-height: 10px;
            text-align: left;
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
                <th style="width: 400%;">FORMATO<br>Format</th>
                <th style="width: 70%;">CÓDIGO<br>Code</th>
                <th style="width: 100%;">FOR-PIMP-05_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2"> Informe de Análisis Químico Mediante la Técnica de Espectrometría de Emisión Óptica (OES)<br>
                    Chemical Analysis Report Using the Optical Emission Spectrometry Technique (OES)</th>
                <th>VERSIÓN<br>Version</th>
                <th>2</th>
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
                <th colspan="6" class="tituloGeneralPdf">DATOS GENERALES<br>General Data</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th class="etiquetaGeneral">FECHA:<br>Date</th>
                <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">No. REPORTE:</span>No. Report:</th>
                <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">CLIENTE:<br>Client:</th>
                <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">No. CONTRATO:</span>No. Contract:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">PROYECTO:<br>Project:</th>
                <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral" style="white-space: nowrap;">ORDEN DE TRABAJO:<br>Work Order:</th>
                <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">FOLIO:<br>Folio:</th>
                <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">PARTIDA:<br>Lot:</th>
                <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">INSTALACIÓN:<br>Location:</th>
                <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">No. ISOMÉTRICO:</span>No. Isometric:</th>
                <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral" style="white-space: nowrap;">NOMBRE DE LAS PIEZAS:<br>Name of the Piece:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">MATERIAL:</span>Material: </th>
                <td class="valorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">TRAZABILIDAD:</span>Traceability:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">PROCEDIMIENTO:<br>Procedure:</th>
                <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">CRITERIO DE EVALUACIÓN:</span>Evaluation Criterion:</th>
                <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">ACCESORIO:<br>Fittings:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Accesorio'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">TUBERÍA</span>Piping:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Tuberia'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">ESTRUCTURAL:</span>Structural:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Estructural'] ?? '' }}</td>
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
                        @if(!empty($fotoCompleta['es_cuadro_texto']))
                            <div class="fotoCuadranteTexto">{{ $fotoCompleta['comment'] ?? '' }}</div>
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
                @foreach([
                    ['arriba_izquierda', 'arriba_derecha'],
                    ['abajo_izquierda', 'abajo_derecha']
                ] as $filaPosiciones)
                    <tr>
                        @foreach($filaPosiciones as $posicion)
                            @php($foto = $fotosPorPosicion[$posicion] ?? null)
                            <td class="{{ $foto ? '' : 'fotoCuadranteVacio' }}">
                                @if($foto)
                                    <div class="fotoCuadrante">
                                        @if(!empty($foto['es_cuadro_texto']))
                                            <div class="fotoCuadranteTexto">{{ $foto['comment'] ?? '' }}</div>
                                        @else
                                            <img src="{{ $foto['path'] }}">
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
