<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>FORMATO FOR-01-INS-17</title>
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
                        margin-top: 27px; /* Ajusta para que el contenido no se sobreponga al header */
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
                        font-size: 8px;
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
                    }

            .encabezadoAzul{
                text-align: center;
                width: 100%;
                font-size: 8px;
                background-color: #305496;
                color: #ffffff;
                outline: 1px double #000000; /* Contorno externo */
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
                margin-left: -15.6; /* Asegura que la tabla se alinee al margen izquierdo */
                width: 106%;
                border-collapse: separate;
                /*border-spacing: 20px; /* Espacio entre celdas */
                border-spacing: 20px 20px; /* 20px entre columnas, 0px entre filas */
                margin-bottom: 0;
                table-layout: fixed; /* Fija el ancho de las celdas */
            }

            .foto-container {
                padding: 0px; /* Asegura que la imagen toque el borde de la celda de izquierda- a(0) derecha+*/
                width: 312px;  /* Fija el ancho de la celda */
                height: 170px; /* Fija la altura de la celda */
                border: 1px solid black; 
                vertical-align: middle;
            }

            .foto-container img {
                /*object-fit: contain; /* Ajusta la imagen dentro del recuadro sin recortarla */
                object-fit: cover; /* Llenar el espacio sin distorsionar */
                width: 332.5px;  /* Ajusta el ancho de la celda */
                height: 170px; /* Ajusta la altura de la celda */
                vertical-align: middle;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
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
                height: 23%;
            }

            /* ===== Imagen que ocupa una hoja completa ===== */
            .foto-full {
                width: 100% !important;
                height: 435px !important;
            }

            .foto-full img {
                width: 100% !important;
                height: 404px !important;
                object-fit: contain; /* no recorta */
            }

            .foto-full .comment {
                margin-top: 0px;
                font-size: 12px;
            }

            /* ===== UTILIDADES Y CLASES REUTILIZABLES ===== */
            
            /* Tabla de sección estándar */
            .tabla-seccion {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid #333;
                margin-bottom: 0;
            }

            .tabla-seccion thead {
                background-color: #305496;
                color: #ffffff;
            }

            .tabla-seccion th {
                text-align: center;
                padding: 8px;
                font-weight: bold;
                font-size: 8px;
            }

            .tabla-seccion td {
                padding: 8px;
                border: 1px solid #333;
            }

            /* Celda de etiqueta (header izquierdo) */
            .celda-etiqueta {
                width: 25%;
                background-color: #DBDBDB;
                font-weight: bold;
                text-align: center;
                border-right: 1px solid #333333;
            }

            /* Celda de datos */
            .celda-dato {
                width: 25%;
                text-align: center;
                border-right: 1px solid #333;
            }

            /* Última celda sin borde derecho */
            .celda-dato-final {
                width: 25%;
                text-align: center;
                border-right: 0;
            }

            /* Separador de contenido */
            .espaciador {
                margin-bottom: 12px;
            }

            .espaciador-pequeno {
                margin-bottom: 6px;
            }

            /* Tabla de dos columnas (imágenes) */
            .tabla-dos-columnas {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid #333;
            }

            .tabla-dos-columnas td {
                width: 50%;
                padding: 10px;
                border: 1px solid #333;
                text-align: center;
            }

            .tabla-dos-columnas .col-izq {
                border-right: 1px solid #333;
            }

            /* Contenedor de imagen con fallback */
            .imagen-container {
                max-width: 100%;
                max-height: 200px;
                display: inline-block;
            }

            .imagen-placeholder {
                width: 100%;
                height: 200px;
                background: #f0f0f0;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 48px;
                color: #ccc;
            }

            /* Tabla de etiquetas debajo de imágenes */
            .tabla-etiquetas {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid #333;
                border-top: none;
                background-color: #f0f0f0;
            }

            .tabla-etiquetas td {
                padding: 8px;
                border: 1px solid #333;
                text-align: center;
                font-weight: bold;
                font-size: 8px;
            }

            .tabla-etiquetas .col-izq {
                border-right: 1px solid #333;
            }

            /* Tabla de datos operativos */
            .tabla-operativo tr {
                border-bottom: 1px solid #333;
            }

            .tabla-operativo .celda-etiqueta {
                background-color: #DBDBDB;
            }

            .tabla-operativo .celda-dato {
                text-align: center;
            }

            /* Tabla de contenido texto (Observaciones, Nota, Recomendaciones) */
            .tabla-contenido-texto {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid #333;
            }

            .tabla-contenido-texto td {
                text-align: justify;
                padding: 12px;
                font-size: 8px;
                line-height: 1.5;
                min-height: 80px;
                border: 1px solid #333;
                word-wrap: break-word;
            }

            /* Severidad - colores dinámicos */
            .severidad-row {
                border-bottom: 1px solid #333;
            }

            .severidad-indicador {
                width: 4%;
                min-width: 4%;
                padding: 0;
                border: 1px solid #333;
                text-align: center;
            }

            .severidad-etiqueta {
                padding: 10px;
                text-align: center;
                width: 14%;
                font-weight: bold;
                border: 1px solid #333;
                border-right: 1px solid #333;
                font-size: 8px;
                color: #333333;
                background-color: transparent;
            }

            .severidad-descripcion {
                padding: 10px;
                text-align: left;
                width: 32%;
                font-size: 8px;
                line-height: 1.4;
                border: 1px solid #333;
                border-right: 1px solid #333;
                color: #000000;
                background-color: transparent;
            }

            /* Page break utility */
            .page-break {
                page-break-after: always;
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
                            <th style="width: 80%;">FOR-INS-17/01</th>
                            <th rowspan="3" style="width: 80%;"><img  src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <th rowspan="2" style="font-size: 8pt;"> INSPECCIÓN CON TERMOGRAFÍA INFRARROJA A TABLEROS</th>
                            <th>Versión</th>
                            <th>2</th>
                        </tr>
                        <tr>
                            <th>Página</th>
                            <th></th>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 6px;"></div>
            </header>
            
            <footer>
                    <table class="datosgenerales">
                        <thead>
                            @if( $numFirmas == 1)
                            <!-- 1 Firmas -->
                                <tr>
                                    <td style="width: 30px;"></td>
                                    <th>{{ $Firmas_Reportes['Realizo'] }}</th>
                                    <td style="width: 30px;"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td style="width: 30px; height:40px" class="lineaInferior"></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                                </tr>
                                                                    
                                <tr>
                                    <th></th>
                                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] }}</strong></td>
                                </tr>

                                <tr>
                                    <th></th>
                                    <td><strong>Asesoría e Inspección en Construcción Costa Fuera, S.C.</strong></td>
                                </tr>
                            @elseif( $numFirmas == 2)
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

            <div class="content">

            @php
                /**
                 * Configuración de severidad
                 * Define los estilos y comportamientos para cada nivel de severidad
                 */
                $severidadConfig = [
                    'BUENO' => [
                        'bg' => '#90EE90',
                        'color' => '#006400',
                        'label_color' => '#28a745',
                        'descripcion' => 'El equipo se encuentra en condiciones óptimas.'
                    ],
                    'MODERADO' => [
                        'bg' => '#FFFF00',
                        'color' => '#000000',
                        'label_color' => '#d4b000',
                        'descripcion' => 'Ligera anomalía, mantener en observación y programar reparación.'
                    ],
                    'PREVENTIVO' => [
                        'bg' => '#FFA500',
                        'color' => '#FFFFFF',
                        'label_color' => '#ffc107',
                        'descripcion' => 'Media anomalía programar el paro del equipo para su reparación.'
                    ],
                    'NO ACEPTABLE' => [
                        'bg' => '#FF0000',
                        'color' => '#FFFFFF',
                        'label_color' => '#dc3545',
                        'descripcion' => 'Anomalía severa, se recomienda parar el equipo para su intervención.'
                    ]
                ];

                $severidadSeleccionada = strtoupper(str_replace('_', ' ', $Datos_Equipo['severidad'] ?? 'NONE'));
                $valorPorDefecto = '---';
                $datosGenerales = [
                    ['label' => 'FECHA:', 'value' => $Detalles_Generales['Fecha'] ?? $valorPorDefecto, 'label2' => 'NO. REPORTE:', 'value2' => $Detalles_Generales['No_Reporte'] ?? $valorPorDefecto],
                    ['label' => 'CLIENTE:', 'value' => $Detalles_Generales['Cliente'] ?? $valorPorDefecto, 'label2' => 'CONTRATO:', 'value2' => $Detalles_Generales['Contrato'] ?? $valorPorDefecto],
                    ['label' => 'PROYECTO:', 'value' => $Detalles_Generales['Proyecto'] ?? $valorPorDefecto, 'colspan' => 3],
                    ['label' => 'ORDEN DE TRABAJO:', 'value' => $Detalles_Generales['Orden_Trabajo'] ?? $valorPorDefecto, 'colspan' => 3],
                    ['label' => 'FOLIO:', 'value' => $Detalles_Generales['Folio'] ?? $valorPorDefecto, 'colspan' => 3],
                    ['label' => 'EQUIPO:', 'value' => $Detalles_Generales['Equipo'] ?? $valorPorDefecto, 'colspan' => 3],
                    ['label' => 'PARTIDA:', 'value' => $Detalles_Generales['Partida'] ?? $valorPorDefecto, 'label2' => 'UBICACIÃ“N:', 'value2' => $Detalles_Generales['Ubicacion'] ?? $valorPorDefecto],
                    ['label' => 'LUGAR:', 'value' => $Detalles_Generales['Lugar'] ?? $valorPorDefecto, 'label2' => 'HORA DE INSPECCIÃ“N:', 'value2' => $Detalles_Generales['H_Inspeccion'] ?? $valorPorDefecto],
                    ['label' => 'PROCEDIMIENTO:', 'value' => $Detalles_Generales['Procedimiento'] ?? $valorPorDefecto, 'label2' => 'ESTÃNDAR DE REFERENCIA:', 'value2' => $Detalles_Generales['Stndr_refe'] ?? $valorPorDefecto, 'label2_width' => '160px'],
                ];
                $camposEquipo = [
                    ['etiqueta' => 'MARCA:', 'valor' => $Datos_Equipo['MARCA_EQUIPO'] ?? $valorPorDefecto, 'etiqueta2' => 'FECHA DE CALIBRACIÃ“N:', 'valor2' => $Datos_Equipo['FEC_CAL'] ?? $valorPorDefecto],
                    ['etiqueta' => 'MODELO:', 'valor' => $Datos_Equipo['MODELO_EQUIPO'] ?? $valorPorDefecto, 'etiqueta2' => 'CERTIFICADO POR:', 'valor2' => $Datos_Equipo['CER_POR'] ?? $valorPorDefecto],
                    ['etiqueta' => 'N.S.:', 'valor' => $Datos_Equipo['NS_EQUIPO'] ?? $valorPorDefecto, 'etiqueta2' => 'RANGO DE MEDICIÃ“N:', 'valor2' => $Datos_Equipo['RAN_MED'] ?? $valorPorDefecto],
                ];
                $imagenesReferencia = [
                    ['termograma_foto' => $Fotos[1] ?? null, 'termograma_alt' => 'Termograma 1', 'termograma_valor' => $Datos_Equipo['termograma1'] ?? $valorPorDefecto, 'visible_foto' => $Fotos[2] ?? null, 'visible_alt' => 'Imagen Visible 1', 'emisividad_valor' => $Datos_Equipo['emisividad1'] ?? $valorPorDefecto],
                    ['termograma_foto' => $Fotos[3] ?? null, 'termograma_alt' => 'Termograma 2', 'termograma_valor' => $Datos_Equipo['termograma2'] ?? $valorPorDefecto, 'visible_foto' => $Fotos[4] ?? null, 'visible_alt' => 'Imagen Visible 2', 'emisividad_valor' => $Datos_Equipo['emisividad2'] ?? $valorPorDefecto],
                ];
                $severidadFilas = [
                    ['BUENO', 'PREVENTIVO'],
                    ['MODERADO', 'NO ACEPTABLE'],
                ];
                $seccionesTexto = [
                    ['titulo' => 'OBSERVACIONES', 'campo' => 'Observaciones'],
                    ['titulo' => 'NOTA', 'campo' => 'Nota'],
                    ['titulo' => 'RECOMENDACIONES', 'campo' => 'Recomendaciones'],
                ];
            @endphp

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
                            <th>EQUIPO:</th>
                            <td class="lineaInferior" colspan="3">{{ $Detalles_Generales['Equipo'] }}</td>
                        </tr>
                        <tr>
                            <th>PARTIDA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Partida'] }}</td>
                            <th>UBICACIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Ubicacion'] }}</td>
                        </tr>
                        <tr>
                            <th>LUGAR:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Lugar'] }}</td>
                            <th>HORA DE INSPECCIÓN:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['H_Inspeccion'] }}</td>
                        </tr>
                        <tr>
                            <th >PROCEDIMIENTO:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Procedimiento'] }}</td>
                            <th style="width: 160px;">ESTÁNDAR DE REFERENCIA:</th>
                            <td class="lineaInferior">{{ $Detalles_Generales['Stndr_refe'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-bottom: 6px;"></div>

                <!-- DATOS Y AJUSTES DEL EQUIPO -->
                <table class="datosgenerales" style="width: 100%;">
                    <thead class="encabezadoAzul">
                        <tr style="padding: 8px; text-align: center; font-weight: bold;">
                            <th style="text-align: center; padding: 8px;">DATOS Y AJUSTES DEL EQUIPO</th>
                        </tr>
                    </thead>
                </table>

                <table class="tabla-seccion" style="margin-top: 0;">
                    <tbody>
                        <tr style="border-bottom: 1px solid #333;">
                            <td colspan="4" style="padding: 8px; background-color: #DBDBDB; font-weight: bold; border: 1px solid #333; text-align: center;">EQUIPO</td>
                        </tr>
                        @php
                            $camposEquipo = [
                                ['etiqueta' => 'MARCA:', 'valor' => $Datos_Equipo['MARCA_EQUIPO'] ?? '---', 'etiqueta2' => 'FECHA DE CALIBRACIÓN:', 'valor2' => $Datos_Equipo['FEC_CAL'] ?? '---'],
                                ['etiqueta' => 'MODELO:', 'valor' => $Datos_Equipo['MODELO_EQUIPO'] ?? '---', 'etiqueta2' => 'CERTIFICADO POR:', 'valor2' => $Datos_Equipo['CER_POR'] ?? '---'],
                                ['etiqueta' => 'N.S.:', 'valor' => $Datos_Equipo['NS_EQUIPO'] ?? '---', 'etiqueta2' => 'RANGO DE MEDICIÓN:', 'valor2' => $Datos_Equipo['RAN_MED'] ?? '---'],
                            ];
                        @endphp
                        @foreach($camposEquipo as $fila)
                            <tr style="@if(!$loop->last)border-bottom: 1px solid #333;@endif">
                                <td class="celda-etiqueta">{{ $fila['etiqueta'] }}</td>
                                <td class="celda-dato">{{ $fila['valor'] }}</td>
                                <td class="celda-etiqueta">{{ $fila['etiqueta2'] }}</td>
                                <td class="celda-dato-final">{{ $fila['valor2'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-bottom: 6px;"></div>

                <!-- IMÁGENES DE REFERENCIA - TODAS JUNTAS CON BORDES Y COLORES -->
                <table style="width: 100%; border-collapse: collapse;">
                    <thead class="encabezadoAzul">
                        <tr><th style="text-align: center; padding: 10px; font-weight: bold; font-size: 9px;">IMÁGENES DE REFERENCIA</th></tr>
                    </thead>  
                </table>

                <!-- TODAS LAS IMÁGENES EN UNA TABLA UNIFICADA -->
                <table style="width: 100%; border-collapse: collapse; border: 2px solid #333; background-color: #ffffff;">
                    <tbody>
                        <!-- FILA 1: IMÁGENES 1-2 (Termograma 1 - Visible 1) -->
                        <tr style="height: 280px;">
                            <td style="width: 50%; border-right: 2px solid #333; border-bottom: 2px solid #333; padding: 8px; text-align: center; vertical-align: middle;">
                                @if($Fotos[1] ?? null)
                                    <img src="{{ $Fotos[1] }}" alt="Termograma 1" style="max-width: 100%; max-height: 260px; display: inline-block;">
                                @else
                                    <div style="width: 100%; height: 260px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 52px; color: #ccc;">✕</div>
                                @endif
                            </td>
                            <td style="width: 50%; border-bottom: 2px solid #333; padding: 8px; text-align: center; vertical-align: middle;">
                                @if($Fotos[2] ?? null)
                                    <img src="{{ $Fotos[2] }}" alt="Imagen Visible 1" style="max-width: 100%; max-height: 260px; display: inline-block;">
                                @else
                                    <div style="width: 100%; height: 260px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 52px; color: #ccc;">✕</div>
                                @endif
                            </td>
                        </tr>

                        <!-- ETIQUETAS FILA 1 -->
                        <tr style="background-color: #ffffff; border-bottom: 2px solid #333;">
                            <td style="width: 50%; border-right: 2px solid #333; padding: 8px; text-align: center; font-weight: bold; font-size: 8px; background-color: #DBDBDB;">
                                Termograma
                            </td>
                            <td style="width: 50%; padding: 8px; text-align: center; font-size: 7px;">
                                {{ $Datos_Equipo['termograma1'] ?? '---' }}
                            </td>
                        </tr>

                        <!-- VALORES FILA 1 -->
                        <tr style="background-color: #ffffff; border-bottom: 2px solid #333;">
                            <td style="width: 50%; border-right: 2px solid #333; padding: 8px; text-align: center; font-weight: bold; font-size: 8px; background-color: #DBDBDB;">
                                Emisividad
                            </td>
                            <td style="width: 50%; padding: 8px; text-align: center; font-size: 7px;">
                                {{ $Datos_Equipo['emisividad1'] ?? '---' }}
                            </td>
                        </tr>

                        <!-- FILA 2: IMÁGENES 3-4 (Termograma 2 - Visible 2/Emisividad) -->
                        <tr style="height: 280px;">
                            <td style="width: 50%; border-right: 2px solid #333; border-bottom: 2px solid #333; padding: 8px; text-align: center; vertical-align: middle;">
                                @if($Fotos[3] ?? null)
                                    <img src="{{ $Fotos[3] }}" alt="Termograma 2" style="max-width: 100%; max-height: 260px; display: inline-block;">
                                @else
                                    <div style="width: 100%; height: 260px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 52px; color: #ccc;">✕</div>
                                @endif
                            </td>
                            <td style="width: 50%; border-bottom: 2px solid #333; padding: 8px; text-align: center; vertical-align: middle;">
                                @if($Fotos[4] ?? null)
                                    <img src="{{ $Fotos[4] }}" alt="Imagen Visible 2" style="max-width: 100%; max-height: 260px; display: inline-block;">
                                @else
                                    <div style="width: 100%; height: 260px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 52px; color: #ccc;">✕</div>
                                @endif
                            </td>
                        </tr>

                        <!-- ETIQUETAS FILA 2 -->
                        <tr style="background-color: #ffffff; border-bottom: 2px solid #333;">
                            <td style="width: 50%; border-right: 2px solid #333; padding: 8px; text-align: center; font-weight: bold; font-size: 8px; background-color: #DBDBDB;">
                                Termograma
                            </td>
                            <td style="width: 50%; padding: 8px; text-align: center; font-size: 7px;">
                                {{ $Datos_Equipo['termograma2'] ?? '---' }}
                            </td>
                        </tr>

                        <!-- VALORES FILA 1 -->
                        <tr style="background-color: #ffffff; border-bottom: 2px solid #333;">
                            <td style="width: 50%; border-right: 2px solid #333; padding: 8px; text-align: center; font-weight: bold; font-size: 8px; background-color: #DBDBDB;">
                                Emisividad
                            </td>
                            <td style="width: 50%; padding: 8px; text-align: center; font-size: 7px;">
                                {{ $Datos_Equipo['emisividad2'] ?? '---' }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="espaciador-pequeno"></div>

                <!-- DATOS DE OPERACIÓN -->
                <table class="datosgenerales" style="width: 100%;">
                    <thead class="encabezadoAzul">
                        <tr><th style="text-align: center;">DATOS DE OPERACIÓN</th></tr>
                    </thead>  
                </table>

                <table class="tabla-seccion tabla-operativo">
                    <tr>
                        <td class="celda-etiqueta">VOLTAJE:</td>
                        <td class="celda-dato">{{ $Datos_Equipo['voltaje'] ?? '---' }}</td>
                        <td class="celda-etiqueta">CARGA AMP:</td>
                        <td class="celda-dato-final">{{ $Datos_Equipo['CARGA_AMP'] ?? '---' }}</td>
                    </tr>
                </table>

                <div class="espaciador"></div>

                <!-- TABLA DE SEVERIDAD -->
                <table class="datosgenerales" style="width: 100%;">
                    <thead class="encabezadoAzul">
                        <tr><th style="text-align: center;">TABLA DE SEVERIDAD</th></tr>
                    </thead>  
                </table>

                <table class="tabla-seccion">
                    <tbody>
                        @foreach($severidadFilas as $filaSeveridad)
                            <tr @class(['severidad-row' => !$loop->last])>
                                @foreach($filaSeveridad as $severidad)
                                    @php
                                        $config = $severidadConfig[$severidad];
                                        $esSeleccionada = $severidadSeleccionada === $severidad;
                                        $labelColor = $config['label_color'] ?? '#333333';
                                        $bg = $esSeleccionada ? $labelColor : 'transparent';
                                    @endphp
                                    <td class="severidad-etiqueta" style="color: {{ $labelColor }};">
                                        {{ $severidad }}
                                    </td>
                                    <td class="severidad-indicador" style="background-color: {{ $bg }};"></td>
                                    <td class="severidad-descripcion">
                                        {{ $config['descripcion'] }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="espaciador"></div>

                <div class="page-break"></div>

                <!-- SECCIONES DE CONTENIDO: OBSERVACIONES, NOTA, RECOMENDACIONES -->
                <div class="espaciador"></div>

                @foreach($seccionesTexto as $seccion)
                    <table class="datosgenerales" style="width: 100%;">
                        <thead class="encabezadoAzul">
                            <tr><th style="text-align: center;">{{ $seccion['titulo'] }}</th></tr>
                        </thead>  
                    </table>

                    <table class="tabla-contenido-texto">
                        <tr>
                            <td>
                                {{ $Datos_Equipo[$seccion['campo']] ?? $valorPorDefecto }}
                            </td>
                        </tr>
                    </table>

                    @if(!$loop->last)
                        <div class="espaciador"></div>
                    @endif
                @endforeach

            </div>

        </body>
    </html>
