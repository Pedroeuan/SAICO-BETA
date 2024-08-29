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
        Gate::define('Superadmin-access', function ($user) { 
            return $user->rol === 'Super Administrador';
        });

        Gate::define('administrador-access', function ($user) {
            return in_array($user->rol, ['Administrador']);
        });

        Gate::define('cliente-access', function ($user) {
            return $user->rol === 'Cliente';
        });

        Gate::define('ventas-access', function ($user) {
            return $user->rol === 'Ventas';
        });

        Gate::define('tecnicos-access', function ($user) {
            return $user->rol === 'Técnicos';
        });

        Gate::define('planeacion-access', function ($user) {
            return $user->rol === 'Planeación';
        });

        Gate::define('equipos-access', function ($user) {
            return $user->rol === 'Equipos';
        });

        
    }
}
