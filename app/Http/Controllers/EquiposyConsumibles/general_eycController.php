<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\Certificados;

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

      /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
    // Obtener todos los equipos con sus certificados
        $generalConCertificados = general_eyc::with('certificados')->get();
        
        return view('Equipos.index', compact('generalConCertificados'));
                       /*vista*/    /*variable donde se guardan los datos*/
    }
  

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('Equipos.create'); /*Muestra la vista de equipos */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) /*Metodo para Guardar/Agregar al BD */
    {       /*instancia */
        $generalConCertificados=new general_eyc;
        /*variable->nombredelacolumnaenlaBD=$request->input('nombredelinputenelformulario') */
        $generalConCertificados->Nombre_E_P_BP=$request->input('Nombre_EyC');
        $generalConCertificados->No_economico=$request->input('No_economico');
        $generalConCertificados->Serie=$request->input('Serie');
        $generalConCertificados->Marca=$request->input('Marca');
        $generalConCertificados->Modelo=$request->input('Modelo');
        $generalConCertificados->Ubicacion=$request->input('Ubicacion');
        $generalConCertificados->Almacenamiento=$request->input('Almacenamiento');
        $generalConCertificados->Comentario=$request->input('Comentario');
        $generalConCertificados->SAT=$request->input('SAT');
        $generalConCertificados->BMPRO=$request->input('BMPRO');
        $generalConCertificados->Factura=$request->input('Factura');
        $generalConCertificados->Destino=$request->input('Destino');
        $generalConCertificados->Tipo=$request->input('Tipo');
        //dd($generalConCertificados);
        $generalConCertificados->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(general_eyc $general_eyc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(general_eyc $general_eyc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, general_eyc $general_eyc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(general_eyc $general_eyc)
    {
        //
    }
}
