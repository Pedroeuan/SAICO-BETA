<?php

namespace App\Http\Controllers\OrdenServicio;

use App\Models\OrdenServicio\Orden_Servicio; 
use App\Models\OrdenServicio\Grupo_Juntas_Detalles_OS;
use App\Models\Reporte\reporte;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;    
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\Clientes\clientes;

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

            Log::info('***********************');
            Log::info('clienteNombre: ', ['clienteNombre' => $clienteNombre]);


        if (!empty($clienteNombre)) {
            $cliente = clientes::where('Cliente', trim($clienteNombre))->first();
            Log::info('cliente: ', ['cliente' => $cliente]);
            if ($cliente) {
                $OS->idClientes = $cliente->idClientes;
                Log::info('$cliente->idClientes: ', ['$cliente->idClientes' => $cliente->idClientes]);
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orden_Servicio $orden_Servicio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orden_Servicio $orden_Servicio)
    {
        //
    }
}
