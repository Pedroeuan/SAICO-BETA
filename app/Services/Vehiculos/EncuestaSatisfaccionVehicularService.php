<?php

namespace App\Services\Vehiculos;

use App\Models\User;
use App\Models\Vehiculos\EncuestaSatisfaccionVehicular;
use App\Models\Vehiculos\SalidaVehiculo;

class EncuestaSatisfaccionVehicularService
{
    public function createFromSalida(SalidaVehiculo $salida, User $usuario, array $data): EncuestaSatisfaccionVehicular
    {
        $payload = array_merge($data, [
            'salida_vehiculo_id' => $salida->id,
            'vehiculo_id' => $salida->vehiculo_id,
            'user_id' => $usuario->id,
            'origen_respuesta' => $this->resolveOrigenRespuesta($salida, $usuario),
            'sentimiento' => $this->resolveSentimiento($data),
            'fecha_encuesta' => now()->toDateString(),
            'respondida_en' => now(),
        ]);

        return EncuestaSatisfaccionVehicular::create($payload);
    }

    public function resolveOrigenRespuesta(SalidaVehiculo $salida, User $usuario): string
    {
        if ((int) $salida->solicitado_por === (int) $usuario->id) {
            return 'solicitante';
        }

        if ((int) $salida->chofer_id === (int) $usuario->id) {
            return 'chofer';
        }

        return 'operativo';
    }

    public function resolveSentimiento(array $data): string
    {
        $promedio = (
            ((int) $data['calificacion_servicio']) +
            ((int) $data['calificacion_estado_unidad']) +
            ((int) $data['calificacion_tiempo_respuesta'])
        ) / 3;

        $nps = (int) ($data['nps'] ?? 0);
        $comentario = mb_strtolower((string) ($data['comentario'] ?? ''));

        $palabrasNegativas = ['malo', 'demora', 'tarde', 'sucio', 'fallo', 'fallas', 'pesimo', 'pésimo', 'queja'];
        $palabrasPositivas = ['excelente', 'bien', 'rapido', 'rápido', 'limpio', 'bueno', 'apoyo', 'eficiente'];

        foreach ($palabrasNegativas as $palabra) {
            if ($comentario !== '' && str_contains($comentario, $palabra)) {
                return 'negativo';
            }
        }

        foreach ($palabrasPositivas as $palabra) {
            if ($comentario !== '' && str_contains($comentario, $palabra) && $promedio >= 4) {
                return 'positivo';
            }
        }

        if ($nps >= 9 && $promedio >= 4) {
            return 'positivo';
        }

        if ($nps <= 6 || $promedio < 3) {
            return 'negativo';
        }

        return 'neutro';
    }
}
