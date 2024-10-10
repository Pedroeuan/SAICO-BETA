<?php

namespace App\Http\Controllers\Certificados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\historial_certificado;


class CertificadosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $generalConCertificadosConHistorialYAlmacen = general_eyc::with(['certificados.historial_certificado','almacen'])->get();
        
        return view("Certificados.index", compact('generalConCertificadosConHistorialYAlmacen'));
        
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
    public function show(certificados $certificados)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(certificados $certificados)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, certificados $certificados)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(certificados $certificados)
    {
        //
    }
}
