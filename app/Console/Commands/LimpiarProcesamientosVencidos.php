<?php

namespace App\Console\Commands;

use App\Models\Procesamiento\TrabajoProcesamiento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LimpiarProcesamientosVencidos extends Command
{
    protected $signature = 'procesamientos:limpiar-vencidos {--solo-archivos : Conserva los registros de estado}';
    protected $description = 'Elimina archivos privados temporales de Fiji, XRF y PDF que ya vencieron';

    /** Procesa en bloques para no cargar todos los trabajos historicos en memoria. */
    public function handle(): int
    {
        $eliminados = 0;
        TrabajoProcesamiento::query()
            ->whereNotNull('expira_at')
            ->where('expira_at', '<=', now())
            ->orderBy('id')
            ->chunkById(100, function ($trabajos) use (&$eliminados): void {
                foreach ($trabajos as $trabajo) {
                    // El directorio contiene solo temporales del UUID, nunca evidencias publicas del reporte.
                    Storage::disk('local')->deleteDirectory("procesamientos/{$trabajo->usuario_id}/{$trabajo->id}");
                    if (!$this->option('solo-archivos')) {
                        $trabajo->delete();
                    }
                    $eliminados++;
                }
            }, 'id');

        $this->info("Procesamientos vencidos limpiados: {$eliminados}");

        return self::SUCCESS;
    }
}
