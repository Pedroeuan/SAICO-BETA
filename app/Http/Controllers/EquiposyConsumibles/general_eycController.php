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

    public function indexKits()
    {
        // Obtener todos los Kits con sus detalles_kits
        $kitsConDetalles = Kits::with('detalles_kits')->get();

        return view('Equipos.indexKits', compact('kitsConDetalles'));
                    /*vista*/    /*variable donde se guardan los datos*/
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createEquipos()
    {
        $general = general_eyc::get();
        $generalConCertificados = general_eyc::with('certificados')->where('Disponibilidad_Estado', 'DISPONIBLE')->get();
        //$DetallesKits = detalles_Kits::where('idKits', $id)->get()

        return view('Equipos.create', compact('general','generalConCertificados')); /*Muestra la vista de equipos*/
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
            $CertificadosHistorialCertificados = historial_certificado::where('idGeneral_EyC', $id)->first();
            // Retornar la vista con los datos obtenidos
            return view('Equipos.edit', compact('id','generalEyC', 'generalConEquipos','generalConCertificados', 'generalConConsumibles','generalConAlmacen','generalConAccesorios','generalConBlocks','generalConHerramientas','CertificadosHistorialCertificados'));
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
     * Store a newly created resource in storage.
     */
    public function storeEquipos(Request $request)
{
        $request->validate([
            'Nombre_E_P_BP' => 'required|string|max:255',
            'No_economico' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'Modelo' => 'required|string|max:255',
            'Serie' => 'required|string|max:255',
        ]);

    /* Tabla General_EyC */
        $general = new general_eyc;
        $EsperaDato ='ESPERA DE DATO';
        if($request->input('Nombre_E_P_BP')==null)
        {
            $general->Nombre_E_P_BP = $EsperaDato;
        }else{
            $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
        }
        if($request->input('No_economico')==null)
        {
            $general->No_economico = $EsperaDato;
        }else{
            $general->No_economico = $request->input('No_economico');
        }
        if($request->input('Serie')==null)
        {
            $general->Serie = $EsperaDato;
        }else{
            $general->Serie = $request->input('Serie');
        }
        if($request->input('Marca')==null)
        {
            $general->Marca = $EsperaDato;
        }else{
            $general->Marca = $request->input('Marca');
        }
        if($request->input('Modelo')==null)
        {
            $general->Modelo = $EsperaDato;
        }else{
            $general->Modelo = $request->input('Modelo');
        }
        if($request->input('Ubicacion')==null)
        {
            $general->Ubicacion = $EsperaDato;
        }else{
            $general->Ubicacion = $request->input('Ubicacion');
        }
        if($request->input('Almacenamiento')==null)
        {
            $general->Almacenamiento = $EsperaDato;
        }else{
            $general->Almacenamiento = $request->input('Almacenamiento');
        }
        if($request->input('Comentario')==null)
        {
            $general->Comentario = $EsperaDato;
        }else{
            $general->Comentario = $request->input('Comentario');
        }
        if($request->input('SAT')==null)
        {
            $general->SAT = $EsperaDato;
        }else{
            $general->SAT = $request->input('SAT');
        }
        if($request->input('BMPRO')==null)
        {
            $general->BMPRO = $EsperaDato;
        }else{
            $general->BMPRO = $request->input('BMPRO');
        }
        if($request->input('Tipo')==null)
        {
            $general->Tipo = $EsperaDato;
        }else{
            $general->Tipo = $request->input('Tipo');
        } 
        if($request->input('Disponibilidad_Estado')=='Elige un Tipo')
        {
            $general->Disponibilidad_Estado = $EsperaDato;
        }else{
            $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
        } 
        $general->save();
    // Validar que se ha enviado el archivo de factura
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        $pdf = $request->file('Factura');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();
        // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Equipos', $newFileNameFactura, 'public');
        // Guardar la ruta en la base de datos
        $general->Factura = $pdfPath;
    } else {
        $general->Factura = $EsperaDato;
    }
    $general->save();
    // Validar que se ha enviado el archivo de foto
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $foto = $request->file('Foto');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFoto = $newNumber . '_' . $foto->getClientOriginalName();
        // Guardar el archivo en la carpeta "public/Equipos/Fotos"
        $fotoPath = $foto->storeAs('Equipos y Consumibles/Fotos/Equipos', $newFileNameFoto, 'public');
        $general->Foto = $fotoPath;
    } else {
        $general->Foto = $EsperaDato;
    }
    $general->save();
    // Equipos
    $generalConEquipos = new equipos;
    $generalConEquipos->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    $generalConEquipos->save();
    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = $EsperaDato;
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
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $certificado = $request->file('Certificado_Actual');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $certificado->getClientOriginalName();

        $certificadoPath = $certificado->storeAs('Equipos y Consumibles/Certificados/Equipos', $newFileNameCertificado, 'public');
        $generalConCertificados->Certificado_Actual = $certificadoPath;
    } else {
        $generalConCertificados->Certificado_Actual = $EsperaDato;
    }
    $generalConCertificados->save();

     /*Almacen */
    $generalConAlmacen = new almacen;
     $generalConAlmacen->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Lote')==null)
    {
        $generalConAlmacen->Lote = 'N/A';
    }
    if($request->input('Stock')==null)
    {
        $generalConAlmacen->Stock = 1;
    }
    $generalConAlmacen->save();

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
     * Update the specified resource in storage.
     */
    
/*Update Equipos*/
public function updateEquipos(Request $request, $id)
{
    
    // Obtener el equipo existente
    $generalEyC  = general_eyc::find($id);
    $EsperaDato ='ESPERA DE DATO';
    // Verificar el valor de Disponibilidad_Estado y asignar 'ESPERA DE DATO' si es 'Elige un Tipo'
    $disponibilidadEstado = $request->input('Disponibilidad_Estado');
    if ($disponibilidadEstado == 'Elige un Tipo') {
        $disponibilidadEstado = $EsperaDato;
    }

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
        'Disponibilidad_Estado' => $disponibilidadEstado,
    ]);

    // Actualizar los datos del equipo asociado
    $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
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
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();
        
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Equipos/', $newFileNameFactura, 'public');
        // Actualizar la ruta de la factura en la base de datos
        $generalEyC->Factura = $pdfPath;
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
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFoto = $newNumber . '_' .  $imagen->getClientOriginalName();  
        
        $imagenPath = $imagen->storeAs('Equipos y Consumibles/Fotos/Equipos/', $newFileNameFoto, 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = $imagenPath;
        $generalEyC->save();
    }

    $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
    if($request->input('Fecha_calibracion')==null)
    {
        $fechaCalibracion = '2001-01-01';
    }else{
        $fechaCalibracion = $request->input('Fecha_calibracion');
    }  
    if($request->input('Prox_fecha_calibracion')==null)
    {
        $proxFechaCalibracion = '2001-01-01';
    }else{
            $proxFechaCalibracion = $request->input('Prox_fecha_calibracion');
    }  
    $generalConCertificado->update([
        'No_certificado' => $request->input('No_certificado'),
        'Fecha_calibracion' => $fechaCalibracion,
        'Prox_fecha_calibracion' => $proxFechaCalibracion,
    ]);

    // Verificar si se ha proporcionado un nuevo certificado actual
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        // Obtener la ruta del certificado actual desde la base de datos
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        // Guardar el nuevo certificado en la carpeta original
        $certificado = $request->file('Certificado_Actual');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $certificado->getClientOriginalName();
        
        $certificadoPath = $certificado->storeAs('Equipos y Consumibles/Certificados/Equipos', $newFileNameCertificado, 'public');
        // Actualizar la ruta del certificado en la base de datos
        $generalConCertificado->Certificado_Actual = $certificadoPath;
        $generalConCertificado->save();

        // Si hay un certificado anterior, moverlo a la carpeta de certificados caducados
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            // Obtener el nombre del archivo
            $nombreArchivo = pathinfo($rutaAnterior, PATHINFO_BASENAME);
            // Construir la nueva ruta para mover el archivo
            $nuevaRuta = 'Equipos y Consumibles/Certificados Caducados/Equipos/' . $nombreArchivo;
            // Mover el archivo
            Storage::disk('public')->move($rutaAnterior, $nuevaRuta);
            /* Tabla Historial_certificados */
            $CertificadosHistorialCertificados = new historial_certificado;
            $CertificadosHistorialCertificados->idCertificados = $generalConCertificado->idCertificados;
            $CertificadosHistorialCertificados->idGeneral_EyC = $generalEyC->idGeneral_EyC;
            $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
            /*$Espera_Dato='ESPERA DE DATO';
            $CertificadosHistorialCertificados->Tipo = $Espera_Dato;*/
            $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
            $CertificadosHistorialCertificados->save();
            }
        }
        return redirect()->route('inventario');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroyEquipos($id)
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
    /*CONSUMIBLES*/
    public function storeConsumibles(Request $request)
    {
    /* Tabla General_EyC */
    $general = new general_eyc;
    $EsperaDato ='ESPERA DE DATO';
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = $EsperaDato;
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
        $general->Marca = $EsperaDato;
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = $EsperaDato;
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = $EsperaDato;
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = $EsperaDato;
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = $EsperaDato;
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = $EsperaDato;
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = $EsperaDato;
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Disponibilidad_Estado')=='Elige un Tipo')
    {
        $general->Disponibilidad_Estado = $EsperaDato;
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    } 
    if($request->input('Tipo')==null)
    {
        $general->Tipo = $EsperaDato;
    }else{
        $general->Tipo = $request->input('Tipo');
    }  
    // Validar que se ha enviado el archivo de factura
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener el archivo PDF de la solicitud
        $pdf = $request->file('Factura');
            // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Consumibles/'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
                })
                ->sort()
                ->last();
            $lastNumber = 0;
            if ($lastFile) {
                $lastNumber = (int)explode('_', basename($lastFile))[0];
            }
            // Incrementar el número consecutivo
            $newNumber = $lastNumber + 1;
            $newFileName = $newNumber . '_' . $pdf->getClientOriginalName();
        // Guardar el archivo PDF en la carpeta "Equipos y Consumibles/Facturas/Consumibles/ "
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Consumibles', $newFileName, 'public');
        // Opcional: guardar la ruta en la base de datos
        $general->Factura = $pdfPath;// Guarda la ruta del archivo de factura
    } else {
        if($request->input('Factura') == null)
    {
        $general->Factura = $EsperaDato;
    }
        // Si no se ha enviado un archivo PDF válido, devolver un mensaje de error
        //return redirect()->back()->withErrors(['Factura' => 'Error: no se ha enviado un archivo PDF válido.']);
    }
    /*Ficha Tecnica */
    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Consumibles'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileName = $newNumber . '_' . $Foto->getClientOriginalName();
        // Guardar el archivo PDF en la carpeta "Equipos y Consumibles/Facturas/Consumibles/"
        $FotoPath = $Foto->storeAs('Equipos y Consumibles/Fotos/Consumibles', $newFileName, 'public'); 
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = $EsperaDato;
    }
    }
    $general->save();
    /*Consumibles */
    $generalConConsumible = new consumibles;
    $generalConConsumible->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Proveedor')==null)
    {
        $generalConConsumible->Proveedor = $EsperaDato;
    }else{
        $generalConConsumible->Proveedor = $request->input('Proveedor');
    } 
    $generalConConsumible->save();
    
    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = $EsperaDato;
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

    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Consumibles'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileName = $newNumber . '_' . $Certificado_Actual->getClientOriginalName();
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos y Consumibles/Certificados/Consumibles', $newFileName, 'public');
        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = $EsperaDato;
    }
    }
    $generalConCertificados->save();

     /*Almacen */
    $generalConAlmacen = new almacen;
    $generalConAlmacen->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Lote')==null)
    {
        $generalConAlmacen->Lote = $EsperaDato;
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
     // Verificar el valor de Disponibilidad_Estado y asignar 'ESPERA DE DATO' si es 'Elige un Tipo'
    $EsperaDato ='ESPERA DE DATO';
    $disponibilidadEstado = $request->input('Disponibilidad_Estado');
    if ($disponibilidadEstado == 'Elige un Tipo') {
        $disponibilidadEstado = $EsperaDato;
    }
    // Actualizar los datos del equipo
    $generalEyC ->update([
        'Nombre_E_P_BP' => $request->input('Nombre_E_P_BP'),
        //'No_economico' => $request->input('No_economico'),
        'Serie' => $request->input('Serie'),
        'Marca' => $request->input('Marca'),
        'Modelo' => $request->input('Modelo'),
        'Ubicacion' => $request->input('Ubicacion'),
        'Almacenamiento' => $request->input('Almacenamiento'),
        'Comentario' => $request->input('Comentario'),
        'SAT' => $request->input('SAT'),
        'BMPRO' => $request->input('BMPRO'),
        //'Tipo' => $request->input('Tipo'),
        'Disponibilidad_Estado' => $disponibilidadEstado,
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
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Consumibles'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();
        // Actualizar la ruta de la factura en la base de datos
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Consumibles', $newFileNameFactura, 'public');
        $generalEyC->Factura = $pdfPath;
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

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Consumibles'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFoto = $newNumber . '_' .  $imagen->getClientOriginalName();  
        // Actualizar la ruta de la imagen en la base de datos
        $imagenPath = $imagen->storeAs('Equipos y Consumibles/Fotos/Consumibles',  $newFileNameFoto, 'public');
        $generalEyC->Foto = $imagenPath;
    }
    $generalEyC->save();

    // Actualizar los datos del certificado asociado
    $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
    if($request->input('Fecha_calibracion')==null)
    {
        $fechaCalibracion = '2001-01-01';
    }else{
        $fechaCalibracion = $request->input('Fecha_calibracion');
    }  
    if($request->input('Prox_fecha_calibracion')==null)
    {
        $proxFechaCalibracion = '2001-01-01';
    }else{
            $proxFechaCalibracion = $request->input('Prox_fecha_calibracion');
    }  
    $generalConCertificado->update([
        'No_certificado' => $request->input('No_certificado'),
        'Fecha_calibracion' => $fechaCalibracion,
        //'Prox_fecha_calibracion' => $proxFechaCalibracion,
    ]);

    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $Certificado_Actual = $request->file('Certificado_Actual');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Consumibles'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado_Actual = $newNumber . '_' .  $Certificado_Actual->getClientOriginalName();  
        
        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos y Consumibles/Certificados/Consumibles/', $newFileNameCertificado_Actual, 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalConCertificado->Certificado_Actual = $Certificado_ActualPath;
        $generalConCertificado->save();
    }
     // Verificar si se ha proporcionado un nuevo certificado actual
    /* if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        // Obtener la ruta del certificado actual desde la base de datos
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        // Guardar el nuevo certificado en la carpeta origina
        $certificado = $request->file('Certificado_Actual');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Consumibles'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $certificado->getClientOriginalName();
        
        $certificadoPath = $certificado->storeAs('Equipos y Consumibles/Certificados/Consumibles', $newFileNameCertificado, 'public');
        // Actualizar la ruta del certificado en la base de datos
        $generalConCertificado->Certificado_Actual = $certificadoPath;*/

        // Si hay un certificado anterior, moverlo a la carpeta de certificados caducados
        /* if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            // Obtener el nombre del archivo
            $nombreArchivo = pathinfo($rutaAnterior, PATHINFO_BASENAME);
            // Construir la nueva ruta para mover el archivo
            $nuevaRuta = 'Equipos y Consumibles/Certificados Caducados/Consumibles/' . $nombreArchivo;
            // Mover el archivo
            Storage::disk('public')->move($rutaAnterior, $nuevaRuta);
            /* Tabla Historial_certificados */
            /*$CertificadosHistorialCertificados = new historial_certificado;
            $CertificadosHistorialCertificados->idCertificados = $generalConCertificado->idCertificados;
            $CertificadosHistorialCertificados->idGeneral_EyC = $generalEyC->idGeneral_EyC;
            $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
            /*$Espera_Dato='ESPERA DE DATO';
            $CertificadosHistorialCertificados->Tipo = $Espera_Dato;*/
            /* $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
            $CertificadosHistorialCertificados->save();
            }
        }*/
    $generalConCertificado->save();

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
    $EsperaDato ='ESPERA DE DATO';
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = $EsperaDato;
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = $EsperaDato;
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = $EsperaDato;
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = $EsperaDato;
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = $EsperaDato;
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = $EsperaDato;
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = $EsperaDato;
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = $EsperaDato;
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = $EsperaDato;
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = $EsperaDato;
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Disponibilidad_Estado')=='Elige un Tipo')
    {
        $general->Disponibilidad_Estado = $EsperaDato;
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    } 
    if($request->input('Tipo')==null)
    {
        $general->Tipo = $EsperaDato;
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
        $general->Foto = $EsperaDato;

     // Validar que se ha enviado el archivo de factura
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener el archivo PDF de la solicitud
        $pdf = $request->file('Factura');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Accesorios'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();
         // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Accesorios', $newFileNameFactura, 'public');
         $general->Factura = $pdfPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Factura') == null)
    {
        $general->Factura = $EsperaDato;
    }
    }
    $general->save();
    
     /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = $EsperaDato;
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
        
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Accesorios'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $Certificado_Actual->getClientOriginalName();

        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos y Consumibles/Certificados/Accesorios', $newFileNameCertificado, 'public');
         $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = $EsperaDato;
    }
    }
    $generalConCertificados->save();

      /* Accesorios */
    $generalConAccesorios = new accesorios;
    $generalConAccesorios->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Proveedor')==null)
    {
        $generalConAccesorios->Proveedor = $EsperaDato;
    }else{
        $generalConAccesorios->Proveedor = $request->input('Proveedor');
    } 
    $generalConAccesorios->save();

    /*Almacen */
    $generalConAlmacen = new almacen;
    $generalConAlmacen->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Lote')==null)
    {
        $generalConAlmacen->Lote = 'N/A';
    }else{
        $generalConAlmacen->Lote = $request->input('Lote');
    }
    if($request->input('Stock')==null)
    {
        $generalConAlmacen->Stock = 1;
    }else{
        $generalConAlmacen->Stock = $request->input('Stock');
    }
    $generalConAlmacen->save();

    return redirect()->route('inventario');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAccesorios(Request $request, $id)
{
    // Obtener el equipo existente
    $generalEyC  = general_eyc::find($id);

    // Verificar el valor de Disponibilidad_Estado y asignar 'ESPERA DE DATO' si es 'Elige un Tipo'
    $EsperaDato ='ESPERA DE DATO';
    $disponibilidadEstado = $request->input('Disponibilidad_Estado');
    if ($disponibilidadEstado == 'Elige un Tipo') {
        $disponibilidadEstado = $EsperaDato;
    }

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
        'Disponibilidad_Estado' => $disponibilidadEstado,
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
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();

        // Actualizar la ruta de la factura en la base de datos
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Accesorios/', $newFileNameFactura, 'public');
        $generalEyC->Factura = $pdfPath; 
    }
    $generalEyC->save();
     // Actualizar los datos del certificado asociado
    $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
    if($request->input('Fecha_calibracion')==null)
    {
        $fechaCalibracion = '2001-01-01';
    }else{
        $fechaCalibracion = $request->input('Fecha_calibracion');
    }  
    if($request->input('Prox_fecha_calibracion')==null)
    {
        $proxFechaCalibracion = '2001-01-01';
    }else{
            $proxFechaCalibracion = $request->input('Prox_fecha_calibracion');
    }  
    $generalConCertificado->update([
        'No_certificado' => $request->input('No_certificado'),
        //'Fecha_calibracion' => $fechaCalibracion,
        //'Prox_fecha_calibracion' => $proxFechaCalibracion,
    ]);

    // Verificar si se ha proporcionado un nuevo certificado actual
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        // Obtener la ruta del certificado actual desde la base de datos
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        // Guardar el nuevo certificado en la carpeta origina

        $certificado = $request->file('Certificado_Actual');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Accesorios'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $certificado->getClientOriginalName();
        
        $certificadoPath = $certificado->storeAs('Equipos y Consumibles/Certificados/Accesorios/', $newFileNameCertificado, 'public');
        // Actualizar la ruta del certificado en la base de datos
        $generalConCertificado->Certificado_Actual = $certificadoPath;
        $generalConCertificado->save();

        // Si hay un certificado anterior, moverlo a la carpeta de certificados caducados
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            // Obtener el nombre del archivo
            $nombreArchivo = pathinfo($rutaAnterior, PATHINFO_BASENAME);
            // Construir la nueva ruta para mover el archivo
            $nuevaRuta = 'Equipos y Consumibles/Certificados Caducados/Accesorios/' . $nombreArchivo;
            // Mover el archivo
            Storage::disk('public')->move($rutaAnterior, $nuevaRuta);
            /* Tabla Historial_certificados */
            $CertificadosHistorialCertificados = new historial_certificado;
            $CertificadosHistorialCertificados->idCertificados = $generalConCertificado->idCertificados;
            $CertificadosHistorialCertificados->idGeneral_EyC = $generalEyC->idGeneral_EyC;
            $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
            /*$Espera_Dato='ESPERA DE DATO';
            $CertificadosHistorialCertificados->Tipo = $Espera_Dato;*/
            $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
            $CertificadosHistorialCertificados->save();
            }
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
    $EsperaDato ='ESPERA DE DATO';
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = $EsperaDato;
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = $EsperaDato;
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = $EsperaDato;
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = $EsperaDato;
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = $EsperaDato;
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = $EsperaDato;
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = $EsperaDato;
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = $EsperaDato;
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = $EsperaDato;
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = $EsperaDato;
    }else{
        $general->BMPRO = $request->input('BMPRO');
    }
    if($request->input('Disponibilidad_Estado')=='Elige un Tipo')
    {
        $general->Disponibilidad_Estado = $EsperaDato;
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    } 
    if($request->input('Tipo')==null)
    {
        $general->Tipo = $EsperaDato;
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
        $general->Foto = $EsperaDato;

    // Validar que se ha enviado el archivo de factura
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener el archivo PDF de la solicitud
        $pdf = $request->file('Factura');
        
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Block y Probeta'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();

        // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Block y Probeta', $newFileNameFactura, 'public');
        $general->Factura = $pdfPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Factura') == null)
    {
        $general->Factura = $EsperaDato;
    }
    }

    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Block y Probeta'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFoto = $newNumber . '_' . $Foto->getClientOriginalName();

        $FotoPath = $Foto->storeAs('Equipos y Consumibles/Fotos/Block y Probeta', $newFileNameFoto, 'public');
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = $EsperaDato;
    }
    }
    $general->save();
    
    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = $EsperaDato;
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

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Block y Probeta'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $Certificado_Actual->getClientOriginalName();

        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos y Consumibles/Certificados/Block y Probeta', $newFileNameCertificado, 'public');
        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = $EsperaDato;
    }
    }
    $generalConCertificados->save();

     /* Block y Probeta */
    $generalConBlockyprobeta = new block_y_probeta;
    $generalConBlockyprobeta->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    /*Factura*/
// Validar que se ha enviado el archivo de factura
    if ($request->hasFile('Plano') && $request->file('Plano')->isValid()) {
        $Plano = $request->file('Plano');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Planos/Block y Probeta'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNamePlano = $newNumber . '_' . $Plano->getClientOriginalName();

        // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
        $PlanoPath = $Plano->storeAs('Equipos y Consumibles/Planos/Block y Probeta', $newFileNamePlano, 'public');
        // Guardar la ruta en la base de datos
        $generalConBlockyprobeta->Plano = $PlanoPath;
    } else {
        $generalConBlockyprobeta->Plano = $EsperaDato;
    }

    $generalConBlockyprobeta->save();

    /*Almacen */
    $generalConAlmacen = new almacen;
    $generalConAlmacen->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Lote')==null)
    {
        $generalConAlmacen->Lote = 'N/A';
    }else{
        $generalConAlmacen->Lote = $request->input('Lote');
    }
    if($request->input('Stock')==null)
    {
        $generalConAlmacen->Stock = 1;
    }else{
        $generalConAlmacen->Stock = $request->input('Stock');
    }
    $generalConAlmacen->save();

    return redirect()->route('inventario');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateBlocks(Request $request, $id)
{
    // Obtener el equipo existente
    $generalEyC  = general_eyc::find($id);
    $EsperaDato ='ESPERA DE DATO';
    // Verificar el valor de Disponibilidad_Estado y asignar 'ESPERA DE DATO' si es 'Elige un Tipo'
    $disponibilidadEstado = $request->input('Disponibilidad_Estado');
    if ($disponibilidadEstado == 'Elige un Tipo') {
        $disponibilidadEstado = $EsperaDato;
    }

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
        'Disponibilidad_Estado' => $disponibilidadEstado,
    ]);

    // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        $rutaAnterior = $generalEyC->Factura;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $pdf = $request->file('Factura');
         // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Block y Probeta'))
        ->filter(function ($file) {
            return preg_match('/^\d+_/', basename($file));
        })
        ->sort()
        ->last();
    $lastNumber = 0;
    if ($lastFile) {
        $lastNumber = (int)explode('_', basename($lastFile))[0];
    }
     // Incrementar el número consecutivo
    $newNumber = $lastNumber + 1;
    $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();

        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Block y Probeta/', $newFileNameFactura, 'public');
        $generalEyC->Factura = $pdfPath;
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

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Block y Probeta'))
        ->filter(function ($file) {
            return preg_match('/^\d+_/', basename($file));
        })
        ->sort()
        ->last();
    $lastNumber = 0;
    if ($lastFile) {
        $lastNumber = (int)explode('_', basename($lastFile))[0];
    }
    // Incrementar el número consecutivo
    $newNumber = $lastNumber + 1;
    $newFileNameFoto = $newNumber . '_' .  $imagen->getClientOriginalName();

        $imagenPath = $imagen->storeAs('Equipos y Consumibles/Fotos/Block y Probeta/', $newFileNameFoto, 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = $imagenPath;
    }
    $generalEyC->save();

    /*Block y Probeta*/
    $generalConBlockyprobeta = block_y_probeta::where('idGeneral_EyC', $id)->first();
    // Eliminar el archivo de imagen anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Plano') && $request->file('Plano')->isValid()) {
        // Obtener la ruta del archivo anterior desde la base de datos
        $rutaAnterior = $generalConBlockyprobeta->Plano;

        // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        // Guardar el nuevo archivo de imagen
        $Plano = $request->file('Plano');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Planos/Block y Probeta'))
        ->filter(function ($file) {
            return preg_match('/^\d+_/', basename($file));
        })
        ->sort()
        ->last();
    $lastNumber = 0;
    if ($lastFile) {
        $lastNumber = (int)explode('_', basename($lastFile))[0];
    }
    // Incrementar el número consecutivo
    $newNumber = $lastNumber + 1;
    $newFileNamePlano = $newNumber . '_' .  $Plano->getClientOriginalName();

        $PlanoPath = $Plano->storeAs('Equipos y Consumibles/Planos/Block y Probeta/', $newFileNamePlano, 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalConBlockyprobeta->Plano = $PlanoPath;
    }
    $generalConBlockyprobeta->save();

    /*Certificados*/
    $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();
    if($request->input('Fecha_calibracion')==null)
    {
        $fechaCalibracion = '2001-01-01';
    }else{
        $fechaCalibracion = $request->input('Fecha_calibracion');
    }  
    if($request->input('Prox_fecha_calibracion')==null)
    {
        $proxFechaCalibracion = '2001-01-01';
    }else{
            $proxFechaCalibracion = $request->input('Prox_fecha_calibracion');
    }  
    $generalConCertificado->update([
        'No_certificado' => $request->input('No_certificado'),
        'Fecha_calibracion' => $fechaCalibracion,
        //'Prox_fecha_calibracion' => $proxFechaCalibracion,
    ]);

    // Verificar si se ha proporcionado un nuevo certificado actual
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        // Obtener la ruta del certificado actual desde la base de datos
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        // Guardar el nuevo certificado en la carpeta origina

        $certificado = $request->file('Certificado_Actual');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Block y Probeta'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $certificado->getClientOriginalName();
        
        $certificadoPath = $certificado->storeAs('Equipos y Consumibles/Fotos/Block y Probeta/', $newFileNameCertificado, 'public');
        // Actualizar la ruta del certificado en la base de datos
        $generalConCertificado->Certificado_Actual = $certificadoPath;
        $generalConCertificado->save();

        // Si hay un certificado anterior, moverlo a la carpeta de certificados caducados
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            // Obtener el nombre del archivo
            $nombreArchivo = pathinfo($rutaAnterior, PATHINFO_BASENAME);
            // Construir la nueva ruta para mover el archivo
            $nuevaRuta = 'Equipos y Consumibles/Certificados Caducados/Block y Probeta/' . $nombreArchivo;
            // Mover el archivo
            Storage::disk('public')->move($rutaAnterior, $nuevaRuta);
            /* Tabla Historial_certificados */
            $CertificadosHistorialCertificados = new historial_certificado;
            $CertificadosHistorialCertificados->idCertificados = $generalConCertificado->idCertificados;
            $CertificadosHistorialCertificados->idGeneral_EyC = $generalEyC->idGeneral_EyC;
            $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
            /*$Espera_Dato='ESPERA DE DATO';
            $CertificadosHistorialCertificados->Tipo = $Espera_Dato;*/
            $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
            $CertificadosHistorialCertificados->save();
            }
        }
    return redirect()->route('inventario');
}

/*HERRAMIENTAS*/
public function storeHerramientas(Request $request)
{
    /* Tabla General_EyC */
    $general = new general_eyc;
    $EsperaDato ='ESPERA DE DATO';
    if($request->input('Nombre_E_P_BP')==null)
    {
        $general->Nombre_E_P_BP = $EsperaDato;
    }else{
        $general->Nombre_E_P_BP = $request->input('Nombre_E_P_BP');
    }
    if($request->input('No_economico')==null)
    {
        $general->No_economico = $EsperaDato;
    }else{
        $general->No_economico = $request->input('No_economico');
    }
    if($request->input('Serie')==null)
    {
        $general->Serie = $EsperaDato;
    }else{
        $general->Serie = $request->input('Serie');
    }
    if($request->input('Marca')==null)
    {
        $general->Marca = $EsperaDato;
    }else{
        $general->Marca = $request->input('Marca');
    }
    if($request->input('Modelo')==null)
    {
        $general->Modelo = $EsperaDato;
    }else{
        $general->Modelo = $request->input('Modelo');
    }
    if($request->input('Ubicacion')==null)
    {
        $general->Ubicacion = $EsperaDato;
    }else{
        $general->Ubicacion = $request->input('Ubicacion');
    }
    if($request->input('Almacenamiento')==null)
    {
        $general->Almacenamiento = $EsperaDato;
    }else{
        $general->Almacenamiento = $request->input('Almacenamiento');
    }
    if($request->input('Comentario')==null)
    {
        $general->Comentario = $EsperaDato;
    }else{
        $general->Comentario = $request->input('Comentario');
    }
    if($request->input('SAT')==null)
    {
        $general->SAT = $EsperaDato;
    }else{
        $general->SAT = $request->input('SAT');
    }
    if($request->input('BMPRO')==null)
    {
        $general->BMPRO = $EsperaDato;
    }else{
        $general->BMPRO = $request->input('BMPRO');
    } 
    if($request->input('Disponibilidad_Estado')=='Elige un Tipo')
    {
        $general->Disponibilidad_Estado = $EsperaDato;
    }else{
        $general->Disponibilidad_Estado = $request->input('Disponibilidad_Estado');
    } 
    if($request->input('Tipo')==null)
    {
        $general->Tipo = $EsperaDato;
    }else{
        $general->Tipo = $request->input('Tipo');
    } 
        $general->Foto = $EsperaDato;

    // Validar que se ha enviado el archivo de factura
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        // Obtener el archivo PDF de la solicitud
        $pdf = $request->file('Factura');
        
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Herramientas'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();

        // Guardar el archivo PDF en la carpeta "public/Equipos/Facturas"
        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Herramientas', $newFileNameFactura, 'public');
        $general->Factura = $pdfPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Factura') == null)
    {
        $general->Factura = $EsperaDato;
    }
    }

    if ($request->hasFile('Foto') && $request->file('Foto')->isValid()) {
        $Foto = $request->file('Foto');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Herramientas'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFoto = $newNumber . '_' . $Foto->getClientOriginalName();

        $FotoPath = $Foto->storeAs('Equipos y Consumibles/Fotos/Herramientas', $newFileNameFoto, 'public');
        $general->Foto = $FotoPath;
    } else {
        if($request->input('Foto') == null)
    {
        $general->Foto = $EsperaDato;
    }
    }
    $general->save();

    
    /* Certificados */
    $generalConCertificados = new certificados;
    $generalConCertificados->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    if($request->input('No_certificado')==null)
    {
        $generalConCertificados->No_certificado = $EsperaDato;
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

    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        $Certificado_Actual = $request->file('Certificado_Actual');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Herramientas'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $Certificado_Actual->getClientOriginalName();

        $Certificado_ActualPath = $Certificado_Actual->storeAs('Equipos y Consumibles/Certificados/Herramientas', $newFileNameCertificado, 'public');
        $generalConCertificados->Certificado_Actual = $Certificado_ActualPath; // Guarda la ruta del archivo de factura
    } else {
        if($request->input('Certificado_Actual') == null)
    {
        $generalConCertificados->Certificado_Actual = $EsperaDato;
    }
    }
    $generalConCertificados->save();

    $generalConHerramientas = new herramientas;
    $generalConHerramientas->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación

    if ($request->hasFile('Garantia') && $request->file('Garantia')->isValid()) {
        $Garantia = $request->file('Garantia');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Garantia/Herramienta'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $Garantia->getClientOriginalName();

        $GarantiaPath = $Garantia->storeAs('Equipos y Consumibles/Garantia/Herramientas', $newFileNameFactura, 'public');
        $generalConHerramientas->Garantia = $GarantiaPath; // Guarda la ruta del archivo de garantía
    } else {
        if($request->input('Garantia') == null) {
            $generalConHerramientas->Garantia = $EsperaDato;
        }
    }

    if ($request->hasFile('Plano') && $request->file('Plano')->isValid()) {
        $Plano = $request->file('Plano');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Equipos'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $Plano->getClientOriginalName();

        $PlanoPath = $Plano->storeAs('Equipos y Consumibles/Planos/Herramientas', $newFileNameFactura, 'public');
        $generalConHerramientas->Plano = $PlanoPath; // Guarda la ruta del archivo de garantía
    } else {
        if($request->input('Plano') == null) {
            $generalConHerramientas->Plano = $EsperaDato;
        }
    }
    $generalConHerramientas->save();

    /*Almacen */
    $generalConAlmacen = new almacen;
    $generalConAlmacen->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
    if($request->input('Lote')==null)
    {
        $generalConAlmacen->Lote = 'N/A';
    }else{
        $generalConAlmacen->Lote = $request->input('Lote');
    }
    if($request->input('Stock')==null)
    {
        $generalConAlmacen->Stock = 1;
    }else{
        $generalConAlmacen->Stock = $request->input('Stock');
    }
    $generalConAlmacen->save();

    return redirect()->route('inventario');
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateHerramientas(Request $request, $id)
{
    // Obtener el equipo existente
    $generalEyC  = general_eyc::find($id);
     // Verificar el valor de Disponibilidad_Estado y asignar 'ESPERA DE DATO' si es 'Elige un Tipo'
    $EsperaDato ='ESPERA DE DATO';
    $disponibilidadEstado = $request->input('Disponibilidad_Estado');
    if ($disponibilidadEstado == 'Elige un Tipo') {
        $disponibilidadEstado = $EsperaDato;
    }
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
        'Disponibilidad_Estado' => $disponibilidadEstado,
    ]);

    // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
    if ($request->hasFile('Factura') && $request->file('Factura')->isValid()) {
        $rutaAnterior = $generalEyC->Factura;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $pdf = $request->file('Factura');
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Facturas/Herramientas'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $pdf->getClientOriginalName();

        $pdfPath = $pdf->storeAs('Equipos y Consumibles/Facturas/Herramientas/', $newFileNameFactura, 'public');
        $generalEyC->Factura = $pdfPath;
        
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
        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Fotos/Herramientas'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFoto = $newNumber . '_' .  $imagen->getClientOriginalName(); 
        $imagenPath = $imagen->storeAs('Equipos y Consumibles/Fotos/Herramientas/', $newFileNameFoto, 'public');
        // Actualizar la ruta de la imagen en la base de datos
        $generalEyC->Foto = $imagenPath;
    }

    $generalEyC->save();
     // Actualizar los datos del certificado asociado
    $generalConCertificado = certificados::where('idGeneral_EyC', $id)->first();

    // Verificar si se ha proporcionado un nuevo certificado actual
    if ($request->hasFile('Certificado_Actual') && $request->file('Certificado_Actual')->isValid()) {
        // Obtener la ruta del certificado actual desde la base de datos
        $rutaAnterior = $generalConCertificado->Certificado_Actual;
        // Guardar el nuevo certificado en la carpeta origina

        $certificado = $request->file('Certificado_Actual');

        // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Herramientas'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
            })
            ->sort()
            ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameCertificado = $newNumber . '_' . $certificado->getClientOriginalName();
        
        $certificadoPath = $certificado->storeAs('Equipos y Consumibles/Certificados/Herramientas', $newFileNameCertificado, 'public');
        // Actualizar la ruta del certificado en la base de datos
        $generalConCertificado->Certificado_Actual = $certificadoPath;
        $generalConCertificado->save();

        // Si hay un certificado anterior, moverlo a la carpeta de certificados caducados
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            // Obtener el nombre del archivo
            $nombreArchivo = pathinfo($rutaAnterior, PATHINFO_BASENAME);
            // Construir la nueva ruta para mover el archivo
            $nuevaRuta = 'Equipos y Consumibles/Certificados Caducados/Herramientas/' . $nombreArchivo;
            // Mover el archivo
            Storage::disk('public')->move($rutaAnterior, $nuevaRuta);
            /* Tabla Historial_certificados */
            $CertificadosHistorialCertificados = new historial_certificado;
            $CertificadosHistorialCertificados->idCertificados = $generalConCertificado->idCertificados;
            $CertificadosHistorialCertificados->idGeneral_EyC = $generalEyC->idGeneral_EyC;
            $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
            /*$Espera_Dato='ESPERA DE DATO';
            $CertificadosHistorialCertificados->Tipo = $Espera_Dato;*/
            $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
            $CertificadosHistorialCertificados->save();
            }
        }

    /*HERRAMIENTAS*/
    $generalConHerramientas = herramientas::where('idGeneral_EyC', $id)->first();

    if ($request->hasFile('Garantia') && $request->file('Garantia')->isValid()) {
        $rutaAnterior = $generalConHerramientas->Garantia;
        if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
            Storage::disk('public')->delete($rutaAnterior);
        }
        $Garantia = $request->file('Garantia');
         // Obtener el último número consecutivo
        $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Certificados/Herramientas'))
        ->filter(function ($file) {
            return preg_match('/^\d+_/', basename($file));
        })
        ->sort()
        ->last();
        $lastNumber = 0;
        if ($lastFile) {
            $lastNumber = (int)explode('_', basename($lastFile))[0];
        }
        // Incrementar el número consecutivo
        $newNumber = $lastNumber + 1;
        $newFileNameFactura = $newNumber . '_' . $Garantia->getClientOriginalName();
        $imagenPath = $Garantia->storeAs('Equipos y Consumibles/Certificados/Herramientas/', $newFileNameFactura, 'public');
        $generalConHerramientas->Garantia = $imagenPath;
        $generalConHerramientas->save();
        }

        if ($request->hasFile('Plano') && $request->file('Plano')->isValid()) {
            $rutaAnteriorP = $generalConHerramientas->Plano;
            if ($rutaAnteriorP && Storage::disk('public')->exists($rutaAnteriorP)) {
                Storage::disk('public')->delete($rutaAnteriorP);
            }
            $Plano = $request->file('Plano');
             // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Equipos y Consumibles/Planos/Herramientas'))
            ->filter(function ($file) {
                return preg_match('/^\d+_/', basename($file));
                })
                ->sort()
                ->last();
            $lastNumber = 0;
            if ($lastFile) {
                $lastNumber = (int)explode('_', basename($lastFile))[0];
            }
            // Incrementar el número consecutivo
            $newNumber = $lastNumber + 1;
            $newFileNameFactura = $newNumber . '_' . $Plano->getClientOriginalName();
            $PlanoPath = $Plano->storeAs('Equipos y Consumibles/Planos/Herramientas/', $newFileNameFactura, 'public');
            $generalConHerramientas->Plano = $PlanoPath;
            $generalConHerramientas->save();
            }

        return redirect()->route('inventario');
    }
    
    }
