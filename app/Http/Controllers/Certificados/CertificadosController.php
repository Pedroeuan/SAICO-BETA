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
      /*  $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->get();
        $CertificadosConHistorial = certificados::with('historial_certificados')->get();
        $CertificadosConHistorialPrueba = historial_certificado::get();
        $generalConCertificadosConHistorial = general_eyc::with(['certificados.historial_certificados'])->get();
        //dd($CertificadosConHistorialPrueba);
        return view("Certificados.index", compact('general','generalConCertificados','CertificadosConHistorial','CertificadosConHistorialPrueba','generalConCertificadosConHistorial'));
        */
        /*
        // Obtén todos los registros de general_eyc con sus certificados e historial de certificados
        $generalConCertificadosConHistorial = general_eyc::with(['certificados.historial_certificados'])->get();

        // Pasa los datos a la vista
        return view('Certificados.index', compact('generalConCertificadosConHistorial')); 
        */

        // Obtén todos los registros de general_eyc con sus certificados e historial de certificados
        $generalConCertificadosConHistorial = general_eyc::with(['certificados.historial_certificado'])->get();

        // Pasa los datos a la vista
        return view('Certificados.index', compact('generalConCertificadosConHistorial')); 
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
