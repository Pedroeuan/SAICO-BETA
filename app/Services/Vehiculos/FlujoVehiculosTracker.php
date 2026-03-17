<?php

namespace App\Services\Vehiculos;

use App\Models\Vehiculos\SalidaEventoFlujo;
use Illuminate\Support\Facades\Log;

class FlujoVehiculosTracker
{
    public static function track(
        string $evento,
        ?int $salidaVehiculoId = null,
        ?int $userId = null,
        ?string $rol = null,
        ?string $paso = null,
        ?string $pantalla = null,
        array $metadata = []
    ): void {
        try {
            SalidaEventoFlujo::create([
                'salida_vehiculo_id' => $salidaVehiculoId,
                'user_id' => $userId,
                'rol' => $rol,
                'evento' => $evento,
                'paso' => $paso,
                'pantalla' => $pantalla,
                'metadata' => empty($metadata) ? null : $metadata,
            ]);
        } catch (\Throwable $e) {
            Log::warning('No se pudo registrar evento de flujo de vehiculos', [
                'evento' => $evento,
                'salida_vehiculo_id' => $salidaVehiculoId,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
