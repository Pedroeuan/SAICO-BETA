<?php

namespace App\Http\Controllers\Notificacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

class NotificacionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Obtener solo las notificaciones del usuario autenticado
        $notificaciones = Notificacion::where('users_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        return view('notifications.index', compact('notificaciones'));
    }

    public function crearNotificacionesCertificados()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener fechas límite para las consultas
        $fechaActual = Carbon::now();
        $fecha30DiasAntes = $fechaActual->copy()->subDays(30)->toDateString();
        $fecha15DiasAntes = $fechaActual->copy()->subDays(15)->toDateString();
        $fecha7DiasAntes = $fechaActual->copy()->subDays(7)->toDateString();
        $fecha0DiasAntes = $fechaActual->copy()->subDays(0)->toDateString();

        // Obtener todos los certificados que están relacionados con la tabla general_eyc
        $certificados = Certificados::with('generaleyc') // Cargar la relación con general_eyc
            ->whereIn('Prox_fecha_calibracion', [$fecha30DiasAntes, $fecha15DiasAntes, $fecha7DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_calibracion', [$fecha30DiasAntes, $fecha15DiasAntes, $fecha7DiasAntes, $fecha0DiasAntes])
            ->get();

        // Recorrer cada certificado
        foreach ($certificados as $certificado) {
            // Obtener el registro de general_eyc relacionado con el certificado
            $generalEyc = $certificado->generalEyc;
            $No_economico = $generalEyc->No_economico;

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
                $diasRestantes = Carbon::parse($fechaCalibracion)->diffInDays($fechaActual);

                // Asegúrate de que $diasRestantes es un entero
                $diasRestantes = (int) $diasRestantes;

                // Crear los mensajes corto y largo
                if ($diasRestantes === 0) 
                {
                    if ($tipo === 'EQUIPOS') 
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Calibración VENCIDA";
                        $mensajeLargo = "La Calibración del No. economico: " . $No_economico . " la Calibración esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    elseif ($tipo === 'CONSUMIBLES')
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Certificado CADUCADO";
                        $mensajeLargo = "El No. certificado: " . $certificado->No_certificado . " está CADUCADO (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    elseif ($tipo === 'BLOCK Y PROBETA')
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Calibración VENCIDA";
                        $mensajeLargo = "La Calibración del No. economico: " . $No_economico . " la Calibración esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                } 
                else 
                {
                    if ($tipo === 'EQUIPOS') 
                    {
                        // Mensaje para certificados próximos a vencer
                        $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantes días";
                        $mensajeLargo = "La Calibración del No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    elseif ($tipo === 'CONSUMIBLES')
                    {
                        $mensajeCorto = "Cert. Prox. a CADUCAR en $diasRestantes días";
                        $mensajeLargo = "El No. certificado: " . $certificado->No_certificado . " está próximo a CADUCAR en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    if ($tipo === 'BLOCK Y PROBETA') 
                    {
                        // Mensaje para certificados próximos a vencer
                        $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantes días";
                        $mensajeLargo = "La Calibración del No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    
                }

                // Verificar si la notificación ya existe
                $notificacionExistente = Notificacion::where('users_id', $user->id)
                    ->where('Mensaje_Corto', $mensajeCorto)
                    ->where('Mensaje_Largo', $mensajeLargo)
                    ->first();

                if (!$notificacionExistente) {
                    // Crear la notificación solo si no existe
                    $notificacion = new Notificacion();
                    $notificacion->users_id = $user->id;
                    $notificacion->Mensaje_Corto = $mensajeCorto;
                    $notificacion->Mensaje_Largo = $mensajeLargo;
                    $notificacion->save();
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
                                       //->where('leido', false) // Descomenta esto si necesitas filtrar solo no leídas
                                        ->orderBy('created_at', 'desc')
                                       ->get(['idNotificaciones', 'Mensaje_Corto']); // Asegúrate de tener el 'id' también
    
        // Formatear las notificaciones para AdminLTE
        $formattedNotifications = $notificaciones->map(function ($notificacion) {
            return [
                'id' => $notificacion->idNotificaciones,
                'message' => $notificacion->Mensaje_Corto,
                'url' => '#', // Aquí puedes poner la URL real de la notificación o alguna acción relevante
            ];
        });
    
        // Retornar las notificaciones en formato JSON
        return response()->json($formattedNotifications);
    }
    
    
}
