<?php

namespace App\Http\Controllers\Prueba;

use App\Models\Prueba\prueba;

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
        return view('Pruebas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $Pruebas = prueba::all();
        return view('Pruebas.create', compact('Pruebas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(prueba $prueba)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, prueba $prueba)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(prueba $prueba)
    {
        //
    }
}
