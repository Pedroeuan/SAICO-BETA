<?php

namespace App\Services;

use Illuminate\Support\Collection;

class EstadoDocumentacion
{
    public static function calcular(Collection $reportes): string
    {
        if ($reportes->isEmpty()) {
            return 'sin_reportes';
        }

        return $reportes->every(fn ($reporte) => $reporte->firmado)
            ? 'firmados' : 'pendientes';
    }
}
