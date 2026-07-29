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
        //Validar los datos del formulario
        $validatedData = $request->validate([
            /* Resultados Juntas */
            'Normas_IM' => 'nullable|string', // JSON con [{id,text},...]
            'Elemento' => 'nullable|array',
            'Promedio' => 'nullable|array',
            'Composicion' => 'nullable|array',
        ]);

        $tabla = [];

        if ($request->has('Elemento')) {

            foreach ($request->Elemento as $i => $elemento) {

                $tabla[] = [
                    'Elemento'     => $elemento,
                    'Promedio'     => $request->Promedio[$i] ?? '',
                    'Composicion'  => $request->Composicion[$i] ?? '',
                ];
            }
        }

        $Normas_IM = new Normas_IM();
        $Normas_IM->Nombre_Espe = $request->input('NombreESP');
        $Normas_IM->Variable = $request->input('Variable');
        $Normas_IM->Tabla = json_encode($tabla);
        $Normas_IM->Observaciones = $request->input('Observaciones');
        $Normas_IM->save();

        return redirect()->route('index.Normas_IM');
    }

    /**
     * Guarda una norma desde un reporte y devuelve el registro para seleccionarlo
     * sin recargar ni perder la captura que el usuario lleva en el formulario.
     */
    public function storeRapida(Request $request)
    {
        $validated = $request->validate([
            'NombreESP' => 'required|string|max:255',
            'Variable' => 'nullable|string|max:255',
            'Elemento' => 'required|array|min:1|max:500',
            'Elemento.*' => 'required|string|max:100',
            'Composicion' => 'nullable|array|max:500',
            'Composicion.*' => 'nullable|string|max:255',
            'Observaciones' => 'nullable|string|max:5000',
        ]);

        $nombre = trim($validated['NombreESP']);
        $variable = trim($validated['Variable'] ?? '');

        // Si ya existe la misma norma y variable, se reutiliza para no duplicar el catálogo.
        $existente = Normas_IM::query()
            ->whereRaw('LOWER(TRIM(Nombre_Espe)) = ?', [mb_strtolower($nombre, 'UTF-8')])
            ->whereRaw("LOWER(TRIM(COALESCE(Variable, ''))) = ?", [mb_strtolower($variable, 'UTF-8')])
            ->first();

        if ($existente) {
            return response()->json([
                'message' => 'La norma ya existía y fue seleccionada.',
                'existente' => true,
                'norma' => $this->normalizarNormaParaReporte($existente),
            ]);
        }

        $tabla = [];
        foreach ($validated['Elemento'] as $indice => $elemento) {
            $tabla[] = [
                'Elemento' => trim($elemento),
                'Promedio' => '',
                'Composicion' => trim($validated['Composicion'][$indice] ?? ''),
            ];
        }

        $norma = Normas_IM::create([
            'Nombre_Espe' => $nombre,
            'Variable' => $variable,
            'Tabla' => json_encode($tabla, JSON_UNESCAPED_UNICODE),
            'Observaciones' => trim($validated['Observaciones'] ?? ''),
        ]);

        return response()->json([
            'message' => 'Norma creada y seleccionada correctamente.',
            'existente' => false,
            'norma' => $this->normalizarNormaParaReporte($norma),
        ], 201);
    }

    private function normalizarNormaParaReporte(Normas_IM $norma): array
    {
        return [
            'idnormas_im' => $norma->idnormas_im,
            'Nombre_Espe' => $norma->Nombre_Espe,
            'Variable' => $norma->Variable,
            'Tabla' => json_decode($norma->Tabla, true) ?: [],
            'Observaciones' => $norma->Observaciones,
        ];
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

        return view('Normas_IM.edit', compact('Normas_IM', 'tabla'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $Normas_IM = Normas_IM::where('idnormas_im', $id)->first();
        $validatedData = $request->validate([
            /* Resultados Juntas */
            'Normas_IM' => 'nullable|string', // JSON con [{id,text},...]
            'Elemento' => 'nullable|array',
            'Promedio' => 'nullable|array',
            'Composicion' => 'nullable|array',
        ]);

        $tabla = [];

        if ($request->has('Elemento')) {

            foreach ($request->Elemento as $i => $elemento) {

                $tabla[] = [
                    'Elemento'     => $elemento,
                    'Promedio'     => $request->Promedio[$i] ?? '',
                    'Composicion'  => $request->Composicion[$i] ?? '',
                ];
            }
        }

        $Normas_IM->update([
            'Nombre_Espe' => $request->input('NombreESP'),
            'Variable' => $request->input('Variable'),
            'Tabla' => json_encode($tabla),
            'Observaciones' => $request->input('Observaciones'),
        ]);

        return redirect()->route('index.Normas_IM');
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
