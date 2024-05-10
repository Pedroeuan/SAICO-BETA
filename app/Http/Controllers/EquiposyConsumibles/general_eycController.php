<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\equipos;

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
        //$generalConEquipos = general_eyc::with('equipos')->get();
        //dd($generalConEquipos);
        return view('Equipos.index', compact('generalConCertificados'));
                       /*vista*/    /*variable donde se guardan los datos*/
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Equipos.create'); /*Muestra la vista de equipos */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) /*Metodo para Guardar/Agregar al BD */
    {       
        /*Tabla General_EyC */
        /*instancia */
        $generalConCertificados=new general_eyc;
        /*variable->nombre de la columna en la BD=$request->input('nombre del input en el formulario') */
        $generalConCertificados->Nombre_E_P_BP=$request->input('Nombre_E_P_BP');
        $generalConCertificados->No_economico=$request->input('No_economico');
        $generalConCertificados->Serie=$request->input('Serie');
        $generalConCertificados->Marca=$request->input('Marca');
        $generalConCertificados->Modelo=$request->input('Modelo');
        $generalConCertificados->Ubicacion=$request->input('Ubicacion');
        $generalConCertificados->Almacenamiento=$request->input('Almacenamiento');
        $generalConCertificados->Comentario=$request->input('Comentario');
        $generalConCertificados->SAT=$request->input('SAT');
        $generalConCertificados->BMPRO=$request->input('BMPRO');
        //$generalConCertificados->Factura=$request->input('Factura');
        $generalConCertificados->Destino=$request->input('Destino');
        $generalConCertificados->Tipo=$request->input('Tipo');
        $generalConCertificados->Foto=$request->input('Foto');
        $generalConCertificados->Disponibilidad=$request->input('Disponibilidad');
        //dd($generalConCertificados);
        $generalConCertificados->save();

        /*Equipos*/
        //$generalConEquipos = general_eyc::with('equipos')->get();
        $generalConEquipos=new equipos;
        // Asociar el modelo relacionado con el modelo principal
        $generalConEquipos->idGeneral_EyC = $generalConCertificados->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
        $generalConEquipos->Proceso=$request->input('Proceso');
        $generalConEquipos->Metodo=$request->input('Metodo');
        //dd($generalConEquipos);
        $generalConEquipos->save();

        // Verificar si se ha enviado un archivo PDF
        if ($request->hasFile('Factura') && $request->file('pdf_file')->isValid()) {

            // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
            $pdf = $request->file('Factura');
            $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());

            // Opcional: obtener la ruta del archivo guardado
            $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());

            // Opcional: devolver una respuesta con la ruta del archivo guardado
           // return "PDF subido correctamente. Ruta del archivo: " . public_path($pdfPath);
           // Devolver la ruta del PDF
            return response()->json(['pdf_path' => $pdfPath]);

           return redirect()->route('Equipos');
        } else {
            // Si no se ha enviado un archivo PDF, devolver un mensaje de error
            //return "Error: no se ha enviado un archivo PDF.";
        }

        //return redirect('/Equipos');
        return redirect()->route('Equipos');
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
    public function edit($idgeneral_eyc)
    {
        $generalConCertificados=general_eyc::find($idgeneral_eyc);
        $generalConCertificados->Nombre_E_P_BP=$request->input('Nombre_E_P_BP');
        $generalConCertificados->No_economico=$request->input('No_economico');
        $generalConCertificados->Serie=$request->input('Serie');
        $generalConCertificados->Marca=$request->input('Marca');
        $generalConCertificados->Modelo=$request->input('Modelo');
        $generalConCertificados->Ubicacion=$request->input('Ubicacion');
        $generalConCertificados->Almacenamiento=$request->input('Almacenamiento');
        $generalConCertificados->Comentario=$request->input('Comentario');
        $generalConCertificados->SAT=$request->input('SAT');
        $generalConCertificados->BMPRO=$request->input('BMPRO');
        $generalConCertificados->Destino=$request->input('Destino');
        $generalConCertificados->Tipo=$request->input('Tipo');
        $generalConCertificados->Foto=$request->input('Foto');
        $generalConCertificados->Disponibilidad=$request->input('Disponibilidad');

        $generalConCertificados->update();

        $generalConEquipos=equipos::find($idgeneral_eyc);
        $generalConEquipos->idGeneral_EyC = $generalConCertificados->idGeneral_EyC;
        $generalConEquipos->Proceso=$request->input('Proceso');
        $generalConEquipos->Metodo=$request->input('Metodo');

        $generalConEquipos->update();

        if ($request->hasFile('Factura') && $request->file('pdf_file')->isValid()) {
            $pdf = $request->file('Factura');
            $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());
            $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());
            return response()->json(['pdf_path' => $pdfPath]);
           return redirect()->route('Equipos');
        } else {

        }
        return redirect()->route('Equipos');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$idgeneral_eyc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($general_eyc)
    {
        $generalConCertificados=general_eyc::find($idgeneral_eyc);
        $generalConCertificados->delete();

        $generalConEquipos=equipos::find($idgeneral_eyc);
        $generalConEquipos->delete();

        if ($request->hasFile('Factura') && $request->file('pdf_file')->isValid()) {
            $pdf = $request->file('Factura');
            $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());
            $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());
            return response()->json(['pdf_path' => $pdfPath]);
           return redirect()->route('Equipos');
        } else {

        }
        return redirect()->route('Equipos');
    }
}
