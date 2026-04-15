<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-22/01</title>
            @php
                $dg = $Detalles_Generales ?? [];
                $de = $Datos_Equipo ?? [];
                $grupos = $Grupo_Juntas_Detalles_Re ?? [];
                $tipoFluido = $dg['Tipo_Flu'] ?? $dg['Tip_Flu'] ?? '';
                $procedimientoGeneral = $dg['Procedimiento'] ?? '';
                $observacionesEquipo = $de['Observaciones'] ?? '';
            @endphp
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
                    top: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px;
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin-top: 35px;
                    padding-top: 0;
                    padding-bottom: 95px;
                    font-family: 'arial', sans-serif;
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
                    font-size: 5px;
                }

                .simbologia td, .simbologia th {
                    border: .5px solid black; 
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
            font-size: 7cap;
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
        .content{
            margin-top: 6px; /* ajusta el valor según lo que necesites */
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
                            <th style="width: 80%;">FOR-INS-22/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE  INSPECCIÓN ULTRASÓNICA CON EL METODO DE ONDAS GUIADAS </th>
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
                    <table>                               
                        <tr>                                     
                            <th class="datosgenerales" >OBSERVACIONES:</th>                                         
                            <td class="lineaInferior" style="width: 814px;">{{ $observacionesEquipo }}</td>                            
                        </tr>  
                    </table>

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
                    </table>
            </footer>

        @foreach ($Grupo_Juntas_Detalles_Re as $grupo)
            <div class="content">
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
                            <td class="lineaInferior">{{ $dg['Fecha'] ?? '' }}</td>
                            <th style="width: 15%;">NO. REPORTE:</th>
                            <td class="lineaInferior">{{ $dg['No_Reporte'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>CLIENTE:</th>
                            <td class="lineaInferior">{{ $dg['Cliente'] ?? '' }}</td>
                            <th>CONTRATO:</th>
                            <td class="lineaInferior">{{ $dg['Contrato'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>PROYECTO:</th>
                            <td class="lineaInferior" colspan="3">{{ $dg['Proyecto'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>ORDEN DE TRABAJO:</th>
                            <td class="lineaInferior" colspan="3">{{ $dg['Orden_Trabajo'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>FOLIO:</th>
                            <td class="lineaInferior">{{ $dg['Folio'] ?? '' }}</td>
                            <th>TIPO DE FLUIDO:</th>
                            <td class="lineaInferior">{{ $tipoFluido }}</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior">{{ $dg['Partida'] ?? '' }}</td>
                            <th>TEMPERATURA DE OPERACI&Oacute;N:</th>
                            <td class="lineaInferior">{{ $dg['Temp_Op'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior">{{ $dg['Lugar'] ?? '' }}</td>
                            <th>ESPESOR NOMINAL / C&Eacute;DULA:</th>
                            <td class="lineaInferior">{{ $dg['Esp_Ced'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>TUBER&Iacute;A / UDC / ISOM&Eacute;TRICO / PLANO:</th>
                            <td class="lineaInferior">{{ $dg['Isometrico_Plano'] ?? '' }}</td>
                            <th>MATERIAL:</th>
                            <td class="lineaInferior">{{ $dg['Material'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $procedimientoGeneral }}</td>
                            <th>ESPESOR DI&Aacute;METRO NOMINAL NPS:</th>
                            <td class="lineaInferior">{{ $dg['Dia_NPS'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="7">DATOS DEL EQUIPO</th></tr>
                    </thead>

                    <thead><tr class="sinBordeth"><th colspan="7"></th></tr></thead>

                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO DE ONDAS GUIADAS</th>
                            <th colspan="2">ANILLO TRANSDUCTOR 1</th>
                            <th colspan="2">ANILLO TRANSDUCTOR 2</th>
                            <th>N&Uacute;MERO DE M&Oacute;DULOS:</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 80px;">{{ $de['MARCA_EQUIPO'] ?? '' }}</td>
                            <th class="celdaGris" style="width: 10px;">DI&Aacute;METRO PULG:</th>
                            <td style="width: 80px;">{{ $de['DIAMETRO_PULG'] ?? '' }}</td>
                            <th class="celdaGris" style="width: 60px;">DI&Aacute;METRO PULG:</th>
                            <td style="width: 100px;">{{ $de['DIAMETRO_AN2'] ?? '' }}</td>
                            <td style="width: 100px;">{{ $de['Num_Mode'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $de['MODELO_EQUIPO'] ?? '' }}</td>
                            <th class="celdaGris">MARCA:</th>
                            <td>{{ $de['MARCA_AN1'] ?? '' }}</td>
                            <th class="celdaGris">MODELO:</th>
                            <td>{{ $de['MODELO_AN2'] ?? '' }}</td>
                            <th class="celdaGris" style="width: 100px;">N&Uacute;MERO DE TRANSDUCTORES</th>
                        </tr>
                        <tr>
                            <th class="celdaGris">N.S:</th>
                            <td>{{ $de['NS_EQUIPO'] ?? '' }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td style="width: 60px;">{{ $de['NS_AN1'] ?? '' }}</td>
                            <th class="celdaGris">N.S:</th>
                            <td style="width: 60px;">{{ $de['NS_AN2'] ?? '' }}</td>
                            <td>{{ $de['NUM_TRANS'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosgenerales">
                    <thead class="encabezadoAzul">
                        <tr><th colspan="8">DATOS DE LA INSPECCI&Oacute;N</th></tr>
                    </thead>

                    <thead><tr class="sinBordeth"><th colspan="8"></th></tr></thead>

                    <tbody>
                        <tr>
                            <th style="width: 15%;">FRECUENCIA:</th>
                            <td class="lineaInferior">{{ $de['Frecuencia'] ?? '' }}</td>
                            <th style="width: 15%;">ORIENTACI&Oacute;N DE LA TUBER&Iacute;A:</th>
                            <td class="lineaInferior">{{ $de['Ori_Tube'] ?? '' }}</td>
                            <th style="width: 15%;" colspan="2">REFERENCIA DE LA POSICI&Oacute;N DEL ANILLO:</th>
                            <td class="lineaInferior" colspan="2">{{ $de['Ref_An'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 15%;">MODO DE ONDA:</th>
                            <td class="lineaInferior">{{ $de['Mod_Onda'] ?? '' }}</td>
                            <th style="width: 15%;">DIRECCI&Oacute;N DEL DISPARO:</th>
                            <td class="lineaInferior">{{ $de['Dir_Dis'] ?? '' }}</td>
                            <th style="width: 15%;" colspan="2">DISTANCIA DE POSICI&Oacute;N DEL ANILLO:</th>
                            <td class="lineaInferior" colspan="2">{{ $de['Dm_An'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <th style="width: 15%;">PRESI&Oacute;N DE OPERACI&Oacute;N DEL ANILLO:</th>
                            <td class="lineaInferior">{{ $de['Psi_an'] ?? '' }}</td>
                            <th style="width: 15%;">TIPO DE RECUBRIMIENTO:</th>
                            <td class="lineaInferior">{{ $de['Tip_Rec'] ?? '' }}</td>
                            <th style="width: 15%;">&Aacute;NGULO DE ORIENTACI&Oacute;N DEL ANILLO:</th>
                            <td class="lineaInferior">{{ $de['Ang_An'] ?? '' }}</td>
                            <th style="width: 15%;">COORDENADAS GPS:</th>
                            <td class="lineaInferior">{{ $de['Coor_GPS'] ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                    <table class="datosresultados">

                        <thead class="encabezadoAzul">
                            <tr><th colspan="18">RESULTADOS</th></tr>
                        </thead>

                            <thead><tr class="sinBordeth"><th colspan="18"></th></tr></thead> <!-- Fila vacia -->

                                <thead>
                                    <tr class="celdaGrisResultados">
                                        <th rowspan="2" style="border: 1px solid black; border-left: 2px solid black; border-top: 2px solid black;">ID</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">Elementos</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">Ønom (pulg)</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">Øext (pulg)</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">Long. (m)</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">Elementos idendificados</th>
                                        <th colspan="2" style="border: 1px solid black; border-top: 2px solid black;">Distancia del disparo (m)</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">No. Ind.</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">Distancia relativa al dato (m)</th>
                                        <th rowspan="2" colspan="2" style="border: 1px solid black; border-top: 2px solid black;">Horario Técnico</th>
                                        <th colspan="3" style="border: 1px solid black; border-top: 2px solid black;">Clasificación de la indicación o anomalía</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">porcentaje de reflexión (%)</th>
                                        <th rowspan="2" style="border: 1px solid black; border-top: 2px solid black;">Fotos No.</th>
                                        <th rowspan="2" style="width: 10px; border: 1px solid black; border-right: 2px solid black; border-top: 1px solid black; border-bottom: 1px solid black;">Observaciones</th>
                                    </tr>
                                    <tr class="celdaGrisResultados">
                                        <th style="width: 25px; border: 1px solid black;">(-X)</th>
                                        <th style="width: 25px; border: 1px solid black;">(+X)</th>
                                        <th style="width: 20px; border: 1px solid black;">Categoría</th>
                                        <th style="width: 20px; border: 1px solid black;"> Direccionalidad</th>
                                        <th style="width: 15px; border: 1px solid black;">Clasificación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- TÍTULO --}}
                                @if (!str_starts_with($grupo['titulos_juntas'], 'SIN TITULO'))
                                    <tr class="titulo-row">
                                        <td colspan="14" style="border:.5px solid black;">
                                            {{ $grupo['titulos_juntas'] }}
                                        </td>
                                    </tr>
                                @endif

                                {{-- FILAS DEL BLOQUE --}}
                                @foreach ($grupo['resultados'] as $junta)
                                            <tr class="juntas">
                                                <td>{{ $junta['ID'] }}</td>
                                                <td>{{ $junta['Elemento'] }}</td>
                                                <td>{{ $junta['nom_pulg'] }}</td>
                                                <td>{{ $junta['ext_pulg'] }}</td>
                                                <td>{{ $junta['Long_m'] }}</td>
                                                <td>{{ $junta['Ele_iden'] }}</td>
                                                <td>{{ $junta['-X'] }}</td>
                                                <td>{{ $junta['+X'] }}</td>
                                                <td>{{ $junta['No_Ind'] }}</td>
                                                <td>{{ $junta['Dis_rela'] }}</td>
                                                <td>{{ $junta['HT1'] }}</td>
                                                <td>{{ $junta['HT2'] }}</td>
                                                <td>{{ $junta['Cate'] }}</td>
                                                <td>{{ $junta['Direc'] }}</td>
                                                <td>{{ $junta['Clas'] }}</td>
                                                <td>{{ $junta['Porc_Refl'] }}</td>
                                                <td>{{ $junta['Fotos'] }}</td>
                                                <td>{{ $junta['Observaciones'] }}</td>
                                            </tr>
                                    @endforeach

                                {{-- 🔹 LONGITUD INSPECCIONADA --}}
                                <tr class="">
                                    <th colspan="15">Longitud inspeccionada:</th>
                                    <th colspan="3">
                                        {{ $grupo['Long_Inspecc'][0] ?? '---' }} m
                                    </th>
                                </tr>

                                {{-- 🔹 SALTO DE PÁGINA POR BLOQUE 
                                <tr style="page-break-after: always;" class="sinBordetd">
                                    <td colspan="18"></td>
                                </tr>--}}
                            </tbody>
                    </table>

                <table style="margin: auto; border: 0px solid black;">
                    <tr>
                        <td>
                            <table class="simbologia" style="border: 1px solid black;">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="celdaAmarillo">SIMBOLOGÍA DEL REPORTE</th>
                                    </tr>

                                    <tr>
                                        <td><strong>PE</strong></td>
                                        <td>FIN DE TUBERIA</td>
                                        <td><strong>V</strong></td>
                                        <td>SOLDADURA CIRCUNFERENCIAL</td>
                                        <td><strong>BV</strong></td>
                                        <td>SOLDADURA DE CODO</td>
                                    </tr>

                                    <tr>
                                        <td><strong>HC</strong></td>
                                        <td>ABRAZADERA DE SUJECIÓN</td>
                                        <td><strong>C1</strong></td>
                                        <td>ANOMALÍA O IND. CATEGORIA 1</td>
                                        <td><strong>H.T.</strong></td>
                                        <td>HORARIO TÉCNICO</td>
                                    </tr>

                                    <tr>
                                        <td><strong>SB</strong></td>
                                        <td>RAMAL</td>
                                        <td><strong>C2</strong></td>
                                        <td>ANOMALÍA O IND. CATEGORIA 2</td>
                                        <td><strong>-X</strong></td>
                                        <td>DISTANCIA NEGATIVA</td>
                                    </tr>

                                    <tr>
                                        <td><strong>IND</strong></td>
                                        <td>INDICACIÓN</td>
                                        <td><strong>C3</strong></td>
                                        <td>ANOMALÍA O IND. CATEGORIA 3</td>
                                        <td><strong>+X</strong></td>
                                        <td>DISTANCIA POSITIVA</td>
                                    </tr>

                                </thead>

                            </table>
                        </td>
                        
                        <td>
                            <table>
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
                                </thead>
                            </table>
                        </td>

                        <td>
                        <table class="simbologia" style="border: 0px solid black;">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="celdaAmarillo">RELACIÓN ENTRE ÁNGULOS DE LA DIRECCIONALIDAD Y HORARIOS TÉCNICOS</th>
                                    </tr>

                                    <tr>
                                        <td><strong>ÁNGULO</strong></td>
                                        <td>H.T.</td>
                                        <td><strong>ÁNGULO</strong></td>
                                        <td>H.T.</td>
                                        <td><strong>ÁNGULO</strong></td>
                                        <td>H.T.</td>
                                        <td><strong>ÁNGULO</strong></td>
                                        <td>H.T.</td>
                                    </tr>

                                    <tr>
                                        <td><strong>0°</strong></td>
                                        <td>12:00</td>
                                        <td><strong>90°</strong></td>
                                        <td>03:00</td>
                                        <td><strong>180°</strong></td>
                                        <td>06:00</td>
                                        <td><strong>270°</strong></td>
                                        <td>09:00</td>
                                    </tr>

                                    <tr>
                                        <td><strong>30°</strong></td>
                                        <td>01:00</td>
                                        <td><strong>120°</strong></td>
                                        <td>04:00</td>
                                        <td><strong>210°</strong></td>
                                        <td>07:00</td>
                                        <td><strong>300°</strong></td>
                                        <td>10:00</td>
                                    </tr>

                                    <tr>
                                        <td><strong>45°</strong></td>
                                        <td>01:30</td>
                                        <td><strong>135°</strong></td>
                                        <td>04:30</td>
                                        <td><strong>225°</strong></td>
                                        <td>07:30</td>
                                        <td><strong>315°</strong></td>
                                        <td>10:30</td>
                                    </tr>

                                    <tr>
                                        <td><strong>60°</strong></td>
                                        <td>02:00</td>
                                        <td><strong>150°</strong></td>
                                        <td>05:00</td>
                                        <td><strong>240°</strong></td>
                                        <td>08:00</td>
                                        <td><strong>330°</strong></td>
                                        <td>11:00</td>
                                    </tr>

                                </thead>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            
            </div>
            @if (!$loop->last)
                        <div style="page-break-after: always;"></div>
                    @endif
            @endforeach

        </body>
    </html>
