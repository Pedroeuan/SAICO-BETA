<?php

namespace App\Http\Controllers\Notificacion;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Models\EquiposyConsumibles\general_eyc;
use App\Models\EquiposyConsumibles\equipos;
use App\Models\EquiposyConsumibles\certificados;
use App\Models\EquiposyConsumibles\consumibles;
use App\Models\EquiposyConsumibles\almacen;
use App\Models\EquiposyConsumibles\Historial_Almacen;
use App\Models\EquiposyConsumibles\accesorios;
use App\Models\EquiposyConsumibles\block_y_probeta;
use App\Models\EquiposyConsumibles\herramientas;
use App\Models\EquiposyConsumibles\historial_certificado;
use App\Models\EquiposyConsumibles\detalles_kits;
use App\Models\EquiposyConsumibles\kits;
use App\Models\Notificacion\Notificacion;
use App\Models\User;
use App\Notifications\NotificacionCertificadoMailable;

class NotificacionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rol = $user->rol;

        // Si es Admin o SuperAdmin → ver todas
        if (in_array($rol, ['Administrador', 'SuperAdministrador'])) {
            $notificaciones = Notificacion::with('users_id')
                ->orderBy('created_at', 'desc')
                ->distinct()  // evita duplicados
                ->get();
        } else {
            // Si NO es Admin → solo sus notificaciones, no de todo su rol
            $notificaciones = Notificacion::where('users_id', $user->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('notifications.index', compact('notificaciones'));
    }

    public function crearNotificacionesCertificados()
    {
        Log::info('***********************');
        Log::info('INICIO DE NOTIFICACIONES');
        // Obtener fechas límite para las consultas
        $fechaActual = Carbon::now();
        $fecha45DiasAntes = $fechaActual->copy()->addDays(45)->toDateString();
        $fecha40DiasAntes = $fechaActual->copy()->addDays(40)->toDateString();
        $fecha35DiasAntes = $fechaActual->copy()->addDays(35)->toDateString();
        $fecha30DiasAntes = $fechaActual->copy()->addDays(30)->toDateString();
        $fecha25DiasAntes = $fechaActual->copy()->addDays(25)->toDateString();
        $fecha20DiasAntes = $fechaActual->copy()->addDays(20)->toDateString();
        $fecha15DiasAntes = $fechaActual->copy()->addDays(15)->toDateString();
        $fecha10DiasAntes = $fechaActual->copy()->addDays(10)->toDateString();
        $fecha7DiasAntes = $fechaActual->copy()->addDays(7)->toDateString();
        $fecha5DiasAntes = $fechaActual->copy()->addDays(5)->toDateString();
        $fecha0DiasAntes = $fechaActual->copy()->addDays(0)->toDateString();

        // Obtener todos los certificados que están relacionados con la tabla general_eyc
        $certificados = Certificados::with('generaleyc.ISO') // Cargar la relación con general_eyc
            ->whereIn('Prox_fecha_calibracion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_calibracion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_mantenimiento', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Prox_fecha_mantenimiento', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_Verificacion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Prox_fecha_verificacion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->get();

        // Recorrer cada certificado
        foreach ($certificados as $certificado) {
            // Obtener el registro de general_eyc relacionado con el certificado
            $generalEyc = $certificado->generalEyc;
            $No_economico = $generalEyc->No_economico;
            $Nombre_C = $generalEyc->Nombre_E_P_BP;
            $url = url('edicion/editEyC/' . $certificado->idGeneral_EyC);
            // Obtener el ISO relacionado
            $iso = $generalEyc->ISO ? $generalEyc->ISO->NombreISO : null;

            // Determinar el tipo de general_eyc
            if ($generalEyc) {
                $tipo = $generalEyc->Tipo;

                // Según el tipo, definir qué fecha usar
                if ($iso == '9001')
                {
                    if ($tipo === 'EQUIPOS') {
                        $fechaCalibracion = $certificado->Prox_fecha_calibracion;
                        $fechaMantenimiento = $certificado->Prox_fecha_mantenimiento;
                        $fechaVerificacion = $certificado->Prox_fecha_verificacion;
                    } elseif ($tipo === 'CONSUMIBLES' || $tipo === 'BLOCK Y PROBETA') {
                        $fechaCalibracion = $certificado->Fecha_calibracion;
                    } else {
                        // Si no corresponde a ninguno de los tipos, continuar con el siguiente
                        continue;
                    }
                }
                else //if($iso == '17025')
                {
                    if ($tipo === 'EQUIPOS' || $tipo === 'BLOCK Y PROBETA') {
                        $fechaCalibracion = $certificado->Prox_fecha_calibracion;
                        $fechaMantenimiento = $certificado->Prox_fecha_mantenimiento;
                        $fechaVerificacion = $certificado->Prox_fecha_verificacion;

                    } elseif ($tipo === 'CONSUMIBLES') {
                        $fechaCalibracion = $certificado->Fecha_calibracion;
                    } else {
                        // Si no corresponde a ninguno de los tipos, continuar con el siguiente
                        continue;
                    }
                }

                    // Convertir la fecha al formato DD-MM-YYYY
                    $fechaCalibracionFormateada = Carbon::parse($fechaCalibracion)->format('d-m-Y');
                    $fechaMantenimientoFormateada = Carbon::parse($fechaMantenimiento)->format('d-m-Y');
                    $fechaVerificacionFormateada = Carbon::parse($fechaVerificacion)->format('d-m-Y');
                    
                    // Determinar los días restantes para la calibración
                    $diasRestantesC = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($fechaCalibracion)->startOfDay(),false);
                    $diasRestantesM = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($fechaMantenimiento)->startOfDay(),false);
                    $diasRestantesV = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($fechaVerificacion)->startOfDay(),false);

                // Filtrar usuarios según el ISO
                $usuarios = User::where('Estatus', 'ALTA')
                    ->where(function($query) use ($iso) {
                        $query->whereIn('rol', ['Super Administrador', 'Administrador']);
                        if ($iso == '17025') {
                            $query->orWhere('rol', 'Laboratorio');
                        }
                        if ($iso == '9001') {
                            $query->orWhere('rol', 'Equipos');
                        }
                    })
                    ->get();

                // ===== NOTIFICACIÓN DE CALIBRACIÓN =====
                if ($diasRestantesC >= 0 && $diasRestantesC <= 45) {
                    if ($diasRestantesC == 0) {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Calibración VENCIDA";
                            $mensajeLargo = "La Calibración del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "La Calibración del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'>VENCIDA</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'CONSUMIBLES') {
                            $mensajeCorto = "Certificado CADUCADO";
                            $mensajeLargo = "El Certificado del Consumible: ".$Nombre_C.", Con el No. certificado: " . $certificado->No_certificado . " está CADUCADO (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "El Certificado del Consumible: ".$Nombre_C.", <br>Con el No. certificado: " . $certificado->No_certificado . "<br>está <span style='color: #E01A22;'>CADUCADO </span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'BLOCK Y PROBETA') {
                            $mensajeCorto = "Calibración VENCIDA";
                            $mensajeLargo = "El Block y Probeta: ".$Nombre_C.", La Calibración del No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "El Block y Probeta: ".$Nombre_C.", <br>La Calibración del No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'> VENCIDA </span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        }
                    } else {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantesC días";
                            $mensajeLargo = "La Calibración del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesC días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "La Calibración del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'>VENCER en $diasRestantesC días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'CONSUMIBLES') {
                            $mensajeCorto = "Cert. Prox. a CADUCAR en $diasRestantesC días";
                            $mensajeLargo = "El Certificado del Consumible: ".$Nombre_C.", Con No. certificado: " . $certificado->No_certificado . " está próximo a CADUCAR en $diasRestantesC días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "El Certificado del Consumible: ".$Nombre_C.", <br>Con No. certificado: " . $certificado->No_certificado . " <br>está próximo a <span style='color: #E01A22;'> CADUCAR en $diasRestantesC días</span> <br>(Fecha de vencimiento: " . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'BLOCK Y PROBETA') {
                            $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantesC días";
                            $mensajeLargo = "La Calibración del Block y Probeta: ".$Nombre_C.", Con el No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesC días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "La Calibración del Block y Probeta: ".$Nombre_C.", <br>Con el No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'> VENCER en $diasRestantesC días</span> <br>(Fecha de vencimiento: " . $fechaCalibracionFormateada . "</span>)";
                        }
                    }

                    foreach ($usuarios as $usuario) {
                        $notificacionExistente = Notificacion::where('users_id', $usuario->id)
                            ->where('Mensaje_Corto', $mensajeCorto)
                            ->where('Mensaje_Largo', $mensajeLargo)
                            ->first();

                        if (!$notificacionExistente) {
                            $notificacion = new Notificacion();
                            $notificacion->users_id = $usuario->id;
                            $notificacion->Mensaje_Corto = $mensajeCorto;
                            $notificacion->Mensaje_Largo = $mensajeLargo;
                            $notificacion->url = $url;
                            $notificacion->leida = false;
                            $notificacion->prioridad = $this->prioridadPorVencimiento($diasRestantesC);
                            $notificacion->save();
                            Log::info('Enviando correo a: ' . $usuario->email);
                            //📧 Enviar correo (capturar excepciones para diagnóstico)
                            try {
                                $usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargoemail,$url));
                            } catch (\Throwable $e) {
                                Log::error('Error enviando correo a: ' . $usuario->email . ' - ' . $e->getMessage());
                                Log::error($e->getTraceAsString());
                            }
                        }
                    }
                }

                // ===== NOTIFICACIÓN DE MANTENIMIENTO =====
                if ($diasRestantesM >= 0 && $diasRestantesM <= 45) {
                    if ($diasRestantesM == 0) {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Mantenimiento VENCIDO";
                            $mensajeLargo = "El Mantenimiento del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " esta VENCIDO (Fecha de vencimiento: " . $fechaMantenimientoFormateada . ")";
                            $mensajeLargoemail = "El Mantenimiento del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'>VENCIDO</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaMantenimientoFormateada . "</span>)";
                        }
                    } else {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Mant. Prox. a VENCER en $diasRestantesM días";
                            $mensajeLargo = "El Mantenimiento del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesM días (Fecha de vencimiento: " . $fechaMantenimientoFormateada . ")";
                            $mensajeLargoemail = "El Mantenimiento del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'>VENCER en $diasRestantesM días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaMantenimientoFormateada . "</span>)";
                        }
                    }

                    foreach ($usuarios as $usuario) {
                        $notificacionExistente = Notificacion::where('users_id', $usuario->id)
                            ->where('Mensaje_Corto', $mensajeCorto)
                            ->where('Mensaje_Largo', $mensajeLargo)
                            ->first();

                        if (!$notificacionExistente) {
                            $notificacion = new Notificacion();
                            $notificacion->users_id = $usuario->id;
                            $notificacion->Mensaje_Corto = $mensajeCorto;
                            $notificacion->Mensaje_Largo = $mensajeLargo;
                            $notificacion->url = $url;
                            $notificacion->leida = false;
                            $notificacion->prioridad = $this->prioridadPorVencimiento($diasRestantesM);
                            $notificacion->save();
                            //📧 Enviar correo
                            Log::info('Enviando correo a: ' . $usuario->email);
                            try {
                                $usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargoemail,$url));
                            } catch (\Throwable $e) {
                                Log::error('Error enviando correo a: ' . $usuario->email . ' - ' . $e->getMessage());
                                Log::error($e->getTraceAsString());
                            }
                        }
                    }
                }

                // ===== NOTIFICACIÓN DE VERIFICACIÓN =====
                if ($diasRestantesV >= 0 && $diasRestantesV <= 45) {
                    if ($diasRestantesV == 0) {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Verificación VENCIDA";
                            $mensajeLargo = "La Verificación del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaVerificacionFormateada . ")";
                            $mensajeLargoemail = "La Verificación del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'>VENCIDA</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaVerificacionFormateada . "</span>)";
                        }
                    } else {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Verif. Prox. a VENCER en $diasRestantesV días";
                            $mensajeLargo = "La Verificación del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesV días (Fecha de vencimiento: " . $fechaVerificacionFormateada . ")";
                            $mensajeLargoemail = "La Verificación del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'>VENCER en $diasRestantesV días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaVerificacionFormateada . "</span>)";
                        }
                    }

                    foreach ($usuarios as $usuario) {
                        $notificacionExistente = Notificacion::where('users_id', $usuario->id)
                            ->where('Mensaje_Corto', $mensajeCorto)
                            ->where('Mensaje_Largo', $mensajeLargo)
                            ->first();

                        if (!$notificacionExistente) {
                            $notificacion = new Notificacion();
                            $notificacion->users_id = $usuario->id;
                            $notificacion->Mensaje_Corto = $mensajeCorto;
                            $notificacion->Mensaje_Largo = $mensajeLargo;
                            $notificacion->url = $url;
                            $notificacion->leida = false;
                            $notificacion->prioridad = $this->prioridadPorVencimiento($diasRestantesV);
                            $notificacion->save();
                            //📧 Enviar correo
                            Log::info('Enviando correo a: ' . $usuario->email);
                            try {
                                $usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargoemail,$url));
                            } catch (\Throwable $e) {
                                Log::error('Error enviando correo a: ' . $usuario->email . ' - ' . $e->getMessage());
                                Log::error($e->getTraceAsString());
                            }
                        }
                    }
                }
            }
        }
        Log::info('FIN DE NOTIFICACIONES');
        Log::info('***********************');
    }


    public function crearNotificacionesCertificadosInterno()
    {
        Log::info('***********************');
        Log::info('INICIO DE NOTIFICACIONES INTERNO');
        // Obtener fechas límite para las consultas
        $fechaActual = Carbon::now();
        $fecha45DiasAntes = $fechaActual->copy()->addDays(45)->toDateString();
        $fecha40DiasAntes = $fechaActual->copy()->addDays(40)->toDateString();
        $fecha35DiasAntes = $fechaActual->copy()->addDays(35)->toDateString();
        $fecha30DiasAntes = $fechaActual->copy()->addDays(30)->toDateString();
        $fecha25DiasAntes = $fechaActual->copy()->addDays(25)->toDateString();
        $fecha20DiasAntes = $fechaActual->copy()->addDays(20)->toDateString();
        $fecha15DiasAntes = $fechaActual->copy()->addDays(15)->toDateString();
        $fecha10DiasAntes = $fechaActual->copy()->addDays(10)->toDateString();
        $fecha7DiasAntes = $fechaActual->copy()->addDays(7)->toDateString();
        $fecha5DiasAntes = $fechaActual->copy()->addDays(5)->toDateString();
        $fecha0DiasAntes = $fechaActual->copy()->addDays(0)->toDateString();

        // Obtener todos los certificados que están relacionados con la tabla general_eyc
        $certificados = Certificados::with('generaleyc.ISO') // Cargar la relación con general_eyc
            ->whereIn('Prox_fecha_calibracion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_calibracion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_mantenimiento', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Prox_fecha_mantenimiento', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_Verificacion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Prox_fecha_verificacion', [$fecha45DiasAntes,$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes,$fecha20DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->get();

        // Recorrer cada certificado
        foreach ($certificados as $certificado) {
            // Obtener el registro de general_eyc relacionado con el certificado
            $generalEyc = $certificado->generalEyc;
            $No_economico = $generalEyc->No_economico;
            $Nombre_C = $generalEyc->Nombre_E_P_BP;
            $url = url('edicion/editEyC/' . $certificado->idGeneral_EyC);
            // Obtener el ISO relacionado
            $iso = $generalEyc->ISO ? $generalEyc->ISO->NombreISO : null;

            // Determinar el tipo de general_eyc
            if ($generalEyc) {
                $tipo = $generalEyc->Tipo;

                // Según el tipo, definir qué fecha usar
                if ($iso == '9001')
                {
                    if ($tipo === 'EQUIPOS') {
                        $fechaCalibracion = $certificado->Prox_fecha_calibracion;
                        $fechaMantenimiento = $certificado->Prox_fecha_mantenimiento;
                        $fechaVerificacion = $certificado->Prox_fecha_verificacion;
                    } elseif ($tipo === 'CONSUMIBLES' || $tipo === 'BLOCK Y PROBETA') {
                        $fechaCalibracion = $certificado->Fecha_calibracion;
                    } else {
                        // Si no corresponde a ninguno de los tipos, continuar con el siguiente
                        continue;
                    }
                }
                else //if($iso == '17025')
                {
                    if ($tipo === 'EQUIPOS' || $tipo === 'BLOCK Y PROBETA') {
                        $fechaCalibracion = $certificado->Prox_fecha_calibracion;
                        $fechaMantenimiento = $certificado->Prox_fecha_mantenimiento;
                        $fechaVerificacion = $certificado->Prox_fecha_verificacion;

                    } elseif ($tipo === 'CONSUMIBLES') {
                        $fechaCalibracion = $certificado->Fecha_calibracion;
                    } else {
                        // Si no corresponde a ninguno de los tipos, continuar con el siguiente
                        continue;
                    }
                }

                    // Convertir la fecha al formato DD-MM-YYYY
                    $fechaCalibracionFormateada = Carbon::parse($fechaCalibracion)->format('d-m-Y');
                    $fechaMantenimientoFormateada = Carbon::parse($fechaMantenimiento)->format('d-m-Y');
                    $fechaVerificacionFormateada = Carbon::parse($fechaVerificacion)->format('d-m-Y');
                    
                    // Determinar los días restantes para la calibración
                    $diasRestantesC = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($fechaCalibracion)->startOfDay(),false);
                    $diasRestantesM = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($fechaMantenimiento)->startOfDay(),false);
                    $diasRestantesV = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($fechaVerificacion)->startOfDay(),false);

                // Filtrar usuarios según el ISO
                $usuarios = User::where('Estatus', 'ALTA')
                    ->where(function($query) use ($iso) {
                        $query->whereIn('rol', ['Super Administrador', 'Administrador']);
                        if ($iso == '17025') {
                            $query->orWhere('rol', 'Laboratorio');
                        }
                        if ($iso == '9001') {
                            $query->orWhere('rol', 'Equipos');
                        }
                    })
                    ->get();

                // ===== NOTIFICACIÓN DE CALIBRACIÓN =====
                if ($diasRestantesC >= 0 && $diasRestantesC <= 45) {
                    if ($diasRestantesC == 0) {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Calibración VENCIDA";
                            $mensajeLargo = "La Calibración del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "La Calibración del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'>VENCIDA</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'CONSUMIBLES') {
                            $mensajeCorto = "Certificado CADUCADO";
                            $mensajeLargo = "El Certificado del Consumible: ".$Nombre_C.", Con el No. certificado: " . $certificado->No_certificado . " está CADUCADO (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "El Certificado del Consumible: ".$Nombre_C.", <br>Con el No. certificado: " . $certificado->No_certificado . "<br>está <span style='color: #E01A22;'>CADUCADO </span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'BLOCK Y PROBETA') {
                            $mensajeCorto = "Calibración VENCIDA";
                            $mensajeLargo = "El Block y Probeta: ".$Nombre_C.", La Calibración del No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "El Block y Probeta: ".$Nombre_C.", <br>La Calibración del No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'> VENCIDA </span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        }
                    } else {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantesC días";
                            $mensajeLargo = "La Calibración del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesC días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "La Calibración del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'>VENCER en $diasRestantesC días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'CONSUMIBLES') {
                            $mensajeCorto = "Cert. Prox. a CADUCAR en $diasRestantesC días";
                            $mensajeLargo = "El Certificado del Consumible: ".$Nombre_C.", Con No. certificado: " . $certificado->No_certificado . " está próximo a CADUCAR en $diasRestantesC días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "El Certificado del Consumible: ".$Nombre_C.", <br>Con No. certificado: " . $certificado->No_certificado . " <br>está próximo a <span style='color: #E01A22;'> CADUCAR en $diasRestantesC días</span> <br>(Fecha de vencimiento: " . $fechaCalibracionFormateada . "</span>)";
                        } elseif ($tipo === 'BLOCK Y PROBETA') {
                            $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantesC días";
                            $mensajeLargo = "La Calibración del Block y Probeta: ".$Nombre_C.", Con el No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesC días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                            $mensajeLargoemail = "La Calibración del Block y Probeta: ".$Nombre_C.", <br>Con el No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'> VENCER en $diasRestantesC días</span> <br>(Fecha de vencimiento: " . $fechaCalibracionFormateada . "</span>)";
                        }
                    }

                    foreach ($usuarios as $usuario) {
                        $notificacionExistente = Notificacion::where('users_id', $usuario->id)
                            ->where('Mensaje_Corto', $mensajeCorto)
                            ->where('Mensaje_Largo', $mensajeLargo)
                            ->first();

                        if (!$notificacionExistente) {
                            $notificacion = new Notificacion();
                            $notificacion->users_id = $usuario->id;
                            $notificacion->Mensaje_Corto = $mensajeCorto;
                            $notificacion->Mensaje_Largo = $mensajeLargo;
                            $notificacion->url = $url;
                            $notificacion->leida = false;
                            $notificacion->prioridad = $this->prioridadPorVencimiento($diasRestantesC);
                            $notificacion->save();
                            //Log::info('Enviando correo a: ' . $usuario->email);
                            //📧 Enviar correo
                            //$usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargoemail,$url));
                        }
                    }
                }

                // ===== NOTIFICACIÓN DE MANTENIMIENTO =====
                if ($diasRestantesM >= 0 && $diasRestantesM <= 45) {
                    if ($diasRestantesM == 0) {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Mantenimiento VENCIDO";
                            $mensajeLargo = "El Mantenimiento del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " esta VENCIDO (Fecha de vencimiento: " . $fechaMantenimientoFormateada . ")";
                            $mensajeLargoemail = "El Mantenimiento del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'>VENCIDO</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaMantenimientoFormateada . "</span>)";
                        }
                    } else {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Mant. Prox. a VENCER en $diasRestantesM días";
                            $mensajeLargo = "El Mantenimiento del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesM días (Fecha de vencimiento: " . $fechaMantenimientoFormateada . ")";
                            $mensajeLargoemail = "El Mantenimiento del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'>VENCER en $diasRestantesM días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaMantenimientoFormateada . "</span>)";
                        }
                    }

                    foreach ($usuarios as $usuario) {
                        $notificacionExistente = Notificacion::where('users_id', $usuario->id)
                            ->where('Mensaje_Corto', $mensajeCorto)
                            ->where('Mensaje_Largo', $mensajeLargo)
                            ->first();

                        if (!$notificacionExistente) {
                            $notificacion = new Notificacion();
                            $notificacion->users_id = $usuario->id;
                            $notificacion->Mensaje_Corto = $mensajeCorto;
                            $notificacion->Mensaje_Largo = $mensajeLargo;
                            $notificacion->url = $url;
                            $notificacion->leida = false;
                            $notificacion->prioridad = $this->prioridadPorVencimiento($diasRestantesM);
                            $notificacion->save();
                            //📧 Enviar correo
                            //Log::info('Enviando correo a: ' . $usuario->email);
                            //$usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargoemail,$url));
                        }
                    }
                }

                // ===== NOTIFICACIÓN DE VERIFICACIÓN =====
                if ($diasRestantesV >= 0 && $diasRestantesV <= 45) {
                    if ($diasRestantesV == 0) {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Verificación VENCIDA";
                            $mensajeLargo = "La Verificación del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaVerificacionFormateada . ")";
                            $mensajeLargoemail = "La Verificación del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'>VENCIDA</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaVerificacionFormateada . "</span>)";
                        }
                    } else {
                        if ($tipo === 'EQUIPOS') {
                            $mensajeCorto = "Verif. Prox. a VENCER en $diasRestantesV días";
                            $mensajeLargo = "La Verificación del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantesV días (Fecha de vencimiento: " . $fechaVerificacionFormateada . ")";
                            $mensajeLargoemail = "La Verificación del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'>VENCER en $diasRestantesV días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaVerificacionFormateada . "</span>)";
                        }
                    }

                    foreach ($usuarios as $usuario) {
                        $notificacionExistente = Notificacion::where('users_id', $usuario->id)
                            ->where('Mensaje_Corto', $mensajeCorto)
                            ->where('Mensaje_Largo', $mensajeLargo)
                            ->first();

                        if (!$notificacionExistente) {
                            $notificacion = new Notificacion();
                            $notificacion->users_id = $usuario->id;
                            $notificacion->Mensaje_Corto = $mensajeCorto;
                            $notificacion->Mensaje_Largo = $mensajeLargo;
                            $notificacion->url = $url;
                            $notificacion->leida = false;
                            $notificacion->prioridad = $this->prioridadPorVencimiento($diasRestantesV);
                            $notificacion->save();
                            //📧 Enviar correo
                            //Log::info('Enviando correo a: ' . $usuario->email);
                            //$usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargoemail,$url));
                        }
                    }
                }
            }
        }
        Log::info('FIN DE NOTIFICACIONES INTERNO');
        Log::info('***********************');
    }

    public function getNotificaciones(Request $request)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();
        
        // Obtener notificaciones para el usuario
        $notificaciones = Notificacion::where('users_id', $user->id)
                                        ->where('leida', false) // Descomenta esto si necesitas filtrar solo no leídas
                                        ->orderBy('created_at', 'desc')
                                        ->get(['idNotificaciones', 'Mensaje_Corto', 'url']); // Asegúrate de tener el 'id' también
    
        // Formatear las notificaciones para AdminLTE
        $formattedNotifications = $notificaciones->map(function ($notificacion) {
            return [
                'id' => $notificacion->idNotificaciones,
                'message' => $notificacion->Mensaje_Corto,
                'url' => $this->urlNotificacionSegura($notificacion->url),
            ];
        });

        // El preview se reclama en esta misma actualización de la campana. No usa
        // "leida": una notificación puede seguir sin leer después de mostrarse.
        return response()->json([
            'notifications' => $formattedNotifications,
            'preview' => $this->reclamarPreview($user, $request->query('preview_since')),
        ]);
    }
    
    public function marcarComoLeida($id)
    {
        // Nunca se toma el usuario desde el navegador: sólo puede marcarse una
        // notificación que pertenezca a la sesión autenticada.
        $notificacion = Notificacion::where('idNotificaciones', $id)
            ->where('users_id', Auth::id())
            ->first();

        if ($notificacion) {
            $notificacion->leida = true;
            $notificacion->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Reclama una sola notificación para preview. El UPDATE condicional es la
     * operación que resuelve la concurrencia entre pestañas, dispositivos y
     * peticiones simultáneas.
     */
    private function reclamarPreview(User $user, ?string $previewSince): ?array
    {
        // La marca nace en sessionStorage al abrir el sistema. Así, las
        // notificaciones históricas siguen en la campana/listado, pero nunca
        // se convierten en previews al navegar por los módulos.
        try {
            $desde = $previewSince !== null ? Carbon::parse($previewSince) : now();
        } catch (\Throwable $e) {
            $desde = now();
        }

        $reintentos = [
            'normal' => null,
            'alta' => null,
            // Una alerta crítica no leída se recuerda como máximo una vez al día.
            'critica' => now()->subDay(),
        ];

        $candidatas = Notificacion::query()
            ->where('users_id', $user->id)
            ->where('leida', false)
            ->where('created_at', '>=', $desde)
            ->where(function ($query) use ($reintentos) {
                $query->whereNull('preview_shown_at')
                    ->orWhere(function ($reintento) use ($reintentos) {
                        $reintento->where('prioridad', 'critica')
                            ->where('preview_shown_at', '<=', $reintentos['critica']);
                    });
            })
            ->orderByRaw("CASE prioridad WHEN 'critica' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['idNotificaciones', 'Mensaje_Corto', 'Mensaje_Largo', 'url', 'prioridad']);

        foreach ($candidatas as $notificacion) {
            $reclamo = Notificacion::query()
                ->where('idNotificaciones', $notificacion->idNotificaciones)
                ->where('users_id', $user->id)
                ->where('prioridad', $notificacion->prioridad)
                ->where('created_at', '>=', $desde)
                ->where(function ($query) use ($notificacion, $reintentos) {
                    $query->whereNull('preview_shown_at');

                    if ($reintentos[$notificacion->prioridad] !== null) {
                        $query->orWhere('preview_shown_at', '<=', $reintentos[$notificacion->prioridad]);
                    }
                });
            $reclamada = $reclamo->update(['preview_shown_at' => now()]);

            if ($reclamada !== 1) {
                continue;
            }

            return [
                'id' => $notificacion->idNotificaciones,
                'title' => $notificacion->Mensaje_Corto,
                'message' => $notificacion->Mensaje_Largo,
                'url' => $this->urlNotificacionSegura($notificacion->url),
                'priority' => $notificacion->prioridad,
            ];
        }

        return null;
    }

    /**
     * Sólo se entregan destinos internos o relativos. Esto conserva los enlaces
     * actuales del sistema y evita que una URL almacenada apunte a otro origen.
     */
    private function urlNotificacionSegura(?string $url): string
    {
        $url = trim((string) $url);

        if ($url === '' || $url === '#') {
            return route('notifications.index');
        }

        $partes = parse_url($url);
        if ($partes === false) {
            return route('notifications.index');
        }

        if (!isset($partes['host'])) {
            return str_starts_with($url, '/') ? $url : route('notifications.index');
        }

        $hostActual = parse_url(config('app.url'), PHP_URL_HOST);
        if (($partes['scheme'] ?? '') === 'https' || ($partes['scheme'] ?? '') === 'http') {
            return $hostActual !== null && strcasecmp($partes['host'], $hostActual) === 0
                ? $url
                : route('notifications.index');
        }

        return route('notifications.index');
    }

    private function prioridadPorVencimiento(int $diasRestantes): string
    {
        if ($diasRestantes <= 0) {
            return 'critica';
        }

        return $diasRestantes <= 5 ? 'alta' : 'normal';
    }

}
