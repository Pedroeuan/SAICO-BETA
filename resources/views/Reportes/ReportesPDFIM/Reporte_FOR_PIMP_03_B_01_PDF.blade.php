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
        @page { margin: 3.0cm 1.5cm 2.2cm 1.5cm; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 7px;
            color: #000;
        }

        header {
            position: fixed;
            top: -65px;
            left: 0;
            right: 0;
        }

        footer {
            position: fixed;
            bottom: -58px;
            left: 0;
            right: 0;
        }

        table { width: 100%; border-collapse: collapse; table-layout: fixed; }

        .tabla-header { font-size: 8px; text-align: center; }
        .tabla-header th,
        .tabla-header td {
            border: 1px solid #000;
            padding: 1px 3px;
            height: 16px;
            line-height: 9px;
            vertical-align: middle;
        }
        .tabla-header .nombre-formato {
            font-size: 10px;
            line-height: 13px;
            font-weight: normal;
        }
        .tabla-header img { width: auto; height: 52px; }

        .titulo-seccion {
            background: #305496;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .tabla-datos { margin-top: 1px; font-size: 7px; }
        .tabla-datos th,
        .tabla-datos td {
            border: 0;
            padding: 1px 3px;
            height: 15px;
            vertical-align: middle;
        }
        .tabla-datos th {
            font-weight: bold;
            text-align: left;
            line-height: 8px;
        }
        .tabla-datos td.valor-general {
            border-bottom: 1px solid #000;
            text-align: center;
        }
        .tabla-datos .titulo-seccion {
            border: 1px solid #000;
            padding: 1px;
            height: 17px;
            line-height: 8px;
            text-align: center;
        }
        .etiqueta-centrada { text-align: center !important; }

        .tabla-analisis { margin-top: 3px; font-size: 6px; text-align: center; }
        .tabla-analisis th,
        .tabla-analisis td {
            border: 1px solid #9ea7b3;
            padding: 3px 2px;
            vertical-align: middle;
        }
        .tabla-analisis th { font-weight: bold; line-height: 8px; }

        .tabla-fotos { margin-top: 3px; table-layout: fixed; }
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
            overflow: hidden;
            width: 260px;
            height: auto;
            line-height: 0;
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

        .foto-container > div {
            width: 100%;
            max-width: 270px;
            margin: 0;
            box-sizing: border-box;
        }

        /*
         * contain muestra la imagen completa sin deformarla y ajusta el contenedor
         * a su proporción real.
         */
        .foto-container img {
            display: block;
            max-width: 270px;
            max-height: auto;
            object-fit: contain;
            margin: 0;
        }

        /* Texto descriptivo que se presenta debajo de cada fotografia. */
        .comment {
            margin: 0;
            padding: 3px 2px 2px;
            /*border-top: 1px solid #000;*/
            font-size: 5.3px;
            line-height: 1.05;
            text-align: center;
            box-sizing: border-box;
            width: 100%;
            max-width: 270px;
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
        .foto-marco {
            height: 185px;
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            overflow: hidden;
        }

        .foto-marco img {
            display: block;
            max-width: 100%;
            max-height: 181px;
            margin: 0 auto;
        }

        .tabla-fotos .foto-completa .foto-marco { height: 390px; }
        .tabla-fotos .foto-completa .foto-marco img { max-height: 386px; }

        .comentario-foto {
            height: 16px;
            padding-top: 2px;
            line-height: 8px;
            overflow: hidden;
            text-align: center;
        }

        .tabla-observaciones { margin-bottom: 2px; font-size: 7px; }
        .tabla-observaciones th { width: 17%; text-align: left; }
        .tabla-observaciones td { border-bottom: 1px solid #000; }
        .numero-pagina-actual::before { content: counter(page); }
    </style>
</head>
<body>
<header>
    <table class="tabla-header">
        <tr>
            <th style="width: 53%;">FORMATO<br>Format</th>
            <td style="width: 10%;">Código:<br>Code:</td>
            <td style="width: 11%;">FOR-PIMP-03_B/01</td>
            <td rowspan="3" style="width: 26%;"><img src="{{ $Logo }}" alt="Logo"></td>
        </tr>
        <tr>
            <td rowspan="2" class="nombre-formato">Informe de Análisis Metalográfico<br>Metallographic Analysis Report</td>
            <td>Versión:<br>Version:</td>
            <td>2</td>
        </tr>
        <tr>
            <td>Página:<br>Page:</td>
            <td><span class="numero-pagina-actual"></span> DE {{ $totalPaginasPdf }}<br><span class="numero-pagina-actual"></span> Of {{ $totalPaginasPdf }}</td>
        </tr>
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
    <tr><th colspan="6" class="titulo-seccion">DATOS GENERALES<br>General Data</th></tr>
    <tr>
        <th>FECHA:<br>Date:</th>
        <td colspan="2" class="valor-general">{{ $fechaPdf }}</td>
        <th colspan="2" class="etiqueta-centrada">No. REPORTE:<br>No. Report:</th>
        <td class="valor-general">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
    </tr>
    <tr>
        <th>CLIENTE:<br>Client:</th>
        <td colspan="3" class="valor-general">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
        <th class="etiqueta-centrada">No. CONTRATO:<br>No. Contract:</th>
        <td class="valor-general">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
    </tr>
    <tr>
        <th>CONTRATO:<br>Contract:</th>
        <td colspan="5" class="valor-general">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
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
        <th colspan="2" class="etiqueta-centrada">No. ISOMÉTRICO:<br>No. Isometric:</th>
        <td class="valor-general">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
    </tr>
    <tr>
        <th>NOMBRE DE LA PIEZA:<br>Name of the Piece:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['Nom_pieza'] ?? '' }}</td>
        <th colspan="2" class="etiqueta-centrada">MATERIAL:<br>Material:</th>
        <td class="valor-general">{{ $Detalles_Generales['Material'] ?? '' }}</td>
    </tr>
    <tr>
        <th>PROCEDIMIENTO:<br>Procedure:</th>
        <td colspan="2" class="valor-general">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
        <th colspan="2" class="etiqueta-centrada">TRAZABILIDAD:<br>Traceability:</th>
        <td class="valor-general">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
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

<table class="tabla-analisis">
    <colgroup>
        <col style="width: 14%;"><col style="width: 8%;"><col style="width: 14%;">
        <col style="width: 9%;"><col style="width: 9%;"><col style="width: 11%;">
        <col style="width: 12%;"><col style="width: 10%;"><col style="width: 13%;">
    </colgroup>
    <tr><th colspan="9" class="titulo-seccion">ANÁLISIS METALOGRÁFICO<br>Metallographic Analysis</th></tr>
    <tr>
        <th colspan="3">NÚMERO DE LIJA PARA EL DESBASTE<br>Number of Sanding Paper for Grinding</th>
        <th colspan="2">MATERIAL PARA EL PULIDO<br>Polishing Material</th>
        <th colspan="2">DATOS DE ATAQUE QUÍMICO<br>Chemical Attack Data</th>
        <th>FASES PRESENTES<br>Present Phases</th>
        <th rowspan="2">ESPECIFICACIÓN APROXIMADA DEL MATERIAL<br>Approximate Material Specification</th>
    </tr>
    <tr>
        <td>240</td><td>320</td><td>400</td>
        <th>PAÑO<br>Cloth</th><td>{{ $Datos_Equipo['MATERIAL_PANO'] ?? '' }}</td>
        <th>REACTIVO<br>Reagent</th><td>{{ $Datos_Equipo['REACTIVO'] ?? '' }}</td>
        <td rowspan="2">{{ $Datos_Equipo['FASES_PRESENTES'] ?? '' }}</td>
    </tr>
    <tr>
        <td>500</td><td>1000</td><td>1500</td>
        <th>ABRASIVO<br>Abrasive</th><td>{{ $Datos_Equipo['MATERIAL_ABRASIVO'] ?? '' }}</td>
        <th>TIEMPO<br>Time</th><td>{{ $Datos_Equipo['TIEMPO_ATAQUE'] ?? '' }}</td>
        <td>{{ $Datos_Equipo['ESPECIFICACION_MATERIAL'] ?? '' }}</td>
    </tr>
</table>

@php
    $posiciones = ['arriba_izquierda','arriba_derecha','abajo_izquierda','abajo_derecha',];
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
    <table class="imagenes-reporte" style="width:100%" border="1">
        @if($configuracionFotos['completa'])
            <tr>
                <td class="foto-completa" colspan="2">
                    <div class="foto-marco"><img src="{{ $configuracionFotos['completa']['path'] }}" alt="Fotografía"></div>
                    <div class="comentario-foto">{{ $configuracionFotos['completa']['comment'] ?? '' }}</div>
                </td>
            </tr>
        @else
            @foreach([['arriba_izquierda', 'arriba_derecha'],['abajo_izquierda', 'abajo_derecha'],] as $fila)
                <tr>
                @foreach($fila as $posicion)
                    @php $foto = $configuracionFotos['posiciones'][$posicion] ?? null; @endphp
                    @if($posicion === 'arriba_derecha' || $posicion === 'abajo_derecha')
                        <th style="width:5%">
                            <div>
                                &nbsp;
                            </div>
                        </th>
                    @endif
                    <th class="foto-container {{ $posicion }}">
                        <div>
                            @if($foto) 
                                <img src="{{ $foto['path'] }}" alt="Fotografía">
                            @endif
                        </div>
                        <div class="comment">{{ $foto['comment'] ?? '' }}</div>
                    </th>
                    @if($posicion === 'arriba_izquierda' || $posicion === 'abajo_izquierda')
                    <th style="width:5%">
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
