<?php

namespace App\Http\Controllers\EquiposyConsumibles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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

class consumiblesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        /*CONSUMIBLES*/
        public function storeConsumibles(Request $request)
        {
            $request->validate([
                'Nombre_E_P_BP' => 'required|string|max:255',
                'Marca' => 'required|string|max:255',
                'Modelo' => 'required|string|max:255',
                //'Serie' => 'required|string|max:255',
                'Stock' => 'required|integer|min:1',
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

        /*Historial Almacen */
        // Obtén el id del registro recién creado
        $idAlmacen = $generalConAlmacen->idAlmacen;
        $idGeneral_EyC = $generalConAlmacen->idGeneral_EyC;
        $Tipo='SUMINISTRO';
        $Folio='N/A';
        //$Cantidad = 1;
        //$Fecha = Carbon::now()->format('Y-m-d H:i:s');
        $Fecha = Carbon::now()->format('Y-m-d');
        $Tierra_Costafuera ='N/A';

        // Ahora, crea un registro en la tabla historial_almacen
        $historialAlmacen = new Historial_Almacen;

        $historialAlmacen->idAlmacen = $idAlmacen; // Usa el idAlmacen recién creado
        $historialAlmacen->idGeneral_EyC = $idGeneral_EyC;
        $historialAlmacen->Tipo = $Tipo;
        $historialAlmacen->Cantidad = $request->input('Stock');
        $historialAlmacen->Fecha = $Fecha;
        $historialAlmacen->Tierra_Costafuera = $Tierra_Costafuera;
        $historialAlmacen->Folio = $Folio;
        $historialAlmacen->save();

        return redirect()->route('inventario');
        }

    /**
     * Display the specified resource.
     */
    public function show(consumibles $consumibles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(consumibles $consumibles)
    {
        //
    }


    /* Update the specified resource in storage.
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(consumibles $consumibles)
    {
        //
    }
}
