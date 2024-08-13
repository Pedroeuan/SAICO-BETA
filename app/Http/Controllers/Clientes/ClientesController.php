<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\Clientes\clientes;

class ClientesController extends Controller
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
        return view('Clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clientes = new clientes;
        $EsperaDato ='ESPERA DE DATO';

        if($request->input('Cliente')==null)
        {
            $clientes->Cliente = $EsperaDato;
        }else{
            $clientes->Cliente = $request->input('Cliente');
        }

        if($request->input('RFC')==null)
        {
            $clientes->RFC = $EsperaDato;
        }else{
            $clientes->RFC = $request->input('RFC');
        }

        if($request->input('Telefono')==null)
        {
            $clientes->Telefono = $EsperaDato;
        }else{
            $clientes->Telefono = $request->input('Telefono');
        }

        if($request->input('Correo')==null)
        {
            $clientes->Correo = $EsperaDato;
        }else{
            $clientes->Correo = $request->input('Correo');
        }
        $clientes->save();

        return redirect()->route('index.clientes');
    }

    /**
     * Display the specified resource.
     */
    public function show(clientes $clientes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(clientes $clientes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, clientes $clientes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(clientes $clientes)
    {
        //
    }
}
