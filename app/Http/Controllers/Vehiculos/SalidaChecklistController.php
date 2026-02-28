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
        $defaultLiquidoLimpiaparabrisas = null;
        $defaultAceite = null;
        $defaultAnticongelante = null;
        $defaultEstadoLlantas = null;
        $defaultLlantaDelanteraIzq = null;
        $defaultLlantaDelanteraDer = null;
        $defaultLlantaTraseraIzq = null;
        $defaultLlantaTraseraDer = null;
        if ($ultimaCondicion && $ultimaCondicion->condicion) {
            $defaultNivel = $ultimaCondicion->condicion->nivel_gasolina;
            $defaultKilometraje = $ultimaCondicion->condicion->kilometraje;
            $defaultLiquidoLimpiaparabrisas = $ultimaCondicion->condicion->liquido_limpiaparabrisas;
            $defaultAceite = $ultimaCondicion->condicion->aceite;
            $defaultAnticongelante = $ultimaCondicion->condicion->anticongelante;
            $defaultEstadoLlantas = $ultimaCondicion->condicion->estado_llantas;
            $defaultLlantaDelanteraIzq = $ultimaCondicion->condicion->llanta_delantera_izq_calibracion;
            $defaultLlantaDelanteraDer = $ultimaCondicion->condicion->llanta_delantera_der_calibracion;
            $defaultLlantaTraseraIzq = $ultimaCondicion->condicion->llanta_trasera_izq_calibracion;
            $defaultLlantaTraseraDer = $ultimaCondicion->condicion->llanta_trasera_der_calibracion;
        }

        return view('salidas.checklist.salida', compact(
            'salida',
            'licenciaVigente',
            'tarjetaVigente',
            'polizaVigente',
            'defaultNivel',
            'defaultKilometraje',
            'defaultLiquidoLimpiaparabrisas',
            'defaultAceite',
            'defaultAnticongelante',
            'defaultEstadoLlantas',
            'defaultLlantaDelanteraIzq',
            'defaultLlantaDelanteraDer',
            'defaultLlantaTraseraIzq',
            'defaultLlantaTraseraDer'
        ));
    }

    public function store(Request $request, SalidaVehiculo $salida)
    {
        $request->validate([
            // valores de cada campo
            'nivel_gasolina'  => 'required|string',
            'kilometraje'     => 'required|integer|min:0',
            'limpio_exterior' => 'nullable|in:0,1',
            'limpio_interior' => 'nullable|in:0,1',
            'observaciones'   => 'nullable|string|max:500',
            'liquido_limpiaparabrisas' => 'required|in:suficiente,escaso,no_hay',
            'aceite' => 'required|in:suficiente,escaso,no_hay',
            'anticongelante' => 'required|in:suficiente,escaso,no_hay',
            'estado_llantas' => 'required|in:buen_estado,regular,malo',
            'llanta_delantera_izq_calibracion' => 'required|in:baja,normal,alta',
            'llanta_delantera_der_calibracion' => 'required|in:baja,normal,alta',
            'llanta_trasera_izq_calibracion' => 'required|in:baja,normal,alta',
            'llanta_trasera_der_calibracion' => 'required|in:baja,normal,alta',
            'herramientas'    => 'nullable|array',
            'evidencias' => 'required|array|min:3|max:3',// aumetar o disminuir la cantida de imagen
            'evidencias.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120', // formato

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
                'liquido_limpiaparabrisas' => $request->liquido_limpiaparabrisas,
                'aceite' => $request->aceite,
                'anticongelante' => $request->anticongelante,
                'estado_llantas' => $request->estado_llantas,
                'llanta_delantera_izq_calibracion' => $request->llanta_delantera_izq_calibracion,
                'llanta_delantera_der_calibracion' => $request->llanta_delantera_der_calibracion,
                'llanta_trasera_izq_calibracion' => $request->llanta_trasera_izq_calibracion,
                'llanta_trasera_der_calibracion' => $request->llanta_trasera_der_calibracion,
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

            // Sincroniza kilometraje maestro del vehiculo con el checklist capturado.
            $salida->vehiculo()->update([
                'kilometraje_actual' => (int) $request->kilometraje,
            ]);
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
            return back()->withInput()->with('error', 'Este vehículo ya tiene checklist de entrada');
        }
        if ($salida->estatus === 'finalizado') {
            return back()->withInput()->with('error', 'Esta salida ya fue finalizada');
        }

        // Validar contra el kilometraje real del checklist de salida.
        $checklistSalida = $salida->checklistSalida;
        if (!$checklistSalida || !$checklistSalida->condicion) {
            return back()->withInput()->with('error', 'El checklist de salida no tiene condición registrada');
        }
        $kmSalida = (int) $checklistSalida->condicion->kilometraje;

        $request->validate([
            'nivel_gasolina'  => 'required|string',
            'kilometraje'     => "required|integer|gt:$kmSalida",
            'limpio_exterior' => 'nullable|in:0,1',
            'limpio_interior' => 'nullable|in:0,1',
            'observaciones'   => 'nullable|string|max:500',
            'liquido_limpiaparabrisas' => 'required|in:suficiente,escaso,no_hay',
            'aceite' => 'required|in:suficiente,escaso,no_hay',
            'anticongelante' => 'required|in:suficiente,escaso,no_hay',
            'estado_llantas' => 'required|in:buen_estado,regular,malo',
            'llanta_delantera_izq_calibracion' => 'required|in:baja,normal,alta',
            'llanta_delantera_der_calibracion' => 'required|in:baja,normal,alta',
            'llanta_trasera_izq_calibracion' => 'required|in:baja,normal,alta',
            'llanta_trasera_der_calibracion' => 'required|in:baja,normal,alta',
            'evidencias' => 'required|array|min:3|max:3',
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
                'liquido_limpiaparabrisas' => $request->liquido_limpiaparabrisas,
                'aceite' => $request->aceite,
                'anticongelante' => $request->anticongelante,
                'estado_llantas' => $request->estado_llantas,
                'llanta_delantera_izq_calibracion' => $request->llanta_delantera_izq_calibracion,
                'llanta_delantera_der_calibracion' => $request->llanta_delantera_der_calibracion,
                'llanta_trasera_izq_calibracion' => $request->llanta_trasera_izq_calibracion,
                'llanta_trasera_der_calibracion' => $request->llanta_trasera_der_calibracion,
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
            $salida->vehiculo()->update([
                'estatus' => 'disponible',
                'kilometraje_actual' => (int) $request->kilometraje,
            ]);
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
