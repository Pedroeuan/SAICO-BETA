<?php

namespace App\Http\Controllers\Mobile\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\MobileAnalyticsRequest;
use App\Services\Mobile\MobileAnalyticsService;
use Illuminate\Http\JsonResponse;

class MobileAnalyticsController extends Controller
{
    public function vehicles(\Illuminate\Http\Request $request, MobileAnalyticsService $service): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $service->vehicleOptions($request->user())])
            ->header('Cache-Control', 'private, no-store');
    }

    public function index(MobileAnalyticsRequest $request, MobileAnalyticsService $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Indicadores obtenidos correctamente.',
            'data' => $service->summary($request->user(), $request->validated()),
        ])->header('Cache-Control', 'private, no-store');
    }
}
