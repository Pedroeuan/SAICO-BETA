<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>OC Cotización</title>
            <style>
                @page {
                    margin: 90px 30px; /* Margen superior para header y margen inferior para footer */
                }
                header {
                    position: fixed;
                    top: -50px; /* Ajusta para que no interfiera con el margen de la página */
                    left: 0;
                    right: 0;
                    height: auto; /* Permite que el header crezca dinámicamente */
                    text-align: center;
                    /*background-color:rgb(226, 45, 45); /* Fondo para que sea visible */
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px; /* Ajusta la posición */
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    /*background-color: rgb(7, 231, 18)/* Fondo para que sea visible */
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin-top: 25px; /* Ajusta para que el contenido no se sobreponga al header */
                    /*margin: 0;*/
                    padding-top: 0px; /* Altura del header */
                    padding-bottom: 0px; /* Altura del footer */
                    font-family: 'arial', sans-serif;
                    /*background-color:rgb(45, 78, 226); /* Fondo para que sea visible */
                }
                .content {
                    /*margin-top: 300px; /* Evita superposición con el header */
                    margin-bottom: 0px; /* Evita superposición con el footer */
                }

                .table-container {
                    margin: 100px 0;
                }

                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 11px !important;
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
            border-collapse: collapse;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
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

        .titulo th {
            font-size: 14px;
        }
        .Firmas {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        .DentroFirmas {
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 11px;
        }

        /* Aplica el borde a las celdas de la tabla */
        .DentroFirmas td, .Firmas th {
            border: .6px solid black; 
            /*font-size: 9px;*/
        }
        .celdaAzul{
            background-color: #9BC2E6;
            /*color: #000000;*/
            /*font-weight: bold;*/
        }
        .letraNegra{
            color: #000000;
            font-weight: bold;
        }

        .celdaCrema{
            background-color: #FFF2CC;
        }
        .Comentarios {
            border-collapse: separate;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: justify;
            font-size: 11px;
        }
        /* Aplica el borde a las celdas de la tabla */
        .Comentarios td, .Comentarios th {
            border: .6px solid black; 
        }
        .tablaManifiesto {
            border-collapse: collapse;  /* No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            /*font-size: 11px;*/
        }
        .notas{
            text-align: left;
            font-size: 12px;
            border: 1px solid black; 
        }

        .sinborde-arriba-lados {
            border-top: none !important;
            /*border-left: none !important;*/
            border-right: none !important;
        }
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                        <tr>
                            {{--<th rowspan="3" style="width: 500%; font-size: 15pt;">ORDEN DE SERVICIO/TRABAJO/COMPRA</th>
                            <th style="width: 90%;">Código:</th>
                            <th style="width: 100%;">FOR-PVEN-02</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>--}}
                            <th rowspan="4" style="width: 500%; font-size: 15pt;">ASESORIA E INSPECCIÓN EN CONSTRUCCIÓN COSTA FUERA                "AICO SC"</th>
                            <th style="width: 100%;">Num. De Cotizacion</th>
                        </tr>
                        <tr>
                            <th style="width: 100%;">1</th>
                        <tr>
                            <th style="width: 100%;">Solicitud del cliente</th>
                        </tr>
                        <tr>
                            <th style="width: 100%;">2</th>
                        </tr>
                        </tr>
                        {{--<tr>
                            <th>Versión:</th>
                            <th>0</th>
                        </tr>

                        <tr>
                            <th>Página:</th>
                            <th></th>
                        </tr>--}}
                </table>

                <div style="margin-bottom: 10px;"></div>   

            </header>

            <footer>
                <table style="margin: auto; border: 0px solid black;">
                    <tr>
                        <td>

                            <table class="simbologia">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="celdaAmarillo">DATOS DE PAGO:</th>
                                    </tr>

                                    <tr>
                                        <td style="width: 90px;"><strong>RFC:</strong></td>
                                        <td style="width: 120px;">AIC150303461</td>
                                    </tr>

                                    <tr>
                                        <td><strong>FORMA DE PAGO:</strong></td>
                                        <td>Transferencia</td>
                                    </tr>

                                    <tr>
                                        <td><strong>BANCO:</strong></td>
                                        <td>BBVA</td>
                                    </tr>

                                    <tr>
                                        <td><strong>No. Cuenta:</strong></td>
                                        <td>019-915-083-8</td>
                                    </tr>

                                    <tr>
                                        <td><strong>No. Cuenta CLABE:</strong></td>
                                        <td>012-500-001-991-508-386</td>
                                    </tr>

                                    <tr>
                                        <td><strong>WhatsApp:</strong></td>
                                        <td>938-103-4445</td>
                                    </tr>
                                </thead>
                            </table>

                        </td>

                        <td>
                            <table class="" border="1">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="">&nbsp;</th>
                                    </tr>

                                    <tr>
                                        <td style="width: 100px;"><strong>&nbsp;</strong></td>
                                        <td style="width: 100px;">&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td><strong>&nbsp;</strong></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td><strong>&nbsp;</strong></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td><strong>&nbsp;</strong></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td><strong>&nbsp;</strong></td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td><strong>&nbsp;</strong></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </thead>
                            </table>
                        </td>

                        <td>

                            <table class="simbologia">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="celdaAmarillo">DATOS DE CONTACTO:</th>
                                    </tr>

                                    <tr>
                                        <td style="width: 90px;"><strong>Tel.:</strong></td>
                                        <td style="width: -10px;">938-197-8820</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Correo:</strong></td>
                                        <td>ventas@aicosc.com</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Pagina Web:</strong></td>
                                        <td>www.aicosc.com</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Consulta La Politica de SGI:</strong></td>
                                        <td>https://aicosc.com/Politica/Politica%20del%20SGI%20y%20Objetivos.pdf</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Direccion:</strong></td>
                                        <td>Av. Concordia S/N esq. Periférica, Casa 13, Col. Camaroneros, C.P. 24169, Cd. del Carmen, Cam.  </td>
                                    </tr>
                                </thead>
                            </table>

                        </td>
                    </tr>
                </table>

                    <div style="margin-bottom: 5px;"></div>

                    <table class="datosgenerales">
                        <thead>
                            @if( $numFirmas == 1)
                            <!-- 1 Firmas -->
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                                    <td style="width: 30px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 30px; height:40px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO1'] ?? '' }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO1'] ?? '' }}</strong></td>
                                </tr>

                                {{-- <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                </tr>--}}
                            @elseif( $numFirmas == 2)
                            <!-- 2 Firmas -->
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] ?? '' }}</th>
                                    <td style="width: 30px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:40px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO1'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO2'] ?? '' }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO1'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['CARGO2'] ?? '' }}</strong></td>
                                </tr>

                                {{--<tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                </tr>--}}
                            @elseif( $numFirmas == 3)
                            <!-- 3 Firmas -->
                                <tr>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] ?? '' }}</th>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] ?? '' }}</th>
                                    <td style="width: 20px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 200px; height:20px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:20px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 200px; height:20px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO1'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO2'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO3'] ?? '' }}</strong></td>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO1'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['CARGO2'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['CARGO3'] ?? '' }}</strong></td>
                                </tr>

                                {{-- <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                                </tr>--}}
                            @elseif( $numFirmas == 4)
                            <!-- 4 Firmas -->
                                <tr>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] ?? '' }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] ?? '' }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo3'] ?? '' }}</th>
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
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO1'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO2'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO3'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO4'] ?? '' }}</strong></td>
                                    <th></th>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO1'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['CARGO2'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['CARGO3'] ?? '' }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['CARGO4'] ?? '' }}</strong></td>
                                    <th></th>
                                </tr>

                                {{--<tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] }}</strong></td>
                                    <th></th>
                                </tr> --}}
                            @endif
                        </thead>                            
                    </table>
            </footer>


            @php
                //$porPagina = 20; // número de filas por página
                //$chunks = $detallesOT->chunk($porPagina);
                //dd($detallesOT);
            @endphp

            <div class="content">
                <div style="margin-bottom: 0px;"></div>
                
                <table class="datosinspeccion">
                    <tbody>
                        <tr>
                            <td style="width: 120px;">Objetivo del servicio:</td>
                            <td class="sinborde-arriba-lados"></td>
                        </tr>

                        <tr>
                            <td colspan="2">3</td>
                            
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 15px;"></div>
                
                <table class="datosinspeccion">
                    <tbody>
                        <tr>
                            <td style="width: 120px;">Cliente</td>
                            <td class="">Contacto</td>
                            <td class="">Puesto</td>
                            <td class="">Fecha</td>
                            <td class="">Ciudad</td>
                        </tr>

                        <tr>
                            <td class="">4</td>
                            <td class="">5</td>
                            <td class="">6</td>
                            <td class="">7</td>
                            <td class="">8</td> 
                        </tr>
                        <tr>
                            <td style="width: 120px;">Teléfono</td>
                            <td class="">Email</td>
                            <td colspan="2" class="">Lugar de Trabajo</td>
                            <td class="">Vigencia de Cotizacion</td>
                        </tr>

                        <tr>
                            <td class="">4</td>
                            <td class="">5</td>
                            <td colspan="2" class="">6</td>
                            <td class="">7</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 15px;"></div>

                <table class="datosresultados">
                    <thead>
                        <tr class="celdaAzul">
                            <th class="" style="width: 4%;">Ítem</th>
                            <th class="" style="width: 40%;">Descripción</th>
                            <th class="" style="width: 6%;">Cantidad</th>
                            <th class="" style="width: 6%;">Unidad</th>
                            <th class="" style="width: 40%;">Valor unitario</th>
                            <th class="" style="width: 40%;">Valor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>----</td>
                            <td>----</td>
                            <td>----</td>
                            <td>----</td>
                            <td>----</td>
                            <td>----</td>
                        </tr>
                    </tbody>
                </table> 

                <div style="margin-bottom: 15px;"></div>
                <table class="datosresultados">
                    <tr>
                        <td style="width: 120px;">CONDICIONES DE PAGO:</td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">8</td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">CONDICIONES DE GENERALES:</td>
                    </tr>
                    <tr>
                        <td style="width: 120px;">9</td>
                    </tr>
                </table> 
            </div>

        </body>
    </html>