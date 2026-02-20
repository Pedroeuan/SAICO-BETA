<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Rendimiento de Vehiculos</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 4px 0; }
        h2 { font-size: 13px; margin: 14px 0 6px 0; }
        .meta { margin-bottom: 10px; }
        .kpis { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .kpis td { padding: 6px 8px; border: 1px solid #ddd; width: 25%; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 5px 6px; text-align: left; }
        th { background: #f2f2f2; }
        .muted { color: #666; font-size: 10px; }
        .bar-wrap { width: 100%; background: #f1f5f9; border: 1px solid #dbe3ea; height: 11px; }
        .bar { height: 11px; }
        .mb-8 { margin-bottom: 8px; }
    </style>
</head>
<body>
    <h1>Rendimiento de Vehiculos - {{ $periodo }}</h1>
    <div class="meta">
        <strong>Rango:</strong> {{ $inicio->format('d/m/Y') }} - {{ $fin->format('d/m/Y') }}
    </div>

    <table class="kpis">
        <tr>
            <td><strong>Total salidas</strong><br>{{ $totalSalidas }}</td>
            <td><strong>Salidas activas</strong><br>{{ $salidasActivas }}</td>
            <td><strong>Salidas finalizadas</strong><br>{{ $salidasFinalizadas }}</td>
            <td><strong>Tiempo promedio (min)</strong><br>{{ round($tiempoPromedio) }}</td>
        </tr>
    </table>

    <h2>Grafica: Salidas por estatus</h2>
    @foreach ($estatusChart as $statusItem)
        @php
            $ancho = round(($statusItem['valor'] / $maxEstatus) * 100, 2);
        @endphp
        <table class="mb-8">
            <tr>
                <td style="width: 24%;">{{ $statusItem['label'] }}</td>
                <td style="width: 66%;">
                    <div class="bar-wrap">
                        <div class="bar" style="width: {{ $ancho }}%; background: {{ $statusItem['color'] }};"></div>
                    </div>
                </td>
                <td style="width: 10%; text-align: right;">{{ $statusItem['valor'] }}</td>
            </tr>
        </table>
    @endforeach

    <h2>Grafica: Top vehiculos por salidas</h2>
    @foreach ($porVehiculo->take(8) as $item)
        @php
            $ancho = round(($item->total / $maxTotalVehiculo) * 100, 2);
            $placa = $item->vehiculo->placa ?? 'N/A';
            $marca = $item->vehiculo->marca ?? 'N/A';
        @endphp
        <table class="mb-8">
            <tr>
                <td style="width: 24%;">{{ $placa }} <span class="muted">({{ $marca }})</span></td>
                <td style="width: 66%;">
                    <div class="bar-wrap">
                        <div class="bar" style="width: {{ $ancho }}%; background: #2563eb;"></div>
                    </div>
                </td>
                <td style="width: 10%; text-align: right;">{{ $item->total }}</td>
            </tr>
        </table>
    @endforeach

    <h2>Detalle general por vehiculo</h2>
    <table>
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
            @forelse ($porVehiculo as $item)
                @php
                    $vehiculo = $item->vehiculo;
                    $totalMin = (int) ($item->total_minutos ?? 0);
                    $promMin = (float) ($item->promedio_minutos ?? 0);
                @endphp
                <tr>
                    <td>{{ $vehiculo->placa ?? 'N/A' }}</td>
                    <td>{{ $vehiculo->marca ?? 'N/A' }}</td>
                    <td>{{ $vehiculo->modelo ?? 'N/A' }}</td>
                    <td>{{ $vehiculo->anio ?? 'N/A' }}</td>
                    <td>{{ $vehiculo->estatus ?? 'N/A' }}</td>
                    <td>{{ $vehiculo->kilometraje_actual ?? 0 }}</td>
                    <td>{{ $vehiculo->documentacion_estatus ?? 'N/A' }}</td>
                    <td>{{ $item->total }}</td>
                    <td>{{ round($totalMin / 60, 2) }}</td>
                    <td>{{ round($promMin / 60, 2) }}</td>
                    <td>{{ $item->ultima_salida ? \Carbon\Carbon::parse($item->ultima_salida)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">No hay salidas en el periodo seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
