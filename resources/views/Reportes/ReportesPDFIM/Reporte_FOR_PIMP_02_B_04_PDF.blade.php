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
            right: 10;
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

        .datosgenerales {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }

        .tablaResumenDureza {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            table-layout: fixed;
        }

        .tablaResumenDureza col.col-primaria {
            width: 60% !important;
        }

        .tablaResumenDureza col.col-medicion {
            width: 6% !important;
        }

        .tablaResumenDureza col.col-equipo-etiqueta {
            width: 4% !important;
        }

        .tablaResumenDureza col.col-equipo-valor {
            width: 6% !important;
        }

        .tablaResumenDureza .celdaEtiquetaPromedio {
            width: 25% !important;
            min-width: 60% !important;
            white-space: normal !important;
            overflow-wrap: break-word !important;
            word-break: normal !important;
        }

        .tablaResumenDureza th,
        .tablaResumenDureza td {
            border: .1px solid black;
            padding: 0px 1px;
            vertical-align: middle;
            text-align: center;
        }

        .tablaResumenDureza .encabezadoCompacto,
        .tablaResumenDureza .encabezadoCompactoPrincipal {
            padding: 1px 2px;
            vertical-align: middle;
            text-align: center;
            line-height: .9;
            font-size: 9px;
        }

        .tablaResumenDureza .encabezadoCompacto small,
        .tablaResumenDureza .encabezadoCompactoPrincipal small {
            font-size: 8.2px;
            line-height: 0.12;
            font-weight: bold;
            white-space: nowrap !important;
        }

        .tablaResumenDureza .valorUsuario {
            font-size: 8px;
            line-height: 1;
            text-align: center;
            padding: 4px 1px;
        }

        .croquisSoldadura {
            height: 40px;
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
            white-space: nowrap !important;
            text-align: center;
            line-height: 0.95;
            font-size: 7.5px;
            padding: 1px 2px;
        }

        .valorEquipoLateral {
            font-size: 8px;
            line-height: 1;
            text-align: center;
            vertical-align: middle;
            padding: 4px 1px;
        }

        /* Etiquetas de columnas de medición (METAL BASE / ZAC / SOLDADURA):
            se permite el wrap en varias líneas para que el texto se adapte
           al ancho de la celda sin desbordarse ni romper palabras. */
        .etiquetaPromedio {
            font-weight: bold;
            white-space: normal;
            text-align: center;
            line-height: 0.95;
            word-break: normal;
            overflow-wrap: normal;
        }

        .etiquetaPromedioPrincipal {
            font-weight: bold;
            text-align: center;
            line-height: 1.15 !important;
            font-size: 8px;
            white-space: normal;
            word-break: normal;
            overflow-wrap: normal;
            padding: 2px 2px !important;
            vertical-align: middle !important;
        }

        .etiquetaPromedioPrincipal .texto-es {
            white-space: nowrap !important;
            display: block;
            font-size: 5px;
            line-height: 1.2;
        }

        .etiquetaPromedioPrincipal small {
            display: block;
            font-size: 5px !important;
            line-height: 1.15 !important;
            font-weight: bold;
            white-space: nowrap !important;
            margin-top: 0;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
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

        .tablaDurezaPdf {
            width: 100%;
            border-collapse: collapse;
            font-size: 7px;
            margin-top: 4px;
        }

        .tablaDurezaPdf th,
        .tablaDurezaPdf td {
            border: .1px solid black;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }

        .tablaDurezaPdf thead th {
            font-weight: bold;
            white-space: nowrap !important;
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

        .valorGeneral {
            border-bottom: 1px solid black;
            text-align: center !important;
            vertical-align: middle !important;
            height: 13px;
        }

        .valorGeneralAlto {
            height: 15px;
        }

        .tituloGeneralPdf {
            text-align: center !important;
            line-height: 11px;
            font-weight: bold;
            white-space: nowrap !important;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO<br>Format</th>
                <th style="width: 70%;">Código<br>Code</th>
                <th style="width: 100%;">FOR-PIMP-02_B/04</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Ensayo de Durezas en Soldaduras<br>Test Report on Welding Hardness</th>
                <th>Versión<br>Version</th>
                <th>2</th>
            </tr>
            <tr>
                <th>Página<br>Page</th>
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
                        <td><strong>Asesorí­a e Inspección en Construcción Costa Fuera, S.C.</strong></td>
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

@if(!empty($durezaPages))
<div style="margin-bottom: 2px;"></div>
@foreach($durezaPages as $pageIndex => $page)
    @if($pageIndex > 0)
        <div style="page-break-before: always;"></div>
    @endif

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
    <div style="margin-bottom: 8px;"></div>

        <table class="tablaResumenDureza">
            <colgroup>
                <col class="col-primaria">
                <col class="col-medicion">
                <col class="col-medicion">
                <col class="col-medicion">
                <col class="col-medicion">
                <col class="col-medicion">
                <col class="col-equipo-etiqueta">
                <col class="col-equipo-valor">
            </colgroup>
            <thead>
                <tr>
                    <td colspan="6" rowspan="2" class="croquisSoldadura">
                        <div class="croquisWrapper">
                            @if($croquisExiste)
                                <img src="{{ $croquisPath }}" alt="Croquis de soldadura" style="width:40%; height:40px; display:block; margin:0 auto; object-fit:contain;">
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
                    <th class="etiquetaPromedioPrincipal encabezadoCompactoPrincipal celdaEtiquetaPromedio">
                        <span class="texto-es">VALORES PROMEDIO DE DUREZAS:</span>
                        <small>Average Hardness Values</small>
                    </th>
                    <th class="etiquetaPromedio encabezadoCompacto">
                        <span style="font-size:5.8px;">METAL BASE</span><br>
                        <small style="font-size:4.8px;">Base Metal (A)</small>
                    </th>
                    <th class="etiquetaPromedio encabezadoCompacto">
                        <span style="font-size:5.8px;">ZAC</span><br>
                        <small style="font-size:4.8px;">HAZ (B)</small>
                    </th>
                    <th class="etiquetaPromedio encabezadoCompacto">
                        <span style="font-size:5.6px;">SOLDADURA</span><br>
                        <small style="font-size:4.8px;">Welding (C)</small>
                    </th>
                    <th class="etiquetaPromedio encabezadoCompacto">
                        <span style="font-size:5.8px;">ZAC</span><br>
                        <small style="font-size:4.8px;">HAZ (B1)</small>
                    </th>
                    <th class="etiquetaPromedio encabezadoCompacto">
                        <span style="font-size:5.8px;">METAL BASE</span><br>
                        <small style="font-size:4.8px;">Base Metal (A1)</small>
                    </th>
                    <th class="etiquetaEquipoLateral">MARCA:<br>Brand:</th>
                    <td class="valorEquipoLateral valorUsuario">{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th class="etiquetaPromedioPrincipal encabezadoCompactoPrincipal celdaEtiquetaPromedio">
                        <span class="texto-es">ANTES DEL RELEVADO DE ESFUERZOS (HB):</span>
                        <small>Before PWHT (HB)</small>
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
                    <th class="etiquetaPromedioPrincipal encabezadoCompactoPrincipal celdaEtiquetaPromedio">
                        <span class="texto-es">POSTERIOR AL RELEVADO DE ESFUERZOS (HB):</span>
                        <small>After PWHT (HB)</small>
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
            @foreach($page['rows'] as $row)
                <tr>
                    @if($row['descripcion']['show'])
                        <td rowspan="{{ $row['descripcion']['rowspan'] }}">
                            {{ $row['descripcion']['value'] }}
                        </td>
                    @endif

                    @if($row['horario']['show'])
                        <td rowspan="{{ $row['horario']['rowspan'] }}">
                            {{ $row['horario']['value'] }}
                        </td>
                    @endif

                    @if($row['metal_base_a']['show'])
                        <td rowspan="{{ $row['metal_base_a']['rowspan'] }}">
                            {{ $row['metal_base_a']['value'] }}
                        </td>
                    @endif

                    @if($row['zac_b']['show'])
                        <td rowspan="{{ $row['zac_b']['rowspan'] }}">
                            {{ $row['zac_b']['value'] }}
                        </td>
                    @endif

                    @if($row['soldadura_c']['show'])
                        <td rowspan="{{ $row['soldadura_c']['rowspan'] }}">
                            {{ $row['soldadura_c']['value'] }}
                        </td>
                    @endif

                    @if($row['zac_b1']['show'])
                        <td rowspan="{{ $row['zac_b1']['rowspan'] }}">
                            {{ $row['zac_b1']['value'] }}
                        </td>
                    @endif

                    @if($row['metal_base_a1']['show'])
                        <td rowspan="{{ $row['metal_base_a1']['rowspan'] }}">
                            {{ $row['metal_base_a1']['value'] }}
                        </td>
                    @endif

                    @if($row['observaciones']['show'])
                        <td rowspan="{{ $row['observaciones']['rowspan'] }}">
                            {{ $row['observaciones']['value'] }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach
@endif

</body>
</html>
