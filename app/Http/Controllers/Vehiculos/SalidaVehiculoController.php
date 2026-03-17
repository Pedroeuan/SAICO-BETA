<?php

namespace App\Http\Controllers\Vehiculos;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use App\Models\User;
use App\Http\Requests\Vehiculos\SalidaVehiculoRequest;
use App\Models\Notificacion\Notificacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Vehiculos\FlujoVehiculosTracker;

class SalidaVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Admin y super administrador pueden ver todas las salidas.
        if ($this->puedeVerTodasLasSalidas()) {
            $salidas = SalidaVehiculo::query()
                ->select(['id', 'vehiculo_id', 'chofer_id', 'fecha_salida', 'estatus'])
                ->with(['vehiculo:id,placa', 'chofer:id,name'])
                ->latest('fecha_salida')
                ->get();
        } else {
            $salidas = SalidaVehiculo::where('chofer_id', auth()->id())
                ->select(['id', 'vehiculo_id', 'chofer_id', 'fecha_salida', 'estatus'])
                ->with(['vehiculo:id,placa', 'chofer:id,name'])
                ->latest('fecha_salida')
                ->get();
        }
        $metricas = $this->metricas();
        $this->crearNotificacionesLicencias();
        return view('salidas.index', compact('salidas','metricas'));
    }

    public function metricas(){
        // Filtrar por usuario si no es admin/super.
        $query = SalidaVehiculo::query();
        if (!$this->puedeVerTodasLasSalidas()) {
            $query->where('chofer_id', auth()->id());
        }

        $totalSalidas = (clone $query)->count();
        $salidasActivas = (clone $query)->where('estatus', 'activo')->count();
        $tiempoPromedio = (clone $query)->whereNotNull('duracion_minutos')->avg('duracion_minutos');
        $vehiculoMasUsado = (clone $query)->selectRaw('vehiculo_id, COUNT(*) as total')->groupBy('vehiculo_id')->orderByDesc('total')->with('vehiculo')->first();
        $choferMasActivo = (clone $query)->selectRaw('chofer_id, COUNT(*) as total')->groupBy('chofer_id')->orderByDesc('total')->with('chofer')->first();

        return compact(
            'totalSalidas',
            'salidasActivas',
            'tiempoPromedio',
            'vehiculoMasUsado',
            'choferMasActivo'
        );
    }

    private function puedeVerTodasLasSalidas(): bool
    {
        $rol = Str::of((string) auth()->user()->rol)
            ->trim()
            ->lower()
            ->replace('_', ' ')
            ->replace('-', ' ')
            ->squish()
            ->value();

        $rolesAdmin = [
            'admin',
            'administrador',
            'super admin',
            'super administrador',
            'superadministrador',
        ];

        return in_array($rol, $rolesAdmin, true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarioLogueado = Auth::user();
        if ($usuarioLogueado) {
            FlujoVehiculosTracker::track(
                evento: 'inicio_form_salida',
                userId: (int) $usuarioLogueado->id,
                rol: (string) ($usuarioLogueado->rol ?? ''),
                paso: 'form_create',
                pantalla: 'salidas.create'
            );
        }

        $vehiculos = Vehiculo::where('estatus', 'disponible')
            ->where('documentacion_estatus', 'completa')
            ->whereDoesntHave('salidaActiva')
            ->select(['id', 'placa', 'marca'])
            ->get();
        
        $usuarios = User::whereDoesntHave('salidasComoChofer',function ($q){
            $q->where('estatus','activo');
        })
        ->select(['id', 'name', 'rol', 'licencia_vencimiento'])
        ->orderBy('name')
        ->get();

        return view('salidas.create', compact('vehiculos', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalidaVehiculoRequest $request)
{
    $usuarioLogueado = Auth::user();
    $vehiculo = Vehiculo::findOrFail($request->vehiculo_id);
    $chofer = User::findOrFail($request->chofer_id);
    $ESPERADATO= 'ESPERA DE DATO';
    //VALIDACIONES
    // Vehículo con salida activa
    if ($vehiculo->estatus !== 'disponible') {
        return back()->withInput()->with('error', 'El vehículo no está disponible.');
    }

    // Documentación del vehículo
    if ($vehiculo->documentacion_estatus === 'vencida') {
        return back()->withInput()->with('error', 'El vehículo tiene documentación vencida.');
    }

    if ($vehiculo->documentacion_estatus === 'incompleta') {
        return back()->withInput()->with('error', 'El vehículo no tiene documentación completa.');
    }

    // Chofer con salida activa
    if (SalidaVehiculo::where('chofer_id', $chofer->id)->where('estatus', 'activo')->exists())
        {
        return back()->withInput()->with('error', 'El chofer ya tiene un vehículo asignado.');
    }

    // Validar licencia del chofer
    if (!$chofer->licencia_numero) {
        return back()->withInput()->with('error', 'El chofer no tiene licencia registrada.');
    }

    $fechaSalida = $request->filled('fecha_salida')
        ? Carbon::parse($request->fecha_salida)
        : now();
    $licenciaExpirada = $chofer->licencia_vencimiento
        ? Carbon::parse($chofer->licencia_vencimiento)->toDateString() < $fechaSalida->toDateString()
        : true;

    if ($licenciaExpirada) {
        return back()->withInput()->with('error', 'La licencia del chofer está vencida.');
    }

    // 5️Validar foto (no bloquear la creación, solo advertir)
    if (!$chofer->foto) {
        // Solo avisamos, no impedimos la creación de la salida
        session()->flash('warning', 'El chofer no tiene una foto registrada. Se recomienda agregarla en su perfil.');
    }

    //TRANSACCIÓN SEGURA
    $salidaCreada = null;
    DB::transaction(function () use ($request, $vehiculo, $chofer, $usuarioLogueado,$fechaSalida,$ESPERADATO, &$salidaCreada) {
    //generacion de folio para evitar dependencia del formulario 
    $numReporte = 'SV-' . now()->format('Ymd-His') . '-' . strtoupper(\Illuminate\Support\Str::random(4));

        $salida = SalidaVehiculo::create([
            'vehiculo_id' => $vehiculo->id,
            'chofer_id' => $chofer->id,
            'solicitado_por' => $request->input('solicitado_por', $usuarioLogueado->id),
            'creado_por' => $usuarioLogueado->id,
            'finalizado_por' => null, //aun no finalizado
            'fecha_salida' => $fechaSalida,
            'fecha_regreso' => NULL,
            'duracion_minutos' => NULL,
            'motivo' => $request->input('motivo') ?? $ESPERADATO,
            'estatus' => 'activo',
            'Num_Reporte' => $numReporte, 
        ]);

        $vehiculo->update([
            'estatus' => 'ocupado']);
        $salidaCreada = $salida;
    });

    if ($salidaCreada && $usuarioLogueado) {
        FlujoVehiculosTracker::track(
            evento: 'salida_creada',
            salidaVehiculoId: (int) $salidaCreada->id,
            userId: (int) $usuarioLogueado->id,
            rol: (string) ($usuarioLogueado->rol ?? ''),
            paso: 'store_ok',
            pantalla: 'salidas.store',
            metadata: [
                'chofer_id' => (int) $chofer->id,
                'vehiculo_id' => (int) $vehiculo->id,
            ]
        );
    }

    return redirect()->route('salidas.index')->with('success', 'Salida registrada correctamente.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function finalizar($id)
   {
     $salida = SalidaVehiculo::findOrFail($id);

    if ($salida->estatus === 'finalizado') {
        return back()->with('error', 'Esta salida ya fue finalizada.');
    }

    $duracion = $salida->fecha_salida->diffInMinutes(now());

    DB::transaction(function () use ($salida, $duracion) {

        $salida->update([
            'fecha_regreso' => now(),
            'estatus' => 'finalizado',
            'finalizado_por' => Auth::id(),
            'duracion_minutos' => $duracion
        ]);

        $salida->vehiculo->update([
            'estatus' => 'disponible']);
    });

    $usuarioLogueado = Auth::user();
    if ($usuarioLogueado) {
        FlujoVehiculosTracker::track(
            evento: 'salida_finalizada',
            salidaVehiculoId: (int) $salida->id,
            userId: (int) $usuarioLogueado->id,
            rol: (string) ($usuarioLogueado->rol ?? ''),
            paso: 'finalizar_ok',
            pantalla: 'salidas.finalizar',
            metadata: [
                'duracion_minutos' => (int) $duracion,
            ]
        );
    }

    return redirect()->route('salidas.index')->with('success', 'Salida finalizada correctamente.');
   }

    private function crearNotificacionesLicencias(): void
    {
        $usuarioActual = Auth::user();
        if (!$usuarioActual) {
            return;
        }

        $hoy = Carbon::today();
        $limite = Carbon::today()->addDays(15);

        // 1) Notificación para que el propio usuario vea su licencia.
        if ($usuarioActual->licencia_vencimiento) {
            $fechaLicencia = Carbon::parse($usuarioActual->licencia_vencimiento)->startOfDay();
            $dias = $hoy->diffInDays($fechaLicencia, false);

            if ($dias <= 15) {
                $mensajeCorto = $dias < 0
                    ? 'Tu licencia está vencida'
                    : ($dias === 0 ? 'Tu licencia vence hoy' : "Tu licencia vence en {$dias} días");
                $mensajeLargo = "Revisa tu licencia de conducir. Vencimiento: {$fechaLicencia->format('d/m/Y')}.";
                $this->crearNotificacionSiNoExiste(
                    $usuarioActual->id,
                    $mensajeCorto,
                    $mensajeLargo,
                    route('salidas.index')
                );
            }
        }

        // 2) Notificaciones para admin sobre licencias de usuarios (vencidas o <= 15 días).
        $admins = User::whereIn('rol', ['Administrador', 'Super Administrador', 'SuperAdministrador'])->pluck('id');
        if ($admins->isEmpty()) {
            return;
        }

        $usuariosConLicencia = User::whereNotNull('licencia_vencimiento')
            ->whereDate('licencia_vencimiento', '<=', $limite->toDateString())
            ->get(['id', 'name', 'licencia_vencimiento']);

        foreach ($usuariosConLicencia as $usuario) {
            $fechaLicencia = Carbon::parse($usuario->licencia_vencimiento)->startOfDay();
            $dias = $hoy->diffInDays($fechaLicencia, false);

            $mensajeCorto = $dias < 0
                ? "Licencia vencida: {$usuario->name}"
                : ($dias === 0
                    ? "Licencia vence hoy: {$usuario->name}"
                    : "Licencia vence en {$dias} días: {$usuario->name}");
            $mensajeLargo = "La licencia de {$usuario->name} vence el {$fechaLicencia->format('d/m/Y')}.";
            $url = url("/edicion/editusuarios/{$usuario->id}");

            foreach ($admins as $adminId) {
                $this->crearNotificacionSiNoExiste($adminId, $mensajeCorto, $mensajeLargo, $url);
            }
        }
    }

    private function crearNotificacionSiNoExiste(int $userId, string $mensajeCorto, string $mensajeLargo, string $url): void
    {
        $existe = Notificacion::where('users_id', $userId)
            ->where('Mensaje_Corto', $mensajeCorto)
            ->where('Mensaje_Largo', $mensajeLargo)
            ->first();

        if (!$existe) {
            Notificacion::create([
                'users_id' => $userId,
                'Mensaje_Corto' => $mensajeCorto,
                'Mensaje_Largo' => $mensajeLargo,
                'url' => $url,
                'leida' => 0,
            ]);
        }
    }
}
