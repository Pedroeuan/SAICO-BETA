<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-18/01</title>
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
                    height: 140px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    border-top: 1px solid #ffffff;
                }
                    
                body {
                    margin-top: 300px; /* Ajusta según el tamaño de tu encabezado */
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
                            <th style="width: 80%;">FOR-PINS-18/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> Informe de Detección de Discontinuidades con Corrientes de Eddy </th>
                            <th>Versión</th>
                            <th>2</th>
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
                            <th style="width: 160px;">CODIGO APLICABLE:</th>
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
                            <th colspan="2">SONDA</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">1</td>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">2</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>3</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>4</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>5</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>6</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">BLOCK DE CALIBRACIÓN</th>
                            <th colspan="2">CABLE</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">1</td>
                            <th class="celdaGris" style="width: 15%;">MARCA:</th>
                            <td style="width: 15%;">2</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>3</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>4</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>5</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>6</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>
                
                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">FRECUENCIA</th>
                            <td class="lineaInferior">1</td>
                            <th style="width: 15%;">GANANCIA HORIZONTAL</th>
                            <td class="lineaInferior">2</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">ESPESOR DE PINTURA.</th>
                            <td class="lineaInferior">3</td>
                            <th style="width: 15%;">GANANCIA VERTICAL</th>
                            <td class="lineaInferior">4</td>
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
                                <td style="width: 20px;"><strong>GT</strong></td>
                                <td style="width: 110px;">GRIETA LONGITUDINAL</td>
                                <td style="width: 20px;"><strong>IR</strong></td>
                                <td style="width: 150px;">INDICACIÓN REDONDEADA</td>
                                <td style="width: 20px;"><strong>LA</strong></td>
                                <td style="width: 180px;">LONGITUD AXIAL</td>
                            </tr>

                            <tr>
                                <td><strong>IL</strong></td>
                                <td>INDICACIÓN LINEAL</td>
                                <td><strong>GT</strong></td>
                                <td>GRIETAS TRANSVERSAL</td>
                                <td><strong>LC</strong></td>
                                <td>LONGITUD CIRCUNFERENCIAL</td>
                            </tr>

                            <tr>
                                <td><strong>CC</strong></td>
                                <td>CAMBIO DE CONDUCTIVIDAD</td>
                                <td><strong>ZG</strong></td>
                                <td>ZONA DE GRIETAS</td>
                                <td><strong>H.T.</strong></td>
                                <td>HORARIO TÉCNICO</td>
                            </tr>
                        </thead>
                    </table>

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
                            <th style="width: 30px;" colspan="2">DATOS DE INSPECCIÓN</th>
                                <th style="width: 40px;" colspan="5">DATOS DE LA INDICACIÓN</th>
                                <th style="width: 30px;" colspan="2">Área Inspeccionada</th>
                                <th style="width: 30px;" rowspan="2">Evaluación</th>
                                <th style="width: 30px;" rowspan="2">Fotos</th>
                                <th style="width: 30px;" rowspan="2">Observaciones</th>
                            </tr>  
                            <tr class="celdaGris">
                                <th style="width: 30px;">Junta / Elemento</th>
                                <th style="width: 40px;">Zona de Barrido</th>
                                <th style="width: 30px;">No. IndIcación</th>
                                <th style="width: 30px;">Tipo de Indicación</th>
                                <th style="width: 30px;">LA</th>
                                <th style="width: 30px;">LC</th>
                                <th style="width: 30px;">H.T.</th>
                                <th style="width: 30px;">Largo</th>
                                <th style="width: 30px;">Ancho</th>
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
                                </tr>
                                @endfor
                                <tr class="sinBordeth">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td colspan="2"><strong>TOTAL DE PUNTOS:</strong></td>
                                    <td colspan="2"><strong>0 m<sup>2</sup></strong></td>
                                </tr>

                            </tbody>
                    </table>
            </div>
        </body>
    </html>