<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-11/02</title>
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
                    margin-top: 242px; /* Ajusta según el tamaño de tu encabezado */
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
            background-color: #2F75B5;
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
                            <th style="width: 80%;">FOR-PINS-11/02</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">INFORME DE  INSPECCIÓN ULTRASINFORME DE  INSPECCIÓN ULTRASÓNICA CON HAZ RECTO EN BOCA DE TUBERIA </th>
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
                            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior"></td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="9">DATOS DEL EQUIPO </th>
                    </tr>
                </table>

                <div style="margin-bottom: 2px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th colspan="4">TRANSDUCTOR</th>
                            <th colspan="2">BLOCK DE REFERENCIA</th>
                            <th>ACOPLANTE (MARCA Y TIPO):</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">1</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td colspan="3">2</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">3</td>
                            <th class="" style="width: 100px;">4</th>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>5</td>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">6</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>7</td>
                            <th class="celdaGris" style="width: 100px;">LONGITUD DEL CABLE</th>
                        </tr>
                        <tr>
                            <th class="celdaGris">N.S:</th>
                            <td>8</td>
                            <th class="celdaGris">N.S:</th>
                            <td style="width: 60px;">9</td>
                            <th class="celdaGris" style="width: 50px;">FRECC:</th>
                            <td style="width: 50px;">10</td>
                            <th class="celdaGris">N.S:</th>
                            <td>11</td>
                            <td>12</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 8%;">GANANCIA:</th>
                            <td class="lineaInferior">1</td><td style="width: 1%;">dB</td>
                            <th style="width: 8%;">RANGO:</th>
                            <td class="lineaInferior">2</td>
                            <th style="width: 8%;">RECHAZO:</th>
                            <td class="lineaInferior">3</td>
                            <th style="width: 8%;">SUPERFICIE:</th>
                            <td class="lineaInferior">4</td>
                            <th style="width: 8%;">PINTURA:</th>
                            <td class="lineaInferior">5</td>
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
                <div style="margin-bottom: 5px;"></div>
                
                <table style="margin: auto; border: 0px solid black;">
                    <tr>
                        <td>
                            <table class="simbologia">
                                <thead>
                                    <tr>
                                        <th colspan="4" class="celdaAmarillo">INDICACIONES Y HALLAZGOS</th>
                                    </tr>

                                    <tr>
                                        <td><strong>NPIR:</strong></td>
                                        <td>NO PRESENTA INDICACIÓN RELEVANTE</td>
                                        <td><strong>CI:</strong></td>
                                        <td>CORROSIÓN INTERNA</td>
                                    </tr>

                                    <tr>
                                        <td><strong>I:</strong></td>
                                        <td>INCLUSIÓN NO METÁLICA</td>
                                        <td><strong>L:</strong></td>
                                        <td>LAMINACIÓN</td>
                                    </tr>

                                    <tr>
                                        <td><strong>ZI:</strong></td>
                                        <td>ZONA DE INCLUSIONES NO METALICAS</td>
                                        <td colspan="2" rowspan="2"><strong></strong></td>
                                    </tr>

                                    <tr>
                                        <td><strong>LE:</strong></td>
                                        <td>LAMINACIÓN ESCALONADA</td>
                                    </tr>
                                </thead>
                            </table>
                        </td>

                        <td>
                        <table class="">
                                <thead>
                                    <tr>
                                        <th colspan="6" class=""></th>
                                    </tr>

                                    <tr>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                    </tr>

                                    <tr>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                        <td><strong></strong></td>
                                        <td></td>
                                    </tr>
                                </thead>
                            </table>
                        </td>

                        <td>
                            <table class="simbologia">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA DEL REPORTE</th>
                                    </tr>

                                    <tr>
                                        <td><strong>A:</strong></td>
                                        <td>ÁNGULO (°)</td>
                                        <td><strong>LA:</strong></td>
                                        <td>LONGITUD AXIAL (IN)</td>
                                        <td rowspan="2"><strong>ta:</strong></td>
                                        <td rowspan="2">ESPESOR DE LA PARED EN ZONA SANA ADYACENTE</td>
                                    </tr>

                                    <tr>
                                        <td><strong>G:</strong></td>
                                        <td>GANANCIA (dB)</td>
                                        <td><strong>LC:</strong></td>
                                        <td>LONGITUD CIRCUNFERENCIAL (IN)</td>
                                    </tr>

                                    <tr>
                                        <td><strong>NR:</strong></td>
                                        <td>NIVEL DE REFERENCIA (%)</td>
                                        <td><strong>DNR:</strong></td>
                                        <td>DISTANCIA DE NIVEL DE REFERENCIA (IN)</td>
                                        <td><strong>H.T.</strong></td>
                                        <td>HORARIO TÉCNICO</td>
                                    </tr>

                                    <tr>
                                        <td><strong>NI:</strong></td>
                                        <td>NIVEL DE INDICACIÓN (%)</td>
                                        <td><strong>Tmin</strong></td>
                                        <td>ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                        <td><strong>d</strong></td>
                                        <td>PROFUNDIDAD DE LA INDICACION(IN)</td>
                                    </tr>
                                </thead>
                            </table>
                        </td>
                    </tr>
                </table>

                <br>

                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 915px;"></td>                            
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
                                    <th colspan="7">DATOS DEL MATERIAL</th>
                                    <th colspan="8">DATOS DE LA INDICACIÓN</th>
                                    <th colspan="4">RESULTADOS DE LA INSPECCIÓN</th>
                                    <th rowspan="2" style="width: 20px;">Observaciones</th>
                                </tr>
                                <tr class="celdaGris">
                                    <th style="width: 5px;">ID</th>
                                    <th style="width: 20px;">Elemento / Tubo</th>
                                    <th style="width: 20px;">No. Aceptación</th>
                                    <th style="width: 20px;">No. Serie</th>
                                    <th style="width: 20px;">No. Colada</th>
                                    <th style="width: 20px;">tnominal</th>
                                    <th style="width: 20px;">Ø</th>
                                    <th style="width: 20px;">No.Ind.</th>
                                    <th style="width: 20px;">Tipo de Indicación</th>
                                    <th style="width: 20px;">NR (%)</th>
                                    <th style="width: 20px;">NI (%)</th>
                                    <th style="width: 20px;">H.T.</th>
                                    <th style="width: 20px;">Prof</th>
                                    <th style="width: 20px;">LA</th>
                                    <th style="width: 20px;">LC</th>
                                    <th style="width: 20px;">tmáx</th>
                                    <th style="width: 20px;">tmin</th>
                                    <th style="width: 20px;">Metros Lineales</th>
                                    <th style="width: 25px;">Evaluación</th>
                                </tr>
                            </thead>

                            <tbody>
                            @for($i = 0; $i < 50; $i++)
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
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th colspan="4"><strong>Longitud total inspeccionada:</strong></th>
                                <th>0 m</th>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>
                    </table>
            </div>
        </body>
    </html>