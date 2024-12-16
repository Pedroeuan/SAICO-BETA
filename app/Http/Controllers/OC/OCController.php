<?php

namespace App\Http\Controllers\OC;

use App\Models\OC\OC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class OCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('OC.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'Numero_OC' => 'required|string|max:255',
            'Proyecto' => 'required|string|max:255',
            'Lugar_trabajo' => 'required|string|max:255',
        ]);

        $OC = new OC;
        $EsperaDato ='ESPERA DE DATO';

        if($request->input('Numero_OC')==null)
        {
            $OC->Numero_OC = $EsperaDato;
        }else{
            $OC->Numero_OC = $request->input('Numero_OC');
        }

        if($request->input('Proyecto')==null)
        {
            $OC->Proyecto = $EsperaDato;
        }else{
            $OC->Proyecto = $request->input('Proyecto');
        }

        if($request->input('Lugar_trabajo')==null)
        {
            $OC->Lugar_trabajo = $EsperaDato;
        }else{
            $OC->Lugar_trabajo = $request->input('Lugar_trabajo');
        }

        if($request->input('Fecha_solicitud')==null)
        {
            $OC->Fecha_solicitud = $EsperaDato;
        }else{
            $OC->Fecha_solicitud = $request->input('Fecha_solicitud');
        }

        if($request->input('Tipo_servicio')==null)
        {
            $OC->Tipo_servicio = $EsperaDato;
        }else{
            $OC->Tipo_servicio = $request->input('Tipo_servicio');
        }

        if($request->input('Tipo_servicio')==null)
        {
            $OC->Tipo_servicio = $EsperaDato;
        }else{
            $OC->Tipo_servicio = $request->input('Tipo_servicio');
        }

        $OC->Estatus = $request->input('Estatus');

        $OC->save();

                // Validar que se ha enviado el archivo de factura
        if ($request->hasFile('OC_archivo') && $request->file('OC_archivo')->isValid()) {
            $pdf = $request->file('OC_archivo');
            // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Ventas/OC'))
                ->filter(function ($file) {
                    return preg_match('/^\d+_/', basename($file));
                })
                ->sort()
                ->last();
            $lastNumber = 0;
            if ($lastFile) {
                $lastNumber = (int)explode('_', basename($lastFile))[0];
            }
            // Incrementar el número consecutivo
            $newNumber = $lastNumber + 1;
            $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();
            // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
            $pdfPath = $pdf->storeAs('Ventas/OC', $newFileNameFactura, 'public');
            // Guardar la ruta en la base de datos
            $OC->OC_archivo = $pdfPath;
        } else {
            $OC->OC_archivo = $EsperaDato;
        }
        $OC->save();
    
    }

    /**
     * Display the specified resource.
     */
    public function show(OC $oC)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OC $oC)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OC $oC)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OC $oC)
    {
        //
    }
}
