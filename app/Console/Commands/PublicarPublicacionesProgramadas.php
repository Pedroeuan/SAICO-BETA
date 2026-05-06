<?php

namespace App\Console\Commands;

use App\Services\Publicaciones\PublicacionService;
use Illuminate\Console\Command;
use Throwable;

class PublicarPublicacionesProgramadas extends Command
{
    protected $signature = 'publicaciones:procesar-programadas';

    protected $description = 'Publica automaticamente las publicaciones programadas cuya fecha ya vencio.';

    public function handle(PublicacionService $service): int
    {
        try {
            $procesadas = $service->publicarPendientesProgramadas();
            $this->info("Publicaciones programadas procesadas: {$procesadas}");

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Fallo el procesamiento de publicaciones programadas: ' . $exception->getMessage());

            return self::FAILURE;
        }
    }
}
