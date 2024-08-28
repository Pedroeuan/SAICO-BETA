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
        Gate::define('admin-access', function ($user) { 
            return $user->rol === 'Super Administrador';
        });

        Gate::define('operativo-access', function ($user) {
            return in_array($user->rol, ['Super Administrador', 'Operativo']);
        });

        Gate::define('equipos-access', function ($user) {
            return $user->rol === 'Equipos';
        });
    }
}
