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
        $rows = SalidaVehiculo::whereBetween('fecha_salida', [$this->inicio, $this->fin])
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
            ->get()
            ->map(function ($item) {
                $totalMin = (int) ($item->total_minutos ?? 0);
                $promMin = (float) ($item->promedio_minutos ?? 0);

                return [
                    'placa' => $item->vehiculo->placa ?? 'N/A',
                    'marca' => $item->vehiculo->marca ?? 'N/A',
                    'modelo' => $item->vehiculo->modelo ?? 'N/A',
                    'total_salidas' => (int) $item->total,
                    'total_horas' => round($totalMin / 60, 2),
                    'promedio_horas' => round($promMin / 60, 2),
                    'ultima_salida' => $item->ultima_salida,
                ];
            });

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Placa',
            'Marca',
            'Modelo',
            'Total salidas',
            'Total horas',
            'Promedio horas',
            'Ultima salida',
        ];
    }
}

