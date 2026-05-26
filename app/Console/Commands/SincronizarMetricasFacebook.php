<?php

namespace App\Console\Commands;

use App\Services\Publicaciones\FacebookMetricasSyncService;
use Illuminate\Console\Command;
use Throwable;

class SincronizarMetricasFacebook extends Command
{
    protected $signature = 'publicaciones:facebook-sync-metricas {--limit=25} {--publicacion_id=}';

    protected $description = 'Sincroniza las metricas de Facebook para publicaciones registradas en el sistema.';

    public function handle(FacebookMetricasSyncService $service): int
    {
        try {
            $resultado = $service->sincronizar(
                (int) $this->option('limit'),
                $this->option('publicacion_id') ? (int) $this->option('publicacion_id') : null
            );

            $this->info(sprintf(
                'Sincronizacion terminada. Exitosas: %d, con error: %d',
                $resultado['sincronizadas'],
                $resultado['errores']
            ));

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('No fue posible sincronizar metricas de Facebook: ' . $exception->getMessage());

            return self::FAILURE;
        }
    }
}
