<?php

namespace App\Http\Controllers\Manifiesto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Manifiesto\manifiesto;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\Historial_Almacen;
use Carbon\Carbon;

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
        $Manifiestos = manifiesto::where('idSolicitud', $id)->first();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();
        $Estatus ='PENDIENTE';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);
        //dd($Manifiestos);

        return view("Solicitud.aprobacion", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC','general','generalConCertificados','Manifiestos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /*/solicitud/Manifiesto/{id}----Boton Crear Manifiesto-aprobacion.blade*/
    public function create(Request $request, $id)
{
    //dd($request->input('Cliente'));
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

    $idSolicitud = $Solicitud->idSolicitud;
    $Manifiesto = manifiesto::where('idSolicitud', $idSolicitud)->first();
    //$Manifiesto = manifiesto::find($id);
    
    if ($Manifiesto) {
        //Log::info('Si existe un manifiesto'); 
    }else{ 
            if ($request->filled('idSolicitud'))
            {
                        //Log::info('Si no existe un manifiesto'); 
                        $Manifiesto = new manifiesto;
                        $Manifiesto->idSolicitud = $request->input('idSolicitud');
                        $Manifiesto->Cliente = $request->input('Cliente');
                        $Manifiesto->Folio = $request->input('Folio');
                        $Manifiesto->Destino = $request->input('Destino');
                        // $Manifiesto->Fecha_Salida = $request->input('Fecha_Salida'); // Este campo se quitó de la base de datos de manifiestos pero para el historial de almacén es necesario
                        $Manifiesto->Trabajo = $request->input('Trabajo');
                        $Manifiesto->Puesto = $request->input('Puesto');
                        $Manifiesto->Responsable = $request->input('Responsable');
                        $Manifiesto->Observaciones = $request->input('Observaciones');
                        $Manifiesto->save();
            }
        }

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

    /*BOTON FINALIZAR MANFIESTO DE PRE-MANIFIESTO.BLADE */
    public function store(Request $request)
    {
        //
        $request->validate([
            'Cliente' => 'required|string|max:255',
            'Folio' => 'required|string|max:255',
            'Destino' => 'required|string|max:255',
            //'Fecha_Salida' => 'required|date',
            'Trabajo' => 'required|string|max:255',
            'Puesto' => 'required|string|max:255',
            'Responsable' => 'required|string|max:255',

        ]);


        $id =$request->input('idSolicitud');
        $Solicitud = Solicitudes::find($id);
        $Estatus ='MANIFIESTO';
        $Tipo = 'SALIDA';
        $NO_DISPONIBLE = 'NO DISPONIBLE';
        // Capturar el valor del switch
        $Renta_Salida = $request->has('Renta') ? 'EN RENTA' : 'SALIDA';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);
        

        //$Solicitud = Solicitudes::findOrFail($id);
        //$general = general_eyc::get();
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        //$generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();

        foreach ($DetallesSolicitud as $detalle) {
            $almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
            if ($almacen) {
                //Empezar a descontar por aqui aprovechando el ciclo FOR
            // Verificar si ya existe un registro en Historial_Almacen con el mismo idGeneral_EyC y Fecha_Salida
            $historialAlmacenExistente = Historial_Almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)
            ->where('Fecha', $request->input('Fecha_Salida'))
            ->where('Tipo', $Tipo)
            ->first();

            if (!$historialAlmacenExistente) {

                $historialAlmacen = new Historial_Almacen;
                $historialAlmacen->idAlmacen = $almacen->idAlmacen;
                $historialAlmacen->idGeneral_EyC = $detalle->idGeneral_EyC;
                $historialAlmacen->Tipo = $Renta_Salida;
                $historialAlmacen->Cantidad = $detalle->Cantidad; // Usar la cantidad de detalles_solicitud
                $historialAlmacen->Fecha = $request->input('Fecha_Salida');
                $historialAlmacen->Tierra_Costafuera = $request->input('Destino');
                $historialAlmacen->Folio = $request->input('Folio');
                $historialAlmacen->save();
    
                // Actualizar el estado en general_eyc a "NO DISPONIBLE"
                $generalEyC = general_eyc::find($detalle->idGeneral_EyC);
                $Almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
                $AlmacenStock = $Almacen->Stock;
                    if($AlmacenStock > 0)
                    {
                        $AlmacenDescuento = $detalle->Cantidad;
                        $TotalActual = $AlmacenStock-$AlmacenDescuento;
                        $Almacen ->update([
                            'Stock' => $TotalActual,
                        ]);

                        $generalEyC ->update([
                            'Disponibilidad_Estado' => $NO_DISPONIBLE,
                        ]);

                    }
                }
                }
            }

            if ($request->filled('Cliente')) { 
                $Manifiestos = new manifiesto;
                $Manifiestos->idSolicitud = $request->input('idSolicitud');
                $Manifiestos->Cliente = $request->input('Cliente');
                $Manifiestos->Folio = $request->input('Folio');
                $Manifiestos->Destino = $request->input('Destino');
                // $Manifiestos->Fecha_Salida = $request->input('Fecha_Salida'); // Este campo se quitó de la base de datos de manifiestos pero para el historial de almacén es necesario
                $Manifiestos->Trabajo = $request->input('Trabajo');
                $Manifiestos->Puesto = $request->input('Puesto');
                $Manifiestos->Responsable = $request->input('Responsable');
                $Manifiestos->Observaciones = $request->input('Observaciones');
                $Manifiestos->save();
                }
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
    /*BOTON FINALIZAR MANIFIESTO DE PRE-MANIFIESTOEDIT-ACTUALIZA TABLA MANIFIESTOS */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Cliente' => 'required|string|max:255',
            'Folio' => 'required|string|max:255',
            'Destino' => 'required|string|max:255',
            //'Fecha_Salida' => 'required|date',
            'Trabajo' => 'required|string|max:255',
            'Puesto' => 'required|string|max:255',
            'Responsable' => 'required|string|max:255',
        ]);

        $id = $request->input('idSolicitud');
        $Solicitud = Solicitudes::find($id);
        $Fecha = $request->input('Fecha_Salida');
        $Estatus ='MANIFIESTO';
        $Tipo = ['SALIDA', 'EN RENTA'];
        // Capturar el valor del switch
        $Renta_Salida = $request->has('Renta') ? 'EN RENTA' : 'SALIDA';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);

        //$Solicitud = Solicitudes::findOrFail($id);
        //$general = general_eyc::get();
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        //$generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();

        foreach ($DetallesSolicitud as $detalle) {
            $almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
            if ($almacen) {
            // Verificar si ya existe un registro en Historial_Almacen con el mismo idGeneral_EyC y Fecha_Salida
            $historialAlmacenExistente = Historial_Almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)
            ->where('Fecha', $Fecha)
            ->whereIn('Tipo', $Tipo)
            ->first();

            if (!$historialAlmacenExistente) 
            {
                $historialAlmacen = new Historial_Almacen;
                $historialAlmacen->idAlmacen = $almacen->idAlmacen;
                $historialAlmacen->idGeneral_EyC = $detalle->idGeneral_EyC;
                $historialAlmacen->Tipo = $Renta_Salida;
                $historialAlmacen->Cantidad = $detalle->Cantidad; // Usar la cantidad de detalles_solicitud
                $historialAlmacen->Fecha =  $Fecha;
                $historialAlmacen->Tierra_Costafuera = $request->input('Destino');
                $historialAlmacen->Folio = $request->input('Folio');
                $historialAlmacen->save();
    
                // Actualizar el estado en general_eyc a "NO DISPONIBLE"
                $generalEyC = general_eyc::find($detalle->idGeneral_EyC);
                
                if ($generalEyC) {
                    $generalEyC->Disponibilidad_Estado = 'NO DISPONIBLE';
                    $generalEyC->save();
                        }
            }else
                {
                    $Manifiesto = manifiesto::where('idSolicitud', $id)->first();
                    $Destino_Form = $request->input('Destino');
                    $Cantidad_Detalle_Solicitud = $detalle->Cantidad;
                    $Cantidad_Actualizar = $historialAlmacenExistente->Cantidad;
                    if($Cantidad_Detalle_Solicitud != $Cantidad_Actualizar || $Destino_Form != $historialAlmacenExistente->Tierra_Costafuera || $Tipo != $historialAlmacenExistente->Tipo )
                    {
                        $historialAlmacenExistente ->update([
                            'Cantidad' => $Cantidad_Detalle_Solicitud,
                            'Tierra_Costafuera' => $Destino_Form,
                            'Tipo' => $Renta_Salida,
                        ]);
                    }
                }
                    
                }
            }

            $Manifiestos = manifiesto::where('idSolicitud', $id)->first();
            if($Manifiestos)
            {
                $Manifiestos->update([
                    'idSolicitud' => $request->input('idSolicitud'),
                    'Cliente' =>$request->input('Cliente'),
                    'Folio' =>$request->input('Folio'),
                    'Destino' =>$request->input('Destino'),
                    //'Fecha_Salida' =>$request->input('Fecha_Salida'),
                    'Trabajo' =>$request->input('Trabajo'),
                    'Puesto' =>$request->input('Puesto'),
                    'Responsable' =>$request->input('Responsable'),
                    'Observaciones' =>$request->input('Observaciones'),
                ]);
            }
            


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
