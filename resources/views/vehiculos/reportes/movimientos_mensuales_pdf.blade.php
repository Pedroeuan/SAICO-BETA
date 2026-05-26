<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Mensual de Movimientos</title>
    <style>
        @page { margin: 18px; }

        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 16px; 
            color: #111827; 
        }

        .header { 
            border-bottom: 2px solid #0f172a; 
            padding-bottom: 8px; 
            margin-bottom: 12px; 
        }
        .mini { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .mini th, .mini td { border: 1px solid #333; padding: 4px 6px; font-size: 12px; }
        .mini th { background: #f7f7f7; text-align: left; }

        .title { 
            font-size: 28px; 
            font-weight: bold; 
            margin: 0; 
            color: #0f172a; 
        }

        .subtitle { 
            margin: 4px 0 0; 
            color: #475569; 
            font-size: 15px; 
        }

        .section { 
            margin: 10px 0 5px; 
            font-size: 18px; 
            font-weight: bold; 
            color: #0f172a; 
        }

        .kpis { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px; 
        }

        .kpis td { 
            width: 25%; 
            border: 1px solid #d1d5db; 
            padding: 10px; 
            vertical-align: top; 
        }

        .kpi-label { 
            color: #6b7280; 
            font-size: 13px; 
        }

        .kpi-value { 
            font-size: 24px; 
            font-weight: bold; 
            margin-top: 5px; 
            color: #111827; 
        }

        .desc { 
            margin: 0 0 8px; 
            color: #374151; 
            font-size: 14px; 
            line-height: 1.3; 
        }

        .grid { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px; 
        }

        .grid td { 
            width: 50%; 
            vertical-align: top; 
            border: 1px solid #e5e7eb; 
            padding: 12px;
        }

        .table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        .table th, .table td { 
            border: 1px solid #d1d5db; 
            padding: 6px; 
            font-size: 13px; 
        }

        .table th { 
            background: #f3f4f6; 
            text-align: left; 
            font-size: 13px; 
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* 🔥 GRÁFICAS MÁS GRANDES */
        .chart-img { 
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
            margin: 8px auto;
        }

        .chart-big {
            width: 100%;
            max-width: 420px;
            height: auto;
            display: block;
            margin: 10px auto;
        }

        .indicator {
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            margin-top: 6px;
        }
    </style>
</head>
<body>

@php
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];

    $totalCantidad = max(1, $resumen['mantenimientos_count'] + $resumen['pagos_count'] + $resumen['combustible_count'] + $resumen['llantas_count']);
    $indicadoresCantidad = [
        'Mantenimientos' => ($resumen['mantenimientos_count'] / $totalCantidad) * 100,
        'Pagos' => ($resumen['pagos_count'] / $totalCantidad) * 100,
        'Combustible' => ($resumen['combustible_count'] / $totalCantidad) * 100,
        'Llantas' => ($resumen['llantas_count'] / $totalCantidad) * 100,
    ];

    $totalMonto = max(1, $resumen['mantenimientos_total'] + $resumen['pagos_total'] + $resumen['combustible_total'] + $resumen['llantas_total']);
    $indicadoresMonto = [
        'Mantenimiento' => ($resumen['mantenimientos_total'] / $totalMonto) * 100,
        'Pagos' => ($resumen['pagos_total'] / $totalMonto) * 100,
        'Combustible' => ($resumen['combustible_total'] / $totalMonto) * 100,
        'Llantas' => ($resumen['llantas_total'] / $totalMonto) * 100,
    ];

    $costoPromedioKm = $resumen['km_recorridos_total'] > 0
        ? $resumen['total_general'] / $resumen['km_recorridos_total']
        : 0;
@endphp

@php
    $logoCandidates = [
        $Logo ?? null,
        public_path('images/Logo_AICO_R.jpg'),
        public_path('images/Logo_AICO_R.png'),
        public_path('img/Logo_AICO_R.jpg'),
        public_path('img/Logo_AICO_R.png'),
    ];
    $LogoFinal = null;
    foreach ($logoCandidates as $candidate) {
        if (!empty($candidate) && file_exists($candidate)) {
            $LogoFinal = $candidate;
            break;
        }
    }
@endphp

<table class="mini">
    <tr>
        <th rowspan="3" style="width: 22%; text-align: center; background: #fff;">
            @if(!empty($LogoSrc))
                <img src="{{ $LogoSrc }}" alt="Logo" style="width:auto; max-width:145px; max-height:44px; height:auto; display:block; margin:0 auto;">
            @elseif($LogoFinal)
                <img src="{{ $LogoFinal }}" alt="Logo" style="width:auto; max-width:145px; max-height:44px; height:auto; display:block; margin:0 auto;">
            @else
                <span style="font-size:10px; color:#666;">Sin logo</span>
            @endif
        </th>
        <th style="width: 78%; text-align: center;">Reporte Ejecutivo Mensual del Parque Vehicular</th>
    </tr>
    <tr>
        <td><strong>Panel Vehicular</strong></td>
    </tr>
    <tr>
        <td>Periodo: {{ $meses[$mesSeleccionado] ?? $mesSeleccionado }} {{ $anioSeleccionado }}</td>
    </tr>
</table>

<table class="kpis">
    <tr>
        <td>
            <div class="kpi-label">Vehiculos con movimientos</div>
            <div class="kpi-value">{{ $resumen['vehiculos_con_movimientos'] }}</div>
        </td>
        <td>
            <div class="kpi-label">Cantidad total de movimientos</div>
            <div class="kpi-value">
                {{ $resumen['mantenimientos_count'] + $resumen['pagos_count'] + $resumen['combustible_count'] + $resumen['llantas_count'] }}
            </div>
        </td>
        <td>
            <div class="kpi-label">Uso efectivo</div>
            <div class="kpi-value">{{ $resumen['salidas_count'] }} salidas</div>
        </td>
        <td>
            <div class="kpi-label">Monto total mensual</div>
            <div class="kpi-value">
                ${{ number_format($resumen['total_general'], 2) }}
            </div>
        </td>
    </tr>
</table>

<p class="desc">
    Total Mantenimientos: <strong>${{ number_format($resumen['mantenimientos_total'], 2) }}</strong> |
    Total Pagos Administrativos: <strong>${{ number_format($resumen['pagos_total'], 2) }}</strong> |
    Total Combustible: <strong>${{ number_format($resumen['combustible_total'], 2) }}</strong> |
    Total Llantas: <strong>${{ number_format($resumen['llantas_total'], 2) }}</strong> |
    Total General del Mes: <strong>${{ number_format($resumen['total_general'], 2) }}</strong>
</p>

<p class="desc">
    Se registraron <strong>{{ $resumen['mantenimientos_count'] }}</strong> mantenimientos y
    <strong>{{ $resumen['pagos_count'] }}</strong> pagos administrativos,
    <strong>{{ $resumen['combustible_count'] }}</strong> cargas de combustible y
    <strong>{{ $resumen['llantas_count'] }}</strong> movimientos de llantas.
</p>

<p class="desc">
    Uso efectivo del periodo: <strong>{{ $resumen['salidas_count'] }}</strong> salidas,
    <strong>{{ $resumen['salidas_finalizadas'] }}</strong> finalizadas y
    <strong>{{ number_format($resumen['km_recorridos_total'], 2) }} km</strong> recorridos.
    Costo promedio por km: <strong>${{ number_format($costoPromedioKm, 2) }}</strong>
</p>

<div class="section">Conceptos de gasto registrados (mes)</div>
<table class="table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Placa</th>
            <th>Origen</th>
            <th>Concepto</th>
            <th class="text-right">Monto</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($detalleGastos ?? collect()) as $d)
            <tr>
                <td>{{ \Carbon\Carbon::parse($d->fecha)->format('d/m/Y') }}</td>
                <td>{{ $d->placa }}</td>
                <td>{{ $d->origen }}</td>
                <td>{{ ucfirst((string) $d->concepto) }}</td>
                <td class="text-right">${{ number_format((float) $d->monto, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Sin conceptos registrados en el periodo</td>
            </tr>
        @endforelse
    </tbody>
</table>

<table class="grid">
    <tr>
        <td>
            <div class="section" style="margin-top:0;">Distribucion por cantidad</div>
            <img class="chart-img" src="{{ $chartCantidadSrc }}" alt="Distribucion por cantidad">
            <div class="indicator">
                @foreach($indicadoresCantidad as $label => $pct)
                    {{ $label }}: {{ number_format($pct, 1) }}%@if(!$loop->last) | @endif
                @endforeach
            </div>
        </td>
        <td>
            <div class="section" style="margin-top:0;">Distribucion por monto</div>
            <img class="chart-img" src="{{ $chartMontoSrc }}" alt="Distribucion por monto">
            <div class="indicator">
                @foreach($indicadoresMonto as $label => $pct)
                    {{ $label }}: {{ number_format($pct, 1) }}%@if(!$loop->last) | @endif
                @endforeach
            </div>
        </td>
    </tr>
</table>

<div class="section">Gasto por placa (Top 5) - Pastel 3D</div>

@php $top3 = $topPlacas->take(3); @endphp
@if($top3->isNotEmpty())
    <p class="desc">
        @foreach($top3 as $row)
            <strong>{{ $row->placa }}</strong>:
            Mantto ${{ number_format($row->mantenimientos_total, 2) }} |
            Pagos ${{ number_format($row->pagos_total, 2) }} |
            Combustible ${{ number_format($row->combustible_total, 2) }} |
            Llantas ${{ number_format($row->llantas_total, 2) }} |
            Km {{ number_format($row->km_recorridos_total, 2) }} |
            Total ${{ number_format($row->total_general, 2) }}
            @if(!$loop->last) <br> @endif
        @endforeach
    </p>
@endif

<table class="grid">
    <tr>
        <td>
            <img class="chart-big" src="{{ $chartPlacasSrc }}" alt="Gasto por placa">
        </td>
        <td>
            <table class="table">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th class="text-right">Km</th>
                        <th class="text-right">Mantto.</th>
                        <th class="text-right">Pagos</th>
                        <th class="text-right">Comb.</th>
                        <th class="text-right">Llantas</th>
                        <th class="text-right">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPlacas as $row)
                        <tr>
                            <td>{{ $row->placa }}</td>
                            <td class="text-right">{{ number_format($row->km_recorridos_total, 2) }}</td>
                            <td class="text-right">${{ number_format($row->mantenimientos_total, 2) }}</td>
                            <td class="text-right">${{ number_format($row->pagos_total, 2) }}</td>
                            <td class="text-right">${{ number_format($row->combustible_total, 2) }}</td>
                            <td class="text-right">${{ number_format($row->llantas_total, 2) }}</td>
                            <td class="text-right">
                                ${{ number_format($row->total_general, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Sin datos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </td>
    </tr>
</table>

<div class="section">Resumen ejecutivo por unidad</div>
<table class="table">
    <thead>
        <tr>
            <th>Vehiculo</th>
            <th class="text-center">Salidas</th>
            <th class="text-right">Km</th>
            <th class="text-right">Mantto.</th>
            <th class="text-right">Pagos</th>
            <th class="text-right">Combustible</th>
            <th class="text-right">Llantas</th>
            <th class="text-right">Total</th>
            <th class="text-right">Costo / km</th>
        </tr>
    </thead>
    <tbody>
        @forelse($movimientosMensuales as $row)
            <tr>
                <td>{{ $row->placa }}<br><span style="font-size:11px; color:#6b7280;">{{ $row->marca }} {{ $row->modelo }}</span></td>
                <td class="text-center">{{ $row->salidas_count }} / {{ $row->salidas_finalizadas }}</td>
                <td class="text-right">{{ number_format($row->km_recorridos_total, 2) }}</td>
                <td class="text-right">${{ number_format($row->mantenimientos_total, 2) }}</td>
                <td class="text-right">${{ number_format($row->pagos_total, 2) }}</td>
                <td class="text-right">${{ number_format($row->combustible_total, 2) }}</td>
                <td class="text-right">${{ number_format($row->llantas_total, 2) }}</td>
                <td class="text-right">${{ number_format($row->total_general, 2) }}</td>
                <td class="text-right">
                    ${{ number_format($row->km_recorridos_total > 0 ? ($row->total_general / $row->km_recorridos_total) : 0, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">Sin movimientos para el periodo</td>
            </tr>
        @endforelse
    </tbody>
</table>

<p class="desc">
    Periodo: <strong>{{ $meses[$mesSeleccionado] ?? $mesSeleccionado }} {{ $anioSeleccionado }}</strong> |
    Corte: <strong>{{ now()->format('d/m/Y H:i') }}</strong>
</p>

</body>
</html>
