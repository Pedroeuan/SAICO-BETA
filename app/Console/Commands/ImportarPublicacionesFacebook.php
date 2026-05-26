<?php

namespace App\Console\Commands;

use App\Services\Publicaciones\FacebookImportService;
use Illuminate\Console\Command;
use Throwable;

class ImportarPublicacionesFacebook extends Command
{
    protected $signature = 'publicaciones:facebook-importar {--limit=25} {--after=}';

    protected $description = 'Importa publicaciones historicas de Facebook al modulo web.';

    public function handle(FacebookImportService $service): int
    {
        try {
            $resultado = $service->importarHistoricas(
                (int) $this->option('limit'),
                $this->option('after') ?: null
            );

            $this->info(sprintf(
                'Importacion completada. Nuevas: %d, actualizadas: %d, siguiente cursor: %s',
                $resultado['importadas'],
                $resultado['actualizadas'],
                $resultado['siguiente_cursor'] ?? 'N/D'
            ));

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('No fue posible importar publicaciones de Facebook: ' . $exception->getMessage());

            return self::FAILURE;
        }
    }
}
