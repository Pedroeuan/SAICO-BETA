<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\EquiposyConsumibles\general_eyc;

class IndicadoresController extends Controller
{
    public function index()
    {
        // Obtiene todos los datos de almacen y consumibles
        $consumibles = general_eyc::with('almacen')->where('Tipo', 'CONSUMIBLES')->get();

        // Pretornar datos de consulta
        return view('indicadoresEquipos', compact('consumibles'));  
    }  
}
