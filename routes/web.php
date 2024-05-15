<?php
//use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

use App\Http\Controllers\EquiposyConsumibles\general_eycController;
use App\Http\Controllers\EquiposyConsumibles\solicitudEquiposController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*PDF*/
Route::middleware('auth')->group(function () {
Route::post('/upload-pdf', [PDFController::class, 'upload'])->name('upload.pdf');
});

/*Equipos y Consumibles*/ 
Route::middleware('auth')->group(function () {
    /*Rutas de Vistas Equipos Tabla General*/
    Route::get('inventario', [general_eycController::class, 'index'])->name('inventario');

    /*Rutas de Vistas Equipos*/
    Route::get('registros/createEquipos', [general_eycController::class, 'createEquipos'])->name('registros/createEquipos');
    Route::get('registros/SolicitudEyC', [SolicitudEquiposController::class, 'createSolicitud'])->name('registros/SolicitudEyC');
    Route::get('edicion/editEquipos/{general_eyc}', [general_eycController::class, 'editEquipos'])->name('editEquipos');
    
    /*EQUIPOS*/
    /*Ruta de Guardado*/
    //Route::post('general_eyc', [general_eycController::class, 'storeEquipos'])->name('general_eyc.storeEquipos');
    Route::post('/general_eyc/storeEquipos', [general_eycController::class, 'storeEquipos'])->name('general_eyc.storeEquipos'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editEquipos/{id}', [general_eycController::class, 'updateEquipos'])->name('editEquipos.update');
    /*Ruta para borrar*/
    //Route::delete('destroyEquipos/{id}', [general_eycController::class, 'destroyEquipos'])->name('destroyEquipos.destroy');
    Route::delete('/general_eyc/destroyEquipos/{id}', [GeneralEycController::class, 'destroyEquipos'])->name('general_eyc.destroyEquipos');

        /*CONSUMIBLES*/
    /*Ruta de Guardado*/
    Route::post('general_eyc', [general_eycController::class, 'storeConsumibles'])->name('general_eyc.storeConsumibles'); 
});

require __DIR__.'/auth.php';

Auth::routes();

//Route::get('/home',[App\Http\Controller\HomeController::class,'index'])->name('home');

