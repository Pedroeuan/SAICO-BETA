<?php

namespace App\Http\Controllers\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitudes\Solicitudes;
use Carbon\Carbon;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;
use App\Models\Solicitudes\detalles_solicitud;

class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->get();
        return view('Solicitud.index', compact('general','generalConCertificados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $Solicitud = Solicitudes::get();
        return view("Solicitud.edit",compact('Solicitud'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSolicitud(Request $request)
    {
        $now = Carbon::now();
        $Solicitud = new Solicitudes();
        $tecnico = 'Pedro-Cambiar a futuro esto';
        $Estatus = 'PENDIENTE';
        //$Fecha =$now->format('Y-m-d'); // 2024-06-03
        $Fecha = date('Y-m-d H:i:s');
        $Solicitud->tecnico=$tecnico;
        $Solicitud->Fecha=$Fecha;
        $Solicitud->Estatus=$Estatus;
        $Solicitud->save();

    // Obtener los datos de los inputs
    $generalEycIds = $request->input('general_eyc_id');
    $destinos = $request->input('Destino');
    $cantidades = $request->input('Cantidad');
    $unidades = $request->input('Unidad');
    //dd($generalEycIds);
    // Iterar sobre los datos y guardarlos en la base de datos
    foreach ($generalEycIds as $index => $generalEycId) {
    // Verificar si la información está presente en los inputs
    //if (isset($destinos[$index]) && isset($cantidades[$index]) && isset($unidades[$index])) {
    if (isset($cantidades[$index]) && isset($unidades[$index])) {
        //$generaleyc = $generalEycId[$index];
        $destino = $destinos[$index];
        $cantidad = $cantidades[$index];
        $unidad = $unidades[$index];
        
        // Crear una nueva instancia del modelo Solicitud y general
        $detallesolicitud = new detalles_solicitud();
        $generaleyc = new general_eyc();

        $detallesolicitud->idSolicitud = $Solicitud->idSolicitud;
        $detallesolicitud->idGeneral_EyC = $generalEycId;
        $detallesolicitud->cantidad = $cantidad;
        $detallesolicitud->unidad = $unidad;
        $detallesolicitud->save();

        $generaleyc = general_eyc::find($generalEycId);
        if ($generaleyc) {
            $generaleyc->Disponibilidad_Estado = $destino;
            $generaleyc->save();
                }
            }
        }   
    return view("solicitud.edit");
    }


    /**
     * Display the specified resource.
     */
    public function show(Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view("Solicitud.aprobacion");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitudes $solicitudes)
    {
        //
    }

    /**
     * 
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitudes $solicitudes)
    {
        //
    }
}
