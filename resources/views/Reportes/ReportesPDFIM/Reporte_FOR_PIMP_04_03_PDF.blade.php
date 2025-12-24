<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-02_B/03</title>

    <style>
        @page {
            margin: 1cm 1.2cm 1cm 1.2cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header, footer {
            width: 100%;
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
            color: #ffffff;
            font-size: 8px;
            font-weight: bold;
        }

        .datosgenerales {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
        }

        .celdaGris {
            background-color: #f2f2f2;
        }

        /* Espaciadores compactos */
        br {
            content: "";
            display: block;
            margin: 2px 0;
            line-height: 2px;
        }

        /* Microestructura compacta para forzar una hoja */
        .tabla-micro {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 2px;
            font-size: 9px;
        }

        .cuadro {
            border: 1.5px solid #000;
            height: 110px; /* Reducido para ahorrar espacio */
            position: relative;
            vertical-align: top;
        }

        .texto-arriba {
            position: absolute;
            top: 3px;
            left: 5px;
            font-weight: bold;
            font-size: 7.5px;
        }

        .texto-abajo {
            position: absolute;
            bottom: 3px;
            left: 0;
            right: 0;
            font-size: 7.5px;
            text-align: center;
            font-weight: bold;
        }

        /* Tablas Técnicas */
        .tabla-tecnica {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            margin-top: 3px;
            text-align: center;
        }

        .tabla-tecnica th, .tabla-tecnica td {
            border: 1px solid black;
            height: 11px;
            padding: 1px;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <tr>
            <th style="width: 50%;">FORMATO</th>
            <th style="width: 15%;">Código:</th>
            <th style="width: 20%;">FOR-PIMP-04_/03</th>
            <th rowspan="3" style="width: 15%;">
                <img src="{{ $Logo }}" alt="Logo" style="width:50px;">
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="font-size:9pt;">
                Informe de Caracterizacíon de Materiales Mediante la Técnica<br>
                de Fluorescencia de Rx (XRF)
            </th>
            <th>Versión</th>
            <th>0</th>
        </tr>
        <tr>
            <th>Página</th>
            <th>1 de 1</th>
        </tr>
    </table>
</header>

<table class="datosgenerales">
    <tr class="encabezadoAzul">
        <th colspan="4">DATOS GENERALES</th>
    </tr>
    <tr>
        <th style="width: 20%;">FECHA:</th>
        <td class="lineaInferior"></td>
        <th style="width: 20%;">NO. REPORTE:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>CLIENTE:</th>
        <td class="lineaInferior"></td>
        <th>No. CONTRATO:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>PROYECTO:</th>
        <td colspan="3" class="lineaInferior"></td>
    </tr>
    <tr>
        <th>ORDEN DE TRABAJO:</th>
        <td colspan="3" class="lineaInferior"></td>
    </tr>
    <tr>
        <th>FOLIO:</th>
        <td colspan="3" class="lineaInferior"></td>
    </tr>
    <tr>
        <th>PARTIDA:</th>
        <td colspan="3" class="lineaInferior"></td>
    </tr>
    <tr>
        <th>INSTALACIÓN:</th>
        <td class="lineaInferior"></td>
        <th>No. ISOMÉTRICO:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>NOMBRE DE LA PIEZA:</th>
        <td class="lineaInferior"></td>
        <th>MATERIAL:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>TRAZABILIDAD:</th>
        <td class="lineaInferior"></td>
        <th>PROCEDIMIENTO:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>CRITERIO DE EVALUACIÓN:</th>
        <td class="lineaInferior"></td>
        <th>ACCESORIO:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>TUBERÍA:</th>
        <td class="lineaInferior"></td>
        <th>ESTRUCTURAL:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>OBSERVACIONES Y NOTAS:</th>
        <td colspan="3" class="lineaInferior"></td>
    </tr>
</table>

<br>
<table class="tabla-tecnica">
    <tr class="encabezadoAzul">
        <th colspan="6">ENSAYO DE DUREZA - DATOS DEL EQUIPO</th>
    </tr>
    <tr>
        <th style="width: 10%;" class="celdaGris">MARCA</th>
        <td style="width: 23%;"></td>
        <th style="width: 10%;" class="celdaGris">MODELO</th>
        <td style="width: 23%;"></td>
        <th style="width: 15%;" class="celdaGris">NO. DE SERIE</th>
        <td style="width: 19%;"></td>
    </tr>
</table>

<table class="tabla-tecnica">
    <tr class="encabezadoAzul"><th colspan="6">VALORES DE DUREZA MEDIDOS</th></tr>
    <tr>
        <td style="width: 16%;"></td><td style="width: 16%;"></td><td style="width: 16%;"></td><td style="width: 16%;"></td>
        <th class="celdaGris" style="width: 16%;">PROMEDIO</th><td style="width: 20%;"></td>
    </tr>
</table>

<table class="tabla-tecnica">
    <tr class="encabezadoAzul"><th colspan="5">DATOS OBTENIDOS DEL MATERIAL</th></tr>
    <tr class="celdaGris">
        <th>DESCRIPCIÓN</th><th>DUREZA</th><th>R. TENSIÓN (KSI)</th><th>R. CEDENCIA (KSI)</th><th>GRANO</th>
    </tr>
    <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
</table>

<table class="tabla-tecnica">
    <tr class="encabezadoAzul"><th colspan="6">ANÁLISIS QUÍMICO - DATOS DEL EQUIPO</th></tr>
    <tr>
        <th class="celdaGris">MARCA</th><td></td>
        <th class="celdaGris">MODELO</th><td></td>
        <th class="celdaGris">NO. SERIE</th><td></td>
    </tr>
</table>

<table class="tabla-tecnica">
    <tr class="encabezadoAzul"><th colspan="13">COMPOSICIÓN QUÍMICA (%)</th></tr>
    <tr class="celdaGris" style="font-size: 6px;">
        <th>ELEMENTO</th><th>C</th><th>Mn</th><th>P</th><th>S</th><th>Si</th><th>Ni</th><th>Cr</th><th>Mo</th><th>Cu</th><th>V</th><th>Nb</th><th>C.E.</th>
    </tr>
    <tr><th class="celdaGris">ESPECIF.</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    <tr><th class="celdaGris">OBTENIDO</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
</table>

<footer style="margin-top: 10px;">
    <table style="width: 100%; border-collapse: collapse; font-size: 7px;">
        <thead> 
            <tr><td colspan="9" style="padding-bottom: 5px; font-weight: bold;">4 Firmas</td></tr>
            <tr>
                <th></th>
                <td style="width: 150px; height:25px" class="lineaInferior"></td>
                <td></td>
                <td style="width: 150px; height:25px" class="lineaInferior"></td>
                <td></td>
                <td style="width: 150px; height:25px" class="lineaInferior"></td>
                <td></td>
                <td style="width: 150px; height:25px" class="lineaInferior"></td>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <td><strong>ELABORÓ</strong></td>
                <td></td>
                <td><strong>REVISÓ</strong></td>
                <td></td>
                <td><strong>VALIDÓ</strong></td>
                <td></td>
                <td><strong>CLIENTE / INSPECCIÓN</strong></td>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <td style="font-size: 6.5px;"><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><th></th>
            </tr>
        </thead>                            
    </table>
</footer>

</body>
</html>