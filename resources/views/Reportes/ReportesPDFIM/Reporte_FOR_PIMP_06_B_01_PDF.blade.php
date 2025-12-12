<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-07_B/01</title>

    <style>
        @page {
            margin: 3cm 1.2cm 2.1cm 2.2cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-bottom: 60px;
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
            color: #fff;
            font-size: 8px;
        }

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

        .celdaGris {
            background-color: #DBDBDB;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO</th>
                <th style="width: 70%;">Código:</th>
                <th style="width: 100%;">FOR-PIMP-06_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2"> Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X <br>(XRF) Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF)</th>
                <th>Versión</th>
                <th>3</th>
            </tr>
            <tr>
                <th>Página</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<br>

<table class="datosgenerales">
    <thead class="encabezadoAzul">
        <tr><th colspan="4">DATOS GENERALES<br>General Data</th></tr>
    </thead>

    <tbody>
        <tr>
            <th style="width: 12%;">FECHA:<br>Date</th>
            <td class="lineaInferior"></td>
            <th style="width: 15%;">NO. REPORTE:<br>No.Report:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>CLIENTE:<br>Client:</th>
            <td class="lineaInferior"></td>
            <th>No.CONTRATO:<br>No.Contract:</th>
            <td classa="lineaInferior"></td>
        </tr>
        <tr>
            <th>CONTRATO:<br>Contract:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>ORDEN DE TRABAJO:<br> work Orden:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>FOLIO:<br>Folio:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>PARTIDA:<br>Lot:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>INSTALACIÓN<br>Location:</th>
            <td class="lineaInferior"></td>
            <th>NUMERO DE ISOMÉTRICO:<br>No.Isometric:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>NOMBRE DE LA PIEZA:<br>Name of the Piece:</th>
            <td class="lineaInferior"></td>
            <th>MATERIAL:<br>Material:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>PROCEDIMIENTO:<br>Procedure:</th>
            <td class="lineaInferior"></td>
            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:<br>Evaluation Criteria:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>TRAZABILIDAD:<br>Traceability:</th>
            <td class="lineaInferior"></td>
            <th style="width: 160px;">No.JUNTA:<br>No.Join:</th>
            <td class="lineaInferior"></td>
        </tr>
    </tbody>
</table>

<table></table>
<Table></table>
<table></table>

<!-- DATOS DEL EQUIPO -->
<table class="datosinspeccion">
    <tr class="encabezadoAzul">
        <th colspan="6">DATOS DEL EQUIPO</th>
    </tr>

    <tr class="celdaGris">
        <th>MARCA:<br>Brand:</th>
        <td></td>
        <th>MODELO:<br>Model:</th>
        <td></td>
        <th>NÚMERO DE SERIE:<br>Serial Number:</th>
        <td></td>
    </tr>
</table>

<head>
<meta charset="UTF-8">
<title>PDF Análisis Químico</title>
<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
    }
    .main-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    .main-table th {
        background: #1e5288;
        color: white;
        text-align: center;
        padding: 6px;
        border: 1px solid #000;
        font-size: 13px;
    }
    .main-table td {
        border: 1px solid #000;
        height: 140px;
        padding: 5px;
    }

    .sub-table {
        width: 100%;
        border-collapse: collapse;
    }
    .sub-table th {
        background: #1e5288;
        color: white;
        padding: 4px;
        border: 1px solid #000;
        font-size: 11px;
    }
    .sub-table td {
        border: 1px solid #000;
        padding: 4px;
        height: 16px;
        font-size: 11px;
    }

</style>
</head>
<body>

<h3 style="text-align:center; margin-bottom:5px;">RESULTADOS DEL ANÁLISIS QUÍMICO DEL ELEMENTO<br>Results of the Chemical Analysis of the Element</h3>

<table class="main-table">
    <tr>
        <th colspan="2">1er. DISPARO<br>(1st shot)<br>VALORES OBTENIDOS EN LA PIEZA ANALIZADA</th>
        <th colspan="2">2do. DISPARO<br>(2nd shot)<br>VALORES OBTENIDOS EN LA PIEZA ANALIZADA</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <th colspan="2">3er. DISPARO<br>(3rd shot)<br>VALORES OBTENIDOS EN LA PIEZA ANALIZADA</th>
        <th colspan="2">&nbsp;</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td colspan="2" rowspan="2" style="padding:0;">
            <table class="sub-table">
                <tr>
                    <th>Elementos Químicos<br>Chemical elements</th>
                    <th>Promedio<br>Average</th>
                    <th>% Composición<br>Chemical Composition</th>
                </tr>
                <tr><td>% Mn</td><td></td><td></td></tr>
                <tr><td>% Si</td><td></td><td></td></tr>
                <tr><td>% Cr</td><td></td><td></td></tr>
                <tr><td>% Ni</td><td></td><td></td></tr>
                <tr><td>% Al</td><td></td><td></td></tr>
                <tr><td>% Cu</td><td></td><td></td></tr>
                <tr><td>% V</td><td></td><td></td></tr>
                <tr><td>% Mo</td><td></td><td></td></tr>
                <tr><td>% P</td><td></td><td></td></tr>
                <tr><td>% S</td><td></td><td></td></tr>
                <tr><td>% Fe</td><td></td><td></td></tr>
                <tr><td>% Mg</td><td></td><td></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>

</body>
</html>
