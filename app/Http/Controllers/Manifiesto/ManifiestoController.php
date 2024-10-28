<?php

namespace App\Http\Controllers\Manifiesto;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use App\Models\Manifiesto\manifiesto;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\Historial_Almacen;
use App\Models\Clientes\clientes;
use App\Models\EquiposyConsumibles\devolucion;
use Carbon\Carbon;

use App\Http\Controllers\Solicitudes\SolicitudesController;

class ManifiestoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getCount()
    {
        $total = Manifiesto::where('Folio', 'REGEXP', '^[A-Z]{4}-[0-9]+/[0-9]{2}$')->count();
        return response()->json(['total' => $total]);
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

        foreach ($DetallesSolicitud as $detalle) {
            $almacen = Almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
              // Obtener el stock actual del almacén
                $stockActual = $almacen ? $almacen->Stock : 0;

                // Sumar el stock actual con la cantidad ya solicitada en `DetallesSolicitud`
                $detalle->stockDisponible = $stockActual + $detalle->Cantidad;
        }

        return view("Solicitud.aprobacion", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC','general','generalConCertificados','Manifiestos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    /*/solicitud/Manifiesto/{id}----Boton Crear Manifiesto-aprobacion.blade*/
    public function create(Request $request, $id)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;

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
                        $Manifiesto->Trabajo = $request->input('Trabajo');
                        $Manifiesto->Puesto = $request->input('Puesto');
                        $Manifiesto->Responsable = $request->input('Responsable');
                        $Manifiesto->Observaciones = $request->input('Observaciones');
                        $Manifiesto->ScanPDF = 'ESPERA DE DATO';
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

        $clientes = clientes::all();

        if ($Solicitud->Estatus == 'APROBADO') {
            if ($Manifiestos) {
            return view('Manifiesto.Pre-Manifiestoedit', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados','Manifiestos','clientes','Nombre'));
            }
            else{
                return view("Manifiesto.Pre-Manifiesto", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'clientes','Nombre'));
            }
        }
    }

    public function createplus(Request $request, $id)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;

        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
        $Solicitud = Solicitudes::find($id);
        if (!$Solicitud) {
            return redirect()->back()->with('error', 'Solicitud no encontrada.');
        }

        $Estatus = 'APROBADO';
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
        ]);


        $idSolicitud = $Solicitud->idSolicitud;
        $Manifiesto = manifiesto::where('idSolicitud', $idSolicitud)->first();
        //$Manifiesto = manifiesto::find($id);
        $Manifiesto->delete();

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

            $idDetalles_Solicitud = $request->input('idDetalles_Solicitud', []);

                if (is_array($idDetalles_Solicitud)) {
                    foreach ($idDetalles_Solicitud as $idDetalle) {

                        // Eliminar los registros de detalles_solicitud basados en los valores individuales
                        detalles_solicitud::where('idDetalles_Solicitud', $idDetalle)
                            ->where('idSolicitud', $idSolicitud)
                            ->delete();
                    }
                } else {
                    Log::warning('El campo idDetalles_Solicitud no es un array.');
                }

                if ($request->filled('idSolicitud'))
                {
                        $Manifiesto = new manifiesto;
                        $Manifiesto->idSolicitud = $request->input('idSolicitud');
                        $Manifiesto->Cliente = $request->input('Cliente');
                        $Manifiesto->Folio = $request->input('Folio');
                        $Manifiesto->Destino = $request->input('Destino');
                        $Manifiesto->Trabajo = $request->input('Trabajo');
                        $Manifiesto->Puesto = $request->input('Puesto');
                        $Manifiesto->Responsable = $request->input('Responsable');
                        $Manifiesto->Observaciones = $request->input('Observaciones');
                        $Manifiesto->ScanPDF = 'ESPERA DE DATO';
                        $Manifiesto->save();
                }

        // Obtener todos los detalles de la solicitud
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();

        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');

        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();

        $Manifiestos = manifiesto::where('idSolicitud', $id)->first();

        $clientes = clientes::all();

        if ($Solicitud->Estatus == 'APROBADO') {
            if ($Manifiestos) {
            return view('Manifiesto.Pre-Manifiestoeditplus', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados','Manifiestos','clientes','Nombre'));
            }
        }
    }

    /*BOTON FINALIZAR MANFIESTO DE PRE-MANIFIESTO.BLADE */
    public function store(Request $request)
    {
        //
        $request->validate([
            'Cliente' => 'required|exists:clientes,Cliente', // 'required' asegura que no esté vacío
            'Folio' => 'required|string|max:255',
            'Destino' => 'required|string|max:255',
            'Fecha_Salida' => 'required|date',
            'Trabajo' => 'required|string|max:255',
            'Puesto' => 'required|string|max:255',
            'Responsable' => 'required|string|max:255',
            'Recibe_Nombre' => 'required|string|max:255',
        ]);

        $id = $request->input('idSolicitud');
        $Solicitud = Solicitudes::find($id);
        $Fecha_Form = $request->input('Fecha_Salida');
        $Fecha_BD = $Solicitud->Fecha;
        $Estatus ='MANIFIESTO';
        $Tipo = ['SALIDA', 'EN RENTA'];
        $NO_DISPONIBLE = 'NO DISPONIBLE';
        $EsperaDato ='ESPERA DE DATO';
        // Capturar el valor del switch
        $Renta_Salida = $request->has('Renta') ? 'EN RENTA' : 'SALIDA';
        $Recibe_Nombre = $request->input('Recibe_Nombre');
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
            'tecnico' => $Recibe_Nombre,
        ]);

        if($Fecha_Form != $Fecha_BD)
        {        
            // Actualizar los datos del la solicitud
            $Solicitud ->update([
                'Fecha' => $Fecha_Form,
            ]);
        }
        
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');

        foreach ($DetallesSolicitud as $detalle) 
        {
            $almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
            if ($almacen) 
            {
                // Verificar si ya existe un registro en Historial_Almacen con el mismo idGeneral_EyC y Fecha_Salida
                $historialAlmacenExistente = Historial_Almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)
                ->where('Fecha', $Fecha_Form)
                ->where('Tipo', $Tipo)
                ->first();

                if (!$historialAlmacenExistente) 
                {
                    $historialAlmacen = new Historial_Almacen;
                    $historialAlmacen->idAlmacen = $almacen->idAlmacen;
                    $historialAlmacen->idGeneral_EyC = $detalle->idGeneral_EyC;
                    $historialAlmacen->Tipo = $Renta_Salida;
                    $historialAlmacen->Cantidad = $detalle->Cantidad; // Usar la cantidad de detalles_solicitud
                    $historialAlmacen->Fecha = $Fecha_Form;
                    $historialAlmacen->Tierra_Costafuera = $request->input('Destino');
                    $historialAlmacen->Folio = $request->input('Folio');
                    $historialAlmacen->save();
        
                    // Actualizar el estado en general_eyc a "NO DISPONIBLE"
                    $generalEyC = general_eyc::find($detalle->idGeneral_EyC);
                    $Almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
                    $AlmacenStock = $Almacen->Stock;
                    $AlmacenDescuento = $detalle->Cantidad;
                    $Verificar = $AlmacenStock-$AlmacenDescuento;
                    
                        if($Verificar == 0)
                        {
                            $TotalActual = $AlmacenStock-$AlmacenDescuento;
                            $Almacen ->update([
                                'Stock' => $TotalActual,
                            ]);

                            $generalEyC ->update([
                                'Disponibilidad_Estado' => $NO_DISPONIBLE,
                            ]);

                        }
                        else
                        {
                            $TotalActual = $AlmacenStock-$AlmacenDescuento;
                            $Almacen ->update([
                                'Stock' => $TotalActual,
                            ]);
                        }
                }
            }
        }

            if ($request->filled('Cliente')) 
            { 
                $Manifiestos = new manifiesto;
                $Manifiestos->idSolicitud = $request->input('idSolicitud');
                $Manifiestos->Cliente = $request->input('Cliente');
                $Manifiestos->Folio = $request->input('Folio');
                $Manifiestos->Destino = $request->input('Destino');
                $Manifiestos->Trabajo = $request->input('Trabajo');
                $Manifiestos->Puesto = $request->input('Puesto');
                $Manifiestos->Responsable = $request->input('Responsable');
                $Manifiestos->ScanPDF = $EsperaDato;
                if($request->input('Observaciones')==null)
                {
                    $Manifiestos->Observaciones = '-----';
                }else{
                    $Manifiestos->Observaciones = $request->input('Observaciones');
                }
                $Manifiestos->save();
            }

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
            'Fecha_Salida' => 'required|date',
            'Trabajo' => 'required|string|max:255',
            'Puesto' => 'required|string|max:255',
            'Responsable' => 'required|string|max:255',
            'Recibe_Nombre' => 'required|string|max:255',
        ]);

        $id = $request->input('idSolicitud');
        $Solicitud = Solicitudes::find($id);
        $Fecha = $request->input('Fecha_Salida');
        $Fecha_DB_Solicitud = $Solicitud->Fecha;
        $Estatus ='MANIFIESTO';
        $Tipo = ['SALIDA', 'EN RENTA'];
        $NO_DISPONIBLE = 'NO DISPONIBLE';
        // Capturar el valor del switch
        $Renta_Salida = $request->has('Renta') ? 'EN RENTA' : 'SALIDA';
        $Recibe_Nombre = $request->input('Recibe_Nombre');
        
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
            'Fecha' => $Fecha,
            'tecnico' => $Recibe_Nombre,
        ]);

        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');

        foreach ($DetallesSolicitud as $detalle) 
        {
            $almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
            if ($almacen) 
            {
                    // Verificar si ya existe un registro en Historial_Almacen con el mismo idGeneral_EyC y Fecha_Salida
                    $historialAlmacenExistente = Historial_Almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)
                    ->where('Fecha', $Fecha_DB_Solicitud)
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
                        $Almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first(); 
                        $AlmacenStock = $Almacen->Stock;
                        $AlmacenDescuento = $detalle->Cantidad;
                        $Verificar = $AlmacenStock-$AlmacenDescuento;
                        
                        if($Verificar == 0)
                        {
                            $TotalActual = $AlmacenStock-$AlmacenDescuento;
                            $Almacen ->update([
                                'Stock' => $TotalActual,
                            ]);

                            $generalEyC ->update([
                                'Disponibilidad_Estado' => $NO_DISPONIBLE,
                            ]);
                        }
                        else
                        {
                            $TotalActual = $AlmacenStock-$AlmacenDescuento;
                            $Almacen ->update([
                                'Stock' => $TotalActual,
                            ]);
                        }
                    }
                    else /*Si ya existe un $historialAlmacenExistente */
                        {
                            $Almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first(); 

                            $Destino_Form = $request->input('Destino');
                            $Destino_BD = $historialAlmacenExistente->Tierra_Costafuera;

                            $Tipo_DB= $historialAlmacenExistente->Tipo;
                            $Fecha_BD = $historialAlmacenExistente->Fecha;

                            $Cantidad_Detalle_Solicitud = $detalle->Cantidad;
                            $Cantidad_Actualizar = $historialAlmacenExistente->Cantidad;

                            $Folio_Form = $request->input('Folio');
                            $Folio_DB = $historialAlmacenExistente->Folio;

                            $CantidadAnterior = $historialAlmacenExistente->Cantidad;
                            $CantidadAlmacen = $Almacen->Stock;
                            $CantidadNueva = $detalle->Cantidad;
                            $StockAnterior = $CantidadAnterior + $CantidadAlmacen;
                            $StockAlmacenActualizar = $StockAnterior - $CantidadNueva;
                            
                            if($Cantidad_Detalle_Solicitud != $Cantidad_Actualizar)
                                {
                                    $Almacen ->update([
                                        'Stock' => $StockAlmacenActualizar,
                                    ]);

                                    $historialAlmacenExistente ->update([
                                        'Cantidad' => $Cantidad_Detalle_Solicitud,
                                    ]);
                                }

                            if($Destino_Form != $Destino_BD)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Tierra_Costafuera' => $Destino_Form,
                                    ]);
                                }

                            if($Tipo != $Tipo_DB)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Tipo' => $Renta_Salida,
                                    ]);
                                }

                            if($Folio_Form != $Folio_DB)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Folio' => $Folio_Form,
                                    ]);
                                }

                            if($Fecha != $Fecha_BD)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Fecha' => $Fecha,
                                    ]);
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
                        'Trabajo' =>$request->input('Trabajo'),
                        'Puesto' =>$request->input('Puesto'),
                        'Responsable' =>$request->input('Responsable'),
                        'Observaciones' =>$request->input('Observaciones'),
                    ]);

                // Validar que se ha enviado el archivo de foto
                    if ($request->hasFile('ScanPDF') && $request->file('ScanPDF')->isValid()) {
                        $ScanPDF = $request->file('ScanPDF');
                        // Obtener el último número consecutivo
                        $lastFile = collect(Storage::disk('public')->files('Manifiestos/Salidas'))
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
                        $newFileNameFoto = $newNumber . '_' . $ScanPDF->getClientOriginalName();
                        // Guardar el archivo en la carpeta "public/Equipos/Fotos"
                        $ScanPDFPath = $ScanPDF->storeAs('Manifiestos/Salidas', $newFileNameFoto, 'public');

                        $Manifiestos->ScanPDF = $ScanPDFPath;
                    }

                    $Manifiestos->save();
                }
            
        }

        return redirect()->route('solicitud.index');
    }

    public function updateplus(Request $request, $id)
    {
        $request->validate([
            'Cliente' => 'required|string|max:255',
            'Folio' => 'required|string|max:255',
            'Destino' => 'required|string|max:255',
            'Fecha_Salida' => 'required|date',
            'Trabajo' => 'required|string|max:255',
            'Puesto' => 'required|string|max:255',
            'Responsable' => 'required|string|max:255',
            'Recibe_Nombre' => 'required|string|max:255',
        ]);

        $id = $request->input('idSolicitud');
        $Solicitud = Solicitudes::find($id);
        $Fecha = $request->input('Fecha_Salida');
        $Estatus ='MANIFIESTO';
        $Tipo = ['SALIDA', 'EN RENTA'];
        $NO_DISPONIBLE = 'NO DISPONIBLE';
        // Capturar el valor del switch
        $Renta_Salida = $request->has('Renta') ? 'EN RENTA' : 'SALIDA';
        $Recibe_Nombre = $request->input('Recibe_Nombre');
        // Actualizar los datos del equipo
        $Solicitud ->update([
            'Estatus' => $Estatus,
            'Fecha' => $Fecha,
            'tecnico' => $Recibe_Nombre,
        ]);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');

        foreach ($DetallesSolicitud as $detalle) 
        {
            $almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
            if ($almacen) 
            {
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
                        $Almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
                        $AlmacenStock = $Almacen->Stock;
                        $AlmacenDescuento = $detalle->Cantidad;
                        $Verificar = $AlmacenStock-$AlmacenDescuento;
                        
                        if($Verificar == 0)
                        {
                            $TotalActual = $AlmacenStock-$AlmacenDescuento;
                            $Almacen ->update([
                                'Stock' => $TotalActual,
                            ]);

                            $generalEyC ->update([
                                'Disponibilidad_Estado' => $NO_DISPONIBLE,
                            ]);
                        }
                        else
                        {
                            $TotalActual = $AlmacenStock-$AlmacenDescuento;
                            $Almacen ->update([
                                'Stock' => $TotalActual,
                            ]);
                        }
                    }
                    else /*Si ya existe un $historialAlmacenExistente  */
                        {
                            $Destino_Form = $request->input('Destino');
                            $Destino_BD = $historialAlmacenExistente->Tierra_Costafuera;

                            $Tipo_DB= $historialAlmacenExistente->Tipo;
                            $Fecha_BD = $historialAlmacenExistente->Fecha;

                            $Cantidad_Detalle_Solicitud = $detalle->Cantidad;
                            $Cantidad_Actualizar = $historialAlmacenExistente->Cantidad;

                            $Folio_Form = $request->input('Folio');
                            $Folio_DB = $historialAlmacenExistente->Folio;

                            if($Cantidad_Detalle_Solicitud != $Cantidad_Actualizar)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Cantidad' => $Cantidad_Detalle_Solicitud,
                                    ]);
                                }

                            if($Destino_Form != $Destino_BD)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Tierra_Costafuera' => $Destino_Form,
                                    ]);
                                }

                            if($Tipo != $Tipo_DB)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Tipo' => $Renta_Salida,
                                    ]);
                                }

                            if($Folio_Form != $Folio_DB)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Folio' => $Folio_Form,
                                    ]);
                                }

                            if($Fecha != $Fecha_BD)
                                {
                                    $historialAlmacenExistente ->update([
                                        'Fecha' => $Fecha,
                                    ]);
                                }
                            // Obtener el registro correspondiente en la tabla 'almacen'
                            $Almacen = almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();

                            // Verificar si se encontró el registro en 'almacen'
                            if ($Almacen) 
                            {
                                $AlmacenStock = $Almacen->Stock;
                                $AlmacenDescuento = $detalle->Cantidad;
                                $Verificar = $AlmacenStock - $AlmacenDescuento;

                                // Actualizar el stock y el estado de disponibilidad
                                if ($Verificar <= 0) 
                                {
                                    // Si el stock resultante es cero o menos, actualizar el estado a "NO DISPONIBLE"
                                    $Almacen->update([
                                        'Stock' => 0, // Puede ser 0 en lugar de un número negativo
                                    ]);

                                    // Obtener el registro correspondiente en la tabla 'general_eyc'
                                    $generalEyC = general_eyc::find($detalle->idGeneral_EyC);

                                    // Verificar si se encontró el registro en 'general_eyc'
                                    if ($generalEyC) {
                                        $generalEyC->update([
                                            'Disponibilidad_Estado' => 'NO DISPONIBLE',
                                        ]);
                                    }
                                } 
                                else 
                                {
                                    // Si el stock resultante es mayor a cero, simplemente actualizar el stock
                                    $Almacen->update([
                                        'Stock' => $Verificar,
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
                        'Trabajo' =>$request->input('Trabajo'),
                        'Puesto' =>$request->input('Puesto'),
                        'Responsable' =>$request->input('Responsable'),
                        'Observaciones' =>$request->input('Observaciones'),
                    ]);
                }
            
        }

        return redirect()->route('solicitud.index');
    }

    public function PreConcluirManifiesto (Request $request, $id)
    { 
        /**/
        $EntregaDevolucion = $request->input('Entrega_Nombre_Devolucion');
        $RecibeDevolucion = $request->input('Recibe_Nombre_Devolucion');
        $Observaciones = $request->input('Observaciones_Devolucion');
        $Condiciones = $request->input('Condiciones_Retorno');
        $ScanPDF = 'ESPERA DE DATO';

        // Obtener los ids de las solicitudes en formato array
        $idsSolicitud = json_decode($request->input('idSolicitudes'), true);

        // Actualizar el estatus de las solicitudes
        Solicitudes::whereIn('idSolicitud', $idsSolicitud)->update(['Estatus' => 'PRE-CONCLUIDO']);
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;

        // Obtener las solicitudes actualizadas
        $solicitudesActualizadas = Solicitudes::whereIn('idSolicitud', $idsSolicitud)->get();

        // Usar un array para rastrear las solicitudes ya insertadas
        $solicitudesProcesadas = [];

        // Insertar los registros en la tabla devoluciones
        foreach ($solicitudesActualizadas as $solicitud) {
            // Obtener el idManifiesto asociado a la solicitud
            $idSolicitud = $solicitud->idSolicitud; // Obtener idSolicitud

        // Verificar si ya se procesó esta solicitud
        if (in_array($idSolicitud, $solicitudesProcesadas)) {
            continue; // Si ya se procesó, saltar a la siguiente iteración
        }

            // Buscar el idManifiesto en la tabla manifiestos basado en el idSolicitud
            $idManifiesto = DB::table('manifiestos')
            ->where('idSolicitud', $idSolicitud)
            ->value('idManifiestos'); // Obtener solo el valor de idManifiesto

            $Fecha = Carbon::now();
            // Crear una nueva devolución
            DB::table('devoluciones')->insert([

                'idManifiestos'=> $idManifiesto,
                'idSolicitud'=> $idSolicitud,
                'Entrega' => $EntregaDevolucion,
                'Recibe'  => $RecibeDevolucion,
                'Fecha'  => $Fecha,
                'Observaciones'  => $Observaciones,
                'Condiciones'  => $Condiciones,
                'ScanPDF'  => $ScanPDF,
            ]);
        }

        if($rol == 'Técnicos')
        {
            $Solicitudes = Solicitudes::where('tecnico',$Nombre)->get();
        }
        else
        {
            // Obtener todas las solicitudes
            //$Solicitudes = Solicitudes::all();
            $Solicitudes = Solicitudes::with(['detalles_solicitud.manifiesto.devolucion'])->get();
        }

        /*Condiciones de los Folios para la vista de solicitud*/
        // Crear un array para almacenar el último folio encontrado para cada grupo
        $ultimoFolioPorGrupo = [];

        // Procesar cada solicitud
        foreach ($Solicitudes as $solicitud) 
        {
            $manifiesto = manifiesto::where('idSolicitud', $solicitud->idSolicitud)->first();
            $devolucion = devolucion::where('idSolicitud', $solicitud->idSolicitud)->first();  

            if ($manifiesto) 
            {
                
                $solicitud->folio = $manifiesto->Folio;
                $solicitud->pdf = $manifiesto->ScanPDF; // Guardar la ruta del PDF
                
                if($devolucion)
                {
                    //$devolucion->pdf = $devolucion->ScanPDF;
                    $solicitud->devolucion_pdf = $devolucion->ScanPDF;
                }else {
                $solicitud->devolucion_pdf = null;
                }
                //dump($devolucion);
                // Verificar si la expresión regular coincide
                if (preg_match('/^([A-Z]+-\d+)/', $solicitud->folio, $matches)) {
                    $folioBase = $matches[1];
                } else {
                    // Si no coincide, asignar un valor predeterminado o manejar el caso
                    $folioBase = '';
                }
        
                // Extraer la letra al final del folio si existe (después del número antes de la "/")
                if (preg_match('/([A-Z]?)\/\d{2}$/', $solicitud->folio, $matches)) {
                    $folioLetra = $matches[1] ?? ''; // Si no hay letra, asigna una cadena vacía
                } else {
                    $folioLetra = '';
                }
        
                // Verificar si este folio es el último en su grupo (mayor en orden lexicográfico)
                if (!isset($ultimoFolioPorGrupo[$folioBase]) || strcmp($folioLetra, $ultimoFolioPorGrupo[$folioBase]) > 0) {
                    $ultimoFolioPorGrupo[$folioBase] = $folioLetra;
                }
            } 
            else 
            {
                $solicitud->folio = "No Asignado";
                $solicitud->pdf = null; // No hay PDF disponible
                $solicitud->devolucion_pdf = null;
            }
        }
        

        // Marcar los folios que deben ocultar el botón
        foreach ($Solicitudes as $solicitud) 
        {
            // Intentar coincidir con el patrón del folio base
            if (preg_match('/^([A-Z]+-\d+)/', $solicitud->folio, $matches)) {
                $folioBase = $matches[1];  // Si coincide, asignar el valor
            } else {
                $folioBase = '';  // Si no coincide, asignar un valor predeterminado
            }
        
            // Intentar coincidir con el patrón de la letra del folio
            if (preg_match('/([A-Z]?)\/\d{2}$/', $solicitud->folio, $matches)) {
                $folioLetra = $matches[1] ?? '';  // Si coincide, asignar la letra o cadena vacía
            } else {
                $folioLetra = '';  // Si no coincide, asignar una cadena vacía
            }
        
            // Si este folio no es el último en su grupo, ocultar el botón
            $solicitud->hidePlus = isset($ultimoFolioPorGrupo[$folioBase]) && $folioLetra !== $ultimoFolioPorGrupo[$folioBase];
        }
            return view("Solicitud.index", compact('Solicitudes','Nombre','rol'));
    }

    public function ConcluirManifiesto (Request $request, $id)
    { 
        $EntregaDevolucion = $request->input('Entrega_Nombre_Devolucion');
        $RecibeDevolucion = $request->input('Recibe_Nombre_Devolucion');
        $Observaciones = $request->input('Observaciones_Devolucion');
        $Condiciones = $request->input('Condiciones_Retorno');

        // Obtener los ids de las solicitudes en formato array
        $idsSolicitud = json_decode($request->input('idSolicitudes'), true);

        // Actualizar el estatus de las solicitudes
        Solicitudes::whereIn('idSolicitud', $idsSolicitud)->update(['Estatus' => 'CONCLUIDO']);
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;

        // Obtener las solicitudes actualizadas
        $solicitudesActualizadas = Solicitudes::whereIn('idSolicitud', $idsSolicitud)->get();

        // Insertar los registros en la tabla devoluciones
        foreach ($solicitudesActualizadas as $solicitud) {
            // Obtener el idManifiesto asociado a la solicitud
            $idSolicitud = $solicitud->idSolicitud; // Obtener idSolicitud

            // Buscar el idManifiesto en la tabla manifiestos basado en el idSolicitud
            $idManifiesto = DB::table('manifiestos')
            ->where('idSolicitud', $idSolicitud)
            ->value('idManifiestos'); // Obtener solo el valor de idManifiesto

            $Fecha = Carbon::now();
            // Actualizar o insertar en la tabla devoluciones
            DB::table('devoluciones')->updateOrInsert(
                ['idManifiestos' => $idManifiesto, 'idSolicitud' => $idSolicitud], // Condiciones de búsqueda
                [
                    'Entrega' => $EntregaDevolucion,
                    'Recibe' => $RecibeDevolucion,
                    'Fecha' => $Fecha,
                    'Condiciones' => $Condiciones,
                    'Observaciones' => $Observaciones,
                    //Agregar ScanPDF
                ]
            );

    $Devoluciones = devolucion::where('idSolicitud', $idSolicitud)->first();
        // Validar que se ha enviado el archivo de foto
        if ($request->hasFile('ScanPDF') && $request->file('ScanPDF')->isValid()) {
            $ScanPDF = $request->file('ScanPDF');
                // Obtener el último número consecutivo
                    $lastFile = collect(Storage::disk('public')->files('Manifiestos/Devoluciones'))
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
                $newFileNameFoto = $newNumber . '_' . $ScanPDF->getClientOriginalName();
                // Guardar el archivo en la carpeta "public/Equipos/Fotos"
                $ScanPDFPath = $ScanPDF->storeAs('Manifiestos/Devoluciones', $newFileNameFoto, 'public');
            
                $Devoluciones->ScanPDF = $ScanPDFPath;
                }
                $Devoluciones->save();
            }

        if($rol == 'Técnicos')
        {
            $Solicitudes = Solicitudes::where('tecnico',$Nombre)->get();
        }
        else
        {
            // Obtener todas las solicitudes
            //Solicitudes = Solicitudes::all();
            $Solicitudes = Solicitudes::with(['detalles_solicitud.manifiesto.devolucion'])->get();
        }

        /*Condiciones de los Folios para la vista de solicitud*/
        // Crear un array para almacenar el último folio encontrado para cada grupo
        $ultimoFolioPorGrupo = [];

        // Procesar cada solicitud
        foreach ($Solicitudes as $solicitud) 
        {
            $manifiesto = manifiesto::where('idSolicitud', $solicitud->idSolicitud)->first();
            $devolucion = devolucion::where('idSolicitud', $solicitud->idSolicitud)->first();  

            if ($manifiesto) 
            {
                
                $solicitud->folio = $manifiesto->Folio;
                $solicitud->pdf = $manifiesto->ScanPDF; // Guardar la ruta del PDF
                
                if($devolucion)
                {
                    //$devolucion->pdf = $devolucion->ScanPDF;
                    $solicitud->devolucion_pdf = $devolucion->ScanPDF;
                }else {
                $solicitud->devolucion_pdf = null;
                }
                //dump($devolucion);
                // Verificar si la expresión regular coincide
                if (preg_match('/^([A-Z]+-\d+)/', $solicitud->folio, $matches)) {
                    $folioBase = $matches[1];
                } else {
                    // Si no coincide, asignar un valor predeterminado o manejar el caso
                    $folioBase = '';
                }
        
                // Extraer la letra al final del folio si existe (después del número antes de la "/")
                if (preg_match('/([A-Z]?)\/\d{2}$/', $solicitud->folio, $matches)) {
                    $folioLetra = $matches[1] ?? ''; // Si no hay letra, asigna una cadena vacía
                } else {
                    $folioLetra = '';
                }
        
                // Verificar si este folio es el último en su grupo (mayor en orden lexicográfico)
                if (!isset($ultimoFolioPorGrupo[$folioBase]) || strcmp($folioLetra, $ultimoFolioPorGrupo[$folioBase]) > 0) {
                    $ultimoFolioPorGrupo[$folioBase] = $folioLetra;
                }
            } 
            else 
            {
                $solicitud->folio = "No Asignado";
                $solicitud->pdf = null; // No hay PDF disponible
                $solicitud->devolucion_pdf = null;
            }
        }
        

        // Marcar los folios que deben ocultar el botón
        foreach ($Solicitudes as $solicitud) 
        {
            // Intentar coincidir con el patrón del folio base
            if (preg_match('/^([A-Z]+-\d+)/', $solicitud->folio, $matches)) {
                $folioBase = $matches[1];  // Si coincide, asignar el valor
            } else {
                $folioBase = '';  // Si no coincide, asignar un valor predeterminado
            }
        
            // Intentar coincidir con el patrón de la letra del folio
            if (preg_match('/([A-Z]?)\/\d{2}$/', $solicitud->folio, $matches)) {
                $folioLetra = $matches[1] ?? '';  // Si coincide, asignar la letra o cadena vacía
            } else {
                $folioLetra = '';  // Si no coincide, asignar una cadena vacía
            }
        
            // Si este folio no es el último en su grupo, ocultar el botón
            $solicitud->hidePlus = isset($ultimoFolioPorGrupo[$folioBase]) && $folioLetra !== $ultimoFolioPorGrupo[$folioBase];
        }
            return view("Solicitud.index", compact('Solicitudes','Nombre','rol'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(manifiesto $manifiesto)
    {
        //
    }
}
