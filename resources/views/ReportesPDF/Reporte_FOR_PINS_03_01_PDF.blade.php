<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-03/01</title>
            <style>
                @page {
                    margin: 90px 30px; /* Margen superior para header y margen inferior para footer */
                }
                header {
                    position: fixed;
                    top: -80px; /* Ajustar para que quede dentro del margen superior */
                    left: 0;
                    right: 0;
                    height: 100px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    border-bottom: 1px solid #ffffff;
                }
                footer {
                    position: fixed;
                    bottom: 100px; /* Ajustar para que quede dentro del margen inferior */
                    left: 0;
                    right: 0;
                    height: 100px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    border-top: 1px solid #ffffff;
                }
                    
                body {
                    margin-top: 248px; /* Ajusta según el tamaño de tu encabezado */
                    font-family: 'arial', sans-serif;
                }
                .content {
                    /*margin-top: 300px; /* Evita superposición con el header */
                    margin-bottom: 200px; /* Evita superposición con el footer */
                }

                .table-container {
                    margin: 100px 0;
                }

                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 8px !important;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                }
                    
                .simbologia {
                    border-collapse: collapse;  /*separate No colapsar bordes */
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 8px;
                }

                .simbologia td, .simbologia th {
                    border: .6px solid black; 
                }
                .celdaAmarillo{
                    background-color: #FFF2CC;
                }

                .tablaheader {
                    border-collapse: collapse; 
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 10px;
                }
                    
                /* Aplica el borde a las celdas de la tabla */
                .tablaheader th {
                    /*width: 70%;*/
                    border: 1px solid black; 
                }

        .encabezadoAzul{
            text-align: center;
            width: 100%;
            font-size: 8px;
            background-color: #305496;
            color: #ffffff;
            outline: 1px double #000000; /* Contorno externo */
        }
            
        .datosinspeccion{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 8px;
        }

        .datosinspeccion td, .datosinspeccion th {
            border: .6px solid black; 
        }

        .datosinspeccionsinborde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .datosresultados{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 8px;
        }

        .datosresultados td, .datosresultados th {
            border: .6px solid black; 
        }
        .celdaGris{
            background-color: #DBDBDB;
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
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 500%;">FORMATO</th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%;">FOR-PINS-03/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE INSPECCIÓN CON PARTÍCULAS MAGNÉTICAS</th>
                            <th>Versión</th>
                            <th>3</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 5px;"></div>
        
                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="4">DATOS GENERALES</th>
                    </tr>
                </table>   
                <div style="margin-bottom: 5px;"></div>         
                <table class="datosgenerales">
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
                            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="7">DATOS DE LA INSPECCIÓN</th>
                    </tr>
                </table>

                <div style="margin-bottom: 2px;"></div>

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
                            <th style="width: 20%;">EQUIPO</th>
                            <th style="width: 20%;">MARCA</th>
                            <th style="width: 20%;">MODELO</th>
                            <th style="width: 20%;">N/S</th>
                            <th style="width: 20%;">CORRIENTE</th>
                            <th style="width: 20%;">DISTANCIA ENTRE PATAS</th>
                            
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

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 10%;">TIPO DE LUZ:</th>
                            <td class="lineaInferior">1</td>
                            <th style="width: 10%;">INTENCIDAD:</th>
                            <td class="lineaInferior">2</td> <th style="text-align: left; width: 5%;"> Lx </th>
                            <th style="width: 10%;">CONDICIÓN SUPERFICIAL:</th>
                            <td class="lineaInferior">3</td>|
                            <th style="width: 10%;">TEMPERATURA DE PRUEBA:</th>
                            <td class="lineaInferior">4</td> <th style="text-align: left; width: 5%;"> °C </th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="encabezadoAzul">
                        <tr>
                            <th colspan="9">RESULTADOS</th>
                        </tr>
                </table>
            </header>

            <footer>
                    <table class="simbologia">
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
                    <br>

                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 676px;"></td>                            
                        </tr>                      
                    </table>

                    <br>
                                                
                    <table class="datosgenerales">
                        <thead>
                            <tr>
                                <td style="width: 30px;"></td>
                                <th>Realizó</th>
                                <td style="width: 30px;"></td>
                                <th>Vo.Bo.</th>
                                <td style="width: 30px;"></td>
                                <th>Vo.Bo.</th>
                            </tr>

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
            </footer>

            <div class="content">
                <div style="margin-bottom: 0px;"></div>
                    <table class="datosresultados">
                            <thead>
                                <tr class="celdaGris">
                                    <th rowspan= "2" style="width: 20%;">No.</th>
                                    <th rowspan= "2" style="width: 100%;">No. De Junta / Componente</th>
                                    <th rowspan= "2">No. Indicación</th>
                                    <th rowspan= "2">Tipo de Indicación</th>
                                    <th colspan="3">Dim. De Indicación</th>

                                    <th style="width: 100%;">Localización</th>
                                    <th rowspan= "2" style="width: 100%;">Evaluación</th>
                                    <th rowspan= "2" style="width: 150%;">Longitud Inspeccionada</th>
                                </tr>
                                <tr class="celdaGris">
                                    <th style="width: 100%;">Largo</th>
                                    <th style="width: 100%;">Ancho</th>
                                    <th style="width: 50%;">Ø</th>
                                    <th style="width: 50%;">H.T.</th>
                                </tr>
                            </thead>
                            <tbody>
                            @for($i = 0; $i < 54; $i++)
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
            </div>
        </body>
    </html>