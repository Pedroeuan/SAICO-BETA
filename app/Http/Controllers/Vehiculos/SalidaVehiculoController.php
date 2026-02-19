<?php

namespace App\Http\Controllers\Vehiculos;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use App\Models\User;
use App\Http\Requests\Vehiculos\SalidaVehiculoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SalidaVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Admin y super administrador pueden ver todas las salidas.
        if ($this->puedeVerTodasLasSalidas()) {
            $salidas = SalidaVehiculo::with(['vehiculo', 'chofer'])->get();
        } else {
            $salidas = SalidaVehiculo::where('chofer_id', auth()->id())
                ->with(['vehiculo', 'chofer'])->get();
        }
        $metricas = $this->metricas();
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
        $vehiculos=Vehiculo::where('estatus','disponible')->whereDoesntHave('salidaActiva')->get();
        
        $usuarios = User::whereDoesntHave('salidasComoChofer',function ($q){
            $q->where('estatus','activo');
        })
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

    if ($chofer->licencia_estatus === 'vencida') {
        return back()->withInput()->with('error', 'La licencia del chofer está vencida.');
    }

    // 5️Validar foto (no bloquear la creación, solo advertir)
    if (!$chofer->foto) {
        // Solo avisamos, no impedimos la creación de la salida
        session()->flash('warning', 'El chofer no tiene una foto registrada. Se recomienda agregarla en su perfil.');
    }

    //TRANSACCIÓN SEGURA

    DB::transaction(function () use ($request, $vehiculo, $chofer, $usuarioLogueado) {

        $salida = SalidaVehiculo::create([
            'vehiculo_id' => $vehiculo->id,
            'chofer_id' => $chofer->id,
            'solicitado_por' => $usuarioLogueado->id,
            'creado_por' => $usuarioLogueado->id,
            'fecha_salida' => now(),
            'estatus' => 'activo'
        ]);

        $vehiculo->update([
            'estatus' => 'ocupado']);
    });

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

    return redirect()->route('salidas.index')->with('success', 'Salida finalizada correctamente.');
   }
}
