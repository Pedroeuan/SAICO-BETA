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

    /**
     * Display the specified resource.
     */
    public function show(general_eyc $general_eyc)
    {
        //
    }
    
    }
