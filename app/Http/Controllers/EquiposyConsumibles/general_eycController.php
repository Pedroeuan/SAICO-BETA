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
    public function storeEquipos(Request $request)
{
    /* Tabla General_EyC */
    $generalConCertificados = new general_eyc;
    if($request->input('Nombre_E_P_BP')==null)
    {
        $generalConCertificados->Nombre_E_P_BP = 'N/A';
    }else{
        $generalConCertificados->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $generalConCertificados->No_economico = 'N/A';
    }else{
        $generalConCertificados->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $generalConCertificados->Serie = 'N/A';
    }else{
        $generalConCertificados->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $generalConCertificados->Marca = 'N/A';
    }else{
        $generalConCertificados->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $generalConCertificados->Modelo = 'N/A';
    }else{
        $generalConCertificados->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $generalConCertificados->Ubicacion = 'N/A';
    }else{
        $generalConCertificados->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $generalConCertificados->Almacenamiento = 'N/A';
    }else{
        $generalConCertificados->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $generalConCertificados->Comentario = 'N/A';
    }else{
        $generalConCertificados->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $generalConCertificados->SAT = 'N/A';
    }else{
        $generalConCertificados->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $generalConCertificados->BMPRO = 'N/A';
    }else{
        $generalConCertificados->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Destino')==null)
    {
        $generalConCertificados->Destino = 'N/A';
    }else{
        $generalConCertificados->Destino = $request->input('Destino');
    } 
    if($request->input('Tipo')==null)
    {
        $generalConCertificados->Tipo = 'N/A';
    }else{
        $generalConCertificados->Tipo = $request->input('Tipo');
    } 
    if($request->input('Disponibilidad_Estado')==null)
    {
        $generalConCertificados->Disponibilidad_Estado = 'N/A';
    }else{
        $generalConCertificados->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    } 

    // Validar que se ha enviado el archivo de factura
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener el archivo PDF de la solicitud
        $pdf = $request->file('Factura');
        
        // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');

        // Opcional: guardar la ruta en la base de datos
        // $generalConCertificados->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        // $generalConCertificados->save();
        $generalConCertificados->Factura = $pdfPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Factura') == null)
    {
        $generalConCertificados->Factura = 'N/A';
    }
        // Si no se ha enviado un archivo PDF válido, devolver un mensaje de error
        //return redirect()->back()->withErrors(['Factura' => 'Error: no se ha enviado un archivo PDF válido.']);
    }

       // Validar que se ha enviado el archivo de imagen
       if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Validar que el archivo es una imagen
        $request->validate([
            'Foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Ajuste de validación
        ]);

        // Obtener el archivo de imagen de la solicitud
        $imagen = $request->file('Foto');
        
        // Guardar el archivo de imagen en la carpeta "public/Equipos/Fotos"
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');

        // Opcional: guardar la ruta en la base de datos
        // $generalConCertificados->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        // $generalConCertificados->save();
        $generalConCertificados->Foto = $imagenPath; // Guarda la ruta del archivo de foto
    } else {
        // Si no se ha enviado un archivo de imagen válido, devolver un mensaje de error
       //return redirect()->back()->withErrors(['Foto' => 'Error: no se ha enviado un archivo de imagen válido.']);
       if($request->input('Foto') == null)
       {
           $generalConCertificados->Foto = 'N/A';
       }
    }
    $generalConCertificados->save();

    /* Equipos */
    $generalConEquipos = new equipos;
    $generalConEquipos->idGeneral_EyC = $generalConCertificados->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Proceso')==null)
    {
        $generalConCertificados->Proceso = 'N/A';
    }else{
        $generalConEquipos->Proceso = $request->input('Proceso');
    } 
    if($request->input('Metodo')==null)
    {
        $generalConCertificados->Metodo = 'N/A';
    }else{
        $generalConEquipos->Metodo = $request->input('Metodo');
    } 
    if($request->input('Tipo_E')==null)
    {
        $generalConCertificados->Tipo_E = 'N/A';
    }else{
        $generalConEquipos->Tipo_E = $request->input('Tipo_E');
    } 
    
    $generalConEquipos->save();

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
        'Disponibilidad_Estado' => $request->input('Disponibilidad_Estado'),
    ]);

    // Actualizar los datos del equipo asociado
    $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
    $generalConEquipos->update([
        'Proceso' => $request->input('Proceso'),
        'Metodo' => $request->input('Metodo'),
        'Tipo_E' => $request->input('Tipo_E'),
    ]);

    // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalConCertificados->Factura;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        // Guardar el nuevo archivo PDF
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');

        // Actualizar la ruta de la factura en la base de datos
        $generalConCertificados->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $generalConCertificados->save();
    }

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalConCertificados->Foto;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        // Guardar el nuevo archivo de imagen
        $imagen = $request->file('Foto');
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');

        // Actualizar la ruta de la imagen en la base de datos
        $generalConCertificados->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        $generalConCertificados->save();
    }

    return redirect()->route('inventario');
}


    /**
     * Remove the specified resource from storage.
     */
    // EquiposController.php
    public function destroyEquipos($id)
    {
        $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();


         // Verifica si el registro existe antes de intentar eliminarlo
         if ($generalConEquipos) {
            // Eliminar el registro de la base de datos
            
        $generalConCertificados = general_eyc::find($id);
    
        // Verifica si el registro existe antes de intentar eliminarlo
        if ($generalConCertificados) {
            // Eliminar archivos asociados si existen
            if ($generalConCertificados->Factura && Storage::disk('public')->exists($generalConCertificados->Factura)) {
                Storage::disk('public')->delete($generalConCertificados->Factura);
            }
            if ($generalConCertificados->Foto && Storage::disk('public')->exists($generalConCertificados->Foto)) {
                Storage::disk('public')->delete($generalConCertificados->Foto);
            }
    
            // Eliminar el registro de la base de datos
            $generalConEquipos->delete();
            $generalConCertificados->delete();
            return redirect()->route('inventario');
        }
    }
    
        //return response()->json(['success' => true]);
        
    }
    
   /* public function destroyEquipos($id)
    {

        $generalConCertificados=general_eyc::find($id);
        $generalConCertificados->delete();

        $generalConEquipos=equipos::find($id);
        $generalConEquipos->delete();


        return redirect()->route('inventario');

    }*/
}
