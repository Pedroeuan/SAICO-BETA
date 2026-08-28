<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Clientes\clientes;

class ClientesController extends Controller
{
        public function Portal_index($token)
        {
            // Buscar el cliente mediante el token
            $cliente = clientes::where('portal_token', $token)->first();

            // Si el token no existe
            if (!$cliente) {
                abort(404);
            }

            // Obtener los contratos únicos de las órdenes
            // pertenecientes SOLAMENTE a este cliente
            $contratos = DB::table('orden_servicio')
                ->where('idClientes', $cliente->idClientes)
                ->whereNotNull('Contrato')
                ->where('Contrato', '!=', '')
                ->select(
                    'Contrato',
                    'Proyecto_actividad',
                    'idOrden_Servicio'
                )
                ->distinct()
                ->orderBy('Contrato')
                ->get()
                ->groupBy('Contrato');
            //dd($contratos);
            return view('Reportes_publicos.index', compact('cliente', 'contratos'));
        }

        public function reportes_clientes($token, $idOrden_Servicio)
        {
            // Buscar el cliente mediante el token
            $cliente = clientes::where('portal_token', $token)->first();

            // Si el token no existe
            if (!$cliente) {
                abort(404);
            }

            $orden = DB::table('orden_servicio')
                ->where('idOrden_Servicio', $idOrden_Servicio)
                ->where('idClientes', $cliente->idClientes)
                ->first();

            if (!$orden) {
                abort(404);
            }

            $lineal_ideal = DB::table('lineal_ideal')
                ->where('idOrden_Servicio', $idOrden_Servicio)
                ->get();

            $reportes = DB::table('lineal_ideal as li')
                ->join('Reportes as r', 'r.idReportes', '=', 'li.idReportes')
                ->where('li.idOrden_Servicio', $idOrden_Servicio)
                ->select('r.idReportes', 'r.Detalles_Generales', 'r.Estatus')
                ->distinct()
                ->get()
                ->each(function ($reporte) {
                    $reporte->detalles = json_decode($reporte->Detalles_Generales, true) ?: [];
                });

            return view('Reportes_publicos.Reportes', compact('cliente', 'orden', 'reportes'));
        }

        public function pdf_reporte($token, $idOrden_Servicio, $idReporte)
        {
            $cliente = clientes::where('portal_token', $token)->firstOrFail();

            $reporte = DB::table('lineal_ideal as li')
                ->join('Reportes as r', 'r.idReportes', '=', 'li.idReportes')
                ->join('orden_servicio as os', 'os.idOrden_Servicio', '=', 'li.idOrden_Servicio')
                ->where('li.idOrden_Servicio', $idOrden_Servicio)
                ->where('li.idReportes', $idReporte)
                ->where('os.idClientes', $cliente->idClientes)
                ->select('r.Datos_Equipo')
                ->firstOrFail();

            $datosEquipo = json_decode($reporte->Datos_Equipo, true) ?: [];
            $rutaPdf = $datosEquipo['PDF_UNIFICADO'] ?? null;

            abort_unless($rutaPdf, 404, 'PDF no encontrado');

            $ruta = trim(str_replace('\\', '/', $rutaPdf));
            $ruta = preg_replace('#^/?(storage|public)/#', '', $ruta);
            $ruta = ltrim($ruta, '/');

            $rutasPdf = [
                storage_path('app/public/' . $ruta),
                storage_path($ruta),
                public_path($ruta),
                public_path('storage/' . $ruta),
                public_path('public/' . $ruta),
            ];

            $rutaPdf = collect(array_unique($rutasPdf))
                ->first(fn ($rutaCandidata) => is_file($rutaCandidata));

            abort_unless($rutaPdf, 404, 'Archivo PDF no encontrado');

            return response()->file($rutaPdf, ['Content-Type' => 'application/pdf']);
        }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtén todos los clientes excepto el cliente "POR DEFINIR"
        $clientes = clientes::where('Cliente', '!=', 'POR DEFINIR')->get();

        return view('Clientes.index', compact('clientes'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Cliente' => 'required|string|max:255',
            'RFC' => 'nullable|string|max:255',
            'Telefono' => 'nullable|string|max:255',
            'Correo' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $clientes = new clientes;

        $EsperaDato = 'ESPERA DE DATO';


        /*
        |--------------------------------------------------------------------------
        | DATOS DEL CLIENTE
        |--------------------------------------------------------------------------
        */

        if ($request->input('Cliente') == null) {

            $clientes->Cliente = $EsperaDato;

        } else {

            $clientes->Cliente = $request->input('Cliente');

        }


        if ($request->input('RFC') == null) {

            $clientes->RFC = $EsperaDato;

        } else {

            $clientes->RFC = $request->input('RFC');

        }


        if ($request->input('Telefono') == null) {

            $clientes->Telefono = $EsperaDato;

        } else {

            $clientes->Telefono = $request->input('Telefono');

        }


        if ($request->input('Correo') == null) {

            $clientes->Correo = $EsperaDato;

        } else {

            $clientes->Correo = $request->input('Correo');

        }


        /*
        |--------------------------------------------------------------------------
        | GENERAR TOKEN DEL PORTAL
        |--------------------------------------------------------------------------
        */

        $clientes->portal_token = (string) Str::uuid();


        /*
        |--------------------------------------------------------------------------
        | GUARDAR LOGO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            $rutaLogo = $request->file('logo')->store('clientes', 'public');

            $clientes->logo = $rutaLogo;

        }


        /*
        |--------------------------------------------------------------------------
        | GUARDAR CLIENTE
        |--------------------------------------------------------------------------
        */

        $clientes->save();


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA PARA AJAX
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([
                'success' => true,
                'message' => 'Cliente guardado correctamente.',
                'idCliente' => $clientes->idClientes,
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | RESPUESTA NORMAL
        |--------------------------------------------------------------------------
        */

        return redirect()->route('clientes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(clientes $clientes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $clientes = clientes::where('idClientes', $id)->first();

        return view('Clientes.edit', compact('id','clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Cliente' => 'required|string|max:255',
            'RFC' => 'nullable|string|max:255',
            'Telefono' => 'nullable|string|max:255',
            'Correo' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        /*
        |--------------------------------------------------------------------------
        | OBTENER CLIENTE
        |--------------------------------------------------------------------------
        */

        $clientes = clientes::find($id);


        if (!$clientes) {

            return redirect()
                ->route('clientes.index')
                ->with('error', 'Cliente no encontrado.');

        }


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR DATOS
        |--------------------------------------------------------------------------
        */

        $clientes->Cliente = $request->input('Cliente');
        $clientes->RFC = $request->input('RFC');
        $clientes->Telefono = $request->input('Telefono');
        $clientes->Correo = $request->input('Correo');


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR LOGO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            /*
            | Eliminar logo anterior
            */

            if ($clientes->logo) {

                Storage::disk('public')->delete($clientes->logo);

            }


            /*
            | Guardar nuevo logo
            */

            $rutaLogo = $request->file('logo')->store('clientes', 'public');

            $clientes->logo = $rutaLogo;

        }


        /*
        |--------------------------------------------------------------------------
        | ASEGURAR TOKEN
        |--------------------------------------------------------------------------
        |
        | Esto es importante para clientes antiguos.
        |
        | Si un cliente fue creado antes de implementar
        | portal_token, aquí se le genera automáticamente.
        |
        */

        if (empty($clientes->portal_token)) {

            $clientes->portal_token = (string) Str::uuid();

        }


        /*
        |--------------------------------------------------------------------------
        | GUARDAR
        |--------------------------------------------------------------------------
        */

        $clientes->save();


        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $clientes = clientes::find($id);
    
        if ($clientes) {
            $clientes->delete();
            return response()->json(['success' => true, 'message' => 'Cliente eliminado correctamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'No se pudo encontrar el cliente.']);
        }
    }
}
