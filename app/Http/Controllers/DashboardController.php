<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (Gate::forUser($user)->allows('vehiculos-admin-access')) {
            return redirect()->route('salidas.panel');
        }

        if (Gate::forUser($user)->allows('administrador-access')) {
            return redirect('/Admin/index');
        }

        return redirect()->route('publicaciones.index');
    }
}
