<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-14/01</title>
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
                    bottom: 0px; /* Ajustar para que quede dentro del margen inferior */
                    left: 0;
                    right: 0;
                    height: 160px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    border-top: 1px solid #ffffff;
                }
                    
                body {
                    margin-top: 460px; /* Ajusta según el tamaño de tu encabezado */
                    font-family: 'arial', sans-serif;
                }
                .content {
                    /*margin-top: 300px; /* Evita superposición con el header */
                    margin-bottom: 175px; /* Evita superposición con el footer */
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
        .rotar-texto-dividido {
            text-align: center; /* Centra el texto horizontalmente */
            padding: 0;
            display: inline-block; /* Necesario para la rotación */
            transform: rotate(270deg); /* Rota solo el texto */
            white-space: normal;
        }

        .rotar-texto-sin-dividir {
            text-align: center; /* Centra el texto horizontalmente */
            padding: 0;
            display: inline-block; /* Necesario para la rotación */
            transform: rotate(270deg); /* Rota solo el texto */
            white-space: nowrap; /* Evita que el texto se divida en varias líneas */
            max-width: 20px; /* Ajusta al ancho máximo deseado */
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
                            <th style="width: 80%;">FOR-PINS-14/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE INSPECCIÓN CON ULTRASONIDO POR ARREGLO DE FASES CON EL CODIGO AWS D1.1 </th>
                            <th>Versión</th>
                            <th>0</th>
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
                        <th colspan="9">DATOS Y AJUSTES DEL EQUIPO </th>
                    </tr>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th style="width: 20%;">ACOPLANTE(MARCA Y TIPO)</th>
                            <th style="width: 15%;">BLOCKS DE REF.</th>
                            <th colspan="4">SONDA #1</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 10%;">MARCA:</th>
                            <td style="width: 15%;">1</td>
                            <td>2</td>
                            <td rowspan="2">3</td>
                            <th class="celdaGris" style="width: 12%;">MARCA:</th>
                            <td colspan="3" style="width: 10%;">4</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>5</td>
                            <th class="celdaGris">LONGITUD DEL CABLE</th>
                            <th class="celdaGris">MODELO:</th>
                            <td>6</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td>7</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>8</td>
                            <td>9</td>
                            <td>S/N:</td>
                            <th class="celdaGris">SERIE:</th>
                            
                            <td style="width: 10%;">10</td>
                            <th class="celdaGris">FRECC:</th>
                            <td>11</td>
                            
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="4">SONDA #2</th>
                            <th colspan="4">SONDA #3</th>
                            <th colspan="4">SONDA #4</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 15%;">1</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 15%;">2</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 10%;">3</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 8%;">4</td>
                            <th class="celdaGris">ZAPATAC:</th>
                            <td style="width: 8%;">5</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">6</td>
                            <th class="celdaGris">ZAPATAC:</th>
                            <td style="width: 10%;">7</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">8</td>
                            <th class="celdaGris">ZAPATAC:</th>
                            <td style="width: 10%;">9</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>10</td>
                            <th class="celdaGris">FRECC:</th>
                            <td>11</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>12</td>
                            <th class="celdaGris">FRECC:</th>
                            <td style="width: 10%;">13</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>14</td>
                            <th class="celdaGris">FRECC:</th>
                            <td style="width: 10%;">15</td>
                        </tr>
                    </tbody>
                </table>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="4">TRANSDUCTOR DE TOFD #1</th>
                            <th colspan="4">TRANSDUCTOR DE TOFD #2</th>
                            <th colspan="4">TRANSDUCTOR DE TOFD #3</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 15%;">1</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 15%;">2</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 10%;">3</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 8%;">4</td>
                            <th class="celdaGris">ZAPATAC:</th>
                            <td style="width: 8%;">5</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">6</td>
                            <th class="celdaGris">ZAPATAC:</th>
                            <td style="width: 10%;">7</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">8</td>
                            <th class="celdaGris">ZAPATAC:</th>
                            <td style="width: 10%;">9</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>10</td>
                            <th class="celdaGris">FRECC:</th>
                            <td>11</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>12</td>
                            <th class="celdaGris">FRECC:</th>
                            <td style="width: 10%;">13</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>14</td>
                            <th class="celdaGris">FRECC:</th>
                            <td style="width: 10%;">15</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr>
                            <th colspan="4" class="celdaGris">TRANSDUCTOR DE TOFD #4</th>
                            <th rowspan="2" class="celdaGris">MODELO DE ENCODER 1:</th>
                            <td rowspan="2">1</td>
                            <th>MARCA</th>
                            <td>2</td>
                            <th rowspan="2" class="celdaGris">RESOLUCION DE ESCANEO:</th>
                            <td rowspan="2">3</td>
                        </tr>

                        <tr>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 15%;">4</td>
                            <th style="width: 8%;">SERIE:</th>
                            <td>5</td>
                        </tr>

                        <tr>
                        <th class="celdaGris">MODELO:</th>
                            <td style="width: 8%;">6</td>
                            <th>ZAPATA:</th>
                            <td style="width: 8%;">7</td>
                            <th style="width: 14%;" rowspan="2" >MODELO DE ENCODER 2:</th>
                            <th rowspan="2">8</th>
                            <th>MARCA:</th>
                            <td>9</td>
                            <th rowspan="2">RESOLUCION DE ESCANEO:</th>
                            <td rowspan="2">10</td>
                        </tr>

                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>11</td>
                            <th class="celdaGris">FRECC:</th>
                            <td>12</td>
                            <th>SERIE:</th>
                            <td>13</td>
                        </tr>

                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr>
                            <th style="width: 14%;" class="celdaGris">ANGULO DE INICIO:</th>
                            <td></td>
                            <th style="width: 12%;" class="celdaGris">ANGULO FINAL:</th>
                            <td></td>
                            <th style="width: 8%;" class="celdaGris">VELOCIDAD:</th>
                            <td></td>
                            <th style="width: 8%;" class="celdaGris">FILTRO:</th>
                            <td></td>
                            <th style="width: 8%;" class="celdaGris">CODIGO DE EVALUACION:</th>
                            <td></td>
                        </tr>

                        <tr>
                            <th style="width: 14%;" class="celdaGris">TIPO DE BARRIDO:</th>
                            <td colspan="2"></td>
                            <th style="width: 14%;" class="celdaGris">AREA DE ESCANEO:</th>
                            <td></td>
                            <th colspan="2" style="width: 14%;" class="celdaGris">PROCEDIMIENTO:</th>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">GANANCIA:</th>
                            <td class="lineaInferior">1</td>
                            <th style="width: 15%;">TIPO DE JUNTA:</th>
                            <td class="lineaInferior">2</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">RECHAZO:</th>
                            <td class="lineaInferior">1</td>
                            <th style="width: 15%;">DIAMETRO:</th>
                            <td class="lineaInferior">2</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">TEMPERATURA:</th>
                            <td class="lineaInferior">1</td>
                            <th style="width: 15%;">ESPESOR:</th>
                            <td class="lineaInferior">2</td>
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
                    <br>

                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 675px;"></td>                            
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
                                <th style="width: 30px;" rowspan="2">Junta / Elemento</th>
                                <th style="width: 40px;" rowspan="2">Tipo de ind.</th>
                                <th style="width: 30px;" rowspan="2">L(PLG)</th>
                                <th style="width: 30px;" rowspan="2">A(PLG)</th>
                                <th style="width: 30px;" rowspan="2">ALTURA(PLG)</th>
                                <th style="width: 30px;" colspan="2">EJE DE LA SOLD.</th>
                                <th style="width: 30px;" rowspan="2">DA(PROF)</th>
                                <th style="width: 30px;" rowspan="2">PA</th>
                                <th style="width: 30px;" rowspan="2">SA</th>
                                <th style="width: 30px;" rowspan="2">Tmin</th>
                                <th style="width: 30px;" rowspan="2">DATOS DEL ARCHIVO (Escaneo)</th>
                                <th style="width: 30px;" rowspan="2">EVALUACION</th>
                                <th style="width: 30px;" rowspan="2">FOTOS</th>
                            </tr>  
                            <tr class="celdaGris">
                                <td style="width: 30px;">X</td>
                                <td style="width: 30px;">Y</td>
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
                                    <td>----</td>
                                    <td>----</td>
                                    <td>----</td>
                                    <td>----</td>
                                </tr>
                                @endfor
                                <tr class="sinBordeth">
                                    <td colspan="10" rowspan="2">
                                    <b>SIR</b>= Sin indicaciones Relevantes 
                                    <b>L</b>= Indicacion Lineal 
                                    <b>R</b>= Indicacion Redondeada 
                                    <b>A</b>= Aceptado 
                                    <b>R</b>= Rechazado 
                                    <b>FP</b>= Falta de Penetracion 
                                    <b>FF</b>= Falta de Fusion 
                                    <b>P</b>= Poros 
                                    <b>PA</b>= Poros Agrupados
                                    <b>LA</b>= Linea de Escoria (<b>DA</b>=Profundidad / <b>PA</b>=Distancia superficial / <b>SA</b>= Distancia angular)
                                    </td>
                                    <td colspan="2"><strong>TOTAL DE PUNTOS:</strong></td>
                                    <td colspan="2"><strong>0 m</strong></td>
                                </tr>

                            </tbody>
                    </table>
            </div>
        </body>
    </html>