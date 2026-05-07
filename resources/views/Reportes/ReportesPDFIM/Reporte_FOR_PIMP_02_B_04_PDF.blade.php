<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-02_B/03</title>

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
            color: #ffffff;
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
                            <th style="width: 100%;">FOR-PIMP-02_B/04</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;"></th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">  Informe de Ensayo de Durezas en Soldaduras Test Report on Welding Hardness</th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <br>

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
                        <th>PROCEDIMIENTO:</th>
                        <td class="lineaInferior"></td>
                        <th>TRAZABILIDAD:</th>
                        <td class="lineaInferior"></td>
                    </tr>
                </table>

                <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Durezas en Soldaduras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }

        .titulo {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .tabla th, .tabla td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .encabezado {
            background-color: #305496;
            color: white;
            font-weight: bold;
        }

        .subtitulo {
            background-color: #DBDBDB;
            font-weight: bold;
        }

        .observaciones {
            border: 1px solid #000;
            padding: 6px;
            height: 60px;
        }

        .firmas {
            margin-top: 30px;
            text-align: center;
        }

        .firmas td {
            padding: 20px;
        }

        .zona-diagrama {
            text-align: center;
            margin: 20px 0;
        }

        .horarios td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .horarios th {
            background-color: #DBDBDB;
            border: 1px solid #000;
            padding: 4px;
        }
    </style>
</head>
<body>

<table> </table>
<table> </table>

<style>
    table.formato-wps {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 12px;
        text-align: center;
    }

    table.formato-wps th,
    table.formato-wps td {
        border: 1px solid #000;
        padding: 4px 3px;
        vertical-align: middle;
    }

    .header-azul {
        background: #1F4E79;
        color: white;
        font-weight: bold;
    }

    .texto-izq {
        text-align: left !important;
        padding-left: 6px;
    }

    .celda-diagrama {
        height: 120px;
    }
</style>

<table class="formato-wps">
    <tr>
        <!-- CELDA GRANDE IZQUIERDA (6 columnas × 2 filas) -->
        <td colspan="6" rowspan="2" class="celda-diagrama">
            <img src="{{ asset('images/diagrama.png') }}" 
                alt="Diagrama Soldadura"
                style="max-height:110px;">
        </td>

        <!-- ENCABEZADO AZUL DERECHA (2 columnas) -->
        <td colspan="2" class="header-azul">
            DATOS DEL EQUIPO<br><small>Equipment Data</small>
        </td>
    </tr>

    <tr>
        <!-- FILA DE MÉTODO -->
        <td class="texto-izq" style="width: 12%;">MÉTODO:<br><small>Method:</small></td>
        <td></td>
    </tr>

    <!-- ENCABEZADOS PRINCIPALES -->
    <tr>
        <th style="width:18%;">
            VALORES PROMEDIO DE DUREZAS:<br>
            <small>Average Hardness Values</small>
        </th>

        <th style="width:12%;">METAL BASE<br><small>Base Metal (A)</small></th>
        <th style="width:12%;">ZAC<br><small>HAZ (B)</small></th>
        <th style="width:12%;">SOLDADURA<br><small>Welding (C)</small></th>
        <th style="width:12%;">ZAC<br><small>HAZ (B1)</small></th>
        <th style="width:12%;">METAL BASE<br><small>Base Metal (B)</small></th>

        <th style="width:10%;">MARCA:<br><small>Brand:</small></th>
        <td></td>
    </tr>

    <!-- BEFORE PWHT -->
    <tr>
        <td class="texto-izq">
            ANTES DEL RELEVADO DE ESFUERZOS (HB):<br>
            <small>Before PWHT (HB)</small>
        </td>

        <td></td><td></td><td></td><td></td><td></td>

        <td class="texto-izq">MODELO:<br><small>Model:</small></td>
        <td></td>
    </tr>

    <!-- AFTER PWHT -->
    <tr>
        <td class="texto-izq">
            POSTERIOR AL RELEVADO DE ESFUERZOS (HB):<br>
            <small>After PWHT (HB)</small>
        </td>

        <td></td><td></td><td></td><td></td><td></td>

        <td class="texto-izq">NO. DE SERIE:<br><small>Serial Number:</small></td>
        <td></td>
    </tr>
</table>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla de Dureza por Horario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .tabla th, .tabla td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        .encabezado {
            background-color: #305496;
            color: white;
            font-weight: bold;
        }

        .subtitulo {
            background-color: #DBDBDB;
            font-weight: bold;
        }

        .observaciones {
            border: 1px solid #000;
            padding: 6px;
            height: 60px;
        }
    </style>
</head>
<body>

<h3 style="text-align:center;">DATOS DE LA JUNTA / JOIN DATA</h3>

<table class="tabla">
    <tr class="encabezado">
        <th>DESCRIPCIÓN / DESCRIPTION</th>
        <th>HORARIOS TÉCNICOS / TECHNICAL SCHEDULES</th>
        <th>METAL BASE (A)</th>
        <th>ZAC HAZ (B)</th>
        <th>SOLDADURA / WELD (C)</th>
        <th>ZAC HAZ (B1)</th>
        <th>METAL BASE (A1)</th>
    </tr>
    <tr>
        <td rowspan="4">{{ $junta->descripcion ?? '' }}</td>
        <td>12:00</td>
        <td>{{ $junta->a_12 ?? '' }}</td>
        <td>{{ $junta->b_12 ?? '' }}</td>
        <td>{{ $junta->c_12 ?? '' }}</td>
        <td>{{ $junta->b1_12 ?? '' }}</td>
        <td>{{ $junta->a1_12 ?? '' }}</td>
    </tr>
    <tr>
        <td>03:00</td>
        <td>{{ $junta->a_03 ?? '' }}</td>
        <td>{{ $junta->b_03 ?? '' }}</td>
        <td>{{ $junta->c_03 ?? '' }}</td>
        <td>{{ $junta->b1_03 ?? '' }}</td>
        <td>{{ $junta->a1_03 ?? '' }}</td>
    </tr>
    <tr>
        <td>06:00</td>
        <td>{{ $junta->a_06 ?? '' }}</td>
        <td>{{ $junta->b_06 ?? '' }}</td>
        <td>{{ $junta->c_06 ?? '' }}</td>
        <td>{{ $junta->b1_06 ?? '' }}</td>
        <td>{{ $junta->a1_06 ?? '' }}</td>
    </tr>
    <tr>
        <td>09:00</td>
        <td>{{ $junta->a_09 ?? '' }}</td>
        <td>{{ $junta->b_09 ?? '' }}</td>
        <td>{{ $junta->c_09 ?? '' }}</td>
        <td>{{ $junta->b1_09 ?? '' }}</td>
        <td>{{ $junta->a1_09 ?? '' }}</td>
    </tr>
</table>

<!-- OBSERVACIONES -->
<div class >OBSERVACIONES / REMARKS</div>
<div class="observaciones"></div>

<!-- FIRMAS -->
<table class="firmas" width="100%">
    <tr>
        <td>ELABORÓ</td>
        <td>APROBÓ</td>
        <td>CLIENTE</td>
    </tr>
    <tr>
        <td>________________________</td>
        <td>________________________</td>
        <td>________________________</td>
    </tr>
</table>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-02_B/03</title>

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
            color: #ffffff;
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
                            <th style="width: 100%;">FOR-PIMP-02_B/04</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;"></th>
                        </tr>
                    </thead>


                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">  Informe de Ensayo de Durezas en Soldaduras Test Report on Welding Hardness</th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <br>

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
                        <th>PROCEDIMIENTO:</th>
                        <td class="lineaInferior"></td>
                        <th>TRAZABILIDAD:</th>
                        <td class="lineaInferior"></td>
                    </tr>
                </table>

                </body>
</html>