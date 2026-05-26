<?php

namespace App\Services\Vehiculos;

use App\Models\Vehiculos\HistorialLlanta;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Support\Facades\DB;

class HistorialLlantaService
{
    public function create(Vehiculo $vehiculo, array $data): HistorialLlanta
    {
        return DB::transaction(function () use ($vehiculo, $data): HistorialLlanta {
            $payload = $this->preparePayload($data);
            $payload['vehiculo_id'] = $vehiculo->id;

            return HistorialLlanta::create($payload);
        });
    }

    public function update(HistorialLlanta $llanta, array $data): HistorialLlanta
    {
        return DB::transaction(function () use ($llanta, $data): HistorialLlanta {
            $llanta->update($this->preparePayload($data));

            return $llanta->fresh();
        });
    }

    public function delete(HistorialLlanta $llanta): void
    {
        $llanta->delete();
    }

    private function preparePayload(array $data): array
    {
        if (($data['estado'] ?? null) !== 'baja') {
            $data['fecha_baja'] = null;
            $data['kilometraje_baja'] = null;
        }

        return $data;
    }
}
