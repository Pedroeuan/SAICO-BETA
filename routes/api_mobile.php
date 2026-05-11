<?php

use App\Http\Controllers\Mobile\Auth\MobileAuthController;
use App\Http\Controllers\Mobile\Vehiculos\MobileChecklistController;
use App\Http\Controllers\Mobile\Vehiculos\MobileCatalogController;
use App\Http\Controllers\Mobile\Vehiculos\MobileSalidaController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [MobileAuthController::class, 'logout']);
        Route::get('/me', [MobileAuthController::class, 'me']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('catalogos')->group(function () {
        Route::get('/vehiculos-disponibles', [MobileCatalogController::class, 'availableVehicles']);
        Route::get('/usuarios-operativos', [MobileCatalogController::class, 'operationalUsers']);
    });

    Route::prefix('salidas')->group(function () {
        Route::get('/', [MobileSalidaController::class, 'index']);
        Route::get('/activa', [MobileSalidaController::class, 'active']);
        Route::get('/historial', [MobileSalidaController::class, 'history']);
        Route::post('/', [MobileSalidaController::class, 'store']);
        Route::get('/{salida}', [MobileSalidaController::class, 'show']);
        Route::post('/{salida}/checklist-salida', [MobileChecklistController::class, 'storeDeparture']);
        Route::post('/{salida}/checklist-entrada', [MobileChecklistController::class, 'storeArrival']);
    });
});
