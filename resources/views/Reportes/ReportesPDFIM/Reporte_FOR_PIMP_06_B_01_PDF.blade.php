<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-06_B/01</title>

    <style>
        @page {
            margin: 2cm 1.2cm 1.1cm 2.2cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin-top: 27px;
            padding-top: 0;
            padding-bottom: 0;
        }

        header {
            position: fixed;
            top: -56px;
            left: 0;
            right: 0;
            height: auto;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            height: auto;
            text-align: center;
        }

        footer table {
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        footer th,
        footer td {
            text-align: center;
            vertical-align: middle;
        }

        .tablaheader {
            border-collapse: collapse;
            width: 100%;
            text-align: center;
            font-size: 10px;
        }

        .tablaheader th {
            border: 1px solid black;
        }

        .encabezadoAzul {
            text-align: center;
            background-color: #305496;
            color: #fff;
            font-size: 8px;
        }

        .datosgenerales,
        .datosinspeccion {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .datosinspeccion th,
        .datosinspeccion td {
            border: .6px solid black;
            padding: 3px;
        }

        .tablaEquipos {
            table-layout: fixed;
            height: 42px;
        }

        .tablaEquipos th,
        .tablaEquipos td {
            padding: 1px;
        }

        .celdaGris {
            background-color: #fdfafa;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
        }

        .tablaPrueba {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
            border: none;
        }

        .tablaPrueba th {
            padding: 0;
            line-height: 9px;
        }

        .tablaPrueba td {
            padding: 6px 3px;
            text-align: center;
            vertical-align: middle;
            border: none;
        }


        .etiquetaPrueba {
            width: 28%;
            font-weight: bold;
            line-height: 11px;
        }

        .valorPrueba {
            width: 18%;
            border-bottom: 1px solid black;
            min-height: 12px;
        }

        .tablaPrueba td.valorPrueba {
            border-bottom: 1px solid black;
        }

        .separadorPrueba {
            width: 8%;
        }

        .tablaGenerales {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
            table-layout: fixed;
        }

        .tablaGenerales th,
        .tablaGenerales td {
            padding: 1.5px 1.5px;
            vertical-align: middle;
        }
        .valorGeneral {
            border-bottom: 1px solid black;
            text-align: center !important;
            vertical-align: middle !important;
            height: 10px;
        }

        .valorGeneralAlto {
            height: 15px;
        }

        .valorGeneralConLinea {
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }

        .lineaValorGeneral {
            width: 100%;
            min-height: 10px;
            border-bottom: 1px solid black;
            box-sizing: border-box;
            text-align: center;
        }

        .paginaDisparos {
            page-break-inside: avoid;
        }

        .tablaDisparos {
            width: 17.22cm;
            margin: 0 auto;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .celdaDisparo {
            width: 8.61cm;
            padding: 0;
            vertical-align: top;
            box-sizing: border-box;
            padding-bottom: 0.3cm;
        }

        .celdaDisparoIzquierda {
            padding-right: 0.25cm;
        }

        .celdaDisparoDerecha {
            padding-left: 0.25cm;
        }

        .tituloDisparo {
            background-color: #305496;
            color: white;
            border: 1px solid black;
            box-sizing: border-box;
            padding: 2px;
            text-align: center;
            font-size: 6px;
            line-height: 7px;
        }

        .espacioImagenDisparo {
            width: 4.125cm;
            padding: 0;
            vertical-align: top;
            box-sizing: border-box;
        }

        .espacioImagenDisparoIzquierdo {
            padding-right: 0.33cm;
        }

        .espacioImagenDisparoDerecho {
            padding-left: 0.33cm;
        }

        .espacioImagenDisparoDerecho .imagenDisparo {
            width: 3.68cm;
        }

        .espacioImagenDisparoDerecho .imagenDisparo img {
            width: 3.62cm;
        }

        .imagenDisparo {
            width: 3.78cm;
            height: 3.99cm;
            box-sizing: border-box;
            border: 1px solid black;
            text-align: center;
            vertical-align: middle;
            padding: 2px;
        }

        .imagenDisparo img {
            display: block;
            width: 3.68cm;
            height: 3.89cm;
            object-fit: contain;
            margin: 0 auto;
        }

        .tablaImagenesDisparo {
            width: 8.27cm;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .espacioTablaQuimica {
            width: 8.36cm;
            height: 4.99cm;
        }

        .tablaQuimicaDisparo {
            width: 8.36cm;
            height: 4.99cm;
            border-collapse: collapse;
            table-layout: fixed;
            text-align: center;
            font-size: 5.5px;
        }

        .tablaQuimicaDisparo th,
        .tablaQuimicaDisparo td {
            border: 1px solid black;
            padding: 1px;
            line-height: 6px;
            overflow-wrap: break-word;
        }

        .tablaQuimicaDisparo thead th {
            height: 1.25cm;
            font-weight: bold;
        }

        .sinImagenDisparo {
            color: #777;
            font-size: 8px;
        }
        .etiquetaGeneral {
            width: 15%;
            font-weight: bold;
            white-space: nowrap !important;
            line-height: 10px;
            text-align: left;
            padding-left: 2px;
            vertical-align: middle;
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
        .observacionesBox {
            width: 50%;
            margin-left: 50%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
            position: relative;
            top: -25px;
        }

        .observacionesBox th,
        .observacionesBox td {
            width: 50%;
            padding: 3px 5px;
            text-align: center;
            font-size: 8px;
        }

        .observacionesTitulo {
            vertical-align: middle;
            font-weight: bold;
        }

        .observacionesLineas {
            height: 24px;
            border-bottom: 1px solid black;
            vertical-align: bottom;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO<br>Format</th>
                <th style="width: 70%;">CÓDIGO<br>Code</th>
                <th style="width: 100%;">FOR-PIMP-06_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Análisis químico mediante la Técnica de Fluorescencia de Rayos X (XRF)<br>
                    Chemicals Analysis Report Using the X-Ray Fluorescense Technique (XRF)</th>
                <th>VERSIÓN<br>Version</th>
                <th>3</th>
            </tr>
            <tr>
                <th>PÁGINA<br>Page</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>

<footer>
        @include('Reportes.partials.firmas_im_pdf')
        <table class="datosgenerales" style="display: none;">
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

            {{-- ================= DATOS GENERALES ================= --}}
<div style="margin-bottom: 2px;"></div>

<table class="tablaGenerales">
    <thead class="encabezadoAzul">
        <tr><th colspan="6">DATOS GENERALES<br>General Data</th></tr>
    </thead>
    <tbody>
        <tr>
            <th class="etiquetaGeneral">FECHA<br>Date:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">No. REPORTE<br>No. Report:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">CLIENTE<br>Client:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">No. CONTRATO<br>No. Contract:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">CONTRATO<br>Contract:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">ORDEN DE TRABAJO<br>Work Order:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">FOLIO<br>Folio:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">PARTIDA<br>Lot:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">INSTALACION<br>Location:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada" style="white-space: nowrap;">NUMERO DE ISOMETRICO<br>No. Isometric:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" style="white-space: nowrap;">NOMBRE DE LA PIEZA<br>Name of the Piece:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Nom_Pieza'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">MATERIAL<br>Material:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">PROCEDIMIENTO<br>Procedure:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada" style="white-space: nowrap;">CRITERIO DE EVALUACION<br>Evaluation Criteria:</th>
            <td class="valorGeneral valorGeneralConLinea"><div class="lineaValorGeneral">{{ $Detalles_Generales['Criterio_Evaluacion'] ?? '' }}</div></td>
            <th class="etiquetaGeneral etiquetaGeneralCentrada">TRAZABILIDAD<br>Traceability:</th>
            <td class="valorGeneral valorGeneralConLinea"><div class="lineaValorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</div></td>
        </tr>
        <tr>
            <th class="etiquetaGeneral">No. JUNTA<br>No. Joint:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Junta'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 2px;"></div>
<table class="datosinspeccion tablaEquipos">
    <colgroup>
        <col style="width: 40%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
        <col style="width: 20%;">
    </colgroup>
    <thead class="encabezadoAzul">
        <tr><th colspan="6">DATOS DE EQUIPOS<br>
            Equipment Data</th></tr>
    </thead>

    <tbody>
        <tr class="celdaGris">
            <th>MARCA<br> 
                Brand</th>
                <td>{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
            <th>MODELO<br> 
                Model</th>
                <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}</td>
            <th>No. SERIE<br> 
                Serial Number</th>
                <td>{{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}</td>
        </tr>
    </tbody>
    <thead class="encabezadoAzul">
        <tr><th colspan="6">RESULTADOS DEL ANÁLISIS QUÍMICO DEL ELEMENTO<br>Results of the Chemical Analysis of the Element</th></tr>
    </thead>
</table>
<div style="margin-bottom: 2px;"></div>

@php
        $ordinalesDisparoPdf = [1 => '1er.', 2 => '2do.', 3 => '3er.'];
        $ordinalesDisparoIngles = [1 => '1st', 2 => '2nd', 3 => '3rd'];
        ksort($Disparos);
        $distribucionDisparosPdf = [[1, 2], [3, 'tabla_quimica']];
@endphp

<div class="paginaDisparos">
        <table class="tablaDisparos">
            @foreach ($distribucionDisparosPdf as $disparosFila)
                <tr>
                    @foreach ($disparosFila as $celdaDisparo)
                        @if ($celdaDisparo === 'tabla_quimica')
                            <td class="celdaDisparo {{ $loop->first ? 'celdaDisparoIzquierda' : 'celdaDisparoDerecha' }}">
                                @if (!empty($NormaIM['Tabla']))
                                    <table class="tablaQuimicaDisparo">
                                        <colgroup>
                                            <col style="width: 28%;">
                                            <col style="width: 36%;">
                                            <col style="width: 36%;">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th>Elementos Quimicos<br>Chemical elements</th>
                                                <th>Promedio de Valores Obtenidos en la Pieza Analizada<br>Average Values Obtained in the Analyzed Piece</th>
                                                <th>Composicion Quimica Teorica<br>Theoretical Chemical Composition</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($NormaIM['Tabla'] as $filaNorma)
                                                <tr>
                                                    <th>{{ $filaNorma['Elemento'] ?? '' }}</th>
                                                    <td>{{ $filaNorma['Promedio'] ?? '' }}</td>
                                                    <td>{{ $filaNorma['Composicion'] ?? '' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="espacioTablaQuimica"></div>
                                @endif
                            </td>
                        @elseif (!empty($Disparos[$celdaDisparo]))
                            <td class="celdaDisparo {{ $loop->first ? 'celdaDisparoIzquierda' : 'celdaDisparoDerecha' }}">
                                <div class="tituloDisparo">
                                    {{ $ordinalesDisparoPdf[$celdaDisparo] }} DISPARO<br>
                                    ({{ $ordinalesDisparoIngles[$celdaDisparo] }} shot)<br>
                                    VALORES OBTENIDOS EN LA PIEZA ANALIZADA<br>
                                    Values obtained in the analyzed piece
                                </div><table class="tablaImagenesDisparo">
                                    <colgroup>
                                        <col style="width: 4.125cm;">
                                        <col style="width: 4.125cm;">
                                    </colgroup>
                                    <tr>
                                        @foreach ($Disparos[$celdaDisparo] as $indiceImagen => $imagen)
                                            <td class="espacioImagenDisparo {{ $indiceImagen === 0 ? 'espacioImagenDisparoIzquierdo' : 'espacioImagenDisparoDerecho' }}">
                                                <div class="imagenDisparo">
                                                    <img src="{{ $imagen }}" alt="Imagen {{ $indiceImagen + 1 }} del disparo {{ $celdaDisparo }}">
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </td>
                        @else
                            <td class="celdaDisparo {{ $loop->first ? 'celdaDisparoIzquierda' : 'celdaDisparoDerecha' }}"></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
</div>
<div style="margin-bottom: 30px;"></div>
@php
    // Dompdf puede mostrar como "?" algunos guiones Unicode usados por las normas ASTM.
    $nombreNormaPdf = str_replace(
        ["\u{2212}", "\u{2013}", "\u{2014}"],
        '-',
        (string) ($NormaIM['Nombre_Espe'] ?? '')
    );
    $variableNormaPdf = str_replace(
        ["\u{2212}", "\u{2013}", "\u{2014}"],
        '-',
        (string) ($NormaIM['Variable'] ?? '')
    );
@endphp
<table class="observacionesBox">
    <tr>
        <th class="observacionesTitulo">NORMA O ESPECIFICACIÓN APROXIMADA DEL MATERIAL:<br>
            Approximate Material Standard or Specification:</th>
        <td class="observacionesLineas">
            {{ $nombreNormaPdf }}
            @if ($variableNormaPdf !== '')
                <br>{{ $variableNormaPdf }}
            @endif
        </td>
    </tr>
</table>
</body>
</html>
