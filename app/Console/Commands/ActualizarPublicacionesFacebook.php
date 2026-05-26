<?php

namespace App\Console\Commands;

use App\Services\Publicaciones\FacebookImportService;
use App\Services\Publicaciones\FacebookMetricasSyncService;
use Illuminate\Console\Command;
use Throwable;

class ActualizarPublicacionesFacebook extends Command
{
    protected $signature = 'publicaciones:facebook-actualizar {--limit=25} {--after=}';

    protected $description = 'Importa publicaciones nuevas de Facebook y sincroniza sus metricas visibles.';

    public function handle(
        FacebookImportService $importService,
        FacebookMetricasSyncService $syncService
    ): int {
        try {
            $limit = (int) $this->option('limit');
            $after = $this->option('after') ?: null;

            $importacion = $importService->importarHistoricas($limit, $after);
            $sincronizacion = $syncService->sincronizar($limit);

            $this->info(sprintf(
                'Actualizacion completada. Importadas: %d, actualizadas: %d, metricas OK: %d, metricas con error: %d',
                $importacion['importadas'],
                $importacion['actualizadas'],
                $sincronizacion['sincronizadas'],
                $sincronizacion['errores']
            ));

            if (!empty($importacion['siguiente_cursor'])) {
                $this->line('Siguiente cursor detectado: ' . $importacion['siguiente_cursor']);
            }

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('No fue posible actualizar publicaciones de Facebook: ' . $exception->getMessage());

            return self::FAILURE;
        }
    }
}
