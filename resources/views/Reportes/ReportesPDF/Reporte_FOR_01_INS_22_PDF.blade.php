<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-15/03</title>
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
                    padding-top: 235px; /* Altura del header */
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
                            <th style="width: 80%;">FOR-INS-15/03</th>
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
                            <td class="lineaInferior">{{ $Detalles_Generales['Fecha'] }}</td>
                            <th style="width: 15%;">No. Reporte:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['No_Reporte'] }}</td>
                        </tr>
                        <tr>
                            <th>Cliente:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Cliente'] }}</td>
                            <th>Contrato:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Contrato'] }}</td>
                        </tr>
                        <tr>
                            <th>Proyecto:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Proyecto'] }}</td>
                        </tr>
                        <tr>
                            <th>Orden de Trabajo:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Orden_Trabajo'] }}</td>
                        </tr>
                        <tr>
                            <th>Folio:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Folio'] }}</td>
                            <th>Tipo de Fluido:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Tipo_de_Fluido'] }}</td>
                        </tr>
                        <tr>
                            <th>Partida:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Partida'] }}</td>
                            <th>Temperatura de Operación:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Temperatura_de_Operacion'] }}</td>
                        </tr>
                        <tr>
                            <th>Lugar:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Lugar'] }}</td>
                            <th>Espesor Nominal / Cedula:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Espesor_Nominal_Cedula'] }}</td>
                        </tr>
                        <tr>
                            <th>Tuberia / UDC / Isometrico / Plano:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Isometrico_Plano'] }}</td>
                            <th>Material:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Material'] }}</td>
                        </tr>
                        <tr>
                            <th>Procedimiento:</th>
                            <td class="lineaInferior"{{ $Detalles_Generales['Procedimiento'] }}></td>
                            <th>Espesor Diametro Nominal NPS:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Diametro_Nominal_NPS'] }}</td>
                        </tr>
                    </tbody>
                </table>


                
                <div style="margin-bottom: 5px;"></div>
        
                <table class="encabezadoAzul">
                    <tr>
                        <th colspan="4">DATOS DEL EQUIPO</th>
                    </tr>
                </table>   
                <div style="margin-bottom: 5px;"></div>         
                <table class="datosgenerales">
                    <tbody>
                        <tr>

                <table class="datosinspeccion">
                    <tbody>
                        <tr class="celdaGris">
                            <th colspan="2">EQUIPO DE ONDAS GUIADAS</th>
                            <th colspan="4">ANILLO TRANSDUCTOR 1</th>
                            <th colspan="2">ANILLO TRANSDUCTOR 2</th>
                            <th>NÚMERO DE TRANSDUCTORES:</th>
                        </tr>
                        <tr>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 100px;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td colspan="3">{{ $Datos_Equipo['MARCA_AN1'] }}</td>
                            <th class="celdaGris">NÚMERO DE MODULOS:</th>
                        </tr>
                            <th class="celdaGris">N.S.:</th>
                            <td>{{ $Datos_Equipo['NS_EQUIPO'] }}</td>
                            <th class="celdaGris">N.S.:</th>
                            <td style="width: 60px;">{{ $Datos_Equipo['NS_AN1'] }}</td>
                            <th class="celdaGris">N.S.:</th>
                            <td style="width: 60px;"><td>{{ $Datos_Equipo['NS_AN2'] }}</td>
                        </tr>
                </table>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>

                <table class="datosinspeccion">
                    <thead class="encabezadoAzul">
                        <th colspan="6">DATOS DE LA INSPECCIÓN</th>
                    </thead> 
                </table>

                <div style="margin-bottom: 2px;"></div>

                    <div style="margin-bottom: 4px;"></div>

                    <table class="datosinspeccionsinborde">
                        <tr>
                            <th style="width: 15%;">FRECUENCIA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Frecuencia'] }}</td>
                            <th style="width: 15%;">ORIENTACIÓN DE LA TUBERIA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Orientacion_de_la_Tuberia'] }}</td>
                            <th style="width: 15%;">REFERENCIA DE LA POSICIÓN DEL ANILLO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Referencia_de_la_Posicion_del_Anillo'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">MODO DE ONDA:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Modo_de_Onda'] }}</td>
                            <th style="width: 15%;">DIRECCIÓN DEL DISPARO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Direccion_del_Disparo'] }}</td>
                            <th style="width: 15%;">DISTANCIA DE POSICIÓN DEL ANILLO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Distancia_de_Posicion_del_Anillo'] }}</td>
                        </tr>

                        <tr>
                            <th style="width: 15%;">PRESIÓN DE OPERACIÓN DEL ANILLO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Presion_de_Operacion_del_anillo'] }}</td>
                            <th style="width: 15%;">TIPO DE RECUBRIMIENTO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Tipo_de_Recubrimiento'] }}</td>
                            <th style="width: 15%;">ANGULO DE ORIENTACIÓN DEL ANILLO:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Angulo_de_Orientacion_del_Anillo'] }}</td>
                            <th style="width: 15%;">COORDENADAS GPS:</th>
                            <td class="lineaInferior">{{ $Datos_Equipo['Coordenadas_GPS'] }}</td>
                        </tr>
                    </tbody>
                </table>




                    <table class="datosresultados">

                        <thead class="encabezadoAzul">
                            <tr><th colspan="25">RESULTADOS</th></tr>
                        </thead>
                            <thead><tr class="sinBordeth"><th colspan="35"></th></tr></thead> <!-- Fila vacia -->
                <div style="margin-bottom: 10px;"></div>

                    <table class="datosresultados">
                        <thead>
                            <tr class="celdaGris">
                                <th style="width: 10px; border: 1px solid black; border-left: 2px solid black; border-bottom: 2px solid black;">ID</th>
                                <th style="width: 50px; border: 1px solid black;">Elemento</th>
                                <th style="width: 60px; border: 1px solid black;">Ønom (pulg)</th>
                                <th style="width: 40px; border: 1px solid black;">Øext (pulg)</th>
                                <th style="width: 40px; border: 1px solid black;">Long. (m)</th>
                                <th style="width: 20px; border: 1px solid black;">Elementos idendificados</th>
                                <th style="width: 20px; border: 1px solid black;">Distacia del disparo (m)</th>
                                <th style="width: 20px; border: 1px solid black;">(-X)</th>
                                <th style="width: 20px; border: 1px solid black;">(+X)</th>
                                <th style="width: 20px; border: 1px solid black;">No. Ind.</th>
                                <th style="width: 20px; border: 1px solid black;">Distancia relativa al dato (m)</th>
                                <th style="width: 20px; border: 1px solid black;"><span style="font-size: 15px; position: relative; top: 3px;"><sup>t</sup></span% Horario Técnico</th>
                                <th style="width: 40px; border: 1px solid black;">Clasificación de la indicación o anomalía</th>
                                <th style="width: 40px; border: 1px solid black;">Categoría</th>
                                <th style="width: 40px; border: 1px solid black;">Direccionalidad</th>
                                <th style="width: 40px; border: 1px solid black;">Clasificación</th>
                                <th style="width: 40px; border: 1px solid black;">porcentaje de reflexión (%)</th>
                                <th style="width: 40px; border: 1px solid black;">Fotos No.</th>
                                <th style="width: 40px; border: 1px solid black;">Observaciones</th>
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
                                                <td colspan="14" style="border-left: 2px solid black; border-right: 2px solid black;">
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
                                                //$totalMetros += floatval($junta['Observaciones']);
                                                $esUltimaFila = $loop->last;
                                            @endphp
                                            <tr class="juntas">
                                                <td style="border-left: 2px solid black; @if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['ID'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Elemento'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Ønom_pulg'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Øext_pulg'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Long_m'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Elementos_idendificados'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['-X'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['+X'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['No_Ind'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Distancia_relativa_al_dato_m'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Horario_Tecnico'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Perdida'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Espesor_remanente'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Direccionalidad'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Clasificacion'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Observaciones'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['porcentaje_de_reflexion_'] }}</td>
                                                <td style="@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Fotos_No'] }}</td>
                                                <td style="border-right: 2px solid black;@if ($contadorFilas % $filasPorPagina === 0) border-bottom: 2px solid black; @elseif ($esUltimaFila) @if($titulo == 'SIN TITULO') border-bottom: 2px solid black; @endif @endif">{{ $junta['Observaciones'] }}</td>
                                            </tr>
                                            @php $contador++; @endphp
                                        @endforeach
                                    @endforeach
                            </tbody>
                    </table>
            </div>


        </body>
    </html>