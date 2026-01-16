<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
use App\Models\EquiposyConsumibles\clasificacion;
use App\Models\EquiposyConsumibles\iso;


class equiposController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //return view('Equipos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
                'ISO' => 'required|in:9001,17025',
                'Disponibilidad_Estado' => 'required|string|max:255',
            ]);
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

            //$existsSerie = general_eyc::whereRaw("LOWER(Serie) = ?", [$serie])->exists();
            // ⚠️ Solo verificar duplicado de serie si el valor no es '---'
            $existsSerie = false;
            if ($serie !== '---') {
                $existsSerie = general_eyc::whereRaw("LOWER(Serie) = ?", [$serie])->exists();
            }

            //exists(): Devuelve true si encuentra algún registro que cumpla con la condición, indicando duplicado.
            //Si encuentra duplicados, devuelve un mensaje de error en No_economico y Serie.
            if ($existsNo_Economico && $existsSerie)
            {
                return redirect()->back()->withErrors([
                    'No_economico' => 'El No economico ya existe en la base de datos.',
                    'Serie' => 'La Serie ya existe en la base de datos.',
                ])->withInput();
            }
            else if ($existsNo_Economico) {
                return redirect()->back()->withErrors([
                    'No_economico' => 'El No economico ya existe en la base de datos.',
                ])->withInput();
            }
            else if ($existsSerie)
            {
                return redirect()->back()->withErrors([
                    'Serie' => 'La Serie ya existe en la base de datos.',
                ])->withInput();
            }
            //De esta manera, se valida que no existan duplicados en No_economico o Serie con variaciones en el formato y mayúsculas/minúsculas.

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
        if($request->input('Num_Reporte')==null)
            {
                $generalConEquipos->Num_Reporte = $EsperaDato;
            }else{
                $generalConEquipos->Num_Reporte = $request->input('Num_Reporte');
            }
        $generalConEquipos->save();

        // Clasificación
        $generalConClasificacion = new clasificacion;
        $generalConClasificacion->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
        if($request->input('Clasificacion')=='Elige el tipo de inspección que pertenece')
        {
            $general->Disponibilidad_Estado = $EsperaDato;
        }else{
            $generalConClasificacion->NombreC =  $request->input('Clasificacion');
        } 
        $generalConClasificacion->save();

        // ISO
        $generalConISO = new ISO;
        $generalConISO->idGeneral_EyC = $general->idGeneral_EyC; // Asigna la clave primaria del modelo principal al campo de relación
        if($request->input('ISO')=='Elige el tipo de ISO')
        {
            $generalConISO->NombreISO = $EsperaDato;
        }else{
            $generalConISO->NombreISO =  $request->input('ISO');
        } 
        if($request->input('Alcance')==null)
        {
            $generalConISO->Alcance = $EsperaDato;
        }else{
            $generalConISO->Alcance =  $request->input('Alcance');
        }
        if($request->input('Frec_Cali_Mant_Prev')==null)
        {
            $generalConISO->Frec_Cali_Mant_Prev = $EsperaDato;
        }else{
            $generalConISO->Frec_Cali_Mant_Prev =  $request->input('Frec_Cali_Mant_Prev');
        } 
        if($request->input('Frec_Man_Inter_Time')==null)
        {
            $generalConISO->Frec_Man_Inter_Time = $EsperaDato;
        }else{
            $generalConISO->Frec_Man_Inter_Time =  $request->input('Frec_Man_Inter_Time');
        } 
        if($request->input('Frec_Verificacion')==null)
        {
            $generalConISO->Frec_Verificacion = $EsperaDato;
        }else{
            $generalConISO->Frec_Verificacion =  $request->input('Frec_Verificacion');
        } 
        $generalConISO->save();

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
        if($request->input('Fecha_verificacion')==null)
        {
            $generalConCertificados->Fecha_verificacion = '01/01/0001';
        }else{
            $generalConCertificados->Fecha_verificacion = $request->input('Fecha_verificacion');
        }
        if($request->input('Prox_fecha_verificacion')==null)
        {
            $generalConCertificados->Prox_fecha_verificacion = '01/01/0001';
        }else{
            $generalConCertificados->Prox_fecha_verificacion = $request->input('Prox_fecha_verificacion');
        }
        if($request->input('Fecha_mantenimiento')==null)
        {
            $generalConCertificados->Fecha_mantenimiento = '01/01/0001';
        }else{
            $generalConCertificados->Fecha_mantenimiento = $request->input('Fecha_mantenimiento');
        }
        if($request->input('Prox_fecha_mantenimiento')==null)
        {
            $generalConCertificados->Prox_fecha_mantenimiento = '01/01/0001';
        }else{
            $generalConCertificados->Prox_fecha_mantenimiento = $request->input('Prox_fecha_mantenimiento');
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
        if($request->input('Unidad')==null)
        {
            $generalConAlmacen->Unidad = $EsperaDato;
        }else{
            $generalConAlmacen->Unidad = $request->input('Unidad');
        }
        $generalConAlmacen->save();

        /*Historial Almacen */
        // Obtén el id del registro recién creado
        $idAlmacen = $generalConAlmacen->idAlmacen;
        $idGeneral_EyC = $generalConAlmacen->idGeneral_EyC;
        $Tipo='SUMINISTRO';
        $Folio='N/A';
        $Cantidad = 1;
        $Fecha = Carbon::now()->format('Y-m-d');
        $Tierra_Costafuera ='N/A';

        // Ahora, crea un registro en la tabla historial_almacen
        $historialAlmacen = new Historial_Almacen;

        $historialAlmacen->idAlmacen = $idAlmacen; // Usa el idAlmacen recién creado
        $historialAlmacen->idGeneral_EyC = $idGeneral_EyC;
        $historialAlmacen->Tipo = $Tipo;
        $historialAlmacen->Cantidad = $Cantidad;
        $historialAlmacen->Fecha = $Fecha;
        $historialAlmacen->Tierra_Costafuera = $Tierra_Costafuera;
        $historialAlmacen->Folio = $Folio;
        $historialAlmacen->save();

        return redirect()->route('inventario');
    }

    /**
     * Display the specified resource.
     */
    public function show(equipos $equipos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(equipos $equipos)
    {
        //
    }

    /*Update Equipos*/
    public function updateEquipos(Request $request, $id)
    {
        $request->validate([
            'Nombre_E_P_BP' => 'required|string|max:255',
            'No_economico' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'Modelo' => 'required|string|max:255',
            'Serie' => 'required|string|max:255',
            'ISO' => 'required|in:9001,17025',
            'Disponibilidad_Estado' => 'required|string|max:255',
        ]);

        // Obtener el equipo existente
        $generalEyC  = general_eyc::find($id);

        $EsperaDato ='ESPERA DE DATO';

        $No_EBD = $generalEyC->No_economico;
        $SerBD = $generalEyC->Serie;

        $No_EF = $request->input('No_economico');
        $SerF = $request->input('Serie');

        //if (strcasecmp(trim($No_EF), trim($No_EBD)) == 0 && strcasecmp(trim($SerF), trim($SerBD)) == 0)
        if(strcasecmp(trim($No_EF), trim($No_EBD)) != 0 || strcasecmp(trim($SerF), trim($SerBD)) != 0)
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
            ->where('idGeneral_EyC', '!=', $id)
            ->where('No_economico', '!=', $noEconomicoLimpio)  // ← EXCLUYE SU PROPIO REGISTRO
            ->exists();

            // ⚠️ Solo verificar duplicado de serie si el valor no es '---'
            $existsSerie = false;
            if ($serie !== '---') {
                $existsSerie = general_eyc::whereRaw("LOWER(Serie) = ?", [$serie])->exists();
            }

            //exists(): Devuelve true si encuentra algún registro que cumpla con la condición, indicando duplicado.
            //Si encuentra duplicados, devuelve un mensaje de error en No_economico y Serie.
            if ($existsNo_Economico && $existsSerie)
            {
                return redirect()->back()->withErrors([
                    'No_economico' => 'El No economico ya existe en la base de datos.',
                    'Serie' => 'La Serie ya existe en la base de datos.',
                ])->withInput();
            }
            elseif ($existsNo_Economico) {
                return redirect()->back()->withErrors([
                    'No_economico' => 'El No economico ya existe en la base de datos.',
                ])->withInput();
            }
            elseif ($existsSerie)
            {
                return redirect()->back()->withErrors([
                    'Serie' => 'La Serie ya existe en la base de datos.',
                ])->withInput();
            }
            //De esta manera, se valida que no existan duplicados en No_economico o Serie con variaciones en el formato y mayúsculas/minúsculas.
        }
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
                'Disponibilidad_Estado' => $disponibilidadEstado,
            ]);

                // Actualizar los datos del equipo asociado
                $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();

                if ($generalConEquipos) {

                    // Si el Num_Reporte viene vacío o no viene, asignar "ESPERA DE DATO"
                    $numReporte = $request->filled('Num_Reporte') 
                        ? $request->input('Num_Reporte') 
                        : 'ESPERA DE DATO';

                    $generalConEquipos->update([
                        'Num_Reporte' => $numReporte,
                    ]);
                }
            
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
            $Almacen = almacen::where('idGeneral_EyC', $id)->first();
            $Almacen->update([
                'Unidad' => $request->input('Unidad'),
            ]);

            // Actualizar los datos de Clasificación
            $generalConClasificacion = clasificacion::where('idGeneral_EyC', $id)->first();
            $generalConClasificacion->update([
                'NombreC' => $request->input('Clasificacion'),
            ]);

            // Actualizar los datos de ISO
            $generalConISO= ISO::where('idGeneral_EyC', $id)->first();
            $generalConISO->update([
                'NombreISO' => $request->input('ISO'),
                'Alcance' => $request->input('Alcance'),
                'Frec_Cali_Mant_Prev' => $request->input('Frec_Cali_Mant_Prev'),
                'Frec_Man_Inter_Time' => $request->input('Frec_Man_Inter_Time'),
                'Frec_Verificacion' => $request->input('Frec_Verificacion'),
            ]);

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
            if($request->input('Fecha_verificacion')==null)
            {
                $Fecha_verificacion = '2001-01-01';
            }else{
                $Fecha_verificacion = $request->input('Fecha_verificacion');
            }  
                if($request->input('Prox_fecha_verificacion')==null)
            {
                $Prox_fecha_verificacion = '2001-01-01';
            }else{
                $Prox_fecha_verificacion = $request->input('Prox_fecha_verificacion');
            }  
                if($request->input('Fecha_mantenimiento')==null)
            {
                $Fecha_mantenimiento = '2001-01-01';
            }else{
                $Fecha_mantenimiento = $request->input('Fecha_mantenimiento');
            }  
                if($request->input('Prox_fecha_mantenimiento')==null)
            {
                $Prox_fecha_mantenimiento = '2001-01-01';
            }else{
                $Prox_fecha_mantenimiento = $request->input('Prox_fecha_mantenimiento');
            }  
            $generalConCertificado->update([
                'No_certificado' => $request->input('No_certificado'),
                'Fecha_calibracion' => $fechaCalibracion,
                'Prox_fecha_calibracion' => $proxFechaCalibracion,
                'Fecha_verificacion' => $Fecha_verificacion,
                'Prox_fecha_verificacion' => $Prox_fecha_verificacion,
                'Fecha_mantenimiento' => $Fecha_mantenimiento,
                'Prox_fecha_mantenimiento' => $Prox_fecha_mantenimiento,
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
                    //$CertificadosHistorialCertificados->idGeneral_EyC = $generalConCertificado->idGeneral_EyC;
                    $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
                    $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
                    $CertificadosHistorialCertificados->save();
                    }
                }

        // Solo validar duplicados si realmente se modificó
        /*if (strcasecmp(trim($No_EF), trim($No_EBD)) == 0 &&
        strcasecmp(trim($SerF), trim($SerBD)) == 0)
        {
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
                'Disponibilidad_Estado' => $disponibilidadEstado,
            ]);

            // Actualizar los datos del equipo asociado
            $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
            $generalConEquipos->update([
                'Num_Reporte' => $request->input('Num_Reporte'),
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
            $Almacen = almacen::where('idGeneral_EyC', $id)->first();
            $Almacen->update([
                'Unidad' => $request->input('Unidad'),
            ]);
            // Actualizar los datos de Clasificación
            $generalConClasificacion = clasificacion::where('idGeneral_EyC', $id)->first();
            $generalConClasificacion->update([
                'NombreC' => $request->input('Clasificacion'),
            ]);

            // Actualizar los datos de ISO
            $generalConISO= ISO::where('idGeneral_EyC', $id)->first();
            $generalConISO->update([
                'NombreISO' => $request->input('ISO'),
                'Alcance' => $request->input('Alcance'),
                'Frec_Cali_Mant_Prev' => $request->input('Frec_Cali_Mant_Prev'),
                'Frec_Man_Inter_Time' => $request->input('Frec_Man_Inter_Time'),
                'Frec_Verificacion' => $request->input('Frec_Verificacion'),
            ]);

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
            if($request->input('Fecha_verificacion')==null)
            {
                $Fecha_verificacion = '2001-01-01';
            }else{
                $Fecha_verificacion = $request->input('Fecha_verificacion');
            }  
                if($request->input('Prox_fecha_verificacion')==null)
            {
                $Prox_fecha_verificacion = '2001-01-01';
            }else{
                $Prox_fecha_verificacion = $request->input('Prox_fecha_verificacion');
            }  
                if($request->input('Fecha_mantenimiento')==null)
            {
                $Fecha_mantenimiento = '2001-01-01';
            }else{
                $Fecha_mantenimiento = $request->input('Fecha_mantenimiento');
            }  
                if($request->input('Prox_fecha_mantenimiento')==null)
            {
                $Prox_fecha_mantenimiento = '2001-01-01';
            }else{
                $Prox_fecha_mantenimiento = $request->input('Prox_fecha_mantenimiento');
            }  
            $generalConCertificado->update([
                'No_certificado' => $request->input('No_certificado'),
                'Fecha_calibracion' => $fechaCalibracion,
                'Prox_fecha_calibracion' => $proxFechaCalibracion,
                'Fecha_verificacion' => $Fecha_verificacion,
                'Prox_fecha_verificacion' => $Prox_fecha_verificacion,
                'Fecha_mantenimiento' => $Fecha_mantenimiento,
                'Prox_fecha_mantenimiento' => $Prox_fecha_mantenimiento,
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
                    /*$CertificadosHistorialCertificados = new historial_certificado;
                    $CertificadosHistorialCertificados->idCertificados = $generalConCertificado->idCertificados;
                    $CertificadosHistorialCertificados->idGeneral_EyC = $generalEyC->idGeneral_EyC;
                    //$CertificadosHistorialCertificados->idGeneral_EyC = $generalConCertificado->idGeneral_EyC;
                    $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
                    $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
                    $CertificadosHistorialCertificados->save();
                    }
                }
        }
        else
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

            //exists(): Devuelve true si encuentra algún registro que cumpla con la condición, indicando duplicado.
            //Si encuentra duplicados, devuelve un mensaje de error en No_economico y Serie.
            if ($existsNo_Economico && $existsSerie)
            {
                return redirect()->back()->withErrors([
                    'No_economico' => 'El No economico ya existe en la base de datos.',
                    'Serie' => 'La Serie ya existe en la base de datos.',
                ])->withInput();
            }
            else if ($existsNo_Economico) {
                return redirect()->back()->withErrors([
                    'No_economico' => 'El No economico ya existe en la base de datos.',
                ])->withInput();
            }
            else if ($existsSerie)
            {
                return redirect()->back()->withErrors([
                    'Serie' => 'La Serie ya existe en la base de datos.',
                ])->withInput();
            }
            //De esta manera, se valida que no existan duplicados en No_economico o Serie con variaciones en el formato y mayúsculas/minúsculas.

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
                'Disponibilidad_Estado' => $disponibilidadEstado,
            ]);

            // Actualizar los datos del equipo asociado
            $generalConEquipos = equipos::where('idGeneral_EyC', $id)->first();
            $generalConEquipos->update([
                'Num_Reporte' => $request->input('Num_Reporte'),
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
            $Almacen = almacen::where('idGeneral_EyC', $id)->first();
            $Almacen->update([
                'Unidad' => $request->input('Unidad'),
            ]);

            // Actualizar los datos de Clasificación
            $generalConClasificacion = clasificacion::where('idGeneral_EyC', $id)->first();
            $generalConClasificacion->update([
                'NombreC' => $request->input('Clasificacion'),
            ]);

            // Actualizar los datos de ISO
            $generalConISO= ISO::where('idGeneral_EyC', $id)->first();
            $generalConISO->update([
                'NombreISO' => $request->input('ISO'),
                'Alcance' => $request->input('Alcance'),
                'Frec_Cali_Mant_Prev' => $request->input('Frec_Cali_Mant_Prev'),
                'Frec_Man_Inter_Time' => $request->input('Frec_Man_Inter_Time'),
                'Frec_Verificacion' => $request->input('Frec_Verificacion'),
            ]);

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
            if($request->input('Fecha_verificacion')==null)
            {
                $Fecha_verificacion = '2001-01-01';
            }else{
                $Fecha_verificacion = $request->input('Fecha_verificacion');
            }  
                if($request->input('Prox_fecha_verificacion')==null)
            {
                $Prox_fecha_verificacion = '2001-01-01';
            }else{
                $Prox_fecha_verificacion = $request->input('Prox_fecha_verificacion');
            }  
                if($request->input('Fecha_mantenimiento')==null)
            {
                $Fecha_mantenimiento = '2001-01-01';
            }else{
                $Fecha_mantenimiento = $request->input('Fecha_mantenimiento');
            }  
                if($request->input('Prox_fecha_mantenimiento')==null)
            {
                $Prox_fecha_mantenimiento = '2001-01-01';
            }else{
                $Prox_fecha_mantenimiento = $request->input('Prox_fecha_mantenimiento');
            }  
            $generalConCertificado->update([
                'No_certificado' => $request->input('No_certificado'),
                'Fecha_calibracion' => $fechaCalibracion,
                'Prox_fecha_calibracion' => $proxFechaCalibracion,
                'Fecha_verificacion' => $Fecha_verificacion,
                'Prox_fecha_verificacion' => $Prox_fecha_verificacion,
                'Fecha_mantenimiento' => $Fecha_mantenimiento,
                'Prox_fecha_mantenimiento' => $Prox_fecha_mantenimiento,
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
                    /*$CertificadosHistorialCertificados = new historial_certificado;
                    $CertificadosHistorialCertificados->idCertificados = $generalConCertificado->idCertificados;
                    $CertificadosHistorialCertificados->idGeneral_EyC = $generalEyC->idGeneral_EyC;
                    //$CertificadosHistorialCertificados->idGeneral_EyC = $generalConCertificado->idGeneral_EyC;
                    $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
                    $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
                    $CertificadosHistorialCertificados->save();
                    }
                }
        }*/
            return redirect()->route('inventario');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(equipos $equipos)
    {
        //
    }
}
