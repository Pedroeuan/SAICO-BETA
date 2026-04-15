<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-INS-20/01</title>
            <style>
                @page {
                    margin: 
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
                    margin-top: 38px; /* Ajusta para que el contenido no se sobreponga al header */
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
                    font-size: 9px !important;
                    font-family: 'arial', sans-serif;
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

        /* ************** */
        .imagenes-reporte {
            margin-left: -15.6; /* Asegura que la tabla se alinee al margen izquierdo */
            width: 104%;
            border-collapse: separate;
            /*border-spacing: 20px; /* Espacio entre celdas */
            border-spacing: 25px 25px; /* 20px entre columnas, 0px entre filas */
            margin-bottom: 0;
            table-layout: fixed; /* Fija el ancho de las celdas */
        }

        .foto-container {
            padding: 0px; /* Asegura que la imagen toque el borde de la celda de izquierda- a(0) derecha+*/
            width: 270px;  /* Fija el ancho de la celda */
            height: 270px; /* Fija la altura de la celda */
            border: 1px solid black; 
            vertical-align: middle;
        }

        .foto-container img {
            /*object-fit: contain; /* Ajusta la imagen dentro del recuadro sin recortarla */
            object-fit: cover; /* Llenar el espacio sin distorsionar */
            width: 443px;  /* Ajusta el ancho de la celda */
            height: 272px; /* Ajusta la altura de la celda */
            vertical-align: middle;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Estilo para los comentarios */
        .comment { 
            border-top: 1px solid black; /* Borde superior de 2px en color negro */
            padding-top: 1px; /* Espaciado entre el borde y el texto */
            margin-top: 0px; /* Espacio entre la imagen y el comentario */
            height: 5px;
            text-align: center; /* Centrar el texto */
            /*font-size: 12px; /* Ajusta el tamaño de la fuente si es necesario */
            max-width: 100%; /* Para que el texto no desborde */
            word-wrap: break-word; /* Permite que el texto se ajuste */
        }
        /* Estilo para los "comentarios" en blanco */
        .empty-comment {
            margin-top: 170px;   /* Añade espacio entre las líneas cruzadas y el comentario */
            border-top: 1px solid black; /* Borde superior de 2px en color negro */
            padding-top: 42px; /* Espaciado entre el borde y el texto del comentario de las vacios*/
        }
        
        .empty-box {
            background-color:rgb(255, 255, 255); /* Color de fondo para los cuadros vacíos */
        }

        .cross-line {
            width: 74%;
            height: 0px; /* Ajusta según el tamaño de las imágenes */
            position: relative;
        }

        .cross-line::before,
        .cross-line::after {
            content: "";
            position: absolute;
            top: 84px; /* Ajusta esta propiedad para mover la línea hacia arriba o hacia abajo */
            left: -21px; /* Ajusta para alinear la línea */
            width: 152.5%; /* Aumenta el ancho de la línea */
            height: 100%;
            border-top: 2px solid black;
            transform: rotate(27deg); /* Ajusta el ángulo de la primera línea */
        }

        .cross-line::after {
            transform: rotate(-27deg);
        }
        .foto-container[colspan="2"] img {
            width: 100%;
            height:27%;
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
                            <th style="width: 80%;">FOR-INS-20/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 50%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 9pt;"> INFORME DE ANÁLISIS MEDIANTE CORRIENTE EDDY PULSADA (PECT) </th>
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

            @php
                    $chunks = [];
                    $grupoActual = [];
                    foreach ($Fotos as $foto) {
                        // Si la imagen es de hoja completa
                        if (!empty($foto['una_hoja']) && $foto['una_hoja'] == 1) {
                            // Guardar grupo previo (si existe)
                            if (!empty($grupoActual)) {
                                $chunks[] = $grupoActual;
                                $grupoActual = [];
                            }
                            // La imagen va SOLA
                            $chunks[] = [$foto];
                            continue;
                        }
                        // Imagen normal
                        $grupoActual[] = $foto;
                        if (count($grupoActual) == 2) {
                            $chunks[] = $grupoActual;
                            $grupoActual = [];
                        }
                    }
                    if (!empty($grupoActual)) {
                        $chunks[] = $grupoActual;
                    }
            @endphp
        @foreach($chunks as $fotosGrupo)
            <div class="content">
                <table class="datosgenerales" border="0">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="6">DATOS GENERALES</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="6"></th></tr></thead> <!-- Fila vacia -->

                    <tbody>
                        <tr>
                            <th style="width: 12%;">FECHA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Fecha'] }}</td>
                            <th style=""></th>
                            <td style=""></td>
                            <th style="width: 15%;">NO. REPORTE:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['No_Reporte'] }}</td>
                        </tr>
                        <tr>
                            <th>CLIENTE:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Cliente'] }}</td>
                            <th>CONTRATO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Contrato'] }}</td>
                        </tr>
                        <tr>
                            <th>PROYECTO: </th>
                            <td class="lineaInferior" colspan="5">{{ $Detalles_Generales['Proyecto'] }}</td>
                        </tr>
                        <tr>
                            <th>ORDEN DE TRABAJO:</th>
                            <td class="lineaInferior" colspan="5">{{ $Detalles_Generales['Orden_Trabajo'] }}</td>
                        </tr>
                        <tr>
                            <th>FOLIO:</th>
                            <td class="lineaInferior" colspan="2">{{ $Detalles_Generales['Folio'] }}</td>
                            <th style="width: 200px;">TIPO DE RECUBRIMIENTO O AISLAMIENTO:</th>
                            <td class="lineaInferior" colspan="2">{{ $Detalles_Generales['tip_ais'] }}</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior" colspan="5">{{ $Detalles_Generales['Partida'] }}</td>
                        </tr>
                        <tr>
                            <th>INSTALACIÓN:</th>
                            <td class="lineaInferior" colspan="2">{{ $Detalles_Generales['ins'] }}</td>
                            <th>No. ISOMETRICO:</th>
                            <td class="lineaInferior" colspan="2">{{ $Detalles_Generales['Isometrico_Plano'] }}</td>
                        </tr>
                        <tr>
                            <th>NOMBRE DE LA PIEZA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Nom_pz'] }}</td>
                            <th>MATERIAL:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Material'] }}</td>
                            <th>TRAZABILIDAD:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Trazabilidad'] }}</td>
                        </tr>
                        <tr>
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior" colspan="2">{{ $Detalles_Generales['Procedimiento'] }}</td>
                            <th>CRITERIO DE EVALUACIÓN:</th>
                            <td class="lineaInferior" colspan="2">{{ $Detalles_Generales['Criterio_Evaluacion'] }}</td>
                        </tr>
                        <tr>
                            <th>ACCESORIO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Accesorio'] }}</td>
                            <th>TUBERIA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Tuberia'] }}</td>
                            <th>ESTRUCTURAL:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Estructural'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 6px;"></div>

                <table class="datosinspeccion">

                    <thead class="encabezadoAzul">
                        <tr><th colspan="6">DATOS DEL EQUIPO</th></tr>
                    </thead>  

                    <thead><tr class="sinBordeth"><th colspan="6"></th></tr></thead> <!-- Fila vacia -->
                
                    <tbody>
                        <tr>
                            <th class="celdaGris" style="width: 60px;">MARCA:</th>
                            <td style="width: 60px;">{{ $Datos_Equipo['MARCA_EQUIPO'] }}</td>
                            <th class="celdaGris" style="width: 60px;">MODELO:</th>
                            <td style="width: 60px;">{{ $Datos_Equipo['MODELO_EQUIPO'] }}</td>
                            <th class="celdaGris" style="width: 60px;">NO. DE SERIE:</th>
                            <td style="width: 60px;">{{ $Datos_Equipo['NS_EQUIPO'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 4px;"></div>
                    @php
                        $esHojaCompleta = (
                            count($fotosGrupo) == 1 &&
                            !empty($fotosGrupo[0]['una_hoja']) &&
                            $fotosGrupo[0]['una_hoja'] == 1
                        );
                    @endphp

                    <table class="datosgenerales">
                        <thead class="encabezadoAzul">
                            <tr>            
                                <th>
                                    {{ $esHojaCompleta 
                                        ? 'SEÑAL DE REFERENCIA' 
                                        : 'MATRIZ DE DATOS OBTENIDA DE LA PIEZA' 
                                    }}
                                </th>
                            </tr>
                        </thead>  
                    </table>
                            <table class="imagenes-reporte">
                                <tr>
                                    @foreach($fotosGrupo as $index => $foto)
                                        {{-- Caso 1 imagen: ocupa toda la hoja 
                                        @if(!empty($foto['una_hoja']) && $foto['una_hoja'] == 1)
                                            <td class="foto-container foto-full" colspan="2">
                                                <img src="{{ $foto['path'] }}">
                                                <p class="comment">{{ $foto['comment'] }}</p>
                                            </td>--}}
                                        @if(!empty($foto['una_hoja']) && $foto['una_hoja'] == 1)
                                            </tr><tr>
                                            
                                            {{-- Imagen izquierda --}}
                                            <td class="foto-container">
                                                <img src="{{ $foto['path'] }}">
                                            </td>

                                            {{-- Comentario derecha --}}
                                            <td class="foto-container">
                                                <div class="">
                                                    {{ $foto['comment'] }}
                                                </div>
                                            </td>

                                            </tr><tr>
                                        @else
                                            <td class="foto-container">
                                                <img src="{{ $foto['path'] }}">
                                                <p class="comment">{{ $foto['comment'] }}</p>
                                            </td>
                                            @if(($index + 1) % 2 == 0)
                                                </tr><tr>
                                            @endif
                                        @endif
                                    @endforeach

                                </tr>
                            </table>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach

        </body>
    </html>