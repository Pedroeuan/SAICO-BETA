<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-02-INS-04</title>
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
                    top: -38px; /* Ajusta para que no interfiera con el margen de la página */
                    left: 0;
                    right: 0;
                    height: auto; /* Permite que el header crezca dinámicamente */
                    text-align: center;
                    /*background-color:rgb(226, 45, 45); /* Fondo para que sea visible */
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -40px; /* Ajuste para que título esté fuera de imagen pero visible */
                    left: 0;
                    right: 0;
                    height: auto;
                    text-align: center;
                    /*background-color: rgb(7, 231, 18)/* Fondo para que sea visible */
                    font-family: 'arial', sans-serif;
                }

                body {
                    margin-top: 42px; /* Ajusta para que el contenido no se sobreponga al header */
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
                    font-size: 6.3px !important;
                    font-family: 'arial', sans-serif;
                } 
                
                /*muestra solo la linea inferior de la celda*/
                .lineaInferior{
                    border-bottom: 1px solid black;
                    text-align: center;
                    font-size: 6.3px;
                }

                .tablaheader {
                    border-collapse: collapse; 
                    border-spacing: 0px;        /* Espacio entre celdas */
                    width: 100%;
                    text-align: center;
                    font-size: 9px;
                }
                    
                /* Aplica el borde a las celdas de la tabla */
                .tablaheader th {
                    /*width: 70%;*/
                    border: 1px solid black;
                    padding: 2px 3px;
                    vertical-align: middle;
                }

        .encabezadoAzul{
            text-align: center;
            width: 100%;
            font-size: 7px;
            background-color: #2F75B5;
            color: #ffffff;
            outline: 1px double #000000; /* Contorno externo */
        }

        .firma-footer {
            table-layout: fixed;
            font-size: 6.2px !important;
            line-height: 1.1;
        }

        .firma-footer th,
        .firma-footer td {
            padding: 0 1px;
            text-align: center;
            vertical-align: top;
        }

        .header-title {
            font-size: 8.1pt;
            line-height: 1.12;
            padding: 5px 4px;
        }

        .border {
            border: 1px solid black; 
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
        /* ************** */
        .imagenes-reporte {
            margin-left: 0;
            width: 100%;
            border-collapse: separate;
            /*border-spacing: 20px; /* Espacio entre celdas */
            border-spacing: 10px 10px;
            margin-bottom: 0;
            table-layout: fixed; /* Fija el ancho de las celdas */
        }

        .foto-container {
            padding: 0;
            width: auto;
            height: 175px;
            border: 1px solid black; 
            vertical-align: middle;
        }

         .foto-container img {
            object-fit: cover;
            width: 100%;
            height: 142px;
            vertical-align: middle;
            display: block;
        }

        /* Estilo para los comentarios */
        .comment { 
            border-top: 1px solid black; /* Borde superior de 2px en color negro */
            padding-top: 7px; /* Espaciado entre el borde y el texto */
            margin-top: 0px; /* Espacio entre la imagen y el comentario */
            text-align: center; /* Centrar el texto */
            /*font-size: 12px; /* Ajusta el tamaño de la fuente si es necesario */
            max-width: 100%; /* Para que el texto no desborde */
            word-wrap: break-word; /* Permite que el texto se ajuste */
        }
        /* Estilo para los "comentarios" en blanco */
        .empty-comment {
            margin-top: 142px;   /* Añade espacio entre las líneas cruzadas y el comentario */
            border-top: 1px solid black; /* Borde superior de 2px en color negro */
            padding-top: 22px; /* Espaciado entre el borde y el texto del comentario de las vacios*/
        }
        
        .empty-box {
                background-color:rgb(255, 255, 255); /* Color de fondo para los cuadros vacíos */
        }

        .cross-line {
            width: 100%;
            height: 0px; /* Ajusta según el tamaño de las imágenes */
            position: relative;
        }

        .cross-line::before,
        .cross-line::after {
            content: "";
            position: absolute;
            top: 72px; /* Ajusta esta propiedad para mover la línea hacia arriba o hacia abajo */
            left: 0;
            width: 100%;
            height: 100%;
            border-top: 2px solid black;
            transform: rotate(27deg); /* Ajusta el ángulo de la primera línea */
        }

        .cross-line::after {
            transform: rotate(-27deg);
        }
        .foto-container[colspan="2"] img {
            width: 100%;
            height: 155px;
        }

        /* ===== Imagen que ocupa una hoja completa ===== */
        .foto-full {
            width: 100% !important;
            height: 435px !important;
        }

        .foto-full img {
            width: 100% !important;
            height: 340px !important;
            object-fit: contain; /* no recorta */
        }

        .foto-full .comment {
            margin-top: 0px;
            font-size: 12px;
        }
        .firma-contenedor {
            width: 100%;
            margin-top: 5px;
        }

        .firma-tabla {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .firma-col {
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }

        .firma-titulo {
            display: block;
            margin-bottom: 8px; /* separación título - línea */
            font-weight: bold;
        }

        .firma-linea {
            border-bottom: 1px solid #000;
            height: 35px;
            width: 80%;
            margin: 0 auto 6px auto;
        }

        .firma-texto {
            display: block;
            line-height: 1.2;
        }
            </style>
        </head>
        <body>

            <header>
                <table class="tablaheader">
                    <thead>
                        <tr>
                            <th style="width: 60%;">FORMATO</th>
                            <th style="width: 12%;">Código:</th>
                            <th style="width: 12%;">FOR-INS-04/02</th>
                            <th rowspan="3" style="width: 16%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 62%; max-height: 58px;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" class="header-title">INFORME DE INSPECCIÓN DE SOLDADURAS CON ULTRASONIDO, DE ACUERDO CON AWS D1.1 PARA COMPONENTES TUBULARES</th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 2px;"></div>
            </header>
            
            <footer>
                    <table class="datosgenerales firma-footer">
                        <div class="firma-contenedor">
                        @php
                            $firmas = [];

                            if(!empty($Firmas_Reportes['Realizo'])){
                                $firmas[] = [
                                    'titulo' => $Firmas_Reportes['Realizo'],
                                    'nombre' => $Firmas_Reportes['NOMBRE_TECNICO'] ?? '',
                                    'cargo' => $Firmas_Reportes['CARGO_TECNICO'] ?? '',
                                    'empresa' => $Firmas_Reportes['EMPRESA_TECNICO'] ?? 'Asesoría e Inspección en Construcción Costa Fuera, S.C.'
                                ];
                            }

                            if(!empty($Firmas_Reportes['Vobo1'])){
                                $firmas[] = [
                                    'titulo' => $Firmas_Reportes['Vobo1'],
                                    'nombre' => $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '',
                                    'cargo' => $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '',
                                    'empresa' => $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? ''
                                ];
                            }

                            if(!empty($Firmas_Reportes['Vobo2'])){
                                $firmas[] = [
                                    'titulo' => $Firmas_Reportes['Vobo2'],
                                    'nombre' => $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '',
                                    'cargo' => $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '',
                                    'empresa' => $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? ''
                                ];
                            }

                            if(!empty($Firmas_Reportes['Vobo3'])){
                                $firmas[] = [
                                    'titulo' => $Firmas_Reportes['Vobo3'],
                                    'nombre' => $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] ?? '',
                                    'cargo' => $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] ?? '',
                                    'empresa' => $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] ?? ''
                                ];
                            }

                            $total = count($firmas);
                            $width = $total > 0 ? floor(100 / $total) : 100;
                        @endphp

                        <table class="firma-tabla">
                            <tr>
                                @foreach($firmas as $firma)
                                    <td class="firma-col" style="width: {{ $width }}%;">
                                        <span class="firma-titulo">{{ $firma['titulo'] }}</span>
                                        <div class="firma-linea"></div>
                                        <span class="firma-texto"><strong>{{ $firma['nombre'] }}</strong></span>
                                        <span class="firma-texto"><strong>{{ $firma['cargo'] }}</strong></span>
                                        <span class="firma-texto"><strong>{{ $firma['empresa'] }}</strong></span>
                                    </td>
                                @endforeach
                            </tr>
                        </table>
                    </div>                
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
                        if (count($grupoActual) == 4) {
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
                    <div style="height: 2px;"></div>

                    <table class="datosgenerales firma-footer">

                        <thead class="encabezadoAzul">
                            <tr><th colspan="4">DATOS GENERALES</th></tr>
                        </thead>  

                        <thead><tr class="sinBordeth"><th colspan="4"></th></tr></thead> <!-- Fila vacia -->

                        <tbody>
                            <tr>
                                <th style="width: 12%; text-align: center;">FECHA:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Fecha'] }}</td>
                                <th style="width: 15%; text-align: center;">NO. REPORTE:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['No_Reporte'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">CLIENTE:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Cliente'] }}</td>
                                <th style="text-align: center;">CONTRATO:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Contrato'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">PROYECTO: </th>
                                <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Proyecto'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">ORDEN DE TRABAJO:</th>
                                <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Orden_Trabajo'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">FOLIO:</th>
                                <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Folio'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">PARTIDA:</th>
                                <td class="lineaInferior" colspan="3" style="text-align: center;">{{ $Detalles_Generales['Partida'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">LUGAR:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Lugar'] }}</td>
                                <th style="text-align: center;">ISOMETRICO/PLANO:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Isometrico_Plano'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">PIEZA:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Pieza'] }}</td>
                                <th style="text-align: center;">MATERIAL:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Material'] }}</td>
                            </tr>
                            <tr>
                                <th style="text-align: center;">PROCEDIMIENTO:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Procedimiento'] }}</td>
                                <th style="width: 160px; text-align: center;">CRITERIO DE EVALUACION:</th>
                                <td class="lineaInferior" style="text-align: center;">{{ $Detalles_Generales['Criterio_Evaluacion'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                <div style="margin-bottom: 2px;"></div>

                <table class="datosgenerales">
                    <thead class="encabezadoAzul">
                        <tr><th>REGISTRO FOTOGRÁFICO</th></tr>
                    </thead>  
                </table>
                @php
                    $esHojaCompleta = (
                        count($fotosGrupo) == 1 &&
                        !empty($fotosGrupo[0]['una_hoja']) &&
                        $fotosGrupo[0]['una_hoja'] == 1
                    );
                @endphp

                <table class="imagenes-reporte">
                    @if($esHojaCompleta)
                        <tr>
                            <td class="foto-container foto-full" colspan="2">
                                <img src="{{ $fotosGrupo[0]['path'] }}">
                                <p class="comment">{{ $fotosGrupo[0]['comment'] }}</p>
                            </td>
                        </tr>
                    @elseif(count($fotosGrupo) == 3)
                        <tr>
                            <td class="foto-container">
                                <img src="{{ $fotosGrupo[0]['path'] }}">
                                <p class="comment">{{ $fotosGrupo[0]['comment'] }}</p>
                            </td>
                            <td class="foto-container">
                                <img src="{{ $fotosGrupo[1]['path'] }}">
                                <p class="comment">{{ $fotosGrupo[1]['comment'] }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td class="foto-container" colspan="2">
                                <img src="{{ $fotosGrupo[2]['path'] }}">
                                <p class="comment">{{ $fotosGrupo[2]['comment'] }}</p>
                            </td>
                        </tr>
                    @else
                        @php
                            $fotosRender = $fotosGrupo;
                            while (count($fotosRender) < 4) {
                                $fotosRender[] = null;
                            }
                            $filasFotos = array_chunk($fotosRender, 2);
                        @endphp

                        @foreach($filasFotos as $fila)
                            <tr>
                                @foreach($fila as $foto)
                                    @if($foto)
                                        <td class="foto-container">
                                            <img src="{{ $foto['path'] }}">
                                            <p class="comment">{{ $foto['comment'] }}</p>
                                        </td>
                                    @else
                                        <td class="foto-container empty-box">
                                            <div class="cross-line"></div>
                                            <div class="empty-comment"></div>
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach

        </body>
    </html>



