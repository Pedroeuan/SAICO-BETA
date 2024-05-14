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
    public function createEquipos()
    {
        return view('Equipos.create'); /*Muestra la vista de equipos*/
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeEquipos(Request $request) /*Metodo para Guardar/Agregar al BD */
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
        $generalConCertificados->Factura=$request->input('Factura');
        $generalConCertificados->Destino=$request->input('Destino');
        $generalConCertificados->Tipo=$request->input('Tipo');
        $generalConCertificados->Foto=$request->input('Foto');
        $generalConCertificados->Disponibilidad_Estado=$request->input('Disponibilidad_Estado');
        //dd($generalConCertificados);
        $generalConCertificados->save();

        /*Equipos*/
        //$generalConEquipos = general_eyc::with('equipos')->get();
        $generalConEquipos=new equipos;
        // Asociar el modelo relacionado con el modelo principal
        $generalConEquipos->idGeneral_EyC = $generalConCertificados->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
        $generalConEquipos->Proceso=$request->input('Proceso');
        $generalConEquipos->Metodo=$request->input('Metodo');
        $generalConEquipos->Tipo_E=$request->input('Tipo_E');
        //dd($generalConEquipos);
        $generalConEquipos->save();

        // Verificar si se ha enviado un archivo PDF
        //if ($request->hasFile('Factura') && $request->file('pdf_file')->isValid()) {
        if ($request->hasFile('Factura')&& $request->file('Factura')->isValid()) {

            // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
            $pdf = $request->file('Factura');
            $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());

            // Opcional: obtener la ruta del archivo guardado
            $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());

            // Opcional: devolver una respuesta con la ruta del archivo guardado
           // return "PDF subido correctamente. Ruta del archivo: " . public_path($pdfPath);
            //return response()->json(['pdf_path' => $pdfPath]);// Devolver la ruta del PDF
           return redirect()->route('inventario');

        } else {
            // Si no se ha enviado un archivo PDF, devolver un mensaje de error
            //return "Error: no se ha enviado un archivo PDF.";
        }

        //return redirect('/Equipos');
        return redirect()->route('inventario');
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
    public function editEquipos($id)
    {
            // Buscar el equipo y los datos generales en la base de datos
            $equipo = Equipos::findOrFail($id);
            $generalEyC = general_eyc::findOrFail($equipo->idGeneral_EyC);
            //dd( $generalEyC);
            // Pasar los datos del equipo y los datos generales a la vista de edición
            return view('Equipos.edit', compact('equipo', 'generalEyC', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEquipos(Request $request, $id)
{
    // Obtener el equipo existente
    $generalConCertificados = general_eyc::find($id);

    // Actualizar los datos del equipo
    $generalConCertificados->update([
        'Nombre_E_P_BP' => $request->input('Nombre_E_P_BP'),
        'No_economico' => $request->input('No_economico'),
        'Serie' => $request->input('Serie'),
        'Marca' => $request->input('Marca'),
        'Modelo' => $request->input('Modelo'),
        'Ubicacion' => $request->input('Ubicacion'),
        'Almacenamiento' => $request->input('Almacenamiento'),
        'Comentario' => $request->input('Comentario'),
        'SAT' => $request->input('SAT'),
        'BMPRO' => $request->input('BMPRO'),
        'Destino' => $request->input('Destino'),
        'Tipo' => $request->input('Tipo'),
        'Foto' => $request->input('Foto'),
        'Disponibilidad_Estado' => $request->input('Disponibilidad_Estado'),
    ]);

    // Actualizar los datos del equipo asociado
    $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
    $generalConEquipos->update([
        'Proceso' => $request->input('Proceso'),
        'Metodo' => $request->input('Metodo'),
        'Tipo_E' => $request->input('Tipo_E'),
    ]);

    // Actualizar la factura si se proporciona un nuevo archivo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        $pdf = $request->file('Factura');
        $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());

        // Actualizar la ruta de la factura en la base de datos
        $generalConCertificados->update(['Factura' => $pdf->getClientOriginalName()]);
    }

    // Redireccionar al usuario a donde desees
    return redirect()->route('inventario');
}

    /*
    public function updateEquipos(Request $request, $id)
    {
        $generalConCertificados = general_eyc::find($id);
    
        $generalConCertificados->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
        $generalConCertificados->No_economico = $request->input('No_economico');
        $generalConCertificados->Serie = $request->input('Serie');
        $generalConCertificados->Marca = $request->input('Marca');
        $generalConCertificados->Modelo = $request->input('Modelo');
        $generalConCertificados->Ubicacion = $request->input('Ubicacion');
        $generalConCertificados->Almacenamiento = $request->input('Almacenamiento');
        $generalConCertificados->Comentario = $request->input('Comentario');
        $generalConCertificados->SAT = $request->input('SAT');
        $generalConCertificados->BMPRO = $request->input('BMPRO');
        $generalConCertificados->Factura=$request->input('Factura');
        $generalConCertificados->Destino = $request->input('Destino');
        $generalConCertificados->Tipo = $request->input('Tipo');
        $generalConCertificados->Foto = $request->input('Foto');
        $generalConCertificados->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    
        $generalConCertificados->update();
    
        $generalConEquipos = equipos::find($id); // Corrección aquí
    
        $generalConEquipos->idGeneral_EyC = $generalConCertificados->idGeneral_EyC;
        $generalConEquipos->Proceso = $request->input('Proceso');
        $generalConEquipos->Metodo = $request->input('Metodo');
        $generalConEquipos->Tipo_E = $request->input('Tipo_E');
    
        $generalConEquipos->update();
    
        if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
            $pdf = $request->file('Factura');
            $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());
            $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName());
    
            //return response()->json(['pdf_path' => $pdfPath]);
        }
    
        return redirect()->route('inventario');
    }*/
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroyEquipos($id)
    {

        $generalConCertificados=general_eyc::find($id);
        $generalConCertificados->delete();

        $generalConEquipos=equipos::find($id);
        $generalConEquipos->delete();


        return redirect()->route('inventario');

    }
}
