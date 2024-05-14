<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolicitudEquiposController extends Controller
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

    /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
    // Obtener todos los equipos con sus certificados
        //$generalConCertificados = general_eyc::with('certificados')->get();
        //$generalConEquipos = general_eyc::with('equipos')->get();
        //dd($generalConEquipos);
        //return view('Equipos.index', compact('generalConCertificados'));
        /*vista*/    /*variable donde se guardan los datos*/
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createSolicitud()
    {
        return view('Equipos.SolicitudEyC'); /*Muestra la vista de equipos*/
    }

    public function store(Request $request)
    {
        //
    }

}
