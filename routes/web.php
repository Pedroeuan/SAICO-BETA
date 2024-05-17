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
    Route::get('registros/createEyC', [general_eycController::class, 'createEquipos'])->name('registros.createEyC');
    Route::get('registros/SolicitudEyC', [SolicitudEquiposController::class, 'createSolicitud'])->name('registros.SolicitudEyC');
    Route::get('edicion/editEyC/{general_eyc}', [general_eycController::class, 'editEyC'])->name('edicion.editEyC');
    Route::get('/edicion/editEyC/{id}', [general_eycController::class, 'editEyC'])->name('editEyC');
    Route::get('/edicion/editEyCon/{id}', [general_eycController::class, 'editEyCon'])->name('edicion.editEyCon');
    
    /*EQUIPOS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeEquipos', [general_eycController::class, 'storeEquipos'])->name('general_eyc.storeEquipos'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editEquipos/{id}', [general_eycController::class, 'updateEquipos'])->name('editEquipos.update');
    /*Ruta para borrar*/
    Route::delete('/eliminar/destroyEquipos/{id}', [general_eycController::class, 'destroyEquipos'])->name('eliminar.destroyEquipos');
    

        /*CONSUMIBLES*/
    /*Ruta de Guardado*/
    Route::post('general_eyc', [general_eycController::class, 'storeConsumibles'])->name('general_eyc.storeConsumibles'); 
});

require __DIR__.'/auth.php';

Auth::routes();

//Route::get('/home',[App\Http\Controller\HomeController::class,'index'])->name('home');

