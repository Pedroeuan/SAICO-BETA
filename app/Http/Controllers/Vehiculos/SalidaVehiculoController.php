<?php

namespace App\Http\Controllers\Vehiculos;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Vehiculo;
use App\Models\User;
use App\Http\Requests\Vehiculos\SalidaVehiculoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SalidaVehiculoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salidas = SalidaVehiculo::with(['vehiculo', 'checklist'])->get();
        return view('salidas.index', compact('salidas'));
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
        //validar licencia antes de selecionar vehiculo
        $user = auth::user();
        if (!$user->licencia_numero || !$user->licencia_vencimiento || now()->gt($user->licencia_vencimiento)) 
            {
            return redirect()->back()->with('error', 'No puede generar salida. Licencia inválida o vencida.');
            }
             
            // se bloquea por doble salida activa
        if (SalidaVehiculo::where('vehiculo_id',$request->vehiculo_id)->where('estatus','activo')->exists()){
            return redirect()->back()->with('error','Este vehículo ya se enecuentra en uso.');
        }         
            {
                return redirect()->back()->with('error', 'Este vehículo ya se encuentra en uso.');
                }
            
                //bloquear usuario con salida activs
        if (SalidaVehiculo::where('user_id', $request->user_id)->where('estatus', 'activo')->exists()) 
            {
                return redirect()->back()->with('error', 'Este usuario ya tiene un vehículo asignado.');
            }
        
        //bloquear salida chofer si tiene salida
        if(SalidaVehiculo::where('chofer_id',$request->chofer_id)->where('estatus','activo')->exists()){
            return redirect()->back()->with('error', 'Este chofer ya tiene un vehículo asignado.');
        }

        //calidacion del usuario que no tenga salida activa
        $choferOcupado = SalidaVehiculo::where('chofer_id',$request->chofer_id)->where('estatus','activo')->exists();

        if($choferOcupado){
            return back()->with('error','El chofer ya tiene un vehiculo asignado');
        }

                    
        DB::transaction(function () use ($request) 
        {
            $salida = SalidaVehiculo::create([
                    'vehiculo_id' => $request->vehiculo_id,
                    'user_id' => $request->id(),    
                    'fecha_salida' => now(),
                    'estatus' => 'activo'
                    ]);
                    
                $salida->vehiculo->update(['estatus' => 'ocupado']);
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

    $salida->update(['fecha_regreso' => now(), 'estatus' => 'finalizado']);
    
    Vehiculo::where('id', $salida->vehiculo_id)->update(['estatus' => 'disponible']);
    

    return redirect() ->route('salidas.index')->with('success', 'Salida finalizada');
    
   }
}

