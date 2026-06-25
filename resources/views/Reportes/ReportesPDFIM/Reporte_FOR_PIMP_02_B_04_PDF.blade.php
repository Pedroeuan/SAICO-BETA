<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-02_B/04</title>

    <style>
        @page {
            margin: 3cm 1.2cm 2.1cm 2.2cm;
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
        }

        .tablaResumenDureza {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            table-layout: fixed;
        }

        .tablaResumenDureza th,
        .tablaResumenDureza td {
            border: .6px solid black;
            padding: 1px 2px;
            vertical-align: middle;
            text-align: center;
        }

        .tablaResumenDureza .encabezadoCompacto,
        .tablaResumenDureza .encabezadoCompactoPrincipal {
            padding: 8px 10px;
            vertical-align: middle;
            text-align: center;
            line-height: .95;
            white-space: normal;
            word-break: normal;
            overflow-wrap: normal;
        }

        .tablaResumenDureza .encabezadoCompacto {
            font-size: 7px;
        }

        .tablaResumenDureza .encabezadoCompactoPrincipal {
            font-size: 7px;
        }

        .tablaResumenDureza .encabezadoCompacto small,
        .tablaResumenDureza .encabezadoCompactoPrincipal small {
            font-size: 5.2px;
            line-height: 0.9;
            font-weight: bold;
        }

        .tituloEspanolCorto {
            white-space: nowrap;
        }

        .tablaResumenDureza .valorUsuario {
            font-size: 8px;
            line-height: 1;
            text-align: center;
            height: 24px;
        }

        .croquisSoldadura {
            height: 86px;
            text-align: center;
            padding: 0;
        }

        .croquisWrapper {
            width: 100%;
            text-align: center;
        }

        .croquisSoldadura svg {
            display: block;
            margin: 0 auto;
        }

        .etiquetaEquipoLateral {
            font-weight: bold;
            text-align: center;
            line-height: 0.95;
            font-size: 7.5px;
            padding: 1px 2px;
        }

        .valorEquipoLateral {
            font-size: 8px;
            line-height: 1;
            text-align: center;
            height: 24px;
        }

        .etiquetaPromedio {
            font-weight: bold;
            text-align: center;
            line-height: 0.95;
            white-space: normal;
            word-break: normal;
            overflow-wrap: normal;
        }

        .etiquetaPromedioPrincipal {
            font-weight: bold;
            text-align: center;
            line-height: 0.9 !important;
            font-size: 7px;
            white-space: normal;
            word-break: normal;
            overflow-wrap: normal;
            padding: 1px 1px !important;
            vertical-align: middle !important;
        }

        .etiquetaPromedioPrincipal .texto-es {
            white-space: nowrap !important;
            display: inline-block;
            font-size: 4.6px !important;
            line-height: 0.9 !important;
        }

        .etiquetaPromedioPrincipal small {
            display: block;
            font-size: 5px !important;
            line-height: 0.9 !important;
            font-weight: bold;
        }

        .celdaGris {
            background-color: #DBDBDB;
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

        .tablaPrueba .encabezadoAzul th {
            border: .6px solid black;
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
            padding: 3px 3px;
            vertical-align: bottom;
            text-align: left;
        }

        .tablaDurezaPdf {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            margin-top: 4px;
        }

        .tablaDurezaPdf th,
        .tablaDurezaPdf td {
            border: .6px solid black;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }

        .tablaDurezaPdf thead th {
            font-weight: bold;
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
                <th>OBSERVACIONES<br>
                REMARKS:</th>                                         
                <td class="lineaInferior" style="width: 600px;">{{ $Datos_Equipo['Observaciones'] }}</td>                            
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
                        <td><strong>Asesorí­a e InspecciÃ³n en Construcción Costa Fuera, S.C.</strong></td>
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
                        <td><strong>Asesorí­a e InspecciÃ³n en Construcción Costa Fuera, S.C.</strong></td>
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
                        <td><strong>Asesorí­a e InspecciÃ³n en Construcción Costa Fuera, S.C.</strong></td>
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
<div style="margin-bottom: 3px;"></div>
@php
    $durezaPromedio = $Datos_Equipo['DUREZA_PROMEDIO'] ?? [];
    $croquisPath = public_path('img/reportes/for-pimp-02-b-04-croquis.png');
    $croquisExiste = file_exists($croquisPath);

    // AJUSTE RAPIDO: cambia estos valores para probar tamanos y proporciones
    $anchoColPrincipal = 10;
    $anchoColsMedicion = 4.5;
    $anchoColEtiquetaEquipo = 13.5;
    $anchoColValorEquipo = 18;
    $tamTextoEsLargo = 4.6;
    $tamTextoEsCorto = 6.3;
    $tamTextoEsSoldadura = 6.1;
    $tamTextoEn = 5;
@endphp

<table class="tablaResumenDureza">
    <colgroup>
        <col style="width: {{ $anchoColPrincipal }}%;">
        <col style="width: {{ $anchoColsMedicion }}%;">
        <col style="width: {{ $anchoColsMedicion }}%;">
        <col style="width: {{ $anchoColsMedicion }}%;">
        <col style="width: {{ $anchoColsMedicion }}%;">
        <col style="width: {{ $anchoColsMedicion }}%;">
        <col style="width: {{ $anchoColEtiquetaEquipo }}%;">
        <col style="width: {{ $anchoColValorEquipo }}%;">
    </colgroup>
    <thead>
        <tr>
            <td colspan="6" rowspan="2" class="croquisSoldadura">
                <div class="croquisWrapper">
                    @if($croquisExiste)
                        <img src="{{ $croquisPath }}" alt="Croquis de soldadura" style="width:100%; height:72px; display:block; margin:0 auto; object-fit:contain;">
                    @endif
                </div>
            </td>
            <th colspan="2" class="encabezadoAzul">DATOS DEL EQUIPO<br>Equipment Data</th>
        </tr>
        <tr>
            <th class="etiquetaEquipoLateral">METODO:<br>Method:</th>
            <td class="valorEquipoLateral valorUsuario">{{ $Detalles_Generales['Metodo'] ?? '' }}</td>
        </tr>
        <tr>
    <th class="etiquetaPromedioPrincipal encabezadoCompactoPrincipal">
        <span class="texto-es" style="font-size: 302px;">VALORES PROMEDIO DE DUREZAS:</span>
        <small style="font-size: 4.8px;">Average Hardness Values</small>
    </th>
    <th class="etiquetaPromedio encabezadoCompacto"><span style="white-space:nowrap; font-size:5.8px;">METAL BASE</span><br><small style="font-size: 4.8px;">Base Metal</small><br>(A)</th>
    <th class="etiquetaPromedio encabezadoCompacto"><span style="white-space:nowrap; font-size:5.8px;">ZAC</span><br><small style="font-size: 4.8px;">HAZ (B)</small></th>
    <th class="etiquetaPromedio encabezadoCompacto"><span style="white-space:nowrap; font-size:5.6px;">SOLDADURA</span><br><small style="font-size: 4.8px;">Welding</small><br>(C)</th>
    <th class="etiquetaPromedio encabezadoCompacto"><span style="white-space:nowrap; font-size:5.8px;">ZAC</span><br><small style="font-size: 4.8px;">HAZ</small><br>(B1)</th>
    <th class="etiquetaPromedio encabezadoCompacto"><span style="white-space:nowrap; font-size:5.8px;">METAL BASE</span><br><small style="font-size: 4.8px;">Base Metal</small><br>(B)</th>
    <th class="etiquetaEquipoLateral">MARCA:<br>Brand:</th>
    <td class="valorEquipoLateral valorUsuario">{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
</tr>
<tbody>
    <tr>
        <th class="etiquetaPromedioPrincipal encabezadoCompactoPrincipal">
            <span class="texto-es" style="font-size: 300.2px;">ANTES DEL RELEVADO DE ESFUERZOS (HB):</span>
            <small style="font-size: 4.8px;">Before PWHT (HB)</small>
        </th>
        <td class="valorUsuario">{{ $durezaPromedio['ANTES_A'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['ANTES_B'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['ANTES_C'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['ANTES_B1'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['ANTES_BM'] ?? '' }}</td>
        <th class="etiquetaEquipoLateral">MODELO:<br>Model:</th>
        <td class="valorEquipoLateral valorUsuario">{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}</td>
    </tr>
    <tr>
        <th class="etiquetaPromedioPrincipal encabezadoCompactoPrincipal">
            <span class="texto-es" style="font-size: 300px;">POSTERIOR AL RELEVADO DE ESFUERZOS (HB):</span>
            <small style="font-size: 4.8px;">After PWHT (HB)</small>
        </th>
        <td class="valorUsuario">{{ $durezaPromedio['DESPUES_A'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['DESPUES_B'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['DESPUES_C'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['DESPUES_B1'] ?? '' }}</td>
        <td class="valorUsuario">{{ $durezaPromedio['DESPUES_BM'] ?? '' }}</td>
        <th class="etiquetaEquipoLateral">NO. DE SERIE:<br>Serial Number:</th>
        <td class="valorEquipoLateral valorUsuario">{{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}</td>
    </tr>
</tbody>
</table>

@php
    $durezaRows = $Datos_Equipo['DUREZA_ROWS'] ?? [];
    $durezaMergeConfig = $Datos_Equipo['DUREZA_MERGE_CONFIG'] ?? [];

    if (!is_array($durezaRows)) {
        $durezaRows = [];
    }

    if (!is_array($durezaMergeConfig)) {
        $durezaMergeConfig = [];
    }

    $durezaRowspans = [];
    $durezaHiddenCells = [];

    foreach ($durezaMergeConfig as $merge) {
        $row = isset($merge['row']) ? (int) $merge['row'] : -1;
        $field = $merge['field'] ?? '';
        $rowspan = isset($merge['rowspan']) ? (int) $merge['rowspan'] : 1;

        if ($row < 0 || $rowspan < 2 || $field === '') {
            continue;
        }

        $durezaRowspans[$row . '|' . $field] = $rowspan;

        for ($offset = 1; $offset < $rowspan; $offset++) {
            $durezaHiddenCells[($row + $offset) . '|' . $field] = true;
        }
    }
@endphp

@if(!empty($durezaRows))
<div style="margin-bottom: 2px;"></div>
<table class="tablaDurezaPdf">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="8">ANTES O DESPUES DEL RELEVADO DE ESFUERZOS<br>BEFORE OR AFTER PWHT</th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th colspan="2">DATOS DE LA JUNTA<br>Join Data</th>
            <th colspan="5">VALORES DE DUREZA (ESCALA BRINELL)<br>Hardness Values (Brinell Scale)</th>
            <th rowspan="2" style="width: 14%;">OBSERVACIONES<br>Remarks</th>
        </tr>
        <tr>
            <th style="width: 18%;">DESCRIPCION<br>Description</th>
            <th style="width: 10%;">HORARIOS TECNICOS<br>Technical schedules</th>
            <th style="width: 11%;">METAL BASE<br>Base Metal (A)</th>
            <th style="width: 11%;">ZAC / HAZ<br>(B)</th>
            <th style="width: 11%;">SOLDADURA<br>Weld (C)</th>
            <th style="width: 11%;">ZAC / HAZ<br>(B1)</th>
            <th style="width: 11%;">METAL BASE<br>Base Metal (A1)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($durezaRows as $index => $row)
            <tr>
                @if(empty($durezaHiddenCells[$index . '|descripcion']))
                    <td @if(isset($durezaRowspans[$index . '|descripcion'])) rowspan="{{ $durezaRowspans[$index . '|descripcion'] }}" @endif>
                        {{ $row['descripcion'] ?? '' }}
                    </td>
                @endif

                @if(empty($durezaHiddenCells[$index . '|horario']))
                    <td @if(isset($durezaRowspans[$index . '|horario'])) rowspan="{{ $durezaRowspans[$index . '|horario'] }}" @endif>
                        {{ $row['horario'] ?? '' }}
                    </td>
                @endif

                <td>{{ $row['metal_base_a'] ?? '' }}</td>
                <td>{{ $row['zac_b'] ?? '' }}</td>
                <td>{{ $row['soldadura_c'] ?? '' }}</td>
                <td>{{ $row['zac_b1'] ?? '' }}</td>
                <td>{{ $row['metal_base_a1'] ?? '' }}</td>

                @if(empty($durezaHiddenCells[$index . '|observaciones']))
                    <td @if(isset($durezaRowspans[$index . '|observaciones'])) rowspan="{{ $durezaRowspans[$index . '|observaciones'] }}" @endif>
                        {{ $row['observaciones'] ?? '' }}
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
@endif

</body>
</html>
