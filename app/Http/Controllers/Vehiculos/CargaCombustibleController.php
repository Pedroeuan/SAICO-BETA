<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\CargaCombustibleRequest;
use App\Models\Vehiculos\CargaCombustible;
use App\Models\Vehiculos\Vehiculo;
use App\Services\Vehiculos\CargaCombustibleService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;

class CargaCombustibleController extends Controller
{
    public function __construct(
        protected CargaCombustibleService $service
    ) {
    }

    public function index(int $vehiculoId): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        $cargas = CargaCombustible::query()
            ->where('vehiculo_id', $vehiculoId)
            ->orderByDesc('fecha_carga')
            ->orderByDesc('id')
            ->paginate(15);

        $resumen = CargaCombustible::query()
            ->where('vehiculo_id', $vehiculoId)
            ->selectRaw('COUNT(*) as total_cargas')
            ->selectRaw('COALESCE(SUM(litros), 0) as litros_total')
            ->selectRaw('COALESCE(SUM(costo_total), 0) as costo_total')
            ->selectRaw('COALESCE(AVG(precio_por_litro), 0) as precio_promedio')
            ->first();

        return view('vehiculos.combustible.index', compact('vehiculo', 'cargas', 'resumen'));
    }

    public function create(int $vehiculoId): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        return view('vehiculos.combustible.create', compact('vehiculo'));
    }

    public function store(CargaCombustibleRequest $request, int $vehiculoId): RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $this->service->create($vehiculo, $request->validated());

        return redirect()
            ->route('vehiculos.combustible.index', $vehiculo->id)
            ->with('success', 'Carga de combustible registrada.');
    }

    public function edit(int $vehiculoId, int $id): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $carga = CargaCombustible::query()
            ->where('vehiculo_id', $vehiculoId)
            ->findOrFail($id);

        return view('vehiculos.combustible.edit', compact('vehiculo', 'carga'));
    }

    public function update(CargaCombustibleRequest $request, int $vehiculoId, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $carga = CargaCombustible::query()
            ->where('vehiculo_id', $vehiculoId)
            ->findOrFail($id);

        $this->service->update($carga, $request->validated());

        return redirect()
            ->route('vehiculos.combustible.index', $vehiculoId)
            ->with('success', 'Carga de combustible actualizada.');
    }

    public function destroy(int $vehiculoId, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $carga = CargaCombustible::query()
            ->where('vehiculo_id', $vehiculoId)
            ->findOrFail($id);

        $this->service->delete($carga);

        return redirect()
            ->route('vehiculos.combustible.index', $vehiculoId)
            ->with('success', 'Carga de combustible eliminada.');
    }

    private function redirectIfTableMissing(int $vehiculoId): ?RedirectResponse
    {
        if (Schema::hasTable('cargas_combustible')) {
            return null;
        }

        return redirect()
            ->route('vehiculos.edit', $vehiculoId)
            ->with('warning', 'El modulo de combustible aun no esta habilitado en esta base local. Ejecuta su migracion especifica.');
    }
}
