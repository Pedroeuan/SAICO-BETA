<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;


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
        //$generalConEquipos = general_eyc::with('Equipos')->get();
        return view('Equipos.index', compact('general','generalConCertificados'));
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
        $general->Nombre_E_P_BP = 'ESPERA DE DATO';
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = 'ESPERA DE DATO';
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = 'ESPERA DE DATO';
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = 'ESPERA DE DATO';
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = 'ESPERA DE DATO';
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = 'ESPERA DE DATO';
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = 'ESPERA DE DATO';
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = 'ESPERA DE DATO';
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = 'ESPERA DE DATO';
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = 'ESPERA DE DATO';
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Tipo')==null)
    {
        $general->Tipo = 'ESPERA DE DATO';
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
    if($request->input('Disponibilidad_Estado')==null)
    {
        $general->Disponibilidad_Estado = 'ESPERA DE DATO';
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
        $general->Factura = 'ESPERA DE DATO';
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
        $general->Foto = 'ESPERA DE DATO';
    }
    }
    $general->save();

    /* Equipos */
    $generalConEquipos = new equipos;
    $generalConEquipos->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Proceso')==null)
    {
        $generalConEquipos->Proceso = 'ESPERA DE DATO';
    }else{
        $generalConEquipos->Proceso = $request->input('Proceso');
    } 
    if($request->input('Metodo')==null)
    {
        $generalConEquipos->Metodo = 'ESPERA DE DATO';
    }else{
        $generalConEquipos->Metodo = $request->input('Metodo');
    } 
    if($request->input('Tipo_E')==null)
    {
        $generalConEquipos->Tipo_E = 'ESPERA DE DATO';
    }else{
        $generalConEquipos->Tipo_E = $request->input('Tipo_E');
    }   
    $generalConEquipos->save();

    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = 'ESPERA DE DATO';
    }else{
        $generalConCertificados->No_certificado = $request->input('No_certificado');
    }   

    if($request->input('Fecha_calibracion')==null)
    {
        $generalConCertificados->Fecha_calibracion = '01/01/0001';
    }else{
        $generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
    }  

    if($request->input('Prox_fecha_calibracion')==null)
    {
        $generalConCertificados->Prox_fecha_calibracion = '01/01/0001';
    }else{
        $generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    }  
        //$generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
        //$generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');
        
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos/Certificados', $Certificado_Actual->getClientOriginalName(), 'public');

        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = 'ESPERA DE DATO';
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

            // Retornar la vista con los datos obtenidos
            return view('Equipos.edit', compact('id','generalEyC', 'generalConEquipos','generalConCertificados', 'generalConConsumibles','generalConAlmacen','generalConAccesorios','generalConBlocks','generalConHerramientas'));
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
        $rutaAnterior = $generalEyC->Factura;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        // Guardar el nuevo archivo PDF
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');

        // Actualizar la ruta de la factura en la base de datos
        $generalEyC->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $generalEyC->save();
    }

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalEyC->Foto;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $imagen = $request->file('Foto');
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        $generalEyC->save();
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

/*Update Equipos---Historial */
public function updateEquiposH(Request $request, $id)
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

        // Guardar el nuevo archivo PDF
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');

        // Actualizar la ruta de la factura en la base de datos
        $generalEyC->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $generalEyC->save();
    

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalEyC->Foto;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $imagen = $request->file('Foto');
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        $generalEyC->save();
    }

     // Actualizar los datos del certificado asociado
     $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
     $generalConCertificado->Fecha_calibracion = $TempgeneralConCertificado;
     $generalConCertificado->update([
         'No_certificado' => $request->input('No_certificado'),
         'Fecha_calibracion' => $request->input('Fecha_calibracion'),
         'Prox_fecha_calibracion' => $request->input('Prox_fecha_calibracion'),
     ]);

     $generalConCertificadoHistorial = new historial_certificado;
     $generalConCertificadoHistorial->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
     $generalConCertificadoHistorial->idCertificados = $general->idGeneral_EyC;
 
     if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
         // Obtener la ruta del archivo anterior desde la base de datos
         $rutaAnterior = $generalEyC->Factura;
         $sindato='SIN DATO';

         if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior))
         {
            $generalConCertificadoHistorial->update([
                'Certificado_Caducado' => $rutaAnterior,
                'Tipo' => $sindato,
                'Ultima_Fecha_calibracion' => $TempgeneralConCertificado,
            ]);
         }
        }
 
         // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        /* if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
             Storage::disk('public')->delete($rutaAnterior);
         }*/

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
        $generalConCertificados = certificados::find($id);
        $generalConConsumible = consumibles::find($id);
        $generalConAlmacen = almacen::find($id);
        $generalConAcesorios = accesorios::find($id);
        $generalConBlockyprobeta = block_y_probeta::find($id);
        $generalConHerramientas = herramientas::find($id);
            // Verifica si el registro existe antes de intentar eliminarlo
            if ($generalConEquipos) {
            // Elimina el registro de la tabla 'equipos'
            $generalConEquipos->delete();
            }
            if ($generalConConsumible) {
                $generalConConsumible->delete();
            }
            if ($generalConAlmacen) {
                $generalConAlmacen->delete();
            }
            if ($generalConAcesorios) {
                $generalConAcesorios->delete();
            }
            if ($generalConBlockyprobeta) {
                $generalConBlockyprobeta->delete();
            }

            // Elimina archivos asociados si existen
        if ($generalConCertificados->Certificado_Actual) {
            $certificadosPaths = json_decode($generalConCertificados->Certificado_Actual, true);
            if (is_array($certificadosPaths)) {
                foreach ($certificadosPaths as $certificadoPath) {
                    if (Storage::disk('public')->exists($certificadoPath)) {
                        Storage::disk('public')->delete($certificadoPath);
                    }
                }
            }
        }
        $generalConCertificados->delete();
            //$generalConCertificados = certificados::find($id);
            // Verifica si el registro existe antes de intentar eliminarlo
            if ($generalConCertificados) {
                // Elimina archivos asociados si existen
                if ($generalConCertificados->Certificado_Actual && Storage::disk('public')->exists($generalConCertificados->Certificado_Actual)) {
                    Storage::disk('public')->delete($generalConCertificados->Certificado_Actual);
                }
                // Elimina el registro de la tabla 'general_eyc'
                $generalConCertificados->delete();
            }
            // Verifica si el registro existe antes de intentar eliminarlo
            if ($generalConHerramientas) {
                // Elimina archivos asociados si existen
                if ($generalConHerramientas->Garantia && Storage::disk('public')->exists($generalConHerramientas->Garantia)) {
                    Storage::disk('public')->delete($generalConHerramientas->Garantia);
                }
                // Elimina el registro de la tabla 'general_eyc'
                $generalConHerramientas->delete();
            }

            $general = general_eyc::find($id);
            // Verifica si el registro existe antes de intentar eliminarlo
            if ($general) {
                // Elimina archivos asociados si existen
                if ($general->Factura && Storage::disk('public')->exists($general->Factura)) {
                    Storage::disk('public')->delete($general->Factura);
                }
                if ($general->Foto && Storage::disk('public')->exists($general->Foto)) {
                    Storage::disk('public')->delete($general->Foto);
                }
                // Elimina el registro de la tabla 'general_eyc'
                $general->delete();
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
        $general->Nombre_E_P_BP = 'ESPERA DE DATO';
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
        $general->Serie = 'ESPERA DE DATO';
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = 'ESPERA DE DATO';
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = 'ESPERA DE DATO';
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = 'ESPERA DE DATO';
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = 'ESPERA DE DATO';
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = 'ESPERA DE DATO';
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = 'ESPERA DE DATO';
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = 'ESPERA DE DATO';
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Disponibilidad_Estado')==null)
    {
        $general->Disponibilidad_Estado = 'ESPERA DE DATO';
    }else{
        $general->Disponibilidad_Estado = $request->input('Proveedor');
    }
    if($request->input('Tipo')==null)
    {
        $general->Tipo = 'ESPERA DE DATO';
    }else{
        $general->Tipo = $request->input('Tipo');
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
        $general->Factura = 'ESPERA DE DATO';
    }
        // Si no se ha enviado un archivo PDF válido, devolver un mensaje de error
        //return redirect()->back()->withErrors(['Factura' => 'Error: no se ha enviado un archivo PDF válido.']);
    }
    /*Ficha Tecnica */
       if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');
        $FotoPath = $Foto->storeAs('Equipos/Fotos', $Foto->getClientOriginalName(), 'public');
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = 'ESPERA DE DATO';
    }
    }
    $general->save();

        /*Consumibles */
        $generalConConsumible = new consumibles;
        $generalConConsumible->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Proveedor')==null)
    {
        $generalConConsumible->Proveedor = 'ESPERA DE DATO';
    }else{
        $generalConConsumible->Proveedor = $request->input('Proveedor');
    } 
    if($request->input('Tipo_TI_CO')==null)
    {
        $generalConConsumible->Tipo = 'ESPERA DE DATO';
    }else{
        $generalConConsumible->Tipo = $request->input('Tipo_TI_CO');
    }  
    $generalConConsumible->save();
    
    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = 'ESPERA DE DATO';
    }else{
        $generalConCertificados->No_certificado = $request->input('No_certificado');
    }  
    
        $generalConCertificados->Fecha_calibracion = '01/01/0001';
        $generalConCertificados->Prox_fecha_calibracion = '01/01/0001';

        //$generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
        //$generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');
        
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos/Certificados', $Certificado_Actual->getClientOriginalName(), 'public');

        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = 'ESPERA DE DATO';
    }
    }
    $generalConCertificados->save();

     /*Almacen */
     $generalConAlmacen = new almacen;
     $generalConAlmacen->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
     if($request->input('Lote')==null)
     {
        $generalConAlmacen->Lote = 'ESPERA DE DATO';
     }else{
        $generalConAlmacen->Lote = $request->input('Lote');
     }

     if($request->input('Stock')==null)
     {
        $generalConAlmacen->Stock = 0;
     }else{
        $generalConAlmacen->Stock = $request->input('Stock');
     }

     $generalConAlmacen->save();

    return redirect()->route('inventario');
}

   /**
     * Update the specified resource in storage.
     */
    public function updateConsumibles(Request $request, $id)
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
        'Tipo' => $request->input('Tipo'),
        'Disponibilidad_Estado' => $request->input('Disponibilidad_Estado'),
    ]);

    // Actualizar los datos del equipo asociado
    /*$generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
    $generalConEquipos->update([
        'Proceso' => $request->input('Proceso'),
        'Metodo' => $request->input('Metodo'),
        'Tipo_E' => $request->input('Tipo_E'),
    ]);*/

    // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalEyC->Factura;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        // Guardar el nuevo archivo PDF
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');

        // Actualizar la ruta de la factura en la base de datos
        $generalEyC->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $generalEyC->save();
    }

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalEyC->Foto;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $imagen = $request->file('Foto');
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        $generalEyC->save();
    }

     // Actualizar los datos del certificado asociado
     $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
     $generalConCertificado->update([
         'No_certificado' => $request->input('No_certificado'),
         //'Fecha_calibracion' => $request->input('Fecha_calibracion'),
         //'Prox_fecha_calibracion' => $request->input('Prox_fecha_calibracion'),
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

     // Actualizar los datos del Almacen asociado
     $generalConAlmacen = almacen::where('idGeneral_EyC', $id)->first();
     $generalConAlmacen->update([
         'Lote' => $request->input('Lote'),
         'Stock' => $request->input('Stock'),
     ]);


    return redirect()->route('inventario');
}


 /*ACCESORIOS*/
 public function storeAccesorios(Request $request)
 {
     /* Tabla General_EyC */
     $general = new general_eyc;
     if($request->input('Nombre_E_P_BP')==null)
     {
         $general->Nombre_E_P_BP = 'ESPERA DE DATO';
     }else{
         $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
     }
     if($request->input('No_economico')==null)
     {
         $general->No_economico = 'ESPERA DE DATO';
     }else{
         $general->No_economico = $request->input('No_economico');
     }
     if($request->input('Serie')==null)
     {
         $general->Serie = 'ESPERA DE DATO';
     }else{
         $general->Serie = $request->input('Serie');
     }
     if($request->input('Marca')==null)
     {
         $general->Marca = 'ESPERA DE DATO';
     }else{
         $general->Marca = $request->input('Marca');
     }
     if($request->input('Modelo')==null)
     {
         $general->Modelo = 'ESPERA DE DATO';
     }else{
         $general->Modelo = $request->input('Modelo');
     }
     if($request->input('Ubicacion')==null)
     {
         $general->Ubicacion = 'ESPERA DE DATO';
     }else{
         $general->Ubicacion = $request->input('Ubicacion');
     }
     if($request->input('Almacenamiento')==null)
     {
         $general->Almacenamiento = 'ESPERA DE DATO';
     }else{
         $general->Almacenamiento = $request->input('Almacenamiento');
     }
     if($request->input('Comentario')==null)
     {
         $general->Comentario = 'ESPERA DE DATO';
     }else{
         $general->Comentario = $request->input('Comentario');
     }
     if($request->input('SAT')==null)
     {
         $general->SAT = 'ESPERA DE DATO';
     }else{
         $general->SAT = $request->input('SAT');
     }
     if($request->input('BMPRO')==null)
     {
         $general->BMPRO = 'ESPERA DE DATO';
     }else{
         $general->BMPRO = $request->input('BMPRO');
     }
     if($request->input('Disponibilidad_Estado')==null)
     {
         $general->Disponibilidad_Estado = 'ESPERA DE DATO';
     }else{
         $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
     }
     if($request->input('Tipo')==null)
     {
         $general->Tipo = 'ESPERA DE DATO';
     }else{
         $general->Tipo = $request->input('Tipo');
     } 
         $general->Foto = 'ESPERA DE DATO';
  
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
         $general->Factura = 'ESPERA DE DATO';
     }
    }
     $general->save();
 
     
     /* Certificados */
     $generalConCertificados = new certificados;
     $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
 
     $generalConCertificados->No_certificado = 'ESPERA DE DATO';
     $generalConCertificados->Fecha_calibracion = '01/01/0001';
     $generalConCertificados->Prox_fecha_calibracion = '01/01/0001';
 
         //$generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
         //$generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
     if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
         $Certificado_Actual = $request->file('Certificado_Actual');
         
         $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos/Certificados', $Certificado_Actual->getClientOriginalName(), 'public');
 
         $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
     } else {
         if($request->input('Certificado_Actual') == null)
     {
         $generalConCertificados->Certificado_Actual = 'ESPERA DE DATO';
     }
     }
     $generalConCertificados->save();

      /* Accesorios */
      $generalConAccesorios = new accesorios;
      $generalConAccesorios->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
      if($request->input('Proveedor')==null)
     {
         $generalConAccesorios->Proveedor = 'ESPERA DE DATO';
     }else{
         $generalConAccesorios->Proveedor = $request->input('Proveedor');
     } 
     $generalConAccesorios->save();

     return redirect()->route('inventario');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAccesorios(Request $request, $id)
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
        'Tipo' => $request->input('Tipo'),
        'Disponibilidad_Estado' => $request->input('Disponibilidad_Estado'),
    ]);

    // Actualizar los datos del equipo asociado
    /*$generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
    $generalConEquipos->update([
        'Proceso' => $request->input('Proceso'),
        'Metodo' => $request->input('Metodo'),
        'Tipo_E' => $request->input('Tipo_E'),
    ]);*/

    // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalEyC->Factura;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }

        // Guardar el nuevo archivo PDF
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');

        // Actualizar la ruta de la factura en la base de datos
        $generalEyC->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $generalEyC->save();
    }

     // Actualizar los datos del certificado asociado
     $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
     $generalConCertificado->update([
         'No_certificado' => $request->input('No_certificado'),
         //'Fecha_calibracion' => $request->input('Fecha_calibracion'),
         //'Prox_fecha_calibracion' => $request->input('Prox_fecha_calibracion'),
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

     // Actualizar los datos del Almacen asociado
     $generalConAccesorios = accesorios::where('idGeneral_EyC', $id)->first();
     $generalConAccesorios->update([
         'Proveedor' => $request->input('Proveedor'),
     ]);


    return redirect()->route('inventario');
}

/*BLOCKS*/
public function storeBlocks(Request $request)
{
    /* Tabla General_EyC */
    $general = new general_eyc;
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = 'ESPERA DE DATO';
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = 'ESPERA DE DATO';
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = 'ESPERA DE DATO';
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = 'ESPERA DE DATO';
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = 'ESPERA DE DATO';
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = 'ESPERA DE DATO';
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = 'ESPERA DE DATO';
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = 'ESPERA DE DATO';
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = 'ESPERA DE DATO';
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = 'ESPERA DE DATO';
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Disponibilidad_Estado')==null)
    {
        $general->Disponibilidad_Estado = 'ESPERA DE DATO';
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    }
    if($request->input('Tipo')==null)
    {
        $general->Tipo = 'ESPERA DE DATO';
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
        $general->Foto = 'ESPERA DE DATO';
 
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
        $general->Factura = 'ESPERA DE DATO';
    }
   }

    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');
        $FotoPath = $Foto->storeAs('Equipos/Fotos', $Foto->getClientOriginalName(), 'public');
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = 'ESPERA DE DATO';
    }
    }
    $general->save();

    
    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = 'ESPERA DE DATO';
    }else{
        $generalConCertificados->No_certificado = $request->input('No_certificado');
    }  
    if($request->input('Fecha_calibracion')==null)
    {
        $generalConCertificados->Fecha_calibracion = '01/01/0001';
    }else{
        $generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
    }  

    if($request->input('Prox_fecha_calibracion')==null)
    {
        $generalConCertificados->Prox_fecha_calibracion = '01/01/0001';
    }else{
        $generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    }  

        //$generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
        //$generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');
        
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos/Certificados', $Certificado_Actual->getClientOriginalName(), 'public');

        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = 'ESPERA DE DATO';
    }
    }
    $generalConCertificados->save();

     /* Block y Probeta */
     $generalConBlockyprobeta = new block_y_probeta;
     $generalConBlockyprobeta->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
     $generalConBlockyprobeta->save();

    return redirect()->route('inventario');
   }

      /**
     * Update the specified resource in storage.
     */
    public function updateBlocks(Request $request, $id)
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
        'Tipo' => $request->input('Tipo'),
        'Disponibilidad_Estado' => $request->input('Disponibilidad_Estado'),
    ]);

    // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        $rutaAnterior = $generalEyC->Factura;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');
        $generalEyC->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $generalEyC->save();
    }

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalEyC->Foto;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $imagen = $request->file('Foto');
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        $generalEyC->save();
    }

     // Actualizar los datos del certificado asociado
     $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
     $generalConCertificado->update([
         'No_certificado' => $request->input('No_certificado'),
         'Fecha_calibracion' => $request->input('Fecha_calibracion'),
         //'Prox_fecha_calibracion' => $request->input('Prox_fecha_calibracion'),
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

/*HERRAMIENTAS*/
public function storeHerramientas(Request $request)
{
    /* Tabla General_EyC */
    $general = new general_eyc;
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = 'ESPERA DE DATO';
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = 'ESPERA DE DATO';
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = 'ESPERA DE DATO';
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = 'ESPERA DE DATO';
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = 'ESPERA DE DATO';
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = 'ESPERA DE DATO';
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = 'ESPERA DE DATO';
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = 'ESPERA DE DATO';
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = 'ESPERA DE DATO';
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = 'ESPERA DE DATO';
    }else{
        $general->BMPRO = $request->input('BMPRO');
    } 
    if($request->input('Disponibilidad_Estado')==null)
    {
        $general->Disponibilidad_Estado = 'ESPERA DE DATO';
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    }
    if($request->input('Tipo')==null)
    {
        $general->Tipo = 'ESPERA DE DATO';
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
        $general->Foto = 'ESPERA DE DATO';
 
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
        $general->Factura = 'ESPERA DE DATO';
    }
   }

    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');
        $FotoPath = $Foto->storeAs('Equipos/Fotos', $Foto->getClientOriginalName(), 'public');
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = 'ESPERA DE DATO';
    }
    }
    $general->save();

    
    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = 'ESPERA DE DATO';
    }else{
        $generalConCertificados->No_certificado = $request->input('No_certificado');
    }  
    if($request->input('Fecha_calibracion')==null)
    {
        $generalConCertificados->Fecha_calibracion = '01/01/0001';
    }else{
        $generalConCertificados->Fecha_calibracion = $request->input('Fecha_calibracion');
    }  

    if($request->input('Prox_fecha_calibracion')==null)
    {
        $generalConCertificados->Prox_fecha_calibracion = '01/01/0001';
    }else{
        $generalConCertificados->Prox_fecha_calibracion = $request->input('Prox_fecha_calibracion');
    }  

    if ($request->hasFile('Certificado_Actual')) {
        $certificadosPaths = []; // Array to store paths of uploaded files
        
        foreach ($request->file('Certificado_Actual') as $certificado) {
            if ($certificado->isValid()) {
                $certificadoPath = $certificado->storeAs(
                    'Equipos/Certificados', 
                    $certificado->getClientOriginalName(), 
                    'public'
                );
                $certificadosPaths[] = $certificadoPath; // Add the path to the array
            }
        }
        // Convert the array to a JSON string to store in the database
        $generalConCertificados->Certificado_Actual = json_encode($certificadosPaths);
    } else {
        if ($request->input('Certificado_Actual') == null) {
            $generalConCertificados->Certificado_Actual = 'ESPERA DE DATO';
        }
    }
    
    $generalConCertificados->save();


   /* if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');
        
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos/Certificados', $Certificado_Actual->getClientOriginalName(), 'public');

        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = 'ESPERA DE DATO';
    }
    }

    $generalConCertificados->save();*/

    $generalConHerramientas = new herramientas;
    $generalConHerramientas->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    if ($request->hasFile('Garantia') && $request->file('Garantia')->isValid()) {
        $Garantia = $request->file('Garantia');
        $GarantiaPath = $Garantia->storeAs('Equipos/Garantia', $Garantia->getClientOriginalName(), 'public');
        $generalConHerramientas->Garantia = $GarantiaPath; // Guarda la ruta del archivo de garantía
    } else {
        if($request->input('Garantia') == null) {
            $generalConHerramientas->Garantia = 'ESPERA DE DATO';
        }
    }

    $generalConHerramientas->save();

    return redirect()->route('inventario');
   }

      /**
     * Update the specified resource in storage.
     */
    public function updateHerramientas(Request $request, $id)
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
        'Tipo' => $request->input('Tipo'),
        'Disponibilidad_Estado' => $request->input('Disponibilidad_Estado'),
    ]);

    // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        $rutaAnterior = $generalEyC->Factura;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $pdf = $request->file('Factura');
        $pdfPath = $pdf->storeAs('Equipos/Facturas', $pdf->getClientOriginalName(), 'public');
        $generalEyC->Factura = 'Equipos/Facturas/' . $pdf->getClientOriginalName();
        $generalEyC->save();
    }

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalEyC->Foto;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $imagen = $request->file('Foto');
        $imagenPath = $imagen->storeAs('Equipos/Fotos', $imagen->getClientOriginalName(), 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = 'Equipos/Fotos/' . $imagen->getClientOriginalName();
        $generalEyC->save();
    }

    $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();

    if ($request->hasFile('Certificado_Actual')) {
    // Eliminar archivos existentes si los hay
    $existingPaths = json_decode($generalConCertificado->Certificado_Actual, true);
    if (is_array($existingPaths)) {
        foreach ($existingPaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
    
    $certificadosPaths = []; // Array to store paths of uploaded files
    
    foreach ($request->file('Certificado_Actual') as $certificado) {
        if ($certificado->isValid()) {
            $certificadoPath = $certificado->storeAs(
                'Equipos/Certificados', 
                $certificado->getClientOriginalName(), 
                'public'
            );
            $certificadosPaths[] = $certificadoPath; // Add the path to the array
        }
    }

    // Convert the array to a JSON string to store in the database
    $generalConCertificado->Certificado_Actual = json_encode($certificadosPaths);
} else {
    if ($request->input('Certificado_Actual') == null) {
        $generalConCertificados->Certificado_Actual = 'ESPERA DE DATO';
    }
}

$generalConCertificados->save();

    
    $generalConCertificados->save();
    

     // Actualizar los datos del certificado asociado
  /*   $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();

    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $imagen = $request->file('Certificado_Actual');
        $imagenPath = $imagen->storeAs('Equipos/Certificados', $imagen->getClientOriginalName(), 'public');
        $generalConCertificado->Certificado_Actual = 'Equipos/Certificados/' . $imagen->getClientOriginalName();

        $generalConCertificado->save();
    }*/

    /*HERRAMIENTAS*/
    $generalConHerramientas = herramientas::where('idGeneral_EyC', $id)->first();

    if ($request->hasFile('Garantia') && $request->file('Garantia')->isValid()) {
        $rutaAnterior = $generalConHerramientas->Garantia;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $imagen = $request->file('Garantia');
        $imagenPath = $imagen->storeAs('Equipos/Garantia', $imagen->getClientOriginalName(), 'public');
        $generalConHerramientas->Garantia = 'Equipos/Garantia/' . $imagen->getClientOriginalName();

        $generalConHerramientas->save();
    }
    return redirect()->route('inventario');
}

    }
