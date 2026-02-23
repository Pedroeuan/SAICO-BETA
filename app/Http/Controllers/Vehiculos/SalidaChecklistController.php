<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Models\Vehiculos\Checklist\SalidaChecklist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class SalidaChecklistController extends Controller
{
    // CHECKLIST DE SALIDA
    public function create(SalidaVehiculo $salida)
    {
        //dd($salida);
        if ($salida->checklistSalida) {
            return redirect()->route('salidas.index')->with('error', 'Este vehiculo ya tiene checklist de salida');
        }
        // vlidacion
        $chofer = $salida->chofer;

        $licenciaVigente = false;

        if ($chofer && $chofer->licencia_vencimiento) {
            $licenciaVigente = Carbon::parse($chofer->licencia_vencimiento)->endOfDay()->gte(now());
        }

        // estados de documentación del vehículo
        $vehiculo = $salida->vehiculo;
        $tarjetaVigente = false;
        $polizaVigente = false;
        if ($vehiculo) {
            if ($vehiculo->tarjeta_circulacion_vencimiento) {
                $tarjetaVigente = Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->endOfDay()->gte(now());
            }
            if ($vehiculo->poliza_seguro_vencimiento) {
                $polizaVigente = Carbon::parse($vehiculo->poliza_seguro_vencimiento)->endOfDay()->gte(now());
            }
        }

        // valores por defecto desde la última entrada registrada para este vehículo
        $ultimaCondicion = SalidaChecklist::whereHas('salida', function($q) use ($salida){
            $q->where('vehiculo_id', $salida->vehiculo_id);
        })->where('tipo', 'entrada')->with('condicion')->orderByDesc('id')->first();

        $defaultNivel = null;
        $defaultKilometraje = null;
        if ($ultimaCondicion && $ultimaCondicion->condicion) {
            $defaultNivel = $ultimaCondicion->condicion->nivel_gasolina;
            $defaultKilometraje = $ultimaCondicion->condicion->kilometraje;
        }

        return view('salidas.checklist.salida', compact('salida', 'licenciaVigente', 'tarjetaVigente', 'polizaVigente', 'defaultNivel', 'defaultKilometraje'));
    }

    public function store(Request $request, SalidaVehiculo $salida)
    {
        $request->validate([
            'nivel_gasolina'  => 'required|string',
            'kilometraje'     => 'required|integer|min:0',
            'limpio_exterior' => 'nullable|in:0,1',
            'limpio_interior' => 'nullable|in:0,1',
            'observaciones'   => 'nullable|string|max:500',
            'herramientas'    => 'nullable|array',
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
                'limpio_exterior' => $request->input('limpio_exterior', 0),
                'limpio_interior' => $request->input('limpio_interior', 0),
                'observaciones'   => $request->observaciones,
            ]);

            // Documentos: determinar automáticamente a partir del chofer y vehículo
            $chofer = $salida->chofer;
            $vehiculo = $salida->vehiculo;

            $licenciaEstatus = 'vencido';
            if ($chofer && $chofer->licencia_vencimiento && Carbon::parse($chofer->licencia_vencimiento)->endOfDay()->gte(now())) {
                $licenciaEstatus = 'ok';
            }

            $tarjetaEstatus = 'vencido';
            if ($vehiculo && $vehiculo->tarjeta_circulacion_vencimiento && Carbon::parse($vehiculo->tarjeta_circulacion_vencimiento)->endOfDay()->gte(now())) {
                $tarjetaEstatus = 'ok';
            }

            $polizaEstatus = 'vencido';
            if ($vehiculo && $vehiculo->poliza_seguro_vencimiento && Carbon::parse($vehiculo->poliza_seguro_vencimiento)->endOfDay()->gte(now())) {
                $polizaEstatus = 'ok';
            }

            $autoDocs = [
                'licencia_conducir' => $licenciaEstatus,
                'tarjeta_circulacion' => $tarjetaEstatus,
                'poliza_seguro' => $polizaEstatus,
            ];

            foreach ($autoDocs as $documento => $estatus) {
                $checklist->documentos()->create(['documento' => $documento, 'estatus' => $estatus]);
            }

            //  Guardar herramientas (incluir no marcados como 0)
            $herramientasList = [
                'llantas', 'extintor', 'cables_corriente', 'gato_hidraulico', 'llave_cruz', 'llanta_refaccion'
            ];
            foreach ($herramientasList as $herramienta) {
                $disponible = $request->input("herramientas.$herramienta", 0);
                $checklist->herramientas()->create(['herramienta' => $herramienta, 'disponible'  => $disponible]);
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

        // Validar contra el kilometraje real del checklist de salida.
        $checklistSalida = $salida->checklistSalida;
        if (!$checklistSalida || !$checklistSalida->condicion) {
            return redirect()->route('salidas.index')->with('error', 'El checklist de salida no tiene condición registrada');
        }
        $kmSalida = (int) $checklistSalida->condicion->kilometraje;

        $request->validate([
            'nivel_gasolina'  => 'required|string',
            'kilometraje'     => "required|integer|gt:$kmSalida",
            'limpio_exterior' => 'nullable|in:0,1',
            'limpio_interior' => 'nullable|in:0,1',
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
                'limpio_exterior' => $request->input('limpio_exterior', 0),
                'limpio_interior' => $request->input('limpio_interior', 0),
                'observaciones'   => $request->observaciones,
            ]);

            foreach ($request->file('evidencias') as $foto) {
                $ruta = $foto->store('checklists/entrada', 'public');

                $checklist->evidencias()->create(['foto' => $ruta]);
            }

            //$salida->update(['fecha_regreso' => now(), 'estatus' => 'finalizado',]);
            // se rempleza para que marque el estaus finalizado y se mida la duracion del tiempo de vehiculo que estuvo 
            $fechaRegreso = now();
            $salida->update([
                'fecha_regreso' =>$fechaRegreso,
                'estatus' => 'finalizado',
                'finalizado_por' => auth()->id(),
                'duracion_minutos' => $salida->fecha_salida ? $salida->fecha_salida->diffInMinutes($fechaRegreso): null,
            ]);
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

        $Logo = public_path('images/Logo_AICO_R.jpg');

        return Pdf::loadView(
            'salidas.checklist.pdf_unificado',
            [
                'salida' => $salida,
                'checklistSalida' => $salida->checklistSalida,
                'checklistEntrada' => $salida->checklistEntrada,
                'Logo' => $Logo,
            ]
        )
        ->setPaper('letter', 'portrait')
        ->stream("checklist_vehiculo_{$salida->id}.pdf");
    }
}
