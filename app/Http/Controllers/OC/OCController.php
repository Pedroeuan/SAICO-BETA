<?php

namespace App\Http\Controllers\OC;

use App\Models\detallesOC\detallesOC;
use App\Models\Catalogo_OC\Catalogo_OC;
use App\Models\OC\OC;
use App\Models\Clientes\clientes;
use App\Models\Reporte\reporte;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class OCController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $OC = OC::all();

        return view('OC.index', compact('OC'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $Clientes = clientes::where('Cliente', '!=', 'POR DEFINIR')->get();
        $catalogo = DB::table('Catalogo_OC')->get();
        return view('OC.create', compact('Clientes', 'catalogo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOC(Request $request)
    {
        //
        $request->validate([
            'Contrato' => 'required|string',
            'TieneCliente' => 'required|in:si,no',
            'Numero_OC' => 'required|integer',
            'Requisicion' => 'required|string',
            //'Proyecto' => 'required|string',
            //'Lugar_trabajo' => 'required|string',
        ]);

        /*Modelos para registro*/
        $OC = new OC;
        $detallesOCModel = new detallesOC;

        $EsperaDato ='ESPERA DE DATO';
        // ==========================
        // Lógica para manejar Contrato
        // ==========================
        // Lógica para manejar el campo Contrato
        if ($request->input('TieneContrato') === "no") {

            // Si el usuario alteró el value o no llegó, se recalcula en backend
            $actual = $request->input('Contrato');

            // Verificar que realmente tenga el formato correcto
            if (!$actual || !preg_match('/^AICO-INT-[0-9]{4}$/', $actual)) {

                // Seguridad: volver a calcular el consecutivo
                /*$registros = reporte::orderBy('idReportes', 'DESC')->get();
                $ultimoNumero = 0;
                
                /*foreach ($registros as $r) {
                    $json = json_decode($r->Detalles_Generales, true);

                    if (!empty($json['Contrato']) && str_starts_with($json['Contrato'], 'AICO-INT-')) {
                        $n = intval(str_replace('AICO-INT-', '', $json['Contrato']));
                        if ($n > $ultimoNumero) $ultimoNumero = $n;
                        break;
                    }
                }
                //$nuevo = "AICO-INT-" . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);*/

                $registros = OC::where('Contrato', 'LIKE', 'AICO-INT-%')
                            ->orderBy('Contrato', 'DESC')
                            ->value('Contrato');
                $ultimoNumero = $registros ? intval(substr($registros, strrpos($registros, '-') + 1)) : 0;
                $nuevo = "AICO-INT-" . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

                $OC->Contrato = $nuevo;

            } else {
                // Si el frontend envió un contrato válido, se utiliza ese
                $OC->Contrato = $actual;
            }
        } else {
            $OC->Contrato = $request->input('Contrato', $EsperaDato);
        }

        /* NumCotizacion*/
        if($request->input('NumCotizacion')==null)
        {
            $detallesOCModel->NumCotizacion = $EsperaDato;
        }else{
            $detallesOCModel->NumCotizacion = $request->input('NumCotizacion');
        }
        /* SolicitudCliente*/
        if($request->input('SolicitudCliente')==null)
        {
            $detallesOCModel->SolicitudCliente = $EsperaDato;
        }else{
            $detallesOCModel->SolicitudCliente = $request->input('SolicitudCliente');
        }
        /* Tipo_servicio*/
        if($request->input('Tipo_servicio')==null)
        {
            $OC->Tipo_servicio = $EsperaDato;
        }else{
            $OC->Tipo_servicio = $request->input('Tipo_servicio');
        }
         // ==========================
        // Lógica para manejar Cliente
        // ==========================
        $clienteNombre = $request->input('TieneCliente') === 'si'
            ? $request->input('ClienteSelect')
            : $request->input('ClienteInput');

        if (!empty($clienteNombre)) {
            $cliente = clientes::where('Cliente', trim($clienteNombre))->first();
            Log::info('cliente: ', ['cliente' => $cliente]);
            if ($cliente) {
                $OC->idClientes = $cliente->idClientes;
            }else {
                $NewCliente = new clientes();
                $NewCliente->Cliente = $clienteNombre;
                $NewCliente->RFC = $EsperaDato;
                $NewCliente->Telefono = $EsperaDato;
                $NewCliente->Correo = $EsperaDato;
                $NewCliente->Logo = $EsperaDato;
                $NewCliente->portal_token = (string) Str::uuid();
                $NewCliente->save();

                $OC->idClientes = $NewCliente->idClientes;
            }
        }

        /* Tipo_servicio*/
        if($request->input('Contacto')==null)
        {
            $detallesOCModel->Contacto = $EsperaDato;
        }else{
            $detallesOCModel->Contacto = $request->input('Contacto');
        }

        /* Puesto*/
        if($request->input('Puesto')==null)
        {
            $detallesOCModel->Puesto = $EsperaDato;
        }else{
            $detallesOCModel->Puesto = $request->input('Puesto');
        }
        /* Fecha_solicitud*/
        if($request->input('Fecha_solicitud')==null)
        {
            $OC->Fecha_solicitud = '2001-01-01';
        }else{
            $OC->Fecha_solicitud = $request->input('Fecha_solicitud');
        }

        /* Ciudad*/
        if($request->input('Ciudad')==null)
        {
            $detallesOCModel->Ciudad = $EsperaDato;
        }else{
            $detallesOCModel->Ciudad = $request->input('Ciudad');
        }

        /* Telefono*/
        if($request->input('Telefono')==null)
        {
            $detallesOCModel->Telefono = $EsperaDato;
        }else{
            $detallesOCModel->Telefono = $request->input('Telefono');
        }

        /* Correo*/
        if($request->input('Correo')==null)
        {
            $detallesOCModel->Correo = $EsperaDato;
        }else{
            $detallesOCModel->Correo = $request->input('Correo');
        }
        /* Lugar_trabajo*/
        if($request->input('Lugar_trabajo')==null)
        {
            $OC->Lugar_trabajo = $EsperaDato;
        }else{
            $OC->Lugar_trabajo = $request->input('Lugar_trabajo');
        }

        /* Vigencia*/
        if($request->input('Vigencia')==null)
        {
            $detallesOCModel->Vigencia = $EsperaDato;
        }else{
            $detallesOCModel->Vigencia = $request->input('Vigencia');
        }
        /* Numero_OC*/
        if($request->input('Numero_OC')==null)
        {
            $OC->Num_OC = $EsperaDato;
        }else{
            $OC->Num_OC = $request->input('Numero_OC');
        }

        /* Requisicion*/
        if($request->input('Requisicion')==null)
        {
            $OC->Requisicion = $EsperaDato;
        }else{
            $OC->Requisicion = $request->input('Requisicion');
        }

        if($request->input('Proyecto')==null)
        {
            $OC->Proyecto = $EsperaDato;
        }else{
            $OC->Proyecto = $request->input('Proyecto');
        }

        $OC->Estatus = $request->input('Estatus');


        // Validar que se ha enviado el archivo de factura
        if ($request->hasFile('OC_archivo') && $request->file('OC_archivo')->isValid()) {
            $pdf = $request->file('OC_archivo');
            // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Ventas/OC'))
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
            $newFileNameOC = $newNumber . '_' . $pdf->getClientOriginalName();
            // Guardar el archivo PDF en la carpeta "public/Ventas/OC"
            $pdfPath = $pdf->storeAs('Ventas/OC', $newFileNameOC, 'public');
            // Guardar la ruta en la base de datos
            $OC->OC_archivo = $pdfPath;
        } else {
            $OC->OC_archivo = $EsperaDato;
        }
        $OC->save();

        // Decodificar el input JSON en un arreglo
        $detallesOC = json_decode($request->input('dynamicTableData'), true);

        // Comprobar si el arreglo tiene elementos antes de continuar
        if (!empty($detallesOC)) {

            // Convertir el arreglo en una cadena JSON
            $detallesJSON = json_encode($detallesOC); 

            // Asignar el idOC
            $detallesOCModel->idOC = $OC->idOC;

            // Guardar el JSON en la columna 'Detalles'
            $detallesOCModel->Detalles = $detallesJSON;

            // Guardar el objeto en la base de datos
            $detallesOCModel->save();
        } else {
            //Log::warning('No se han enviado detalles para guardar');
        }

        return redirect()->route('OC.indexOC');
    }

    /**
     * Display the specified resource.
     */
    public function show(OC $oC)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $OC = OC::where('idOC', $id)->first();
        $detallesOCM = detallesOC::where('idOC',$OC->idOC)->first();
        $catalogo = DB::table('Catalogo_OC')->get();

        $idCliente = $OC->idClientes;
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $Cliente = clientes::where('idClientes', $idCliente)->first(); 
        $Nombre_Cliente = $Cliente->Cliente;
        //Decodificar JSON de la columna 'Detalles'
        $detallesOC = $detallesOCM ? json_decode($detallesOCM->Detalles, true) : [];

        return view('OC.edit', compact('id','OC','detallesOC','catalogo','detallesOCM','Nombre_Cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateOC(Request $request, $id)
    {
        //
        $request->validate([
            'Numero_OC' => 'required|integer',
            'Requisicion' => 'required|string',
            'Proyecto' => 'required|string',
            'Lugar_trabajo' => 'required|string',
        ]);

        $OC = OC::find($id);
        // Actualizar los datos de la OC
        $OC->update([
            'Contrato' => $request->input('Contrato'),
            'Num_OC' => $request->input('Numero_OC'),
            'Requisicion' => $request->input('Requisicion'),
            'Proyecto' => $request->input('Proyecto'),
            'Lugar_trabajo' => $request->input('Lugar_trabajo'),
            'Fecha_solicitud' => $request->input('Fecha_solicitud'),
            'Tipo_servicio' => $request->input('Tipo_servicio'),
        ]);

        // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
        if ($request->hasFile('OC_archivo') && $request->file('OC_archivo')->isValid()) {
            // Obtener la ruta del archivo anterior desde la base de datos
            $rutaAnterior = $OC->OC_archivo;
            // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
            if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
                Storage::disk('public')->delete($rutaAnterior);
            }
            // Guardar el nuevo archivo PDF
            $pdf = $request->file('OC_archivo');
            // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Ventas/OC'))
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
            $newFileNameOC = $newNumber . '_' . $pdf->getClientOriginalName();
            
            $pdfPath = $pdf->storeAs('Ventas/OC', $newFileNameOC, 'public');
            // Actualizar la ruta de la OC_archivo en la base de datos
            $OC->OC_archivo = $pdfPath;
            $OC->save();
        }

        // Decodificar el input JSON en un arreglo
        $detallesOC = json_decode($request->input('dynamicTableData'), true);

        // Comprobar si el arreglo tiene elementos antes de continuar
        if (!empty($detallesOC)) {

            // Convertir el arreglo en una cadena JSON
            $detallesJSON = json_encode($detallesOC); 

            // Crear un nuevo registro en la tabla detallesOC
            $detallesOCModel = new detallesOC;

            // Asignar el idOC
            $detallesOCModel = detallesOC::find($id);

            $detallesOCModel->update([
                'Detalles' =>  $detallesJSON,
            ]);
        } else {
            //Log::warning('No se han enviado detalles para guardar');
        }

        return redirect()->route('OC.indexOC');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $OC = OC::find($id);

        if (!$OC) {
            return response()->json([
                'success' => false,
                'message' => 'Orden de compra no encontrada',
            ], 404);
        }

        // Eliminar el archivo asociado desde storage/app/public/Ventas/OC.
        if ($OC->OC_archivo && Storage::disk('public')->exists($OC->OC_archivo)) {
            Storage::disk('public')->delete($OC->OC_archivo);
        }

        // Eliminar los detalles de la OC
        $detallesOC = detallesOC::where('idOC', $OC->idOC)->first();
        if($detallesOC)
            {
                $detallesOC->delete();
            }
        $OC->delete();
         // Responder con éxito
        return response()->json(['success' => true, 'message' => 'Orden de compra eliminada exitosamente']);
    }

}
