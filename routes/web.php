<?php
//use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\OC\OCController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Manifiesto\PDFController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Prueba\PruebaController;
use App\Http\Controllers\Clientes\ClientesController;
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
use App\Http\Controllers\Reporte\ReporteController;


    Route::get('/', function () {
        return view('auth.login');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::middleware('auth')->group(function () {
    Route::middleware('can:equipos-access')->group(function () {
        /*Creación de Notificaciones*/
        Route::get('notificacion/index', [NotificacionController::class, 'index'])->name('notifications.index');
        /*Obtener Notificaciones*/
        Route::get('notificaciones/update', [NotificacionController::class, 'getNotificaciones']);
        });
    });
    

    Route::middleware('auth')->group(function () {
        //Route::middleware('can:administrador-access')->group(function () {
        /*vista Page in construction */
        Route::get('/Page_In_Construction', [general_eycController::class, 'PageInConstruction'])->name('Page_In_Construction');
        /*vista Page welcome*/
        Route::get('/Welcome', [general_eycController::class, 'Welcome'])->name('Welcome');

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

        /*REPORTES*/
        /*Obtiene las Normas segun La prueba del select*/
        Route::get('/Obtener/normas/{id}', [ReporteController::class, 'ObtenerNormas'])->name('Obtener.normas');
        /*Obtiene los Formatos segun La prueba del select*/
        Route::get('/Obtener/formatos/{id}', [ReporteController::class, 'ObtenerFormatos'])->name('Obtener.formatos');

        /*Rutas de Vistas del index de selección de los contratos*/
        Route::get('/index/ContratoProyecto', [ReporteController::class, 'indexContratoProyecto'])->name('index.ContratoProyecto');
        /*Rutas de Vistas del index despues de la selección de los contratos/Reportes de estos*/
        Route::post('/index/ReporteProyectoContrato', [ReporteController::class, 'indexReporteProyectoContrato'])->name('index.ReporteProyectoContrato');

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
        /*Ruta Para pasar las variables al reporte*/
        Route::get('/Editar/Reporte/{id}', [ReporteController::class, 'Edicion_Reportes'])->name('Editar.Reporte');
        /*Ruta de Guardado Reportes/INS*/
        Route::post('/Reportes_FOR_02_PRO_INS_10/store', [ReporteController::class, 'FOR_02_PRO_INS_10'])->name('Reportes_FOR_02_PRO_INS_10.store');



        //});
    });

    Route::middleware('auth')->group(function () {
        
    Route::middleware('can:tecnicos-equipos-access')->group(function () {
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
    /*Ruta para ver el manifiesto PDF*/
    Route::get('Manifiesto/NewFormatPDF/{id}', [PDFController::class, 'generaManifiestoNewFormatPDF'])->name('Manifiesto.NewFormat.pdf');
    });
    
    /*EQUIPOS INVENTARIO-REGISTRO*/
    Route::middleware('can:equipos-access')->group(function () {

    /*DEVOLUCIONES*/
    /*Rutas de Devolución para listar y devolver*/
    Route::get('/devolucion/EyC/{id}', [DevolucionController::class, 'editDevolucionListado'])->name('devolucion.EyC');
    /*Ruta para devolver los articulos de la lista al almacen */
    Route::post('/devolver-item', [DevolucionController::class, 'devolverItem'])->name('devolver.item');
    /*GENERAL EYC*/
    /*Rutas de Vistas Equipos y Consumibles-Tabla General*/
    Route::get('/inventario', [general_eycController::class, 'index'])->name('inventario');
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

    /*A DEFINIR EL ACCESO */
    /*REPORTES PDF*/
    /*Ruta para ver los PDF de los Reportes*/
    Route::get('/Reporte/FOR-INS-02/02', [PDFReportesController::class, 'FOR_INS_02_02'])->name('Reporte_FOR_INS_02_02.PDF');
    Route::get('/Reporte/FOR-INS-03/01', [PDFReportesController::class, 'FOR_INS_03_01'])->name('Reporte_FOR_INS_03_01.PDF');
    Route::get('/Reporte/FOR-INS-04/01', [PDFReportesController::class, 'FOR_INS_04_01'])->name('Reporte_FOR_INS_04_01.PDF');
    Route::get('/Reporte/FOR-INS-04/02', [PDFReportesController::class, 'FOR_INS_04_02'])->name('Reporte_FOR_INS_04_02.PDF');
    Route::get('/Reporte/FOR-INS-05/01', [PDFReportesController::class, 'FOR_INS_05_01'])->name('Reporte_FOR_INS_05_01.PDF');
    Route::get('/Reporte/FOR-INS-06/01', [PDFReportesController::class, 'FOR_INS_06_01'])->name('Reporte_FOR_INS_06_01.PDF');
    Route::get('/Reporte/FOR-INS-07/01', [PDFReportesController::class, 'FOR_INS_07_01'])->name('Reporte_FOR_INS_07_01.PDF');
    Route::get('/Reporte/FOR-INS-08/01', [PDFReportesController::class, 'FOR_INS_08_01'])->name('Reporte_FOR_INS_08_01.PDF');
    Route::get('/Reporte/FOR-INS-09/01', [PDFReportesController::class, 'FOR_INS_09_01'])->name('Reporte_FOR_INS_09_01.PDF');
    Route::get('/Reporte/FOR-INS-10/01', [PDFReportesController::class, 'FOR_INS_10_01'])->name('Reporte_FOR_INS_10_01.PDF');
    Route::get('/Reporte/FOR-INS-10/02', [PDFReportesController::class, 'FOR_INS_10_02'])->name('Reporte_FOR_INS_10_02.PDF');
    Route::get('/Reporte/FOR-INS-12/01', [PDFReportesController::class, 'FOR_INS_12_01'])->name('Reporte_FOR_INS_12_01.PDF');
    Route::get('/Reporte/FOR-INS-13/01', [PDFReportesController::class, 'FOR_INS_13_01'])->name('Reporte_FOR_INS_13_01.PDF');
    Route::get('/Reporte/FOR-INS-15/01', [PDFReportesController::class, 'FOR_INS_15_01'])->name('Reporte_FOR_INS_15_01.PDF');
    Route::get('/Reporte/FOR-INS-15/02', [PDFReportesController::class, 'FOR_INS_15_02'])->name('Reporte_FOR_INS_15_02.PDF');
    Route::get('/Reporte/FOR-INS-15/03', [PDFReportesController::class, 'FOR_INS_15_03'])->name('Reporte_FOR_INS_15_03.PDF');
    Route::get('/Reporte/FOR-INS-16/01', [PDFReportesController::class, 'FOR_INS_16_01'])->name('Reporte_FOR_INS_16_01.PDF');
    Route::get('/Reporte/FOR-INS-17/01', [PDFReportesController::class, 'FOR_INS_17_01'])->name('Reporte_FOR_INS_17_01.PDF');
    Route::get('/Reporte/FOR-INS-18/01', [PDFReportesController::class, 'FOR_INS_18_01'])->name('Reporte_FOR_INS_18_01.PDF');

    /*A DEFINIR EL ACCESO */

    Route::get('/Reporte/FORMATO/01', [PDFReportesController::class, 'FORMATO_01'])->name('Reporte_FOR_PINS_03_01.PDF');
    Route::get('/Reporte/FORMATO/02', [PDFReportesController::class, 'FORMATO_02'])->name('Reporte_FOR_PINS_04_01.PDF');

    Route::get('/reporte/create', [PDFReportesController::class, 'Reportecreate'])->name('reporte.create');


    /*A DEFINIR EL ACCESO */



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
});

require __DIR__.'/auth.php';

Auth::routes();

//Route::get('/home',[App\Http\Controller\HomeController::class,'index'])->name('home');

