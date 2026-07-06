<?php

namespace App\Http\Controllers\Procedimientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\Procedimientos\Procedimiento;

class ProcedimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $Procedimientos = Procedimiento::all();

        return view('Procedimientos.index', compact('Procedimientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Procedimientos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
        ]);

        $procedimiento = new Procedimiento;
        $procedimiento->Nombre = $request->input('Nombre');

        // Si se sube un PDF, guardarlo con número consecutivo y asignar la ruta
        if ($request->hasFile('Procedimiento')) {
            $pdfFile = $request->file('Procedimiento');
            if (is_array($pdfFile)) {
                $pdfFile = reset($pdfFile);
            }
            if ($pdfFile && $pdfFile->isValid()) {
                $pdf = $pdfFile;

                // Calcular último número existente de forma segura
                $files = collect(Storage::disk('public')->files('Procedimientos'));
                $lastNumber = $files->map(function ($file) {
                    $base = basename($file);
                    if (preg_match('/^(\d+)_/', $base, $m)) {
                        return (int) $m[1];
                    }
                    return 0;
                })->max() ?? 0;

                $newNumber = $lastNumber + 1;
                $newFileName = $newNumber . '_' . $pdf->getClientOriginalName();

                $pdfPath = $pdf->storeAs('Procedimientos', $newFileName, 'public');
                $procedimiento->PDF = $pdfPath;
            }
        }

        $procedimiento->save();

        return redirect()->route('index.Procedimientos');
    }

    /**
     * Display the specified resource.
     */
    public function show(Procedimiento $procedimiento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $Procedimiento = Procedimiento::where('idProcedimiento', $id)->first();
        
        return view('Procedimientos.edit', compact('id','Procedimiento'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $request->validate([
            'Nombre' => 'required|string|max:255',
        ]);

        $procedimiento = Procedimiento::where('idProcedimiento', $id)->first();
        if (! $procedimiento) {
            return redirect()->route('index.Procedimientos')->withErrors('Procedimiento no encontrado.');
        }

        $pdfPath = null;

        if ($request->hasFile('Procedimiento')) {
            $pdfFile = $request->file('Procedimiento');
            if (is_array($pdfFile)) {
                $pdfFile = reset($pdfFile);
            }
            if ($pdfFile && $pdfFile->isValid()) {
                // Eliminar archivo anterior si existe
                $rutaAnterior = $procedimiento->PDF;
                if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
                    Storage::disk('public')->delete($rutaAnterior);
                }

                $pdf = $pdfFile;
                $files = collect(Storage::disk('public')->files('Procedimientos'));
                $lastNumber = $files->map(function ($file) {
                    $base = basename($file);
                    if (preg_match('/^(\d+)_/', $base, $m)) {
                        return (int) $m[1];
                    }
                    return 0;
                })->max() ?? 0;

                $newNumber = $lastNumber + 1;
                $newFileName = $newNumber . '_' . $pdf->getClientOriginalName();

                $pdfPath = $pdf->storeAs('Procedimientos', $newFileName, 'public');
            }
        }

        $procedimiento->update([
            'Nombre' => $request->input('Nombre'),
            'PDF' => $pdfPath ?? $procedimiento->PDF,
        ]);

        return redirect()->route('index.Procedimientos');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Procedimientos = Procedimiento::find($id);
            if (! $Procedimientos) {
                return response()->json(['success' => false, 'message' => 'No se pudo encontrar el Procedimiento.'], 404);
            }

            $Procedimientos->delete();

            return response()->json(['success' => true, 'message' => 'Procedimiento eliminado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar procedimiento: ' . $e->getMessage(), ['id' => $id]);
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al procesar la petición.'], 500);
        }
    }
}
