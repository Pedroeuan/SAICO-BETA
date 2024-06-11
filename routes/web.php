<?php
//use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

use App\Http\Controllers\EquiposyConsumibles\general_eycController;
use App\Http\Controllers\EquiposyConsumibles\solicitudEquiposController;
use App\Http\Controllers\Solicitudes\SolicitudesController;
use App\Http\Controllers\Certificados\CertificadosController;

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

    /*Rutas de Vistas Equipos y Consumibles Tabla General*/
    Route::get('/inventario', [general_eycController::class, 'index'])->name('inventario');

    /*Rutas de Vistas Equipos y Consumibles-Registro*/
    Route::get('/registros/createEyC', [general_eycController::class, 'createEquipos'])->name('registros.createEyC');
    /*Rutas de Vistas Equipos y Consumibles-Edición*/
    Route::get('/edicion/editEyC/{id}', [general_eycController::class, 'editEyC'])->name('edicion.editEyC');

    /*Rutas de Vistas Equipos y Consumibles-Solicitud*/
    Route::get('/registros/SolictudEyC', [SolicitudEquiposController::class, 'createSolicitud'])->name('registros.SolicitudEyC');
    /*Rutas de Vistas Equipos y Consumibles-Historial Certificados*/
    Route::get('/Historial-Certificados', [historial_certificadoController::class, 'index'])->name('Historial-Certificados');

    /*EQUIPOS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeEquipos', [general_eycController::class, 'storeEquipos'])->name('general_eyc.storeEquipos'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editEquipos/{id}', [general_eycController::class, 'updateEquipos'])->name('editEquipos.update');

    /*CONSUMIBLES*/
    /*Ruta de Guardado*/
    Route::post('general_eyc/storeConsumibles', [general_eycController::class, 'storeConsumibles'])->name('general_eyc.storeConsumibles'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editConsumibles/{id}', [general_eycController::class, 'updateConsumibles'])->name('editConsumibles.update');

    /*ACCESORIOS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeAccesorios', [general_eycController::class, 'storeAccesorios'])->name('general_eyc.storeAccesorios'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editAccesorios/{id}', [general_eycController::class, 'updateAccesorios'])->name('editAccesorios.update');

    /*BLOCKS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeBlocks', [general_eycController::class, 'storeBlocks'])->name('general_eyc.storeBlocks'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editBlocks/{id}', [general_eycController::class, 'updateBlocks'])->name('editBlocks.update');

    /*HERRAMIENTAS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeHerramientas', [general_eycController::class, 'storeHerramientas'])->name('general_eyc.storeHerramientas'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editHerramientas/{id}', [general_eycController::class, 'updateHerramientas'])->name('editHerramientas.update');

    /*Ruta para borrar, equipos, comsumibles, block, herramientas---NO HABILITAR*/
    Route::delete('/eliminar/destroyEquipos/{id}', [general_eycController::class, 'destroyEquipos'])->name('eliminar.destroyEquipos');

    /*SOLICITUDES*/
    /*Rutas de Vistas de Solicitudes-Registro*/
    Route::get('/solicitud/create', [SolicitudesController::class, 'create'])->name('solicitud.create');
    /*Rutas de Vistas de Solicitudes-Tabla de Solicitud*/
    Route::get('/solicitud/index', [SolicitudesController::class, 'index'])->name('solicitud.index');
    
    /*Rutas de Vistas de Solicitudes-Edición*/
    Route::get('/solicitud/edit/{id}', [SolicitudesController::class, 'edit'])->name('solicitud.edit');
    /*Ruta de Eliminación-de Solicitud*/
    Route::delete('/solicitudes/eliminar/{id}', [SolicitudesController::class, 'destroySolicitud'])->name('solicitudes.destroySolicitud');

    /*SOLICITUD*/
    /*Ruta de Guardado*/
    Route::post('/solicitudes/storeSolicitud', [SolicitudesController::class, 'storeSolicitud'])->name('solicitudes.storeSolicitud');
    /*Ruta de Agregar-datos a detalles solicitud*/
    Route::post('/solicitudes/agregar', [SolicitudesController::class, 'agregarDetallesSolicitud'])->name('solicitudes.agregarDetallesSolicitud');
    /*Ruta de Eliminación-detalles_Solicitud*/
    Route::delete('/Detalles_solicitudes/eliminar/{id}', [SolicitudesController::class, 'destroyDetallesSolicitud'])->name('solicitudes.destroyDetallesSolicitud');


    /*HISTORIAL CERTIFICADOS*/
    /*Ruta de Vista de historial de certificados*/
    Route::get('/certificados/index', [CertificadosController::class, 'index'])->name('certificados/index');
});

require __DIR__.'/auth.php';

Auth::routes();

//Route::get('/home',[App\Http\Controller\HomeController::class,'index'])->name('home');

