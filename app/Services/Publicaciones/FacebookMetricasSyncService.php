<?php

namespace App\Services\Publicaciones;

use App\Models\Publicacion;
use App\Models\PublicacionMetricaHistorial;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FacebookMetricasSyncService
{
    public function __construct(
        protected FacebookGraphClient $client
    ) {
    }

    /**
     * @return array{sincronizadas:int, errores:int}
     */
    public function sincronizar(int $limit = 25, ?int $publicacionId = null): array
    {
        $sincronizadas = 0;
        $errores = 0;

        $query = Publicacion::query()
            ->whereNotNull('facebook_post_id')
            ->when($publicacionId !== null, fn ($builder) => $builder->whereKey($publicacionId))
            ->orderByRaw("CASE WHEN sincronizado_metricas_at IS NULL THEN 0 ELSE 1 END")
            ->orderBy('sincronizado_metricas_at')
            ->limit(max(1, min($limit, 100)));

        foreach ($query->get() as $publicacion) {
            try {
                $this->sincronizarPublicacion($publicacion);
                $sincronizadas++;
            } catch (\Throwable $exception) {
                $errores++;
                $publicacion->forceFill([
                    'estado_sync_metricas' => 'error',
                    'ultima_respuesta_api' => [
                        'error_sync_metricas' => $exception->getMessage(),
                    ],
                ])->save();
            }
        }

        return compact('sincronizadas', 'errores');
    }

    protected function sincronizarPublicacion(Publicacion $publicacion): void
    {
        $response = $this->client->fetchPostMetrics((string) $publicacion->facebook_post_id);
        $post = $response['post'];

        $reacciones = (int) Arr::get($post, 'reactions.summary.total_count', 0);
        $comentarios = (int) Arr::get($post, 'comments.summary.total_count', 0);
        $compartidos = (int) Arr::get($post, 'shares.count', 0);
        $alcance = null;
        $impresiones = null;
        $clicks = null;
        $engagement = null;

        DB::transaction(function () use (
            $publicacion,
            $post,
            $response,
            $reacciones,
            $comentarios,
            $compartidos,
            $alcance,
            $impresiones,
            $clicks,
            $engagement
        ): void {
            $publicacion->forceFill([
                'facebook_permalink' => $post['permalink_url'] ?? $publicacion->facebook_permalink,
                'facebook_created_at' => $post['created_time'] ?? $publicacion->facebook_created_at,
                'sincronizado_metricas_at' => now(),
                'estado_sync_metricas' => 'ok',
                'metricas_facebook_json' => [
                    'reacciones' => $reacciones,
                    'comentarios' => $comentarios,
                    'compartidos' => $compartidos,
                    'alcance' => $alcance,
                    'impresiones' => $impresiones,
                    'clicks' => $clicks,
                    'engagement' => $engagement,
                    'alcance_disponible' => false,
                    'insights_pendientes_meta' => true,
                ],
                'ultima_respuesta_api' => $response,
            ])->save();

            PublicacionMetricaHistorial::updateOrCreate(
                [
                    'publicacion_id' => $publicacion->id,
                    'fecha_corte' => now()->toDateString(),
                ],
                [
                    'reacciones' => $reacciones,
                    'comentarios' => $comentarios,
                    'compartidos' => $compartidos,
                    'alcance' => 0,
                    'impresiones' => 0,
                    'clicks' => 0,
                    'engagement' => 0,
                    'detalle_json' => $response,
                ]
            );
        });
    }
}
