<?php

namespace App\Http\Controllers\Prueba;

use App\Models\Prueba\prueba;
use App\Models\Norma_Codigo\norma_codigo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexMenuServicios()
    {
        return view('Pruebas.pruebas');
    }

    public function indexPruebas()
    {
        $PruebaconNormaOCodigo = general_eyc::with('certificados')->with('almacen')->get();
        return view('Pruebas.index',compact('Pruebas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('Pruebas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'Tipo_Prueba' => 'required|string',
        ]);

        $Prueba = new prueba;
        $Prueba->Nombre = $request->input('Tipo_Prueba');
        $Prueba->save();

        $normasCodigos = json_decode($request->input('normas_codigos'), true);

        foreach ($normasCodigos as $normaCodigo) {
            norma_codigo::create([
                'idPrueba' => $Prueba->idPrueba,
                'Nombre' => $normaCodigo['norma_codigo']
            ]);
        }
        
        return redirect()->route('Pruebas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(prueba $prueba)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(prueba $prueba)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, prueba $prueba)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(prueba $prueba)
    {
        //
    }
}
