<?php

namespace App\Http\Controllers\TICS;


use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\TICS\Prestamos_TICS;
use App\Models\EquiposyConsumibles\general_eyc;
class PrestamosTICSController extends Controller
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
        $rol = Auth::user()->rol;
        $general = general_eyc::get();
        //$generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
        $generalConCertificados = general_eyc::with('certificados')
        ->where(function ($query) {
            $query->where('Disponibilidad_Estado', 'DISPONIBLE')
                ->where('Tipo', 'TICS');
                })
        ->get();
        return view('TICS.create', compact('general','generalConCertificados','rol'));
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
    public function show(Prestamos_TICS $prestamos_TICS)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prestamos_TICS $prestamos_TICS)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prestamos_TICS $prestamos_TICS)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prestamos_TICS $prestamos_TICS)
    {
        //
    }
}
