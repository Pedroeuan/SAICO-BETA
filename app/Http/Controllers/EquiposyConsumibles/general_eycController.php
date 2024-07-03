<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;
use App\Models\EquiposyConsumibles\detalles_kits;
use App\Models\EquiposyConsumibles\kits;



class general_eycController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    /*GENERAL*/

    public function index()
    {
    // Obtener todos los equipos con sus certificados
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->get();
        //$generalConEquipos = general_eyc::with('Equipos')->get();
        return view('Equipos.index', compact('general','generalConCertificados'));
                    /*vista*/    /*variable donde se guardan los datos*/
    }

    public function createEquipos()
    {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
         //$DetallesKits = detalles_Kits::where('idKits', $id)->get()

        return view('Equipos.create', compact('general','generalConCertificados')); /*Muestra la vista de equipos*/
    }

    public function editEyC($id)
    {
        $generalEyC = general_eyc::findOrFail($id);
        /*devuelven los datos de la tabla a la que estan ligados */
        //$generalConEquipos = general_eyc::with('certificados')->where('idGeneral_EyC', $id)->first();
        $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
        $generalConCertificados = certificados::where('idGeneral_EyC', $id)->first();
        $generalConConsumibles = consumibles::where('idGeneral_EyC', $id)->first();
        $generalConAlmacen = almacen::where('idGeneral_EyC', $id)->first();
        $generalConAccesorios = accesorios::where('idGeneral_EyC', $id)->first();
        $generalConBlocks = block_y_probeta::where('idGeneral_EyC', $id)->first();
        $generalConHerramientas = herramientas::where('idGeneral_EyC', $id)->first();
        $CertificadosHistorialCertificados = historial_certificado::where('idGeneral_EyC', $id)->first();
        // Retornar la vista con los datos obtenidos
        return view('Equipos.edit', compact('id','generalEyC', 'generalConEquipos','generalConCertificados', 'generalConConsumibles','generalConAlmacen','generalConAccesorios','generalConBlocks','generalConHerramientas','CertificadosHistorialCertificados'));
    }

    public function BajaEyC($id)
    {
        // Obtener el equipo existente
    $generalEyC  = general_eyc::find($id);
    $Baja='FUERA DE SERVICIO/BAJA';
    // Actualizar los datos del equipo
    $generalEyC ->update([
        'Disponibilidad_Estado' => $Baja,
    ]);
    return redirect()->route('inventario');
    }

    public function indexKits()
    {
        // Obtener todos los Kits con sus detalles_kits
        $kitsConDetalles = Kits::with('detalles_kits')->get();

        return view('Equipos.indexKits', compact('kitsConDetalles'));
                    /*vista*/    /*variable donde se guardan los datos*/
    }


        public function GuardarKits(Request $request)
    {
        try {
            // Crear el kit principal
            $kit = new kits();
            $kit->Nombre = $request->input('Nombre') ?? 'ESPERA DE DATO';
            $kit->Prueba = $request->input('Prueba') ?? 'ESPERA DE DATO';
            $kit->save();

            // Obtener el id del kit recién creado
            $idKit = $kit->idKits;

            // Crear los detalles del kit si hay datos
            $kitData = $request->input('kitData');
            
            if (!empty($kitData)) {
                foreach ($kitData as $data) {
                    detalles_Kits::create([
                        'idGeneral_EyC' => $data['idGeneral_EyC'],
                        'idKits' => $idKit,
                        'Cantidad' => $data['cantidad'],
                        'Unidad' => $data['unidad'],
                    ]);
                }
            } else {
                \Log::warning('No se recibieron datos válidos en kitData');
            }

            return redirect()->route('index.Kits');
        } catch (\Exception $e) {
            \Log::error('Error en GuardarKits: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud.'], 500);
        }
    }


    public function destroyKits($id)
    {
        // Eliminar los detalles relacionados con el idSolicitud

        detalles_kits::where('idKits', $id)->delete();
        kits::where('idKits', $id)->delete();
            
        return redirect()->route('index.Kits');
    }


        public function editKits($id)
        {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
        
        $Kit = kits::findOrFail($id);
        $DetallesKits = detalles_kits::where('idKits', $id)->get();
        // Obtener los IDs de General_EyC relacionados con los DetallesSolicitud
        $generalEyCIds = $DetallesKits->pluck('idGeneral_EyC');
        // Obtener los registros de General_EyC relacionados
        $generalEyC = general_eyc::whereIn('idGeneral_EyC', $generalEyCIds)->get();
        
        return view("Equipos.editKits", compact('id', 'Kit', 'DetallesKits', 'generalEyC','general','generalConCertificados'));
        }

        /*Boton agregar */
        public function agregarDetallesKits(Request $request)
        {
            // Obtén las variables de la solicitud
            $idFila = $request->input('idFila');
            $idKits = $request->input('idKits');
            $cantidad=0;
            $unidad='ESPERA DE DATO';

            // Registra los valores en el archivo de log
            //Log::info('ID de Fila:', ['idFila' => $idFila]);
            //Log::info('ID de Kits:', ['idKits' => $idKits]);
            /*Los logs de Laravel se encuentran en el archivo storage/logs/laravel.log. Puedes revisar este archivo para ver los valores registrados.*/

            // Procesa los datos según tus necesidades
            // Aquí puedes agregar la lógica para agregar el detalle a la solicitud
            $DetallesKits = new detalles_kits();
            $DetallesKits->idKits = $idKits;
            $DetallesKits->idGeneral_EyC = $idFila;
            $DetallesKits->cantidad = $cantidad;
            $DetallesKits->Unidad = $unidad;
            $DetallesKits->save();

            // Retornar una respuesta JSON con el idDetalles_Kits recién creado
            return response()->json([
                'status' => 'success',
                'idDetalles_Kits' => $DetallesKits->idDetalles_Kits,
            ]);
        }
            /*Botón Eliminar */
            public function destroyDetallesKits($id)
        {
            try {
                $detalle = detalles_kits::findOrFail($id); // Utiliza findOrFail para lanzar una excepción si no encuentra el modelo
                $detalle->delete();
        
                return response()->json(['success' => 'Record deleted successfully!']);
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'Record not found.'], 404);
            } catch (\Exception $e) {
                return response()->json(['error' => 'An error occurred while deleting the record.'], 500);
            }
        }

        public function updateKits(Request $request, $id)
        {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'Nombre' => 'required|string|max:255',
                'Prueba' => 'required|string|max:255',
                'Cantidad.*' => 'required|integer|min:0',
                'Unidad.*' => 'required|string|max:255',
            ]);

            // Actualizar los datos del Kit
            $kit = kits::findOrFail($id);
            $kit->Nombre = $request->input('Nombre');
            $kit->Prueba = $request->input('Prueba');
            $kit->save();

            // Actualizar los detalles del Kit
            foreach ($request->input('Cantidad') as $detalleId => $cantidad) {
                $detalle = detalles_kits::findOrFail($detalleId);
                $detalle->Cantidad = $cantidad;
                $detalle->Unidad = $request->input("Unidad.$detalleId");
                $detalle->save();
            }

            // Redirigir o mostrar un mensaje de éxito
            return redirect()->route('index.Kits');
        }


    /**
     * Display the specified resource.
     */
    public function show(general_eyc $general_eyc)
    {
        //
    }





    
    }
