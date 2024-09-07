<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    /*public function index()
    {
        // Obtener el usuario autenticado
        $user = auth()->user();

        // Obtener notificaciones no leídas
        $notificaciones = $user->unreadNotifications;

        //return view('dashboard', compact('notificaciones'));
        return view('MenuPrincipal', compact('notificaciones'));
    }*/
    
    public function index()
    {
        //$notificaciones = auth()->user()->notifications;

        //return view('notificaciones.index', compact('notificaciones'));
        //return view('MenuPrincipal', compact('notificaciones'));
        return view('MenuPrincipal');
    }

}
