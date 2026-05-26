<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictToCoreModules
{
    /**
     * Limita esta version del sistema a Publicaciones, Vehiculos y Usuarios.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $allowedPatterns = [
            '/',
            'dashboard',
            'home',
            'login',
            'logout',
            'register',
            'profile',
            'profile/*',
            'password',
            'password/*',
            'confirm-password',
            'forgot-password',
            'reset-password/*',
            'verify-email',
            'verify-email/*',
            'email/verification-notification',
            'publicaciones',
            'publicaciones/*',
            'vehiculos',
            'vehiculos/*',
            'salidas-vehiculos',
            'salidas-vehiculos/*',
            'Admin/index',
            'Admin/create',
            'registro/storeusuarios',
            'edicion/editusuarios/*',
            'edicion/updateUsuario/*',
            'Usuarios/eliminar/*',
            'notificacion/index',
            'notificaciones/update',
            'notificaciones/marcar-leida/*',
        ];

        if ($request->is($allowedPatterns)) {
            return $next($request);
        }

        return redirect()
            ->route('dashboard')
            ->with('warning', 'Esta version del sistema solo incluye Publicaciones, Gestion de Vehiculos y Usuarios.');
    }
}
