<?php

namespace App\Http\Controllers\OC;

use App\Models\detallesOC\detallesOC;
use App\Models\Firmantes_OC\Firmantes_OC;
use App\Models\Catalogo_OC\Catalogo_OC;
use App\Models\OC\OC;
use App\Models\Clientes\clientes;
use App\Models\Reporte\reporte;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
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
     * Show the form for editing the specified resource.
     */
    public function edit_revisar($id)
    {
        $OC = OC::where('idOC', $id)->first();
        $detallesOCM = detallesOC::where('idOC',$OC->idOC)->first();
        $catalogo = DB::table('Catalogo_OC')->get();
        $Condiciones_Pago = DB::table('Condiciones_Pago')->get();
        $Condiciones_Generales = DB::table('Condiciones_Generales')->get();
        $idCliente = $OC->idClientes;
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $Cliente = clientes::where('idClientes', $idCliente)->first(); 
        $Nombre_Cliente = $Cliente->Cliente;
        // Soporta tanto el formato anterior (lista de detalles) como el formato
        // actual que guarda los detalles junto con sus totales.
        $datosGuardados = $detallesOCM ? json_decode($detallesOCM->Detalles, true) : [];
        $detallesOC = $datosGuardados['detallesOC'] ?? $datosGuardados;
        $detallesOC = is_array($detallesOC) ? array_values($detallesOC) : [];
        $totales = $datosGuardados['totales'] ?? [];

        if (empty($totales) && !empty($detallesOC)) {
            $subtotalCentavos = 0;
            foreach ($detallesOC as $detalle) {
                $cantidad = (float) ($detalle['cantidad'] ?? 0);
                $valorUnitario = (float) ($detalle['valor_unitario'] ?? 0);
                $subtotalCentavos += (int) round($cantidad * $valorUnitario * 100);
            }

            $ivaCentavos = (int) round($subtotalCentavos * 0.16);
            $totales = [
                'subtotal' => $subtotalCentavos / 100,
                'iva' => $ivaCentavos / 100,
                'total' => ($subtotalCentavos + $ivaCentavos) / 100,
            ];
        }

        $totales = array_merge([
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
        ], is_array($totales) ? $totales : []);

    $Firmantes = Firmantes_OC::where('idOC',$OC->idOC)->first();
    // Decodificar JSON de la columna 'Detalles'
        $Firmas = $Firmantes ? json_decode($Firmantes->Nombre_Cargo, true) : [];
        // Obtener el numero de firmas
        $numFirmas = $Firmas['numFirmas'] ?? 1;

        // Marcas (anotaciones) guardadas previamente sobre el PDF.
        $marcas = [];
        if (!empty($OC->Revision_Marcas)) {
            $marcasDecodificadas = json_decode($OC->Revision_Marcas, true);
            if (is_array($marcasDecodificadas)) {
                $marcas = $marcasDecodificadas;
            }
        }

        return view('OC.Revision.edit_revisar', compact('id','OC','detallesOC','catalogo','detallesOCM','Nombre_Cliente','totales','Condiciones_Pago','Condiciones_Generales','numFirmas','marcas'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index_AD()
    {
        // OC en revisión y ya revisadas (Revisión: V1, Revisado V1, V2, ...).
        $OC = OC::where('Estatus', 'like', 'Revis%')->get();

        return view('OC.Revision.index_AD', compact('OC'));
    }
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
        $Condiciones_Pago = DB::table('Condiciones_Pago')->get();
        $Condiciones_Generales = DB::table('Condiciones_Generales')->get();
        return view('OC.create', compact('Clientes', 'catalogo', 'Condiciones_Pago', 'Condiciones_Generales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOC(Request $request)
    {
        //
        //$request->all();
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
            //Log::info('cliente: ', ['cliente' => $cliente]);
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

        // Asignar un cliente por defecto si no se capturó ningún cliente
        if (empty($OC->idClientes)) {
            $clientePorDefecto = clientes::first();
            $OC->idClientes = $clientePorDefecto ? $clientePorDefecto->idClientes : 1;
        }

        $OC->save();

        /*Notas */
        if($request->input('Notas')==null)
        {
            $detallesOCModel->Notas = $EsperaDato;
        }else{
            $detallesOCModel->Notas = $request->input('Notas');
        }
        /*Condiciones_pago */
        if($request->input('Condiciones_pago')==null)
        {
            $detallesOCModel->Condiciones_pago = $EsperaDato;
        }else{
            $detallesOCModel->Condiciones_pago = $request->input('Condiciones_pago');
        }
        /*Condiciones_generales */
        if($request->input('Condiciones_generales')==null)
        {
            $detallesOCModel->Condiciones_generales = $EsperaDato;
        }else{
            $detallesOCModel->Condiciones_generales = $request->input('Condiciones_generales');
        }
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

            //$items = json_decode($request->input('dynamicTableData'), true);

            $subtotalCentavos = 0;

            foreach ($detallesOC as $detalle) {
                $cantidad = (float) ($detalle['cantidad'] ?? 0);
                $valorUnitario = (float) ($detalle['valor_unitario'] ?? 0);

                $subtotalCentavos += (int) round($cantidad * $valorUnitario * 100);
            }

            $ivaCentavos = (int) round($subtotalCentavos * 0.16);

            $datosOrden = [
                'detallesOC' => $detallesOC,
                'totales' => [
                    'subtotal' => $subtotalCentavos / 100,
                    'iva' => $ivaCentavos / 100,
                    'total' => ($subtotalCentavos + $ivaCentavos) / 100,
                ],
            ];

            $detallesJSON = json_encode($datosOrden, JSON_THROW_ON_ERROR);
            $detallesOCModel->Detalles = $detallesJSON;
            // Guardar el objeto en la base de datos
            $detallesOCModel->save();
        } else {
            //Log::warning('No se han enviado detalles para guardar');
        }

        // Crear un nuevo registro en la firmantes_OS
            $firmantes_OC = new Firmantes_OC;

            /* Firmas */
            $numFirmas = (int) $request->input('numFirmas', 1);
            $firmasPorCantidad = [
                1 => $request->input('Firmas_Reportes1', []),
                2 => $request->input('Firmas_Reportes2', []),
                3 => $request->input('Firmas_Reportes3', []),
                4 => $request->input('Firmas_Reportes4', []),
            ];

            $datosFirmas = $firmasPorCantidad[$numFirmas] ?? [];
            $datosFirmas['numFirmas'] = $numFirmas;
            $firmantes_OC->Nombre_Cargo = json_encode($datosFirmas);
            $firmantes_OC->idOC = $OC->idOC;
            $firmantes_OC->save();

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
        $Condiciones_Pago = DB::table('Condiciones_Pago')->get();
        $Condiciones_Generales = DB::table('Condiciones_Generales')->get();
        $idCliente = $OC->idClientes;
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $Cliente = clientes::where('idClientes', $idCliente)->first(); 
        $Nombre_Cliente = $Cliente->Cliente;
        // Soporta tanto el formato anterior (lista de detalles) como el formato
        // actual que guarda los detalles junto con sus totales.
        $datosGuardados = $detallesOCM ? json_decode($detallesOCM->Detalles, true) : [];
        $detallesOC = $datosGuardados['detallesOC'] ?? $datosGuardados;
        $detallesOC = is_array($detallesOC) ? array_values($detallesOC) : [];
        $totales = $datosGuardados['totales'] ?? [];

        if (empty($totales) && !empty($detallesOC)) {
            $subtotalCentavos = 0;
            foreach ($detallesOC as $detalle) {
                $cantidad = (float) ($detalle['cantidad'] ?? 0);
                $valorUnitario = (float) ($detalle['valor_unitario'] ?? 0);
                $subtotalCentavos += (int) round($cantidad * $valorUnitario * 100);
            }

            $ivaCentavos = (int) round($subtotalCentavos * 0.16);
            $totales = [
                'subtotal' => $subtotalCentavos / 100,
                'iva' => $ivaCentavos / 100,
                'total' => ($subtotalCentavos + $ivaCentavos) / 100,
            ];
        }

        $totales = array_merge([
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
        ], is_array($totales) ? $totales : []);

    $Firmantes = Firmantes_OC::where('idOC',$OC->idOC)->first();
    // Decodificar JSON de la columna 'Detalles'
        $Firmas = $Firmantes ? json_decode($Firmantes->Nombre_Cargo, true) : [];
        // Obtener el numero de firmas
        $numFirmas = $Firmas['numFirmas'] ?? 1;

        return view('OC.edit', compact('id','OC','detallesOC','catalogo','detallesOCM','Nombre_Cliente','totales','Condiciones_Pago','Condiciones_Generales','numFirmas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateOC(Request $request, $id)
    {
        //dd($request->all());
        $request->validate([
            'Numero_OC' => 'required|integer',
            'Requisicion' => 'required|string',
            'Proyecto' => 'required|string',
            'Lugar_trabajo' => 'required|string',
        ]);

        $OC = OC::find($id);
        $detallesOCModel = detallesOC::find($id);
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

        $detallesOCModel->update([
            'NumCotizacion' => $request->input('NumCotizacion'),
            'SolicitudCliente' => $request->input('SolicitudCliente'),
            'Contacto' => $request->input('Contacto'),
            'Puesto' => $request->input('Puesto'),
            'Ciudad' => $request->input('Ciudad'),
            'Telefono' => $request->input('Telefono'),
            'Correo' => $request->input('Correo'),
            'Vigencia' => $request->input('Vigencia'),
            'Notas' => $request->input('Notas'),
            'Condiciones_pago' => $request->input('Condiciones_pago'),
            'Condiciones_generales' => $request->input('Condiciones_generales'),
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
        $request->validate([
            'dynamicTableData' => 'required|json',
        ]);

        $detallesOC = json_decode($request->input('dynamicTableData'), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($detallesOC) || !array_is_list($detallesOC)) {
            return back()->withErrors([
                'dynamicTableData' => 'Los detalles de la orden tienen un formato inválido.',
            ])->withInput();
        }

        $subtotalCentavos = 0;
        foreach ($detallesOC as $detalle) {
            $cantidad = is_array($detalle) && is_numeric($detalle['cantidad'] ?? null)
                ? (float) $detalle['cantidad']
                : 0;
            $valorUnitario = is_array($detalle) && is_numeric($detalle['valor_unitario'] ?? null)
                ? (float) $detalle['valor_unitario']
                : 0;

            $subtotalCentavos += (int) round($cantidad * $valorUnitario * 100);
        }

        $ivaCentavos = (int) round($subtotalCentavos * 0.16);
        $detallesJSON = json_encode([
            'detallesOC' => $detallesOC,
            'totales' => [
                'subtotal' => $subtotalCentavos / 100,
                'iva' => $ivaCentavos / 100,
                'total' => ($subtotalCentavos + $ivaCentavos) / 100,
            ],
        ], JSON_THROW_ON_ERROR);

        $detallesOCModel = detallesOC::firstOrNew(['idOC' => $OC->idOC]);
        $detallesOCModel->Detalles = $detallesJSON;
        $detallesOCModel->save();

        // Buscar las firmas por la orden de servicio; $id no es la clave primaria de Firmantes_OS.
        $firmantes_OS = Firmantes_OC::firstOrNew([
            'idOC' => $OC->idOC,
        ]);

        /* Firmas */
        $numFirmas = (int) $request->input('numFirmas', 1);
        $firmasPorCantidad = [
            1 => $request->input('Firmas_Reportes1', []),
            2 => $request->input('Firmas_Reportes2', []),
            3 => $request->input('Firmas_Reportes3', []),
            4 => $request->input('Firmas_Reportes4', []),
        ];

        $datosFirmas = $firmasPorCantidad[$numFirmas] ?? [];
        $datosFirmas['numFirmas'] = $numFirmas;
        $firmantes_OS->Nombre_Cargo = json_encode($datosFirmas);
        $firmantes_OS->save();
        return redirect()->route('OC.indexOC');
    }

    /**
     * Guarda los cambios realizados durante la revisión de la OC,
     * incluyendo el texto largo de observaciones (Revision_OC).
     */
    public function updateOC_Revision(Request $request, $id)
    {
        $OC = OC::find($id);

        // La versión de Revision define el estatus final: Revisado V1, V2, etc.
        // Si la columna está vacía, se intenta recuperar la versión del estatus
        // actual (por ejemplo "Revisión: V2") antes de usar V1 por defecto.
        $revision = trim((string) $OC->Revision);

        if ($revision === '') {
            if (preg_match('/V\s*(\d+)/i', (string) $OC->Estatus, $coincidencias)) {
                $revision = 'V' . (int) $coincidencias[1];
            } else {
                $revision = 'V1';
            }
        }

        // Solo se guardan las marcas si el JSON recibido es válido y no está vacío.
        $marcasGuardadas = null;
        $marcasInput = $request->input('Revision_Marcas');

        if (is_string($marcasInput)) {
            $marcasDecodificadas = json_decode($marcasInput, true);
            if (is_array($marcasDecodificadas) && !empty($marcasDecodificadas)) {
                $marcasGuardadas = $marcasInput;
            }
        }

        $OC->update([
            'Revision_OC' => $request->input('Revision_OC'),
            'Revision_Marcas' => $marcasGuardadas,
            'Revision' => $revision,
            'Estatus' => 'Revisado ' . $revision,
            'Revision_Autor' => auth()->user()->name ?? null,
            'Revision_At' => now(),
        ]);

        return redirect()->route('OC.indexOC_AD')
            ->with('success', 'La revisión de la orden de compra se guardó correctamente.');
    }

    /**
     * Guarda el comentario que agrega ventas después de revisar una OC.
     * Se conserva separado de Revision_OC para no perder las observaciones
     * originales del revisor.
     */
    public function guardarComentarioVentas(Request $request, $id)
    {
        $request->validate([
            'Comentario_Ventas' => 'required|string|max:4000',
        ], [
            'Comentario_Ventas.required' => 'Escribe un comentario antes de guardarlo.',
            'Comentario_Ventas.max' => 'El comentario no puede superar los 4000 caracteres.',
        ]);

        $OC = OC::where('idOC', $id)->first();

        if (!$OC) {
            abort(404, 'Orden de compra no encontrada.');
        }

        $OC->Comentario_Ventas = trim((string) $request->input('Comentario_Ventas'));
        $OC->Comentario_Ventas_Autor = auth()->user()->name ?? null;
        $OC->Comentario_Ventas_At = now();
        $OC->save();

        return redirect()
            ->route('OC.revisionPDF', ['id' => $OC->idOC])
            ->with('success', 'Tu comentario se guardó correctamente.');
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

    /**
     * Envía la orden de compra a revisión.
     *
     * Si es la primera vez usa "V1". Si la OC ya fue revisada
     * ("Revisado V1", "Revisado V2", ...) el nuevo envío crea la
     * siguiente versión ("V2", "V3", ...). Si ya está en revisión,
     * conserva la versión actual.
     */
    public function enviarRevision($id)
    {
        $OC = OC::find($id);

        if (!$OC) {
            return response()->json([
                'success' => false,
                'message' => 'Orden de compra no encontrada',
            ], 404);
        }

        $estatusActual = trim((string) $OC->Estatus);
        $revision = trim((string) $OC->Revision);

        // Recuperar la versión desde el estatus si la columna está vacía.
        if ($revision === '' && preg_match('/V\s*(\d+)/i', $estatusActual, $coincidencias)) {
            $revision = 'V' . (int) $coincidencias[1];
        }

        if ($revision === '') {
            $revision = 'V1';
        }

        // Si la revisión anterior ya terminó, este envío inicia la siguiente.
        if (stripos($estatusActual, 'Revisado') === 0) {
            $revision = $this->siguienteRevision($revision);
        }

        $OC->Revision = $revision;
        $OC->Estatus = 'Revisión: ' . $revision;
        $OC->Revision_Autor = null;
        $OC->Revision_At = null;
        $OC->save();

        return response()->json([
            'success' => true,
            'message' => 'La orden de compra fue enviada a revisión.',
            'estatus' => $OC->Estatus,
            'revision' => $OC->Revision,
        ]);
    }

    /**
     * Devuelve la siguiente versión a partir de una cadena tipo "V1".
     */
    private function siguienteRevision(string $revision): string
    {
        if (preg_match('/^V\s*(\d+)$/i', trim($revision), $coincidencias)) {
            return 'V' . ((int) $coincidencias[1] + 1);
        }

        return 'V1';
    }

    /**
     * Construye los datos que necesita la plantilla OC_PDF.
     */
    private function datosPDFOC($id): array
    {
        $OC = OC::find($id);
        $cliente = clientes::find($OC->idClientes);
        $OC->cliente = $cliente; // Agregar el cliente al objeto OC
        $detallesRegistro = detallesOC::where('idOC', $id)->first();
        // El JSON se guarda como {detallesOC:[...], totales:{...}}.
        // Se soporta también el formato anterior (lista plana de detalles).
        $datosGuardados = $detallesRegistro ? json_decode($detallesRegistro->Detalles, true) : [];
        $detallesOC = $datosGuardados['detallesOC'] ?? $datosGuardados;
        $detallesOC = is_array($detallesOC) ? array_values($detallesOC) : [];
        $detallesOC = collect($detallesOC)->map(fn ($detalle) => (object) $detalle);
        $totales = $datosGuardados['totales'] ?? [];
        $totales = array_merge([
            'subtotal' => 0,
            'iva' => 0,
            'total' => 0,
        ], is_array($totales) ? $totales : []);
        $firmantes = Firmantes_OC::where('idOC', $id)->first();
        $Firmas_Reportes = $firmantes ? json_decode($firmantes->Nombre_Cargo, true) : [];
        $numFirmas = (int) ($Firmas_Reportes['numFirmas'] ?? 1);

        $Logo = public_path('images/Logo_AICO_R.jpg');

        return [
            'title' => 'Manifiesto PDF',
            'OC' => $OC,
            'detallesOC' => $detallesOC,
            'totales' => $totales,
            'detallesRegistro' => $detallesRegistro,
            'numFirmas' => $numFirmas,
            'Firmas_Reportes' => $Firmas_Reportes,
            'Logo' => $Logo,
        ];
    }

    public function OC_PDF($id)
    {
        $pdf = PDF::loadView('OC.OC_PDF', $this->datosPDFOC($id));

        return $pdf->stream('OC.PDF');
    }

    /**
     * Devuelve las marcas guardadas de una OC como arreglo.
     */
    private function marcasDeOC(OC $OC): array
    {
        if (empty($OC->Revision_Marcas)) {
            return [];
        }

        $marcasDecodificadas = json_decode($OC->Revision_Marcas, true);

        return is_array($marcasDecodificadas) ? $marcasDecodificadas : [];
    }

    /**
     * Genera una copia del PDF de la OC con las marcas de revisión ya
     * incrustadas, para descargarla o imprimirla.
     */
    public function OC_PDF_Marcas($id)
    {
        $OC = OC::where('idOC', $id)->first();

        if (!$OC) {
            abort(404, 'Orden de compra no encontrada.');
        }

        $marcas = $this->marcasDeOC($OC);
        $pdfBase = PDF::loadView('OC.OC_PDF', $this->datosPDFOC($id))->output();

        if (empty($marcas)) {
            return response($pdfBase, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="OC_' . $id . '_marcas.pdf"',
                'Content-Length' => strlen($pdfBase),
            ]);
        }

        $archivoTemporal = tempnam(sys_get_temp_dir(), 'oc_pdf_');
        file_put_contents($archivoTemporal, $pdfBase);

        try {
            $pdf = new Fpdi();
            $totalPaginas = $pdf->setSourceFile($archivoTemporal);

            $marcasPorPagina = [];
            foreach ($marcas as $marca) {
                if (!is_array($marca) || !isset($marca['page'], $marca['strokes']) || !is_array($marca['strokes'])) {
                    continue;
                }

                $pagina = (int) $marca['page'];

                if ($pagina < 1) {
                    continue;
                }

                $marcasPorPagina[$pagina] = array_merge(
                    $marcasPorPagina[$pagina] ?? [],
                    $marca['strokes']
                );
            }

            for ($numeroPagina = 1; $numeroPagina <= $totalPaginas; $numeroPagina++) {
                $plantilla = $pdf->importPage($numeroPagina);
                $tamano = $pdf->getTemplateSize($plantilla);

                $pdf->AddPage($tamano['orientation'], [$tamano['width'], $tamano['height']]);
                $pdf->useTemplate($plantilla, 0, 0, $tamano['width'], $tamano['height']);

                if (!empty($marcasPorPagina[$numeroPagina])) {
                    $this->dibujarMarcasEnPDF(
                        $pdf,
                        $marcasPorPagina[$numeroPagina],
                        $tamano['width'],
                        $tamano['height']
                    );
                }
            }

            $pdfConMarcas = $pdf->Output('S');
        } finally {
            @unlink($archivoTemporal);
        }

        return response($pdfConMarcas, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="OC_' . $id . '_marcas.pdf"',
            'Content-Length' => strlen($pdfConMarcas),
        ]);
    }

    /**
     * Dibuja las marcas (mano alzada, rectángulos y elipses) sobre una página
     * del PDF. Las coordenadas guardadas están normalizadas de 0 a 1.
     */
    private function dibujarMarcasEnPDF(Fpdi $pdf, array $marcas, float $ancho, float $alto): void
    {
        foreach ($marcas as $marca) {
            if (!is_array($marca)) {
                continue;
            }

            $tipo = $marca['type'] ?? 'free';
            [$r, $g, $b] = $this->colorRGB($marca['color'] ?? '#e00000');

            $pdf->SetDrawColor($r, $g, $b);
            $pdf->SetLineWidth(max(0.1, ((float) ($marca['width'] ?? 0.003)) * $ancho));

            if ($tipo === 'free') {
                $puntos = $marca['points'] ?? [];

                if (!is_array($puntos) || empty($puntos)) {
                    continue;
                }

                $coordenadas = [];
                foreach ($puntos as $punto) {
                    if (!is_array($punto) || count($punto) < 2) {
                        continue;
                    }

                    $coordenadas[] = [
                        ((float) $punto[0]) * $ancho,
                        ((float) $punto[1]) * $alto,
                    ];
                }

                if (count($coordenadas) === 1) {
                    $pdf->Line($coordenadas[0][0], $coordenadas[0][1], $coordenadas[0][0] + 0.1, $coordenadas[0][1]);
                    continue;
                }

                for ($i = 1; $i < count($coordenadas); $i++) {
                    $pdf->Line(
                        $coordenadas[$i - 1][0],
                        $coordenadas[$i - 1][1],
                        $coordenadas[$i][0],
                        $coordenadas[$i][1]
                    );
                }

                continue;
            }

            $x1 = ((float) ($marca['x1'] ?? 0)) * $ancho;
            $y1 = ((float) ($marca['y1'] ?? 0)) * $alto;
            $x2 = ((float) ($marca['x2'] ?? 0)) * $ancho;
            $y2 = ((float) ($marca['y2'] ?? 0)) * $alto;

            if ($tipo === 'rect') {
                $pdf->Rect(min($x1, $x2), min($y1, $y2), abs($x2 - $x1), abs($y2 - $y1));
            } elseif ($tipo === 'ellipse') {
                $this->dibujarElipsePDF(
                    $pdf,
                    ($x1 + $x2) / 2,
                    ($y1 + $y2) / 2,
                    abs($x2 - $x1) / 2,
                    abs($y2 - $y1) / 2
                );
            }
        }
    }

    /**
     * Aproxima una elipse con segmentos de recta (FPDF no trae Ellipse).
     */
    private function dibujarElipsePDF(Fpdi $pdf, float $cx, float $cy, float $rx, float $ry): void
    {
        $segmentos = 72;
        $anterior = null;

        for ($i = 0; $i <= $segmentos; $i++) {
            $angulo = (2 * M_PI * $i) / $segmentos;
            $x = $cx + $rx * cos($angulo);
            $y = $cy + $ry * sin($angulo);

            if ($anterior !== null) {
                $pdf->Line($anterior[0], $anterior[1], $x, $y);
            }

            $anterior = [$x, $y];
        }
    }

    /**
     * Convierte un color hexadecimal (#rrggbb) a componentes RGB.
     */
    private function colorRGB(string $color): array
    {
        $hex = ltrim(trim($color), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (!preg_match('/^[0-9a-f]{6}$/i', $hex)) {
            $hex = 'e00000';
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Muestra la OC generada junto con las marcas guardadas durante la
     * revisión. Es una vista de solo lectura para que el editor pueda ver
     * qué se marcó y corregir la orden.
     */
    public function revisionPDF($id)
    {
        $OC = OC::where('idOC', $id)->first();

        if (!$OC) {
            abort(404, 'Orden de compra no encontrada.');
        }

        $marcas = $this->marcasDeOC($OC);

        return view('OC.Revision.ver_marcas', compact('id', 'OC', 'marcas'));
    }
}
