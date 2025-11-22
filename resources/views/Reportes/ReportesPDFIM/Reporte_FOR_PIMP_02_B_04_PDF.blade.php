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

<div class="titulo">Informe de Ensayo de Durezas en Soldaduras / Welding Hardness Test Report</div>

<!-- DATOS DEL EQUIPO -->
<table class="tabla">
    <tr class="encabezado"><th colspan="4">DATOS DEL EQUIPO / EQUIPMENT DATA</th></tr>
    <tr>
        <th>Método / Method</th>
        <td>{{ $equipo->metodo ?? '' }}</td>
        <th>Marca / Brand</th>
        <td>{{ $equipo->marca ?? '' }}</td>
    </tr>
    <tr>
        <th>Modelo / Model</th>
        <td>{{ $equipo->modelo ?? '' }}</td>
        <th>No. de Serie / Serial Number</th>
        <td>{{ $equipo->serie ?? '' }}</td>
    </tr>
</table>

<!-- VALORES PROMEDIO -->
<table class="tabla">
    <tr class="encabezado"><th colspan="6">VALORES PROMEDIO DE DUREZAS / AVERAGE HARDNESS VALUES</th></tr>
    <tr class="subtitulo">
        <th>METAL BASE (A)</th>
        <th>ZAC HAZ (B)</th>
        <th>SOLDADURA / WELD (C)</th>
        <th>ZAC HAZ (B1)</th>
        <th>METAL BASE (B)</th>
        <th>ANTES / BEFORE PWHT</th>
    </tr>
    <tr>
        <td>{{ $valores->base_a ?? '' }}</td>
        <td>{{ $valores->zac_b ?? '' }}</td>
        <td>{{ $valores->soldadura_c ?? '' }}</td>
        <td>{{ $valores->zac_b1 ?? '' }}</td>
        <td>{{ $valores->base_b ?? '' }}</td>
        <td>{{ $valores->antes_pwht ?? '' }}</td>
    </tr>
    <tr class="subtitulo">
        <th colspan="5">POSTERIOR / AFTER PWHT</th>
        <td>{{ $valores->despues_pwht ?? '' }}</td>
    </tr>
</table>

<!-- DATOS DE LA JUNTA -->
<table class="tabla">
    <tr class="encabezado"><th colspan="6">DATOS DE LA JUNTA / JOIN DATA</th></tr>
    <tr>
        <th>Descripción / Description</th>
        <td colspan="5">{{ $junta->descripcion ?? '' }}</td>
    </tr>
</table>

<!-- HORARIOS TÉCNICOS -->
<table class="tabla horarios">
    <tr>
        <th>12:00</th>
        <th>03:00</th>
        <th>06:00</th>
        <th>09:00</th>
    </tr>
    <tr>
        <td>{{ $junta->hora_12 ?? '' }}</td>
        <td>{{ $junta->hora_03 ?? '' }}</td>
        <td>{{ $junta->hora_06 ?? '' }}</td>
        <td>{{ $junta->hora_09 ?? '' }}</td>
    </tr>
</table>

<!-- VALORES DE DUREZA -->
<table class="tabla">
    <tr class="encabezado"><th colspan="5">VALORES DE DUREZA (ESCALA BRINELL) / HARDNESS VALUES (BRINELL SCALE)</th></tr>
    <tr class="subtitulo">
        <th>METAL BASE (A)</th>
        <th>ZAC HAZ (B)</th>
        <th>SOLDADURA (C)</th>
        <th>ZAC HAZ (B1)</th>
        <th>METAL BASE (A1)</th>
    </tr>
    <tr>
        <td>{{ $valores->base_a1 ?? '' }}</td>
        <td>{{ $valores->zac_b ?? '' }}</td>
        <td>{{ $valores->soldadura_c ?? '' }}</td>
        <td>{{ $valores->zac_b1 ?? '' }}</td>
        <td>{{ $valores->base_a1 ?? '' }}</td>
    </tr>
</table>

<!-- OBSERVACIONES -->
<div class="encabezado">OBSERVACIONES / REMARKS</div>
<div class="observaciones">{{ $reporte->observaciones ?? '' }}</div>

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
