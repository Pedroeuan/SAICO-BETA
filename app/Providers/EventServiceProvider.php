<?php

namespace App\Providers;

use App\Models\Lineal_Ideal\Lineal_Ideal;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Models\Reporte\ComentarioReporte;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\Reporte\reporte;
use App\Observers\ReportePdfCacheObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // El PDF cacheado debe descartarse cuando cambia el reporte o sus datos.
        reporte::observe(ReportePdfCacheObserver::class);
        Fotos_Reporte::observe(ReportePdfCacheObserver::class);
        Firma_Reporte::observe(ReportePdfCacheObserver::class);
        Grupo_Juntas_Detalles_Re::observe(ReportePdfCacheObserver::class);
        Lineal_Ideal::observe(ReportePdfCacheObserver::class);
        ComentarioReporte::observe(ReportePdfCacheObserver::class);
        Prueba_Aplica::observe(ReportePdfCacheObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
