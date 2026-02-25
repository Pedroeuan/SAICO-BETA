<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-10/01</title>
            <style>
                @page {
                    margin: 
                    3.0cm /* superior */
                    2.1cm /* derecho */
                    2.1cm /* inferior */
                    2.4cm; /* izquierdo */
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
                    bottom: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    /*background-color: rgb(7, 231, 18);*/
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin-top: 35px; /* Ajusta para que el contenido no se sobreponga al header */
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
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 10px;
        }

        .datosresultados td, .datosresultados th {
            border: .1px solid black; 
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
                            <th style="width: 80%;">FOR-INS-10/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">INFORME DE  INSPECCIÓN ULTRASÓNICA CON HAZ RECTO PARA METAL BASE </th>
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

                    <table class="datosgenerales">                                
                        <tr>                                     
                            <th>OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 805px;">{{ $Datos_Equipo['Observaciones'] }}</td>                            
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
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                                    <td style="width: 30px;"></td>
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
                                    <td style="width: 10px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo1'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo2'] }}</th>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Vobo3'] }}</th>
                                    <td style="width: 30px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
                                    <td></td>
                                    <td style="width: 190px; height:40px" class="lineaInferior"></td>
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
                        <tr><th colspan="9">DATOS DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="9"></th></tr></thead> <!-- Fila vacia -->
                
                    <tbody>
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
                            <td colspan="3">{{ $Datos_Equipo['MARCA_TRANSDUCTOR'] }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_BLOCK'] }}</td>
                            <td class="" style="width: 100px;">{{ $Datos_Equipo['ACOPLANTE'] }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{ $Datos_Equipo['MODELO_TRANSDUCTOR'] }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] }}</td>
                            <th class="celdaGris" style="width: 100px;">LONGITUD DEL CABLE</th>
                        </tr>
                        <tr>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $Datos_Equipo['N_S_EQUIPO'] }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td style="width: 60px;">{{ $Datos_Equipo['N_S_TRANSDUCTOR'] }}</td>
                            <th class="celdaGris" style="width: 50px;">FREC:</th>
                            <td style="width: 50px;">{{ $Datos_Equipo['FREC_TRANSDUCTOR'] }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $Datos_Equipo['N_S_BLOCK'] }}</td>
                            <td>{{ $Datos_Equipo['LONGITUD_CABLE'] }}</td>
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
                            <td class="lineaInferior">{{ $Datos_Equipo['PRES_OPE'] }}</td>
                            <th style="width: 15%;">PRESIÓN MÁXIMA DE OPERACIÓN:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['PRES_MAX_OPE'] }}</td>
                            <th style="width: 20%;">TEMPERATURA MAX. DE OPERACION:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['TEMP_MAX_OPE'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">CONDICION SUPERFICIAL:</th>
                            <td class="lineaInferior" colspan="2">{{ $Datos_Equipo['COND_SUPER'] }}</td>
                            <th style="width: 15%;">ESTADO DE PINTURA:</th>
                            <td class="lineaInferior" colspan="2">{{ $Datos_Equipo['EST_PINT'] }}</td>
                        </tr>

                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                    <table class="datosresultados">

                        <thead class="encabezadoAzul">
                            <tr><th colspan="21">RESULTADOS</th></tr>
                        </thead>

                            <thead><tr class="sinBordeth"><th colspan="20"></th></tr></thead> <!-- Fila vacia -->

                                <thead>
                                <tr class="celdaGris">
                                    <th style="width: 5px;">ID</th>
                                    <th style="width: 20px;">Elemento</th>
                                    <th style="width: 20px;">Nivel</th>
                                    <th style="width: 20px;">Ønom</th>
                                    <th style="width: 20px;">Øext</th>
                                    <th style="width: 20px;">No.Ind.</th>
                                    <th style="width: 20px;">Tipo de Indicación</th>
                                    <th style="width: 20px;">G (dB)</th>
                                    <th style="width: 20px;">NR (%)</th>
                                    <th style="width: 20px;">NI (%)</th>
                                    <th style="width: 20px;">DNR</th>
                                    <th style="width: 20px;">Horario Técnico</th>
                                    <th style="width: 20px;">S.C. ó Lado de Referencia</th>
                                    <th style="width: 20px;">LA</th>
                                    <th style="width: 20px;">LC</th>
                                    <th style="width: 20px;">tmin</th>
                                    <th style="width: 20px;">d</th>
                                    <th style="width: 20px;">ta</th>
                                    <th style="width: 25px;">Perdida de Material (%)</th>
                                    <th style="width: 20px;">Fotos No.</th>
                                    <th style="width: 5px;">Observaciones</th>
                                </tr>
                            </thead>

                                <tbody>
                                
                                {{-- 🔹 TÍTULO --}}
                                @if (!str_starts_with($grupo['titulos_juntas'], 'SIN TITULO'))
                                    <tr class="titulo-row">
                                        <td colspan="21" style="border:.5px solid black;">
                                            {{ $grupo['titulos_juntas'] }}
                                        </td>
                                    </tr>
                                @endif

                                {{-- 🔹 FILAS DEL BLOQUE --}}
                                @foreach ($grupo['resultados'] as $junta)
                                    <tr class="juntas">
                                        
                                        <td>{{ $junta['ID'] }}</td>
                                        <td>{{ $junta['Elemento'] }}</td>
                                        <td>{{ $junta['Nivel'] }}</td>
                                        <td>{{ $junta['nom'] }}</td>
                                        <td>{{ $junta['ext'] }}</td>
                                        <td>{{ $junta['no_ind'] }}</td>
                                        <td>{{ $junta['Tipo_ind'] }}</td>
                                        <td>{{ $junta['G'] }}</td>
                                        <td>{{ $junta['NR'] }}</td>
                                        <td>{{ $junta['NI'] }}</td>
                                        <td>{{ $junta['DNR'] }}</td>
                                        <td>{{ $junta['Hora_Tec'] }}</td>
                                        <td>{{ $junta['sc'] }}</td>
                                        <td>{{ $junta['la'] }}</td>
                                        <td>{{ $junta['lc'] }}</td>
                                        <td>{{ $junta['tmin'] }}</td>
                                        <td>{{ $junta['d'] }}</td>
                                        <td>{{ $junta['ta'] }}</td>
                                        <td>{{ $junta['Perd_Mate'] }}</td>
                                        <td>{{ $junta['fotos'] }}</td>
                                        <td>{{ $junta['Observaciones'] }}</td>

                                    </tr>
                                @endforeach

                                {{-- 🔹 LONGITUD INSPECCIONADA --}}
                                <tr class="sinBordetd">
                                    <td colspan="14">
                                    </td>
                                    <th colspan="5">Longitud inspeccionada:</th>
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