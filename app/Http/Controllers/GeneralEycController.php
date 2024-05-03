<?php

namespace App\Http\Controllers;

use App\Models\general_eyc;
use Illuminate\Http\Request;

class GeneralEycController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $Equipos=general_eyc::all();
        //dd($Equipos);
        return view('Equipos.index',compact('Equipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(general_eyc $general_eyc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(general_eyc $general_eyc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, general_eyc $general_eyc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(general_eyc $general_eyc)
    {
        //
    }
}
