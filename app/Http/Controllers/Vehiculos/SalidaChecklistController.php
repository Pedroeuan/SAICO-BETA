<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Checklist\SalidaChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class SalidaChecklistController extends Controller
{
    // CHECKLIST DE SALIDA
    public function create(SalidaVehiculo $salida)
    {
        if ($salida->checklistSalida) {
            return redirect()->route('salidas.index')->with('error', 'Este vehiculo ya tiene checklist de salida');
        }
        return view('salidas.checklist.salida', compact('salida'));
    }

    public function store(Request $request, SalidaVehiculo $salida)
    {
        $request->validate([
            'nivel_gasolina'  => 'required|string',
            'kilometraje'     => 'required|integer|min:0',
            'limpio_exterior' => 'required|in:0,1',
            'limpio_interior' => 'required|in:0,1',
            'observaciones'   => 'nullable|string|max:500',
            'herramientas'    => 'required|array',
            'evidencias' => 'required|array|min:5',
            'evidencias.*' => 'image|max:5120',

        ]);

        DB::transaction(function () use ($request, $salida) {

            //  Crear checklist de salida
            $checklist = SalidaChecklist::create([
                'salida_vehiculo_id' => $salida->id,
                'tipo'               => 'salida',
            ]);

            //  Guardar condición general
            $checklist->condicion()->create([
                'nivel_gasolina'  => $request->nivel_gasolina,
                'kilometraje'     => $request->kilometraje,
                'limpio_exterior' => $request->limpio_exterior,
                'limpio_interior' => $request->limpio_interior,
                'observaciones'   => $request->observaciones,
            ]);

            if ($request->has('documentos')) {
                foreach ($request->documentos as $documento => $estatus) {
                    $checklist->documentos()->create(['documento' => $documento, 'estatus' => $estatus,]);
                }
            }

            //  Guardar herramientas
            foreach ($request->herramientas as $herramienta => $disponible) {
                $checklist->herramientas()->create(['herramienta' => $herramienta, 'disponible'  => $disponible,]);
            }
            foreach ($request->file('evidencias') as $foto) {
                $ruta = $foto->store('checklists/salida', 'public');
                $checklist->evidencias()->create(['foto' => $ruta]);
            }
        });
        return redirect()->route('salidas.index')->with('success', 'Checklist de salida registrado correctamente');
    }


    // CHECKLIST DE ENTRADA (HISTORIAL NO PERMITE EDITAR)
    public function createEntrada(SalidaVehiculo $salida)
    {
        if (!$salida->checklistSalida) {
            return redirect()->route('salidas.index')->with('error', 'No se puede registrar entrada sin checklist de salida');
        }
        if ($salida->checklistEntrada) {
            return redirect()->route('salidas.index')->with('error', 'Este vehículo ya tiene checklist de entrada');
        }
        return view('salidas.checklist.entrada', compact('salida'));
    }

    public function storeEntrada(Request $request, SalidaVehiculo $salida)
    {
        if ($salida->checklistEntrada) {
            return redirect()->route('salidas.index')->with('error', 'Este vehículo ya tiene checklist de entrada');
        }
        if ($salida->estatus === 'finalizado') {
            return redirect()->route('salidas.index')->with('error', 'Esta salida ya fue finalizada');
        }

        // no se puede finalizar dos veces 
    if($salida->estatus==='finalizado'){
    return redirect()->route('salidas.index')->with('erro','Esta salida ya fue finalizada');
   }
   // validar kilometraje
   if ($request->kilometraje <=$salida->kilometraje_inicial){
    return back()->with('error','El kilometraje final debe ser mayor al inical');
   }

        $checklistSalida = $salida->checklistSalida;

        if (!$checklistSalida || !$checklistSalida->condicion) {
            return redirect()->route('salidas.index')->with('error', 'El checklist de salida no tiene condición registrada');
        }

        $kmSalida = $checklistSalida->condicion->kilometraje;
        $request->validate([
            'nivel_gasolina'  => 'required|string',
            'kilometraje'     => "required|integer|min:$kmSalida",
            'limpio_exterior' => 'required|in:0,1',
            'limpio_interior' => 'required|in:0,1',
            'observaciones'   => 'nullable|string|max:500',
            'evidencias' => 'required|array|min:5',
            'evidencias.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        DB::transaction(function () use ($request, $salida) {

            $checklist = SalidaChecklist::create([
                'salida_vehiculo_id' => $salida->id,
                'tipo'               => 'entrada',
            ]);

            $checklist->condicion()->create([
                'nivel_gasolina'  => $request->nivel_gasolina,
                'kilometraje'     => $request->kilometraje,
                'limpio_exterior' => $request->limpio_exterior,
                'limpio_interior' => $request->limpio_interior,
                'observaciones'   => $request->observaciones,
            ]);

            foreach ($request->file('evidencias') as $foto) {
                $ruta = $foto->store('checklists/entrada', 'public');

                $checklist->evidencias()->create(['foto' => $ruta]);
            }


            $salida->update(['fecha_regreso' => now(), 'estatus' => 'finalizado',]);
            $salida->vehiculo->update(['estatus' => 'disponible']);
        });

        return redirect()->route('salidas.index')->with('success', 'Checklist de entrada registrado correctamente');
    }

    public function show(SalidaVehiculo $salida, $tipo)
    {
        $checklist = $salida->checklist()->where('tipo', $tipo)->with(['condicion', 'documentos', 'herramientas', 'evidencias'])->firstOrFail();
        return view('salidas.checklist.show', compact('salida', 'checklist', 'tipo'));
    }
    //para generar pdf
    public function pdf(SalidaVehiculo $salida)
    {
        $salida->load([
                'vehiculo',
                'chofer',
                'checklistSalida.condicion',
                'checklistSalida.documentos',
                'checklistSalida.herramientas',
                'checklistEntrada.condicion',
                'checklistEntrada.evidencias',
                'checklistSalida.evidencias',
            ]);

        return Pdf::loadView(
            'salidas.checklist.pdf_unificado',
            [
                'salida' => $salida,
                'checklistSalida' => $salida->checklistSalida,
                'checklistEntrada' => $salida->checklistEntrada
            ]
        )->stream();
    }
}