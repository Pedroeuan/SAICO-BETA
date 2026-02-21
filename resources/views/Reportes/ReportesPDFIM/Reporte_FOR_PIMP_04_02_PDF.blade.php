<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-02_B/03</title>

    <style>
        @page {
            /* Se reducen márgenes para ganar espacio vertical y evitar el salto de hoja */
            margin: 1.5cm 1.2cm 1.5cm 1.2cm;
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
        }

        .datosgenerales {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
        }

        /* Espaciadores más pequeños */
        br {
            content: "";
            display: block;
            margin: 3px 0;
            line-height: 5px;
        }

        /* ===============================
        ESTILOS MICROESTRUCTURA
        =============================== */
        .tabla-micro {
            width: 100%;
            border-collapse: separate;
            border-spacing: 25px 5px; 
            font-size: 10px;
        }

        .cuadro {
            border: 2px solid #000;
            height: 155px; /* Reducido ligeramente para asegurar una sola hoja */
            position: relative;
            vertical-align: top;
        }

        .texto-arriba {
            position: absolute;
            top: 4px;
            left: 6px;
            font-weight: bold;
            font-size: 9px;
        }

        .texto-abajo {
            position: absolute;
            bottom: 4px;
            left: 0;
            right: 0;
            font-size: 8.5px;
            text-align: center;
            font-weight: bold;
        }

        .area-imagen {
            position: absolute;
            top: 15px;
            bottom: 15px;
            left: 10px;
            right: 10px;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <tr>
            <th style="width: 50%;">FORMATO</th>
            <th style="width: 15%;">Código:</th>
            <th style="width: 20%;">FOR-PIMP-04_/02</th>
            <th rowspan="3" style="width: 15%;">
                <img src="{{ $Logo }}" alt="Logo" style="width:55px;">
            </th>
        </tr>
        <tr>
            <th rowspan="2" style="font-size:9pt;">
                Informe de Caracterización de Materiales Mediante la Técnica de<br>
                Espectrometría de Emisión Óptica (OES)
            </th>
            <th>Versión</th>
            <th>2</th>
        </tr>
        <tr>
            <th>Página</th>
            <th>1 de 1</th>
        </tr>
    </table>
</header>

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

<table class="datosgenerales">
    <tr class="encabezadoAzul">
        <th colspan="4">ANÁLISIS METALOGRÁFICO</th>
    </tr>
</table>

<table style="width:100%; font-size:8px; border-collapse: collapse;" border="1" cellspacing="0">
    <tr>
        <th colspan="3">NÚMERO DE LIJA PARA EL DESBASTE</th>
        <th colspan="2">MATERIAL PARA EL PULIDO</th>
        <th colspan="2">DATOS DE ATAQUE QUÍMICO</th>
        <th>FASES PRESENTES</th>
        <th rowspan="2">ESPECIFICACIÓN APROXIMADA DEL MATERIAL</th>
    </tr>
    <tr>
        <td>240</td><td>320</td><td>400</td>
        <th>PAÑO</th><td></td>
        <th>REACTIVO</th><td></td>
        <td></td>
    </tr>
    <tr>
        <td>500</td><td>1000</td><td>1500</td>
        <th>ABRASIVO</th><td></td>
        <th>TIEMPO</th><td colspan="2"></td>
        <td></td>
    </tr>
</table>

<br>

<table class="tabla-micro">
    <tr>
        <td class="cuadro">
            <div class="area-imagen"></div>
            <div class="texto-abajo">FOTOMICROGRAFÍA A 100X</div>
        </td>
        <td class="cuadro">
            <div class="texto-arriba">DESCRIPCIÓN DE LA MICROESTRUCTURA:</div>
            <div class="area-imagen"></div>
        </td>
    </tr>
    <tr>
        <td class="cuadro">
            <div class="area-imagen"></div>
            <div class="texto-abajo">TAMAÑO DE GRANO XXX COMPARATIVA ASTM E-112</div>
        </td>
        <td class="cuadro">
            <div class="area-imagen"></div>
            <div class="texto-abajo" style="color:#0a7f2e;">
                FOTOGRAFÍA ESPECÍFICA DE CARACTERIZACIÓN
            </div>
        </td>
    </tr>
</table>

<footer>
    <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
        <thead> 
                <tr>
                    <td colspan="9" style="text-align: center; padding-bottom: 5px;">4 Firmas</td>
                </tr>
                <tr>
                    <td style="width: 15px;"></td>
                    <th></th>
                    <td style="width: 15px;"></td>
                    <th></th>
                    <td style="width: 15px;"></td>
                    <th></th>
                    <td style="width: 15px;"></td>
                    <th></th>
                    <td style="width: 15px;"></td>
                </tr>

                <tr>
                    <th></th>
                    <td style="width: 150px; height:35px" class="lineaInferior"></td>
                    <td></td>
                    <td style="width: 150px; height:35px" class="lineaInferior"></td>
                    <td></td>
                    <td style="width: 150px; height:35px" class="lineaInferior"></td>
                    <td></td>
                    <td style="width: 150px; height:35px" class="lineaInferior"></td>
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
                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                    <td></td>
                    <td><strong></strong></td>
                    <td></td>
                    <td><strong></strong></td>
                    <td></td>
                    <td><strong></strong></td>
                    <th></th>
                </tr>
        </thead>                            
    </table>
</footer>

</body>
</html>