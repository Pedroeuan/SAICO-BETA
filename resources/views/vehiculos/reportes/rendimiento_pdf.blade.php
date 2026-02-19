<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Rendimiento de Vehículos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 16px; margin: 0 0 10px 0; }
        .meta { margin-bottom: 12px; }
        .kpis { width: 100%; margin-bottom: 12px; }
        .kpis td { padding: 6px 8px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Rendimiento de Vehículos - {{ $periodo }}</h1>
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

    <table>
        <thead>
            <tr>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Total salidas</th>
                <th>Total horas</th>
                <th>Promedio horas</th>
                <th>Última salida</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($porVehiculo as $item)
                @php
                    $totalMin = (int) ($item->total_minutos ?? 0);
                    $promMin = (float) ($item->promedio_minutos ?? 0);
                @endphp
                <tr>
                    <td>{{ $item->vehiculo->placa ?? 'N/A' }}</td>
                    <td>{{ $item->vehiculo->marca ?? 'N/A' }}</td>
                    <td>{{ $item->vehiculo->modelo ?? 'N/A' }}</td>
                    <td>{{ $item->total }}</td>
                    <td>{{ round($totalMin / 60, 2) }}</td>
                    <td>{{ round($promMin / 60, 2) }}</td>
                    <td>{{ $item->ultima_salida ? \Carbon\Carbon::parse($item->ultima_salida)->format('d/m/Y') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

