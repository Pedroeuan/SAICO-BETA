<?php

namespace App\Observers;

use App\Models\Lineal_Ideal\Lineal_Ideal;
use App\Models\PruebaAplica\Prueba_Aplica;
use App\Models\Reporte\ComentarioReporte;
use App\Models\Reporte\Firma_Reporte;
use App\Models\Reporte\Fotos_Reporte;
use App\Models\Reporte\Grupo_Juntas_Detalles_Re;
use App\Models\Reporte\reporte;
use App\Services\ServicioPdfCache;
use Illuminate\Database\Eloquent\Model;

/**
 * Invalida el PDF cacheado cuando cambia el reporte o cualquiera de los datos
 * que se incrustan en el PDF (fotos, firmas, juntas, lineal, comentarios, prueba).
 */
class ReportePdfCacheObserver
{
    /** Modelos que guardan el id del reporte en la columna idReportes. */
    private const POR_REPORTE = [
        Fotos_Reporte::class,
        Firma_Reporte::class,
        Grupo_Juntas_Detalles_Re::class,
        Lineal_Ideal::class,
        ComentarioReporte::class,
    ];

    public function saved(Model $model): void
    {
        $this->invalidar($model);
    }

    public function deleted(Model $model): void
    {
        $this->invalidar($model);
    }

    public function restored(Model $model): void
    {
        $this->invalidar($model);
    }

    private function invalidar(Model $model): void
    {
        if ($model instanceof reporte) {
            ServicioPdfCache::invalidar((int) $model->idReportes);
            return;
        }

        if ($model instanceof Prueba_Aplica) {
            reporte::where('idPrueba_Aplica', $model->getKey())
                ->pluck('idReportes')
                ->each(fn ($id) => ServicioPdfCache::invalidar((int) $id));
            return;
        }

        if (in_array(get_class($model), self::POR_REPORTE, true) && $model->idReportes) {
            ServicioPdfCache::invalidar((int) $model->idReportes);
        }
    }
}
