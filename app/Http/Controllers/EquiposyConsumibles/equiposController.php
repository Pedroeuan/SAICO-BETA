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
                //$CertificadosHistorialCertificados->idGeneral_EyC = $generalConCertificado->idGeneral_EyC;
                $CertificadosHistorialCertificados->Certificado_Caducado = $nuevaRuta;
                $CertificadosHistorialCertificados->Ultima_Fecha_calibracion = $generalConCertificado->Fecha_calibracion;
                $CertificadosHistorialCertificados->save();
                }
            }
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
