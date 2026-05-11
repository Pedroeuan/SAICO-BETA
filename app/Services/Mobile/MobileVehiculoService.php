<?php

namespace App\Services\Mobile;

use App\Models\User;
use App\Models\Vehiculos\Checklist\SalidaChecklist;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MobileVehiculoService
{
    public function availableVehicles()
    {
        return Vehiculo::query()
            ->where('estatus', 'disponible')
            ->where('documentacion_estatus', 'completa')
            ->whereDoesntHave('salidaActiva')
            ->orderBy('placa')
            ->get();
    }

    public function operationalUsers()
    {
        return User::query()
            ->whereDoesntHave('salidasComoChofer', function ($query) {
                $query->where('estatus', 'activo');
            })
            ->orderBy('name')
            ->get();
    }

    public function listVisibleSalidas(User $user): Builder
    {
        $query = SalidaVehiculo::query()
            ->with([
                'vehiculo',
                'chofer',
                'solicitante',
                'checklistSalida.condicion',
                'checklistEntrada.condicion',
            ])
            ->latest('fecha_salida');

        if ($this->canViewAll($user)) {
            return $query;
        }

        return $query->where(function ($innerQuery) use ($user) {
            $innerQuery->where('chofer_id', $user->id)
                ->orWhere('solicitado_por', $user->id);
        });
    }

    public function activeSalida(User $user): ?SalidaVehiculo
    {
        return $this->listVisibleSalidas($user)
            ->where('estatus', 'activo')
            ->first();
    }

    public function paginatedHistory(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->listVisibleSalidas($user)->paginate($perPage);
    }

    public function createSalida(User $user, array $payload): SalidaVehiculo
    {
        $vehiculo = Vehiculo::findOrFail($payload['vehiculo_id']);
        $chofer = User::findOrFail($payload['chofer_id']);
        $solicitadoPor = $payload['solicitado_por'] ?? $user->id;
        $fechaSalida = Carbon::parse($payload['fecha_salida']);

        if ($vehiculo->estatus !== 'disponible') {
            throw ValidationException::withMessages([
                'vehiculo_id' => 'El vehiculo no esta disponible.',
            ]);
        }

        if ($vehiculo->documentacion_estatus === 'vencida') {
            throw ValidationException::withMessages([
                'vehiculo_id' => 'El vehiculo tiene documentacion vencida.',
            ]);
        }

        if ($vehiculo->documentacion_estatus === 'incompleta') {
            throw ValidationException::withMessages([
                'vehiculo_id' => 'El vehiculo no tiene documentacion completa.',
            ]);
        }

        if (SalidaVehiculo::where('chofer_id', $chofer->id)->where('estatus', 'activo')->exists()) {
            throw ValidationException::withMessages([
                'chofer_id' => 'El chofer ya tiene un vehiculo asignado.',
            ]);
        }

        if (!$chofer->licencia_numero) {
            throw ValidationException::withMessages([
                'chofer_id' => 'El chofer no tiene licencia registrada.',
            ]);
        }

        $licenciaExpirada = $chofer->licencia_vencimiento
            ? Carbon::parse($chofer->licencia_vencimiento)->toDateString() < $fechaSalida->toDateString()
            : true;

        if ($licenciaExpirada) {
            throw ValidationException::withMessages([
                'chofer_id' => 'La licencia del chofer esta vencida.',
            ]);
        }

        return DB::transaction(function () use ($user, $vehiculo, $chofer, $solicitadoPor, $fechaSalida, $payload) {
            $salida = SalidaVehiculo::create([
                'vehiculo_id' => $vehiculo->id,
                'chofer_id' => $chofer->id,
                'solicitado_por' => $solicitadoPor,
                'creado_por' => $user->id,
                'finalizado_por' => null,
                'fecha_salida' => $fechaSalida,
                'fecha_regreso' => null,
                'duracion_minutos' => null,
                'motivo' => $payload['motivo'] ?? 'ESPERA DE DATO',
                'estatus' => 'activo',
                'Num_Reporte' => 'SV-' . now()->format('Ymd-His') . '-' . strtoupper(Str::random(4)),
            ]);

            $vehiculo->update(['estatus' => 'ocupado']);

            return $salida->load(['vehiculo', 'chofer', 'solicitante']);
        });
    }

    public function storeDepartureChecklist(SalidaVehiculo $salida, array $payload): SalidaChecklist
    {
        if ($salida->checklistSalida) {
            throw ValidationException::withMessages([
                'salida' => 'Este vehiculo ya tiene checklist de salida.',
            ]);
        }

        return DB::transaction(function () use ($salida, $payload) {
            $checklist = SalidaChecklist::create([
                'salida_vehiculo_id' => $salida->id,
                'tipo' => 'salida',
            ]);

            $checklist->condicion()->create($this->conditionPayload($payload));

            foreach ($this->buildDocumentsStatus($salida) as $documento => $estatus) {
                $checklist->documentos()->create([
                    'documento' => $documento,
                    'estatus' => $estatus,
                ]);
            }

            foreach ($this->toolKeys() as $toolKey) {
                $checklist->herramientas()->create([
                    'herramienta' => $toolKey,
                    'disponible' => (bool) data_get($payload, "herramientas.$toolKey", 0),
                ]);
            }

            $this->storeEvidenceFiles($checklist, $payload['evidencias']);

            $salida->vehiculo()->update([
                'kilometraje_actual' => (int) $payload['kilometraje'],
            ]);

            return $checklist->load(['condicion', 'documentos', 'herramientas', 'evidencias']);
        });
    }

    public function storeArrivalChecklist(SalidaVehiculo $salida, array $payload, int $userId): SalidaChecklist
    {
        if ($salida->checklistEntrada) {
            throw ValidationException::withMessages([
                'salida' => 'Este vehiculo ya tiene checklist de entrada.',
            ]);
        }

        if ($salida->estatus === 'finalizado') {
            throw ValidationException::withMessages([
                'salida' => 'Esta salida ya fue finalizada.',
            ]);
        }

        $checklistSalida = $salida->checklistSalida;
        $kmSalida = (int) optional(optional($checklistSalida)->condicion)->kilometraje;

        if (!$checklistSalida || !$checklistSalida->condicion) {
            throw ValidationException::withMessages([
                'salida' => 'El checklist de salida no tiene condicion registrada.',
            ]);
        }

        if ((int) $payload['kilometraje'] <= $kmSalida) {
            throw ValidationException::withMessages([
                'kilometraje' => 'El kilometraje final debe ser mayor al kilometraje de salida.',
            ]);
        }

        return DB::transaction(function () use ($salida, $payload, $userId) {
            $checklist = SalidaChecklist::create([
                'salida_vehiculo_id' => $salida->id,
                'tipo' => 'entrada',
            ]);

            $checklist->condicion()->create($this->conditionPayload($payload));
            $this->storeEvidenceFiles($checklist, $payload['evidencias']);

            $fechaRegreso = now();
            $salida->update([
                'fecha_regreso' => $fechaRegreso,
                'estatus' => 'finalizado',
                'finalizado_por' => $userId,
                'duracion_minutos' => $salida->fecha_salida
                    ? $salida->fecha_salida->diffInMinutes($fechaRegreso)
                    : null,
            ]);

            $salida->vehiculo()->update([
                'estatus' => 'disponible',
                'kilometraje_actual' => (int) $payload['kilometraje'],
            ]);

            return $checklist->load(['condicion', 'documentos', 'herramientas', 'evidencias']);
        });
    }

    public function departureDefaults(SalidaVehiculo $salida): array
    {
        $ultimaCondicion = SalidaChecklist::whereHas('salida', function ($query) use ($salida) {
            $query->where('vehiculo_id', $salida->vehiculo_id);
        })->where('tipo', 'entrada')
            ->with('condicion')
            ->orderByDesc('id')
            ->first();

        if (!$ultimaCondicion || !$ultimaCondicion->condicion) {
            return [];
        }

        return [
            'nivel_gasolina' => $ultimaCondicion->condicion->nivel_gasolina,
            'kilometraje' => $ultimaCondicion->condicion->kilometraje,
            'liquido_limpiaparabrisas' => $ultimaCondicion->condicion->liquido_limpiaparabrisas,
            'aceite' => $ultimaCondicion->condicion->aceite,
            'anticongelante' => $ultimaCondicion->condicion->anticongelante,
            'liquido_frenos' => $ultimaCondicion->condicion->liquido_frenos,
            'estado_llantas' => $ultimaCondicion->condicion->estado_llantas,
            'llanta_delantera_izq_calibracion' => $ultimaCondicion->condicion->llanta_delantera_izq_calibracion,
            'llanta_delantera_der_calibracion' => $ultimaCondicion->condicion->llanta_delantera_der_calibracion,
            'llanta_trasera_izq_calibracion' => $ultimaCondicion->condicion->llanta_trasera_izq_calibracion,
            'llanta_trasera_der_calibracion' => $ultimaCondicion->condicion->llanta_trasera_der_calibracion,
        ];
    }

    public function canViewAll(User $user): bool
    {
        $role = Str::of((string) $user->rol)
            ->trim()
            ->lower()
            ->replace('_', ' ')
            ->replace('-', ' ')
            ->squish()
            ->value();

        return in_array($role, [
            'admin',
            'administrador',
            'super admin',
            'super administrador',
            'superadministrador',
        ], true);
    }

    private function buildDocumentsStatus(SalidaVehiculo $salida): array
    {
        $chofer = $salida->chofer;
        $vehiculo = $salida->vehiculo;

        $licenciaEstatus = 'vencido';
        if ($chofer && $chofer->licencia_vencimiento && Carbon::parse($chofer->licencia_vencimiento)->endOfDay()->gte(now())) {
            $licenciaEstatus = 'ok';
        }

        $tarjetaEstatus = 'vencido';
        if ($vehiculo && $vehiculo->tarjeta_circulacion_vencimiento && Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->endOfDay()->gte(now())) {
            $tarjetaEstatus = 'ok';
        }

        $polizaEstatus = 'vencido';
        if ($vehiculo && $vehiculo->poliza_seguro_vencimiento && Carbon::parse($vehiculo->poliza_seguro_vencimiento)->endOfDay()->gte(now())) {
            $polizaEstatus = 'ok';
        }

        return [
            'licencia_conducir' => $licenciaEstatus,
            'tarjeta_circulacion' => $tarjetaEstatus,
            'poliza_seguro' => $polizaEstatus,
        ];
    }

    private function conditionPayload(array $payload): array
    {
        return [
            'nivel_gasolina' => $payload['nivel_gasolina'],
            'kilometraje' => $payload['kilometraje'],
            'limpio_exterior' => $payload['limpio_exterior'] ?? 0,
            'limpio_interior' => $payload['limpio_interior'] ?? 0,
            'observaciones' => $payload['observaciones'] ?? null,
            'liquido_limpiaparabrisas' => $payload['liquido_limpiaparabrisas'],
            'aceite' => $payload['aceite'],
            'anticongelante' => $payload['anticongelante'],
            'liquido_frenos' => $payload['liquido_frenos'],
            'estado_llantas' => $payload['estado_llantas'],
            'llanta_delantera_izq_calibracion' => $payload['llanta_delantera_izq_calibracion'],
            'llanta_delantera_der_calibracion' => $payload['llanta_delantera_der_calibracion'],
            'llanta_trasera_izq_calibracion' => $payload['llanta_trasera_izq_calibracion'],
            'llanta_trasera_der_calibracion' => $payload['llanta_trasera_der_calibracion'],
        ];
    }

    /**
     * @param array<int, UploadedFile> $files
     */
    private function storeEvidenceFiles(SalidaChecklist $checklist, array $files): void
    {
        foreach ($files as $file) {
            $path = $file->store("checklists/{$checklist->tipo}", 'public');
            $checklist->evidencias()->create(['foto' => $path]);
        }
    }

    /**
     * @return array<int, string>
     */
    private function toolKeys(): array
    {
        return [
            'llantas',
            'extintor',
            'cables_corriente',
            'gato_hidraulico',
            'llave_cruz',
            'llanta_refaccion',
        ];
    }
}
