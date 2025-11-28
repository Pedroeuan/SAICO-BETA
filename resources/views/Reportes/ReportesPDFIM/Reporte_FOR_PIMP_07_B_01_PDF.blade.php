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
                            <th style="width: 100%;">FOR-PIMP-07_B/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE RELEVADO DE ESFUERZOS / RELIEVED OF STRESS INFORM</th>
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
        <th>ELEMENTOS SOLDADOS:</th>
        <td class="lineaInferior"></td>
        <th>MATERIAL:</th>
        <td class="lineaInferior"></td>
    </tr>

    <tr>
        <th>No. JUNTA:</th>
        <td class="lineaInferior"></td>
        <th>TRAZABILIDAD:</th>
        <td class="lineaInferior"></td>
        <th>ESPESORES:</th>
        <td class="lineaInferior"></td>
    </tr>
        <tr>
        <th>PROCEDIMIENTO:</th>
        <td class="lineaInferior"></td>
        <th>CÓDIGO DE DISEÑO:</th>
        <td class="lineaInferior"></td>
        <th>DIÁM. NOMINAL:</th>
        <td class="lineaInferior"></td>
    </tr>
    <tr>
        <th>REPORTE DE DUREZA ANTES DEL RELEVADO:</th>
        <td class="lineaInferior"></td>
        <th>REPORTE DE DUREZA DESPUÉS DEL RELEVADO:</th>
        <td class="lineaInferior"></td>
    </tr>
</table>

<br>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DE LA INSPECCIÓN</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead> <!-- Fila vacia -->

                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 60px;">EQUIPO</th>
                                <th style="width: 100px;">MARCA</th>
                                <th style="width: 100px;">MODELO</th>
                                <th style="width: 100px;">No. SERIE</th>
                            </tr>
                            <tr>
                                <th class="celdaGris">MAQUINA DE RELEVADO:</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="celdaGris">REMOVEDOR:</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="celdaGris">REVELEADOR:</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                        </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 10%;">TIPO DE LUZ:</th>
                            <td class="lineaInferior"></td>
                            <th style="width: 10%;">INTENCIDAD:</th>
                            <td class="lineaInferior"></th>
                            <th style="width: 10%;">CONDICIÓN SUPERFICIAL:</th>
                            <td class="lineaInferior"></td>
                            <th style="width: 10%;">TEMPERATURA DE PRUEBA:</th>
                            <td class="lineaInferior"></td> 
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>
            </header>

            <footer>
                    <table class="simbologia">
                        <thead>
                            <tr>
                                <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA</th>
                            </tr>

                            <tr>
                                <td style="width: 20px;" class="celdaGris"><strong>NPIR</strong></td>
                                <td style="width: 110px;">NO PRESENTA INDICACIÓN RELEVANTE</td>
                                <td style="width: 20px;" class="celdaGris"><strong>DM</strong></td>
                                <td style="width: 150px;">DAÑO MECÁNICO</td>
                                <td style="width: 20px;" class="celdaGris"><strong>PT</strong></td>
                                <td style="width: 180px;">POROSIDAD TUBULAR</td>
                            </tr>

                            <tr>
                                <td class="celdaGris"><strong>G</strong></td>
                                <td>GRIETA</td>
                                <td class="celdaGris"><strong>S</strong></td>
                                <td>SOCAVADO</td>
                                <td class="celdaGris"><strong>C</strong></td>
                                <td>CRATER</td>
                            </tr>

                            <tr>
                                <td class="celdaGris"><strong>ZG</strong></td>
                                <td>ZONA DE GRIETAS</td>
                                <td class="celdaGris"><strong>P</strong></td>
                                <td>POROSIDAD</td>
                                <td class="celdaGris"><strong>IL</strong></td>
                                <td>INDICACIÓN LINEAL</td>
                            </tr>

                            <tr>
                                <td class="celdaGris"><strong>FF</strong></td>
                                <td>FALTA DE FUSIÓN</td>
                                <td class="celdaGris"><strong>ZP</strong></td>
                                <td>ZONA DE POROS</td>
                                <td class="celdaGris"><strong>IR</strong></td>
                                <td>INDICACIÓN REDONDEADA</td>
                            </tr>
                        </thead>
                    </table>
                    <br>

                    <table class="datosgenerales">                               
                        <tr>                                     
                            <th>OBSERVACIONES:</th>                   
                            <td class="lineaInferior" style="width: 606.5px;"></td>                            
                        </tr>                      
                    </table>

                    <br>


                            <!-- 4 Firmas -->
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
                            @endif
                        </thead>                            
                    </table>
            </footer>
</body>
</html>