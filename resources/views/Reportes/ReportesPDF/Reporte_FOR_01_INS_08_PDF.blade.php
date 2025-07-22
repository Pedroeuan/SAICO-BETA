<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-08/01</title>
           <style>
                @page {
                    margin: 
                    3.0cm /* superior */
                    2.1cm /* derecho */
                    2.1cm /* inferior */
                    2.4cm; /* izquierdo */
                }
                @if ($totalTitulosYFilas <=15)
                header {
                    width: 100%;
                    top: -30px; /* Ajusta para que no interfiera con el margen de la página */
                    height: auto; /* Permite crecer según el contenido */
                    text-align: center;
                    /*background-color: rgb(226, 45, 45);*/
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    /*background-color: rgb(7, 231, 18);*/
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin: -30px, 0; /* Ajusta el margen de la página */
                    padding-bottom: 60px; /* Para que el contenido no se monte en el footer */
                    font-family: 'arial', sans-serif;
                    /*background-color: rgb(45, 78, 226);*/
                }
            @else
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
                    /*margin-top: 320px; /* Ajusta para que el contenido no se sobreponga al header */
                    margin: 0;
                    padding-top: 285px; /* Altura del header */
                    padding-bottom: 95px; /* Altura del footer */
                    font-family: 'arial', sans-serif;
                    /*background-color:rgb(45, 78, 226); /* Fondo para que sea visible */
                }
                @endif
                .datosgenerales{
                    border: 0px !important;
                    text-align: center;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 6px !important;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                    font-size: 6px;
                }
                    
                .simbologia {
                    border-collapse: collapse;  /*separate No colapsar bordes */
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 5px;
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
            font-size: 7px;
            background-color: #2F75B5;
            color: #ffffff;
            outline: 1px double #000000; /* Contorno externo */
        }

        .border {
            border: 1px solid black; 
        }
            
        .datosinspeccion{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 6px;
        }

        .datosinspeccion td, .datosinspeccion th {
            border: .6px solid black; 
        }
        

        .datosinspeccionsinborde{
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
            font-size: 6px;
        }

        .datosresultados{
            border-collapse: separate;  /*separate; No colapsar bordes */ /*collapse; Fusiona los bordes de las celdas */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 10px;
            /*border : 1px solid black;*/
        }

        .datosresultados td, .datosresultados th {
            border: .1px solid black; /* Borde grueso de 2px */
        }
        .celdaGris{
            background-color: #DBDBDB;
            font-size: 6px;
        }

        .celdaGrisResultados{
            background-color: #DBDBDB;
            font-size: 9px;
        }

        .juntas{
            font-size: 9px;
        }
        
        .sinBordetdth td, .sinBordetdth th {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
        }
        
        .sinBordetd td {
            border: 0px !important;
            text-align: center;
            border-collapse: collapse;
            width: 100%;
        }

        .sinBordeth th {
            border: 0px !important;
            text-align: left;
            border-collapse: collapse;
            width: 100%;
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
                            <th style="width: 80%;">FOR-INS-08/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">   INFORME DE INSPECCIÓN ULTRASÓNICA CON HAZ ANGULAR </th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 4px;"></div>
        
                <table class="datosgenerales">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="4">DATOS GENERALES</th></tr>
                    </thead>  

                    <thead>
                        <tr class="sinBordeth"><th colspan="4"></th></tr>
                    </thead> <!-- Fila vacia -->
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
                        <tr><th colspan="9">DATOS DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="9"></th></tr></thead> <!-- Fila vacia -->
                
                    <tbody>
                        <tr><th style="border:none;"></th> </tr>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th colspan="4">TRANSDUCTOR</th>
                            <th colspan="2">BLOCK DE REFERENCIA</th>
                            <th>ACOPLANTE (MARCA Y TIPO):</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_TR'] }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_BLOCK'] }}</td>
                            <td class="" style="width: 100px;">{{ $Datos_Equipo['ACOPLANTE'] }}</td>
                        </tr>
                         <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{ $Datos_Equipo['MODELO_TR'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] }}</td>
                            <th class="celdaGris" style="width: 100px;">LONGITUD DEL CABLE</th>
                        </tr>
                        <tr>
                             <th class="celdaGris">N.S:</th>
                            <td>{{ $Datos_Equipo['NS_EQUIPO'] }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td style="width: 60px;">{{ $Datos_Equipo['NS_TR'] }}</td>
                            <th class="celdaGris" style="width: 50px;">FREC:</th>
                            <td style="width: 50px;">{{ $Datos_Equipo['FREC_TR'] }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $Datos_Equipo['NS_BLOCK'] }}</td>
                            <td>{{ $Datos_Equipo['LONG_CABLE'] }}</td>
                        </tr>
                        

                    </tbody>
                </table>

                <div style="margin-bottom: 5px;"></div>

                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="9">DATOS DE LA INSPECCIÓN</th>
                    </tr>
                </table>

                <div style="margin-bottom: 5px;"></div>
                
                <table class="datosinspeccionsinborde">
                    <tbody>
                        <tr>
                            <th style="width: 15%;">GANANCIA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['GANANCIA'] }}</td>
                            <th style="width: 15%;">RANGO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['RANGO'] }}</td>
                            <th style="width: 15%;">RECHAZO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['RECHAZO'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">PRESION DE OPERACIÓN:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRESION_OPERACION'] }}</td>
                            <th style="width: 15%;">PRESIÓN MÁXIMA DE OPERACIÓN:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRES_MAX_OPE'] }}</td>
                            <th style="width: 20%;">TEMPERATURA MAX. DE OPERACION:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMP_MAX_OP'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">CONDICION SUPERFICIAL:</th>
                            <td class="lineaInferior" colspan="2">{{ $Datos_Equipo['CONDICION_SUPERFICIAL'] }}</td>
                            <th style="width: 15%;">ESTADO DE PINTURA:</th>
                            <td class="lineaInferior" colspan="2">{{ $Datos_Equipo['ESTADO_PINTURA'] }}</td>
                        </tr>

                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>
            </header>

            <footer style="display:;">
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
                                            <td><strong>FP:</strong></td>
                                            <td>FALTA DE PENETRACIÓN</td>
                                        </tr>

                                        <tr>
                                            <td><strong>FF:</strong></td>
                                            <td>FALTA DE FUSIÓN</td>
                                            <td><strong>G:</strong></td>
                                            <td>GRIETAS</td>
                                        </tr>

                                        <tr>
                                            <td><strong>IE:</strong></td>
                                            <td>INCLUSIÓN DE ESCORIA</td>
                                            <td><strong>IVL:</strong></td>
                                            <td>INDICACIÓN VOLUMÉTRICA</td>
                                        </tr>

                                        <tr>
                                            <td><strong>IL</strong></td>
                                            <td>INDICACIÓN LINEAL</td>
                                            <td><strong>IT:</strong></td>
                                            <td>INDICACIÓN TRANSVERSSAL</td>
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
                                            <td><strong>NI:</strong></td>
                                            <td>NIVEL DE INDICACIÓN (%)</td>
                                            <td><strong>d</strong></td>
                                            <td>PROFUNDIDAD (PULG)</td>
                                        </tr>

                                        <tr>
                                            <td><strong>G</strong></td>
                                            <td>GANANCIA (dB)</td>
                                            <td><strong>L:</strong></td>
                                            <td>LONGITUD DE LA INDICACIÓN (PULG)</td>
                                            <td><strong></strong></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td><strong>NR:</strong></td>
                                            <td>NIVEL DE REFERENCIA (%)</td>
                                            <td><strong>DSR</strong></td>
                                            <td>DISTANCIA DE LA INDICACIÓN</td>
                                            <td><strong></strong></td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td><strong>S:</strong></td>
                                            <td>DISTANCIA ANGULAR (Pulg)</td>
                                            <td><strong>Tmin</strong></td>
                                            <td>ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                            <td><strong></strong></td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                </table>
                            </td>
                        </tr>
                    </table>

                    <div style="margin-bottom: 5px;"></div>

                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 814px;"></td>                            
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
                            @else
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
            
            <div class="content">
                    <table class="datosresultados">

                        <thead class="encabezadoAzul">
                            <tr><th colspan="22">RESULTADOS</th></tr>
                        </thead>

                            <thead><tr class="sinBordeth"><th colspan="20">
                                </th></tr>
                            </thead> <!-- Fila vacia -->
                            <thead>
                            <tr><th style="border:none;"></th></tr>
                            <tr class="celdaGris">
                                <th style="width: 30px;" rowspan="2">ID</th>
                                <th style="width: 40px;" rowspan="2">No. De Junta</th>
                                <th style="width: 30px;" colspan="2">Elemento de referencia</th>
                                <th style="width: 30px;" rowspan="2">Ø</th>
                                <th style="width: 30px;" rowspan="2">No.Ind.</th>
                                <th style="width: 30px;" rowspan="2">Tipo de Indicación</th>
                                <th style="width: 30px;" rowspan="2">A (°)</th>
                                <th style="width: 30px;" rowspan="2">G (dB)</th>
                                <th style="width: 30px;" rowspan="2">NR (%)</th>
                                <th style="width: 30px;" rowspan="2">NI (%)</th>
                                <th style="width: 30px;" colspan="2">DSR</th>
                                <th style="width: 30px;" rowspan="2">Horario Técnico</th>
                                <th style="width: 30px;" rowspan="2">No. De Pierna</th>
                                <th style="width: 30px;" rowspan="2">S</th>
                                <th style="width: 30px;" rowspan="2">L</th>
                                <th style="width: 30px;" rowspan="2">d</th>
                                <th style="width: 30px;" rowspan="2">tmin</th>
                                <th style="width: 30px;" rowspan="2">Evaluación</th>
                                <th style="width: 30px;" rowspan="2">Fotos No.</th>
                                <th style="width: 30px;" rowspan="2">Observaciones</th>
                            </tr>
                            <tr class="celdaGris">
                                <th style="width: 30px;">Lado A</th>
                                <th style="width: 30px;">Lado B</th>
                                <th style="width: 30px;">X</th>
                                <th style="width: 30px;">Y</th>
                            </tr>
                        </thead>

                                <tbody>
                                    @php
                                        $contador = 1;
                                        $filasPorPagina = 15;
                                        $contadorFilas = 0;
                                        $contadorFilasPagina = 0;
                                        $totalMetros = 0;
                                    @endphp

                                    @foreach ($Grupo_Juntas_Detalles_Re as $grupo)
                                        @php
                                            $titulo = $grupo['titulos_juntas'];
                                            $juntasDelGrupo = count($grupo['resultados']);
                                            $filasDelGrupo = $juntasDelGrupo + ($titulo !== 'SIN TITULO' ? 1 : 0); // +1 por el título si aplica
                                        @endphp

                                        @if ($titulo !== 'SIN TITULO')
                                            <!-- Fila del título -->
                                            <tr class="titulo-row">
                                                <td colspan="22" style="border-left: 2px solid black; border-right: 2px solid black;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        {{ $titulo }}
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $contadorFilasPagina++; @endphp
                                        @endif

                                        @foreach ($grupo['resultados'] as $junta)
                                            @php
                                                $contadorFilas++;
                                                $contadorFilasPagina++;
                                                $totalMetros += floatval($junta['observaciones']);
                                                $esUltimaFila = $loop->last;
                                            @endphp
                                            <tr class="juntas" class="lineaInferior">
                                                <td style="border-left: 2px solid black; @if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $contador }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['no_junta'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['lado_a'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['lado_b'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['diametro'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['no_indicacion'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['tipo_indicacion'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Ang'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Gdb'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['nr'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['ni'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['x'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['y'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['horario_tecnico'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['no_pierna'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['s'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['l'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['d'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['tmin'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['evaluacion'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['fotos'] }}</td>
                                                <td style="border-right: 2px solid black;@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['observaciones'] }}</td> 
                                            </tr>

                                                @if ($contadorFilas % $filasPorPagina === 0)
                                                    <!-- Fila de total antes del salto de página -->
                                                    <tr style="page-break-after: always; font-size: 6px !important;" class="sinBordetd">
                                                        <td colspan="16" style="border-top: 2px solid black;"></td>
                                                        <th colspan="5" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong style="font-size: 6px !important;">Longitud inspeccionada1:</strong></th>
                                                        <th style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m</th>
                                                    </tr>

                                                    @php
                                                        $totalMetros = 0; // Reinicia el acumulador para la siguiente página
                                                    @endphp

                                                @endif
                                            @php $contador++; @endphp
                                        @endforeach

                                            @if ($contadorFilasPagina + $filasDelGrupo > $filasPorPagina && $titulo != 'SIN TITULO') //detectar si todo el grupo no cabe en la página, y si es así, el título anterior es el último de esa página.  
                                            <!-- Salto de página porque no cabe el grupo completo -->
                                                <tr style="page-break-after: always; font-size: 6px !important;" class="sinBordetd">
                                                    <td colspan="16" style="border-top: 2px solid black;"></td>
                                                    <th colspan="5" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong style="font-size: 6px !important;">Longitud inspeccionada2:</strong></th>
                                                    <th style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m</th>
                                                </tr>
                                                @php
                                                    $contadorFilasPagina = 0;
                                                    $totalMetros = 0;
                                                @endphp
                                            @endif
                                    @endforeach
                                    
                                    @if($titulo == 'SIN TITULO' && $totalTitulosYFilas <>15 || $titulo != 'SIN TITULO')
                                    <!-- Total al final si no se llenó la última página -->
                                        @if ($contadorFilasPagina > 0)
                                            <tr style="page-break-after: always; font-size: 6px !important;" class="sinBordetd">
                                                <td colspan="16" style="border-top: 2px solid black;"></td>
                                                <th colspan="5" style="border-right: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;"><strong font-size: 6px !important;>Longitud inspeccionada3:</strong></th>
                                                <th style="border-right: 2px solid black; border-left: 1px solid black; border-bottom: 2px solid black;">{{ number_format($totalMetros, 2) }} m</th>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                    </table>
            </div>

        </body>
    </html>