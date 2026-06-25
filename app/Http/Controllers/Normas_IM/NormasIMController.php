<?php

namespace App\Http\Controllers\Normas_IM;

use App\Http\Controllers\Controller;
use App\Models\Normas_IM\Normas_IM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class NormasIMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Normas_IM = Normas_IM::all();
        return view('Normas_IM.index', compact('Normas_IM'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Normas_IM.Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        // Validar los datos del formulario
        $validatedData = $request->validate([
            /* Resultados Juntas */
            'Normas_IM' => 'nullable|string', // JSON con [{id,text},...]
            'Elemento' => 'nullable|array',
            'Promedio' => 'nullable|array',
            'Composicion' => 'nullable|array',
        ]);

        $Normas_IM = new Normas_IM();
        $Normas_IM->Nombre_Espe = $request->input('NombreESP');
        $Normas_IM->Variable = $request->input('Variable');

        $Normas_IM->Tabla = $request->input('Normas_IM');

        $Normas_IM->Observaciones = $request->input('Observaciones');
        $Normas_IM->save();

        return redirect()->route('index.Normas_IM');
    }

    /**
     * Display the specified resource.
     */
    public function show(Normas_IM $normas_IM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $Normas_IM = Normas_IM::where('idnormas_im', $id)->first();

        // Convertir JSON a arreglo PHP
        $tabla = json_decode($Normas_IM->Tabla, true);
        
        //$tabla = $Normas_IM ? json_decode($Normas_IM->Tabla, true) : [];

        return view('Normas_IM.edit', compact('Normas_IM', 'tabla'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Normas_IM $normas_IM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Normas_IM = Normas_IM::find($id);
    
        if ($Normas_IM) {
            $Normas_IM->delete();
            return response()->json(['success' => true, 'message' => 'Norma eliminada correctamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No se pudo encontrar la norma.']);
        }
    }
}
