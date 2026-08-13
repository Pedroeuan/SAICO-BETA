<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-02_B/03</title>

    <style>
        @page {
            margin: 
            3cm
            1.2cm 
            2.1cm 
            1.5cm;
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
            text-align: center;
        }

        .datosinspeccion th,
        .datosinspeccion td {
            border: .6px solid black;
            padding: 3px;
        }

        .tablaEquipos {
            table-layout: fixed;
        }

        .celdaGris {
            background-color: #DBDBDB;
        }

        .lineaInferior {
            border-bottom: 1px solid black;
        }

        .observacionesBox {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
            position: relative;
            top: -12px;
        }

        .observacionesBox td {
            padding: 3px 5px;
            text-align: left;
            vertical-align: top;
            font-size: 8px;
        }

        .observacionesTitulo {
            font-weight: bold;
            line-height: 10px;
            padding-bottom: 1px;
        }

        .observacionesLineas {
            height: 38px;
            background-image: linear-gradient(to bottom, transparent 11px, black 11px, black 12px, transparent 12px);
            background-size: 100% 12px;
            background-repeat: repeat-y;
        }

        .firmasDosPorDos {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 0;
        }

        .firmasDosPorDos td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 2px 12px 0 12px;
            font-size: 8px;
        }

        .firmasTres {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .firmasTres td {
            width: 33.333%;
            text-align: center;
            vertical-align: top;
            padding: 0 12px;
            font-size: 8px;
        }

        .firmasTres .espacioFirma {
            padding: 0;
        }

        .firmasTres .firmasLaterales td {
            padding-top: 10px;
        }

        .bloqueFirma {
            width: 100%;
        }

        .tituloFirma {
            font-weight: bold;
            line-height: 11px;
            min-height: 8px;
        }

        .lineaFirma {
            border-bottom: 1px solid black;
            height: 10px;
            margin-top: 0px;
            font-weight: bold;
            line-height: 10px;
            padding-top: 10px;
            box-sizing: border-box;
        }

        .cargoFirma,
        .empresaFirma,
        .fichaFirma {
            margin-top: 2px;
            font-weight: bold;
            line-height: 10px;
        }

        .tablaPrueba {
            border-collapse: collapse;
            width: 20%;
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
        }

        .filaGeneral {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
            table-layout: fixed;
        }

        .filaGeneral th,
        .filaGeneral td {
            padding: 1px 3px;
            vertical-align: bottom;
        }

        .filaGeneral th {
            text-align: left;
            font-weight: bold;
            line-height: 10px;
        }

        .filaGeneral .valorGeneral {
            border-bottom: .5px solid black;
            text-align: center;
            height: 13px;
        }

        .filaGeneral .valorGeneralAlto {
            height: 15px;
        }

        .tablaDureza {
            border-collapse: collapse;
            width: 53%;
            font-size: 8px;
        }

        .tablaDureza th,
        .tablaDureza td {
            border: .3px solid black;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
        }

        .tituloDureza {
            background-color: #DBDBDB;
            font-weight: bold;
            text-align: left;
        }

        .longitudDureza {
            text-align: left;
            font-weight: bold;
        }
        .tablaDurezaResumen {
            width: 40%;
            border-collapse: collapse;
            margin-left: 0;
        }

        .fotoDurezaBox {
            position: absolute;
            left: 56%;
            top: 400px;
            width: 43.7%;
            height: 250px;
            border: none;
            text-align: center;
            overflow: hidden;
        }

        .fotoDurezaBox img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            display: block;
        }

        .fotoDurezaComentario {
            border-top: none;
            height: 20px;
            margin: 0;
            padding: 5px 3px 0 3px;
            text-align: center;
            font-size: 8px;
            line-height: 5px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
@php
    $filasDureza = [];
    foreach (($Grupo_Juntas_Detalles_Re ?? []) as $bloque) {
        if (is_array($bloque) && array_key_exists('tipo', $bloque)) {
            $filasDureza[] = $bloque;
            continue;
        }

        if (is_array($bloque)) {
            foreach ($bloque as $item) {
                if (is_array($item) && array_key_exists('tipo', $item)) {
                    $filasDureza[] = $item;
                }
            }
        }
    }
@endphp

<header>
    <table class="tablaheader">
        <thead>
            <tr>
                <th style="width:360%">FORMATO<br>Format</th>
                <th rowspan="3" style="width:70%">
                    @if(!empty($QR_PDF))
                        <img src="{{ $QR_PDF }}" alt="QR de documentos" style="width:58px; height:58px; display:block; margin:auto; padding:0;">
                    @endif
                </th>
                <th style="width:60%">Código<br>Code</td>
                <th style="width:100%">FOR-PIMP-02_B/03</th>
                <th rowspan="3" style="width:80%"><img src="{{ $Logo }}" alt="Logo" style="width:55%; height:auto"></th>
            </tr>
            <tr>
                <th rowspan="2">Informe de Ensayo de Durezas en Metales Base<br>Hardness Test Report on Base Metals</th>
                <th>VERSIÓN<br>Version:</td>
                <th>2</th>
            </tr>
            <tr>
                <th>PÁGINA<br>Page:</th>
                <th></th>
            </tr>
        </thead>
    </table>
</header>
<footer>
        <table class="observacionesBox">
            <tr>
                <td>
                    <div class="observacionesTitulo">OBSERVACIONES O CONCLUSIONES:<br>Remarks:</div>
                    <div class="observacionesLineas">{{ $Datos_Equipo['Observaciones'] ?? '' }}</div>
                </td>
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
                        <td style="width: 200px; height:40px; vertical-align: bottom;" class="lineaInferior"><strong>{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</strong></td>
                        <td></td>
                        <td style="width: 200px; height:40px; vertical-align: bottom;" class="lineaInferior"><strong>{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</strong></td>
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
                        <td colspan="9" style="padding: 0;">
                            <table class="firmasTres">
                                <tr>
                                    <td class="espacioFirma"></td>
                                    <td>
                                        <div class="bloqueFirma">
                                            <div class="tituloFirma">{{ $Firmas_Reportes['Vobo1'] }}</div>
                                            <div class="lineaFirma">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</div>
                                            <div class="cargoFirma">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</div>
                                            <div class="empresaFirma">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</div>
                                        </div>
                                    </td>
                                    <td class="espacioFirma"></td>
                                </tr>
                                <tr class="firmasLaterales">
                                    <td>
                                        <div class="bloqueFirma">
                                            <div class="tituloFirma">{{ $Firmas_Reportes['Realizo'] }}</div>
                                            <div class="lineaFirma">{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</div>
                                            <div class="cargoFirma">{{ $Firmas_Reportes['CARGO_TECNICO'] }}</div>
                                            <div class="empresaFirma">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
                                        </div>
                                    </td>
                                    <td class="espacioFirma"></td>
                                    <td>
                                        <div class="bloqueFirma">
                                            <div class="tituloFirma">{{ $Firmas_Reportes['Vobo2'] }}</div>
                                            <div class="lineaFirma">{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</div>
                                            <div class="cargoFirma">{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</div>
                                            <div class="empresaFirma">{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</div>
                                            <div class="fichaFirma">{{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @elseif( $numFirmas == 4)
                <!-- 4 Firmas -->
                    <tr>
                        <td colspan="9" style="padding: 0;">
                            <table class="firmasDosPorDos">
                                <tr>
                                    <td>
                                        <div class="bloqueFirma">
                                            <div class="tituloFirma">{{ $Firmas_Reportes['Realizo'] }}</div>
                                            <div class="lineaFirma">{{ $Firmas_Reportes['NOMBRE_TECNICO'] }}</div>
                                            <div class="cargoFirma">{{ $Firmas_Reportes['CARGO_TECNICO'] }}</div>
                                            <div class="empresaFirma">Asesoría e Inspección en Construcción Costa Fuera, S.C.</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="bloqueFirma">
                                            <div class="tituloFirma">{{ $Firmas_Reportes['Vobo1'] }}</div>
                                            <div class="lineaFirma">{{ $Firmas_Reportes['NOMBRE_ENCARGADO'] }}</div>
                                            <div class="cargoFirma">{{ $Firmas_Reportes['PUESTO_ENCARGADO'] }}</div>
                                            <div class="empresaFirma">{{ $Firmas_Reportes['EMPRESA_ENCARGADO'] }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 16px;">
                                        <div class="bloqueFirma">
                                            <div class="tituloFirma">{{ $Firmas_Reportes['Vobo2'] }}</div>
                                            <div class="lineaFirma">{{ $Firmas_Reportes['NOMBRE_2DO_ENCARGADO'] }}</div>
                                            <div class="cargoFirma">{{ $Firmas_Reportes['PUESTO_2DO_ENCARGADO'] }}</div>
                                            <div class="empresaFirma">{{ $Firmas_Reportes['EMPRESA_2DO_ENCARGADO'] }}</div>
                                        </div>
                                    </td>
                                    <td style="padding-top: 16px;">
                                        <div class="bloqueFirma">
                                            <div class="tituloFirma">{{ $Firmas_Reportes['Vobo3'] }}</div>
                                            <div class="lineaFirma">{{ $Firmas_Reportes['NOMBRE_3RO_ENCARGADO'] }}</div>
                                            <div class="cargoFirma">{{ $Firmas_Reportes['PUESTO_3RO_ENCARGADO'] }}</div>
                                            <div class="empresaFirma">{{ $Firmas_Reportes['EMPRESA_3RO_ENCARGADO'] }}</div>
                                            <div class="fichaFirma">{{ $Firmas_Reportes['NUMERO_FICHA'] ?? '' }}</div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
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
            <th>DATOS GENERALES<br>General Data</th>
        </tr>
    </thead>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">FECHA:<br>Date</th>
            <td class="valorGeneral" style="width: 57%;">{{ $Detalles_Generales['Fecha'] ?? '' }}</td>
            <th style="width: 14%;">No. REPORTE:<br>No. Report</th>
            <td class="valorGeneral" style="width: 18%;">{{ $Detalles_Generales['No_Reporte'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">CLIENTE:<br>Client:</th>
            <td class="valorGeneral" style="width: 57%;">{{ $Detalles_Generales['Cliente'] ?? '' }}</td>
            <th style="width: 14%;">No. CONTRATO:<br>No. Contract:</th>
            <td class="valorGeneral" style="width: 18%;">{{ $Detalles_Generales['Contrato'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">PROYECTO:<br>Project:</th>
            <td class="valorGeneral" style="width: 89%;">{{ $Detalles_Generales['Proyecto'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 22%;">ORDEN DE TRABAJO:<br>Work Order:</th>
            <td class="valorGeneral" style="width: 78%;">{{ $Detalles_Generales['Orden_Trabajo'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">FOLIO:<br>Folio:</th>
            <td class="valorGeneral" style="width: 89%;">{{ $Detalles_Generales['Folio'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">PARTIDA:<br>Lot:</th>
            <td class="valorGeneral" style="width: 89%;">{{ $Detalles_Generales['Partida'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">INSTALACIÓN:<br>Location:</th>
            <td class="valorGeneral valorGeneralAlto" style="width: 46%;">{{ $Detalles_Generales['Instalacion'] ?? '' }}</td>
            <th style="width: 12%;">No. ISOMÉTRICO:<br>No. Isometric:</th>
            <td class="valorGeneral valorGeneralAlto" style="width: 31%;">{{ $Detalles_Generales['No_Isometrico'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 22%;">NOMBRE DE LA PIEZA:<br>Name of the piece:</th>
            <td class="valorGeneral valorGeneralAlto" style="width: 35%;">{{ $Detalles_Generales['Nom_Pieza'] ?? '' }}</td>
            <th style="width: 12%;">MATERIAL:<br>Material:</th>
            <td class="valorGeneral valorGeneralAlto" style="width: 31%;">{{ $Detalles_Generales['Material'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">PROCEDIMIENTO:<br>Procedure:</th>
            <td class="valorGeneral" style="width: 46%;">{{ $Detalles_Generales['Procedimiento'] ?? '' }}</td>
            <th style="width: 12%;">TRAZABILIDAD:<br>Traceability:</th>
            <td class="valorGeneral" style="width: 31%;">{{ $Detalles_Generales['Trazabilidad'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<table class="filaGeneral">
    <tbody>
        <tr>
            <th style="width: 11%;">ACCESORIO:<br>Fitting:</th>
            <td class="valorGeneral" style="width: 22%;">{{ $Detalles_Generales['Accesorio'] ?? '' }}</td>
            <th style="width: 11%;">TUBERIA:<br>Tube:</th>
            <td class="valorGeneral" style="width: 15%;">{{ $Detalles_Generales['Tuberia'] ?? '' }}</td>
            <th style="width: 13%;">ESTRUCTURAL:<br>Structural:</th>
            <td class="valorGeneral" style="width: 28%;">{{ $Detalles_Generales['Estructural'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 3px;"></div>
<table class="datosinspeccion">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">DATOS DE LA PRUEBA<br>Test Data</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th style="width: 8%;">MÉTODO<br>Method:</th>
            <td style="width: 11%;">{{ $Detalles_Generales['Metodo'] ?? '' }}</td>
            <th style="width: 21%;">TEMPERATURA DE LA PIEZA<br>Piece Temperature:</th>
            <td style="width: 14%;">{{ $Detalles_Generales['Temp_Pieza'] ?? '' }}</td>
            <th style="width: 16%;">ESPESOR/CEDÚLA<br>Thickness/Schedule:</th>
            <td style="width: 17%;">{{ $Detalles_Generales['Esp_Ced'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 3px;"></div>
{{-- ================= DATOS DEL EQUIPO ================= --}}
<table class="datosinspeccion">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="6">
                DATOS DEL EQUIPO<br>
                Equipment Data
            </th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <th style="width:12%;">
                MARCA:<br>Brand
            </th>

            <td style="width:30%;">
                {{ $Datos_Equipo['MARCA_EQUIPO'] ?? '' }}
            </td>

            <th style="width:13%;">
                MODELO:<br>Model
            </th>

            <td style="width:20%;">
                {{ $Datos_Equipo['MODELO_EQUIPO'] ?? '' }}
            </td>
            <th style="width:13%;">
                NO. DE SERIE:<br>Serial Number
            </th>

            <td style="width:24%;">
                {{ $Datos_Equipo['NS_EQUIPO'] ?? '' }}
            </td>
        </tr>
    </tbody>
</table>
<div style="margin-bottom: 12px;"></div>
{{-- ================= DATOS DE DUREZA ================= --}}
<table class="tablaDureza">
    <thead class="encabezadoAzul">
        <tr>
            <th colspan="5">VALORES DE DUREZA MEDIDOS EN {{ $Datos_Equipo['ESCALA_DUREZA'] ?? '' }}<br>Measured Hardness Values ({{ $Datos_Equipo['ESCALA_DUREZA'] ?? '' }})</th>
        </tr>
    </thead>
    <tbody>
        @php $contadorDureza = 1; @endphp
        @forelse($filasDureza as $item)
            @if(($item['tipo'] ?? '') === 'titulo')
                <tr>
                    <td class="tituloDureza" colspan="5">{{ $item['texto'] ?? '' }}</td>
                </tr>
            @elseif(($item['tipo'] ?? '') === 'longitud')
                <tr>
                    <td class="longitudDureza" colspan="5">LONGITUD INSPECCIONADA: {{ $item['valor'] ?? '' }}</td>
                </tr>
            @elseif(($item['tipo'] ?? '') === 'fila')
                <tr>
                    <td>{{ $item['data']['valor_dureza1'] ?? '' }}</td>
                    <td>{{ $item['data']['valor_dureza2'] ?? '' }}</td>
                    <td>{{ $item['data']['valor_dureza3'] ?? '' }}</td>
                    <td>{{ $item['data']['valor_dureza4'] ?? '' }}</td>
                    <td>{{ $item['data']['valor_dureza5'] ?? '' }}</td>
                </tr>
            @endif
        @empty
            <tr>
                <td colspan="5">Sin valores registrados</td>
            </tr>
        @endforelse
    </tbody>
</table>
<div style="margin-bottom: 10px;"></div>
<table class="datosinspeccion" style="width:42.5%;">
    <tbody>
        <tr>
            <th style="width: 88%;">DUREZA PROMEDIO MEDIDO<br>Measured Average Hardness</th>
            <td style="width: 30%;">{{ $Datos_Equipo['DUREZA_PROMEDIO_MEDIDO'] ?? '' }}</td>
        </tr>
        <tr>
            <th style="width: 88%;">DUREZA DE ACUERDO A LA ESPECIFICACION DE REFERENCIA<br>Hardness According To Reference Specification</th>
            <td style="width: 30%;">{{ $Datos_Equipo['DUREZA_ESPECIFICACION_REFERENCIA'] ?? '' }}</td>
        </tr>
    </tbody>
</table>
<div class="fotoDurezaBox">
    @if(!empty($Fotos[0]['path']))
        <img src="{{ $Fotos[0]['path'] }}" alt="Imagen del reporte">
    @endif
    <p class="fotoDurezaComentario">{{ $Fotos[0]['comment'] ?? '' }}</p>
</div>

</body>
</html>
