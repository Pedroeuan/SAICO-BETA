<?php

namespace App\Http\Controllers\Normas_IM;

use App\Http\Controllers\Controller;
use App\Models\Normas_IM\Normas_IM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class NormasIMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexNormasIM()
    {
        //
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
        //
    }

    public function storeRapida(Request $request)
    {
        $datos = $request->validate([
            'NombreESP' => 'required|string|max:255',
            'Variable' => 'nullable|string|max:255',
            'Elemento' => 'required|array|min:1',
            'Elemento.*' => 'required|string|max:100',
            'Composicion' => 'required|array|size:'.count((array) $request->input('Elemento', [])),
            'Composicion.*' => 'nullable|string|max:255',
            'Observaciones' => 'nullable|string|max:5000',
        ]);

        $elementos = array_values($datos['Elemento']);
        $composiciones = array_values($datos['Composicion']);
        $tabla = [];
        foreach ($elementos as $indice => $elemento) {
            $tabla[] = [
                'Elemento' => $elemento,
                'Composicion' => $composiciones[$indice] ?? '',
            ];
        }

        $norma = Normas_IM::create([
            'Nombre_Espe' => $datos['NombreESP'],
            'Variable' => $datos['Variable'] ?? '',
            'Tabla' => json_encode($tabla, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            'Observaciones' => $datos['Observaciones'] ?? '',
        ]);

        return response()->json([
            'message' => 'Norma creada correctamente.',
            'existente' => false,
            'norma' => [
                'idnormas_im' => $norma->idnormas_im,
                'Nombre_Espe' => $norma->Nombre_Espe,
                'Variable' => $norma->Variable,
                'Tabla' => $tabla,
                'Observaciones' => $norma->Observaciones,
            ],
        ], 201);
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
    public function edit(Normas_IM $normas_IM)
    {
        //
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
    public function destroy(Normas_IM $normas_IM)
    {
        //
    }
}
