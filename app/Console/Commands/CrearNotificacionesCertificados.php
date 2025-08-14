<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NotificacionesEyC;
use App\Models\Notificacion\Notificacion;
use App\Models\EquiposyConsumibles\certificados;
use App\Notifications\NotificacionCertificadoMailable;

class CrearNotificacionesCertificados extends Command
{
    protected $signature = 'notificaciones:crear-certificados';
    protected $description = 'Crear notificaciones para los certificados según sus fechas de calibración';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener fechas límite para las consultas
        $fechaActual = Carbon::now();
        $fecha40DiasAntes = $fechaActual->copy()->addDays(40)->toDateString();
        $fecha35DiasAntes = $fechaActual->copy()->addDays(35)->toDateString();
        $fecha30DiasAntes = $fechaActual->copy()->addDays(30)->toDateString();
        $fecha25DiasAntes = $fechaActual->copy()->addDays(25)->toDateString();
        $fecha15DiasAntes = $fechaActual->copy()->addDays(15)->toDateString();
        $fecha10DiasAntes = $fechaActual->copy()->addDays(10)->toDateString();
        $fecha7DiasAntes = $fechaActual->copy()->addDays(7)->toDateString();
        $fecha5DiasAntes = $fechaActual->copy()->addDays(5)->toDateString();
        $fecha0DiasAntes = $fechaActual->copy()->addDays(0)->toDateString();

        // Obtener todos los certificados que están relacionados con la tabla general_eyc
        $certificados = Certificados::with('generaleyc') // Cargar la relación con general_eyc
            ->whereIn('Prox_fecha_calibracion', [$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->orWhereIn('Fecha_calibracion', [$fecha40DiasAntes,$fecha35DiasAntes, $fecha30DiasAntes, $fecha25DiasAntes, $fecha15DiasAntes, $fecha10DiasAntes, $fecha7DiasAntes, $fecha5DiasAntes, $fecha0DiasAntes])
            ->get();

        // Obtener todos los usuarios con los roles especificados
        $usuarios = User::whereIn('rol', ['Super Administrador', 'Administrador', 'Equipos'])->get();
        //$usuarios = User::whereIn('rol', ['Equipos'])->get();

        // Recorrer cada certificado
        foreach ($certificados as $certificado) {
            // Obtener el registro de general_eyc relacionado con el certificado
            $generalEyc = $certificado->generalEyc;
            $No_economico = $generalEyc->No_economico;
            $Nombre_C = $generalEyc->Nombre_E_P_BP;
            $url = url('edicion/editEyC/' . $certificado->idGeneral_EyC);

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
                if ($diasRestantes === 0) 
                {
                    if ($tipo === 'EQUIPOS') 
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Calibración VENCIDA";
                        $mensajeLargo = "El Equipo: ".$Nombre_C.", La Calibración del No. economico: " . $No_economico . " la Calibración esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    elseif ($tipo === 'CONSUMIBLES')
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Certificado CADUCADO";
                        $mensajeLargo = "El Consumibles: ".$Nombre_C.", El No. certificado: " . $certificado->No_certificado . " está CADUCADO (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    elseif ($tipo === 'BLOCK Y PROBETA')
                    {
                        // Mensaje especial para certificados vencidos
                        $mensajeCorto = "Calibración VENCIDA";
                        $mensajeLargo = "El Block y Probeta: ".$Nombre_C.", La Calibración del No. economico: " . $No_economico . " la Calibración esta VENCIDA (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                } 
                else 
                {
                    if ($tipo === 'EQUIPOS') 
                    {
                        // Mensaje para certificados próximos a vencer
                        $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantes días";
                        $mensajeLargo = "El Equipo: ".$Nombre_C.", La Calibración del No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    elseif ($tipo === 'CONSUMIBLES')
                    {
                        $mensajeCorto = "Cert. Prox. a CADUCAR en $diasRestantes días";
                        $mensajeLargo = "El Consumibles: ".$Nombre_C.", El No. certificado: " . $certificado->No_certificado . " está próximo a CADUCAR en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    if ($tipo === 'BLOCK Y PROBETA') 
                    {
                        // Mensaje para certificados próximos a vencer
                        $mensajeCorto = "Calib. Prox. a VENCER en $diasRestantes días";
                        $mensajeLargo = "El Block y Probeta: ".$Nombre_C.", La Calibración del No. economico: " . $No_economico . " está próximo a VENCER en $diasRestantes días (Fecha de vencimiento: " . $fechaCalibracionFormateada . ")";
                    }
                    
                }

                //if($user->rol == 'Super Administrador' || $user->rol == 'Administrador' || $user->rol == 'Equipos' )
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
                        $notificacion->save();
                    }

                    // 📧 Enviar correo
                    $usuario->notify(new NotificacionCertificadoMailable($mensajeCorto, $mensajeLargo,$url));
                }
            }
        }
    }
}
