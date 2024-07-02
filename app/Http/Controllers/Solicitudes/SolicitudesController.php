<?php

namespace App\Http\Controllers\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;


use Carbon\Carbon;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\detalles_kits;
use App\Models\EquiposyConsumibles\kits;
use App\Models\Manifiesto\manifiesto;

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

    public function obtenerDetallesKits($id)
    {
        $detallesKits = detalles_kits::where('idKits', $id)->get();
        return response()->json($detallesKits);
    }

    public function obtenerGeneralKits($id)
    {
        $generalEyC = general_eyc::with('Certificados')->find($id);
        return response()->json($generalEyC);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        // Obtener todos los Kits con sus detalles_kits
        $kitsConDetalles = Kits::with('detalles_kits')->get();

        /*Inventario */
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
        /*Kits */
        /*$Kit = kits::findOrFail($id);
        $DetallesKits = detalles_kits::where('idKits', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesKits->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();*/

        return view('Solicitud.create', compact('general','generalConCertificados','kitsConDetalles'));
                                    /*vista*/    /*variable donde se guardan los datos*/
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSolicitud(Request $request)
    {
        $now = Carbon::now();
        $Solicitud = new Solicitudes();
        $tecnico = 'Pedro'; // Cambia esto a futuro por el nombre de usuario o rol
        $Estatus = 'PENDIENTE';
        $Fecha = $request->input('Fecha_Servicio');
        $Solicitud->tecnico = $tecnico;
        $Solicitud->Fecha = $Fecha;
        $Solicitud->Estatus = $Estatus;
        $Solicitud->save();

        // Obtener los datos de los inputs
        $generalEycIds = $request->input('general_eyc_id');
        $cantidades = $request->input('cantidad');
        $unidades = $request->input('unidad');

        // Iterar sobre los datos y guardarlos en la base de datos
        foreach ($generalEycIds as $index => $generalEycId) {
            if (isset($cantidades[$index]) && isset($unidades[$index])) {
                $cantidad = $cantidades[$index];
                $unidad = $unidades[$index];

                // Crear una nueva instancia del modelo detalles_solicitud
                $detallesolicitud = new detalles_solicitud();
                $detallesolicitud->idSolicitud = $Solicitud->idSolicitud;
                $detallesolicitud->idGeneral_EyC = $generalEycId;
                $detallesolicitud->Cantidad = $cantidad;
                $detallesolicitud->Unidad = $unidad;
                $detallesolicitud->save();
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
        $Solicitud = Solicitudes::findOrFail($id);
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();
        $Manifiestos = manifiesto::where('idSolicitud', $id)->first();

        if ($Solicitud->Estatus == 'PENDIENTE') {
            if (!$Manifiestos) {
                return view("Solicitud.aprobacion", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados'));
            }
            // Opcionalmente, puedes manejar el caso donde la solicitud sí está en Manifiestos cuando está pendiente
            // return redirect()->route('alguna_ruta')->with('error', 'La solicitud está pendiente y se encuentra en Manifiestos');
        }
    
        if ($Solicitud->Estatus == 'APROBADO') {
            if (!$Manifiestos) {
                return view('Manifiesto.Pre-Manifiesto', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados'));
            }
        }
    
        if ($Solicitud->Estatus == 'MANIFIESTO') {
            if ($Manifiestos) {
            return view('Manifiesto.Pre-Manifiestoedit', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados', 'Manifiestos'));
            }
        }

        /*switch ($Solicitud->Estatus) {
            case 'PENDIENTE':
                return view("Solicitud.aprobacion", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC','general','generalConCertificados'));
            case 'APROBADO':
                return view('Manifiesto.Pre-Manifiesto', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC','general','generalConCertificados'));
            case 'MANIFIESTO':
                return view('Manifiesto.Pre-Manifiestoedit', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC','general','generalConCertificados','Manifiestos'));
            //default:
                //return view('solicitudes.default', compact('solicitud'));
        }*/
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
    /*Botón de detalles solicitud */
    public function destroyDetallesSolicitud($id)
    {
        try {
            $detalle = detalles_solicitud::findOrFail($id); // Utiliza findOrFail para lanzar una excepción si no encuentra el modelo
            $detalle->delete();
    
            return response()->json(['success' => 'Record deleted successfully!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the record.'], 500);
        }
    }

    /*Eliminación de Toda la Solicitud */
    public function destroySolicitud($id)
    {
        // Eliminar los detalles relacionados con el idSolicitud
        manifiesto::where('idSolicitud',$id)->delete();
        detalles_solicitud::where('idSolicitud', $id)->delete();
        Solicitudes::where('idSolicitud', $id)->delete();
            
        return redirect()->route('solicitud.index');
    }

    public function agregarDetallesSolicitud(Request $request)
    {
        // Obtén las variables de la solicitud
        $idFila = $request->input('idFila');
        $idSolicitud = $request->input('idSolicitud');
        $cantidad=0;
        $unidad='ESPERA DE DATO';

         // Registra los valores en el archivo de log
        //Log::info('ID de Fila:', ['idFila' => $idFila]);
        //Log::info('ID de Solicitud:', ['idSolicitud' => $idSolicitud]);
        /*Los logs de Laravel se encuentran en el archivo storage/logs/laravel.log. Puedes revisar este archivo para ver los valores registrados.*/

        // Procesa los datos según tus necesidades
        // Aquí puedes agregar la lógica para agregar el detalle a la solicitud
        $DetallesSolicitud = new detalles_solicitud();
        $DetallesSolicitud->idSolicitud = $idSolicitud;
        $DetallesSolicitud->idGeneral_EyC = $idFila;
        $DetallesSolicitud->cantidad = $cantidad;
        $DetallesSolicitud->Unidad = $unidad;
        $DetallesSolicitud->save();

        // Retornar una respuesta JSON con el idDetalles_Solicitud recién creado
        return response()->json([
            'status' => 'success',
            'idDetalles_Solicitud' => $DetallesSolicitud->idDetalles_Solicitud,
        ]);
    }


}
