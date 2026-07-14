<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOTOS FOR-PIMP-02_B/04</title>
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
                    top: -51px; /* Ajusta para que no interfiera con el margen de la pÃ¡gina */
                    left: 0;
                    right: 0;
                    height: auto; /* Permite que el header crezca dinÃ¡micamente */
                    text-align: center;
                    /*background-color:rgb(226, 45, 45); /* Fondo para que sea visible */
                    font-family: 'arial', sans-serif;
                }

                footer {
                    position: fixed;
                    bottom: -30px; /* Ajusta la posiciÃ³n */
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
            /*font-size: 12px; /* Ajusta el tamaÃ±o de la fuente si es necesario */
            max-width: 100%; /* Para que el texto no desborde */
            word-wrap: break-word; /* Permite que el texto se ajuste */
        }
        /* Estilo para los "comentarios" en blanco */
        .empty-comment {
            margin-top: 170px;   /* AÃ±ade espacio entre las lÃ­neas cruzadas y el comentario */
            border-top: 1px solid black; /* Borde superior de 2px en color negro */
            padding-top: 42px; /* Espaciado entre el borde y el texto del comentario de las vacios*/
        }
        
        .empty-box {
            background-color:rgb(255, 255, 255); /* Color de fondo para los cuadros vacÃ­os */
        }

        .cross-line {
            width: 74%;
            height: 0px; /* Ajusta segÃºn el tamaÃ±o de las imÃ¡genes */
            position: relative;
        }

        .cross-line::before,
        .cross-line::after {
            content: "";
            position: absolute;
            top: 84px; /* Ajusta esta propiedad para mover la lÃ­nea hacia arriba o hacia abajo */
            left: -21px; /* Ajusta para alinear la lÃ­nea */
            width: 152.5%; /* Aumenta el ancho de la lÃ­nea */
            height: 100%;
            border-top: 2px solid black;
            transform: rotate(27deg); /* Ajusta el Ã¡ngulo de la primera lÃ­nea */
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

        .tablaGenerales {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
            table-layout: fixed;
        }

        .tablaGenerales th,
        .tablaGenerales td {
            padding: 3px 3px;
            vertical-align: bottom;
            text-align: left;
        }

        .etiquetaGeneral {
            width: 12%;
            font-weight: bold;
            line-height: 10px;
            text-align: left;
        }

        .etiquetaGeneralCentrada {
            text-align: center !important;
            vertical-align: middle !important;
        }

        .etiquetaGeneralCentrada .titulo-es-nowrap {
            display: block;
            white-space: nowrap;
            text-align: center;
        }

        .valorGeneral {
            border-bottom: 1px solid black;
            height: 13px;
            text-align: center;
            vertical-align: middle;
            padding-left: 0;
            padding-right: 0;
        }

        .tituloGeneralPdf {
            text-align: center !important;
            line-height: 11px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO<br>FORMAT</th>
                <th style="width: 70%;">CÓDIGO<br>CODE</th>
                <th style="width: 100%;">FOR-PIMP-02_B/04</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Ensayo de Durezas en Soldaduras<br>Test Report on Welding Hardness</th>
                <th>VERSIÓN<br>VERSION</th>
                <th>2</th>
            </tr>
            <tr>
                <th>PÁGINA<br>PAGE</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<footer>
    <table class="datosgenerales">
        <tr>
            <th>OBSERVACIONES<br>REMARKS:</th>
            <td class="lineaInferior" style="width: 600px;">{{ $Datos_Equipo['Observaciones'] ?? '' }}</td>
        </tr>
    </table>

    <table class="datosgenerales">
        <thead>
            @if($numFirmas == 1)
                <tr>
                    <th>{{ $Firmas_Reportes['Realizo'] ?? '' }}</th>
                </tr>
                <tr>
                    <td style="width: 260px; height:40px" class="lineaInferior"></td>
                </tr>
                <tr>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                </tr>
            @elseif($numFirmas == 2)
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
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
            @elseif($numFirmas == 3)
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
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</strong></td>
                </tr>
            @elseif($numFirmas == 4)
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
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] ?? '' }}</strong></td>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>{{ $Firmas_Reportes['CARGO_TECNICO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] ?? '' }}</strong></td>
                    <th></th>
                </tr>
                <tr>
                    <th></th>
                    <td><strong>Asesoria e Inspeccion en Construccion Costa Fuera, S.C.</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] ?? '' }}</strong></td>
                    <td></td>
                    <td><strong>{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] ?? '' }}</strong></td>
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
        if (!empty($foto['una_hoja']) && $foto['una_hoja'] == 1) {
            if (!empty($grupoActual)) {
                $chunks[] = $grupoActual;
                $grupoActual = [];
            }
            $chunks[] = [$foto];
            continue;
        }

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
    @php
        $esHojaCompleta = (
            count($fotosGrupo) == 1 &&
            !empty($fotosGrupo[0]['una_hoja']) &&
            $fotosGrupo[0]['una_hoja'] == 1
        );
    @endphp
    <table class="tablaGenerales">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6" class="tituloGeneralPdf">DATOS GENERALES<br>General Data</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="etiquetaGeneral">FECHA:<br>Date</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. REPORTE:<br>No. Report:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">CLIENTE:<br>Client:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. CONTRATO:<br>No. Contract:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">PROYECTO:<br>Project:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">ORDEN DE TRABAJO:<br>Work Order:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">FOLIO:<br>Folio:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">PARTIDA:<br>Lot:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">INSTALACIÓN:<br>Location:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">NUMERO DE ISOMÉTRICO:</span>No. Isometric:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">NOMBRE DE LAS PIEZAS:</span>Name of the Pieces:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Nom_Pieza'] ?? '' }}</td>
            <th class="etiquetaGeneral">MATERIAL:<br>Material:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">PROCEDIMIENTO:<br>Procedure</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">CRITERIO DE EVALUACIÓN:</span>Evaluation Criteria:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</td>
            <th class="etiquetaGeneral">TRAZABILIDAD:<br>Traceability:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">No JUNTA:<br>No. Joint:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Junta'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">TEMPERATURA DE LA PIEZA:</span>Piece Temperature</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Temperatura_pieza'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada"><span class="titulo-es-nowrap">ESPESOR/CÉDULA:</span>Thickness / Schedule:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Espesor_cedula'] ?? '' }}</td>
        </tr>
    </tbody>
</table>

        @if(!$esHojaCompleta)
        <div style="margin-bottom: 6px;"></div>


        <div style="margin-bottom: 6px;"></div>
        @endif

        <table class="datosgenerales">
            <thead class="encabezadoAzul">
                <tr><th>REGISTRO FOTOGRAFICO<br>PHOTOGRAPHIC RECORD</th></tr>
            </thead>
        </table>

        <table class="imagenes-reporte">
            <tr>
                @foreach($fotosGrupo as $index => $foto)
                    @if(!empty($foto['una_hoja']) && $foto['una_hoja'] == 1)
                        <td class="foto-container foto-full" colspan="2">
                            <img src="{{ $foto['path'] }}">
                            <p class="comment">{{ $foto['comment'] }}</p>
                        </td>
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

