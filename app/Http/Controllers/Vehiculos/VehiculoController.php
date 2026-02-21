<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Vehiculo;
use App\Http\Requests\Vehiculos\VehiculoRequest;


class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiculos = Vehiculo::orderBy('id', 'desc')->get();

        // Estadísticas y alertas
        $totalVehiculos = Vehiculo::count();
        $disponibles = Vehiculo::where('estatus', 'disponible')->count();
        $ocupados = Vehiculo::where('estatus', 'ocupado')->count();
        $vencidos = Vehiculo::where('documentacion_estatus', 'vencida')->count();

        // Alertas de documentación
        $documentosVencidos = Vehiculo::where('documentacion_estatus', 'vencida')->get();
        
        $proximo15dias = \Carbon\Carbon::now()->addDays(15);
        $documentosProximoVencer = Vehiculo::where('documentacion_estatus', 'completa')
            ->where(function($q) use ($proximo15dias) {
                $q->whereBetween('poliza_seguro_vencimiento', [\Carbon\Carbon::now(), $proximo15dias])
                ->orWhereBetween('tarjeta_circulacion_vencimiento', [\Carbon\Carbon::now(), $proximo15dias]);
            })
            ->get();

        $documentosSinRegistrar = Vehiculo::where('documentacion_estatus', 'incompleta')->get();
        $vencidosCount = $vencidos;

        return view('vehiculos.index', compact(
            'vehiculos',
            'totalVehiculos',
            'disponibles',
            'ocupados',
            'vencidos',
            'documentosVencidos',
            'documentosProximoVencer',
            'documentosSinRegistrar',
            'vencidosCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehiculos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehiculoRequest $request)
    {
        //dd($request->all());
        $data = $request->validated();

        $ESPERADATO = 'ESPERA DE DATO';

        // crear vehículo sin archivos primero
        /*$vehiculo = Vehiculo::create(
            array_merge($data, ['kilometraje_actual' => $data['kilometraje_actual'] ?? 0])
        );*/
        $vehiculo = new Vehiculo($data);
        $vehiculo->kilometraje_actual = $data['kilometraje_actual'] ?? 0;
        $vehiculo->save(); 
        // almacenar PDFs en carpeta organizada
        if ($request->hasFile('poliza_seguro_pdf') && $request->file('poliza_seguro_pdf') != null) {
            $path = $request->file('poliza_seguro_pdf')->store("vehiculos/{$vehiculo->id}/poliza", 'public');
            $vehiculo->poliza_seguro_pdf = $path;
        }else{
            $vehiculo->poliza_seguro_pdf = $ESPERADATO;
        }
        if ($request->hasFile('tarjeta_circulacion_pdf') && $request->file('tarjeta_circulacion_pdf') != null) {
            $path = $request->file('tarjeta_circulacion_pdf')->store("vehiculos/{$vehiculo->id}/tarjeta", 'public');
            $vehiculo->tarjeta_circulacion_pdf = $path;
        }else{
            $vehiculo->tarjeta_circulacion_pdf = $ESPERADATO;
        }
        // fechas
        if ($request->filled('poliza_seguro_vencimiento') && $request->input('poliza_seguro_vencimiento') != null) {
            $vehiculo->poliza_seguro_vencimiento = $request->input('poliza_seguro_vencimiento');
        }else{
            $vehiculo->poliza_seguro_vencimiento = '2001-01-01';
        }
        if ($request->filled('tarjeta_circulacion_vencimiento') && $request->input('tarjeta_circulacion_vencimiento') != null) {
            $vehiculo->tarjeta_circulacion_vencimiento = $request->input('tarjeta_circulacion_vencimiento');
        }else{
            $vehiculo->tarjeta_circulacion_vencimiento = '2001-01-01';
        }

        $vehiculo->save();

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado correctamente');
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
    public function edit($id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        return view('vehiculos.edit', compact('vehiculo'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(VehiculoRequest $request, $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);

        $data = $request->validated();

        // actualizar campos básicos
        $vehiculo->update($data);

        // archivos
        if ($request->hasFile('poliza_seguro_pdf')) {
            $path = $request->file('poliza_seguro_pdf')->store("vehiculos/{$vehiculo->id}/poliza", 'public');
            $vehiculo->poliza_seguro_pdf = $path;
        }
        if ($request->hasFile('tarjeta_circulacion_pdf')) {
            $path = $request->file('tarjeta_circulacion_pdf')->store("vehiculos/{$vehiculo->id}/tarjeta", 'public');
            $vehiculo->tarjeta_circulacion_pdf = $path;
        }

        if ($request->filled('poliza_seguro_vencimiento')) {
            $vehiculo->poliza_seguro_vencimiento = $request->input('poliza_seguro_vencimiento');
        }
        if ($request->filled('tarjeta_circulacion_vencimiento')) {
            $vehiculo->tarjeta_circulacion_vencimiento = $request->input('tarjeta_circulacion_vencimiento');
        }

        $vehiculo->save();

        return redirect()->route('vehiculos.index')->with('success', 'Vehiculo actualizado');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Vehiculo::findOrFail($id)->delete();

        return redirect()->route('vehiculos.index')->with('success', 'Vehiculo eliminado');

    }
}
