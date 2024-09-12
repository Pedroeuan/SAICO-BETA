<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Models\EquiposyConsumibles\devolucion;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Solicitudes\Solicitudes;
use App\Models\Solicitudes\detalles_solicitud;
use App\Models\Manifiesto\manifiesto;
use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\almacen;

class DevolucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(devolucion $devolucion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    /*public function editDevolucionListado(Request $request, $id)
    {
        $manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $folioBase = $manifiesto->Folio;

        // Extraer el prefijo (4 letras), número y año del Folio base
        preg_match('/^([A-Z]{4}-\d+)/', $folioBase, $matches);
        if (count($matches) > 0) {
            $folioPattern = $matches[1]; // Prefijo + número como "PROP-001"
            $anioPattern = substr($folioBase, -2); // Año como "24"

            // Usar expresión regular para buscar folios similares
            $foliosSimilares = manifiesto::where('Folio', 'REGEXP', '^' . $folioPattern . '[A-Z]?\/' . $anioPattern . '$')->get();

            // Obtener todos los idSolicitud de los folios similares
            $idsSolicitud = $foliosSimilares->pluck('idSolicitud');
            dd($idsSolicitud);
            // Obtener los Folios asociados a cada idSolicitud desde la tabla manifiesto
            $foliosManifiestos = manifiesto::whereIn('idSolicitud', $idsSolicitud)
                ->get(['idSolicitud', 'Folio'])
                ->keyBy('idSolicitud'); // Indexar por idSolicitud para fácil acceso
            
            // Buscar esos idSolicitud en la tabla detalles_solicitud y contar idGeneral_EyC
            $detallesSolicitud = detalles_solicitud::whereIn('idSolicitud', $idsSolicitud)
                ->select('idSolicitud', 'idGeneral_EyC', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('idGeneral_EyC', 'idSolicitud')
                ->get();

            // Obtener los idGeneral_EyC de los resultados obtenidos
            $idsGeneralEyC = $detallesSolicitud->pluck('idGeneral_EyC');

            // Buscar los idGeneral_EyC en la tabla General_EyC para obtener el Nombre
            //$generalesEyC = general_eyc::whereIn('idGeneral_EyC', $idsGeneralEyC)->get(['idGeneral_EyC', 'Nombre_E_P_BP']);
            $generalesEyC = general_eyc::whereIn('idGeneral_EyC', $idsGeneralEyC)->get(['idGeneral_EyC', 'Nombre_E_P_BP', 'Disponibilidad_Estado']);

            // Preparar un array asociativo para la vista con el Nombre, cantidad y Folio
            $datosManifiesto = [];
            foreach ($detallesSolicitud as $detalle) {
                $general = $generalesEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                $folio = $foliosManifiestos->get($detalle->idSolicitud)?->Folio; // Obtener Folio correspondiente desde $foliosManifiestos
                if ($general) {
                    $datosManifiesto[] = [
                        'idGeneral_EyC' => $detalle->idGeneral_EyC,
                        'Nombre' => $general->Nombre_E_P_BP,
                        'cantidad' => $detalle->cantidad, // Cantidad de ocurrencias
                        'Folio' => $folio, // Agregar el Folio desde manifiesto
                        'Disponibilidad_Estado'=> $general->Disponibilidad_Estado, // Agregar Disponibilidad_Estado
                    ];
                }
            }
        } else {
            $datosManifiesto = [];
        }

        // Pasar los datos a la vista
        return view('Equipos.devolucion', compact('datosManifiesto','id'));
    }*/
    
    public function editDevolucionListado(Request $request, $id)
    {
        $manifiesto = manifiesto::where('idSolicitud', $id)->first();
        $folioBase = $manifiesto->Folio;

        // Extraer el prefijo (4 letras), número y año del Folio base
        preg_match('/^([A-Z]{4}-\d+)/', $folioBase, $matches);
        if (count($matches) > 0) {
            $folioPattern = $matches[1]; // Prefijo + número como "PROP-001"
            $anioPattern = substr($folioBase, -2); // Año como "24"

            // Usar expresión regular para buscar folios similares
            $foliosSimilares = manifiesto::where('Folio', 'REGEXP', '^' . $folioPattern . '[A-Z]?\/' . $anioPattern . '$')->get();

            // Obtener todos los idSolicitud de los folios similares
            $idsSolicitud = $foliosSimilares->pluck('idSolicitud')->toArray(); // Convertir a array

            // Obtener los Folios asociados a cada idSolicitud desde la tabla manifiesto
            $foliosManifiestos = manifiesto::whereIn('idSolicitud', $idsSolicitud)
                ->get(['idSolicitud', 'Folio'])
                ->keyBy('idSolicitud'); // Indexar por idSolicitud para fácil acceso
            
            // Buscar esos idSolicitud en la tabla detalles_solicitud y contar idGeneral_EyC
            $detallesSolicitud = detalles_solicitud::whereIn('idSolicitud', $idsSolicitud)
                ->select('idSolicitud', 'idGeneral_EyC', DB::raw('COUNT(*) as cantidad'))
                ->groupBy('idGeneral_EyC', 'idSolicitud')
                ->get();

            // Obtener los idGeneral_EyC de los resultados obtenidos
            $idsGeneralEyC = $detallesSolicitud->pluck('idGeneral_EyC')->toArray(); // Convertir a array

            // Buscar los idGeneral_EyC en la tabla General_EyC para obtener el Nombre
            $generalesEyC = general_eyc::whereIn('idGeneral_EyC', $idsGeneralEyC)
                ->get(['idGeneral_EyC', 'Nombre_E_P_BP', 'Disponibilidad_Estado']);

            // Preparar un array asociativo para la vista con el Nombre, cantidad y Folio
            $datosManifiesto = [];
            foreach ($detallesSolicitud as $detalle) {
                $general = $generalesEyC->firstWhere('idGeneral_EyC', $detalle->idGeneral_EyC);
                $folio = $foliosManifiestos->get($detalle->idSolicitud)?->Folio; // Obtener Folio correspondiente desde $foliosManifiestos
                if ($general) {
                    $datosManifiesto[] = [
                        'idGeneral_EyC' => $detalle->idGeneral_EyC,
                        'Nombre' => $general->Nombre_E_P_BP,
                        'cantidad' => $detalle->cantidad, // Cantidad de ocurrencias
                        'Folio' => $folio, // Agregar el Folio desde manifiesto
                        'Disponibilidad_Estado'=> $general->Disponibilidad_Estado, // Agregar Disponibilidad_Estado
                    ];
                }
            }
        } else {
            $datosManifiesto = [];
            $idsSolicitud = [];
        }

        // Pasar los datos a la vista
        return view('Equipos.devolucion', compact('datosManifiesto', 'id', 'idsSolicitud'));
    }

    public function devolverItem(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'idGeneral_EyC' => 'required|integer',
            'cantidad' => 'required|integer|min:1',
        ]);

        $idGeneral_EyC = $request->input('idGeneral_EyC');
        $cantidad = $request->input('cantidad');

        // Buscar el registro en General_EyC
        $generalEyC = general_eyc::where('idGeneral_EyC', $idGeneral_EyC)->first();

        if (!$generalEyC) {
            return response()->json(['error' => 'Elemento no encontrado'], 404);
        }

        // Cambiar el estado a "DISPONIBLE"
        $generalEyC->Disponibilidad_Estado = 'DISPONIBLE';
        $generalEyC->save();

        // Actualizar la cantidad en Almacen
        $almacen = almacen::where('idGeneral_EyC', $idGeneral_EyC)->first();
        if ($almacen) {
            $almacen->Stock += $cantidad; // Devolver la cantidad al stock
            $almacen->save();
        }

        // Retornar respuesta exitosa
        return response()->json(['success' => 'Elemento devuelto exitosamente.']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, devolucion $devolucion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(devolucion $devolucion)
    {
        //
    }
}
