<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-14/01</title>
            <style>
                @page {
                    margin: 
                    /*3.0cm /* superior */
                    /*2.1cm /* derecho */
                    /*2.1cm /* inferior */
                    /*2.4cm; /* izquierdo */
                    3.0cm /* superior */
                    1.2cm /* derecho */
                    2.1cm /* inferior */
                    2.2cm; /* izquierdo */
                }

                header {
                    position: fixed;
                    top: -30px; /* Ajusta para que no interfiera con el margen de la página */
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
            border: 1px solid black; 
        }

        .datosresultados td, .datosresultados th {
            border: .5px solid black; 
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
                            <th style="width: 80%;">FOR-INS-14/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">PROCEDIMIENTO DE INSPECCIÓN CON ULTRASONIDO POR EL METODO TOFD (TIME OF FLIGHT DIFFRACTION) </th>
                            <th>Versión</th>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>
                
            </header>

            <footer>
                    <br>

                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 605px;"></td>                            
                        </tr>                      
                    </table>

                    <br>
                                                
                    <table class="datosgenerales">
                        <thead>
                            @if( $numFirmas == 2)
                            <!-- 2 Firmas -->
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
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
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                </tr>
                            @elseif( $numFirmas == 3)
                            <!-- 3 Firmas -->
                                <tr>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 20px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
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
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</strong></td>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                                </tr>
                            @elseif( $numFirmas == 4)
                            <!-- 4 Firmas -->
                                <tr>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                                    <td style="width: 15px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo3'] }}</th>
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
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] }}</strong></td>
                                    <th></th>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] }}</strong></td>
                                    <th></th>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] }}</strong></td>
                                    <th></th>
                                </tr>
                            @endif
                        </thead>                            
                    </table>
            </footer>

            @foreach ($Grupo_Juntas_Detalles_Re as $grupo)
            <div class="content">
                
                <div style="margin-bottom: 0px;"></div>
                <table class="datosgenerales">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="4">DATOS GENERALES</th></tr>
                    </thead>   

                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                    <tr>
                            <th style="width: 12%;">FECHA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Fecha'] }}</td>
                            <th style="width: 15%;">NO. REPORTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['No_Reporte'] }}</td>
                        </tr>
                        <tr>
                            <th>CLIENTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Cliente'] }}</td>
                            <th>CONTRATO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Contrato'] }}</td>
                        </tr>
                        <tr>
                            <th>PROYECTO: </th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Proyecto'] }}</td>
                        </tr>
                        <tr>
                            <th>ORDEN DE TRABAJO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Orden_Trabajo'] }}</td>
                        </tr>
                        <tr>
                            <th>FOLIO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Folio'] }}</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Partida'] }}</td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Lugar'] }}</td>
                            <th>ISOMETRICO/PLANO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Isometrico_Plano'] }}</td>
                        </tr>
                        <tr>
                            <th>PIEZA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Pieza'] }}</td>
                            <th>MATERIAL:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Material'] }}</td>
                        </tr>
                        <tr>
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Procedimiento'] }}</td>
                            <th style="width: 160px;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Criterio_Evaluacion'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="8">DATOS Y AJUSTES DEL EQUIPO </th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="8"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th style="width: 20%;">ACOPLANTE(MARCA Y TIPO)</th>
                            <th style="width: 15%;">BLOCKS DE REF.</th>
                            <th colspan="4">SONDA #1</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 10%;">MARCA:</th>
                            <td style="width: 15%;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                            <td>{{ $Datos_Equipo['ACOPLANTE'] }}</td>
                            <td rowspan="2">{{ $Datos_Equipo['NOMB_BLOCK'] }}</td>
                            <th class="celdaGris" style="width: 12%;">MARCA:</th>
                            <td colspan="3" style="width: 10%;">{{ $Datos_Equipo['MARCA_SONDA1'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris">LONGITUD DEL CABLE</th>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_SONDA1'] }}</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td>{{ $Datos_Equipo['ZAPATA_SONDA1'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_EQUIPO'] }}</td>
                            <td>{{ $Datos_Equipo['LONG_CAB'] }}</td>
                            <td style="text-align: left;">S/N: {{ $Datos_Equipo['NS_BLOCK'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['NS_SONDA1'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td>{{ $Datos_Equipo['FREC_SONDA1'] }}</td>
                            
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
                            <td colspan="3" style="width: 15%;">{{ $Datos_Equipo['MARCA_SONDA2'] }}</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 15%;">{{ $Datos_Equipo['MARCA_SONDA3'] }}</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 10%;">{{ $Datos_Equipo['MARCA_SONDA4'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 8%;">{{ $Datos_Equipo['MODELO_SONDA2'] }}</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td style="width: 8%;">{{ $Datos_Equipo['ZAPATA_SONDA2'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['MODELO_SONDA3'] }}</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['ZAPATA_SONDA3'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['MODELO_SONDA4'] }}</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['ZAPATA_SONDA4'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_SONDA2'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td>{{ $Datos_Equipo['FREC_SONDA2'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_SONDA3'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['FREC_SONDA3'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_SONDA4'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['FREC_SONDA4'] }}</td>
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
                            <td colspan="3" style="width: 15%;">{{ $Datos_Equipo['MARCA_TRANS1'] }}</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 15%;">{{ $Datos_Equipo['MARCA_TRANS2'] }}</td>
                            <th class="celdaGris" style="width: 8%;">MARCA:</th>
                            <td colspan="3" style="width: 10%;">{{ $Datos_Equipo['MARCA_TRANS3'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 8%;">{{ $Datos_Equipo['MODELO_TRANS1'] }}</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td style="width: 8%;">{{ $Datos_Equipo['ZAPATA_TRANS1'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['MODELO_TRANS2'] }}</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['ZAPATA_TRANS2'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['MODELO_TRANS3'] }}</td>
                            <th class="celdaGris">ZAPATA:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['ZAPATA_TRANS2'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_TRANS1'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td>{{ $Datos_Equipo['FREC_TRANS1'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_TRANS2'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['FREC_TRANS2'] }}</td>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_TRANS3'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td style="width: 10%;">{{ $Datos_Equipo['FREC_TRANS3'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr>
                            <th colspan="4" class="celdaGris">TRANSDUCTOR DE TOFD #4</th>
                            <th rowspan="2" class="celdaGris">MODELO DE ENCODER 1:</th>
                            <td rowspan="2">{{ $Datos_Equipo['MODELO_ENCODER1'] }}</td>
                            <th>MARCA</th>
                            <td>{{ $Datos_Equipo['MARCA_ENCODER1'] }}</td>
                            <th rowspan="2" class="celdaGris">RESOLUCION DE ESCANEO:</th>
                            <td rowspan="2">{{ $Datos_Equipo['RES_SCAN1'] }}</td>
                        </tr>

                        <tr>
                            <th class="celdaGris" style="width: 6.5%;">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_TRANS4'] }}</td>
                            <th>SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_TRANS4'] }}</td>
                        </tr>

                        <tr>
                        <th class="celdaGris">MODELO:</th>
                            <td >{{ $Datos_Equipo['MODELO_TRANS4'] }}</td>
                            <th class="celdaGris" style="width: 7%;">ZAPATA:</th>
                            <td>{{ $Datos_Equipo['ZAPATA_TRANS4'] }}</td>
                            <th style="width: 16%;" rowspan="2" class="celdaGris">MODELO DE ENCODER 2:</th>
                            <th rowspan="2">{{ $Datos_Equipo['MODELO_ENCODER2'] }}</th>
                            <th>MARCA:</th>
                            <td>{{ $Datos_Equipo['MARCA_ENCODER2'] }}</td>
                            <th rowspan="2" class="celdaGris">RESOLUCION DE ESCANEO:</th>
                            <td rowspan="2">{{ $Datos_Equipo['RES_SCAN2'] }}</td>
                        </tr>

                        <tr>
                            <th class="celdaGris">SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_TRANS4'] }}</td>
                            <th class="celdaGris">FREC:</th>
                            <td>{{ $Datos_Equipo['FREC_TRANS4'] }}</td>
                            <th>SERIE:</th>
                            <td>{{ $Datos_Equipo['NS_ENCODER2'] }}</td>
                        </tr>

                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccion">
                    <tbody>
                        <tr>
                            <th style="width: 14%;" class="celdaGris">ANGULO DE INICIO:</th>
                            <td>{{ $Datos_Equipo['ANG_INI'] }}</td>
                            <th style="width: 12%;" class="celdaGris">ANGULO FINAL:</th>
                            <td>{{ $Datos_Equipo['ANG_FIN'] }}</td>
                            <th style="width: 8%;" class="celdaGris">VELOCIDAD:</th>
                            <td>{{ $Datos_Equipo['VELOCIDAD'] }}</td>
                            <th style="width: 8%;" class="celdaGris">FILTRO:</th>
                            <td>{{ $Datos_Equipo['FILTRO'] }}</td>
                            <th style="width: 8%;" class="celdaGris">CODIGO DE EVALUACION:</th>
                            <td>{{ $Datos_Equipo['COD_EVA'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 14%;" class="celdaGris">TIPO DE BARRIDO:</th>
                            <td colspan="2">6{{ $Datos_Equipo['TIP_BARR'] }}</td>
                            <th style="width: 14%;" class="celdaGris">AREA DE ESCANEO:</th>
                            <td>{{ $Datos_Equipo['AREA_SCAN'] }}</td>
                            <th colspan="2" style="width: 14%;" class="celdaGris">PROCEDIMIENTO:</th>
                            <td colspan="3">{{ $Datos_Equipo['PROCEDIMIENTO'] }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div style="margin-bottom: 5px;"></div>

                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">GANANCIA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['GANANCIA'] }}</td>
                            <th style="width: 15%;">TIPO DE JUNTA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TIP_JUNTA'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">RECHAZO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['RECHAZO'] }}</td>
                            <th style="width: 15%;">DIAMETRO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['DIAMETRO'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">TEMPERATURA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMP'] }}</td>
                            <th style="width: 15%;">ESPESOR:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['ESPESOR'] }}</td>
                        </tr>

                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="encabezadoAzul">
                        <tr>
                            <th colspan="9">RESULTADOS</th>
                        </tr>
                </table>
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
                            

                                {{-- 🔹 TÍTULO --}}
                                @if (!str_starts_with($grupo['titulos_juntas'], 'SIN TITULO'))
                                    <tr class="titulo-row">
                                        <td colspan="14" style="border:.5px solid black;">
                                            {{ $grupo['titulos_juntas'] }}
                                        </td>
                                    </tr>
                                @endif

                                {{-- 🔹 FILAS DEL BLOQUE --}}
                                @foreach ($grupo['resultados'] as $junta)
                                    <tr class="juntas">
                                        <td>{{ $junta['no_junta'] }}</td>
                                        <td>{{ $junta['Tip_Ind'] }}</td>
                                        <td>{{ $junta['L_PGL'] }}</td>
                                        <td>{{ $junta['A_PGL'] }}</td>
                                        <td>{{ $junta['AL_PGL'] }}</td>
                                        <td>{{ $junta['X'] }}</td>
                                        <td>{{ $junta['Y'] }}</td>
                                        <td>{{ $junta['DA_PROF'] }}</td>
                                        <td>{{ $junta['PA'] }}</td>
                                        <td>{{ $junta['SA'] }}</td>
                                        <td>{{ $junta['TMIN'] }}</td>
                                        <td>{{ $junta['SCAN'] }}</td>
                                        <td>{{ $junta['EVAL'] }}</td>
                                        <td>{{ $junta['FOTOS'] }}</td>
                                    </tr>
                                @endforeach

                                {{-- 🔹 LONGITUD INSPECCIONADA --}}
                                <tr class="">
                                    <td colspan="9"><b>SIR</b>= Sin indicaciones Relevantes <b>L</b>= Indicacion Lineal <b>R</b>= Indicacion Redondeada <b>A</b>= Aceptado 
                                    <b>R</b>= Rechazado <br> <b>FP</b>= Falta de Penetracion <b>FF</b>= Falta de Fusion <b>P</b>= Poros <b>PA</b>= Poros Agrupados
                                    <b>LA</b>= Linea de Escoria (<b>DA</b>=Profundidad / <b>PA</b>=Distancia superficial / <b>SA</b>= Distancia angular)
                                    </td>
                                    <th colspan="3">Longitud inspeccionada:</th>
                                    <th colspan="2">
                                        {{ $grupo['Long_Inspecc'][0] ?? '---' }} m
                                    </th>
                                </tr>

                                {{-- 🔹 SALTO DE PÁGINA POR BLOQUE 
                                <tr style="page-break-after: always;" class="sinBordetd">
                                    <td colspan="14"></td>
                                </tr>--}}
                            </tbody>
                    </table>

                <table>
            </div>
                @if (!$loop->last)
                    <div style="page-break-after: always;"></div>
                @endif
            @endforeach
        </body>
    </html>