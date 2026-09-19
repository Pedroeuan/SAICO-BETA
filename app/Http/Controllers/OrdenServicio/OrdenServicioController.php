<?php

namespace App\Http\Controllers\OrdenServicio;

use App\Models\OrdenServicio\Orden_Servicio; 
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;
use App\Models\OrdenServicio\Firmantes_OS;
use App\Models\Clientes\clientes;
use App\Models\Reporte\reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;    
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class OrdenServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtén todas las órdenes de servicio desde la base de datos
        $OS = Orden_Servicio::with('cliente')->get();

        return view('OT_S.index', compact('OS'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $Clientes = clientes::where('Cliente', '!=', 'POR DEFINIR')->get();
        return view('OT_S.create', compact('Clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'TieneCliente' => 'required|in:si,no',
            'Contrato' => 'required|string',
            'Proyecto' => 'required|string',
            'Lugar' => 'required|string',
        ]);

        $OS = new Orden_Servicio;
        $EsperaDato ='ESPERA DE DATO';

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
                $OS->idClientes = $cliente->idClientes;
            }else {
                $NewCliente = new clientes();
                $NewCliente->Cliente = $clienteNombre;
                $NewCliente->RFC = $EsperaDato;
                $NewCliente->Telefono = $EsperaDato;
                $NewCliente->Correo = $EsperaDato;
                $NewCliente->Logo = $EsperaDato;
                $NewCliente->portal_token = (string) Str::uuid();
                $NewCliente->save();

                $OS->idClientes = $NewCliente->idClientes;
            }
        }

        if($request->input('Fecha')==null)
        {
            $OS->Fecha = $EsperaDato;
        }else{
            $OS->Fecha = $request->input('Fecha');
        }

        if($request->input('Lugar')==null)
        {
            $OS->Lugar = $EsperaDato;
        }else{
            $OS->Lugar = $request->input('Lugar');
        }

        // ==========================
        // Lógica para manejar Contrato
        // ==========================
        // Lógica para manejar el campo Contrato
        if ($request->input('TieneContrato') === "no") {

            // Si el usuario alteró el valor o no llegó, se recalcula en backend
            $actual = $request->input('Contrato');

            // Verificar que realmente tenga el formato correcto
            if (!$actual || !preg_match('/^AICO-INT-[0-9]{4}$/', $actual)) {

                // Seguridad: volver a calcular el consecutivo
                $registros = reporte::orderBy('idReportes', 'DESC')->get();
                $ultimoNumero = 0;

                foreach ($registros as $r) {
                    $json = json_decode($r->Detalles_Generales, true);

                    if (!empty($json['Contrato']) && str_starts_with($json['Contrato'], 'AICO-INT-')) {
                        $n = intval(str_replace('AICO-INT-', '', $json['Contrato']));
                        if ($n > $ultimoNumero) $ultimoNumero = $n;
                        break;
                    }
                }

                $nuevo = "AICO-INT-" . str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);

                $OS->Contrato = $nuevo;

            } else {
                // Si el frontend envió un contrato válido, se utiliza ese
                $OS->Contrato = $actual;
            }
        } else {
            $OS->Contrato = $request->input('Contrato', $EsperaDato);
        }

        if($request->input('Proyecto')==null)
        {
            $OS->Proyecto_actividad = $EsperaDato;
        }else{
            $OS->Proyecto_actividad = $request->input('Proyecto');
        }

        if($request->input('Material')==null)
        {
            $OS->Material = $EsperaDato;
        }else{
            $OS->Material = $request->input('Material');
        }

        if($request->input('Plano_isometrico')==null)
        {
            $OS->Plano_isometrico = $EsperaDato;
        }else{
            $OS->Plano_isometrico = $request->input('Plano_isometrico');
        }


        // Validar que se ha enviado el archivo de factura
        if ($request->hasFile('OT_archivo') && $request->file('OT_archivo')->isValid()) {
            $pdf = $request->file('OT_archivo');
            // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Operativo/OT'))
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
            $newFileNameOT = $newNumber . '_' . $pdf->getClientOriginalName();
            // Guardar el archivo PDF en la carpeta "public/Operativo/OT"
            $pdfPath = $pdf->storeAs('Operativo/OT', $newFileNameOT, 'public');
            // Guardar la ruta en la base de datos
            $OS->OT_archivo = $pdfPath;
        } else {
            $OS->OT_archivo = $EsperaDato;
        }

        $OS->save();

        // Decodificar el input JSON en un arreglo
        $detallesOT = json_decode($request->input('dynamicTableData'), true);

        // Comprobar si el arreglo tiene elementos antes de continuar
        if (!empty($detallesOT)) {

            // Convertir el arreglo en una cadena JSON
            $detallesJSON = json_encode($detallesOT); 

            // Crear un nuevo registro en la tabla detallesOC
            $detallesOTModel = new Grupo_Juntas_Detalles_OS;

            // Asignar el idOT
            $detallesOTModel->idOrden_Servicio = $OS->idOrden_Servicio;

            // Guardar el JSON en la columna real del modelo
            $detallesOTModel->Juntas_grupo = $detallesJSON;

            // Guardar el objeto en la base de datos
            $detallesOTModel->save();
        } else {
            //Log::warning('No se han enviado detalles para guardar');
        }

        // Crear un nuevo registro en la firmantes_OS
        $firmantes_OS = new Firmantes_OS;

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
        $firmantes_OS->Firmas = json_encode($datosFirmas);
        $firmantes_OS->idOrden_Servicio = $OS->idOrden_Servicio;
        $firmantes_OS->save();

        return redirect()->route('OT_S.index')->with('success', 'Orden de servicio guardada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Orden_Servicio $orden_Servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $OT = Orden_Servicio::where('idOrden_Servicio', $id)->first();
        $detallesOS = Grupo_Juntas_Detalles_OS::where('idOrden_Servicio',$OT->idOrden_Servicio)->first(); 

        $idCliente = $OT->idClientes;
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $Cliente = clientes::where('idClientes', $idCliente)->first(); 
        $Nombre_Cliente = $Cliente->Cliente;

        $detallesOT = Grupo_Juntas_Detalles_OS::where('idOrden_Servicio',$OT->idOrden_Servicio)->first();

        // Decodificar JSON de la columna 'Detalles'
        $detallesOT = $detallesOT ? json_decode($detallesOT->Juntas_grupo, true) : [];

        $Firmantes = Firmantes_OS::where('idOrden_Servicio',$OT->idOrden_Servicio)->first();

        // Decodificar JSON de la columna 'Detalles'
        $Firmas = $Firmantes ? json_decode($Firmantes->Firmas, true) : [];
        // Obtener el numero de firmas
        $numFirmas = $Firmas['numFirmas'] ?? 1;

        //dd($numFirmas);

        return view('OT_S.edit', compact('id','OT','detallesOS','Cliente','idCliente','Nombre_Cliente','detallesOT','numFirmas','Firmas'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $OS = Orden_Servicio::find($id);
        //$EsperaDato ='ESPERA DE DATO';

        $OS->update([
            'Fecha' => $request->input('Fecha'),
            'Lugar' => $request->input('Lugar'),
            'Proyecto_actividad' => $request->input('Proyecto'),
            'Material' => $request->input('Material'),
            'Plano_isometrico' => $request->input('Plano_isometrico'),
        ]);


        // Eliminar el archivo PDF anterior si existe y se proporciona uno nuevo
        if ($request->hasFile('OT_archivo') && $request->file('OT_archivo')->isValid()) {
            // Obtener la ruta del archivo anterior desde la base de datos
            $rutaAnterior = $OS->OT_archivo;
            // Verificar si existe una ruta anterior y eliminar el archivo correspondiente
            if ($rutaAnterior && Storage::disk('public')->exists($rutaAnterior)) {
                Storage::disk('public')->delete($rutaAnterior);
            }
            // Guardar el nuevo archivo PDF
            $pdf = $request->file('OT_archivo');
            // Obtener el último número consecutivo
            $lastFile = collect(Storage::disk('public')->files('Operativo/OT'))
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
            $newFileNameOT = $newNumber . '_' . $pdf->getClientOriginalName();
            
            $pdfPath = $pdf->storeAs('Operativo/OT', $newFileNameOT, 'public');
            // Actualizar la ruta de la OT_archivo en la base de datos
            $OS->OT_archivo = $pdfPath;
            $OS->save();
        }

        // Reemplazar todos los detalles de la orden, incluso cuando se eliminaron todas las filas.
        $detallesOT = json_decode($request->input('dynamicTableData', '[]'), true) ?: [];
        $detallesOTModel = Grupo_Juntas_Detalles_OS::firstOrNew([
            'idOrden_Servicio' => $OS->idOrden_Servicio,
        ]);
        $detallesOTModel->Juntas_grupo = json_encode($detallesOT);
        $detallesOTModel->save();

        // Buscar las firmas por la orden de servicio; $id no es la clave primaria de Firmantes_OS.
        $firmantes_OS = Firmantes_OS::firstOrNew([
            'idOrden_Servicio' => $OS->idOrden_Servicio,
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
        $firmantes_OS->Firmas = json_encode($datosFirmas);
        $firmantes_OS->save();

        return redirect()->route('OT_S.index')->with('success', 'Orden de servicio guardada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Encontrar la OT
        $OT = Orden_Servicio::find($id);    
        if (!$OT) {
            return response()->json([
                'success' => false,
                'message' => 'Orden de Servicio no encontrada',
            ], 404);
        }

        // Encontrar las Firmas de la OT
        $FirmasOT = Firmantes_OS::where('idOrden_Servicio', $OT->idOrden_Servicio)->first();
        // Encontrar los detalles de la OT
        $detallesOT = Grupo_Juntas_Detalles_OS::where('idOrden_Servicio', $OT->idOrden_Servicio)->first();

        // Eliminar el archivo asociado desde storage/app/public/Ventas/OC.
        if ($OT->OT_archivo && Storage::disk('public')->exists($OT->OT_archivo)) {
            Storage::disk('public')->delete($OT->OT_archivo);
        }
        
        if($FirmasOT)
        {
            $FirmasOT->delete();
        }

        if($detallesOT)
        {
            $detallesOT->delete();
        }

        if($OT)
        {
            $OT->delete();
        }
        
         // Responder con éxito
        return response()->json(['success' => true, 'message' => 'Orden de Servicio eliminada exitosamente']);
    }

    /**
     * PDF de la orden de servicio de AICO
     */
    public function OT_S_PDF($id)
    {

    }
}
