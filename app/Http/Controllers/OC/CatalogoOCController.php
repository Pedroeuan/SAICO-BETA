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
        //
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
        //
        dd($request->all());
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

        $Catalogo_OC->save();

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
