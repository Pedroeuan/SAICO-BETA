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
    $general = general_eyc::get();
    $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
    $Solicitud = Solicitudes::find($id);
    if (!$Solicitud) {
        return redirect()->back()->with('error', 'Solicitud no encontrada.');
    }

    $Estatus = 'APROBADO';
    
    // Actualizar el estado de la solicitud
    $Solicitud->update([
        'Estatus' => $Estatus,
    ]);

    // Obtener todos los detalles de la solicitud
    $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();

    // Recorrer cada detalle de la solicitud para actualizar Cantidad y Unidad
    foreach ($DetallesSolicitud as $detalle) {
        $cantidad = request()->input('Cantidad')[$detalle->idDetalles_Solicitud] ?? null;
        $unidad = request()->input('Unidad')[$detalle->idDetalles_Solicitud] ?? null;

        if ($cantidad !== null && $unidad !== null) {
            $detalle->update([
                'Cantidad' => $cantidad,
                'Unidad' => $unidad,
            ]);
        } else {
            return redirect()->back()->with('error', 'Datos de cantidad o unidad faltantes para algún detalle de la solicitud.');
        }
    }

    // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
    $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');

    // Obtener los registros de General_EyC relacionados
    $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();


    $Manifiestos = manifiesto::where('idSolicitud', $id)->first();
    if ($Solicitud->Estatus == 'APROBADO') {
        if ($Manifiestos) {
        return view('Manifiesto.Pre-Manifiestoedit', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados', 'Manifiestos'));
        }
        else{
            return view("Manifiesto.Pre-Manifiesto", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC'));
        }
    }

    
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

        $id =$request->input('idSolicitud');
        $Solicitud = Solicitudes::find($id);
        $Estatus ='MANIFIESTO';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);

        /*$Solicitud = Solicitudes::findOrFail($id);
        $general = general_eyc::get();
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();*/

        $Manifiestos = new manifiesto;
        $Manifiestos->idSolicitud = $request->input('idSolicitud');
        $Manifiestos->Cliente = $request->input('Cliente');
        $Manifiestos->Folio = $request->input('Folio');
        $Manifiestos->Destino = $request->input('Destino');
        $Manifiestos->Fecha_Salida = $request->input('Fecha_Salida');
        $Manifiestos->Trabajo = $request->input('Trabajo');
        $Manifiestos->Puesto = $request->input('Puesto');
        $Manifiestos->Responsable = $request->input('Responsable');
        $Manifiestos->Observaciones = $request->input('Observaciones');
        $Manifiestos->save();

        //$Solicitud = Solicitudes::all();
        //return view("Solicitud.index",compact('Solicitud'));
        return redirect()->route('solicitud.index');
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'Cliente' => 'required|string|max:255',
            'Folio' => 'required|string|max:255',
            'Destino' => 'required|string|max:255',
            'Fecha_Salida' => 'required|date',
            'Trabajo' => 'required|string|max:255',
            'Puesto' => 'required|string|max:255',
            'Responsable' => 'required|string|max:255',

        ]);

        $id =$request->input('idSolicitud');
        $Solicitud = Solicitudes::find($id);
        $Estatus ='MANIFIESTO';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);

        /*$Solicitud = Solicitudes::findOrFail($id);
        $general = general_eyc::get();
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();*/

        $Manifiestos = manifiesto::where('idSolicitud', $id)->first();
        $Manifiestos->update([
            'idSolicitud' => $request->input('idSolicitud'),
            'Cliente' =>$request->input('Cliente'),
            'Folio' =>$request->input('Folio'),
            'Destino' =>$request->input('Destino'),
            'Fecha_Salida' =>$request->input('Fecha_Salida'),
            'Trabajo' =>$request->input('Trabajo'),
            'Puesto' =>$request->input('Puesto'),
            'Responsable' =>$request->input('Responsable'),
            'Observaciones' =>$request->input('Observaciones'),
        ]);

        //$Solicitud = Solicitudes::all();
        //return view("Solicitud.index",compact('Solicitud'));
        return redirect()->route('solicitud.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(manifiesto $manifiesto)
    {
        //
    }
}
