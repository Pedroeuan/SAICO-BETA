<?php

namespace App\Http\Controllers\OC;

use App\Models\Condiciones_Generales\Condiciones_Generales;
use Illuminate\Http\Request;

class CondicionesGeneralesController extends Controller
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
        return view('OC.CondicionesGenerales.create');
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
    public function show(Condiciones_Generales $condiciones_Generales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condiciones_Generales $condiciones_Generales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condiciones_Generales $condiciones_Generales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condiciones_Generales $condiciones_Generales)
    {
        //
    }
}
