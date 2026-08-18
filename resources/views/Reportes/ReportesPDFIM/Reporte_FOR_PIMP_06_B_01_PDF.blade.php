<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-06_B/01</title>

<style>
    @page {
        margin: 
        2.5cm /* superior */
        1.5cm /* derecho */
        1.1cm /* inferior */
        1.5cm; /* izquierdo */
    }

    body {
        font-family: Arial, sans-serif;
        margin-top: 27px;
        padding-top: 0;
        padding-bottom: 0;
    }

    header {
        position: fixed;
        top: -56px;
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

    /* =========================
    ENCABEZADO
    ========================= */

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

    /* =========================
        DATOS DE INSPECCION
       ========================= */

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
        text-align: center;
    }

    /* =========================
        DATOS DE EQUIPOS
       ========================= */

    .tablaEquipos {
        table-layout: fixed;
        height: 42px;
    }

    .tablaEquipos th,
    .tablaEquipos td {
        padding: 3px;
    }

    .celdaGris {
        background-color: #fdfafa;
    }

    .lineaInferior {
        border-bottom: 1px solid black;
    }

    /* =========================
        TABLA PRUEBA
       ========================= */

    .tablaPrueba {
        border-collapse: collapse;
        width: 100%;
        font-size: 8px;
        border: none;
    }

    .tablaPrueba th {
        padding: 0;
        line-height: 9px;
    }

    .tablaPrueba td {
        padding: 6px 3px;
        text-align: center;
        vertical-align: middle;
        border: none;
    }

    .etiquetaPrueba {
        width: 28%;
        font-weight: bold;
        line-height: 11px;
    }

    .valorPrueba {
        width: 18%;
        border-bottom: 1px solid black;
        min-height: 12px;
    }

    .tablaPrueba td.valorPrueba {
        border-bottom: 1px solid black;
    }

    .separadorPrueba {
        width: 8%;
    }

    /* =========================
        DATOS GENERALES
       ========================= */

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

    /* Celda de valor */
    .valorGeneral {
    text-align: center !important;
    vertical-align: bottom !important;
    padding: 0 !important;
    height: auto !important;
}

.valorGeneralConLinea {
    border-bottom: none !important;
    padding: 0 !important;
    vertical-align: bottom !important;
}

/* CONTENEDOR DE LA LINEA */
.lineaValorGeneral {
    width: 100%;
    min-height: 11px;
    height: auto !important;
    border-bottom: .5px solid black;
    padding: 1px 2px !important;
    margin: 0 !important;
    box-sizing: border-box;
}

/* DATO DEL USUARIO */
.textoValorGeneral {
    position: static !important;
    display: block;
    width: 100%;
    text-align: center;
    line-height: 8px;
    font-size: 8.5px;
    white-space: normal !important;
    overflow-wrap: break-word;
    word-wrap: break-word;
}

    .valorGeneralAlto {
        height: 15px;
    }

    /* Simbolo diametro */
    .simboloDiametro {
        font-family: "DejaVu Sans", sans-serif;
    }

    /* =========================
        DISPAROS
       ========================= */

    .paginaDisparos {
        page-break-inside: avoid;
        position: relative;
        top: -12px;
    }

    .tablaDisparos {
        /* Mismo ancho util que el encabezado "RESULTADOS..." para alinear el 2do disparo al borde derecho. */
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        border-spacing: 0;
        table-layout: fixed;
    }

    .celdaDisparo {
        width: 50%;
        padding: 0;
        vertical-align: top;
        box-sizing: border-box;
        padding-bottom: 0.3cm;
    }

    .celdaDisparoIzquierda {
        /* Separa la tarjeta azul/imagenes del disparo izquierdo contra la tarjeta derecha. */
        padding-right: 0.15cm;
    }

    .celdaDisparoDerecha {
        /* Separa la tarjeta azul/imagenes del disparo derecho contra la tarjeta izquierda. */
        padding-left: 0.15cm;
    }

    .tituloDisparo {
        width: 98.2%;
        display: block;
        background-color: #305496;
        color: white;
        border: 1px solid black;
        box-sizing: border-box;
        padding: 2px;
        text-align: center;
        font-size: 7.5px;
        line-height: 8px;
    }

    /* =========================
        IMAGENES DE DISPAROS
       ========================= */

    .espacioImagenDisparo {
    width: 49%;
    padding: 0;
    vertical-align: top;
    box-sizing: border-box;
    border: none;
}

    .espacioImagenDisparoIzquierdo {
        padding: 0 !important;
    }

    .espacioImagenDisparoDerecho {
    padding: 0 !important;
}

.separacionImagenDisparo {
    width: 0.04cm;
    padding: 0 !important;
    border: none !important;
    /* Separacion fina tipo Excel: el borde lo dibuja cada imagen, no una barra negra. */
    background: #fff;
}

.imagenDisparo {
    width: 100%;
    /* Aprovecha el espacio libre antes de la especificacion sin deformar la distribucion de disparos. */
    height: 5.65cm;
    box-sizing: border-box;
    border: none;
    padding: 0;
    margin: 0;
    overflow: hidden;
}

/* Imagen */
.imagenDisparo img {
    display: block;
    width: 100%;
    height: 5.65cm;
    /* Marco directo en la imagen: es lo mas estable en Dompdf para no perder lados. */
    border: 1px solid black !important;
    box-sizing: border-box;
    padding: 2px;
    object-fit: cover;
    margin: 0;
}

    .tablaImagenesDisparo {
    width: 100%;
    /* La separacion interna se controla con una celda central para no desfasar el encabezado azul. */
    border-collapse: collapse;
    border-spacing: 0;
    table-layout: fixed;
    margin: 0;
}

    .marcoImagenDisparo {
        width: 100%;
        height: 5.65cm;
        border-collapse: collapse;
        table-layout: fixed;
        margin: 0;
    }

    .marcoImagenDisparo td {
        border: 1px solid black;
        /* Sin padding: el recorte XRF debe quedar al paño del borde de la celda del disparo. */
        padding: 0;
        vertical-align: middle;
        text-align: center;
        overflow: hidden;
    }

    .marcoImagenDisparo img {
        display: block;
        width: 100%;
        height: 5.65cm;
        object-fit: cover;
        margin: 0;
        padding: 0;
        border: none !important;
    }

    .marcoImagenDisparoTabla img {
        /* La tabla puede crecer segun la norma; se muestra completa para no cortar filas. */
        object-fit: contain;
    }

    /* =========================
        TABLA QUIMICA
       ========================= */

    .espacioTablaQuimica {
        width: 7.85cm;
        height: 4.99cm;
        margin: 0 auto;
    }

    .tablaQuimicaDisparo {
        /* Aqui se controla el ancho completo de la tabla quimica. */
        width: 7.85cm;
        height: 4.99cm;
        margin: 0 auto;
        border-collapse: collapse;
        table-layout: fixed;
        text-align: center;
        font-size: 7px;
    }

    .tablaQuimicaDisparo th,
    .tablaQuimicaDisparo td {
        border: 1px solid black;
        padding: 1px;
        line-height: 6px;
        overflow-wrap: break-word;
    }

    .tablaQuimicaDisparo thead th {
        height: 0.70cm;
        padding: 1px 2px;
        line-height: 7.5px;
        font-weight: bold;
    }

    .sinImagenDisparo {
        color: #777;
        font-size: 8px;
    }

    /* =========================
        OBSERVACIONES
       ========================= */

    .observacionesBox {
        width: 7.85cm;
        margin: 0 auto;
        border-collapse: collapse;
        table-layout: fixed;
        margin-bottom: 4px;
        position: relative;
        top: 0;
    }

    .observacionesBox th,
    .observacionesBox td {
        width: 50%;
        padding: 3px 5px;
        text-align: center;
        font-size: 7.5px;
    }

    .observacionesTitulo {
        vertical-align: middle;
        font-weight: bold;
    }

    .observacionesLineas {
        height: 24px;
        border-bottom: 1px solid black;
        vertical-align: bottom;
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
                <th style="width:100%">FOR-PIMP-06_B/01</th>
                <th rowspan="3" style="width:85%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X (XRF)<br>
                    Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF)</th>
                <th>VERSIÓN<br>Version:</td>
                <th>3</th>
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

<div style="margin-bottom: 2px;"></div>

<table class="tablaGenerales">

    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">
                DATOS GENERALES<br>
                General Data
            </th>
        </tr>
    </thead>

    <tbody>

        {{-- FECHA / No. REPORTE --}}
        <tr>

            <th class="etiquetaGeneral">
                FECHA<br>
                Date:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="2">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Fecha'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                No. REPORTE<br>
                No. Report:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="2">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['No_Reporte'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- CLIENTE / No. CONTRATO --}}
        <tr>

            <th class="etiquetaGeneral">
                CLIENTE<br>
                Client:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="3">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Cliente'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                No. CONTRATO<br>
                No. Contract:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Contrato'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- CONTRATO --}}
        <tr>

            <th class="etiquetaGeneral" style="white-space: nowrap;">
                CONTRATO<br>
                Contract:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Proyecto'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- ORDEN DE TRABAJO --}}
        <tr>

            <th class="etiquetaGeneral" style="white-space: nowrap;">
                ORDEN DE TRABAJO<br>
                Work Order:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- FOLIO --}}
        <tr>

            <th class="etiquetaGeneral">
                FOLIO<br>
                Folio:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Folio'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- PARTIDA --}}
        <tr>

            <th class="etiquetaGeneral">
                PARTIDA<br>
                Lot:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="5">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Partida'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- INSTALACION / NUMERO DE ISOMETRICO --}}
        <tr>

            <th class="etiquetaGeneral">
                INSTALACION<br>
                Location:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="3">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Instalacion'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada"
                style="white-space: nowrap;">
                NUMERO DE ISOMETRICO<br>
                No. Isometric:
            </th>

            <td class="valorGeneral valorGeneralConLinea" colspan="1">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['No_Isometrico'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>


        {{-- NOMBRE DE LA PIEZA / MATERIAL --}}
        <tr>

    <th class="etiquetaGeneral" style="white-space: nowrap;">
        NOMBRE DE LA PIEZA<br>
        Name of the Piece:
    </th>

    <td class="valorGeneral valorGeneralConLinea">
        <div class="lineaValorGeneral">
            <span class="textoValorGeneral">
                {!! str_replace(
                    '⌀',
                    '<span class="simboloDiametro">⌀</span>',
                    e($Detalles_Generales['Nom_Pieza'] ?? '')
                ) !!}
            </span>
        </div>
    </td>

    <th class="etiquetaGeneral etiquetaGeneralCentrada">
        No. JUNTA<br>
        No. Joint:
    </th>

    <td class="valorGeneral valorGeneralConLinea">
        <div class="lineaValorGeneral">
            <span class="textoValorGeneral">
                {{ $Detalles_Generales['No_Junta'] ?? '' }}
            </span>
        </div>
    </td>

    <th class="etiquetaGeneral etiquetaGeneralCentrada">
        MATERIAL<br>
        Material:
    </th>

    <td class="valorGeneral valorGeneralConLinea">
        <div class="lineaValorGeneral">
            <span class="textoValorGeneral">
                {{ $Detalles_Generales['Material'] ?? '' }}
            </span>
        </div>
    </td>

</tr>


        {{-- PROCEDIMIENTO / CRITERIO / TRAZABILIDAD --}}
        <tr>

            <th class="etiquetaGeneral">
                PROCEDIMIENTO<br>
                Procedure:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Procedimiento'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada"
                style="white-space: nowrap;">
                CRITERIO DE EVALUACION<br>
                Evaluation Criteria:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}
                    </span>
                </div>
            </td>

            <th class="etiquetaGeneral etiquetaGeneralCentrada">
                TRAZABILIDAD<br>
                Traceability:
            </th>

            <td class="valorGeneral valorGeneralConLinea">
                <div class="lineaValorGeneral">
                    <span class="textoValorGeneral">
                        {{ $Detalles_Generales['Trazabilidad'] ?? '' }}
                    </span>
                </div>
            </td>

        </tr>

    </tbody>

</table>

<div style="margin-bottom: 2px;"></div>
<table class="datosinspeccion tablaEquipos">
    <colgroup>
        <col style="width: 40%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
    </colgroup>
    <thead class="encabezadoAzul">
        <tr><th colspan="6">DATOS DE EQUIPOS<br>
            Equipment Data</th></tr>
    </thead>

    <tbody>
        <tr class="celdaGris">
            <th>MARCA / Brand</></th>
            <td>{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
            <th>MODELO / Model</th>
            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}</td>
            <th>No. SERIE / Serial Number</th>
            <td>{{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 3px;"></div>

<table class="datosinspeccion tablaEquipos">

    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">
                RESULTADOS DEL ANÁLISIS QUÍMICO DEL ELEMENTO<br>
                Results of the Chemical Analysis of the Element
            </th>
        </tr>
    </thead>

</table>

<div style="margin-bottom: 0px;"></div>

@php
        // Datos de especificacion usados debajo de la tabla quimica para que
        // acompanen a cualquier norma y no se encimen con las firmas.
        $nombreNormaPdf = str_replace(
            ["\u{2212}", "\u{2013}", "\u{2014}"],
            '-',
            (string) ($NormaIM['Nombre_Espe'] ?? '')
        );
        $variableNormaPdf = str_replace(
            ["\u{2212}", "\u{2013}", "\u{2014}"],
            '-',
            (string) ($NormaIM['Variable'] ?? '')
        );
        $ordinalesDisparoPdf = [1 => '1er.', 2 => '2do.', 3 => '3er.'];
        $ordinalesDisparoIngles = [1 => '1st', 2 => '2nd', 3 => '3rd'];
        ksort($Disparos);
        $distribucionDisparosPdf = [[1, 2], [3, 'tabla_quimica']];
@endphp

<div class="paginaDisparos">
        <table class="tablaDisparos">
            @foreach ($distribucionDisparosPdf as $disparosFila)
                <tr>
                    @foreach ($disparosFila as $celdaDisparo)
                        @if ($celdaDisparo === 'tabla_quimica')
                            <td class="celdaDisparo {{ $loop->first ? 'celdaDisparoIzquierda' : 'celdaDisparoDerecha' }}">
                                @if (!empty($NormaIM['Tabla']))
                                    <table class="tablaQuimicaDisparo">
                                        <colgroup>
                                            <col style="width: 28%;">
                                            <col style="width: 36%;">
                                            <col style="width: 36%;">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>Elementos Quimicos<br>Chemical elements</th>
                                                <th>Promedio de Valores Obtenidos en la Pieza Analizada<br>Average Values Obtained in the Analyzed Piece</th>
                                                <th>Composicion Quimica Teorica<br>Theoretical Chemical Composition</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($NormaIM['Tabla'] as $filaNorma)
                                                <tr>
                                                    <th>{{ $filaNorma['Elemento'] ?? '' }}</th>
                                                    <td>{{ $filaNorma['Promedio'] ?? '' }}</td>
                                                    <td>{{ $filaNorma['Composicion'] ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="espacioTablaQuimica"></div>
                                @endif
                                <table class="observacionesBox">
                                    <tr>
                                        <th class="observacionesTitulo">ESPECIFICACION APROX. DEL MATERIAL:<br>
                                            Approx. Material Specification:</th>
                                        <td class="observacionesLineas">
                                            {{ $nombreNormaPdf }}
                                            @if ($variableNormaPdf !== '')
                                                <br>{{ $variableNormaPdf }}
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        @elseif (!empty($Disparos[$celdaDisparo]))
                            <td class="celdaDisparo {{ $loop->first ? 'celdaDisparoIzquierda' : 'celdaDisparoDerecha' }}">
                                <div class="tituloDisparo">
                                    {{ $ordinalesDisparoPdf[$celdaDisparo] }} DISPARO
                                    ({{ $ordinalesDisparoIngles[$celdaDisparo] }} shot)<br>
                                    VALORES OBTENIDOS EN LA PIEZA ANALIZADA<br>
                                    Values obtained in the analyzed piece
                                </div><table class="tablaImagenesDisparo">
                                    <colgroup>
                                        <col style="width: 49.75%;">
                                        <col style="width: 0.04cm;">
                                        <col style="width: 49.75%;">
                                    </colgroup>
                                    <tr>
                                        @foreach ($Disparos[$celdaDisparo] as $indiceImagen => $imagen)
                                            <td class="espacioImagenDisparo {{ $indiceImagen === 0 ? 'espacioImagenDisparoIzquierdo' : 'espacioImagenDisparoDerecho' }}">
                                                {{-- Marco en tabla: Dompdf conserva mejor los cuatro bordes que con div/img. --}}
                                                <table class="marcoImagenDisparo {{ $indiceImagen === 0 ? 'marcoImagenDisparoTabla' : '' }}">
                                                    <tr>
                                                        <td>
                                                            <img src="{{ $imagen }}" alt="Imagen {{ $indiceImagen + 1 }} del disparo {{ $celdaDisparo }}">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            @if ($indiceImagen === 0 && count($Disparos[$celdaDisparo]) > 1)
                                                {{-- Separacion visual entre ambas evidencias sin modificar el ancho total del disparo. --}}
                                                <td class="separacionImagenDisparo"></td>
                                            @endif
                                        @endforeach
                                    </tr>
                                </table>
                            </td>
                        @else
                            <td class="celdaDisparo {{ $loop->first ? 'celdaDisparoIzquierda' : 'celdaDisparoDerecha' }}"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
</div>
</body>
</html>
