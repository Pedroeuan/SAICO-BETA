<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\Historial_Almacen;
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
    // Obtener todos los equipos con sus certificados y almacen
        $general = general_eyc::get();
        $generalConCertificadosConAlmacen = general_eyc::with('certificados')->with('almacen')->get();

        return view('Equipos.index', compact('general','generalConCertificadosConAlmacen'));
                    /*vista*/    /*variable donde se guardan los datos*/
    }

    public function createEquipos()
    {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();

        return view('Equipos.create', compact('general','generalConCertificados')); /*Muestra la vista de equipos*/
    }

    public function editEyC($id)
    {
        $generalEyC = general_eyc::findOrFail($id);
        /*devuelven los datos de la tabla a la que estan ligados */
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

    public function verificarDuplicadoEquipos(Request $request)
    {
    // Limpia y normaliza el número económico
    $noEconomico = $request->input('No_economico');
    $serie = Str::lower($request->input('Serie'));

    // Eliminar prefijos como "No. Eco-", "No Eco-", "Eco-" y ceros a la izquierda
    $noEconomicoLimpio = preg_replace('/^(no\.?\s*eco[- ]?|eco[- ]?)/i', '', $noEconomico);// Elimina el prefijo
    $noEconomicoLimpio = ltrim($noEconomicoLimpio, '0'); // Elimina ceros iniciales

    // Verifica si el número económico ya existe (compara el número limpio)
    $existsNo_Economico = general_eyc::whereRaw("TRIM(LEADING '0' FROM REGEXP_REPLACE(LOWER(No_economico), '^(no\\.\\s*eco-?|eco-?)', '')) = ?", [$noEconomicoLimpio])
    ->where('Tipo', 'EQUIPOS')
    ->exists();

    $existsSerie = general_eyc::whereRaw("LOWER(Serie) = ?", [$serie])->exists();

        // Construir respuesta JSON según los duplicados encontrados
        $response = [
            'duplicado' => false,
            'mensaje' => ''
        ];

        if ($existsNo_Economico && $existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico y la Serie ya existen en la base de datos.';
        } elseif ($existsNo_Economico) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico ya existe en la base de datos.';
        } elseif ($existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'La Serie ya existe en la base de datos.';
        }

        return response()->json($response);
    }

    public function verificarDuplicadoAccesorios(Request $request)
    {
    // Limpia y normaliza el número económico
    $noEconomico = $request->input('No_economico');
    $serie = Str::lower($request->input('Serie'));
    
    // Eliminar prefijos como "No. AICO-", "No AICO-", "AICO-" y ceros a la izquierda
    $noEconomicoLimpio = preg_replace('/^(no\.?\s*aico[- ]?|aico[- ]?)/i', '', $noEconomico);
    $noEconomicoLimpio = ltrim($noEconomicoLimpio, '0'); // Elimina ceros iniciales

    $existsNo_Economico = general_eyc::whereRaw("TRIM(LEADING '0' FROM LOWER(REPLACE(REPLACE(No_economico, 'No. ', ''), 'AICO-', ''))) = ?", [$noEconomicoLimpio])
    ->where('Tipo', 'ACCESORIOS')
    ->exists();

    $existsSerie = general_eyc::whereRaw("LOWER(Serie) = ?", [$serie])->exists();

        // Construir respuesta JSON según los duplicados encontrados
        $response = [
            'duplicado' => false,
            'mensaje' => ''
        ];

        if ($existsNo_Economico && $existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico y la Serie ya existen en la base de datos.';
        } elseif ($existsNo_Economico) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico ya existe en la base de datos.';
        } elseif ($existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'La Serie ya existe en la base de datos.';
        }

        return response()->json($response);
    }

    public function verificarDuplicadoBlockyProbeta(Request $request)
    {
    // Limpia y normaliza el número económico
    $noEconomico = $request->input('No_economico');
    $serie = Str::lower($request->input('Serie'));
    
    // Eliminar prefijos como "No. AICO-", "No AICO-", "AICO-" y ceros a la izquierda
    $noEconomicoLimpio = preg_replace('/^(no\.?\s*eco[- ]?|eco[- ]?|eco-b[- ]?)/i', '', $noEconomico);
    $noEconomicoLimpio = ltrim($noEconomicoLimpio, '0'); // Elimina ceros iniciales

    $existsNo_Economico = general_eyc::whereRaw("TRIM(LEADING '0' FROM LOWER(REPLACE(REPLACE(REPLACE(No_economico, 'No. ', ''), 'ECO-', ''), 'ECO-B-', ''))) = ?", [$noEconomicoLimpio])
    ->where('Tipo', 'BLOCK Y PROBETA')
    ->exists();

    $existsSerie = general_eyc::whereRaw("LOWER(Serie) = ?", [$serie])->exists();

        // Construir respuesta JSON según los duplicados encontrados
        $response = [
            'duplicado' => false,
            'mensaje' => ''
        ];

        if ($existsNo_Economico && $existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico y la Serie ya existen en la base de datos.';
        } elseif ($existsNo_Economico) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico ya existe en la base de datos.';
        } elseif ($existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'La Serie ya existe en la base de datos.';
        }

        return response()->json($response);
    }

    public function verificarDuplicadoHerramientas(Request $request)
    {
    // Limpia y normaliza el número económico
    $noEconomico = $request->input('No_economico');
    $serie = Str::lower($request->input('Serie'));
    
    // Eliminar prefijos como "No. AICO-", "No AICO-", "AICO-" y ceros a la izquierda
    $noEconomicoLimpio = preg_replace('/^(no\.?\s*ad[- ]?|ad[- ]?)/i', '', $noEconomico);
    $noEconomicoLimpio = ltrim($noEconomicoLimpio, '0'); // Elimina ceros iniciales

    $existsNo_Economico = general_eyc::whereRaw("TRIM(LEADING '0' FROM LOWER(REPLACE(REPLACE(No_economico, 'No. ', ''), 'AD-', ''))) = ?", [$noEconomicoLimpio])
    ->where('Tipo', 'HERRAMIENTAS')
    ->exists();

    $existsSerie = general_eyc::whereRaw("LOWER(Serie) = ?", [$serie])->exists();

        // Construir respuesta JSON según los duplicados encontrados
        $response = [
            'duplicado' => false,
            'mensaje' => ''
        ];

        if ($existsNo_Economico && $existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico y la Serie ya existen en la base de datos.';
        } elseif ($existsNo_Economico) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'El Número Económico ya existe en la base de datos.';
        } elseif ($existsSerie) {
            $response['duplicado'] = true;
            $response['mensaje'] = 'La Serie ya existe en la base de datos.';
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(general_eyc $general_eyc)
    {
        //
    }
    
    }
