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

        <tr>
            <th rowspan="2">Informe de Relevado de Esfuerzos / Stress Relief Report</th>
            <th>Versión</th>
            <th>1</th>
        </tr>

        <tr>
            <th>Página</th>
            <th></th>
        </tr>
    </table>
</header>

<br>


                <div style="margin-bottom: 4px;"></div>

                <table class="datosgenerales">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="4">DATOS GENERALES</th></tr>
                    </thead>   

                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

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
                            <th>PROYECTO: </th>
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
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior"></td>
                            <th style="width: 160px;">CÓDIGO APLICABLE:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                    </tbody>
                </table>

<br>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DE EQUIPOS/EQUIPMENT DATA</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead> <!-- Fila vacia -->

                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 100px;">EQUIPO/EQUIPMENT</th>
                                <th style="width: 100px;">MARCA/BRAND</th>
                                <th style="width: 100px;">MODELO/MODEL</th>
                                <th style="width: 100px;">No.SERIE/SERIAL NUMBER</th>
                            </tr>
                            <tr>
                                <th class="celdaGris">MAQUINA DERELEVADO/STRESS RELIEF MACHINE:</th>
                                <td></td>
                                <td></td>
                                <td></td>

                            </tr>
                            <tr>
                                <th class="celdaGris">GRAFICADOR/GRAPHIER:</th>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                </table>


{{-- ================================
        DATOS DE PRUEBA
================================ --}}
                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DE LA INSPECCIÓN</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead> <!-- Fila vacia -->

                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 60px;"></th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">TEMPERATURA INICIAL INITIAL TEMPERATURE (°F)</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">HORA INCIAL DE PRUEBA TEST START TIME:</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;"></th>
                            </tr>
                            <tr>
                            <tr class="celdaGris">
                                <th style="width: 60px;"></th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">VEL.DE CALENTAMIENTO HEATING RATE (°F/hr)</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">HORA FINAL DEPRUEBA TEST END TIME:</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;"></th>
                            </tr>
                            <tr class="celdaGris">
                                <th style="width: 60px;"></th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">TEMP.SOSTENIMIENTO HOLDING TEMPERATURE (°F)</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">DÍA DE INICIO DE PRUEBA TEST START DAY:</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;"></th>
                            </tr>
                            <tr>
                            <tr class="celdaGris">
                                <th style="width: 60px;"></th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">TIEMPO DE SOSTENIMIENTO HOLDING TIME (MIN)</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">DÍA DE FINALIZACIÓN DE PRUEBA TEST END DAY:</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;"></th>
                            </tr>
                            <tr class="celdaGris">
                                <th style="width: 60px;"></th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">VEL.DE ENFRIAMIENTO COOLING RATE (°F/hr)</th>
                                <th style="width: 100px;"></th>
                                <th style="width: 100px;">No.GRÁFICA No.GRAPH</th>
                                <td></td>
                                <td></td>
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