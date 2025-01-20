<?php

namespace App\Http\Controllers\Reporte;

use App\Models\Reporte\reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class ReporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexMenuServicios()
    {
        return view('Pruebas.pruebas');
    }

    public function Servicios_Pruebas(Request $request)
    {
        // Captura el nombre del servicio
        $servicio = $request->query('serviceName');


        // Lógica según el servicio seleccionado
        return view('Reportes.Servicios', compact('servicio'));

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
    public function show(reporte $reporte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reporte $reporte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reporte $reporte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reporte $reporte)
    {
        //
    }
}
