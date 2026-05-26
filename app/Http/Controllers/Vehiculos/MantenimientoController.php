<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\CargaCombustible;
use App\Models\Vehiculos\Mantenimiento;
use App\Models\Vehiculos\PagoVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;

class MantenimientoController extends Controller
{
    public function index($vehiculoId)
    {
        // Lista historial de mantenimientos del vehiculo con paginacion.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $mantenimientos = Mantenimiento::where('vehiculo_id', $vehiculoId)
            ->orderByDesc('fecha')
            ->paginate(15);

        return view('vehiculos.mantenimientos.index', compact('vehiculo', 'mantenimientos'));
    }

    public function create($vehiculoId)
    {
        // Muestra formulario para registrar mantenimiento ligado al vehiculo.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        return view('vehiculos.mantenimientos.create', compact('vehiculo'));
    }

    public function store(Request $request, $vehiculoId)
    {
        // Valida, guarda archivo factura (si existe) y crea el mantenimiento.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        $data = $request->validate([
            'tipo' => 'required|in:preventivo,correctivo',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'kilometraje' => 'nullable|integer|min:0',
            'costo' => 'nullable|numeric|min:0',
            'proxima_revision_fecha' => 'nullable|date',
            'proxima_revision_km' => 'nullable|integer|min:0',
            'factura_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'factura_numero' => 'nullable|string|max:100',
            'factura_fecha' => 'nullable|date',
            'factura_monto' => 'nullable|numeric|min:0',
        ]);

        $data['vehiculo_id'] = $vehiculo->id;

        if ($request->hasFile('factura_pdf')) {
            $data['factura_pdf'] = $request->file('factura_pdf')
                ->store("vehiculos/{$vehiculo->id}/mantenimientos/facturas", 'public');
        }

        Mantenimiento::create($data);

        return redirect()->route('vehiculos.mantenimientos.index', $vehiculo->id)
            ->with('success', 'Mantenimiento registrado.');
    }

    public function edit($vehiculoId, $id)
    {
        // Carga mantenimiento especifico del vehiculo para edicion segura.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $mantenimiento = Mantenimiento::where('vehiculo_id', $vehiculoId)->findOrFail($id);

        return view('vehiculos.mantenimientos.edit', compact('vehiculo', 'mantenimiento'));
    }

    public function update(Request $request, $vehiculoId, $id)
    {
        // Actualiza datos y reemplaza factura solo si suben un nuevo archivo.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $mantenimiento = Mantenimiento::where('vehiculo_id', $vehiculoId)->findOrFail($id);

        $data = $request->validate([
            'tipo' => 'required|in:preventivo,correctivo',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'kilometraje' => 'nullable|integer|min:0',
            'costo' => 'nullable|numeric|min:0',
            'proxima_revision_fecha' => 'nullable|date',
            'proxima_revision_km' => 'nullable|integer|min:0',
            'factura_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'factura_numero' => 'nullable|string|max:100',
            'factura_fecha' => 'nullable|date',
            'factura_monto' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('factura_pdf')) {
            $data['factura_pdf'] = $request->file('factura_pdf')
                ->store("vehiculos/{$vehiculo->id}/mantenimientos/facturas", 'public');
        }

        $mantenimiento->update($data);

        return redirect()->route('vehiculos.mantenimientos.index', $vehiculo->id)
            ->with('success', 'Mantenimiento actualizado.');
    }

    public function destroy($vehiculoId, $id)
    {
        // Elimina solo si el mantenimiento pertenece al vehiculo indicado.
        $mantenimiento = Mantenimiento::where('vehiculo_id', $vehiculoId)->findOrFail($id);
        $mantenimiento->delete();

        return redirect()->route('vehiculos.mantenimientos.index', $vehiculoId)
            ->with('success', 'Mantenimiento eliminado.');
    }

    public function historial($vehiculoId)
    {
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        $ultimosMantenimientos = Mantenimiento::where('vehiculo_id', $vehiculoId)
            ->orderByDesc('fecha')
            ->limit(5)
            ->get();

        $ultimosPagos = PagoVehiculo::where('vehiculo_id', $vehiculoId)
            ->orderByDesc('fecha_pago')
            ->limit(5)
            ->get();

        $ultimasCargas = CargaCombustible::where('vehiculo_id', $vehiculoId)
            ->orderByDesc('fecha_carga')
            ->limit(5)
            ->get();

        return view('vehiculos.historial.index', compact('vehiculo', 'ultimosMantenimientos', 'ultimosPagos', 'ultimasCargas'));
    }
}
