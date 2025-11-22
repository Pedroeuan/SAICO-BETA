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
                            <th style="width: 100%;">FOR-PIMP-02_B/03</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> Informe de Ensayo de Durezas en Metales Base Hardness Test Report on Base Metals</th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <!-- <div style="margin-bottom: 4px;"></div>

                <table class="datosgenerales">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="6">DATOS GENERALES</th></tr>
                    </thead>  -->
<!-- DATOS GENERALES -->
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

<br>

<!-- DATOS DE LA PRUEBA -->
<table class="datosinspeccion">
    <tr class="encabezadoAzul">
        <th colspan="6">DATOS DE LA PRUEBA</th>
    </tr>

    <tr class="celdaGris">
        <th>MÉTODO:</th>
        <td></td>
        <th>TEMPERATURA PIEZA:</th>
        <td></td>
        <th>ESPESOR/CÉDULA:</th>
        <td></td>
    </tr>
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
        <th>NO. SERIE:</th>
        <td></td>
    </tr>
</table>

<br>

<table width="100%" style="border-collapse: collapse;">
    <tr>
        <!-- IZQUIERDA -->
        <td width="70%" valign="top">

<table class="datosinspeccion" style="margin-bottom: 25px;">
    <tr class="encabezadoAzul">
        <th colspan="5">VALORES DE DUREZA MEDIDOS (ESCALA BRINELL)</th>
    </tr>

    <tr>
        <td height="25px"></td><td></td><td></td><td></td><td></td>
    </tr>
    <tr>
        <td height="25px"></td><td></td><td></td><td></td><td></td>
    </tr>
</table>


            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <td style="padding:6px; width:80%;">
                        <strong>DUREZA PROMEDIO MEDIDO</strong><br>
                        <em>Measured Average Hardness</em>
                    </td>
                    <td colspan="3"></td>
                </tr>

                <tr>
                    <td style="padding:6px;">
                        <strong>DUREZA SEGÚN ESPECIFICACIÓN</strong><br>
                        <em>Hardness per Specification</em>
                    </td>
                    <td colspan="3"></td>
                </tr>
            </table>
        </td>

        <!-- DERECHA -->
        <td width="30%" align="center" valign="top">
            <img src="{{ asset('storage/fotos/' . ($foto ?? 'default.png')) }}" width="180">
        </td>
    </tr>
</table>

<table class="datosgenerales" style="margin-top: 25px;">                               
    <tr>                                     
        <th>OBSERVACIONES:</th>                   
        <td class="lineaInferior" style="width: 606.5px;"></td>                            
    </tr>                      
</table>


                    <table>
                        <thead> 
                                4 Firmas 
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
                                    <td><strong></strong></td>
                                    <td></td>
                                    <td><strong></strong></td>
                                    <td></td>
                                    <td><strong></strong></td>
                                    <td></td>
                                    <td><strong></strong></td>
                                    <th></th>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong></strong></td>
                                    <td></td>
                                    <td><strong></strong></td>
                                    <td></td>
                                    <td><strong></strong></td>
                                    <td></td>
                                    <td><strong></strong></td>
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

            <div class="content"> 

                
            </div>
        </body>
        
    </html>