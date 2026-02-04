<?php

namespace App\Http\Controllers\Vehiculos;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use App\Models\User;
use App\Http\Requests\Vehiculos\SalidaVehiculoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SalidaVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salidas = SalidaVehiculo::with(['vehiculo', 'chofer'])->orderBy('fecha_salida','desc')->get();
        return view('salidas.index', compact('salidas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vehiculos = Vehiculo::where('estatus', 'disponible')->get();
        $usuarios = User::orderBy('name')->get();
        return view('salidas.create', compact('vehiculos', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalidaVehiculoRequest $request)
    {
        DB::transaction(function () use ($request) {
            SalidaVehiculo::create([
                'vehiculo_id' => $request->vehiculo_id,
                'chofer_id' => $request->chofer_id,
                'solicitado_por' => $request->solicitado_por,
                'fecha_salida' => $request->fecha_salida,
                'fecha_regreso' => $request->fecha_regreso,
                'motivo' => $request->motivo,
                'estatus' => 'activo',
            ]);
            Vehiculo::where('id', $request->vehiculo_id)->update(['estatus' => 'ocupado']);
        });

    return redirect()->route('salidas.index')->with('success', 'Salida registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function finalizar($id)
   {
    $salida = SalidaVehiculo::findOrFail($id);

    $salida->update(['fecha_regreso' => now(), 'estatus' => 'finalizado']);
    
    Vehiculo::where('id', $salida->vehiculo_id)->update(['estatus' => 'disponible']);

    return redirect() ->route('salidas.index')->with('success', 'Salida finalizada');
    
   }
}

