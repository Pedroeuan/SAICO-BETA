<?php

namespace App\Http\Controllers\OC;

use App\Models\Condiciones_Generales\Condiciones_Generales;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CondicionesGeneralesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Condiciones_Generales = Condiciones_Generales::all();
        return view('OC.CondicionesGenerales.index',compact('Condiciones_Generales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('OC.CondicionesGenerales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'Nombre' => 'required|string',
            'Descripcion' => 'required|string',
        ]);

        $Condiciones_Generales = new Condiciones_Generales;
        $Condiciones_Generales->Nombre = $request->input('Nombre');
        $Condiciones_Generales->Descripcion = $request->input('Descripcion');
        $Condiciones_Generales->save();

        return redirect()->route('OC.indexGenerales');
    }

    /**
     * Display the specified resource.
     */
    public function show(Condiciones_Generales $condiciones_Generales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $Condiciones_Generales = Condiciones_Generales::find($id);
        if (!$Condiciones_Generales) {
            return redirect()->route('OC.indexGenerales')->with('error', 'Condiciones Generales no encontrado.');
        }
        return view('OC.CondicionesGenerales.edit', compact('Condiciones_Generales', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'Nombre' => 'required|string',
            'Descripcion' => 'required|string',
        ]);

        $Condiciones_Generales = Condiciones_Generales::find($id);
        if (!$Condiciones_Generales) {
            return redirect()->route('OC.indexGenerales')->with('error', 'Condiciones Generales no encontrado.');
        }

        $Condiciones_Generales->Nombre = $request->input('Nombre');
        $Condiciones_Generales->Descripcion = $request->input('Descripcion');
        $Condiciones_Generales->save();

        return redirect()->route('OC.indexGenerales')->with('success', 'Condiciones Generales actualizado correctamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $Condiciones_Generales = Condiciones_Generales::find($id);
        if ($Condiciones_Generales) {
            $Condiciones_Generales->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Condiciones Generales no encontrado.']);
        }
        return redirect()->route('OC.indexGenerales')->with('success', 'Condiciones Generales eliminado correctamente.');
    }
}
