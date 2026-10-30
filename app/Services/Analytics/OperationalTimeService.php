<?php

namespace App\Services\Analytics;

use App\Models\Vehiculos\SalidaVehiculo;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OperationalTimeService
{
    // Called within the checklist transaction. Lock even for older clients.
    public function record(SalidaVehiculo $salida, string $tipo, ?string $value, string $canal): void
    {
        $locked = SalidaVehiculo::whereKey($salida->id)->lockForUpdate()->firstOrFail();
        if ($locked->estatus !== 'activo' || $locked->checklist()->where('tipo', $tipo)->exists()) {
            throw ValidationException::withMessages(['salida' => 'El checklist ya existe o la salida esta cerrada.']);
        }
        // Absence is unknown, never infer a physical event from server time.
        if (!$value) {
            return;
        }
        $zone = config('app.timezone');
        try {
            $occurred = CarbonImmutable::parse($value, $zone)->setTimezone($zone);
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['fecha_operativa' => 'Indica una fecha y hora valida.']);
        }
        $recorded = CarbonImmutable::now($zone);
        $departure = DB::table('salidas_tiempos_operativos')
            ->where('salida_vehiculo_id', $locked->id)->where('tipo', 'salida')->value('ocurrido_at');
        $lower = $tipo === 'entrada' && $departure ? $departure : $locked->created_at;
        if ($occurred->gt($recorded) || ($lower && $occurred->lt(CarbonImmutable::parse($lower, $zone)))) {
            throw ValidationException::withMessages(['fecha_operativa' => 'La hora real no puede ser futura ni anterior a la solicitud o a la salida real.']);
        }
        DB::table('salidas_tiempos_operativos')->insert([
            'salida_vehiculo_id' => $locked->id,
            'tipo' => $tipo,
            'ocurrido_at' => $occurred->format('Y-m-d H:i:s'),
            'registrado_at' => $recorded->format('Y-m-d H:i:s'),
            'registrado_por' => auth()->id(),
            'canal' => $canal,
            'entorno' => app()->environment(),
            'zona_horaria' => $zone,
            // Versioned context as observed at capture, not at request/prediction time.
            'contexto' => json_encode([
                'version' => 1,
                'instante' => 'captura_checklist',
                'vehiculo_id' => $locked->vehiculo_id,
                'solicitud_creada' => $locked->created_at?->format('Y-m-d H:i:s'),
                'salida_programada' => $locked->fecha_salida?->format('Y-m-d H:i:s'),
            ], JSON_THROW_ON_ERROR),
        ]);
    }
}
