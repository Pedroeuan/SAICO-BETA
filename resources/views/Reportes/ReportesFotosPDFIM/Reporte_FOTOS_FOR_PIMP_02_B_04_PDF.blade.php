<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-02_B/04</title>
    <style>
        /*
         * CONFIGURACION DE LA PAGINA
         * Orden de los margenes: superior, derecho, inferior e izquierdo.
         * El espacio superior e inferior permite mostrar el encabezado y las firmas.
         */
        @page {
            margin:
            3cm    /* Superior */
            1.2cm  /* Derecho */
            2.1cm  /* Inferior */
            2.2cm; /* Izquierdo */
        }

        /* Estilos generales del contenido del PDF. */
        body {
            font-family: Arial, sans-serif;
            /* Separa el contenido del encabezado fijo. */
            margin-top: 27px;
            padding-top: 0;
            padding-bottom: 0;
        }

        /*
         * ENCABEZADO Y PIE DE PAGINA
         * Las posiciones negativas colocan ambos elementos dentro de los margenes
         * reservados por @page. Modificarlas con cuidado para evitar traslapes.
         */
        header {
            position: fixed;
            top: -58px;
            left: 0;
            right: 0;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            text-align: center;
        }

        /* Tabla que contiene el nombre, codigo, version, pagina y logotipo. */
        .tablaheader {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 10px;
        }

        .tablaheader th {
            border: 1px solid #000;
        }

        /* Encabezados azules utilizados en las secciones del reporte. */
        .encabezadoAzul {
            background-color: #305496;
            color: #fff;
            text-align: center;
            font-size: 8px;
        }

        /* Tabla auxiliar para el registro fotografico y las firmas. */
        .datosgenerales {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .datosgenerales th,
        .datosgenerales td {
            padding: 3px;
            text-align: center;
            vertical-align: bottom;
        }

        /* Genera la linea donde se muestran nombres, valores o firmas. */
        .lineaInferior {
            border-bottom: 1px solid #000;
        }

        /*
         * DATOS GENERALES
         * table-layout: fixed conserva el ancho de las seis columnas aunque
         * alguno de los valores contenga mucho texto.
         */
        .tablaGenerales {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 7px;
        }

        .tablaGenerales th,
        .tablaGenerales td {
            /*padding: 1.5px;*/
            vertical-align: middle;
        }

        /* Etiquetas en español e ingles de la tabla de datos generales. */
        .etiquetaGeneral {
            width: 15%;
            padding-left: 2px;
            font-weight: bold;
            /*line-height: 10px;*/
            text-align: left;
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Variante para etiquetas que deben quedar centradas. */
        .etiquetaGeneralCentrada {
            text-align: center !important;
            vertical-align: middle !important;
        }

        /* Evita que el titulo en español se divida en dos lineas. */
        .titulo-es-nowrap {
            display: block;
            text-align: center;
            white-space: nowrap;
        }

        /* Valores capturados; el borde inferior funciona como renglon visual. */
        .valorGeneral {
            /*height: 13px;*/
            border-bottom: .5px solid #000;
            text-align: center;
            vertical-align: middle;
        }

        /* Titulo principal de la seccion DATOS GENERALES. */
        .tituloGeneralPdf {
            font-weight: bold;
            /*line-height: 11px;*/
            text-align: center !important;
            white-space: nowrap;
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
        /* Medidas de cada uno de los cuatro espacios disponibles por pagina. */
        .foto-container {
            padding: 0;
            /*border: 1px solid #000;
            display: block;*/
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            width: 310px;
            height: auto;
            /*line-height: 0;*/
            position: relative;
        }

        .foto-container.arriba_izquierda {
            text-align: left;
        }

        .foto-container.arriba_derecha {
            text-align: right;
        }

        .foto-container.abajo_izquierda {
            text-align: left;
        }

        .foto-container.abajo_derecha {
            text-align: right;
        }

        /*
         * contain muestra la imagen completa sin deformarla y ajusta el contenedor
         * a su proporción real.
         */
        .foto-container img {
            display: block;
            max-width: 310px;
            max-height: auto;
            object-fit: contain;
            margin: 0 auto;
        }

        .foto-container.arriba_izquierda img,
        .foto-container.abajo_izquierda img {
            margin-left: 0;
            margin-right: auto;
        }

        .foto-container.arriba_derecha img,
        .foto-container.abajo_derecha img {
            margin-left: auto;
            margin-right: 0;
        }

        /*
         * Conserva el espacio necesario para respetar la posicion seleccionada,
         * pero no dibuja el recuadro cuando no existe una fotografia.
         */
        .foto-vacia {
            border: none !important;
            background: #fff;
        }

        /* Texto descriptivo que se presenta debajo de cada fotografia. */
        .comment {
            margin: 0;
            padding: 6px 4px 4px;
            /*border-top: 1px solid #000;*/
            font-size: 8px;
            line-height: 1;
            text-align: center;
            box-sizing: border-box;
            width: 100%;
            max-width: 310px;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        /*.foto-container.arriba_izquierda .comment,
        .foto-container.abajo_izquierda .comment {
            text-align: left;
        }

        .foto-container.arriba_derecha .comment,
        .foto-container.abajo_derecha .comment {
            text-align: right;
        }*/

        /*
         * FOTOGRAFIA DE HOJA COMPLETA
         * Estas medidas se aplican cuando se elige el radio Pagina completa.
         */
        .foto-full {
            width: 100% !important;
            height: 300px !important;
        }

        .foto-full img {
            width: 100% !important;
            height: 272px !important;
            object-fit: contain;
        }

        /* Mantiene juntos los datos generales y sus fotografias en una pagina. */
        .photo-page {
            page-break-inside: avoid;
        }

        /* Centrado de las tablas y celdas que se imprimen en el pie de pagina. */
        footer table {
            margin-right: auto;
            margin-left: auto;
            text-align: center;
        }

        footer th,
        footer td {
            text-align: center;
            vertical-align: middle;
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
                <th style="width:60%">Código<br>Code</td>
                <th style="width:100%">FOR-PIMP-02_B/04</th>
                <th rowspan="3" style="width:80%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Ensayo de Durezas en Soldaduras<br>Test Report on Welding Hardness</th>
                <th>VERSIÓN<br>Version:</td>
                <th>2</th>
            </tr>
            <tr>
                <th>PÁGINA<br>Page:</th>
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
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <td style="width: 260px; height:40px" class="lineaInferior"></td>
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
    /* La hoja fotografica usa la misma etapa guardada por el reporte principal. */
    $etapaFotograficaDureza = ($Datos_Equipo['DUREZA_ETAPA'] ?? 'ANTES') === 'DESPUES'
        ? ['DESPUÉS DEL RELEVADO DE ESFUERZOS', 'AFTER PWHT']
        : ['ANTES DEL RELEVADO DE ESFUERZOS', 'BEFORE PWHT'];

    /*
     * DISTRIBUCION MANUAL DE FOTOGRAFIAS
     * La pagina y la posicion llegan desde los radios de Create/Edit.
     * Se mantienen las posiciones vacias para no mover las demas fotografias.
     */
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
            $paginasFotos[$pagina] = [
                'completa' => null,
                'espacios' => [],
            ];
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
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">NUMERO DE ISOMÉTRICO:</span>No. Isometric:</th>
                <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral" style="white-space: nowrap;">NOMBRE DE LAS PIEZAS:<br>Name of the Pieces:</th>
                <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Nom_Pieza'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">MATERIAL:</span>Material: </th>
                <td class="valorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">PROCEDIMIENTO:<br>Procedure:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">CRITERIO DE EVALUACIÓN:</span>Evaluation Criteria:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">TRAZABILIDAD:</span>Traceability:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
            </tr>
            <tr>
                <th class="etiquetaGeneral">No JUNTA:<br>No. Joint:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['No_Junta'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">TEMPERATURA DE LA PIEZA:</span>Piece Temperature:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Temperatura_pieza'] ?? '' }}</td>
                <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">ESPESOR/CÉDULA:</span>Thickness / Schedule:</th>
                <td class="valorGeneral">{{ $Detalles_Generales['Espesor_cedula'] ?? '' }}</td>
            </tr>
        </tbody>
    </table>

        @if(!$esHojaCompleta)
        <div style="margin-bottom: 6px;"></div>


        <div style="margin-bottom: 6px;"></div>
        @endif

        <table class="datosgenerales">
            <thead class="encabezadoAzul">
                <tr>
                    <th>
                        EVIDENCIA FOTOGRÁFICA {{ $etapaFotograficaDureza[0] }}<br>
                        PHOTOGRAPHIC EVIDENCE {{ $etapaFotograficaDureza[1] }}
                    </th>
                </tr>
            </thead>
        </table>


        <table class="imagenes-reporte" style="width:100%" border="0">
            @if($esHojaCompleta)
                <tr>
                    <td class="foto-container foto-full" colspan="2">
                        <img src="{{ $fotoCompleta['path'] }}">
                        <p class="comment">{{ $fotoCompleta['comment'] }}</p>
                    </td>
                </tr>
            @else
                
                    @foreach([['arriba_izquierda', 'arriba_derecha'],['abajo_izquierda', 'abajo_derecha'],] as $fila)
                        <tr>
                @foreach($fila as $posicion)
                    @php 
                    //$foto = $configuracionFotos['posiciones'][$posicion] ?? null; 
                    //dump($posicion);
                    $foto = $espacios[$posicion] ?? null;
                    @endphp
                    @if($posicion === 'arriba_derecha')
                        <th style="width:5%">
                            <div>
                                &nbsp;
                            </div>
                        </th>
                    @endif
                    <th class="foto-container {{ $posicion }}">
                        <div>
                            @if($foto) 
                                <img src="{{ $espacios[$posicion]['path'] }}" alt="Fotografía">
                            @endif
                        </div>
                        <div class="comment">{{ $espacios[$posicion]['comment'] ?? '' }}</div>
                    </th>
                    @if($posicion === 'abajo_izquierda')
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
    </div>

    @if(!$loop->last)
        <div style="page-break-after: always;"></div>
    @endif
@endforeach
</body>
</html>
