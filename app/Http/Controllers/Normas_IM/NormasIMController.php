<?php

namespace App\Http\Controllers\Normas_IM;

use App\Http\Controllers\Controller;
use App\Models\Normas_IM\Normas_IM;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class NormasIMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Normas_IM = Normas_IM::all();

        return view('Normas_IM.Index', compact('Normas_IM'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Normas_IM.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $norma = new Normas_IM;
        $this->guardarFormulario($request, $norma);

        return redirect()->route('Normas_IM.Edit', $norma->idnormas_im)->with('success', 'Norma registrada correctamente.');
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
    public function edit(int $id)
    {
        $Normas_IM = Normas_IM::findOrFail($id);
        $tabla = json_decode($Normas_IM->Tabla ?? '[]', true);
        $tabla = is_array($tabla) ? $tabla : [];

        return view('Normas_IM.edit', compact('Normas_IM', 'tabla'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $norma = Normas_IM::findOrFail($id);
        $this->guardarFormulario($request, $norma);

        return redirect()->route('Normas_IM.Edit', $id)->with('success', 'Norma actualizada correctamente.');
    }

    private function guardarFormulario(Request $request, Normas_IM $norma): void
    {
        $conservarTablaAnterior = $norma->exists && !$request->has('Elemento');
        $request->validate(['guardar_seccion' => 'nullable|in:quimica,tratamiento,mecanica,otras']);
        $seccion = $request->input('guardar_seccion');
        if ($seccion) {
            $generales = $request->only(['NombreESP', 'Variable', 'Observaciones', 'guardar_seccion', '_token']);
            if ($seccion === 'quimica') {
                $generales += $request->only(['Quimica_Configuraciones', 'Elemento', 'Composicion', 'Promedio', 'Contexto']);
            } else {
                $generales['Tablas_Adicionales'] = [$seccion => $request->input('Tablas_Adicionales.'.$seccion, [])];
            }
            $request->replace($generales);
        }
        $configuraciones = null;
        if ($request->has('Quimica_Configuraciones')) {
            $request->validate(['Quimica_Configuraciones' => 'required|string|max:1000000']);
            $configuraciones = \App\Services\ComposicionNormaIM::validar($request->input('Quimica_Configuraciones'));
        }
        $elementos = (array) $request->input('Elemento', []);
        $composiciones = (array) $request->input('Composicion', []);
        $promedios = (array) $request->input('Promedio', []);
        $contextos = (array) $request->input('Contexto', []);
        foreach ($elementos as $indice => $elemento) {
            if (trim((string) $elemento) === '' && trim((string) ($composiciones[$indice] ?? '')) === '' && trim((string) ($promedios[$indice] ?? '')) === '') {
                if (! empty(array_filter((array) ($contextos[$indice] ?? []), fn ($valor) => $valor !== null && $valor !== ''))) {
                    continue;
                }
                unset($elementos[$indice], $composiciones[$indice], $promedios[$indice], $contextos[$indice]);
            }
        }
        $request->merge(['Elemento' => array_values($elementos), 'Composicion' => array_values($composiciones)]);
        if ($request->has('Contexto')) {
            $request->merge(['Contexto' => array_values($contextos)]);
        }
        if ($request->has('Promedio')) {
            $request->merge(['Promedio' => array_values($promedios)]);
        }
        if ($request->has('Tablas_Adicionales') && is_array($request->input('Tablas_Adicionales'))) {
            $adicionales = $request->input('Tablas_Adicionales');
            foreach ($adicionales as $clave => $valor) {
                if ($valor === '' || $valor === null) {
                    $adicionales[$clave] = [];
                }
            }
            $request->merge(['Tablas_Adicionales' => $adicionales]);
        }
        $datos = $request->validate([
            'NombreESP' => 'required|string|max:255',
            'Variable' => 'nullable|string|max:255',
            'Elemento' => 'present|array',
            'Elemento.*' => 'required|string|max:100',
            'Composicion' => 'present|array|size:'.count((array) $request->input('Elemento', [])),
            'Composicion.*' => 'nullable|string|max:255',
            'Promedio' => 'nullable|array|size:'.count((array) $request->input('Elemento', [])),
            'Promedio.*' => 'nullable|string|max:255',
            'Observaciones' => 'nullable|string|max:5000',
            'Contexto' => 'sometimes|array|size:'.count($elementos),
            'Contexto.*' => 'array:producto,ancho,espesor,condiciones',
            'Contexto.*.producto' => 'nullable|string|max:2000',
            'Contexto.*.ancho' => 'nullable|string|max:2000',
            'Contexto.*.espesor' => 'nullable|string|max:2000',
            'Contexto.*.condiciones' => 'nullable|string|max:2000',
        ] + \App\Services\TablasNormasIM::reglas());
        $tabla = [];
        foreach (array_values($datos['Elemento']) as $indice => $elemento) {
            $fila = ['Elemento' => $elemento, 'Composicion' => array_values($datos['Composicion'])[$indice] ?? ''];
            if (isset($datos['Promedio'])) {
                $fila['Promedio'] = array_values($datos['Promedio'])[$indice] ?? '';
            }
            if (array_key_exists('Contexto', $datos)) {
                $contexto = array_filter(array_map(fn ($valor) => trim((string) ($valor ?? '')), $datos['Contexto'][$indice] ?? []), fn ($valor) => $valor !== '');
                if ($contexto) {
                    $fila['Aplicabilidad'] = $contexto;
                }
            } else {
                $previas = json_decode($norma->Tabla ?? '[]', true);
                if (isset($previas[$indice]['Aplicabilidad'])) {
                    $fila['Aplicabilidad'] = $previas[$indice]['Aplicabilidad'];
                }
            }
            $tabla[] = $fila;
        }
        $atributos = [
            'Nombre_Espe' => $datos['NombreESP'], 'Variable' => $datos['Variable'] ?? '',
            'Tabla' => json_encode($tabla, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            'Observaciones' => $datos['Observaciones'] ?? '',
        ];
        if (array_key_exists('Tablas_Adicionales', $datos)) {
            $atributos['Tablas_Adicionales'] = array_replace($norma->Tablas_Adicionales ?? [], \App\Services\TablasNormasIM::limpiar($datos['Tablas_Adicionales']));
        }
        if ($conservarTablaAnterior || ($seccion && $seccion !== 'quimica' && $norma->exists)) unset($atributos['Tabla']);
        if ($configuraciones !== null) {
            $atributos['Tablas_Adicionales'] = ($atributos['Tablas_Adicionales'] ?? $norma->Tablas_Adicionales ?? []);
            $atributos['Tablas_Adicionales']['quimica_configuraciones'] = $configuraciones;
        }
        $norma->fill($atributos)->save();
        if ($seccion) session()->flash('pestana_norma', $seccion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Normas_IM $normas_IM)
    {
        //
    }
}
