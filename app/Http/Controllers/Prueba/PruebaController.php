<?php

namespace App\Http\Controllers\Prueba;

use App\Models\Prueba\prueba;
use App\Models\Norma_Codigo\norma_codigo;
use App\Models\Formato\formato;
use App\Models\Procedimientos\Procedimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexPruebas()
    {
        $Pruebas = prueba::with('norma_codigo.formato')->get();
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

        // Guardar los datos en la tabla norma_codigo
        foreach ($request->input('codigo') as $codigo) {
            $Prueba->norma_codigo()->create([
                'Nombre' => $codigo,
            ]);
        }

        return redirect()->route('index.Pruebas');
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
     * 
     */
    
    public function editnormas($id)
    {
        //
        $Prueba = prueba::where('idPrueba', $id)->first();
        $Norma_Codigo = norma_codigo::where('idPrueba',$id)->get();

        return view('Pruebas.indexnormas', compact('id','Prueba','Norma_Codigo'));
    }

    public function editformatos($id)
    {
        //
        $Norma_Codigo = norma_codigo::where('idNorma_codigo',$id)->first();

        $Formatos = formato::where('idNorma_codigo',$Norma_Codigo->idNorma_codigo)->get();

        $Procedimientos = Procedimiento::all();
        //dd($Formatos);

        return view('Pruebas.editformatos', compact('id','Norma_Codigo','Formatos','Procedimientos'));
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

        // Actualizar o crear los registros de norma_codigo
        foreach ($request->input('Norma_Codigo') as $idNormaCodigo => $nombre) {
            $normaCodigo = norma_codigo::find($idNormaCodigo);
            if ($normaCodigo) {
                // Actualizar el registro existente
                $normaCodigo->update([
                    'Nombre' => $nombre,
                ]);
            } else {
                // Crear un nuevo registro
                $normaCodigo = new norma_codigo();
                $normaCodigo->idPrueba = $id;
                $normaCodigo->Nombre = $nombre;
                $normaCodigo->save();
            }
        }
        
        return redirect()->route('index.Pruebas');

    }

    public function UpdateCreateFormato(Request $request, $id)
    {
        //dd($request->all());
        $request->validate([
            'Norma_Codigo'  => 'required|string',
            //'Formato' => 'required|string',
            //'Procedimiento' => 'nullable|numeric|exists:procedimientos,idProcedimiento',
        ]);

        $Norma_Codigo = norma_codigo::where('idNorma_Codigo', $id)->first();

        $idPrueba = $Norma_Codigo->idPrueba;

        $Norma_Codigo->update([
                'Nombre' => $request->input('Norma_Codigo'),
        ]);

        $Formato = Formato::where('idNorma_Codigo', $id)->first();
        
        foreach ($request->input('Formato') as $idFormato => $nombre) {

            $datos = [
                'Nombre' => $nombre,
                'idProcedimiento' => $request->input("Procedimientos.$idFormato"),
            ];

            // Si es un registro nuevo
            if (str_starts_with($idFormato, 'new_')) {

                Formato::create([
                    'idNorma_Codigo'   => $id,
                    'idPrueba'         => $idPrueba,
                    'Nombre'           => $nombre,
                    'idProcedimiento'  => $request->input("Procedimientos.$idFormato"),
                ]);

            } else {

                // Actualizar registro existente
                Formato::where('idFormato', $idFormato)
                    ->update($datos);

            }
        }
        $idPrueba = $Norma_Codigo->idPrueba;

        return redirect()->route('Pruebas.Normas_Aplicables.normas', ['id' => $idPrueba]);
    }


    /**
     * Remove the specified resource from storage.
     */

    /*botón del eliminar de la vista Prueba\edit.blade */
    public function destroyPrueba($id)
    {
        try {
        $Prueba = prueba::findOrFail($id);
        $normaCodigo = norma_codigo::where('idPrueba',$id)->get();
        $Formatos = formato::where('idPrueba',$id)->get();
        
        $Formatos->each->delete();
        $normaCodigo->each->delete();
        $Prueba->delete();

            return response()->json(['success' => true, 'message' => 'La Prueba con sus Normas y Formatos ah sido eliminados correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar la Prueba, Norma y Formatos.']);
        }
    }
    /*botón del eliminar de la vista Prueba\edit.blade */
    public function destroyNormaCodigo($id)
    {
        try {
            $Formatos = formato::where('idNorma_codigo', $id)->get();
            $normaCodigo = norma_codigo::findOrFail($id);
            
            $Formatos->each->delete();
            $normaCodigo->delete();
    
            return response()->json(['success' => true, 'message' => 'Norma y formatos eliminados correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar la norma y formatos.']);
        }
    }

    /*botón del eliminar de la vista Prueba\edit.blade */
    public function destroyFormato($id)
    {
        $formato = formato::findOrFail($id);
        
        $formato->delete();
    }

    public function destroy(prueba $prueba)
    {
        //
    }

}
