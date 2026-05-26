<?php

namespace App\Services\Vehiculos;

use App\Models\Vehiculos\CargaCombustible;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CargaCombustibleService
{
    public function create(Vehiculo $vehiculo, array $data): CargaCombustible
    {
        return DB::transaction(function () use ($vehiculo, $data): CargaCombustible {
            $payload = $this->preparePayload($vehiculo, $data);
            $payload['vehiculo_id'] = $vehiculo->id;

            $carga = CargaCombustible::create($payload);
            $this->syncVehicleMileage($vehiculo, (int) $payload['kilometraje']);

            return $carga;
        });
    }

    public function update(CargaCombustible $carga, array $data): CargaCombustible
    {
        return DB::transaction(function () use ($carga, $data): CargaCombustible {
            $payload = $this->preparePayload($carga->vehiculo, $data);
            $ticketAnterior = $carga->ticket_url;

            $carga->update($payload);

            if ($ticketAnterior && $ticketAnterior !== $carga->ticket_url) {
                Storage::disk('public')->delete($ticketAnterior);
            }

            $this->syncVehicleMileage($carga->vehiculo, (int) $payload['kilometraje']);

            return $carga->fresh();
        });
    }

    public function delete(CargaCombustible $carga): void
    {
        DB::transaction(function () use ($carga): void {
            $ticket = $carga->ticket_url;
            $carga->delete();

            if ($ticket) {
                Storage::disk('public')->delete($ticket);
            }
        });
    }

    private function preparePayload(Vehiculo $vehiculo, array $data): array
    {
        if (($data['ticket_url'] ?? null) instanceof UploadedFile) {
            $data['ticket_url'] = $data['ticket_url']->store(
                "vehiculos/{$vehiculo->id}/combustible/tickets",
                'public'
            );
        } else {
            unset($data['ticket_url']);
        }

        return $data;
    }

    private function syncVehicleMileage(Vehiculo $vehiculo, int $kilometraje): void
    {
        if ($kilometraje > (int) $vehiculo->kilometraje_actual) {
            $vehiculo->update([
                'kilometraje_actual' => $kilometraje,
            ]);
        }
    }
}
