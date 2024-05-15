<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;

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

     /*APARTADO DE EQUIPOS */

      /**
     * Show the form for creating a new resource.
     */
    public function index()
    {
    // Obtener todos los equipos con sus certificados
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->get();
        return view('Equipos.index', compact('generalConCertificados','general'));
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
    /*$request->validate([
        'Nombre_E_P_BP' => 'required|string|max:255',
        'No_economico' => 'required|string|max:255',
        'Serie' => 'required|string|max:255',
        'Marca' => 'required|string|max:255',
        'Modelo' => 'required|string|max:255',
        'Ubicacion' => 'required|string|max:255',
        'Almacenamiento' => 'required|string|max:255',
        'Comentario' => 'nullable|string|max:255',
        'SAT' => 'nullable|string|max:255',
        'BMPRO' => 'nullable|string|max:255',
        'Destino' => 'nullable|string|max:255',
        'Tipo' => 'nullable|string|max:255',
        'Disponibilidad_Estado' => 'nullable|string|max:255',
        'Proceso' => 'nullable|string|max:255',
        'Metodo' => 'nullable|string|max:255',
        'Tipo_E' => 'nullable|string|max:255',
        'No_certificado' => 'nullable|string|max:255',
        'Fecha_calibracion' => 'nullable|date',
        'Prox_fecha_calibracion' => 'nullable|date|after_or_equal:Fecha_calibracion',
    ]);*/
    /* Tabla General_EyC */
    $general = new general_eyc;
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = 'N/A';
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = 'N/A';
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = 'N/A';
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = 'N/A';
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = 'N/A';
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = 'N/A';
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = 'N/A';
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = 'N/A';
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = 'N/A';
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = 'N/A';
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Destino')==null)
    {
        $general->Destino = 'N/A';
    }else{
        $general->Destino = $request->input('Destino');
    } 
    if($request->input('Tipo')==null)
    {
        $general->Tipo = 'N/A';
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
    if($request->input('Disponibilidad_Estado')==null)
    {
        $general->Disponibilidad_Estado = 'N/A';
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
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
        $general->Factura = $pdfPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Factura') == null)
    {
        $general->Factura = 'N/A';
    }
        // Si no se ha enviado un archivo PDF válido, devolver un mensaje de error
        //return redirect()->back()->withErrors(['Factura' => 'Error: no se ha enviado un archivo PDF válido.']);
    }
       if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');
        $FotoPath = $Foto->storeAs('Equipos/Fotos', $Foto->getClientOriginalName(), 'public');
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = 'N/A';
    }
    }
    $general->save();

    /* Equipos */
    $generalConEquipos = new equipos;
    $generalConEquipos->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Proceso')==null)
    {
        $generalConEquipos->Proceso = 'N/A';
    }else{
        $generalConEquipos->Proceso = $request->input('Proceso');
    } 
    if($request->input('Metodo')==null)
    {
        $generalConEquipos->Metodo = 'N/A';
    }else{
        $generalConEquipos->Metodo = $request->input('Metodo');
    } 
    if($request->input('Tipo_E')==null)
    {
        $generalConEquipos->Tipo_E = 'N/A';
    }else{
        $generalConEquipos->Tipo_E = $request->input('Tipo_E');
    }   
    $generalConEquipos->save();

    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = 'N/A';
    }else{
        $generalConCertificados->No_certificado = $request->input('No_certificado');
    }   
        $generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
        $generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');
        
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos/Certificados', $Certificado_Actual->getClientOriginalName(), 'public');

        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = 'N/A';
    }
    }
    $generalConCertificados->save();

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
            $generalConEquipos = Equipos::findOrFail($id);
            $generalEyC = general_eyc::findOrFail($generalConEquipos->idGeneral_EyC);
            $generalConCertificados = certificados::findOrFail($generalEyC->idGeneral_EyC);
            //dd( $generalEyC);
            // Pasar los datos del equipo y los datos generales a la vista de edición
            return view('Equipos.edit', compact('generalConCertificados','generalConEquipos', 'generalEyC', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateEquipos(Request $request, $id)
{
    
    // Obtener el equipo existente
    $generalEyC  = general_eyc::find($id);

    // Actualizar los datos del equipo
    $generalEyC ->update([
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
        $rutaAnterior = $general->Factura;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        // Guardar el nuevo archivo PDF
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');

        // Actualizar la ruta de la factura en la base de datos
        $general->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $general->save();
    }

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $general->Foto;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $imagen = $request->file('Foto');
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $general->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        $general->save();
    }

     // Actualizar los datos del certificado asociado
     $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
     $generalConCertificado->update([
         'No_certificado' => $request->input('No_certificado'),
         'Fecha_calibracion' => $request->input('Fecha_calibracion'),
         'Prox_fecha_calibracion' => $request->input('Prox_fecha_calibracion'),
     ]);

    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $imagen = $request->file('Certificado_Actual');
        $imagenPath = $imagen->storeAs('Equipos/Certificados', $imagen->getClientOriginalName(), 'public');
        $generalConCertificado->Certificado_Actual = 'Equipos/Certificados/' . $imagen->getClientOriginalName();
        $generalConCertificado->save();
    }

    return redirect()->route('inventario');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroyEquipos($id)
    {
        $generalConEquipos = equipos::find($id);
            //dd($generalConEquipos);
        // Verifica si el registro existe antes de intentar eliminarlo
        if ($generalConEquipos) {
            $idGeneral_EyC = $generalConEquipos->idGeneral_EyC;
    
            // Elimina el registro de la tabla 'equipos'
            $generalConEquipos->delete();
            
            $generalConCertificados = general_eyc::find($idGeneral_EyC);
            
            // Verifica si el registro existe antes de intentar eliminarlo
            if ($generalConCertificados) {
                // Elimina archivos asociados si existen
                if ($generalConCertificados->Factura && Storage::disk('public')->exists($generalConCertificados->Factura)) {
                    Storage::disk('public')->delete($generalConCertificados->Factura);
                }
                if ($generalConCertificados->Foto && Storage::disk('public')->exists($generalConCertificados->Foto)) {
                    Storage::disk('public')->delete($generalConCertificados->Foto);
                }
                
                // Elimina el registro de la tabla 'general_eyc'
                $generalConCertificados->delete();
            }
        }
    
        return redirect()->route('inventario');
    }


         /*APARTADO DE CONSUMIBLES*/
         public function storeConsumibles(Request $request)
{
    /* Tabla General_EyC */
    $general = new general_eyc;
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = 'N/A';
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = 'N/A';
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = 'N/A';
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = 'N/A';
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = 'N/A';
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = 'N/A';
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = 'N/A';
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = 'N/A';
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = 'N/A';
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = 'N/A';
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Destino')==null)
    {
        $general->Destino = 'N/A';
    }else{
        $general->Destino = $request->input('Destino');
    } 
    if($request->input('Tipo')==null)
    {
        $general->Tipo = 'N/A';
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
    if($request->input('Disponibilidad_Estado')==null)
    {
        $general->Disponibilidad_Estado = 'N/A';
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
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
        $general->Factura = $pdfPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Factura') == null)
    {
        $general->Factura = 'N/A';
    }
        // Si no se ha enviado un archivo PDF válido, devolver un mensaje de error
        //return redirect()->back()->withErrors(['Factura' => 'Error: no se ha enviado un archivo PDF válido.']);
    }
       if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');
        $FotoPath = $Foto->storeAs('Equipos/Fotos', $Foto->getClientOriginalName(), 'public');
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = 'N/A';
    }
    }
    $general->save();

    /* Equipos */
    $generalConEquipos = new equipos;
    $generalConEquipos->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Proceso')==null)
    {
        $generalConEquipos->Proceso = 'N/A';
    }else{
        $generalConEquipos->Proceso = $request->input('Proceso');
    } 
    if($request->input('Metodo')==null)
    {
        $generalConEquipos->Metodo = 'N/A';
    }else{
        $generalConEquipos->Metodo = $request->input('Metodo');
    } 
    if($request->input('Tipo_E')==null)
    {
        $generalConEquipos->Tipo_E = 'N/A';
    }else{
        $generalConEquipos->Tipo_E = $request->input('Tipo_E');
    }   
    $generalConEquipos->save();

    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = 'N/A';
    }else{
        $generalConCertificados->No_certificado = $request->input('No_certificado');
    }   
        $generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
        $generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');
        
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos/Certificados', $Certificado_Actual->getClientOriginalName(), 'public');

        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = 'N/A';
    }
    }
    $generalConCertificados->save();

    return redirect()->route('inventario');
}


}
