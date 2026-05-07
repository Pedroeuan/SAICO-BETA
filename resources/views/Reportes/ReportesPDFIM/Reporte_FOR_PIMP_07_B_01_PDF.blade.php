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
                <th style="width: 100%;">FOR-PIMP-07_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Relevado de Esfuerzos / Stress Relief Report</th>
                <th>Versión</th>
                <th>1</th>
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
        <tr><th colspan="4">DATOS GENERALES</th></tr>
    </thead>

    <tbody>
        <tr>
            <th style="width: 12%;">FECHA:</th>
            <td class="lineaInferior"></td>
            <th style="width: 15%;">NO. REPORTE:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>CLIENTE:</th>
            <td class="lineaInferior"></td>
            <th>CONTRATO:</th>
            <td classa="lineaInferior"></td>
        </tr>
        <tr>
            <th>PROYECTO:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>ORDEN DE TRABAJO:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>FOLIO:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>PARTIDA:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>LUGAR:</th>
            <td class="lineaInferior"></td>
            <th>ISOMETRICO/PLANO:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>PIEZA:</th>
            <td class="lineaInferior"></td>
            <th>MATERIAL:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>PROCEDIMIENTO:</th>
            <td class="lineaInferior"></td>
            <th style="width: 160px;">CÓDIGO APLICABLE:</th>
            <td class="lineaInferior"></td>
        </tr>
    </tbody>
</table>

<br>

<table class="datosinspeccion">
    <thead class="encabezadoAzul">
        <tr><th colspan="7">DATOS DE EQUIPOS / EQUIPMENT DATA</th></tr>
    </thead>

    <tbody>
        <tr class="celdaGris">
            <th>EQUIPO/EQUIPMENT</th>
            <th>MARCA/BRAND</th>
            <th>MODELO/MODEL</th>
            <th>No. SERIE/SERIAL NUMBER</th>
        </tr>
        <tr>
            <th class="celdaGris">MAQUINA DE RELEVADO / STRESS RELIEF MACHINE:</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <th class="celdaGris">GRAFICADOR / GRAPHIER:</th>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>

<br>

<table class="datosinspeccion">
    <thead class="encabezadoAzul">
        <tr><th colspan="7">DATOS DE LA INSPECCIÓN</th></tr>
    </thead>

    <tbody>
        <tr class="celdaGris">
            <th></th><th></th>
            <th>TEMPERATURA INICIAL (°F)</th>
            <th></th>
            <th>HORA INICIAL DE PRUEBA</th>
            <th></th><th></th>
        </tr>

        <tr class="celdaGris">
            <th></th><th></th>
            <th>VEL. DE CALENTAMIENTO (°F/hr)</th>
            <th></th>
            <th>HORA FINAL DE PRUEBA</th>
            <th></th><th></th>
        </tr>

        <tr class="celdaGris">
            <th></th><th></th>
            <th>TEMP. SOSTENIMIENTO (°F)</th>
            <th></th>
            <th>DÍA INICIO DE PRUEBA</th>
            <th></th><th></th>
        </tr>

        <tr class="celdaGris">
            <th></th><th></th>
            <th>TIEMPO DE SOSTENIMIENTO (MIN)</th>
            <th></th>
            <th>DÍA FINAL DE PRUEBA</th>
            <th></th><th></th>
        </tr>

        <tr class="celdaGris">
            <th></th><th></th>
            <th>VEL. DE ENFRIAMIENTO (°F/hr)</th>
            <th></th>
            <th>No. GRÁFICA</th>
            <td></td><td></td>
        </tr>
    </tbody>
</table>

<br>

<table class="datosgenerales" style="margin-top: 25px;">
    <tr>
        <th>OBSERVACIONES:</th>
        <td class="lineaInferior" style="width: 606.5px;"></td>
    </tr>
</table>

<table>
    <thead>
        <tr>
            <td style="width: 15px;"></td><th></th>
            <td style="width: 15px;"></td><th></th>
            <td style="width: 15px;"></td><th></th>
            <td style="width: 15px;"></td><th></th>
            <td style="width: 15px;"></td>
        </tr>

        <tr>
            <th></th>
            <td class="lineaInferior" style="width: 150px; height:40px;"></td>
            <td></td>
            <td class="lineaInferior" style="width: 150px; height:40px;"></td>
            <td></td>
            <td class="lineaInferior" style="width: 150px; height:40px;"></td>
            <td></td>
            <td class="lineaInferior" style="width: 150px; height:40px;"></td>
            <th></th>
        </tr>

        <tr>
            <th></th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><th></th>
        </tr>

        <tr>
            <th></th>
            <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
            <td></td><td></td><td></td><td></td><td></td><td></td>
            <th></th>
        </tr>
    </thead>
</table>

<footer></footer>

</body>
</html>
