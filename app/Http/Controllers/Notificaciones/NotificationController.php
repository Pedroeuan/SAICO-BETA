<?php

namespace App\Http\Controllers\Notificaciones;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\EquiposyConsumibles\certificados;

class NotificationController extends Controller
{
    // Mostrar la vista de todas las notificaciones
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10); // Cambia la cantidad de notificaciones según sea necesario
        //dd($notifications);

        return view('notifications.index', compact('notifications'));
    }

    // Obtener las notificaciones recientes para el dropdown
    public function fetch()
    {
        $user = Auth::user();
        $notifications = $user->unreadNotifications()->limit(10)->get(); // Obtén solo las últimas 10 notificaciones no leídas
        
        $data = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'icon' => 'fa fa-info-circle', // Cambia el ícono según sea necesario
                'text' => $notification->data['mensaje'],
                'time' => $notification->created_at->diffForHumans(),
            ];
        });

        
        return response()->json($data);
    }
}
     // Método para obtener las notificaciones
    /*  public function fetch()
        {
            // Obtener la fecha actual
            $today = Carbon::today();
    
            // Obtener los certificados cuya 'Prox_fecha_calibracion' esté a 10, 15 o 7 días
            $certificados = certificados::whereBetween('Prox_fecha_calibracion', [
                $today->copy()->addDays(7),
                $today->copy()->addDays(15)
            ])
            ->orWhereBetween('Prox_fecha_calibracion', [
                $today->copy()->addDays(10),
                $today->copy()->addDays(10)
            ])->get();
    
            // Formatear las notificaciones
            $notifications = $certificados->map(function ($certificado) {
                return [
                    'id' => $certificado->id,
                    'title' => 'Próxima Calibración',
                    'message' => 'El certificado ' . $certificado->id . ' necesita calibración el ' . $certificado->Prox_fecha_calibracion->format('d-m-Y'),
                    'url' => route('certificados.show', $certificado->id), // Enlace a detalles del certificado
                ];
            });
    
            return response()->json($notifications);
        }*/

