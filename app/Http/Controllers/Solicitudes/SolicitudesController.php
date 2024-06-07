<?php

namespace App\Http\Controllers\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Models\Solicitudes\Solicitudes;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Solicitudes\detalles_solicitud;

class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Solicitud = Solicitudes::all();
        return view("Solicitud.index",compact('Solicitud'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->get();
        return view('Solicitud.create', compact('general','generalConCertificados'));
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
        $Fecha =$now->format('Y-m-d'); // 2024-06-03
        //$Fecha = date('Y-m-d H:i:s');
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
        //$destino = $destinos[$index];
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

        /* $generaleyc = general_eyc::find($generalEycId);
        if ($generaleyc) {
            $generaleyc->Disponibilidad_Estado = $destino;
            $generaleyc->save();
                }*/
            }
        }
        return redirect()->route('solicitud.index');
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
    public function edit($id)
    {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->get();

        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();
        
        return view("Solicitud.aprobacion", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC','general','generalConCertificados'));
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
    public function destroy($id)
    {
        
        $detalle = detalles_solicitud::find($id);
        dd($detalle);
        $detalle->delete();
    
        return response()->json(['success' => 'Record deleted successfully!']);
    }

}
