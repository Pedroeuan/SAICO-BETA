<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-05_B/01</title>

    <style>
        @page {
            margin: 3cm 1.2cm 2.1cm 2.2cm;
        }

        body {
            margin: 0;
            padding-bottom: 60px;
            font-family: Arial, sans-serif;
        }

        /* ----------------- CABECERA ----------------- */
        header, footer {
            width: 100%;
            text-align: center;
        }

        .tablaheader {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            font-size: 10px;
        }

        .tablaheader th {
            border: 1px solid black;
        }

        /* ----------------- COLORES ----------------- */
        .encabezadoAzul {
            background-color: #305496;
            color: #fff;
            text-align: center;
            font-size: 8px;
        }

        .celdaGris {
            background-color: #DBDBDB;
        }

        /* ----------------- TABLAS GENERALES ----------------- */
        .datosgenerales,
        .datosinspeccion {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .datosinspeccion th,
        .datosinspeccion td {
            border: .6px solid black;
            padding: 3px;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
        }

        /* ----------------- TABLAS DE FIRMAS ----------------- */
        .tabla-firmas {
            width: 90%;
            margin: auto;
            font-size: 8px;
            border-collapse: collapse;
        }

        .tabla-firmas td {
            padding: 2px;
        }

        /* ----------------- TABLA + FOTO ----------------- */

        .contenedor-flex {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px; /* separación entre tabla y foto */
        }

        .col-izq {
            display: table-cell;
            width: 40%;
            vertical-align: top;
        }

        .col-der {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .campo-norma {
            font-size: 8px;
            margin-bottom: 10px;
        }

.foto {
    text-align: center;
    border: 1px solid black; /* borde del recuadro */
    padding: 2px;
    width: 150px; /* ancho más pequeño */
    margin: auto;
}

.foto img {
    width: 100%; /* ocupa todo el recuadro */
    height: auto;
    display: block;
}


        /* ----------------- TABLA IZQUIERDA MÁS CHICA ----------------- */
        .tabla-quimica table td,
        .tabla-quimica table th {
            padding: 0px 1px !important;
            height: 10px !important;
            line-height: 10px !important;
            font-size: 6.5px !important;
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
                <th rowspan="2">
                    Informe de Análisis Químico Mediante la Técnica de Espectrometría de Emisión Óptica (OES)<br>
                    Chemical Analysis Report Using the Optical Emission Spectrometry Technique
                </th>
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

<!-- DATOS GENERALES -->
<table class="datosgenerales">
    <thead class="encabezadoAzul">
        <tr><th colspan="4">DATOS GENERALES</th></tr>
    </thead>

    <tbody>
        <tr>
            <th style="width: 12%;">FECHA:<br>Date:</th>
            <td class="lineaInferior"></td>
            <th style="width: 15%;">NO. REPORTE:<br>No.Report:</th>
            <td class="lineaInferior"></td>
        </tr>

        <tr>
            <th>CLIENTE:<br>Client:</th>
            <td class="lineaInferior"></td>
            <th>CONTRATO:<br>No.Contract</th>
            <td class="lineaInferior"></td>
        </tr>

        <tr>
            <th>PROYECTO:<br>Project:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>

        <tr>
            <th>ORDEN DE TRABAJO:<br>Work Order:</th>
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
            <th>INSTALACIÓN:<br>Location:</th>
            <td class="lineaInferior"></td>
            <th>No. ISOMÉTRICO:<br>No.Isometric:</th>
            <td class="lineaInferior"></td>
        </tr>

        <tr>
            <th>NOMBRE DE LA PIEZA:<br>Name of the piece:</th>
            <td class="lineaInferior"></td>
            <th>MATERIAL:<br>Material:</th>
            <td class="lineaInferior"></td>
        </tr>

        <tr>
            <th>TRAZABILIDAD:<br>Traceabeility:</th>
            <td class="lineaInferior"></td>
            <th>PROCEDIMIENTO:<br>Procedure:</th>
            <td class="lineaInferior"></td>
        </tr>

        <tr>
            <th>CRITERIO DE EVALUACIÓN:<br>Evaluation Criterion:</th>
            <td class="lineaInferior"></td>
            <th>ACCESORIO:<br>Fittings:</th>
            <td class="lineaInferior"></td>
        </tr>

        <tr>
            <th>TUBERÍA:<br>Piping</th>
            <td class="lineaInferior"></td>
            <th>ESTRUCTURAL:<br>Structural</th>
            <td class="lineaInferior"></td>
        </tr>
    </tbody>
</table>

<br>

<!-- DATOS DEL EQUIPO -->
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

<!-- TABLA QUÍMICA + FOTO -->
<div class="contenedor-flex">

    <!-- IZQUIERDA -->
    <div class="col-izq tabla-quimica">
        <table class="datosinspeccion">
            <tr class="encabezadoAzul">
                <th>ELEMENTO QUÍMICO<br>Chemical Elements</th>
                <th>PROMEDIOS DE LA PIEZA ANALIZADA<br>Average of the analyzed Piece</th>
                <th>COMPOSICIÓN QUÍMICA TEÓRICA<br>Theoretical Chemical Composition</th>
            </tr>

            @foreach(['C','Si','Mn','P','S','Cr','Mo','Ni','Al','Co','Cu','Nb','Ti','V','W','Pb','Sn','Mg','As','Zr','B','Fe','N'] as $element)
            <tr>
                <td>{{ $element }}</td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </table>
    </div>

    <!-- DERECHA -->
    <div class="col-der">
                <div>
                    <table class="datosgenerales">                               
                        <tr>                                     
                            <th>NORMA DE REFERENCIA O ESPECIFICACIÓN APROXIMADA DEL MATERIAL:<br>Reference standard or approximate material specification:</th>                   
                            <td class="lineaInferior" style="width: 300px;"></td>                            
                        </tr>                      
                    </table>
                </div>
                <table></table>
                <table></table>
                <table></table>
                <table></table>

        <div class="foto" style="text-align:center;">
            <img src="{{ asset('storage/fotos/' . ($foto ?? 'default.png')) }}">
        </div>

    </div>
</div>

<br>

<!-- OBSERVACIONES -->
<table class="datosgenerales" style="margin-top: 25px;">
    <tr>
        <th>OBSERVACIONES:</th>
        <td class="lineaInferior" style="width: 606.5px;"></td>
    </tr>
</table>

<br>

<!-- TABLA DE FIRMAS -->
<table class="tabla-firmas">
    <thead>
        <tr>
            <td></td><th></th>
            <td></td><th></th>
            <td></td><th></th>
            <td></td><th></th>
            <td></td>
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
            <td colspan="6"></td>
            <th></th>
        </tr>
    </thead>
</table>

<footer></footer>

</body>
</html>
