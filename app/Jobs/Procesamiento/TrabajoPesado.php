<?php

namespace App\Jobs\Procesamiento;

use Illuminate\Queue\Middleware\WithoutOverlapping;

trait TrabajoPesado
{
    public const CLAVE_BLOQUEO = 'saico-procesamiento-pesado';

    /**
     * Un mismo candado se comparte entre Fiji, XRF y PDF.
     * Esto protege el servidor aun cuando produccion tenga mas de un worker.
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping(self::CLAVE_BLOQUEO))
                ->shared()
                ->releaseAfter(10)
                ->expireAfter(420),
        ];
    }
}
