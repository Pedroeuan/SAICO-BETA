<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\HistorialLlantaRequest;
use App\Models\Vehiculos\HistorialLlanta;
use App\Models\Vehiculos\Vehiculo;
use App\Services\Vehiculos\HistorialLlantaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HistorialLlantaController extends Controller
{
    public function __construct(
        protected HistorialLlantaService $service
    ) {
    }

    public function index(int $vehiculoId): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        $llantas = HistorialLlanta::query()
            ->where('vehiculo_id', $vehiculoId)
            ->orderByRaw("FIELD(estado, 'activa', 'rotada', 'baja')")
            ->orderBy('posicion')
            ->orderByDesc('fecha_instalacion')
            ->paginate(20);

        $resumen = HistorialLlanta::query()
            ->where('vehiculo_id', $vehiculoId)
            ->selectRaw("SUM(CASE WHEN estado = 'activa' THEN 1 ELSE 0 END) as activas")
            ->selectRaw("SUM(CASE WHEN estado = 'rotada' THEN 1 ELSE 0 END) as rotadas")
            ->selectRaw("SUM(CASE WHEN estado = 'baja' THEN 1 ELSE 0 END) as bajas")
            ->selectRaw('COALESCE(SUM(costo), 0) as costo_total')
            ->first();

        $costoPorPosicion = HistorialLlanta::query()
            ->where('vehiculo_id', $vehiculoId)
            ->select('posicion', DB::raw('COALESCE(SUM(costo), 0) as total_costo'))
            ->groupBy('posicion')
            ->orderBy('posicion')
            ->get();

        return view('vehiculos.llantas.index', compact('vehiculo', 'llantas', 'resumen', 'costoPorPosicion'));
    }

    public function create(int $vehiculoId): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        return view('vehiculos.llantas.create', compact('vehiculo'));
    }

    public function store(HistorialLlantaRequest $request, int $vehiculoId): RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $this->service->create($vehiculo, $request->validated());

        return redirect()
            ->route('vehiculos.llantas.index', $vehiculo->id)
            ->with('success', 'Llanta registrada correctamente.');
    }

    public function edit(int $vehiculoId, int $id): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $llanta = HistorialLlanta::query()
            ->where('vehiculo_id', $vehiculoId)
            ->findOrFail($id);

        return view('vehiculos.llantas.edit', compact('vehiculo', 'llanta'));
    }

    public function update(HistorialLlantaRequest $request, int $vehiculoId, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $llanta = HistorialLlanta::query()
            ->where('vehiculo_id', $vehiculoId)
            ->findOrFail($id);

        $this->service->update($llanta, $request->validated());

        return redirect()
            ->route('vehiculos.llantas.index', $vehiculoId)
            ->with('success', 'Llanta actualizada correctamente.');
    }

    public function destroy(int $vehiculoId, int $id): RedirectResponse
    {
        if ($redirect = $this->redirectIfTableMissing($vehiculoId)) {
            return $redirect;
        }
        $llanta = HistorialLlanta::query()
            ->where('vehiculo_id', $vehiculoId)
            ->findOrFail($id);

        $this->service->delete($llanta);

        return redirect()
            ->route('vehiculos.llantas.index', $vehiculoId)
            ->with('success', 'Llanta eliminada correctamente.');
    }

    private function redirectIfTableMissing(int $vehiculoId): ?RedirectResponse
    {
        if (Schema::hasTable('historial_llantas')) {
            return null;
        }

        return redirect()
            ->route('vehiculos.edit', $vehiculoId)
            ->with('warning', 'El modulo de llantas aun no esta habilitado en esta base local. Ejecuta su migracion especifica.');
    }
}
