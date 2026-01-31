<?php

namespace App\Http\Controllers;
use App\Models\Vehiculo;
use Illuminate\Http\Request;

class VehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiculos = \App\Models\Vehiculo::orderBy('id', 'desc')->get();
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
    public function store(Request $request)
    {
        Vehiculo::create($request->all());
        return redirect()->route('vehiculos.index')->with('success', 'vehiculo resgistrado correctamente');
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
    public function update(Request $request, string $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update($request->all());

        return redirect()->route('vehiculos.index')->with('success', 'Vehiculo actualizado');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        vehiculo::findOrFail($id)->delete();

        return redirect()->route('vehiculos.index')->with('success', 'Vehiculo eliminado');

    }
}
