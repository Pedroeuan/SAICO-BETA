<?php

namespace App\Http\Controllers\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\EquiposyConsumibles\detalles_kits;
use App\Models\EquiposyConsumibles\kits;
use App\Models\Manifiesto\manifiesto;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\devolucion;
use App\Models\EquiposyConsumibles\Historial_Almacen;
use App\Models\Clientes\clientes;

class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;
        $rol = Auth::user()->rol;

        if($rol == 'Técnicos')
        {
            $Solicitudes = Solicitudes::where('tecnico',$Nombre)->get();
        }
        else
        {
            // Obtener todas las solicitudes
            $Solicitudes = Solicitudes::all();
        }

        // Crear un array para almacenar el último folio encontrado para cada grupo
        $ultimoFolioPorGrupo = [];

        // Procesar cada solicitud
        foreach ($Solicitudes as $solicitud) 
        {
            $manifiesto = manifiesto::where('idSolicitud', $solicitud->idSolicitud)->first();
        
            if ($manifiesto) 
            {
                $solicitud->folio = $manifiesto->Folio;
        
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

        $SolicitudesDetallesManifiestosDevoluciones = Solicitudes::with(['detalles_solicitud.manifiesto.devolucion'])->get();

        return view("Solicitud.index", compact('Solicitudes','Nombre','rol', 'SolicitudesDetallesManifiestosDevoluciones'));
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

        return view('Solicitud.create', compact('general','generalConCertificados','kitsConDetalles'));
                                    /*vista*/    /*variable donde se guardan los datos*/
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeSolicitud(Request $request)
        {

            // Obtener el usuario autenticado
            $user = Auth::user();
            // Obtener el nombre del usuario
            $nombre = $user->name;

            $now = Carbon::now();
            $Solicitud = new Solicitudes();
            $tecnico = $nombre;
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
        // Obtener el usuario autenticado
        $user = Auth::user();
        // Obtener el nombre del usuario
        $Nombre = $user->name;

        $Solicitud = Solicitudes::findOrFail($id);
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();

        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();

        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->with('certificados')->with('almacen')->get();

        $Manifiestos = manifiesto::where('idSolicitud', $id)->first();
        $general = general_eyc::get();
        $clientes = clientes::all();

    
        foreach ($DetallesSolicitud as $detalle) {
            $almacen = Almacen::where('idGeneral_EyC', $detalle->idGeneral_EyC)->first();
            $detalle->stockDisponible = $almacen ? $almacen->Stock : 0; // Si no hay stock, se asume 0
        }

        if ($Solicitud->Estatus == 'PENDIENTE') {
            return view("Solicitud.aprobacion", compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados','Manifiestos'));
        }
    
        if ($Solicitud->Estatus == 'APROBADO') {
            if (!$Manifiestos) {
                return view('Manifiesto.Pre-Manifiesto', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados','clientes','Nombre'));
            }else
            {
                return view('Manifiesto.Pre-Manifiestoedit', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados', 'Manifiestos','clientes','Nombre'));
            }
        }
    
        if ($Solicitud->Estatus == 'MANIFIESTO') {
            if ($Manifiestos) {
            return view('Manifiesto.Pre-Manifiestoedit', compact('id', 'Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados', 'Manifiestos','clientes','Nombre'));
            }
        }
    }

    /*Botón de plus para agregar, más datos al manifiesto con el mismo folio */
    public function editplus($id)
    {
        $Solicitudplus = Solicitudes::findOrFail($id);
        
        // Crear la nueva solicitud duplicando la existente
        $nuevaSolicitud = $Solicitudplus->replicate();

        $nuevaSolicitud->Estatus = 'PENDIENTE';
        // Guardar la nueva solicitud en la base de datos
        $nuevaSolicitud->save();

        // Obtener el nuevo ID de la solicitud
        $nuevoIdSolicitud = $nuevaSolicitud->idSolicitud;

        // Obtener los detalles de la solicitud original
        $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();

        // Iterar sobre cada detalle y guardarlo con el nuevo ID de solicitud
        foreach ($DetallesSolicitud as $detalle) {
            $nuevoDetalle = $detalle->replicate();
            $nuevoDetalle->idSolicitud = $nuevoIdSolicitud;
            $nuevoDetalle->save();
        }

        // Obtener el manifiesto original basado en el idSolicitud
        $Manifiesto = manifiesto::where('idSolicitud', $id)->first();

        if ($Manifiesto) {
            // Crear el nuevo manifiesto duplicando el existente
            $nuevoManifiesto = $Manifiesto->replicate();

            // Actualizar el atributo del manifiesto con el nuevo ID de solicitud
            $nuevoManifiesto->idSolicitud = $nuevoIdSolicitud;
            $FolioPlus = $nuevoManifiesto->Folio;

            // Expresión regular para dividir la numeración y el año
            preg_match('/^(\D+-\d+)([A-Z]?)(\/\d{2})$/', $FolioPlus, $matches);

            // Partes del FolioPlus
            $prefix = $matches[1]; // Ejemplo: PROP-001
            $letter = $matches[2]; // Ejemplo: A, B, etc. (puede estar vacío)
            $suffix = $matches[3]; // Ejemplo: /24

            // Determinar la nueva letra
            if ($letter == '') {
                $newLetter = 'A';
            } else {
                $newLetter = chr(ord($letter) + 1); // Incrementar la letra
            }

            // Crear el nuevo FolioPlus
            $nuevoFolioPlus = $prefix . $newLetter . $suffix;

            // Asignar el nuevo FolioPlus al manifiesto
            $nuevoManifiesto->Folio = $nuevoFolioPlus;

            // Guardar el nuevo manifiesto
            $nuevoManifiesto->save();
        }

            return redirect()->route('solicitudplusvista.edit', ['id' => $nuevoIdSolicitud]);
    }
        

        /*Botón de plus para agregar, más datos al manifiesto con el mismo folio */
        public function editplusvista($id)
        {

            $Solicitud = Solicitudes::findOrFail($id);
    
            $DetallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
    
            $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
    
            // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
            $generalEyCIds = $DetallesSolicitud->pluck('idGeneral_EyC');
            // Obtener los registros de General_EyC relacionados
            $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->with('certificados')->with('almacen')->get();
    
            $Manifiestos = manifiesto::where('idSolicitud', $id)->first();
            $general = general_eyc::get();
            $clientes = clientes::all();
    
            if ($Solicitud->Estatus == 'PENDIENTE') {

                return view("Solicitud.aprobacionplus", compact('id','Solicitud', 'DetallesSolicitud', 'generalEyC', 'general', 'generalConCertificados','Manifiestos'));
            }
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
            // Busca el detalle de la solicitud en la tabla detalles_solicitud
            $detalle = detalles_solicitud::findOrFail($id); // Utiliza findOrFail para lanzar una excepción si no encuentra el modelo
            $idSolicitud = $detalle->idSolicitud; // idSolicitud
            
            
            // Busca la solicitud en la tabla Solicitudes
            $solicitud = Solicitudes::findOrFail($idSolicitud); // Utiliza findOrFail para lanzar una excepción si no encuentra el modelo
            $Fecha_Solicitud = $solicitud->Fecha; // Fecha de Solicitud
            $Tipo = ['SALIDA', 'EN RENTA'];
            $idGeneral_EyC = $detalle->idGeneral_EyC; // idGeneral_EyC

            $EyC = general_eyc::where('idGeneral_EyC', $idGeneral_EyC)->first();

            if ($EyC) {
                if($EyC->Disponibilidad_Estado == 'NO DISPONIBLE' )
                    {
                        $Estatus = 'DISPONIBLE';
                        // Actualizar el estado de la solicitud
                        $EyC->update([
                            'Disponibilidad_Estado' => $Estatus,
                        ]);
                    }
                }

            // Busca el historial en la tabla Historial_Almacen
            $Historial_Almacen = Historial_Almacen::where('idGeneral_EyC', $idGeneral_EyC)->where('Fecha', $Fecha_Solicitud)->where('Tipo', $Tipo)->first();
            //Busca el idSolicitud que esta ligado en Manifiesto y lo elimina
            $Manifiestos = manifiesto::where('idSolicitud', $idSolicitud)->first();

            // Si se encuentra un registro en el historial
            if ($Historial_Almacen) {
                $Almacen = almacen::where('idGeneral_EyC', $idGeneral_EyC)->first();
                $CantidadAlmacen = $Almacen->Stock;
                $CantidadHistorialAlmacen = $Historial_Almacen->Cantidad;
                $StockDevuelto = $CantidadAlmacen + $CantidadHistorialAlmacen;
                $Almacen->update([
                    'Stock' => $StockDevuelto,
                ]);

                $Historial_Almacen->delete(); // Elimina el historial
            }

            // Si se encuentra un registro en la tabla manifiesto
            if ($Manifiestos) {
                $Manifiestos->delete(); // Elimina el historial
            }

            $detalle->delete(); // Elimina el detalle de la solicitud

            return response()->json(['success' => 'Record deleted successfully!']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the record.'], 500);
        }
    }

    /*Eliminación de Toda la Solicitud-Boton eliminar del Index*/
    public function destroySolicitud($id)
    {
        //Obtener la solicitud y su fecha de salida
        $solicitud = Solicitudes::find($id);
        $fechaSalida = $solicitud->Fecha;

        $Tipo = ['SALIDA', 'EN RENTA']; 
        $disponibilidadEstado = 'DISPONIBLE';
    
        // Obtener los detalles relacionados con la solicitud
        $detallesSolicitud = detalles_solicitud::where('idSolicitud', $id)->get();
    
        foreach ($detallesSolicitud as $detalle) {
            $idGeneral_EyC = $detalle->idGeneral_EyC;
    
            // Obtener el historial relacionado
            $Historial_Almacen = Historial_Almacen::where('idGeneral_EyC', $idGeneral_EyC)
                                                    ->where('Fecha', $fechaSalida)
                                                    ->whereIn('Tipo', $Tipo)
                                                    ->get();
    
            foreach ($Historial_Almacen as $h_almacen) {
                // Obtener el registro en la tabla Almacen
                $almacen = almacen::where('idGeneral_EyC', $idGeneral_EyC)->first();
    
                if ($almacen) {
                    // Actualizar el Stock en Almacen
                    $nuevoStock = $almacen->Stock + $detalle->Cantidad;
                    $almacen->update(['Stock' => $nuevoStock]);
                }
    
                // Actualizar el estado de General_EyC a "DISPONIBLE"
                $generalEyC = general_eyc::find($idGeneral_EyC);
    
                if ($generalEyC) {
                    $generalEyC->update(['Disponibilidad_Estado' => $disponibilidadEstado]);
                }
    
                // Eliminar el historial
                $h_almacen->delete();
            }
        }
    
        // Eliminar el manifiesto relacionado con la solicitud
        manifiesto::where('idSolicitud', $id)->delete();
    
        // Eliminar los detalles de la solicitud
        detalles_solicitud::where('idSolicitud', $id)->delete();
    
        // Eliminar la solicitud
        Solicitudes::where('idSolicitud', $id)->delete();
    
        return redirect()->route('solicitud.index');
    }
    

    public function agregarDetallesSolicitud(Request $request)
    {
        // Obtén las variables de la solicitud
        $idFila = $request->input('idFila');
        $idSolicitud = $request->input('idSolicitud');
        $cantidad=1;
        $unidad='ESPERA DE DATO';

         // Verifica si el elemento ya existe en la tabla DetallesSolicitud
        $detalleExistente = detalles_solicitud::where('idSolicitud', $idSolicitud)
        ->where('idGeneral_EyC', $idFila)
        ->first();

        if ($detalleExistente) {
            return response()->json([
                'status' => 'error',
                'message' => 'El elemento ya está agregado.'
            ]);
        }

         // Verifica el stock en la tabla Almacen
            $almacen = Almacen::where('idGeneral_EyC', $idFila)->first();
            
            if ($almacen) {
                if ($almacen->Stock >= 1) {
                    // Procesa los datos según tus necesidades
                    $DetallesSolicitud = new detalles_solicitud();
                    $DetallesSolicitud->idSolicitud = $idSolicitud;
                    $DetallesSolicitud->idGeneral_EyC = $idFila;
                    $DetallesSolicitud->cantidad = $cantidad;
                    $DetallesSolicitud->Unidad = $unidad;
                    $DetallesSolicitud->save();

                    // Retornar una respuesta JSON con el idDetalles_Solicitud recién creado y el stock actual
                    return response()->json([
                        'status' => 'success',
                        'idDetalles_Solicitud' => $DetallesSolicitud->idDetalles_Solicitud,
                        'stock' => $almacen->Stock,
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'No hay suficiente stock disponible.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Elemento no encontrado en el almacén.'
                ]);
            }
    }

}
