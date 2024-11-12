<!DOCTYPE html>
<html>
<head>
    <title>Manifiesto</title>
    <style>
        @page {
            header: html_myHeader; /* Nombre del bloque HTML para el header */
            footer: html_myFooter; /* Nombre del bloque HTML para el footer */
            margin-top: 30px;
            margin-bottom: 20px;
        } 

        .tablaheader {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 60px;
        }
        /* Aplica el borde a las celdas de la tabla */
        .tablaheader th {
            width: 70%;
            border: 1px solid black; 
            /*font-size: 11px;*/
        }

        .datosgenerales{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 20px;
        }

        .datosinspeccion{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 60px;
        }

        .datosinspeccion td, .datosinspeccion th {
            border: .6px solid black; 
            /*font-size: 11px;*/
        }

        .datosresultados{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 80px;
            /*margin-top: 60px;*/
        }

        .datosresultados td, .datosresultados th {
            border: .6px solid black; 
            /*font-size: 11px;*/
        }

        .tamanoPersonalizado{
            font-size: 20px;
        }


        .celdaAzul{
            background-color: #305496;
            border:black 2px double;
        }
        .letraBlanca{
            color: #ffffff;
        }

        /*muestra solo la linea inferior de la celda*/
        .lineaInferior{
            border-bottom: 1px solid black;
            text-align: center;
        }

        /*oculta todo el borde de la tabla*/
        .sinBorde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 60px;
        }

        .encabezadoAzul{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        /* Ajusta la celda al texto en los datos generales */
        .datosGeneralesCortos{
            font-weight: bold;
            width: 100px;
            text-align: center;
        }
        .respuestasGenerales{
            /*font-size: 11px;*/
            text-align: center;
        }

        .tablaManifiesto {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            /*font-size: 11px;*/
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaManifiesto td, .tablaManifiesto th {
            border: .6px solid black; 
            /*font-size: 11px;*/
        }
        .tablaManifiesto2 {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 20px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .tablaManifiesto2 td, .tablaManifiesto2 th {
            border: .6px solid black; 
            font-size: 11px;
        }

        .tablaManifiesto3 {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            /*font-size: 11px;*/
        }

        .tablaManifiesto3 td, .tablaManifiesto3 th {
            border: .6px solid black; 
            /*font-size: 8px;*/
            text-align: center;
            /*border:black 2px double;*/
        }

        .celdaCrema{
            background-color: #F8CBAD;
        }

        .celdaGris{
            background-color: #DBDBDB;
        }

        .celdaAmarillo{
            background-color: #FFF2CC;
        }

        .sinBordetdth td, .sinBordetdth th {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 100px;*/
        }
        
        .sinBordetd td {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 100px;*/
        }

        .sinBordeth th {
            border: 0px !important;
            text-align: left;
            border-collapse: collapse;
            width: 100%;
            /*font-size: 10px;*/
        }

        .conBorde{
            border: 10px;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
        }

        .datosequipo table, th, td {
            border-collapse: collapse;  
            border-spacing: 0px;        
            width: 100%;
            text-align: center;
        }

    </style>
    
</head>
    <body>
    <htmlpageheader name="myHeader">
        <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 60%;">FOR-PINS-03/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2"> INFORME DE INSPECCIÓN CON PARTÍCULAS MAGNÉTICAS </th>
                            <th>Versión</th>
                            <th>3</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th colspan="2" align="center" style="text-align: center;" class="lineaInferior"><strong>{PAGENO} de {nbpg} &nbsp;&nbsp;&nbsp;</strong></th>
                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 5px;"></div>
        
                <table class="encabezadoAzul">
                    <tr>
                        <th class="celdaAzul letraBlanca" colspan="4">DATOS GENERALES</th>
                    </tr>
                </table>            
                <table class="datosgenerales">
                    <tbody>
                        <tr>
                            <td class="datosGeneralesCortos" style="width: 26%;">FECHA:</td>
                            <td class="lineaInferior"></td>
                            <td class="datosGeneralesCortos" style="width: 30%;">NO. REPORTE:</td>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">CLIENTE:</td>
                            <td class="lineaInferior"></td>
                            <td class="datosGeneralesCortos">CONTRATO:</td>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">PROYECTO: </td>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">ORDEN DE TRABAJO:</td>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">FOLIO:</td>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">PARTIDA:</td>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">LUGAR:</td>
                            <td class="lineaInferior"></td>
                            <td class="datosGeneralesCortos">ISOMETRICO/PLANO:</td>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">PIEZA:</td>
                            <td class="lineaInferior"></td>
                            <td class="datosGeneralesCortos">MATERIAL:</td>
                            <td class="lineaInferior"></td>
                        </tr>
                        <tr>
                            <td class="datosGeneralesCortos">PROCEDIMIENTO:</td>
                            <td class="lineaInferior"></td>
                            <td class="datosGeneralesCortos" style="width: 160px;">CRITERIO DE EVALUACIÓN:</td>
                            <td class="lineaInferior"></td>
                        </tr>
                    </tbody>
                </table>

            
                <div style="margin-bottom: 4px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th class="celdaAzul letraBlanca" colspan="7">DATOS DE LA INSPECCIÓN</th>
                    </tr>
                </table>

                <table class="datosinspeccion">
                        <tbody>
                            <tr class="celdaGris">
                                <th style="width: 60px;">ITEM</th>
                                <th style="width: 100px;">MARCA</th>
                                <th style="width: 100px;">MODELO</th>
                                <th style="width: 100px;">LOTE</th>
                                <th style="width: 100px;">TIPO</th>
                                <th style="width: 100px;">COLOR</th>
                                <th style="width: 100px;">APLICACIÓN</th>
                            </tr>
                            <tr>
                                <th class="celdaGris">PARTICULAS:</th>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                                <td>5</td>
                                <td>6</td>
                            </tr>
                            <tr>
                                <th class="celdaGris">CONTRASTANTE:</th>
                                <td>7</td>
                                <td>8</td>
                                <td>9</td>
                                <td>10</td>
                                <td>11</td>
                                <td>12</td>
                            </tr>
                        </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th style="width: 200%;">EQUIPO</th>
                            <th style="width: 100%;">MARCA</th>
                            <th style="width: 100%;">MODELO</th>
                            <th style="width: 100%;">N/S</th>
                            <th style="width: 100%;">CORRIENTE</th>
                            <th style="width: 100%;">DISTANCIA ENTRE PATAS</th>
                            
                        </tr>
                        <tr>
                            <td>YUGO ELECTROMÁGNETICO:</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table>
                    <tbody>
                        <tr>
                            <th >TIPO DE LUZ:</th>
                            <td class="lineaInferior">1</td>
                            <th >INTENCIDAD:</th>
                            <td class="lineaInferior">2</td> <th style="text-align: left; width: 10px;"> Lx </th>
                            <th>CONDICIÓN SUPERFICIAL:</th>
                            <td class="lineaInferior">3</td>
                            <th style="width: 60%;">TEMPERATURA DE PRUEBA:</th>
                            <td class="lineaInferior">4</td> <th style="text-align: left; width: 10px;"> °C </th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="encabezadoAzul">
                        <tr>
                            <th class="celdaAzul letraBlanca" colspan="9">RESULTADOS</th>
                        </tr>
                </table>
        </header>
       

        <div style="margin-bottom: 2px;"></div>

    <table class="datosresultados">
            <thead>
                <tr class="celdaGris">
                    <th rowspan= "2" style="width: 20%;">No.</th>
                    <th rowspan= "2">No. De Junta / Componente</th>
                    <th rowspan= "2">No. Indicación</th>
                    <th rowspan= "2">Tipo de Indicación</th>
                    <th colspan="3">Dim. De Indicación</th>

                    <th style="width: 50%;">Localización</th>
                    <th rowspan= "2" style="width: 100%;">Evaluación</th>
                    <th rowspan= "2" style="width: 150%;">Longitud Inspeccionada</th>
                </tr>
                <tr class="celdaGris">
                    <th style="width: 50%;">Largo</th>
                    <th style="width: 50%;">Ancho</th>
                    <th style="width: 50%;">Ø</th>
                    <th style="width: 50%;">H.T.</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 0; $i < 24; $i++)
                <tr>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                    <td>----</td>
                </tr>
                @endfor
                <tr class="sinBordetd">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="4"><strong>Longitud total inspeccionada:</strong></td>
                    <th>0 m</th>
                </tr>
            </tbody>
    </table>
    </htmlpageheader> 
<htmlpagefooter name="myFooter">
    <div style="text-align: center;">
        <footer>
                <table class="tablaManifiesto3">
                    <thead>
                        <tr>
                            <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA</th>
                        </tr>

                        <tr>
                            <td style="width: 20px;"><strong>NPIR</strong></td>
                            <td style="width: 110px;">NO PRESENTA INDICACIÓN RELEVANTE</td>
                            <td style="width: 20px;"><strong>DM</strong></td>
                            <td style="width: 150px;">DAÑO MECÁNICO</td>
                            <td style="width: 20px;"><strong>PT</strong></td>
                            <td style="width: 180px;">POROSIDAD TUBULAR</td>
                        </tr>

                        <tr>
                            <td><strong>G</strong></td>
                            <td>GRIETA</td>
                            <td><strong>S</strong></td>
                            <td>SOCAVADO</td>
                            <td><strong>C</strong></td>
                            <td>CRATER</td>
                        </tr>

                        <tr>
                            <td><strong>ZG</strong></td>
                            <td>ZONA DE GRIETAS</td>
                            <td><strong>P</strong></td>
                            <td>POROSIDAD</td>
                            <td><strong>IL</strong></td>
                            <td>INDICACIÓN LINEAL</td>
                        </tr>

                        <tr>
                            <td><strong>FF</strong></td>
                            <td>FALTA DE FUSIÓN</td>
                            <td><strong>ZP</strong></td>
                            <td>ZONA DE POROS</td>
                            <td><strong>IR</strong></td>
                            <td>INDICACIÓN REDONDEADA</td>
                        </tr>

                    </thead>
                </table>

                <div style="margin-bottom: 20px;"></div>

                <div class="">
                    <table class="sinBorde">
                        <tr>
                            <td class="datosGeneralesCortos">Observaciones:</td>
                            <td class="lineaInferior"></td>
                        </tr>
                    </table>
                </div>

                <div style="margin-bottom: 20px;"></div>

                    <div class="">
                        <table class="sinBorde">
                            <thead>

                                <tr>
                                    <td style="width: 30px;"></td>
                                    <th>Realizó</th>
                                    <td style="width: 30px;"></td>
                                    <th>Vo.Bo.</th>
                                    <td style="width: 30px;"></td>
                                    <th>Vo.Bo.</th>
                                </tr>

                                <!--<tr class="sinBordetdth">-->
                                <tr>
                                    <th></th>
                                    <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>NOMBRE DEL TÉCNICO</strong></td>
                                    <td></td>
                                    <td><strong>NOMBRE DEL ENCARGADO</strong></td>
                                    <td></td>
                                    <td><strong>NOMBRE DEL ENCARGADO</strong></td>
                                </tr>
                                
                                <tr>
                                    <th></th>
                                    <td><strong>Técnico N-II SNT-TC-1A</strong></td>
                                    <td></td>
                                    <td><strong>PUESTO DEL ENCARGADO</strong></td>
                                    <td></td>
                                    <td><strong>PUESTO DEL ENCARGADO</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>EMPRESA A LA QUE PERTENECE</strong></td>
                                    <td></td>
                                    <td><strong>EMPRESA A LA QUE PERTENECE</strong></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
        </footer>           
    </div>
</htmlpagefooter>

    </body>
</html>