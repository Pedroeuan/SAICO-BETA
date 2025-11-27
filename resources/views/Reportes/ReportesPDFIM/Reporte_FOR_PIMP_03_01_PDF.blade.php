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
                            <th style="width: 100%;">FOR_PIMP_03_01</th>
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