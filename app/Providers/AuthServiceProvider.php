<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define tus políticas de acceso aquí
        /*Gate::define('Superadmin-access', function ($user) { 
            return $user->rol === 'Super Administrador';
        });*/

        Gate::define('administrador-access', function ($user) { 
            return $user->rol === 'Administrador' || $user->rol === 'Super Administrador';
        });

        Gate::define('cliente-access', function ($user) {
            return $user->rol === 'Cliente' || $user->rol === 'Super Administrador';
        });

        Gate::define('ventas-access', function ($user) {
            return $user->rol === 'Ventas' || $user->rol === 'Administrador' || $user->rol === 'Super Administrador';
        });

        Gate::define('tecnicos-access', function ($user) {
            return $user->rol === 'Técnicos' || $user->rol === 'Super Administrador';
        });

        Gate::define('planeacion-access', function ($user) {
            return $user->rol === 'Planeación' || $user->rol === 'Super Administrador';
        });

        Gate::define('equipos-access', function ($user) {
            return $user->rol === 'Equipos' || $user->rol === 'Super Administrador';
        });

        Gate::define('tecnicos-equipos-access', function ($user) {
            return $user->rol === 'Equipos' || $user->rol === 'Super Administrador' || $user->rol === 'Técnicos';
        });
        
    }
}
