<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\PagoVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;

class PagoVehiculoController extends Controller
{
    public function index($vehiculoId)
    {
        // Lista pagos del vehículo (tenencia/refrendo/verificación) con paginación.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $pagos = PagoVehiculo::where('vehiculo_id', $vehiculoId)
            ->orderByDesc('anio')
            ->paginate(15);

        return view('vehiculos.pagos.index', compact('vehiculo', 'pagos'));
    }

    public function create($vehiculoId)
    {
        // Formulario de alta de pago para el vehículo seleccionado.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        return view('vehiculos.pagos.create', compact('vehiculo'));
    }

    public function store(Request $request, $vehiculoId)
    {
        // Valida y guarda pago; almacena comprobante en carpeta del vehículo.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        $data = $request->validate([
            'tipo_pago' => 'required|in:tenencia,refrendo,verificacion',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'monto' => 'nullable|numeric|min:0',
            'fecha_pago' => 'nullable|date',
            'comprobante_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $data['vehiculo_id'] = $vehiculo->id;

        if ($request->hasFile('comprobante_url')) {
            $data['comprobante_url'] = $request->file('comprobante_url')
                ->store("vehiculos/{$vehiculo->id}/pagos/comprobantes", 'public');
        }

        PagoVehiculo::create($data);

        return redirect()->route('vehiculos.pagos.index', $vehiculo->id)
            ->with('success', 'Pago registrado.');
    }

    public function edit($vehiculoId, $id)
    {
        // Carga pago puntual del vehículo para edición.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $pago = PagoVehiculo::where('vehiculo_id', $vehiculoId)->findOrFail($id);

        return view('vehiculos.pagos.edit', compact('vehiculo', 'pago'));
    }

    public function update(Request $request, $vehiculoId, $id)
    {
        // Actualiza pago y reemplaza comprobante solo si llega archivo nuevo.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        $pago = PagoVehiculo::where('vehiculo_id', $vehiculoId)->findOrFail($id);

        $data = $request->validate([
            'tipo_pago' => 'required|in:tenencia,refrendo,verificacion',
            'anio' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'monto' => 'nullable|numeric|min:0',
            'fecha_pago' => 'nullable|date',
            'comprobante_url' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('comprobante_url')) {
            $data['comprobante_url'] = $request->file('comprobante_url')
                ->store("vehiculos/{$vehiculo->id}/pagos/comprobantes", 'public');
        }

        $pago->update($data);

        return redirect()->route('vehiculos.pagos.index', $vehiculo->id)
            ->with('success', 'Pago actualizado.');
    }

    public function destroy($vehiculoId, $id)
    {
        // Elimina pago acotado al vehículo para evitar borrados cruzados.
        $pago = PagoVehiculo::where('vehiculo_id', $vehiculoId)->findOrFail($id);
        $pago->delete();

        return redirect()->route('vehiculos.pagos.index', $vehiculoId)
            ->with('success', 'Pago eliminado.');
    }
}
