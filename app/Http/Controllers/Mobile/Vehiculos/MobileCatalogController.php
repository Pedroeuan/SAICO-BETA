<?php

namespace App\Http\Controllers\Mobile\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Mobile\MobileUserResource;
use App\Http\Resources\Mobile\MobileVehiculoResource;
use App\Services\Mobile\MobileVehiculoService;
use Illuminate\Http\JsonResponse;

class MobileCatalogController extends Controller
{
    public function __construct(
        private readonly MobileVehiculoService $vehiculoService
    ) {
    }

    public function availableVehicles(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Vehiculos disponibles obtenidos correctamente.',
            'data' => MobileVehiculoResource::collection($this->vehiculoService->availableVehicles()),
        ]);
    }

    public function operationalUsers(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Usuarios operativos obtenidos correctamente.',
            'data' => MobileUserResource::collection($this->vehiculoService->operationalUsers()),
        ]);
    }
}
