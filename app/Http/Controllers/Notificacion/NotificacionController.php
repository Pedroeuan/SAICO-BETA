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
        // Obtener el usuario autenticado
        $user = Auth::user();

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
            ->whereDate('Prox_fecha_calibracion', '>=', now())
            ->get();
            //->get();

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
                if ($tipo === 'EQUIPOS') {
                    $fechaCalibracion = $certificado->Prox_fecha_calibracion;
                } elseif ($tipo === 'CONSUMIBLES' || $tipo === 'BLOCK Y PROBETA') {
                    $fechaCalibracion = $certificado->Fecha_calibracion;
                } else {
                    // Si no corresponde a ninguno de los tipos, continuar con el siguiente
                    continue;
                }

                // Convertir la fecha al formato DD-MM-YYYY
                $fechaCalibracionFormateada = Carbon::parse($fechaCalibracion)->format('d-m-Y');

                // Determinar los días restantes para la calibración
                //$diasRestantes = Carbon::parse($fechaActual)->diffInDays($fechaCalibracion);
                $diasRestantes = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($fechaCalibracion)->startOfDay(),false);

                // Crear los mensajes corto y largo
                if ($diasRestantes == 0) 
                {
                    if ($tipo === 'EQUIPOS') 
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Calibración VENCIDA";
                        $mensajeLargo = "La Calibración del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                        $mensajeLargoemail = "La Calibración del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'>VENCIDA</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                    }
                    elseif ($tipo === 'CONSUMIBLES')
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Certificado CADUCADO";
                        $mensajeLargo = "El Certificado del Consumible: ".$Nombre_C.", Con el No. certificado: " . $certificado->No_certificado . " está CADUCADO (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                        $mensajeLargoemail = "El Certificado del Consumible: ".$Nombre_C.", <br>Con el No. certificado: " . $certificado->No_certificado . "<br>está <span style='color: #E01A22;'>CADUCADO </span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                    }
                    elseif ($tipo === 'BLOCK Y PROBETA')
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Calibración VENCIDA";
                        $mensajeLargo = "El Block y Probeta: ".$Nombre_C.", La Calibración del No. economico: " . $No_economico . " esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                        $mensajeLargoemail = "El Block y Probeta: ".$Nombre_C.", <br>La Calibración del No. economico: " . $No_economico . "<br>esta <span style='color: #E01A22;'> VENCIDA </span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                    }
                } 
                else 
                {
                    if ($tipo === 'EQUIPOS') 
                    {
                        // Mensaje para certificados próximos a vencer
                        $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantes días";
                        $mensajeLargo = "La calibración del Equipo: ".$Nombre_C.", Con No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                        $mensajeLargoemail = "La calibración del Equipo: ".$Nombre_C.", <br>Con No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'>VENCER en $diasRestantes días</span><br>(Fecha de vencimiento: <span style='color: #E01A22;'>" . $fechaCalibracionFormateada . "</span>)";
                    }
                    elseif ($tipo === 'CONSUMIBLES')
                    {
                        $mensajeCorto = "Cert. Prox. a CADUCAR en $diasRestantes días";
                        $mensajeLargo = "El Certificado del Consumible: ".$Nombre_C.", Con No. certificado: " . $certificado->No_certificado . " está próximo a CADUCAR en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                        $mensajeLargoemail = "El Certificado del Consumible: ".$Nombre_C.", <br>Con No. certificado: " . $certificado->No_certificado . " <br>está próximo a <span style='color: #E01A22;'> CADUCAR en $diasRestantes días</span> <br>(Fecha de vencimiento: " . $fechaCalibracionFormateada . "</span>)";
                    }
                    if ($tipo === 'BLOCK Y PROBETA') 
                    {
                        // Mensaje para certificados próximos a vencer
                        $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantes días";
                        $mensajeLargo = "La calibración del Block y Probeta: ".$Nombre_C.", Con el No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                        $mensajeLargoemail = "La calibración del Block y Probeta: ".$Nombre_C.", <br>Con el No. economico: " . $No_economico . " <br>está próximo a <span style='color: #E01A22;'> VENCER en $diasRestantes días</span> <br>(Fecha de vencimiento: " . $fechaCalibracionFormateada . "</span>)";
                    }
                    
                }
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

                // Crear notificaciones para todos los usuarios con los roles especificados
                foreach ($usuarios as $usuario){
                // Verificar si la notificación ya existe
                $notificacionExistente = Notificacion::where('users_id', $usuario->id)
                    ->where('Mensaje_Corto', $mensajeCorto)
                    ->where('Mensaje_Largo', $mensajeLargo)
                    ->first();

                    if (!$notificacionExistente) 
                    {
                        // Crear la notificación solo si no existe
                        $notificacion = new Notificacion();
                        $notificacion->users_id = $usuario->id; // Asociar la notificación al usuario correspondiente
                        $notificacion->Mensaje_Corto = $mensajeCorto;
                        $notificacion->Mensaje_Largo = $mensajeLargo;
                        $notificacion->url = $url;
                        $notificacion->leida = false;
                        $notificacion->save();

                        // 📧 Enviar correo
                    //$usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargoemail,$url));
                    }

                }
            }
        }
    }

    public function getNotificaciones()
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
                'url' => $notificacion->url ?? '#', // usa la URL de la tabla, fallback si es null
            ];
        });
    
        // Retornar las notificaciones en formato JSON
        return response()->json($formattedNotifications);
    }
    
    public function marcarComoLeida($id)
    {
        $notificacion = Notificacion::find($id);

        if ($notificacion) {
            $notificacion->leida = true;
            $notificacion->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

}
