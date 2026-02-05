<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaChecklist;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SalidaChecklistController extends Controller
{
    // ======================
    // CHECKLIST DE SALIDA
    // ======================
    public function create(SalidaVehiculo $salida)
    {
        return view('salidas.checklist.salida', compact('salida'));
    }

    public function store(Request $request, SalidaVehiculo $salida)
    {
        $request->validate([
            'nivel_gasolina'   => 'required|string',
            'kilometraje'      => 'required|integer|min:0',
            'limpio_exterior'  => 'required|in:0,1',
            'limpio_interior'  => 'required|in:0,1',
            'observaciones'    => 'nullable|string|max:500',
        ]);

        SalidaChecklist::create([
            'salida_vehiculo_id' => $salida->id,
            'tipo'               => 'salida',
            'nivel_gasolina'     => $request->nivel_gasolina,
            'kilometraje'        => $request->kilometraje,
            'limpio_exterior'    => $request->limpio_exterior,
            'limpio_interior'    => $request->limpio_interior,
            'observaciones'      => $request->observaciones,
        ]);

        return redirect()->route('salidas.index')
            ->with('success', 'Checklist de salida registrado correctamente');
    }

    // ======================
    // CHECKLIST DE ENTRADA
    // ======================
    public function createEntrada(SalidaVehiculo $salida)
    {
        if(!$salida->checklistSalida){
            return redirect()->route('salidas.index')->with('error', 'Debe registrar primero el checklist de salida.');
        }
        return view('salidas.checklist.entrada', compact('salida'));
    }

    public function storeEntrada(Request $request, SalidaVehiculo $salida)
    {
        $request->validate([
            'nivel_gasolina'   => 'required|string',
            'kilometraje'      => 'required|integer|min:0',
            'limpio_exterior'  => 'required|in:0,1',
            'limpio_interior'  => 'required|in:0,1',
            'observaciones'    => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $salida) {

            SalidaChecklist::create([
                'salida_vehiculo_id' => $salida->id,
                'tipo'               => 'entrada',
                'nivel_gasolina'     => $request->nivel_gasolina,
                'kilometraje'        => $request->kilometraje,
                'limpio_exterior'    => $request->limpio_exterior,
                'limpio_interior'    => $request->limpio_interior,
                'observaciones'      => $request->observaciones,
            ]);

            // cerrar salida
            $salida->update([
                'fecha_regreso' => now(),
                'estatus'       => 'finalizado',
            ]);

            // liberar vehículo
            Vehiculo::where('id', $salida->vehiculo_id)
                ->update(['estatus' => 'disponible']);
        });

        return redirect()->route('salidas.index')
            ->with('success', 'Checklist de entrada registrado correctamente');
    }
}
