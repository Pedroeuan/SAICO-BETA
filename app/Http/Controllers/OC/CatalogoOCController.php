<?php

namespace App\Http\Controllers\OC;

use App\Http\Controllers\Controller;
use App\Models\Catalogo_OC\Catalogo_OC;
use App\Models\OC\OC;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CatalogoOCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Catalogo_OC = Catalogo_OC::all();
        return view('Catalogo_OC.index', compact('Catalogo_OC'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('Catalogo_OC.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
          //
        $request->validate([
            'Nombre' => 'required|string',
            'Descripcion' => 'required|string',
            'Unidad' => 'required|string',
        ]);

        $Catalogo_OC = new Catalogo_OC;
        $EsperaDato ='ESPERA DE DATO';

        if($request->input('Nombre')==null)
        {
            $Catalogo_OC->Nombre = $EsperaDato;
        }else{
            $Catalogo_OC->Nombre = $request->input('Nombre');
        }

        if($request->input('Descripcion')==null)
        {
            $Catalogo_OC->Descripcion = $EsperaDato;
        }else{
            $Catalogo_OC->Descripcion = $request->input('Descripcion');
        }

        if($request->input('Unidad')==null)
        {
            $Catalogo_OC->Unidad = $EsperaDato;
        }else{
            $Catalogo_OC->Unidad = $request->input('Unidad');
        }

        // Validar que se ha enviado el archivo de factura
        if ($request->hasFile('Imagen') && $request->file('Imagen')->isValid()) {
            $pdf = $request->file('Imagen');
            // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Ventas/OC/Catalogo'))
                ->filter(function ($file) {
                    return preg_match('/^\d+_/', basename($file));
                })
                ->sort()
                ->last();
            $lastNumber = 0;
            if ($lastFile) {
                $lastNumber = (int)explode('_', basename($lastFile))[0];
            }
            // Incrementar el número consecutivo
            $newNumber = $lastNumber + 1;
            $newFileNameOC = $newNumber . '_' . $pdf->getClientOriginalName();
            // Guardar el archivo PDF en la carpeta "public/Ventas/OC/Catalogo"
            $pdfPath = $pdf->storeAs('Ventas/OC/Catalogo', $newFileNameOC, 'public');
            // Guardar la ruta en la base de datos
            $Catalogo_OC->Imagen = $pdfPath;
        } else {
            $Catalogo_OC->Imagen = $EsperaDato;
        }
        $Catalogo_OC->save();

        return redirect()->route('OC.indexCatalogo');
    }

    /**
     * Display the specified resource.
     */
    public function show(Catalogo_OC $catalogo_OC)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catalogo_OC $catalogo_OC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catalogo_OC $catalogo_OC)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catalogo_OC $catalogo_OC)
    {
        //
    }
}
