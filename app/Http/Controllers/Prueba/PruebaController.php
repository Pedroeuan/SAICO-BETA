<?php

namespace App\Http\Controllers\Prueba;

use App\Models\Prueba\prueba;
use App\Models\Norma_Codigo\norma_codigo;
use App\Models\Formato\formato;

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
        $Pruebas = prueba::with('norma_codigo.formato')->get();
        return view('Pruebas.index',compact('Pruebas'));
    }

    public function Servicios_Pruebas(Request $request)
    {
        // Obtener el nombre del servicio de los parámetros de la URL
        $servicio = $request->query('servicio');
    
        // Pasar el nombre del servicio a la vista
        return view('Pruebas.Servicios', ['servicio' => $servicio]);
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

        // Guardar los datos en la tabla norma_codigo
        foreach ($request->input('codigo') as $codigo) {
            $Prueba->norma_codigo()->create([
                'Nombre' => $codigo,
            ]);
        }

        // Redirigir a una ruta específica con un mensaje de éxito
        $Pruebas = prueba::with('norma_codigo.formato')->get();
        return view('Pruebas.index',compact('Pruebas'));
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
    public function edit($id)
    {
        //
        $Prueba = prueba::where('idPrueba', $id)->first();
        $Norma_Codigo = norma_codigo::where('idPrueba',$Prueba->idPrueba)->get();

        return view('Pruebas.edit', compact('id','Prueba','Norma_Codigo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'Tipo_Prueba' => 'required|string',
        ]);

        $Prueba = prueba::where('idPrueba', $id)->first();
        // Actualizar los datos del equipo
        $Prueba ->update([
            'Nombre' => $request->input('Tipo_Prueba'),
        ]);

        foreach ($request->input('codigo') as $codigo) {
            $normaCodigo = new norma_codigo();
            $normaCodigo->idPrueba = $id;
            $normaCodigo->Nombre = $codigo;
            $normaCodigo->save();
        }
        
        // Redirigir a una ruta específica con un mensaje de éxito
        $Pruebas = prueba::with('norma_codigo.formato')->get();
        return view('Pruebas.index',compact('Pruebas'));

    }

    /**
     * Remove the specified resource from storage.
     */
    /*botón del eliminar de la vista Prueba\edit.blade */
    public function destroyNormaCodigo($id)
    {
        $normaCodigo = norma_codigo::findOrFail($id);
        
        $normaCodigo->delete();

        return response()->json(['success' => 'Registro eliminado correctamente']);
    }

    public function destroy(prueba $prueba)
    {
        //
    }

}
