<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Mantenimiento;
use App\Models\Vehiculos\PagoVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;

class PagoVehiculoController extends Controller
{
    public function index($vehiculoId)
    {
        // Lista pagos generales del vehiculo (administrativos + mantenimiento).
        $vehiculo = Vehiculo::findOrFail($vehiculoId);

        $pagosAdministrativos = PagoVehiculo::where('vehiculo_id', $vehiculoId)
            ->orderByDesc('fecha_pago')
            ->get()
            ->map(function ($p) {
                return [
                    'origen' => 'pago',
                    'id' => $p->id,
                    'anio' => $p->anio,
                    'tipo' => ucfirst($p->tipo_pago),
                    'fecha' => $p->fecha_pago,
                    'monto' => (float) ($p->monto ?? 0),
                    'archivo' => $p->comprobante_url,
                    'fecha_orden' => $p->fecha_pago ? $p->fecha_pago->format('Y-m-d') : ($p->anio . '-12-31'),
                ];
            });

        $pagosMantenimiento = Mantenimiento::where('vehiculo_id', $vehiculoId)
            ->whereNotNull('costo')
            ->orderByDesc('fecha')
            ->get()
            ->map(function ($m) {
                return [
                    'origen' => 'mantenimiento',
                    'id' => $m->id,
                    'anio' => optional($m->fecha)->format('Y') ?? 'N/A',
                    'tipo' => 'Mantenimiento ' . ucfirst($m->tipo),
                    'fecha' => $m->fecha,
                    'monto' => (float) ($m->costo ?? 0),
                    'archivo' => $m->factura_pdf,
                    'fecha_orden' => optional($m->fecha)->format('Y-m-d') ?? '1900-01-01',
                ];
            });

        $pagosGenerales = $pagosAdministrativos
            ->concat($pagosMantenimiento)
            ->sortByDesc('fecha_orden')
            ->values();

        return view('vehiculos.pagos.index', compact('vehiculo', 'pagosGenerales'));
    }

    public function create($vehiculoId)
    {
        // Formulario de alta de pago para el vehiculo seleccionado.
        $vehiculo = Vehiculo::findOrFail($vehiculoId);
        return view('vehiculos.pagos.create', compact('vehiculo'));
    }

    public function store(Request $request, $vehiculoId)
    {
        // Valida y guarda pago; almacena comprobante en carpeta del vehiculo.
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
        // Carga pago puntual del vehiculo para edicion.
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
        // Elimina pago acotado al vehiculo para evitar borrados cruzados.
        $pago = PagoVehiculo::where('vehiculo_id', $vehiculoId)->findOrFail($id);
        $pago->delete();

        return redirect()->route('vehiculos.pagos.index', $vehiculoId)
            ->with('success', 'Pago eliminado.');
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

        return view('vehiculos.historial.index', compact('vehiculo', 'ultimosMantenimientos', 'ultimosPagos'));
    }
}
