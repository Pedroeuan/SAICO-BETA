<?php

namespace App\Http\Controllers\Notificaciones;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
