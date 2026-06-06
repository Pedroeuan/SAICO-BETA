<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-PINS-22/01</title>
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
                    top: -45px; /* Ajusta para que no interfiera con el margen de la página */
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
                    padding-bottom: 0; /* Altura del footer */
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
                    font-size: 5px;
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

        .datosEquipos{
            border-collapse: separate;  /*separate No colapsar bordes */
            border-spacing: 0px;        /* Espacio entre celdas */
            width: 100%;
            text-align: center;
            font-size: 6px;
        }

        .datosEquipos td, .datosEquipos th {
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
                            <th rowspan="3" style="width:80%; padding:0; margin:0;">

                                <div style="
                                    width:100%;
                                    height:7.2%;
                                    text-align:center;
                                    vertical-align:middle;
                                    padding:0;
                                    margin:0;
                                ">
                                    @if(!empty($QR_PDF))
                                    <img
                                        src="{{ $QR_PDF }}"
                                        alt="QR"
                                        style="
                                            width:50px;
                                            height:50px;
                                            display:block;
                                            margin:auto;
                                            padding:0;
                                        "
                                    >
                                    @endif
                                </div>

                            </th>
                            <th style="width: 60%;">Código:</th>
                            <th style="width: 80%;">FOR-PINS-22/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;">INFORME DE  INSPECCIÓN DE TUBERIA POR CORREINTES EDDY</th>
                            <th>Versión</th>
                            <th>0</th>
                        </tr>
                        <tr>
                            <th>Fecha de elaboración</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
    
                <div style="margin-bottom: 4px;"></div>

            </header>

            <footer> 

                    <div style="margin-bottom: 8px;"></div>

                        <table class="datosgenerales" style="width:100%;">
                            <tr>
                                <td style="width: 43%; vertical-align: top;">
                                    <table class="simbologia">
                                        <thead>
                                            <tr>
                                                <th colspan="4" class="celdaAmarillo" style="font-size:8px;">INDICACIONES Y HALLAZGOS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>NPIR:</strong></td>
                                                <td style="text-align:left;">NO PRESENTA INDICACIONES RELEVANTES</td>
                                                <td><strong>CI:</strong></td>
                                                <td style="text-align:left;">CORROSIÓN INTERNA</td>
                                            </tr>
                                            <tr>
                                                <td><strong>I:</strong></td>
                                                <td style="text-align:left;">INCLUSIÓN NO METÁLICA</td>
                                                <td><strong>L:</strong></td>
                                                <td style="text-align:left;">LAMINACIÓN</td>
                                            </tr>
                                            <tr>
                                                <td><strong>ZI:</strong></td>
                                                <td style="text-align:left;">ZONA DE INCLUSIONES NO METÁLICAS</td>
                                                <td colspan="2" rowspan="2"></td>
                                            </tr>
                                            <tr>
                                                <td><strong>LE:</strong></td>
                                                <td style="text-align:left;">LAMINACIÓN ESCALONADA</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="width: 4%; border:0 !important;"></td>
                                <td style="width: 54%; vertical-align: top;">
                                    <table class="simbologia">
                                        <thead>
                                            <tr>
                                                <th colspan="6" class="celdaAmarillo" style="font-size:8px;">SIMBOLOGÍA DEL REPORTE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>A:</strong></td>
                                                <td style="text-align:left;">ÁNGULO (°)</td>
                                                <td><strong>LA:</strong></td>
                                                <td style="text-align:left;">LONGITUD AXIAL (IN)</td>
                                                <td rowspan="2"><strong>t<sub>a</sub></strong></td>
                                                <td rowspan="2" style="text-align:left;">ESPESOR DE LA PARED EN ZONA<br>SANA ADYACENTE</td>
                                            </tr>
                                            <tr>
                                                <td><strong>G:</strong></td>
                                                <td style="text-align:left;">GANANCIA (dB)</td>
                                                <td><strong>LC</strong></td>
                                                <td style="text-align:left;">LONGITUD CIRCUNFERENCIAL (IN)</td>
                                            </tr>
                                            <tr>
                                                <td><strong>NR:</strong></td>
                                                <td style="text-align:left;">NIVEL DE REFERENCIA (%)</td>
                                                <td><strong>DNR</strong></td>
                                                <td style="text-align:left;">DISTANCIA DE NIVEL DE REFERENCIA (IN)</td>
                                                <td><strong>H.T.</strong></td>
                                                <td style="text-align:left;">HORARIO TÉCNICO</td>                                     
                                            </tr>
                                            <tr>
                                                <td><strong>NI:</strong></td>
                                                <td style="text-align:left;">NIVEL DE INDICACIÓN (%)</td>
                                                <td><strong>t<sub>min</sub></strong></td>
                                                <td style="text-align:left;">ESPESOR MÍNIMO REGISTRADO (PULG)</td>
                                                <td><strong>d</strong></td>
                                                <td style="text-align:left;">PROFUNDIDAD DE LA INDICACION(IN)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 814px;">{{ $Datos_Equipo['OBS'] ?? '' }}</td>                           
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
            
            @foreach ($Grupo_Juntas_Detalles_Re as $bloque)
            <div class="content">
                <table class="datosgenerales">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="4">DATOS GENERALES</th></tr>
                    </thead>
                    <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead>
                    <tbody>
                        <tr>
                            <th style="width: 12%; text-align:left;">FECHA:</th>
                            <td class="lineaInferior" style="width: 55%;">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
                            <th style="width: 14%; text-align:left;">NO. REPORTE:</th>
                            <td class="lineaInferior" style="width: 19%;">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">CLIENTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
                            <th style="text-align:left;">CONTRATO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">IDENTIFICACIÓN:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Identificacion'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">SERIE:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Serie'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">FABRICANTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Fabricante'] ?? '' }}</td>
                            <th style="text-align:left;">NÚMERO DE TUBOS:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Numero_Tubos'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">DIAMETRO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Diametro'] ?? '' }}</td>
                            <th style="text-align:left;">LONGITUD:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Longitud'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">LUGAR:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Lugar'] ?? '' }}</td>
                            <th style="text-align:left;">AÑO DE FABRICACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Año_Fabricacion'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">PIEZA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Pieza'] ?? '' }}</td>
                            <th style="text-align:left;">MATERIAL:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Material'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
                            <th style="text-align:left;">CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosEquipos">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="9">DATOS DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="9"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO</th>
                            <th colspan="4">BOBINA</th>
                            <th colspan="2">BLOCK DE REFERENCIA</th>
                            <th>CERTIFICADO DE CALIBRACIÓN DEL EQUIPO</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_BP'] ?? '' }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_BLOCK'] ?? '' }}</td>
                            <td>{{ $Datos_Equipo['CER_CALIBRACION'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td colspan="3">{{ $Datos_Equipo['MODELO_BP'] ?? '' }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $Datos_Equipo['MODELO_BLOCK'] ?? '' }}</td>
                            <th class="celdaGris" style="width: 100px;">VIGENCIA DE CALIBACIÓN DEL EQUIPO</th>
                        </tr>
                        <tr>
                            <th class="celdaGris">N.S. </th>
                            <td>{{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}</td>
                            <th class="celdaGris">N.S. </th>
                            <td style="width: 60px;">{{ $Datos_Equipo['NS_BP'] ?? '' }}</td>
                            <th class="celdaGris" style="width: 50px;">FRECC:</th>
                            <td style="width: 50px;">{{ $Datos_Equipo['FREC_BP'] ?? '' }}</td>
                            <th class="celdaGris">N.S. </th>
                            <td>{{ $Datos_Equipo['NS_BLOCK'] ?? '' }}</td>
                            <td>{{ $Datos_Equipo['VIG_CALIBRACION'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>


                <div style="margin-bottom: 5px;"></div>

                    <table class="datosresultados">
                        <thead class="encabezadoAzul">
                            <tr><th colspan="14">RESULTADOS</th></tr>
                        </thead>
                        <thead><tr class="sinBordeth"><th colspan="14"></th></tr></thead>
                        <thead>
                            <tr class="celdaGris">
                                <th>ID</th>
                                <th>Elemento</th>
                                <th>Referencia (mm)</th>
                                <th>Fase °</th>
                                <th>Ganancia</th>
                                <th>Alcance de Inspección</th>
                                <th>Canal</th>
                                <th>%Insp.</th>
                                <th>%Obstr.</th>
                                <th>Fila</th>
                                <th>Columna</th>
                                <th>Tipo de Alarma</th>
                                <th>Longitud de la Franja</th>
                                <th>Observaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bloque as $item)
                                @if (!is_array($item))
                                    @continue
                                @endif

                                @if (($item['tipo'] ?? null) == 'titulo')
                                    <tr class="titulo-row">
                                        <td colspan="14" style="border:.5px solid black;">
                                            {{ $item['texto'] }}
                                        </td>
                                    </tr>
                                @endif

                                @if (($item['tipo'] ?? null) == 'fila')
                                    <tr class="juntas">
                                        <td>{{ $item['data']['ID'] ?? '' }}</td>
                                        <td>{{ $item['data']['elemento'] ?? '' }}</td>
                                        <td>{{ $item['data']['referencia'] ?? '' }}</td>
                                        <td>{{ $item['data']['fase'] ?? '' }}</td>
                                        <td>{{ $item['data']['ganancia'] ?? '' }}</td>
                                        <td>{{ $item['data']['alcanse_inspeccion'] ?? '' }}</td>
                                        <td>{{ $item['data']['canal'] ?? '' }}</td>
                                        <td>{{ $item['data']['inspeccion'] ?? '' }}</td>
                                        <td>{{ $item['data']['obstruccion'] ?? '' }}</td>
                                        <td>{{ $item['data']['fila'] ?? '' }}</td>
                                        <td>{{ $item['data']['columna'] ?? '' }}</td>
                                        <td>{{ $item['data']['tipo_alarma'] ?? '' }}</td>
                                        <td>{{ $item['data']['longitud_franja'] ?? '' }}</td>
                                        <td>{{ $item['data']['observaciones'] ?? '' }}</td>
                                    </tr>
                                @endif

                                @if (($item['tipo'] ?? null) == 'longitud')
                                    <tr class="sinBordetd">
                                        <td colspan="10"></td>
                                        <th colspan="3" style="font-size:8px;">Numeros de tubos inspeccionados:</th>
                                        <th style="font-size:8px;">{{ $item['valor'] ?? '' }} tubos</th>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    @if (!$loop->last)
                        <div style="page-break-after: always;"></div>
                    @endif
            @endforeach
        </body>
    </html>
