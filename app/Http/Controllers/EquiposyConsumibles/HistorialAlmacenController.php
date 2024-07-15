<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\Historial_Almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;
use App\Models\EquiposyConsumibles\detalles_kits;
use App\Models\EquiposyConsumibles\kits;



class HistorialAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    // Obtener todos los registros de historial_almacen con sus relaciones
    $historiales = Historial_Almacen::with(['Almacen.General_EyC'])->get();

    return view('Historial_Almacen/index', compact('historiales'));
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
    public function show(Historial_Almacen $historial_Almacen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Historial_Almacen $historial_Almacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Historial_Almacen $historial_Almacen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Historial_Almacen $historial_Almacen)
    {
        //
    }
}
