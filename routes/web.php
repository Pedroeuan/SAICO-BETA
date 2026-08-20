<?php
//use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AnalisisImagenController;
use App\Http\Controllers\Procesamiento\TrabajoProcesamientoController;
use App\Http\Controllers\Procesamiento\ProcesamientoXrfController;
use App\Http\Controllers\Procesamiento\ProcesamientoPdfController;

use App\Http\Controllers\OC\OCController;
use App\Http\Controllers\TICS\TICSController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Prueba\PruebaController;
use App\Http\Controllers\Manifiesto\PDFController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Clientes\ClientesController;
use App\Http\Controllers\Normas_IM\NormasIMController;
use App\Http\Controllers\Normas_IM\PatronesGranoIMController;
use App\Http\Controllers\Manifiesto\ManifiestoController;
use App\Http\Controllers\Solicitudes\SolicitudesController;
use App\Http\Controllers\PDFReportes\PDFReportesController;
use App\Http\Controllers\EquiposyConsumibles\KitsController;
use App\Http\Controllers\Certificados\CertificadosController;
use App\Http\Controllers\Notificacion\NotificacionController;
use App\Http\Controllers\EquiposyConsumibles\equiposController;
use App\Http\Controllers\EquiposyConsumibles\AlmacenController;
use App\Http\Controllers\EquiposyConsumibles\ExcelEyCController;
use App\Http\Controllers\EquiposyConsumibles\DevolucionController;
use App\Http\Controllers\EquiposyConsumibles\AccesoriosController;
use App\Http\Controllers\EquiposyConsumibles\IndicadoresController;
use App\Http\Controllers\EquiposyConsumibles\consumiblesController;
use App\Http\Controllers\EquiposyConsumibles\general_eycController;
use App\Http\Controllers\EquiposyConsumibles\HerramientasController;
use App\Http\Controllers\EquiposyConsumibles\BlockYProbetaController;
use App\Http\Controllers\EquiposyConsumibles\HistorialAlmacenController;
use App\Http\Controllers\EquiposyConsumibles\solicitudEquiposController;
use App\Http\Controllers\EquiposyConsumibles\SolicitudRecursosController;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_02_B_03Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_02_B_04Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_03_B_01Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_02Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_04_03Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_05_01Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_05_B_01Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_06_B_01Controller;
use App\Http\Controllers\Reporte\IM\FOR_PIMP_07_B_01Controller;
use App\Http\Controllers\Reporte\ReporteController;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_04_01Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_05_01Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_06_01Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_07_01Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_08_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_09_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_10_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_11_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_12_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_13_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_14_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_15_01Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_16_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_17_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_17_01_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_18_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_19_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_20_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_21_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_22_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_23_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_24_01Controller;
Use App\Http\Controllers\Reporte\PINS\FOR_PINS_25_01Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_03_02Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_05_02Controller;
use App\Http\Controllers\Reporte\PINS\FOR_PINS_11_02Controller;
use App\Http\Controllers\Reporte\INS\FOR_03_PRO_INS_15Controller;
use App\Http\Controllers\solicitud_AD\SolicitudADController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Procedimientos\ProcedimientoController;
use App\Http\Controllers\Vehiculos\panelController;
use App\Http\Controllers\Vehiculos\VehiculoController; //controlador a vehiculos
use App\Http\Controllers\Vehiculos\SalidaVehiculoController; //accesso a salidas de vehiculos
use App\Http\Controllers\Vehiculos\SalidaChecklistController; //accesso a checklists de salidas de vehiculos
use App\Http\Controllers\Vehiculos\RendimientoExportController; //exportes rendimiento vehiculos
use App\Http\Controllers\Vehiculos\MantenimientoController;// Controlador de mantenimientos
use App\Http\Controllers\Vehiculos\PagoVehiculoController; // controlador pago


    require __DIR__.'/auth.php';

    Route::redirect('/', '/dashboard');
    Route::redirect('/register', '/dashboard');
    /*Route::get('Reporte/FOR_PIMP_02_B/03', [ReporteController::class, 'FOR_PIMP_02_B_03'])->name('Plantilla_FOR_PIMP_02_B_03.PDF');
    Route::get('Reporte/FOR_PIMP_02_B/04', [ReporteController::class, 'FOR_PIMP_02_B_04'])->name('Plantilla_FOR_PIMP_02_B_04.PDF');
    Route::get('Reporte/FOR_PIMP_07_B/01', [ReporteController::class, 'FOR_PIMP_07_B_01'])->name('Plantilla_FOR_PIMP_07_B_01.PDF');
    Route::get('Reporte/FOR_PIMP_03_B/01', [ReporteController::class, 'FOR_PIMP_03_B_01'])->name('Plantilla_FOR_PIMP_03_B_01.PDF');
    Route::get('Reporte/FOR_PIMP_05/01', [ReporteController::class, 'FOR_PIMP_05_01'])->name('Plantilla_FOR_PIMP_05_01.PDF');
    Route::get('Reporte/FOR_PIMP_05_B/01', [ReporteController::class, 'FOR_PIMP_05_B_01'])->name('Plantilla_FOR_PIMP_05_B_01.PDF');
    Route::get('Reporte/FOR_PIMP_06_B/01', [ReporteController::class, 'FOR_PIMP_06_B_01'])->name('Plantilla_FOR_PIMP_06_B_01.PDF');
    Route::get('Reporte/FOR_PIMP_04/02', [ReporteController::class, 'FOR_PIMP_04_02'])->name('Plantilla_FOR_PIMP_04_02.PDF');
    Route::get('Reporte/FOR_PIMP_04/03', [ReporteController::class, 'FOR_PIMP_04_03'])->name('Plantilla_FOR_PIMP_04_03.PDF');*/

    /*Vista Clientes Publicos*/
    Route::get('/reportes-publicos', function () {
        return view('reportes_publicos.index');
        })->name('reportes.publicos');
    /*QR de Reportes Publicos*/
    Route::get('/qr/reporte/{token}',[ReporteController::class, 'VerPdfQR'])->name('qr.reporte');

    //solicitud_AD
    Route::middleware('auth')->group(function () {
    /*SOLICITUDES-1*/
    /*Rutas de Vistas de Solicitudes-Registro*/
    Route::get('/ADsolicitud/create', [SolicitudADController::class, 'create'])->name('ADsolicitud.create');
    /*Rutas de Vistas de Solicitudes-Tabla de Solicitud*/
    Route::get('/ADsolicitud/index', [SolicitudADController::class, 'index'])->name('ADsolicitud.index');
    /*Rutas de Vistas Solicitudes_AD*/
    Route::get('/ADsolicitud/edit/{id}', [SolicitudADController::class, 'edit'])->name('ADsolicitud.edit');
    /*Ruta de Actualización Solicitud_AD*/
    Route::post('/ADsolicitud/update/{id}', [SolicitudADController::class, 'update'])->name('ADsolicitud.update');
    /*Ruta de Guardado-index*/
    Route::post('/ADsolicitud/store', [SolicitudADController::class, 'store'])->name('ADsolicitud.store');
    /*Ruta de Eliminar-index*/
    Route::delete('/ADsolicitud/destroy/{id}', [SolicitudADController::class, 'destroy'])->name('ADsolicitud.destroy');
    /*Ruta de actualizar-index*/
    Route::post('/ADsolicitud/actualizar/{id}', [SolicitudADController::class, 'actualizar'])->name('adsolicitud.actualizar');
    /*Ruta de actualizar-multiple*/
    Route::post('/ADsolicitud/actualizarMultiple', [SolicitudADController::class, 'actualizarMultiple'])->name('ADsolicitud.actualizarMultiple');
    /*Ruta de actualizar-estatus*/
    Route::get('/estatus-solicitudes', [SolicitudADController::class, 'obtenerEstatus'])->name('estatus.solicitudes');

    });
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware('auth')->group(function () {
    Route::middleware('can:equipos-lab-access')->group(function () {
        /*Creación de Notificaciones*/
        Route::get('notificacion/index', [NotificacionController::class, 'index'])->name('notifications.index');
        /*Obtener Notificaciones*/
        Route::get('notificaciones/update', [NotificacionController::class, 'getNotificaciones']);
        });
    Route::post('/notificaciones/marcar-leida/{id}', [NotificacionController::class, 'marcarComoLeida'])->name('notificaciones.marcarLeida');
    });
    

    Route::middleware('auth')->group(function () {
        Route::middleware('can:tecnicos-access')->group(function () {
        /* Análisis reutilizable de imágenes: histograma exacto y medición final con Fiji/ImageJ. */
        Route::post('/analisis-imagen/histograma', [AnalisisImagenController::class, 'histograma'])
            // El histograma inicia Fiji; este límite evita saturación accidental por usuario autenticado.
            ->middleware('throttle:12,1')
            ->name('analisis-imagen.histograma');
        Route::post('/analisis-imagen/fraccion-fases', [AnalisisImagenController::class, 'fraccionFases'])
            // Measure conserva tres evidencias, por lo que se aplica un límite más estricto.
            ->middleware('throttle:6,1')
            ->name('analisis-imagen.fraccion-fases');
        /* Estado durable de Fiji, XRF y PDF; el UUID se valida contra el tecnico autenticado. */
        Route::get('/procesamientos/{trabajo}/estado', [TrabajoProcesamientoController::class, 'estado'])
            ->middleware('throttle:120,1')
            ->name('procesamientos.estado');
        Route::get('/procesamientos/{trabajo}/descargar', [TrabajoProcesamientoController::class, 'descargar'])
            ->middleware('throttle:30,1')
            ->name('procesamientos.descargar');
        Route::get('/procesamientos/pdf/{reporte}/{formato}', [ProcesamientoPdfController::class, 'pagina'])
            ->middleware('throttle:12,1')
            ->name('procesamientos.pdf.pagina');
        /*vista Page in construction */
        Route::get('/Page_In_Construction', [general_eycController::class, 'PageInConstruction'])->name('Page_In_Construction');
        /*vista Page welcome*/
        Route::get('/Welcome', [general_eycController::class, 'Welcome'])->name('Welcome');

        /*A DEFINIR EL ACCESO */
        /*Obtener Ruta del PDF */
        Route::get('/Obtener/RutaPDF/{id}', [ReporteController::class, 'ObtenerRutaPDF'])->name('Obtener.RutaPDF');
        /*REPORTES*/
        /*Obtiene las Normas segun La prueba del select*/
        Route::get('/Obtener/normas/{id}', [ReporteController::class, 'ObtenerNormas'])->name('Obtener.normas');
        /*Obtiene los Formatos segun La prueba del select*/
        Route::get('/Obtener/formatos/{id}', [ReporteController::class, 'ObtenerFormatos'])->name('Obtener.formatos');

        /*Rutas de Vistas del index de selección de los contratos*/
        Route::get('/index/ContratoProyecto', [ReporteController::class, 'indexContratoProyecto'])->name('index.ContratoProyecto');
        /*Rutas de Vistas del index despues de la selección de los contratos/Reportes de estos*/
        Route::post('/index/ReporteProyectoContrato', [ReporteController::class, 'indexReporteProyectoContrato'])->name('index.ReporteProyectoContrato');
        /*Rutas de Vistas del index despues del Guardado de Reportes */
        Route::get('/indexINS2', [ReporteController::class, 'indexINS2'])->name('indexINS2');
        /*Rutas de Vistas del index despues de la seleccion del Contrato */
        Route::get('/indexINS1', [ReporteController::class, 'indexINS1'])->name('indexINS1');
        /*Rutas de Vistas del index despues de la seleccion del Manifiesto */
        Route::get('/ReportesPrincipalMaster', [ReporteController::class, 'ReportesPrincipalMaster'])->name('ReportesPrincipalMaster');
        /*Rutas de Vistas del index despues de la seleccion prueba,norma y codigo */
        Route::get('/ReportesindexManifiesto', [ReporteController::class, 'ReportesindexManifiesto'])->name('ReportesindexManifiesto');
        /*Rutas de controlador para duplicar los datos y redirigir el Reporte*/
        Route::get('/Next/Reporte/{id}', [ReporteController::class, 'Next_Reporte'])->name('Next.Reporte');

        /*PROCEDIMIENTOS*/
        /*Vista Menu Procedimientos*/
        Route::get('/index/Procedimientos', [ProcedimientoController::class, 'index'])->name('index.Procedimientos');
        /*vista Procedimientos, Norma. Codio y Formato*/
        Route::get('/Procedimientos/Create', [ProcedimientoController::class, 'create'])->name('Procedimientos.Create');
        /*Ruta de Guardado*/
        Route::post('/Procedimiento/Store', [ProcedimientoController::class, 'store'])->name('Procedimientos.store');
        /*Rutas de Vistas Procedimientos/Norma_Codigo*/
        Route::get('/Procedimientos/edit/{id}', [ProcedimientoController::class, 'edit'])->name('Procedimientos.edit');
        /*Ruta de Actualización*/
        Route::post('/Procedimientos/update/{id}', [ProcedimientoController::class, 'update'])->name('Procedimientos.update');
        /*Ruta de Eliminación-del procedimiento index*/
        Route::delete('/Procedimientos/eliminar/{id}', [ProcedimientoController::class, 'destroy'])->name('Procedimientos.destroy');

        /*PRUEBAS*/
        /*Vista Menu Pruebas*/
        Route::get('/index/Pruebas', [PruebaController::class, 'indexPruebas'])->name('index.Pruebas');
        /*vista Pruebas, Norma. Codio y Formato*/
        Route::get('/Pruebas/Create', [PruebaController::class, 'create'])->name('Pruebas.Create');
        /*Ruta de Guardado*/
        Route::post('/Prueba_Norma_Codigo/store', [PruebaController::class, 'store'])->name('Prueba_Norma_Codigo.store');
        /*Rutas de Vistas Pruebas/Norma_Codigo*/
        Route::get('/Pruebas/Norma_Codigo/edit/{id}', [PruebaController::class, 'edit'])->name('Pruebas.Norma_Codigo.edit');
        /*Ruta de Actualizar Prueba/Norma_Codigo*/
        Route::post('/Pruebas/Norma_Codigo/update/{id}', [PruebaController::class, 'update'])->name('Pruebas.Norma_Codigo.update');
        /*Ruta la vista para editar la Norma Aplicable con los formatos*/
        Route::get('/Pruebas/Normas_Aplicables/normas/{id}', [PruebaController::class, 'editnormas'])->name('Pruebas.Normas_Aplicables.normas');
        /*Ruta del botón del eliminar de la vista Prueba\edit.blade */
        Route::delete('/Eliminar/NormaCodigo/Tabla/{id}', [PruebaController::class, 'destroyNormaCodigo'])->name('Eliminar.NormaCodigo.Tabla');
        /*Ruta del botón del eliminar de la vista Prueba\editformatos.blade */
        Route::delete('/Eliminar/Formato/Tabla/{id}', [PruebaController::class, 'destroyFormato'])->name('Eliminar.Formato.Tabla');
        /*Ruta del botón del eliminar del index de Pruebas Registradas index.blade */
        Route::delete('/Eliminar/Prueba/Tabla/{id}', [PruebaController::class, 'destroyPrueba'])->name('Eliminar.Prueba.Tabla');
        /*Rutas de Vistas Pruebas/Norma_Codigo/Formatos*/
        Route::get('/Pruebas/Norma_Codigo/Formatos/edit/{id}', [PruebaController::class, 'editformatos'])->name('Pruebas.Norma_Codigo.Formatos.edit');
        /*Ruta de crear/Actualizar Formato para las Normas o codigos*/
        Route::post('/Pruebas/Norma_Codigo/Formatos/UpdateCreateFormato/{id}', [PruebaController::class, 'UpdateCreateFormato'])->name('Pruebas.Norma_Codigo.Formatos.UpdateCreateFormato');
        
        /*NORMAS IM*/
        /*Vista Menu Normas IM*/
        Route::get('/index/Normas_IM', [NormasIMController::class, 'index'])->name('index.Normas_IM');
        /*vista Normas IM*/
        Route::get('/Normas_IM/Create', [NormasIMController::class, 'create'])->name('Normas_IM.Create');
        /*Ruta de Guardado*/
        Route::post('/Normas_IM/store', [NormasIMController::class, 'store'])->name('Normas_IM.store');
        /*Alta rápida desde los formularios Create/Edit de reportes IM*/
        Route::post('/Normas_IM/store-rapida', [NormasIMController::class, 'storeRapida'])->name('Normas_IM.storeRapida');
        /*vista Edición Normas IM*/
        Route::get('/Normas_IM/Edit/{id}', [NormasIMController::class, 'edit'])->name('Normas_IM.Edit');
        /*Ruta de Actualización*/
        Route::post('/Normas_IM/update/{id}', [NormasIMController::class, 'update'])->name('Normas_IM.update');
        /*Ruta de Eliminación*/
        Route::delete('/Normas_IM/destroy/{id}', [NormasIMController::class, 'destroy'])->name('Normas_IM.destroy');

        /* Catálogo de imágenes maestras para la comparación metalográfica de tamaño de grano. */
        Route::resource('/Patrones_Grano_IM', PatronesGranoIMController::class)
            ->parameters(['Patrones_Grano_IM' => 'patron'])
            ->names('Patrones_Grano_IM')
            ->except('show');

        /*Vista Menu Servicios*/
        Route::get('/Menu/Servicios', [ReporteController::class, 'indexMenuServicios'])->name('Menu.Servicios');
        /*Controlador del a vista Menu.Servicios (Prueba/Prueba) para obtener el servicio y reedirigir a la vista a Seleccion-Servicios-Pruebas*/
        Route::post('/Servicios-Pruebas', [ReporteController::class, 'Servicios_Pruebas'])->name('Servicios.Pruebas');
        /*Menu de Servicios-Pruebas Vista Selección rpueba, norma y formato*/
        Route::get('/Seleccion-Servicios-Pruebas', [ReporteController::class, 'Seleccion_Servicios_Pruebas'])->name('Seleccion.Servicios.Pruebas');
        /*Rutas de Vistas del index de Solicitudes para seleccionar manifiesto*/
        Route::post('/Seleccion/indexManifiesto', [ReporteController::class, 'indexManifiesto'])->name('Seleccion.indexManifiesto');
        /*Ruta Para pasar las variables al reporte*/
        Route::post('/Create/Reporte', [ReporteController::class, 'CreateReporte'])->name('Create.Reporte');
        /*Ruta para crear un nuevo reporte desde el modal del listado*/
        Route::post('/Nuevo/Reporte/DesdeModal/{id}', [ReporteController::class, 'CrearNuevoReporteDesdeModal'])->name('Nuevo.Reporte.DesdeModal');
        /*Ruta Para pasar las variables al reporte*/
        Route::get('/Editar/Reporte/{id}', [ReporteController::class, 'Edicion_Reportes'])->name('Editar.Reporte');
        /*Ruta del botón del eliminar del index de indexINS2 */
        Route::delete('/Eliminar/Reporte/Tabla/{id}', [ReporteController::class, 'destroyReportes'])->name('Eliminar.Reporte.Tabla');

        /*API para obtener el siguiente contrato interno*/
        Route::get('/api/siguiente-contrato-interno', [ReporteController::class, 'obtenerSiguienteContratoInterno']);

        /*Ruta de Guardado Reportes/INS*/
        /*Ruta de Guardado Reportes/PINS FOR_PINS_04_01*/
        Route::post('/Reportes_FOR_PINS_04_01/store', [FOR_PINS_04_01Controller::class, 'FOR_PINS_04_01_store'])->name('Reportes_FOR_PINS_04_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_04_01*/
        Route::post('/Reportes_FOR_PINS_04_01/update/{id}', [FOR_PINS_04_01Controller::class, 'FOR_PINS_04_01_update'])->name('Reportes_FOR_PINS_04_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_04_01*/
        Route::get('/Reporte/FOR-PINS-04_01/PDF/{id}', [FOR_PINS_04_01Controller::class, 'FOR_PINS_04_01'])->name('Reporte_FOR_PINS_04_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_05_01*/
        Route::post('/Reportes_FOR_PINS_05_01/store', [FOR_PINS_05_01Controller::class, 'FOR_PINS_05_01_store'])->name('Reportes_FOR_PINS_05_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_05_01*/
        Route::post('/Reportes_FOR_PINS_05_01/update/{id}', [FOR_PINS_05_01Controller::class, 'FOR_PINS_05_01_update'])->name('Reportes_FOR_PINS_05_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_05_01*/
        Route::get('/Reporte/FOR-PINS-05_01/PDF/{id}', [FOR_PINS_05_01Controller::class, 'FOR_PINS_05_01'])->name('Reporte_FOR_PINS_05_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_06_01*/
        Route::post('/Reportes_FOR_PINS_06_01/store', [FOR_PINS_06_01Controller::class, 'FOR_PINS_06_01_store'])->name('Reportes_FOR_PINS_06_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_06_01*/
        Route::post('/Reportes_FOR_PINS_06_01/update/{id}', [FOR_PINS_06_01Controller::class, 'FOR_PINS_06_01_update'])->name('Reportes_FOR_PINS_06_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_06_01*/
        Route::get('/Reporte/FOR-PINS-06_01/PDF/{id}', [FOR_PINS_06_01Controller::class, 'FOR_PINS_06_01'])->name('Reporte_FOR_PINS_06_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_07_01*/
        Route::post('/Reportes_FOR_PINS_07_01/store', [FOR_PINS_07_01Controller::class, 'FOR_PINS_07_01_store'])->name('Reportes_FOR_PINS_07_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_07_01*/
        Route::post('/Reportes_FOR_PINS_07_01/update/{id}', [FOR_PINS_07_01Controller::class, 'FOR_PINS_07_01_update'])->name('Reportes_FOR_PINS_07_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_07_01*/
        Route::get('/Reporte/FOR-PINS-07_01/PDF/{id}', [FOR_PINS_07_01Controller::class, 'FOR_PINS_07_01'])->name('Reporte_FOR_PINS_07_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_08_01*/
        Route::post('/Reportes_FOR_PINS_08_01/store', [FOR_PINS_08_01Controller::class, 'FOR_PINS_08_01_store'])->name('Reportes_FOR_PINS_08_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_08_01*/
        Route::post('/Reportes_FOR_PINS_08_01/update/{id}', [FOR_PINS_08_01Controller::class, 'FOR_PINS_08_01_update'])->name('Reportes_FOR_PINS_08_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_08_01*/
        Route::get('/Reporte/FOR-PINS-08_01/PDF/{id}', [FOR_PINS_08_01Controller::class, 'FOR_PINS_08_01'])->name('Reporte_FOR_PINS_08_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_09_01*/
        Route::post('/Reportes_FOR_PINS_09_01/store', [FOR_PINS_09_01Controller::class, 'FOR_PINS_09_01_store'])->name('Reportes_FOR_PINS_09_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_09_01*/
        Route::post('/Reportes_FOR_PINS_09_01/update/{id}', [FOR_PINS_09_01Controller::class, 'FOR_PINS_09_01_update'])->name('Reportes_FOR_PINS_09_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_09_01*/
        Route::get('/Reporte/FOR-PINS-09_01/PDF/{id}', [FOR_PINS_09_01Controller::class, 'FOR_PINS_09_01'])->name('Reporte_FOR_PINS_09_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_10_01*/
        Route::post('/Reportes_FOR_PINS_10_01/store', [FOR_PINS_10_01Controller::class, 'FOR_PINS_10_01_store'])->name('Reportes_FOR_PINS_10_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_10_01*/
        Route::post('/Reportes_FOR_PINS_10_01/update/{id}', [FOR_PINS_10_01Controller::class, 'FOR_PINS_10_01_update'])->name('Reportes_FOR_PINS_10_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_10_01*/
        Route::get('/Reporte/FOR-PINS-10_01/PDF/{id}', [FOR_PINS_10_01Controller::class, 'FOR_PINS_10_01'])->name('Reporte_FOR_PINS_10_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_11_01*/
        Route::post('/Reportes_FOR_PINS_11_01/store', [FOR_PINS_11_01Controller::class, 'FOR_PINS_11_01_store'])->name('Reportes_FOR_PINS_11_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_11_01*/
        Route::post('/Reportes_FOR_PINS_11_01/update/{id}', [FOR_PINS_11_01Controller::class, 'FOR_PINS_11_01_update'])->name('Reportes_FOR_PINS_11_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_11_01*/
        Route::get('/Reporte/FOR-PINS-11_01/PDF/{id}', [FOR_PINS_11_01Controller::class, 'FOR_PINS_11_01'])->name('Reporte_FOR_PINS_11_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_12_01*/
        Route::post('/Reportes_FOR_PINS_12_01/store', [FOR_PINS_12_01Controller::class, 'FOR_PINS_12_01_store'])->name('Reportes_FOR_PINS_12_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_12_01*/
        Route::post('/Reportes_FOR_PINS_12_01/update/{id}', [FOR_PINS_12_01Controller::class, 'FOR_PINS_12_01_update'])->name('Reportes_FOR_PINS_12_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_12_01*/
        Route::get('/Reporte/FOR-PINS-12-01/PDF/{id}', [FOR_PINS_12_01Controller::class, 'FOR_PINS_12_01'])->name('Reporte_FOR_PINS_12_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_13_01*/
        Route::post('/Reportes_FOR_PINS_13_01/store', [FOR_PINS_13_01Controller::class, 'FOR_PINS_13_01_store'])->name('Reportes_FOR_PINS_13_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_13_01*/
        Route::post('/Reportes_FOR_PINS_13_01/update/{id}', [FOR_PINS_13_01Controller::class, 'FOR_PINS_13_01_update'])->name('Reportes_FOR_PINS_13_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_13_01*/
        Route::get('/Reporte/FOR-PINS-13-01/PDF/{id}', [FOR_PINS_13_01Controller::class, 'FOR_PINS_13_01'])->name('Reporte_FOR_PINS_13_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_14_01*/
        Route::post('/Reportes_FOR_PINS_14_01/store', [FOR_PINS_14_01Controller::class, 'FOR_PINS_14_01_store'])->name('Reportes_FOR_PINS_14_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_14_01*/
        Route::post('/Reportes_FOR_PINS_14_01/update/{id}', [FOR_PINS_14_01Controller::class, 'FOR_PINS_14_01_update'])->name('Reportes_FOR_PINS_14_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_14_01*/
        Route::get('/Reporte/FOR-PINS-14-01/PDF/{id}', [FOR_PINS_14_01Controller::class, 'FOR_PINS_14_01'])->name('Reporte_FOR_PINS_14_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_15_01*/
        Route::post('/Reportes_FOR_PINS_15_01/store', [FOR_PINS_15_01Controller::class, 'FOR_PINS_15_01_store'])->name('Reportes_FOR_PINS_15_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_15_01*/
        Route::post('/Reportes_FOR_PINS_15_01/update/{id}', [FOR_PINS_15_01Controller::class, 'FOR_PINS_15_01_update'])->name('Reportes_FOR_PINS_15_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_15_01*/
        Route::get('/Reporte/FOR-PINS-15-01/PDF/{id}', [FOR_PINS_15_01Controller::class, 'FOR_PINS_15_01'])->name('Reporte_FOR_PINS_15_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_16_01*/
        Route::post('/Reportes_FOR_PINS_16_01/store', [FOR_PINS_16_01Controller::class, 'FOR_PINS_16_01_store'])->name('Reportes_FOR_PINS_16_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_16_01*/
        Route::post('/Reportes_FOR_PINS_16_01/update/{id}', [FOR_PINS_16_01Controller::class, 'FOR_PINS_16_01_update'])->name('Reportes_FOR_PINS_16_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_16_01*/
        Route::get('/Reporte/FOR-PINS-16-01/PDF/{id}', [FOR_PINS_16_01Controller::class, 'FOR_PINS_16_01'])->name('Reporte_FOR_PINS_16_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_17_01*/
        Route::post('/Reportes_FOR_PINS_17_01/store', [FOR_PINS_17_01Controller::class, 'FOR_PINS_17_01_store'])->name('Reportes_FOR_PINS_17_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_17_01*/
        Route::post('/Reportes_FOR_PINS_17_01/update/{id}', [FOR_PINS_17_01Controller::class, 'FOR_PINS_17_01_update'])->name('Reportes_FOR_PINS_17_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_17_01*/
        Route::get('/Reporte/FOR-PINS-17-01/PDF/{id}', [FOR_PINS_17_01Controller::class, 'FOR_PINS_17_01'])->name('Reporte_FOR_PINS_17_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_17_01_01*/
        Route::post('/Reportes_FOR_PINS_17_01_01/store', [FOR_PINS_17_01_01Controller::class, 'FOR_PINS_17_01_01_store'])->name('Reportes_FOR_PINS_17_01_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_17_01_01*/
        Route::post('/Reportes_FOR_PINS_17_01_01/update/{id}', [FOR_PINS_17_01_01Controller::class, 'FOR_PINS_17_01_01_update'])->name('Reportes_FOR_PINS_17_01_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_17_01_01*/
        Route::get('/Reporte/FOR-PINS-17-01-01/PDF/{id}', [FOR_PINS_17_01_01Controller::class, 'FOR_PINS_17_01_01'])->name('Reporte_FOR_PINS_17_01_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_18_01*/
        Route::post('/Reportes_FOR_PINS_18_01/store', [FOR_PINS_18_01Controller::class, 'FOR_PINS_18_01_store'])->name('Reportes_FOR_PINS_18_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_18_01*/
        Route::post('/Reportes_FOR_PINS_18_01/update/{id}', [FOR_PINS_18_01Controller::class, 'FOR_PINS_18_01_update'])->name('Reportes_FOR_PINS_18_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_18_01*/
        Route::get('/Reporte/FOR-PINS-18-01/PDF/{id}', [FOR_PINS_18_01Controller::class, 'FOR_PINS_18_01'])->name('Reporte_FOR_PINS_18_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_19_01*/
        Route::post('/Reportes_FOR_PINS_19_01/store', [FOR_PINS_19_01Controller::class, 'FOR_PINS_19_01_store'])->name('Reportes_FOR_PINS_19_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_19_01*/
        Route::post('/Reportes_FOR_PINS_19_01/update/{id}', [FOR_PINS_19_01Controller::class, 'FOR_PINS_19_01_update'])->name('Reportes_FOR_PINS_19_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_19_01*/
        Route::get('/Reporte/FOR-PINS-19-01/PDF/{id}', [FOR_PINS_19_01Controller::class, 'FOR_PINS_19_01'])->name('Reporte_FOR_PINS_19_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_20_01*/
        Route::post('/Reportes_FOR_PINS_20_01/store', [FOR_PINS_20_01Controller::class, 'FOR_PINS_20_01_store'])->name('Reportes_FOR_PINS_20_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_20_01*/
        Route::post('/Reportes_FOR_PINS_20_01/update/{id}', [FOR_PINS_20_01Controller::class, 'FOR_PINS_20_01_update'])->name('Reportes_FOR_PINS_20_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_20_01*/
        Route::get('/Reporte/FOR-PINS-20-01/PDF/{id}', [FOR_PINS_20_01Controller::class, 'FOR_PINS_20_01'])->name('Reporte_FOR_PINS_20_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_21_01*/
        Route::post('/Reportes_FOR_PINS_21_01/store', [FOR_PINS_21_01Controller::class, 'FOR_PINS_21_01_store'])->name('Reportes_FOR_PINS_21_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_21_01*/
        Route::post('/Reportes_FOR_PINS_21_01/update/{id}', [FOR_PINS_21_01Controller::class, 'FOR_PINS_21_01_update'])->name('Reportes_FOR_PINS_21_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_21_01*/
        Route::get('/Reporte/FOR-PINS-21-01/PDF/{id}', [FOR_PINS_21_01Controller::class, 'FOR_PINS_21_01'])->name('Reporte_FOR_PINS_21_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_22_01*/
        Route::post('/Reportes_FOR_PINS_22_01/store', [FOR_PINS_22_01Controller::class, 'FOR_PINS_22_01_store'])->name('Reportes_FOR_PINS_22_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_22_01*/
        Route::post('/Reportes_FOR_PINS_22_01/update/{id}', [FOR_PINS_22_01Controller::class, 'FOR_PINS_22_01_update'])->name('Reportes_FOR_PINS_22_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_22_01*/
        Route::get('/Reporte/FOR-PINS-22-01/PDF/{id}', [FOR_PINS_22_01Controller::class, 'FOR_PINS_22_01'])->name('Reporte_FOR_PINS_22_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_23_01*/
        Route::post('/Reportes_FOR_PINS_23_01/store', [FOR_PINS_23_01Controller::class, 'FOR_PINS_23_01_store'])->name('Reportes_FOR_PINS_23_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_23_01*/
        Route::post('/Reportes_FOR_PINS_23_01/update/{id}', [FOR_PINS_23_01Controller::class, 'FOR_PINS_23_01_update'])->name('Reportes_FOR_PINS_23_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_23_01*/
        Route::get('/Reporte/FOR-PINS-23-01/PDF/{id}', [FOR_PINS_23_01Controller::class, 'FOR_PINS_23_01'])->name('Reporte_FOR_PINS_23_01.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_24_01*/
        Route::post('/Reportes_FOR_PINS_24_01/store', [FOR_PINS_24_01Controller::class, 'FOR_PINS_24_01_store'])->name('Reportes_FOR_PINS_24_01.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_24_01*/
        Route::post('/Reportes_FOR_PINS_24_01/update/{id}', [FOR_PINS_24_01Controller::class, 'FOR_PINS_24_01_update'])->name('Reportes_FOR_PINS_24_01.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_24_01*/
        Route::get('/Reporte/FOR-PINS-24-01/PDF/{id}', [FOR_PINS_24_01Controller::class, 'FOR_PINS_24_01'])->name('Reporte_FOR_PINS_24_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_25_01*/
        Route::post('/Reportes_FOR_PINS_25_01/store', [FOR_PINS_25_01Controller::class, 'FOR_PINS_25_01_store'])->name('Reportes_FOR_PINS_25_01.store');
        /*Ruta de Actualización Reportes/INS FOR_PINS_25_01*/
        Route::post('/Reportes_FOR_PINS_25_01/update/{id}', [FOR_PINS_25_01Controller::class, 'FOR_PINS_25_01_update'])->name('Reportes_FOR_PINS_25_01.update');
        /*Ruta del PDF de Reportes/INS FOR_PINS_25_01*/
        Route::get('/Reporte/FOR-01-PINS-25-01/PDF/{id}', [FOR_PINS_25_01Controller::class, 'FOR_PINS_25_01'])->name('Reporte_FOR_PINS_25_01.PDF');
        
        /*Ruta de Guardado Reportes/PINS FOR_PINS_03_02*/
        Route::post('/Reportes_FOR_PINS_03_02/store', [FOR_PINS_03_02Controller::class, 'FOR_PINS_03_02_store'])->name('Reportes_FOR_PINS_03_02.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_03_02*/
        Route::post('/Reportes_FOR_PINS_03_02/update/{id}', [FOR_PINS_03_02Controller::class, 'FOR_PINS_03_02_update'])->name('Reportes_FOR_PINS_03_02.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_03_02*/
        Route::get('/Reporte/FOR-PINS-03_02/PDF/{id}', [FOR_PINS_03_02Controller::class, 'FOR_PINS_03_02'])->name('Reporte_FOR_PINS_03_02.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_05_02*/
        Route::post('/Reportes_FOR_PINS_05_02/store', [FOR_PINS_05_02Controller::class, 'FOR_PINS_05_02_store'])->name('Reportes_FOR_PINS_05_02.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_05_02*/
        Route::post('/Reportes_FOR_PINS_05_02/update/{id}', [FOR_PINS_05_02Controller::class, 'FOR_PINS_05_02_update'])->name('Reportes_FOR_PINS_05_02.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_05_02*/
        Route::get('/Reporte/FOR-PINS-05_02/PDF/{id}', [FOR_PINS_05_02Controller::class, 'FOR_PINS_05_02'])->name('Reporte_FOR_PINS_05_02.PDF');

        /*Ruta de Guardado Reportes/PINS FOR_PINS_11_02*/
        Route::post('/Reportes_FOR_PINS_11_02/store', [FOR_PINS_11_02Controller::class, 'FOR_PINS_11_02_store'])->name('Reportes_FOR_PINS_11_02.store');
        /*Ruta de Actualización Reportes/PINS FOR_PINS_11_02*/
        Route::post('/Reportes_FOR_PINS_11_02/update/{id}', [FOR_PINS_11_02Controller::class, 'FOR_PINS_11_02_update'])->name('Reportes_FOR_PINS_11_02.update');
        /*Ruta del PDF de Reportes/PINS FOR_PINS_11_02*/
        Route::get('/Reporte/FOR-PINS-11/02/PDF/{id}', [FOR_PINS_11_02Controller::class, 'FOR_PINS_11_02'])->name('Reporte_FOR_PINS_11_02.PDF');

        /*Ruta de Guardado Reportes/INS FOR_03_PRO_INS_15*/
        Route::post('/Reportes_FOR_03_PRO_INS_15/store', [FOR_03_PRO_INS_15Controller::class, 'FOR_03_PRO_INS_15_store'])->name('Reportes_FOR_03_PRO_INS_15.store');
        /*Ruta de Actualización Reportes/INS FOR_03_PRO_INS_15*/
        Route::post('/Reportes_FOR_03_PRO_INS_15/update/{id}', [FOR_03_PRO_INS_15Controller::class, 'FOR_03_PRO_INS_15_update'])->name('Reportes_FOR_03_PRO_INS_15.update');
        /*Ruta del PDF de Reportes/INS FOR_03_PRO_INS_15*/
        Route::get('/Reporte/FOR-03-INS-15/PDF/{id}', [FOR_03_PRO_INS_15Controller::class, 'FOR_03_INS_15'])->name('Reporte_FOR_03_INS_15.PDF');
        
        /*Ruta de Guardado Reportes/IM*/
        /*Ruta de Guardado Reportes/IM FOR_PIMP_02_B_03*/
        Route::post('/Reportes_FOR_PIMP_02_B_03/store', [FOR_PIMP_02_B_03Controller::class, 'FOR_PIMP_02_B_03_store'])->name('Reportes_FOR_PIMP_02_B_03.store');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_02_B_03*/
        Route::post('/Reportes_FOR_PIMP_02_B_03/update/{id}', [FOR_PIMP_02_B_03Controller::class, 'FOR_PIMP_02_B_03_update'])->name('Reportes_FOR_PIMP_02_B_03.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_02_B_03*/
        Route::get('/Reporte/FOR_PIMP_02_B_03/PDF/{id}', [FOR_PIMP_02_B_03Controller::class, 'FOR_PIMP_02_B_03'])->name('Reporte_FOR_PIMP_02_B_03.PDF');

        /*Ruta de Guardado Reportes/IM FOR_PIMP_02_B_04*/
        Route::post('/Reportes_FOR_PIMP_02_B_04/store', [FOR_PIMP_02_B_04Controller::class, 'FOR_PIMP_02_B_04_store'])->name('Reportes_FOR_PIMP_02_B_04.store');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_02_B_04*/
        Route::post('/Reportes_FOR_PIMP_02_B_04/update/{id}', [FOR_PIMP_02_B_04Controller::class, 'FOR_PIMP_02_B_04_update'])->name('Reportes_FOR_PIMP_02_B_04.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_02_B_04*/
        Route::get('/Reporte/FOR_PIMP_02_B_04/PDF/{id}', [FOR_PIMP_02_B_04Controller::class, 'FOR_PIMP_02_B_04'])->name('Reporte_FOR_PIMP_02_B_04.PDF');

        /*Ruta de Guardado Reportes/IM FOR_PIMP_03_B_01*/
        Route::post('/Reportes_FOR_PIMP_03_B_01/store', [FOR_PIMP_03_B_01Controller::class, 'FOR_PIMP_03_B_01_store'])->name('Reportes_FOR_PIMP_03_B_01.store');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_03_B_01*/
        Route::post('/Reportes_FOR_PIMP_03_B_01/update/{id}', [FOR_PIMP_03_B_01Controller::class, 'FOR_PIMP_03_B_01_update'])->name('Reportes_FOR_PIMP_03_B_01.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_03_B_01*/
        Route::get('/Reporte/FOR_PIMP_03_B_01/PDF/{id}', [FOR_PIMP_03_B_01Controller::class, 'FOR_PIMP_03_B_01'])->name('Reporte_FOR_PIMP_03_B_01.PDF');
        
        /*Ruta de Guardado Reportes/IM FOR_PIMP_04_02*/
        Route::post('/Reportes_FOR_PIMP_04_02/store', [FOR_PIMP_04_02Controller::class, 'FOR_PIMP_04_02_store'])->name('Reportes_FOR_PIMP_04_02.store');
        /*Vista previa: valida norma, extrae lecturas y devuelve recortes sin guardar el reporte.*/
        Route::post('/Reportes_FOR_PIMP_04_02/extraer-analisis', [ProcesamientoXrfController::class, 'encolarColumnas'])->name('Reportes_FOR_PIMP_04_02.extraer_analisis');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_04_02*/
        Route::post('/Reportes_FOR_PIMP_04_02/update/{id}', [FOR_PIMP_04_02Controller::class, 'FOR_PIMP_04_02_update'])->name('Reportes_FOR_PIMP_04_02.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_04_02*/
        Route::get('/Reporte/FOR_PIMP_04_02/PDF/{id}', [FOR_PIMP_04_02Controller::class, 'FOR_PIMP_04_02'])->name('Reporte_FOR_PIMP_04_02.PDF');

        /*Ruta de Guardado Reportes/IM FOR_PIMP_04_03*/
        Route::post('/Reportes_FOR_PIMP_04_03/store', [FOR_PIMP_04_03Controller::class, 'FOR_PIMP_04_03_store'])->name('Reportes_FOR_PIMP_04_03.store');
        /*Vista previa: valida norma, extrae lecturas y devuelve recortes sin guardar el reporte.*/
        Route::post('/Reportes_FOR_PIMP_04_03/extraer-analisis', [ProcesamientoXrfController::class, 'encolarMultiple'])->name('Reportes_FOR_PIMP_04_03.extraer_analisis');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_04_03*/
        Route::post('/Reportes_FOR_PIMP_04_03/update/{id}', [FOR_PIMP_04_03Controller::class, 'FOR_PIMP_04_03_update'])->name('Reportes_FOR_PIMP_04_03.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_04_03*/
        Route::get('/Reporte/FOR_PIMP_04_03/PDF/{id}', [FOR_PIMP_04_03Controller::class, 'FOR_PIMP_04_03'])->name('Reporte_FOR_PIMP_04_03.PDF');

        /*Ruta de Guardado Reportes/IM FOR_PIMP_05_01*/
        Route::post('/Reportes_FOR_PIMP_05_01/store', [FOR_PIMP_05_01Controller::class, 'FOR_PIMP_05_01_store'])->name('Reportes_FOR_PIMP_05_01.store');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_05_01*/
        Route::post('/Reportes_FOR_PIMP_05_01/update/{id}', [FOR_PIMP_05_01Controller::class, 'FOR_PIMP_05_01_update'])->name('Reportes_FOR_PIMP_05_01.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_05_01*/
        Route::get('/Reporte/FOR_PIMP_05_01/PDF/{id}', [FOR_PIMP_05_01Controller::class, 'FOR_PIMP_05_01'])->name('Reporte_FOR_PIMP_05_01.PDF');

        /*Ruta de Guardado Reportes/IM FOR_PIMP_05_B_01*/
        Route::post('/Reportes_FOR_PIMP_05_B_01/store', [FOR_PIMP_05_B_01Controller::class, 'FOR_PIMP_05_B_01_store'])->name('Reportes_FOR_PIMP_05_B_01.store');
        /*Vista previa XRF: extrae las tres columnas seleccionadas sin guardar el reporte.*/
        Route::post('/Reportes_FOR_PIMP_05_B_01/extraer-analisis', [ProcesamientoXrfController::class, 'encolarColumnas'])->name('Reportes_FOR_PIMP_05_B_01.extraer_analisis');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_05_B_01*/
        Route::post('/Reportes_FOR_PIMP_05_B_01/update/{id}', [FOR_PIMP_05_B_01Controller::class, 'FOR_PIMP_05_B_01_update'])->name('Reportes_FOR_PIMP_05_B_01.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_05_B_01*/
        Route::get('/Reporte/FOR_PIMP_05_B_01/PDF/{id}', [FOR_PIMP_05_B_01Controller::class, 'FOR_PIMP_05_B_01'])->name('Reporte_FOR_PIMP_05_B_01.PDF');

        /*Ruta de Guardado Reportes/IM FOR_PIMP_06_B_01*/
        Route::post('/Reportes_FOR_PIMP_06_B_01/store', [FOR_PIMP_06_B_01Controller::class, 'FOR_PIMP_06_B_01_store'])->name('Reportes_FOR_PIMP_06_B_01.store');
        /*Vista previa XRF independiente para el formato 06_B_01.*/
        Route::post('/Reportes_FOR_PIMP_06_B_01/extraer-analisis', [ProcesamientoXrfController::class, 'encolarMultiple'])->name('Reportes_FOR_PIMP_06_B_01.extraer_analisis');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_06_B_01*/
        Route::post('/Reportes_FOR_PIMP_06_B_01/update/{id}', [FOR_PIMP_06_B_01Controller::class, 'FOR_PIMP_06_B_01_update'])->name('Reportes_FOR_PIMP_06_B_01.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_06_B_01*/
        Route::get('/Reporte/FOR_PIMP_06_B_01/PDF/{id}', [FOR_PIMP_06_B_01Controller::class, 'FOR_PIMP_06_B_01'])->name('Reporte_FOR_PIMP_06_B_01.PDF');


        /*Ruta de Guardado Reportes/IM*/
        /*Ruta de Guardado Reportes/IM FOR_PIMP_07_B_01*/
        Route::post('/Reportes_FOR_PIMP_07_B_01/store', [FOR_PIMP_07_B_01Controller::class, 'FOR_PIMP_07_B_01_store'])->name('Reportes_FOR_PIMP_07_B_01.store');
        /*Ruta de Actualización Reportes/IM FOR_PIMP_07_B_01*/
        Route::post('/Reportes_FOR_PIMP_07_B_01/update/{id}', [FOR_PIMP_07_B_01Controller::class, 'FOR_PIMP_07_B_01_update'])->name('Reportes_FOR_PIMP_07_B_01.update');
        /*Ruta del PDF de Reportes/IM FOR_PIMP_07_B_01*/
        Route::get('/Reporte/FOR_PIMP_07_B_01/PDF/{id}', [FOR_PIMP_07_B_01Controller::class, 'FOR_PIMP_07_B_01'])->name('Reporte_FOR_PIMP_07_B_01.PDF');
        });
    });

    Route::middleware('auth')->group(function () {
        
    Route::middleware('can:tecnicos-equipos-lab-access')->group(function () {
    /*IMPORTAR EXCEL */
    Route::post('/importarEyC', [ExcelEyCController::class, 'importarExcel'])->name('importar.EyC');
    /*SOLICITUDES-1*/
    /*Rutas de Vistas de Solicitudes-Registro*/
    Route::get('/solicitud/create', [SolicitudesController::class, 'create'])->name('solicitud.create');
    /*Rutas de Vistas de Solicitudes-Tabla de Solicitud*/
    Route::get('/solicitud/index', [SolicitudesController::class, 'index'])->name('solicitud.index');

    /*SOLICITUD*/
    /*Ruta de Guardado-index*/
    Route::post('/solicitudes/storeSolicitud', [SolicitudesController::class, 'storeSolicitud'])->name('solicitudes.storeSolicitud');
    /*Ruta de botón Agregar-datos a detalles solicitud-por aprobar*/
    Route::post('/solicitudes/agregar', [SolicitudesController::class, 'agregarDetallesSolicitud'])->name('solicitudes.agregarDetallesSolicitud');
    /*Ruta de botón Eliminación-detalles_Solicitud-por aprobar*/
    Route::delete('/Detalles_solicitudes/eliminar/{id}', [SolicitudesController::class, 'destroyDetallesSolicitud'])->name('solicitudes.destroyDetallesSolicitud');
    /*Ruta de botón obtener-datos de detalles kits-Solicitud.create*/
    Route::get('/Obtener/Kits/{id}', [SolicitudesController::class, 'obtenerDetallesKits'])->name('Obtener.Kits');
    /*Ruta de botón obtener-datos de general_EyC para kits-Solicitud.create*/
    Route::get('/Obtener/generaleyc/{id}', [SolicitudesController::class, 'obtenerGeneralKits'])->name('Obtener.generaleyc');

    /*Ruta /Obtener/CantidadAlmacen/ de la vista create de equipos(Kits) y editkits*/
    Route::get('/Obtener/CantidadAlmacen/{id}', [AlmacenController::class, 'obtenerCantidadAlmacen']);
    
    /*manifiestos*/
    /*Ruta para ver la nueva versión del manifiesto PDF*/
    Route::get('Manifiesto/NewFormatPDF/{id}', [PDFController::class, 'generaManifiestoNewFormatPDF'])->name('Manifiesto.NewFormat.pdf');
    });
    
    /*EQUIPOS INVENTARIO-REGISTRO*/
    Route::middleware('can:equipos-lab-access')->group(function () {
    /*DEVOLUCIONES*/
    /*Rutas de Devolución para listar y devolver*/
    Route::get('/devolucion/EyC/{id}', [DevolucionController::class, 'editDevolucionListado'])->name('devolucion.EyC');
    /*Ruta para devolver los articulos de la lista al almacen */
    Route::post('/devolver-item', [DevolucionController::class, 'devolverItem'])->name('devolver.item');
    /*Ruta para devolver Todos los items*/
    Route::post('/manifiesto/devolver-todo', [DevolucionController::class, 'devolverTodo'])->name('DevolverTodo.Manifiesto');
    /*GENERAL EYC*/
    /*Rutas de Vistas Equipos y Consumibles-Tabla General*/
    Route::get('/inventario', [general_eycController::class, 'index'])->name('inventario');
    /*Rutas de Vistas Equipos y Consumibles-Tabla General*/
    //Route::get('/Calibraciones', [general_eycController::class, 'index'])->name('Calibraciones');
    /*Rutas de Vistas Equipos y Consumibles-Tabla General*/
    //Route::get('/Mantenimientos', [general_eycController::class, 'index'])->name('Mantenimientos');
    /*Rutas de Vistas Equipos y Consumibles-Registro*/
    Route::get('/registros/createEyC', [general_eycController::class, 'createEquipos'])->name('registros.createEyC');
    /*Rutas de Vistas Equipos y Consumibles-Edición*/
    Route::get('/edicion/editEyC/{id}', [general_eycController::class, 'editEyC'])->name('edicion.editEyC');
    /*Ruta para dar de BAJA, equipos, comsumibles, block, herramientas-HABILITADO*/
    Route::delete('/eliminar/BajaEyC/{id}', [general_eycController::class, 'BajaEyC'])->name('eliminar.BajaEyC');
    /*Ruta para verificar duplicados de los Equipos de No economico y Serie de la tabla general_EyC*/
    Route::post('/verificar-duplicado-Equipos', [general_eycController::class, 'verificarDuplicadoEquipos']);
    /*Ruta para verificar duplicados de los Accesorios de No economico y Serie de la tabla general_EyC*/
    Route::post('/verificar-duplicado-Accesorios', [general_eycController::class, 'verificarDuplicadoAccesorios']);
    /*Ruta para verificar duplicados de los Block y Probeta de No economico y Serie de la tabla general_EyC*/
    Route::post('/verificar-duplicado-BlockyProbeta', [general_eycController::class, 'verificarDuplicadoBlockyProbeta']);
    /*Ruta para verificar duplicados de las Herramientas de No economico y Serie de la tabla general_EyC*/
    Route::post('/verificar-duplicado-Herramientas', [general_eycController::class, 'verificarDuplicadoHerramientas']);

    /*TICS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeTICS', [TICSController::class, 'storeTICS'])->name('general_eyc.storeTICS');
    /*Ruta de Actualizar*/
    Route::post('/edicion/editTICS/{id}', [TICSController::class, 'updateTICS'])->name('editTICS.update');

    /*EQUIPOS */
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeEquipos', [equiposController::class, 'storeEquipos'])->name('general_eyc.storeEquipos');
    /*Ruta de Actualizar*/
    Route::post('/edicion/editEquipos/{id}', [equiposController::class, 'updateEquipos'])->name('editEquipos.update');

    /*CONSUMIBLES*/
    /*Ruta de Guardado*/
    Route::post('general_eyc/storeConsumibles', [consumiblesController::class, 'storeConsumibles'])->name('general_eyc.storeConsumibles'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editConsumibles/{id}', [consumiblesController::class, 'updateConsumibles'])->name('editConsumibles.update');

    /*ACCESORIOS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeAccesorios', [AccesoriosController::class, 'storeAccesorios'])->name('general_eyc.storeAccesorios'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editAccesorios/{id}', [AccesoriosController::class, 'updateAccesorios'])->name('editAccesorios.update');

    /*BLOCKS Y PROBETA*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeBlocks', [BlockYProbetaController::class, 'storeBlocks'])->name('general_eyc.storeBlocks'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editBlocks/{id}', [BlockYProbetaController::class, 'updateBlocks'])->name('editBlocks.update');

    /*HERRAMIENTAS*/
    /*Ruta de Guardado*/
    Route::post('/general_eyc/storeHerramientas', [HerramientasController::class, 'storeHerramientas'])->name('general_eyc.storeHerramientas'); 
    /*Ruta de Actualizar*/
    Route::post('/edicion/editHerramientas/{id}', [HerramientasController::class, 'updateHerramientas'])->name('editHerramientas.update');

    /*HISTORIAL CERTIFICADOS*/
    /*Ruta de Vista de historial de certificados*/
    Route::get('/Historial_certificados/index', [CertificadosController::class, 'index'])->name('Historial_certificados.index');

    /*KITS*/
    /*Rutas de Vistas KITS-Tabla KITS*/
    Route::get('/index/Kits', [KitsController::class, 'indexKits'])->name('index.Kits');
    /*Rutas de Vistas KITS-Edición*/
    Route::get('/edicion/editKits/{id}', [KitsController::class, 'editKits'])->name('edicion.editKits');

    /*Ruta de botón Guardado-Alta*/
    Route::post('/GuardarKits/agregarKits', [KitsController::class, 'GuardarKits'])->name('GuardarKits.agregarKits');
    /*Ruta de Eliminación-de Kits-Index*/
    Route::delete('/eliminar/Kits/{id}', [KitsController::class, 'destroyKits'])->name('eliminar.Kits');
    /*Refrecar la tabla de inventario en Kits */
    Route::get('/obtenerDatos/Actualizados', [KitsController::class, 'obtenerDatosActualizados'])->name('obtenerDatos.Actualizados');

    /*Ruta de botón Agregar-datos a detalles kits-edición*/
    Route::post('/kits/agregar', [KitsController::class, 'agregarDetallesKits'])->name('Kits.agregarDetallesKits');
    /*Ruta de botón Eliminación-detalles_Kits-edición*/
    Route::delete('/Detalles_Kits/eliminar/{id}', [KitsController::class, 'destroyDetallesKits'])->name('Kits.destroyDetallesKits');
    /*Ruta de botón Guardar-updateKits-edición*/
    Route::post('/Update/kits/{id}', [KitsController::class, 'updateKits'])->name('kits.update');

    /*SOLICITUDES-PLUS*/
    /*Rutas de Vistas de Solicitudes-Edición-index*/
    Route::get('/solicitud/edit/{id}', [SolicitudesController::class, 'edit'])->name('solicitud.edit');
    /*Rutas de controlador para duplicar los datos y redirigir*/
    Route::get('/solicitudplus/edit/{id}', [SolicitudesController::class, 'editplus'])->name('solicitudplus.edit');
    /*Rutas de controlador que recibe el id del duplicado*/
    Route::get('/solicitudplusvista/edit/{id}', [SolicitudesController::class, 'editplusvista'])->name('solicitudplusvista.edit');
    /*Ruta de Eliminación-de Solicitud-index*/
    Route::delete('/solicitudes/eliminar/{id}', [SolicitudesController::class, 'destroySolicitud'])->name('solicitudes.destroySolicitud');
    /* */
    Route::get('/solicitudindex/solicitud/', [SolicitudesController::class, 'SolicitudIndex'])->name('solicitud.solicitudindex');


    /*MANIFIESTO*/
    /*Rutas de Vistas de Solicitudes-Aprobar solicitudes*/
    Route::post('/solicitud/Manifiesto/{id}', [ManifiestoController::class, 'create'])->name('solicitud.manifiesto');
    /*Rutas de Vistas de Solicitudes-Aprobar solicitudes*/
    Route::post('/solicitudplus/Manifiestoplus/{id}', [ManifiestoController::class, 'createplus'])->name('solicitudplus.manifiestoplus');
    /*Rutas de Vistas de Solicitudes-Pre-Manifiesto(Botón Regresar)*/
    Route::get('/solicitud/Manifiesto-Regresar/{id}', [ManifiestoController::class, 'BotonRegresar'])->name('solicitud.manifiesto-regresar');
    /*Ruta de Guardado*/
    Route::post('/solicitudes/Manifiesto', [ManifiestoController::class, 'store'])->name('solicitudes.storeManifiesto');
    /*Ruta de Actualización*/
    Route::post('/solicitudes/updateSolicitud/{id}', [ManifiestoController::class, 'update'])->name('solicitudes.updateSolicitud');
    /*Ruta de Actualización-plus*/
    Route::post('/solicitudesplus/updateSolicitudplus/{id}', [ManifiestoController::class, 'updateplus'])->name('solicitudesplus.updateSolicitudplus');

    /*ruta para obtener el conteo de registros de manifiesto*/
    Route::get('/manifiestos/count', [ManifiestoController::class, 'getCount'])->name('manifiestos.count');

    /*PRE-CONCLUIR MANIFIESTO*/
    Route::post('/PreConcluir/Manifiesto/{id}', [ManifiestoController::class, 'PreConcluirManifiesto'])->name('PreConcluir.Manifiesto');
    /*CONCLUIR MANIFIESTO*/
    Route::post('/Concluir/Manifiesto/{id}', [ManifiestoController::class, 'ConcluirManifiesto'])->name('Concluir.Manifiesto');

    /*HISTORIAL ALMACEN*/
    /*Rutas de Vistas de Solicitudes-Tabla de Solicitud*/
    Route::get('Historial_Almacen/index', [HistorialAlmacenController::class, 'index'])->name('Historial_Almacen.index');

    /*SOLICITAR RECURSOS*/
    Route::get('solicitar_recursos/create', [SolicitudRecursosController::class, 'create'])->name('solicitar_recursos.create');

    Route::get('/Manifiesto/create/{id}', [PDFController::class, 'generaManifiestoPDF'])->name('Manifiesto.pdf');

    /*CLIENTES*/
    /*Rutas de Vistas de Tabla de Clientes*/
    Route::get('/clientes/index', [ClientesController::class, 'index'])->name('clientes.index');
    /*Rutas de Vista para crear CLIENTES*/
    Route::get('/registro/create', [ClientesController::class, 'create'])->name('registro.create');
    /*Rutas de Vistas Clientes-Edición*/
    Route::get('/edicion/editclientes/{id}', [ClientesController::class, 'edit'])->name('edicion.editClientes');
    /*Ruta de Guardado Clientes*/
    Route::post('/registro/storeclientes', [ClientesController::class, 'store'])->name('registro.storeClientes');
    /*Ruta de Actualizar Clientes*/
    Route::post('/edicion/update/{id}', [ClientesController::class, 'update'])->name('editClientes.update');
    /*Ruta de botón Eliminación-index-Clientes*/
    Route::delete('/Clientes/eliminar/{id}', [ClientesController::class, 'destroy'])->name('Clientes.destroy');
    /*Ruta del portal de los Clientes*/
    Route::get('/portal/{token}', [ClientesController::class, 'Portal_index'])->name('portal.cliente');
    
    });

    /*admin */
    Route::middleware('can:administrador-access')->group(function () {
    /*admin */
    /*Ruta para ver los usuarios*/
    Route::get('/Admin/index', [UsuariosController::class, 'index'])->name('Admin/index');
    /*Ruta vista para alta e usuarios*/
    Route::get('/Admin/create', [UsuariosController::class, 'create'])->name('Admin/create');
    /*Ruta de Guardado Clientes*/
    Route::post('/registro/storeusuarios', [UsuariosController::class, 'store'])->name('registro.storeUsuarios');
    /*Rutas de Vistas Usuadrios-Edición*/
    Route::get('/edicion/editusuarios/{id}', [UsuariosController::class, 'edit'])->name('edicion.editUsuarios');
    /*Ruta de Actualizar Usuarios*/
    Route::post('/edicion/updateUsuario/{id}', [UsuariosController::class, 'update'])->name('editUsuarios.update');
    /*Ruta de botón Eliminación-index-Usuarios*/
    Route::delete('/Usuarios/eliminar/{id}', [UsuariosController::class, 'destroy'])->name('Usuarios.destroy');
    });

    Route::middleware('can:ventas-equipos-access')->group(function () {
    /*OC*/
    /*Ruta de Vista de OC-index*/
    Route::get('/OC/indexOC', [OCController::class, 'index'])->name('OC.indexOC');
    /*Ruta de Vista de Registro de OC*/
    Route::get('/OC/createOC', [OCController::class, 'create'])->name('OC.createOC');
    /*Ruta de Guardado*/
    Route::post('/OC/storeOC', [OCController::class, 'storeOC'])->name('OC.storeOC'); 
    /*Ruta de Actualizar OC*/
    Route::post('/OC/updateOC/{id}', [OCController::class, 'updateOC'])->name('OC.updateOC');

    /*Rutas de Vista de Edición-index*/
    Route::get('/OC/edit/{id}', [OCController::class, 'edit'])->name('OC.edit');
    /*Ruta de botón Eliminación-index-Usuarios*/
    Route::delete('/OC/eliminar/{id}', [OCController::class, 'destroy'])->name('OC.destroy');

    });
    
    /* VEHÍCULOS (SOLO ADMIN) 
    Route::middleware('can:vehiculos-admin-access')->group(function(){

    // CRUD Vehículos
    Route::prefix('vehiculos')->name('vehiculos.')->group(function(){
    Route::get('/',[VehiculoController::class,'index'])->name('index');
    Route::get('/create',[VehiculoController::class, 'create'])->name('create');
    Route::post('/',[VehiculoController::class, 'store'])->name('store');
    Route::get('/{id}/edit',[VehiculoController::class, 'edit'])->name('edit');
    Route::put('/{id}',[VehiculoController::class,'update'])->name('update');
    Route::delete('/{id}',[VehiculoController::class, 'destroy'])->name('destroy');
    });

    // Panel Vehicular (solo admin)
    Route::get('/salidas-vehiculos/panel',[PanelController::class, 'index'])->name('salidas.panel');
    Route::get('/salidas-vehiculos/rendimiento/pdf/{periodo}', [RendimientoExportController::class, 'pdf'])->name('salidas.rendimiento.pdf');
    Route::get('/salidas-vehiculos/rendimiento/excel/{periodo}', [RendimientoExportController::class, 'excel'])->name('salidas.rendimiento.excel');
    });

    //SALIDAS DE VEHÍCULOS (TODOS LOS ROLES)
    Route::prefix('salidas-vehiculos')->name('salidas.')->group(function(){

    Route::get('/',[SalidaVehiculoController::class, 'index'])->name('index');
    Route::get('/create',[SalidaVehiculoController::class, 'create'])->name('create');
    Route::post('/', [SalidaVehiculoController::class, 'store'])->name('store');
    Route::put('/{id}/finalizar',[SalidaVehiculoController::class, 'finalizar'])->name('finalizar');

    //Route::get('/{salida}/checklist-salida',[SalidaChecklistController::class, 'create'])->name('checklist.salida.create');
    Route::post('/{salida}/checklist-salida',[SalidaChecklistController::class, 'store'])->name('checklist.salida.store');
    Route::get('/{salida}/checklist-entrada',[SalidaChecklistController::class, 'createEntrada'])->name('checklist.entrada.create');
    Route::post('/{salida}/checklist-entrada',[SalidaChecklistController::class, 'storeEntrada'])->name('checklist.entrada.store');
    Route::get('/{salida}/checklist/{tipo}',[SalidaChecklistController::class, 'show'])->name('checklist.show');
    Route::get('/{salida}/checklist.pdf',[SalidaChecklistController::class,'pdf'])->name('salidas.checklist.pdf');

    Route::get('SV/{salida}/checklist-salida',[SalidaChecklistController::class, 'create'])->name('checklist.salida.create');

    }); */

    Route::middleware('can:vehiculos-admin-access')->group(function () {
        /*VEHICULOS*/
        /*Ruta de Vista de Vehiculos-index*/
        Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.home');
        Route::get('/vehiculos/index', [VehiculoController::class, 'index'])->name('vehiculos.index');
        /*Ruta de Vista de Registro de Vehiculos*/
        Route::get('/vehiculos/create', [VehiculoController::class, 'create'])->name('vehiculos.create');
        /*Ruta de Guardado Vehiculos*/
        Route::post('/vehiculos/store', [VehiculoController::class, 'store'])->name('vehiculos.store');
        /*Ruta de Actualizar Vehiculos*/
        Route::match(['post', 'put'], '/vehiculos/update/{id}', [VehiculoController::class, 'update'])->name('vehiculos.update');
        /*Rutas de Vista de Edicion-index*/
        Route::get('/vehiculos/edit/{id}', [VehiculoController::class, 'edit'])->name('vehiculos.edit');
        /*Ruta de boton Eliminacion-index-Vehiculos*/
        Route::delete('/vehiculos/delete/{id}', [VehiculoController::class, 'destroy'])->name('vehiculos.destroy');

        /*PANEL VEHICULAR*/
        /*Ruta de Vista de Panel Vehicular*/
        Route::get('/salidas-vehiculos/panel', [PanelController::class, 'index'])->name('salidas.panel');
        /*Ruta de Exportar Rendimiento en PDF*/
        Route::get('/salidas-vehiculos/rendimiento/pdf/{periodo}', [RendimientoExportController::class, 'pdf'])->name('salidas.rendimiento.pdf');
        /*Ruta de Exportar Rendimiento en Excel*/
        Route::get('/salidas-vehiculos/rendimiento/excel/{periodo}', [RendimientoExportController::class, 'excel'])->name('salidas.rendimiento.excel'); 
        /*Ruta PDF reporte mensual de movimientos de vehiculos*/
        Route::get('/vehiculos/reportes/movimientos-mensuales/pdf', [VehiculoController::class, 'movimientosMensualesPdf'])->name('vehiculos.reportes.movimientos.pdf');

        // MANTENIMIENTOS DEL VEHICULO
        // Lista mantenimientos de un vehículo (historial paginado).
        Route::get('/vehiculos/{vehiculo}/mantenimientos', [MantenimientoController::class, 'index'])->name('vehiculos.mantenimientos.index');
        Route::get('/vehiculos/{vehiculo}/mantenimientos/historial', [MantenimientoController::class, 'historial'])->name('vehiculos.mantenimientos.historial');
        // Formulario para crear mantenimiento de un vehículo.
        Route::get('/vehiculos/{vehiculo}/mantenimientos/create', [MantenimientoController::class, 'create'])->name('vehiculos.mantenimientos.create');
        // Guarda nuevo mantenimiento para el vehículo.
        Route::post('/vehiculos/{vehiculo}/mantenimientos/store', [MantenimientoController::class, 'store'])->name('vehiculos.mantenimientos.store');
        // Formulario para editar mantenimiento específico del vehículo.
        Route::get('/vehiculos/{vehiculo}/mantenimientos/edit/{id}', [MantenimientoController::class, 'edit'])->name('vehiculos.mantenimientos.edit');
        // Actualiza mantenimiento específico (acepta PUT o POST por compatibilidad del módulo).
        Route::match(['post', 'put'], '/vehiculos/{vehiculo}/mantenimientos/update/{id}', [MantenimientoController::class, 'update'])->name('vehiculos.mantenimientos.update');
        // Elimina mantenimiento específico del vehículo.
        Route::delete('/vehiculos/{vehiculo}/mantenimientos/delete/{id}', [MantenimientoController::class, 'destroy'])->name('vehiculos.mantenimientos.destroy');

        // PAGOS DEL VEHICULO
        // Lista pagos del vehículo (tenencia, refrendo, verificación).
        Route::get('/vehiculos/{vehiculo}/pagos', [PagoVehiculoController::class, 'index'])->name('vehiculos.pagos.index');
        Route::get('/vehiculos/{vehiculo}/pagos/historial', [PagoVehiculoController::class, 'historial'])->name('vehiculos.pagos.historial');
        // Formulario para registrar pago del vehículo.
        Route::get('/vehiculos/{vehiculo}/pagos/create', [PagoVehiculoController::class, 'create'])->name('vehiculos.pagos.create');
        // Guarda nuevo pago del vehículo.
        Route::post('/vehiculos/{vehiculo}/pagos/store', [PagoVehiculoController::class, 'store'])->name('vehiculos.pagos.store');
        // Formulario para editar pago específico del vehículo.
        Route::get('/vehiculos/{vehiculo}/pagos/edit/{id}', [PagoVehiculoController::class, 'edit'])->name('vehiculos.pagos.edit');
        // Actualiza pago específico (acepta PUT o POST por compatibilidad del módulo).
        Route::match(['post', 'put'], '/vehiculos/{vehiculo}/pagos/update/{id}', [PagoVehiculoController::class, 'update'])->name('vehiculos.pagos.update');
        // Elimina pago específico del vehículo.
        Route::delete('/vehiculos/{vehiculo}/pagos/delete/{id}', [PagoVehiculoController::class, 'destroy'])->name('vehiculos.pagos.destroy');

    });

    /*SALIDAS VEHICULOS*/
    /*Ruta de Vista de Salidas-index*/
    Route::get('/salidas-vehiculos', [SalidaVehiculoController::class, 'index'])->name('salidas.home');
    Route::get('/salidas-vehiculos/index', [SalidaVehiculoController::class, 'index'])->name('salidas.index');
    /*Ruta de Vista de Registro de Salida*/
    Route::get('/salidas-vehiculos/create', [SalidaVehiculoController::class, 'create'])->name('salidas.create');
    /*Ruta de Guardado de Salida*/
    Route::post('/salidas-vehiculos/store', [SalidaVehiculoController::class, 'store'])->name('salidas.store');
    /*Ruta de Finalizar Salida*/
    Route::post('/salidas-vehiculos/finalizar/{id}', [SalidaVehiculoController::class, 'finalizar'])->name('salidas.finalizar');

    /*CHECKLIST*/
    /*Ruta de Vista de Checklist de Salida*/
    Route::get('/salidas-vehiculos/checklist-salida/create/{salida}', [SalidaChecklistController::class, 'create'])->name('salidas.checklist.salida.create');
    /*Ruta de Guardado de Checklist de Salida*/
    Route::post('/salidas-vehiculos/checklist-salida/store{salida}', [SalidaChecklistController::class, 'store'])->name('salidas.checklist.salida.store');
    /*Ruta de Vista de Checklist de Entrada*/
    Route::get('/salidas-vehiculos/checklist-entrada/create/{salida}', [SalidaChecklistController::class, 'createEntrada'])->name('salidas.checklist.entrada.create');
    /*Ruta de Guardado de Checklist de Entrada*/
    Route::post('/salidas-vehiculos/checklist-entrada/store/{salida}', [SalidaChecklistController::class, 'storeEntrada'])->name('salidas.checklist.entrada.store');
    /*Ruta de Vista de Checklist Salida/Entrada*/
    Route::get('/salidas-vehiculos/checklist/{salida}/{tipo}', [SalidaChecklistController::class, 'show'])->name('salidas.checklist.show');
    /*Ruta de PDF de Checklist*/
    Route::get('/salidas-vehiculos/checklist-pdf/{salida}', [SalidaChecklistController::class, 'pdf'])->name('salidas.checklist.pdf');




}); 


//Route::get('/home',[App\Http\Controller\HomeController::class,'index'])->name('home');
