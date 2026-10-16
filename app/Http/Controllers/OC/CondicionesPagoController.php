<?php

namespace App\Http\Controllers\OC;

use App\Models\Condiciones_Pago\Condiciones_Pago;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
class CondicionesPagoController extends Controller
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
        return view('OC.CondicionesPago.create');
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
    public function show(Condiciones_Pago $condiciones_Pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Condiciones_Pago $condiciones_Pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Condiciones_Pago $condiciones_Pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Condiciones_Pago $condiciones_Pago)
    {
        //
    }
}
