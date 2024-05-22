<?php

namespace App\Http\Controllers\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitudes\Solicitudes;
class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("Solicitud.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("Solicitud.edit");
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
    public function show(Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitudes $solicitudes)
    {
        //
    }
}
