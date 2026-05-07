<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Rendimiento de Vehiculos</title>
    <style>
        @page { size: letter portrait; margin: 10mm 9mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9.5px; color: #111; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 3px 4px; vertical-align: top; }
        th { background: #f7f7f7; text-align: left; }
        .mini td, .mini th { padding: 2px 3px; font-size: 8.6px; }
        .kpis td { width: 25%; }
        .section { margin: 7px 0 4px; font-weight: bold; font-size: 11px; }
        .panel-title { font-size: 10px; font-weight: bold; color: #0f172a; margin-bottom: 4px; }
        .pie-grid td { width: 50%; border: 1px solid #cbd5e1; padding: 6px; }
        .pie-img { width: 100%; max-width: 340px; height: auto; display: block; margin: 4px auto; }
        .tiny { font-size: 8px; color: #64748b; }
    </style>
</head>
<body>

<table class="mini" style="margin-bottom: 6px;">
    <tr>
        <th rowspan="3" style="width: 18%; text-align: center; background: #fff;">
            @if(!empty($logoSrc))
                <img src="{{ $logoSrc }}" alt="Logo" style="width:auto; max-width:95px; max-height:30px; height:auto; display:block; margin:0 auto;">
            @else
                <span style="font-size:8px; color:#666;">Sin logo</span>
            @endif
        </th>
        <th style="width: 64%; text-align: center;">Rendimiento de Vehiculos - {{ $periodo }}</th>
        <th rowspan="3" style="width: 18%; text-align: center; background: #fff;">FOR-VEH-PANEL</th>
    </tr>
    <tr>
        <td><strong>Panel Vehicular</strong></td>
    </tr>
    <tr>
        <td>Rango: {{ $inicio->format('d/m/Y') }} - {{ $fin->format('d/m/Y') }}</td>
    </tr>
</table>

<table class="mini kpis" style="margin-bottom: 8px;">
    <tr>
        <td><strong>Total salidas</strong><br>{{ $totalSalidas }}</td>
        <td><strong>Salidas activas</strong><br>{{ $salidasActivas }}</td>
        <td><strong>Salidas finalizadas</strong><br>{{ $salidasFinalizadas }}</td>
        <td><strong>Tiempo promedio (min)</strong><br>{{ round($tiempoPromedio) }}</td>
    </tr>
</table>

<div class="section">Detalle general por vehiculo</div>
<table class="mini" style="margin-bottom: 8px;">
    <thead>
        <tr>
            <th>Placa</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Anio</th>
            <th>Estatus</th>
            <th>Km Actual</th>
            <th>Doc. Estatus</th>
            <th>Total salidas</th>
            <th>Total horas</th>
            <th>Promedio horas</th>
            <th>Ultima salida</th>
        </tr>
    </thead>
    <tbody>
        @forelse($porVehiculo as $item)
            @php
                $totalMin = (int) ($item->total_minutos ?? 0);
                $promMin = (float) ($item->promedio_minutos ?? 0);
            @endphp
            <tr>
                <td>{{ $item->placa ?? 'N/A' }}</td>
                <td>{{ $item->marca ?? 'N/A' }}</td>
                <td>{{ $item->modelo ?? 'N/A' }}</td>
                <td>{{ $item->anio ?? 'N/A' }}</td>
                <td>{{ $item->estatus ?? 'N/A' }}</td>
                <td>{{ $item->kilometraje_reporte ?? $item->kilometraje_actual ?? 0 }}</td>
                <td>{{ $item->documentacion_estatus ?? 'N/A' }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ round($totalMin / 60, 2) }}</td>
                <td>{{ round($promMin / 60, 2) }}</td>
                <td>{{ $item->ultima_salida ? \Carbon\Carbon::parse($item->ultima_salida)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11" style="text-align:center;">No hay salidas en el periodo seleccionado.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table class="pie-grid mini" style="margin-bottom: 8px;">
    <tr>
        <td>
            <div class="panel-title">Grafica pastel: Salidas por estatus</div>
            <img src="{{ $chartEstatusSrc }}" alt="Pastel estatus" class="pie-img">
            <div class="tiny">Ocupados {{ $salidasActivas }} | Disponible {{ $salidasFinalizadas }}</div>
        </td>
        <td>
            <div class="panel-title">Grafica pastel: Vehículos con más salidas</div>
            <img src="{{ $chartTopVehiculosSrc }}" alt="Pastel top vehiculos" class="pie-img">
            <div class="tiny">Top 5 por volumen de salidas</div>
        </td>
    </tr>
</table>

<table class="pie-grid mini" style="margin-bottom: 8px;">
    <tr>
        <td>
            <div class="panel-title">{{ $tituloSalidasPeriodo }}  </div>
            <img src="{{ $chartSalidasPeriodoSrc }}" alt="Pastel salidas periodo" class="pie-img">
        </td>
        <td>
            <div class="panel-title">Vehiculos más uso  </div>
            <img src="{{ $chartTopVehiculosSrc }}" alt="Pastel top vehiculos" class="pie-img">
        </td>
    </tr>
</table>

<table class="pie-grid mini" style="margin-bottom: 8px;">
    <tr>
        <td>
            <div class="panel-title">Usuarios con mas uso del vehículo  </div>
            <img src="{{ $chartChoferesSrc }}" alt="Pastel choferes" class="pie-img">
        </td>
        <td>
            <div class="panel-title">Recorrido de Km  </div>
            <img src="{{ $chartKmPeriodoSrc }}" alt="Pastel km periodo" class="pie-img">
        </td>
    </tr>
</table>

<table class="pie-grid mini" style="margin-bottom: 8px;">
    <tr>
        <td>
            <div class="panel-title">Observaciones de Vehiculo  </div>
            <img src="{{ $chartIncidenciasSrc }}" alt="Pastel incidencias" class="pie-img">
        </td>
        <td>
            <div class="panel-title">Resumen de Distribucion</div>
            <div class="tiny" style="margin-top: 6px;">Cada grafica muestra la proporcion del periodo seleccionado para lectura rapida.</div>
        </td>
    </tr>
</table>

</body>
</html>
