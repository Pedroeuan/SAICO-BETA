<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FOR-PIMP-07_B/01</title>

    <style>
        @page {
            margin: 3cm 1.2cm 2cm 2cm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* ENCABEZADO */
        .tablaheader th {
            border: 1px solid black;
            font-size: 10px;
            padding: 4px;
            text-align: center;
        }

        .encabezadoAzul {
            background-color: #305496;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            font-size: 9px;
        }

        /* TABLAS GENERALES */
        .datos th,
        .datos td {
            border: .6px solid black;
            padding: 3px;
            font-size: 9px;
        }

        .label {
            font-weight: bold;
            background-color: #DBDBDB;
        }

        .linea {
            border-bottom: 1px solid black;
        }

        input {
            width: 100%;
            border: none;
            font-size: 9px;
        }

        textarea {
            width: 100%;
            height: 80px;
            border: 1px solid black;
            resize: none;
            font-size: 9px;
        }

        .signature {
            height: 70px;
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body>

<header>
    <table class="tablaheader">
        <tr>
            <th style="width: 40%;">FORMATO</th>
            <th style="width: 10%;">Código:</th>
            <th style="width: 15%;">FOR-PIMP-07_B-01</th>
            <th rowspan="3" style="width: 35%;">
                <img src="{{ $Logo }}" style="width: 55%;">
            </th>
        </tr>

        <tr>
            <th rowspan="2">Informe de Relevado de Esfuerzos / Stress Relief Report</th>
            <th>Versión</th>
            <th>1</th>
        </tr>

        <tr>
            <th>Página</th>
            <th></th>
        </tr>
    </table>
</header>

<br>


{{-- ================================
        DATOS GENERALES
================================ --}}
<table class="datos">
    <tr class="encabezadoAzul">
        <th colspan="4">DATOS GENERALES / GENERAL DATA</th>
    </tr>

    <tr>
        <th class="label">FECHA:</th>
        <td class="linea"><input></td>

        <th class="label">NO. REPORTE:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">CLIENTE:</th>
        <td class="linea"><input></td>

        <th class="label">NO. CONTRATO:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">PROYECTO:</th>
        <td class="linea"><input></td>

        <th class="label">ORDEN DE TRABAJO:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">FOLIO:</th>
        <td class="linea"><input></td>

        <th class="label">PARTIDA:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">INSTALACIÓN:</th>
        <td class="linea"><input></td>

        <th class="label">NO. ISOMÉTRICO:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">ELEMENTOS SOLDADOS:</th>
        <td class="linea"><input></td>

        <th class="label">MATERIAL:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">NO. JUNTA:</th>
        <td class="linea"><input></td>

        <th class="label">TRAZABILIDAD:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">ESPESORES:</th>
        <td class="linea"><input></td>

        <th class="label">DIÁM. NOMINAL:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">PROCEDIMIENTO:</th>
        <td class="linea"><input value="PRO-PIMP-07_B"></td>

        <th class="label">CÓDIGO DE DISEÑO:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">REPORTE DUREZA ANTES:</th>
        <td class="linea"><input></td>

        <th class="label">REPORTE DUREZA DESPUÉS:</th>
        <td class="linea"><input></td>
    </tr>
</table>

<br>


{{-- ================================
        DATOS DEL EQUIPO
================================ --}}
<table class="datos">
    <tr class="encabezadoAzul">
        <th colspan="4">DATOS DEL EQUIPO / EQUIPMENT DATA</th>
    </tr>

    <tr class="label">
        <th>EQUIPO</th>
        <th>MARCA</th>
        <th>MODELO</th>
        <th>No. SERIE</th>
    </tr>

    <tr>
        <td>MAQUINA DE RELEVADO</td>
        <td><input></td>
        <td><input></td>
        <td><input></td>
    </tr>

    <tr>
        <td>GRAFICADOR</td>
        <td><input></td>
        <td><input></td>
        <td><input></td>
    </tr>
</table>

<br>


{{-- ================================
        DATOS DE PRUEBA
================================ --}}
<table class="datos">
    <tr class="encabezadoAzul">
        <th colspan="4">DATOS DE PRUEBA / TEST DATA</th>
    </tr>

    <tr>
        <th class="label">TEMPERATURA INICIAL (°F):</th>
        <td class="linea"><input></td>

        <th class="label">HORA INICIO:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">VEL. CALENTAMIENTO (°F/hr):</th>
        <td class="linea"><input></td>

        <th class="label">HORA FIN:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">TEMP. SOSTENIMIENTO (°F):</th>
        <td class="linea"><input></td>

        <th class="label">DÍA INICIO:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">TIEMPO DE SOSTENIMIENTO (MIN):</th>
        <td class="linea"><input></td>

        <th class="label">DÍA FIN:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">VEL. ENFRIAMIENTO (°F/hr):</th>
        <td class="linea"><input></td>

        <th class="label">No. GRÁFICA:</th>
        <td class="linea"><input></td>
    </tr>

    <tr>
        <th class="label">VEL. DEL GRAFICADO (mm/hr):</th>
        <td class="linea"><input></td>

        <td colspan="2"></td>
    </tr>
</table>

<br>


{{-- ================================
        OBSERVACIONES
================================ --}}
<table class="datos">
    <tr>
        <th class="label">OBSERVACIONES / REMARKS</th>
    </tr>
    <tr>
        <td><textarea></textarea></td>
    </tr>
</table>

<br>


{{-- ================================
        FIRMAS
================================ --}}
<table class="datos">
    <tr class="label">
        <th>ELABORÓ</th>
        <th>APROBÓ</th>
        <th>CLIENTE</th>
        <th>CLIENTE</th>
    </tr>

    <tr>
        <td class="signature"></td>
        <td class="signature"></td>
        <td class="signature"></td>
        <td class="signature"></td>
    </tr>

    <tr>
        <td>Asesoría e Inspección en Construcción Costa Fuera, S.C.</td>
        <td>Asesoría e Inspección en Construcción Costa Fuera, S.C.</td>
        <td></td>
        <td></td>
    </tr>
</table>

</body>
</html>
