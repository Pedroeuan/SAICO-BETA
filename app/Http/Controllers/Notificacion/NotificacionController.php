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
        // Obtener usuarios con el rol especificado y cargar sus notificaciones
        //$usuariosConRol = User::where('rol', $rolBuscado)->with('notificaciones')->get();

        // Recopilar todas las notificaciones de los usuarios con el rol especificado
        /*$notificaciones = $usuariosConRol->flatMap(function ($usuario) {
            return $usuario->notificaciones;
        });*/
        $notificaciones = Notificacion::all();

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

    // Obtener certificados con fechas de calibración específicas
    $certificados = certificados::whereIn('Prox_fecha_calibracion', [$fecha30DiasAntes, $fecha15DiasAntes, $fecha7DiasAntes, $fecha0DiasAntes])->get();

    // Recorrer cada certificado y crear una notificación para cada uno
    foreach ($certificados as $certificado) {
        // Determinar los días restantes para la calibración
        $diasRestantes = Carbon::parse($certificado->Prox_fecha_calibracion)->diffInDays($fechaActual);

        // Asegúrate de que $diasRestantes es un entero
        $diasRestantes = (int) $diasRestantes;

        // Crear los mensajes corto y largo
        if ($diasRestantes === 0) {
            // Mensaje especial para certificados vencidos
            $mensajeCorto = "Certificado VENCIDO";
            $mensajeLargo = "El No. certificado: " . $certificado->No_certificado . " está VENCIDO (Fecha de vencimiento: " . $certificado->Prox_fecha_calibracion . ")";
        } else {
            // Mensaje para certificados próximos a vencer
            $mensajeCorto = "Cert. Prox. a VENCER en $diasRestantes días";
            $mensajeLargo = "El No. certificado: " . $certificado->No_certificado . " está próximo a vencer en $diasRestantes días (Fecha de vencimiento: " . $certificado->Prox_fecha_calibracion . ")";
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
