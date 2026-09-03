<?php

namespace App\Http\Controllers\Clientes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\Admin\Usuario;
use App\Models\Reporte\reporte;
use App\Models\Formato\formato;
use App\Models\Clientes\clientes;
use App\Models\Reporte\ComentarioReporte;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Jobs\Procesamiento\GenerarReportePdfJob;

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
                ->select('r.idReportes', 'r.idPrueba_Aplica')
                ->firstOrFail();

            $nombreFormato = formato::whereKey(
                Prueba_Aplica::whereKey($reporte->idPrueba_Aplica)->value('idFormato')
            )->value('Nombre');

            $rutasPdf = [
                'FOR-PINS-04-01' => 'Reporte_FOR_PINS_04_01.PDF',
                'FOR-PINS-05-01' => 'Reporte_FOR_PINS_05_01.PDF',
                'FOR-PINS-06-01' => 'Reporte_FOR_PINS_06_01.PDF',
                'FOR-PINS-07-01' => 'Reporte_FOR_PINS_07_01.PDF',
                'FOR-PINS-08-01' => 'Reporte_FOR_PINS_08_01.PDF',
                'FOR-PINS-09-01' => 'Reporte_FOR_PINS_09_01.PDF',
                'FOR-PINS-10-01' => 'Reporte_FOR_PINS_10_01.PDF',
                'FOR-PINS-11-01' => 'Reporte_FOR_PINS_11_01.PDF',
                'FOR-PINS-12-01' => 'Reporte_FOR_PINS_12_01.PDF',
                'FOR-PINS-13-01' => 'Reporte_FOR_PINS_13_01.PDF',
                'FOR-PINS-14-01' => 'Reporte_FOR_PINS_14_01.PDF',
                'FOR-PINS-15-01' => 'Reporte_FOR_PINS_15_01.PDF',
                'FOR-PINS-16-01' => 'Reporte_FOR_PINS_16_01.PDF',
                'FOR-PINS-17-01' => 'Reporte_FOR_PINS_17_01.PDF',
                'FOR-PINS-18-01' => 'Reporte_FOR_PINS_18_01.PDF',
                'FOR-PINS-19-01' => 'Reporte_FOR_PINS_19_01.PDF',
                'FOR-PINS-20-01' => 'Reporte_FOR_PINS_20_01.PDF',
                'FOR-PINS-21-01' => 'Reporte_FOR_PINS_21_01.PDF',
                'FOR-PINS-22-01' => 'Reporte_FOR_PINS_22_01.PDF',
                'FOR-PINS-23-01' => 'Reporte_FOR_PINS_23_01.PDF',
                'FOR-PINS-24-01' => 'Reporte_FOR_PINS_24_01.PDF',
                'FOR-PINS-25-01' => 'Reporte_FOR_PINS_25_01.PDF',
                'FOR-PINS-03-02' => 'Reporte_FOR_PINS_03_02.PDF',
                'FOR-PINS-05-02' => 'Reporte_FOR_PINS_05_02.PDF',
                'FOR-PINS-11-02' => 'Reporte_FOR_PINS_11_02.PDF',
                'FOR-PINS-17-01_01' => 'Reporte_FOR_PINS_17_01_01.PDF',
                'FOR-03-PRO-INS-15' => 'Reporte_FOR_03_INS_15.PDF',
                'FOR-PIMP-02_B/03' => 'Reporte_FOR_PIMP_02_B_03.PDF',
                'FOR-PIMP-02_B/04' => 'Reporte_FOR_PIMP_02_B_04.PDF',
                'FOR-PIMP-05/01' => 'Reporte_FOR_PIMP_05_01.PDF',
                'FOR-PIMP-07_B/01' => 'Reporte_FOR_PIMP_07_B_01.PDF',
            ];

            if (isset($rutasPdf[$nombreFormato])) {
                $ruta = app('router')->getRoutes()->getByName($rutasPdf[$nombreFormato]);
                abort_unless($ruta, 404, 'Generador de PDF no encontrado');

                return app()->call([
                    $ruta->getController(),
                    $ruta->getActionMethod(),
                ], ['id' => (int) $reporte->idReportes]);
            }

            $formatosProcesados = [
                'FOR-PIMP-03_B/01' => '03_B_01',
                'FOR-PIMP-04/02' => '04_02',
                'FOR-PIMP-04/03' => '04_03',
                'FOR-PIMP-05_B/01' => '05_B_01',
                'FOR-PIMP-06_B/01' => '06_B_01',
            ];
            $formatoProcesado = $formatosProcesados[$nombreFormato] ?? null;
            $generador = $formatoProcesado ? GenerarReportePdfJob::FORMATOS[$formatoProcesado] ?? null : null;

            abort_unless($generador, 404, 'Formato de PDF no disponible');

            return app()->call([app($generador[0]), $generador[1]], [
                'id' => (int) $reporte->idReportes,
            ]);
        }

        public function guardarComentario(Request $request, $token, $idReporte)
        {
            try {
                if (!Auth::check()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Debes iniciar sesión para comentar.',
                    ], 401);
                }

                $request->validate([
                    'comentario' => 'required|string|max:2000',
                ]);

                $usuario = Auth::user();
                $comentario = $request->input('comentario');
                $idClientes = 0;
                $idUsuario = $usuario->id;
                $autor = $usuario->name;
                $email = $usuario->email;
                $tipoAutor = 'usuario';

                // Validar el token y obtener el cliente
                $cliente = clientes::where('portal_token', $token)->first();
                if (!$cliente) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token inválido o expirado. Token recibido: ' . $token,
                    ], 401);
                }

                // Validar que el cliente tiene acceso a este reporte
                $tieneAcceso = DB::table('lineal_ideal as li')
                    ->join('orden_servicio as os', 'os.idOrden_Servicio', '=', 'li.idOrden_Servicio')
                    ->where('li.idReportes', $idReporte)
                    ->where('os.idClientes', $cliente->idClientes)
                    ->exists();

                if (!$tieneAcceso) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes acceso a este reporte. Cliente: ' . $cliente->idClientes . ', Reporte: ' . $idReporte,
                    ], 403);
                }

                // Guardar datos del cliente y del usuario autenticado
                $idClientes = $cliente->idClientes;
                $autor = $usuario->name;
                $email = $usuario->email;
                $tipoAutor = 'usuario';

                // Guardar el comentario en el historial
                $comentarioNuevo = ComentarioReporte::create([
                    'idReportes' => $idReporte,
                    'comentario' => $comentario,
                    'autor' => $autor,
                    'email' => $email,
                    'tipo_autor' => $tipoAutor,
                    'idClientes' => $idClientes,
                    'idUsuario' => $idUsuario,
                ]);

                // Obtener todos los comentarios del reporte
                $reporte = reporte::findOrFail($idReporte);
                $comentarios = $reporte->comentariosHistorial;

                return response()->json([
                    'success' => true,
                    'message' => 'Comentario guardado correctamente.',
                    'comentario' => $comentarioNuevo,
                    'total_comentarios' => $comentarios->count(),
                ]);
            } catch (\Exception $e) {
                Log::error('Error al guardar comentario: ' . $e->getMessage(), [
                    'idReporte' => $idReporte,
                    'token' => $token,
                    'exception' => $e
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar el comentario: ' . $e->getMessage(),
                    'debug' => [
                        'token' => $token,
                        'idReporte' => $idReporte,
                        'line' => $e->getLine(),
                        'file' => $e->getFile()
                    ]
                ], 500);
            }
        }

        public function obtenerComentarios($token, $idReporte)
        {
            try {
                // Validar el token
                $cliente = clientes::where('portal_token', $token)->first();
                if (!$cliente) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token inválido o expirado.',
                    ], 401);
                }

                // Validar acceso al reporte
                $tieneAcceso = DB::table('lineal_ideal as li')
                    ->join('orden_servicio as os', 'os.idOrden_Servicio', '=', 'li.idOrden_Servicio')
                    ->where('li.idReportes', $idReporte)
                    ->where('os.idClientes', $cliente->idClientes)
                    ->exists();

                if (!$tieneAcceso) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No tienes acceso a este reporte.',
                    ], 403);
                }

                $reporte = reporte::findOrFail($idReporte);
                $comentarios = $reporte->comentariosHistorial;

                return response()->json([
                    'success' => true,
                    'comentarios' => $comentarios->map(function ($c) {
                        return [
                            'idComentario' => $c->idComentarios,
                            'comentario' => $c->comentario,
                            'autor' => $c->autor,
                            'tipo_autor' => $c->tipo_autor,
                            'fecha' => $c->created_at->format('d/m/Y H:i'),
                            'fecha_raw' => $c->created_at,
                        ];
                    }),
                ]);
            } catch (\Exception $e) {
                Log::error('Error al obtener comentarios: ' . $e->getMessage(), [
                    'idReporte' => $idReporte,
                    'token' => $token,
                    'exception' => $e
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al obtener los comentarios.',
                ], 500);
            }
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
        $clientes = clientes::find($id);

        if (!$clientes) {
            return redirect()
                ->route('clientes.index')
                ->with('error', 'Cliente no encontrado.');
        }

        $usuarioActual = Usuario::where('email', $clientes->Correo)
            ->where('rol', 'Cliente')
            ->first();

        $request->validate([
            'Cliente' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clientes', 'Cliente')->ignore($clientes->idClientes, 'idClientes'),
                Rule::unique('users', 'name')->ignore($usuarioActual?->id),
            ],
            'RFC' => 'nullable|string|max:255',
            'Telefono' => 'nullable|string|max:255',
            'Correo' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('clientes', 'Correo')->ignore($clientes->idClientes, 'idClientes'),
                Rule::unique('users', 'email')->ignore($usuarioActual?->id),
            ],
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'CuentaCliente' => 'required|string',

            'ContrasenaUsuario' => 'nullable|required_if:CuentaCliente,si|string|max:255',

            'RepetirContrasena' =>
            'nullable|required_if:CuentaCliente,si|string|max:255|same:ContrasenaUsuario',
        ]);

        $EsperaDato ='ESPERA DE DATO';

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

        if ($request->input('CuentaCliente') === 'si') {
            $Usuario = $usuarioActual ?? new Usuario;
            $Usuario->name = $request->input('Cliente');
            $Usuario->email = $request->input('Correo') ?? $EsperaDato;

            if ($request->filled('ContrasenaUsuario')) {
                $Usuario->password = Hash::make($request->input('ContrasenaUsuario'));
            }

            $Usuario->rol = 'Cliente';
            $Usuario->Estatus = 'ALTA';
            $Usuario->licencia_numero = $Usuario->licencia_numero ?? $EsperaDato;
            $Usuario->licencia_vencimiento = $Usuario->licencia_vencimiento ?? '2001-01-01';
            $Usuario->licencia_pdf = $Usuario->licencia_pdf ?? $EsperaDato;
            $Usuario->cv_pdf = $Usuario->cv_pdf ?? $EsperaDato;
            $Usuario->save();
        }

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
