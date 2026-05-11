<?php

namespace App\Http\Controllers\Mobile\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\MobileSalidaStoreRequest;
use App\Http\Resources\Mobile\MobileSalidaResource;
use App\Models\Vehiculos\SalidaVehiculo;
use App\Services\Mobile\MobileVehiculoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileSalidaController extends Controller
{
    public function __construct(
        private readonly MobileVehiculoService $vehiculoService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $salidas = $this->vehiculoService->paginatedHistory($request->user(), 10);

        return response()->json([
            'success' => true,
            'message' => 'Salidas obtenidas correctamente.',
            'data' => MobileSalidaResource::collection($salidas->items()),
            'meta' => [
                'current_page' => $salidas->currentPage(),
                'last_page' => $salidas->lastPage(),
                'per_page' => $salidas->perPage(),
                'total' => $salidas->total(),
            ],
        ]);
    }

    public function active(Request $request): JsonResponse
    {
        $salida = $this->vehiculoService->activeSalida($request->user());

        return response()->json([
            'success' => true,
            'message' => $salida ? 'Salida activa encontrada.' : 'No hay salida activa.',
            'data' => $salida ? new MobileSalidaResource($salida) : null,
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        return $this->index($request);
    }

    public function store(MobileSalidaStoreRequest $request): JsonResponse
    {
        $salida = $this->vehiculoService->createSalida($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Salida registrada correctamente.',
            'data' => new MobileSalidaResource($salida),
        ], 201);
    }

    public function show(Request $request, SalidaVehiculo $salida): JsonResponse
    {
        abort_unless(
            $this->vehiculoService->listVisibleSalidas($request->user())->whereKey($salida->id)->exists(),
            403
        );

        $salida->load([
            'vehiculo',
            'chofer',
            'solicitante',
            'checklistSalida.condicion',
            'checklistSalida.documentos',
            'checklistSalida.herramientas',
            'checklistSalida.evidencias',
            'checklistEntrada.condicion',
            'checklistEntrada.evidencias',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Detalle de salida obtenido correctamente.',
            'data' => [
                'salida' => new MobileSalidaResource($salida),
                'defaults_ultima_entrada' => $this->vehiculoService->departureDefaults($salida),
            ],
        ]);
    }
}
