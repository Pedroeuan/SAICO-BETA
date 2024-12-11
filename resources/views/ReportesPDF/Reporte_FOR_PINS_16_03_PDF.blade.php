<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-16/03</title>
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
                    height: 125px;
                    text-align: center;
                    line-height: normal;
                    /*background-color: #f2f2f2;*/
                    border-top: 1px solid #ffffff;
                }
                    
                body {
                    margin-top: 105px; /* Ajusta según el tamaño de tu encabezado */
                    font-family: 'arial', sans-serif;
                }
                .content {
                    /*margin-top: 300px; /* Evita superposición con el header */
                    margin-bottom: 125px; /* Evita superposición con el footer */
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
                            <th style="width: 80%;">FOR-PINS-16/03</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">  LISTADO DE COMPONENTES </th>
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
                            <th>DESCRIPCION DEL EQUIPO:</th>
                            <td class="lineaInferior" colspan="3"></td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior"></td>
                            <th>ISOMETRICO/PLANO:</th>
                            <td class="lineaInferior"></td>
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
                    <div style="margin-bottom: 20px;"></div>
                                                
                    <table class="datosgenerales">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Realizó</th>
                                <th></th>
                            </tr>

                            <tr>
                                <td></td>
                                <td style="width: 20%; height:40px" class="lineaInferior"></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td><strong>NOMBRE</strong></td>
                                <td></td>
                            </tr>
                                                                
                            <tr>
                            <td></td>
                                <td><strong>Técnico N-II SNT-TC-1A</strong></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td></td>
                                <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                <td></td>
                            </tr>
                        </thead>                            
                    </table>
            </footer>
            
            <div class="content">
                <div style="margin-bottom: 0px;"></div>

                    <table class="datosresultados">
                        <thead>
                            <tr class="celdaGris">
                                <th style="width: 20px;">ID</th>
                                <th style="width: 40px;">DESCRIPCIÓN DEL ELEMENTO</th>
                                <th style="width: 30px;">NIVEL</th>
                                <th style="width: 30px;">Ø</th>
                                <th style="width: 30px;">LONGITUD (m)</th>
                                <th style="width: 30px;">CLASE</th>
                                <th style="width: 20px;">ESPECIFICACIÓN</th>
                                <th style="width: 30px;">OBSERVACIONES</th>

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
                                </tr>
                                @endfor

                            </tbody>
                    </table>
            </div>

        </body>
    </html>