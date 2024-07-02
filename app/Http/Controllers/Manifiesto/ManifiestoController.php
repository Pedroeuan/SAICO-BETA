<?php

namespace App\Http\Controllers\Manifiesto;

use App\Http\Controllers\Controller;
use App\Models\Manifiesto\manifiesto;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use Illuminate\Http\Request;

class ManifiestoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function BotonRegresar($id)
    {
        $Solicitud = Solicitudes::findOrFail($id);
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();
        $Estatus ='PENDIENTE';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);

        return view("Solicitud.aprobacion", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC','general','generalConCertificados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $Solicitud = Solicitudes::find($id);
        $Estatus ='APROBADO';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);

        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();
        
        return view("Manifiesto.Pre-Manifiesto", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'Cliente' => 'required|string|max:255',
            'Folio' => 'required|string|max:255',
            'Destino' => 'required|string|max:255',
            'Fecha_Salida' => 'required|date',
            'Trabajo' => 'required|string|max:255',
            'Puesto' => 'required|string|max:255',
            'Responsable' => 'required|string|max:255',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(manifiesto $manifiesto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(manifiesto $manifiesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, manifiesto $manifiesto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(manifiesto $manifiesto)
    {
        //
    }
}
