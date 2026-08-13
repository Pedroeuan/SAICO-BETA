<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-05_B/01</title>

    <style>
        @page {
            margin:
            2.5cm  /* Superior */
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
            top: -60px;
            left: 0;
            right: 0;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -70px;
            left: 0;
            right: 0;
            text-align: center;
        }

        /* Tabla que contiene el nombre, codigo, version, pagina y logotipo. */
        .tablaheader {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 9.5px;
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
            font-size: 8px;
        }

        .tablaGenerales th,
        .tablaGenerales td {
            padding: 1.5px;
            vertical-align: middle;
        }

        /* Etiquetas en español e ingles de la tabla de datos generales. */
        .etiquetaGeneral {
            width: 15%;
            padding-left: 2px;
            font-weight: bold;
            line-height: 10px;
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
            height: 13px;
            border-bottom: 1px solid #000;
            text-align: center;
            vertical-align: middle;
        }

        .textoValorGeneral {
            position: relative;
            top: .5px; /* BAJA SOLO EL TEXTO HACIA LA LÍNEA */
        }
        /* Titulo principal de la seccion DATOS GENERALES. */
        .tituloGeneralPdf {
            font-weight: bold;
            line-height: 11px;
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
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
            width: 220px;
            height: auto;
            line-height: 0;
        }

        /*
         * contain muestra la imagen completa sin deformarla y ajusta el contenedor
         * a su proporción real.
         */
        .foto-container img {
            display: block;
            max-width: 220px;
            max-height: auto;
            object-fit: contain;
            margin: 0 auto;
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
            border-top: 1px solid #000;
            font-size: 8px;
            line-height: 1;
            text-align: center;
            box-sizing: border-box;
        }

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
            position: relative;
            top: -5px; /* SUBE DATOS GENERALES Y TODO EL BLOQUE */
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
        /*   */
        .datosgenerales,
        .datosinspeccion {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .datosinspeccion th,
        .datosinspeccion td {
            border: .6px solid black;
            padding: 3px;
        }
        .lineacentro {
            text-align: center;
        }

        .observacionesBox {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
            position: relative;
            top: -30px;
        }

        .observacionesBox td {
            padding: 3px 5px;
            text-align: left;
            vertical-align: top;
            font-size: 8px;
        }

        .observacionesTitulo {
            font-weight: bold;
            line-height: 10px;
            padding-bottom: 1px;
        }

        .observacionesLineas {
            height: 38px;
            background-image: linear-gradient(to bottom, transparent 11px, black 11px, black 12px, transparent 12px);
            background-size: 100% 12px;
            background-repeat: repeat-y;
        }

        .composicionTitulo {
            width: 100%;
            border-collapse: collapse;
            margin-top: 3px;
        }

        .composicionTitulo th {
            padding: 3px;
            background-color: #305496;
            color: #fff;
            text-align: center;
            font-size: 8px;
            line-height: 9px;
        }

        .composicionLayout {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20;
        }

        .composicionLayout > tbody > tr > td {
            padding: 0;
            vertical-align: top;
        }

        .columnaResultados {
            width: 50%;
            padding-right: 7px !important;
        }

        .columnaEvidencia {
            width: 50%;
            padding-left: 7px !important;
        }

        .tablaComposicion {
            width: 85%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 6.2px;
            margin-top: 35px;
            margin-left: auto;
            margin-right: auto;
        }

        .tablaComposicion th,
        .tablaComposicion td {
            border: .6px solid #000;
            height: 10px;
            padding: 0 2px;
            text-align: center;
            vertical-align: middle;
            line-height: 8px;
        }

        .tablaComposicion thead th {
            height: 32px;
            font-size: 6.2px;
            line-height: 6px;
        }

        .referenciaMaterial {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 1px;
            font-size: 6px;
        }

        .referenciaMaterial th,
        .referenciaMaterial td {
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }

        .valorReferencia {
            height: 14px;
            font-weight: normal;
            width: 38%;
            text-align: center !important;
            padding: 0 2px !important;
        }

        .valorReferenciaCaja {
            display: block;
            width: 100%;
            min-height: 12px;
            border-bottom: 1px solid #000;
            box-sizing: border-box;
            padding: 1px 2px;
            text-align: center;
            position: relative;
            left: -75px;
            top: -15px;
        }

        .etiquetaReferencia {
            width: 62%;
            text-align: center !important;
            line-height: 8px;
        }

        .etiquetaReferenciaContenido {
            position: relative;
            left: -75px;
            text-align: center;
            top: -15px;
            font-size: 7px;
        }

        .tituloValoresXrf {
            border: none;
            padding: 0 !important;
        }

        .tituloValoresXrfCaja {
            width: auto;
            border: 1px solid #000;
            box-sizing: border-box;
            padding: 2px;
            font-weight: bold;
            line-height: 8px;
            text-align: center;
        }

        .capturaXrfOficial {
            display: block;
            width: 100%;
            max-height: 235px;
            object-fit: contain;
            object-position: center top;
            margin: 0;
        }

        .capturaXrfVacia {
            height: 210px;
            border: .6px solid #000;
        }
        .firmasArriba {
            position: relative;
            top: -15px;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width:390%">FORMATO<br>Format</th>
                <th rowspan="3" style="width:70%">
                    @if(!empty($QR_PDF))
                        <img src="{{ $QR_PDF }}" alt="QR de documentos" style="width:55px; height:55px; display:block; margin:auto; padding:0;">
                    @endif
                </th>
                <th style="width:60%">Código<br>Code</td>
                <th style="width:90%">FOR-PIMP-05_B/01</th>
                <th rowspan="3" style="width:85%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis Químico Mediante la Técnica de Espectrometría de Emisión Óptica (OES)<br>
                    Chemical Analysis Report Using the Optical Emission Spectrometry Technique (OES)</th>
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
        <table class="observacionesBox">
            <tr>
                <td>
                    <div class="observacionesTitulo">OBSERVACIONES O CONCLUSIONES:<br>Remarks:</div>
                    <div class="observacionesLineas">{{ $Datos_Equipo['Observaciones'] ?? '' }}</div>
                </td>
            </tr>
        </table>

        <div class="firmasArriba">
            @include('Reportes.partials.firmas_im_pdf')
        </div>
        <table class="datosgenerales" style="display: none;">
            <thead>
                @if( $numFirmas == 2)
                <!-- 2 Firmas -->
                    <tr>
                        <td style="width: 30px;"></td>
                        <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                        <td style="width: 30px;"></td>
                        <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
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
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                    </tr>
                                                        
                    <tr>
                        <th></th>
                        <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                    </tr>

                    <tr>
                        <th></th>
                        <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                    </tr>
                @elseif( $numFirmas == 3)
                <!-- 3 Firmas -->
                    <tr>
                        <td style="width: 20px;"></td>
                        <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                        <td style="width: 20px;"></td>
                        <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                        <td style="width: 20px;"></td>
                        <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
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
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</strong></td>
                    </tr>
                                                        
                    <tr>
                        <th></th>
                        <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</strong></td>
                    </tr>

                    <tr>
                        <th></th>
                        <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                    </tr>
                @elseif( $numFirmas == 4)
                <!-- 4 Firmas -->
                    <tr>
                        <td style="width: 15px;"></td>
                        <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                        <td style="width: 15px;"></td>
                        <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                        <td style="width: 15px;"></td>
                        <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                        <td style="width: 15px;"></td>
                        <th>{{ $Firmas_Reportes['Vobo3'] }}</th>
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
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] }}</strong></td>
                        <th></th>
                    </tr>
                                                        
                    <tr>
                        <th></th>
                        <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] }}</strong></td>
                        <th></th>
                    </tr>

                    <tr>
                        <th></th>
                        <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                        <td></td>
                        <td><strong>{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] }}</strong></td>
                        <th></th>
                    </tr>
                @endif
            </thead>                            
        </table>
</footer>

            {{-- ================= DATOS GENERALES ================= --}}
<div class="photo-page" style="position: relative; top: -1px;">

<div class="photo-page">
    <table class="tablaGenerales">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6" class="tituloGeneralPdf">
                DATOS GENERALES<br>General Data
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th class="etiquetaGeneral">
                FECHA:<br>Date
            </th>

            <td class="valorGeneral" colspan="2">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Fecha'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">No. REPORTE:</span>
                No. Report:
            </th>

            <td class="valorGeneral" colspan="2">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['No_Reporte'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral">
                CLIENTE:<br>Client:
            </th>

            <td class="valorGeneral" colspan="3">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Cliente'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">No. CONTRATO:</span>
                No. Contract:
            </th>

            <td class="valorGeneral">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Contrato'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral">
                PROYECTO:<br>Project:
            </th>

            <td class="valorGeneral" colspan="5">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Proyecto'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">
                ORDEN DE TRABAJO:<br>Work Order:
            </th>

            <td class="valorGeneral" colspan="5">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral">
                FOLIO:<br>Folio:
            </th>

            <td class="valorGeneral" colspan="5">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Folio'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral">
                PARTIDA:<br>Lot:
            </th>

            <td class="valorGeneral" colspan="5">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Partida'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral">
                INSTALACIÓN:<br>Location:
            </th>

            <td class="valorGeneral" colspan="2">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Instalacion'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">No. ISOMÉTRICO:</span>
                No. Isometric:
            </th>

            <td class="valorGeneral" colspan="2">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['No_Isometrico'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">
                NOMBRE DE LAS PIEZAS:<br>Name of the Piece:
            </th>

            <td class="valorGeneral">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Nombre_Pieza'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">MATERIAL:</span>
                Material:
            </th>

            <td class="valorGeneral">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Material'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">TRAZABILIDAD:</span>
                Traceability:
            </th>

            <td class="valorGeneral">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Trazabilidad'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral">
                PROCEDIMIENTO:<br>Procedure:
            </th>

            <td class="valorGeneral" colspan="2">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Procedimiento'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">CRITERIO DE EVALUACIÓN:</span>
                Evaluation Criterion:
            </th>

            <td class="valorGeneral" colspan="2">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}
                </span>
            </td>
        </tr>

        <tr>
            <th class="etiquetaGeneral">
                ACCESORIO:<br>Fittings:
            </th>

            <td class="valorGeneral">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Accesorio'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">TUBERÍA</span>
                Piping:
            </th>

            <td class="valorGeneral">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Tuberia'] ?? '' }}
                </span>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                <span class="titulo-es-nowrap">ESTRUCTURAL:</span>
                Structural:
            </th>

            <td class="valorGeneral">
                <span class="textoValorGeneral">
                    {{ $Detalles_Generales['Estructural'] ?? '' }}
                </span>
            </td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 3px;"></div>
{{-- ================= DATOS DEL EQUIPO ================= --}}
<table class="datosinspeccion">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">
                DATOS DEL EQUIPO<br>Equipment Data
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th style="width:12%;">
                MARCA:<br>Brand
            </th>
            <td class="lineacentro" style="width:30%;">
                {{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}
            </td>
            <th class="lineacentro" style="width:13%;">
                MODELO:<br>Model
            </th>
            <td class="lineacentro" style="width:20%;">
                {{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}
            </td>
            <th style="width:13%;">
                NO. DE SERIE:<br>Serial Number
            </th>
            <td class="lineacentro" style="width:24%;">
                {{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}
            </td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 2px;"></div>

<table class="composicionTitulo">
    <tr>
        <th>COMPOSICIÓN QUÍMICA DE LA PIEZA<br>Chemical Composition of the Piece</th>
    </tr>
</table>

<table class="composicionLayout">
    <tbody>
        <tr>
            <td class="columnaResultados">
                <table class="tablaComposicion">
                    <thead>
                        <tr>
                            <th style="width:25%;">ELEMENTO QUÍMICO<br>Chemical Elements</th>
                            <th style="width:37.5%;">PROMEDIOS DE LA PIEZA ANALIZADA<br>Average of the analyzed Piece</th>
                            <th style="width:37.5%;">COMPOSICIÓN QUÍMICA TEÓRICA<br>Theoretical Chemical Composition</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($NormaIM['Tabla'] ?? []) as $filaNorma)
                            <tr>
                                <th>{{ $filaNorma['Elemento'] ?? '' }}</th>
                                <td>{{ $filaNorma['Promedio'] ?? '' }}</td>
                                <td>{{ $filaNorma['Composicion'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td class="columnaEvidencia">
                <table class="referenciaMaterial">
                    <tr>
                        <th class="etiquetaReferencia">
                            <div class="etiquetaReferenciaContenido">NORMA DE REFERENCIA O ESPECIFICACIÓN APROXIMADA DEL MATERIAL:<br>Reference standard or approximate material specification:</div>
                        </th>
                        <td class="valorReferencia">
                            <div class="valorReferenciaCaja">{{ $NormaIM['Nombre_Espe'] ?? ($NormaIM['Variable'] ?? '') }}</div>
                        </td>
                    </tr>
                    <tr><td style="height:2px;"></td></tr>
                    <tr>
                        <th class="tituloValoresXrf">
                            <div class="tituloValoresXrfCaja">
                                VALORES OBTENIDOS DE LA PIEZA ANALIZADA<br>
                                Values Obtained from the Analyzed Piece
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td style="padding:1px 0 0;">
                            @if (!empty($CapturaXrf))
                                <img class="capturaXrfOficial" src="{{ $CapturaXrf }}" alt="Valores obtenidos del análisis XRF">
                            @else
                                <div class="capturaXrfVacia"></div>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

</body>
</html>
