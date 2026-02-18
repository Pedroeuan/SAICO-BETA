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

        /* SEPARACIÓN ENTRE LAS 4 TABLAS INTERNAS */
        .main-table {
            width: 100%;
            border-collapse: separate !important;
            border-spacing: 12px; /* separación visible */
            margin-top: 10px;
        }
        .main-table th {
            background: #1e5288;
            color: white;
            text-align: center;
            padding: 6px;
            border: 1px solid #000;
            font-size: 13px;
        }
        .main-table td {
            border: 1px solid #000;
            height: 140px;
            padding: 5px;
            background: #fff;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sub-table th {
            background: #1e5288;
            color: white;
            padding: 4px;
            border: 1px solid #000;
            font-size: 11px;
        }
        .sub-table td {
            border: 1px solid #000;
            padding: 4px;
            height: 16px;
            font-size: 11px;
        }

        /* CONTENEDOR QUE DA ESPACIO A CADA TABLA INTERNA */
        .sub-container {
            padding: 6px;
            background: #f5f5f5;
            border-radius: 5px;
            border: 1px solid #ccc;
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
                <th style="width: 100%;">FOR-PIMP-06_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X (XRF) <br> Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF)</th>
                <th>Versión</th>
                <th>3</th>
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
        <tr><th colspan="4">DATOS GENERALES<br>General Data</th></tr>
    </thead>

    <tbody>
        <tr>
            <th style="width: 12%;">FECHA:<br>Date</th>
            <td class="lineaInferior"></td>
            <th style="width: 15%;">NO. REPORTE:<br>No.Report:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>CLIENTE:<br>Client:</th>
            <td class="lineaInferior"></td>
            <th>No.CONTRATO:<br>No.Contract:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>CONTRATO:<br>Contract:</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>ORDEN DE TRABAJO:<br>Work Orden:</th>
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
            <th>INSTALACIÓN<br>Location:</th>
            <td class="lineaInferior"></td>
            <th>NUMERO DE ISOMÉTRICO:<br>No.Isometric:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>NOMBRE DE LA PIEZA:<br>Name of the Piece:</th>
            <td class="lineaInferior"></td>
            <th>MATERIAL:<br>Material:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>PROCEDIMIENTO:<br>Procedure:</th>
            <td class="lineaInferior"></td>
            <th>CRITERIO DE EVALUACIÓN:<br>Evaluation Criteria:</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>TRAZABILIDAD:<br>Traceability:</th>
            <td class="lineaInferior"></td>
            <th>No.JUNTA:<br>No.Join:</th>
            <td class="lineaInferior"></td>
        </tr>
    </tbody>
</table>
<table></table>
<table></table>
<!-- DATOS DEL EQUIPO -->
<table class="datosinspeccion">
    <tr class="encabezadoAzul">
        <th colspan="6">DATOS DEL EQUIPO</th>
    </tr>

    <tr class="celdaGris">
        <th>MARCA:<br>Brand:</th>
        <td></td>
        <th>MODELO:<br>Model:</th>
        <td></td>
        <th>NÚMERO DE SERIE:<br>Serial Number:</th>
        <td></td>
    </tr>
</table>

<br>

<table class="datosgenerales" style="margin-top:15px;">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="2">
                RESULTADOS DEL ANÁLISIS QUÍMICO DEL ELEMENTO<br>
                Results of the Chemical Analysis of the Element
            </th>
        </tr>
    </thead>

    <tbody>
        <!-- FILA SUPERIOR -->
        <tr>
            <!-- DISPARO 1 -->
            <td width="50%" valign="top">
                <table class="datosinspeccion">
                    <tr class="encabezadoAzul">
                        <th>
                            1er. DISPARO (1st shot)<br>
                            VALORES OBTENIDOS EN LA PIEZA ANALIZADA<br>
                            <small>Values obtained in the analyzed piece</small>
                        </th>
                    </tr>
                    <tr><td height="25"></td></tr>
                    <tr><td height="25"></td></tr>
                </table>
            </td>

            <!-- DISPARO 2 -->
            <td width="50%" valign="top">
                <table class="datosinspeccion">
                    <tr class="encabezadoAzul">
                        <th>
                            2do. DISPARO (2nd shot)<br>
                            VALORES OBTENIDOS EN LA PIEZA ANALIZADA<br>
                            <small>Values obtained in the analyzed piece</small>
                        </th>
                    </tr>
                    <tr><td height="25"></td></tr>
                    <tr><td height="25"></td></tr>
                </table>
            </td>
        </tr>

        <!-- FILA INFERIOR -->
        <tr>
            <!-- DISPARO 3 -->
            <td valign="top">
                <table class="datosinspeccion">
                    <tr class="encabezadoAzul">
                        <th>
                            3er. DISPARO (3rd shot)<br>
                            VALORES OBTENIDOS EN LA PIEZA ANALIZADA<br>
                            <small>Values obtained in the analyzed piece</small>
                        </th>
                    </tr>
                    <tr><td height="25"></td></tr>
                    <tr><td height="25"></td></tr>
                </table>
            </td>

            <!-- TABLA QUÍMICA -->
            <td valign="top">
                <table class="datosinspeccion">
                    <tr class="encabezadoAzul">
                        <th>Elementos Químicos<br><small>Chemical Elements</small></th>
                        <th>
                            Promedio de Valores Obtenidos<br>
                            <small>Average Values Obtained</small>
                        </th>
                        <th>
                            % Composición Química de la Aleación<br>
                            <small>% Chemical Composition of the Alloy</small>
                        </th>
                    </tr>

                    <tr><td>% Mn</td><td></td><td></td></tr>
                    <tr><td>% Si</td><td></td><td></td></tr>
                    <tr><td>% Cr</td><td></td><td></td></tr>
                    <tr><td>% Ni</td><td></td><td></td></tr>
                    <tr><td>% Al</td><td></td><td></td></tr>
                    <tr><td>% Cu</td><td></td><td></td></tr>
                    <tr><td>% V</td><td></td><td></td></tr>
                    <tr><td>% Mo</td><td></td><td></td></tr>
                    <tr><td>% P</td><td></td><td></td></tr>
                    <tr><td>% S</td><td></td><td></td></tr>
                    <tr><td>% Fe</td><td></td><td></td></tr>
                    <tr><td>% Mg</td><td></td><td></td></tr>
                </table>
            </td>
        </tr>
    </tbody>
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

        /* ================= EVIDENCIA FOTOGRÁFICA ================= */

        .tabla-evidencia {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 8px;
        }

        .tabla-evidencia th {
            background-color: #305496;
            color: #fff;
            text-align: center;
            padding: 6px;
            border: 1px solid #000;
        }

        .tabla-evidencia td {
            border: 1px solid #000;
            vertical-align: top;
            padding: 6px;
        }

        .titulo-foto {
            font-weight: bold;
            text-align: center;
            font-size: 8px;
            margin-bottom: 4px;
        }

        .contenedor-foto {
            height: 150px;
            border: 1px dashed #666;
        }
    </style>
</head>

<body>

<!-- ================= ENCABEZADO ================= -->
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO</th>
                <th style="width: 70%;">Código:</th>
                <th style="width: 100%;">FOR-PIMP-06_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">
                    Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X (XRF)<br>
                    Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF)
                </th>
                <th>Versión</th>
                <th>3</th>
            </tr>
            <tr>
                <th>Página</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<br>

<!-- ================= DATOS GENERALES ================= -->
<table class="datosgenerales">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="4">DATOS GENERALES<br>General Data</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="width:12%;">FECHA:<br>Date</th>
            <td class="lineaInferior"></td>
            <th style="width:15%;">NO. REPORTE:<br>No.Report</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>CLIENTE:<br>Client</th>
            <td class="lineaInferior"></td>
            <th>No.CONTRATO:<br>No.Contract</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>CONTRATO:<br>Contract</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>ORDEN DE TRABAJO:<br>Work Order</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>FOLIO:<br>Folio</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>PARTIDA:<br>Lot</th>
            <td class="lineaInferior" colspan="3"></td>
        </tr>
        <tr>
            <th>INSTALACIÓN:<br>Location</th>
            <td class="lineaInferior"></td>
            <th>No. ISOMÉTRICO:<br>No. Isometric</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>NOMBRE DE LA PIEZA:<br>Name of the Piece</th>
            <td class="lineaInferior"></td>
            <th>MATERIAL:<br>Material</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>PROCEDIMIENTO:<br>Procedure</th>
            <td class="lineaInferior"></td>
            <th>CRITERIO DE EVALUACIÓN:<br>Evaluation Criteria</th>
            <td class="lineaInferior"></td>
        </tr>
        <tr>
            <th>TRAZABILIDAD:<br>Traceability</th>
            <td class="lineaInferior"></td>
            <th>No. JUNTA:<br>No. Joint</th>
            <td class="lineaInferior"></td>
        </tr>
    </tbody>
</table>

<br>

<!-- ================= EVIDENCIA FOTOGRÁFICA ================= -->
<table class="tabla-evidencia">
    <!-- ENCABEZADO -->
    <tr>
        <th colspan="2">
            EVIDENCIA FOTOGRÁFICA<br>
            <span style="font-size:7px;">Photographic Evidence</span>
        </th>
    </tr>

    <!-- FILA SUPERIOR: SOLO IZQUIERDA -->
    <tr>
        <td width="50%">
            <div class="titulo-foto">
                FOTO: PIEZA INSPECCIONADA<br>
                <span>Photo: Inspected Piece</span>
            </div>

            <div class="contenedor-foto">
                <!--
                <img src="{{ public_path('imagenes/pieza.jpg') }}"
                    style="width:100%; height:100%; object-fit:contain;">
                -->
            </div>
        </td>

        <!-- CELDA OCULTA -->
        <td width="50%" style="border:none; background:none;"></td>
    </tr>

    <!-- FILA INFERIOR: SOLO DERECHA -->
    <tr>
        <!-- CELDA OCULTA -->
        <td style="border:none; background:none;"></td>

        <td>
            <div class="titulo-foto">
                FOTO: REALIZACIÓN DE LA PRUEBA<br>
                <span>Photo: Test Performance</span>
            </div>

            <div class="contenedor-foto">
                <!--
                <img src="{{ public_path('imagenes/prueba.jpg') }}"
                    style="width:100%; height:100%; object-fit:contain;">
                -->
            </div>
        </td>
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
