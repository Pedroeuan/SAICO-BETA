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
use App\Models\EquiposyConsumibles\clasificacion;
use App\Models\EquiposyConsumibles\iso;
use Illuminate\Support\Facades\Auth;

class HistorialAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    // Obtener el usuario autenticado
    $user = Auth::user();
    // Obtener el nombre del usuario
    $Nombre = $user->name;
    $rol = Auth::user()->rol;

    $query = Historial_Almacen::with(['Almacen.General_EyC.ISO']);
    // Filtrar según el rol
    if ($rol === 'Laboratorio') {
        $query->whereHas('Almacen.General_EyC.ISO', function ($q) {
            $q->where('NombreISO', '17025');
        });

    } elseif ($rol === 'Tics') {
        $query->whereHas('Almacen.General_EyC', function ($q) {
            $q->where('Tipo', 'TICS');
        });

    } elseif ($rol === 'Equipos') {
        $query->whereHas('Almacen.General_EyC.ISO', function ($q) {
            $q->where('NombreISO', '9001');
        });
    }
    
    $historiales = $query->get();

    return view('Historial_Almacen.index', compact('historiales','rol'));
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
