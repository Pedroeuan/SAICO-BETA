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
        return view('vehiculos.index', compact('vehiculos'));
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
    Vehiculo::create($request->validated());

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
        $vehiculo->update($request->validated());

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
