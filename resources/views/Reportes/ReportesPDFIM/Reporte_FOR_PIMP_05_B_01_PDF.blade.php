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

        /* Ajuste para hacer las últimas tablas más pequeñas */
        .tabla-firmas {
            width: 90%;
            margin: auto;
            font-size: 8px;
        }

        .tabla-firmas td {
            padding: 2px;
        }

        /* ESTILO EDITADO PARA REDUCIR AMBAS TABLAS */
        .contenedor-tabla {
            width: 100%;
            border: 1px solid #000;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .titulo-azul {
            background: #305496;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 4px;
            font-size: 12px;
        }

        .subtitulo {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .tabla-interna {
            width: 48%;
            border-collapse: collapse;
            font-size: 8px;     /* REDUCIDO */
        }

        .tabla-interna th,
        .tabla-interna td {
            border: 1px solid black;
            padding: 2px;       /* REDUCIDO */
            text-align: left;
        }

        .tabla-right-container {
            width: 48%;
            float: right;
        }

        .tabla-left-container {
            width: 48%;
            float: left;
        }

        .clearfix {
            clear: both;
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
                <th style="width: 100%;">FOR-PIMP-05_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis Químico Mediante la Técnica de Espectrometría de Emisión Óptica (OES) Chemical Analysis Report Using the Optical Emission Spectrometry Technique</th>
                <th>Versión</th>
                <th>2</th>
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
            <td class="lineaInferior"></td>
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
    </tbody>
</table>

<br>

<table class="datosinspeccion">
    <tr class="encabezadoAzul">
        <th colspan="6">DATOS DEL EQUIPO</th>
    </tr>

    <tr class="celdaGris">
        <th>MARCA:</th>
        <td></td>
        <th>MODELO:</th>
        <td></td>
        <th>No. DE SERIE:</th>
        <td></td>
    </tr>
</table>

<br>

<table class="contenedor-tabla">

    <tr>
        <th colspan="2" class="titulo-azul">
            COMPOSICIÓN QUÍMICA DE LA PIEZA<br>
            <span style="font-size: 10px; font-weight: normal;">
                Chemical Composition of the Piece
            </span>
        </th>
    </tr>

    <tr>
        <td style="padding: 10px;">

            <div class="tabla-left-container">
                <table class="tabla-interna">
                    <tr>
                        <th>ELEMENTO QUÍMICO<br><span style="font-size:9px;">Chemical Elements</span></th>
                        <th>PROMEDIOS DE LA PIEZA ANALIZADA<br><span style="font-size:9px;">Average of the Analyzed Piece</span></th>
                        <th>COMPOSICIÓN QUÍMICA TEÓRICA<br><span style="font-size:9px;">Theoretical Chemical Composition</span></th>
                    </tr>

                    @php
                        $elementos = ["C","Si","Mn","P","S","Cr","Mo","Ni","Al","Co","Cu","Nb","Ti","V","W","Pb","Sn","Mg","As","Zr","B","Fe","N"];
                    @endphp

                    @foreach($elementos as $e)
                    <tr>
                        <td>{{ $e }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </table>
            </div>

            <div class="tabla-right-container">
                <div class="subtitulo">
                    ESPECIFICACIÓN APROXIMADA DEL MATERIAL<br>
                    <span style="font-size:9px; font-weight: normal;">
                        Reference standard or approximate material specification
                    </span>
                </div>

                <!-- <table class="tabla-interna">
                    <tr>
                        <th colspan="4" style="text-align:center;">VALORES OBTENIDOS DE LA PIEZA ANALIZADA<br>
                            <span style="font-size:9px; font-weight:normal;">Values Obtained from the Analyzed Piece</span>
                        </th>
                    </tr>

                    <tr>
                        <th>El.</th>
                        <th>% Conc</th>
                        <th>% Conc</th>
                        <th>% Conc</th>
                    </tr>

                    @foreach($elementos as $e)
                    <tr>
                        <td>{{ $e }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach
                </table> -->
            </div>

            <div class="clearfix"></div>

        </td>
    </tr>

</table>

<table class="datosgenerales" style="margin-top: 25px;">
    <tr>
        <th>OBSERVACIONES:</th>
        <td class="lineaInferior" style="width: 606.5px;"></td>
    </tr>
</table>

<!-- TABLA DE FIRMAS REDUCIDA -->
<table class="tabla-firmas">
    <thead>
        <tr>
            <td style="width: 10px;"></td><th></th>
            <td style="width: 10px;"></td><th></th>
            <td style="width: 10px;"></td><th></th>
            <td style="width: 10px;"></td><th></th>
            <td style="width: 10px;"></td>
        </tr>

        <tr>
            <th></th>
            <td class="lineaInferior" style="width: 130px; height:35px;"></td>
            <td></td>
            <td class="lineaInferior" style="width: 130px; height:35px;"></td>
            <td></td>
            <td class="lineaInferior" style="width: 130px; height:35px;"></td>
            <td></td>
            <td class="lineaInferior" style="width: 130px; height:35px;"></td>
            <th></th>
        </tr>

        <tr><th colspan="9"></th></tr>

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
