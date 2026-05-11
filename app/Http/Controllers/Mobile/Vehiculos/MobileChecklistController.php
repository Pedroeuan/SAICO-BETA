<?php

namespace App\Http\Controllers\Mobile\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\MobileChecklistEntradaRequest;
use App\Http\Requests\Mobile\MobileChecklistSalidaRequest;
use App\Http\Resources\Mobile\MobileChecklistResource;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Services\Mobile\MobileVehiculoService;
use Illuminate\Http\JsonResponse;

class MobileChecklistController extends Controller
{
    public function __construct(
        private readonly MobileVehiculoService $vehiculoService
    ) {
    }

    public function storeDeparture(
        MobileChecklistSalidaRequest $request,
        SalidaVehiculo $salida
    ): JsonResponse {
        abort_unless(
            $this->vehiculoService->listVisibleSalidas($request->user())->whereKey($salida->id)->exists(),
            403
        );

        $checklist = $this->vehiculoService->storeDepartureChecklist($salida, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Checklist de salida registrado correctamente.',
            'data' => new MobileChecklistResource($checklist),
        ], 201);
    }

    public function storeArrival(
        MobileChecklistEntradaRequest $request,
        SalidaVehiculo $salida
    ): JsonResponse {
        abort_unless(
            $this->vehiculoService->listVisibleSalidas($request->user())->whereKey($salida->id)->exists(),
            403
        );

        $checklist = $this->vehiculoService->storeArrivalChecklist(
            $salida,
            $request->validated(),
            $request->user()->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Checklist de entrada registrado correctamente.',
            'data' => new MobileChecklistResource($checklist),
        ], 201);
    }
}
