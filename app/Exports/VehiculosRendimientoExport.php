<?php

namespace App\Exports;

use App\Models\Vehiculos\SalidaVehiculo;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VehiculosRendimientoExport implements FromCollection, WithHeadings
{
    private Carbon $inicio;
    private Carbon $fin;

    public function __construct(Carbon $inicio, Carbon $fin)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    public function collection(): Collection
    {
        $baseRows = SalidaVehiculo::whereBetween('fecha_salida', [$this->inicio, $this->fin])
            ->select(
                'vehiculo_id',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(duracion_minutos) as total_minutos'),
                DB::raw('AVG(duracion_minutos) as promedio_minutos'),
                DB::raw('MAX(fecha_salida) as ultima_salida')
            )
            ->groupBy('vehiculo_id')
            ->orderByDesc('total')
            ->with('vehiculo')
            ->get();

        $totalSalidas = (int) $baseRows->sum('total');
        $maxTotal = max((int) ($baseRows->max('total') ?? 0), 1);

        return $baseRows->map(function ($item) use ($totalSalidas, $maxTotal) {
            $vehiculo = $item->vehiculo;
            $totalMin = (int) ($item->total_minutos ?? 0);
            $promMin = (float) ($item->promedio_minutos ?? 0);
            $participacion = $totalSalidas > 0 ? round(((int) $item->total * 100) / $totalSalidas, 2) : 0;

            return [
                'placa' => $vehiculo->placa ?? 'N/A',
                'marca' => $vehiculo->marca ?? 'N/A',
                'modelo' => $vehiculo->modelo ?? 'N/A',
                'anio' => $vehiculo->anio ?? 'N/A',
                'estatus_vehiculo' => $vehiculo->estatus ?? 'N/A',
                'kilometraje_actual' => $vehiculo->kilometraje_actual ?? 0,
                'documentacion_estatus' => $vehiculo->documentacion_estatus ?? 'N/A',
                'total_salidas' => (int) $item->total,
                'participacion_pct' => $participacion,
                'grafica_uso' => str_repeat('|', max((int) round(((int) $item->total / $maxTotal) * 20), 1)),
                'total_horas' => round($totalMin / 60, 2),
                'promedio_horas' => round($promMin / 60, 2),
                'ultima_salida' => $item->ultima_salida
                    ? Carbon::parse($item->ultima_salida)->format('Y-m-d H:i')
                    : 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Placa',
            'Marca',
            'Modelo',
            'Anio',
            'Estatus Vehiculo',
            'Kilometraje Actual',
            'Estatus Documentacion',
            'Total Salidas',
            'Participacion (%)',
            'Grafica Uso',
            'Total Horas',
            'Promedio Horas',
            'Ultima Salida',
        ];
    }
}
