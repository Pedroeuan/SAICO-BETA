<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-07_B/01</title>

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

        /* DejaVu Sans incorpora los glifos Ø y ⌀ que la fuente PDF Arial no garantiza. */
        .valor-diametro {
            font-family: "DejaVu Sans", sans-serif;
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
            text-align: center;
            vertical-align: middle;
        }

        .tablaEquipos {
            table-layout: fixed;
        }

        .celdaGris {
            font-weight: bold;
            text-align: left !important;
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
            padding: 4px 2px;
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
        }

        .tablaGenerales th,
        .tablaGenerales td {
            padding: 1px 1.5px;
            vertical-align: bottom;
            text-align: center;
        }

        .etiquetaGeneral {
            width: 12%;
            font-weight: bold;
            line-height: 10px;
        }

        .valorGeneral {
            border-bottom: 1px solid black;
            height: 13px;
        }
        .observacionesBox {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
            position: relative;
            top: -35px;
        }

        .observacionesBox td {
            padding: 1px 1px;
            text-align: left;
            vertical-align: top;
            font-size: 8px;
        }
        .alinearIzquierda {
            text-align: left !important;
            padding-left: 4px !important;
        }

            .alinearCentro {
            text-align: center !important;
        }
        .textoNegrita {
            font-weight: bold !important;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width: 400%;">FORMATO<br>
                FORMAT</th>
                <th style="width: 70%;">CÓDIGO<br>
                    CODE
                </th>
                <th style="width: 100%;">FOR-PIMP-07_B/01</th>
                <th rowspan="3" style="width: 80%;">
                    <img src="{{ $Logo }}" alt="Logo" style="width: 55%; height: auto;">
                </th>
            </tr>
            <tr>
                <th rowspan="2">INFORME DE RELEVADO DE ESFUERZOS<br>
                    RELIEVED OF STRESS INFORM</th>
                <th>VERSIÓN<br>
                VERSION</th>
                <th>1</th>
            </tr>
            <tr>
                <th>PÁGINA<br>PAGE</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>
<footer>
        <table class="observacionesBox">
            <tr>
                <td>
                    <div class="observacionesTitulo textoNegrita">OBSERVATIONS / OBSERVACIONES:</div>
                    <div class="observacionesLineas">{{ $Datos_Equipo['Observaciones'] ?? '' }}</div>
                </td>
            </tr>
        </table>

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
        <tr>
            <th colspan="6">DATOS GENERALES<br>GENERAL DATA</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">FECHA<br>DATE:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. REPORTE<br>No. REPORT:</th>
            <td class="valorGeneral" colspan="2">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">CLIENTE<br>CLIENT:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. CONTRATO<br>No. CONTRACT:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">PROYECTO<br>PROJECT:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda" style="white-space: nowrap;">ORDEN DE TRABAJO<br>WORK ORDER:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">FOLIO<br>FOLIO:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">PARTIDA<br>LOT:</th>
            <td class="valorGeneral" colspan="5">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">INSTALACION<br>LOCATION:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
            <th class="etiquetaGeneral">No. ISOMETRICO<br>No. ISOMETRIC:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda" style="white-space: nowrap;">ELEMENTOS SOLDADOS<br>WELDINGS:</th>
            <td class="valorGeneral" colspan="3">{{ $Detalles_Generales['Elementos_Soldados'] ?? '' }}</td>
            <th class="etiquetaGeneral">MATERIAL<br>MATERIAL:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Material'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">No. JUNTA<br>No. JOINT:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['No_Junta'] ?? '' }}</td>
            <th class="etiquetaGeneral">TRAZABILIDAD<br>TRACEABILITY:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
            <th class="etiquetaGeneral">ESPESORES<br>THICKNESSES:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Espesores'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral alinearIzquierda">PROCEDIMIENTO<br>PROCEDURE:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
            <th class="etiquetaGeneral">CODIGO DE DISENO<br>DESIGN CODE:</th>
            <td class="valorGeneral">{{ $Detalles_Generales['Codigo_Diseno'] ?? '' }}</td>
            <th class="etiquetaGeneral">DIAM. NOMINAL<br>NOMINAL DIAMETER:</th>
            <td class="valorGeneral valor-diametro">{{ $Detalles_Generales['Diam_Nominal'] ?? '' }}</td>
        </tr>
        <tr>
            <th class="etiquetaGeneral" colspan="2" style="width: 28%;">REPORTE DE DUREZA ANTES<br>DEL RELEVADO<br>HARDNESS REPORT BEFORE THE<br>RELIEVED OF STRESS:</th>
            <td class="valorGeneral" style="width: 22%;">{{ $Detalles_Generales['Reporte_Antes_Relevado'] ?? '' }}</td>
            <th class="etiquetaGeneral" colspan="2" style="width: 28%;">REPORTE DE DUREZA<br>DESPUES DEL RELEVADO<br>HARDNESS REPORT AFTER THE<br>RELIEVED OF STRESS:</th>
            <td class="valorGeneral" style="width: 22%;">{{ $Detalles_Generales['Reporte_Despues_Relevado'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 3px;"></div>
<table class="datosinspeccion tablaEquipos">
            <colgroup>
                <col style="width: 40%;">
                <col style="width: 20%;">
                <col style="width: 20%;">
                <col style="width: 20%;">
            </colgroup>
            <thead>
                <tr class="encabezadoAzul"><th colspan="4">DATOS DEL EQUIPO<br>EQUIPMENT DATA</th></tr>
                <tr>
                    <th >EQUIPO<br>EQUIPMENT</th>
                    <th>MARCA<br>BRAND</th>
                    <th>MODELO<br>MODEL</th>
                    <th>No. SERIE<br>SERIAL NUMBER</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td  class="celdaGris">MAQUINA DE RELEVADO<br>STRESS RELIEF MACHINE:</td>
                    <td>{{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="celdaGris">GRAFICADOR<br>GRAPHIER:</td>
                    <td>{{ $Datos_Equipo['MARCA_EQUIPO1'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['MODELO_EQUIPO1'] ?? '' }}</td>
                    <td>{{ $Datos_Equipo['NS_EQUIPO1'] ?? '' }}</td>
                </tr>
            </tbody>
        </table>
<div style="margin-bottom: 2px;"></div>
<table class="tablaPrueba">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="5">
                DATOS DE PRUEBA<br>
                TEST DATA
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td class="etiquetaPrueba">TEMPERATURA INICIAL<br>INITIAL TEMPERATURE (&deg;F)</td>
            <td class="valorPrueba">{{ $Datos_Equipo['TEMPERATURA_INICIAL'] ?? '' }}</td>
            <td class="separadorPrueba"></td>
            <td class="etiquetaPrueba">HORA INICIO DE PRUEBA<br>TEST START TIME:</td>
            <td class="valorPrueba">{{ $Datos_Equipo['HORA_INICIO'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="etiquetaPrueba">VEL. DE CALENTAMIENTO<br>HEATING RATE (&deg;F/hr)</td>
            <td class="valorPrueba">{{ $Datos_Equipo['VELOCIDAD_CALENTAMIENTO'] ?? '' }}</td>
            <td class="separadorPrueba"></td>
            <td class="etiquetaPrueba">HORA FINAL DE PRUEBA<br>TEST END TIME:</td>
            <td class="valorPrueba">{{ $Datos_Equipo['HORA_FINAL'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="etiquetaPrueba">TEMP. SOSTENIMIENTO<br>HOLDING TEMPERATURE (&deg;F)</td>
            <td class="valorPrueba">{{ $Datos_Equipo['TEMPERATURA_SOSTENIMIENTO'] ?? '' }}</td>
            <td class="separadorPrueba"></td>
            <td class="etiquetaPrueba">DIA DE INICIO DE PRUEBA<br>TEST START DAY</td>
            <td class="valorPrueba">{{ $Datos_Equipo['DIA_INICIO'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="etiquetaPrueba">TIEMPO DE SOSTENIMIENTO<br>HOLDING TIME (MIN)</td>
            <td class="valorPrueba">{{ $Datos_Equipo['TIEMPO_SOSTENIMIENTO'] ?? '' }}</td>
            <td class="separadorPrueba"></td>
            <td class="etiquetaPrueba">DIA DE FINALIZACION DE PRUEBA<br>TEST END DAY:</td>
            <td class="valorPrueba">{{ $Datos_Equipo['DIA_FINAL'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="etiquetaPrueba">VEL. DE ENFRIAMIENTO<br>COOLING RATE (&deg;F/hr)</td>
            <td class="valorPrueba">{{ $Datos_Equipo['VEL_ENFRIAMIENTO'] ?? '' }}</td>
            <td class="separadorPrueba"></td>
            <td class="etiquetaPrueba">No. GRAFICA<br>No.GRAPH</td>
            <td class="valorPrueba">{{ $Datos_Equipo['NO_GRAFICA'] ?? '' }}</td>
        </tr>

        <tr>
            <td class="etiquetaPrueba">VEL. DEL GRAFICADO<br>GRAPHIER SPEED (mm/hr):</td>
            <td class="valorPrueba">{{ $Datos_Equipo['VEL_GRAFICADOR'] ?? '' }}</td>
            <td class="separadorPrueba"></td>
            <td class="etiquetaPrueba"></td>
            <td></td>
        </tr>
    </tbody>
</table>

</body>
</html>
