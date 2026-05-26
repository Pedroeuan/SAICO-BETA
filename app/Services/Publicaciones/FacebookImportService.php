<?php

namespace App\Services\Publicaciones;

use App\Enums\TipoPublicacion;
use App\Models\Publicacion;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacebookImportService
{
    public function __construct(
        protected FacebookGraphClient $client
    ) {
    }

    /**
     * @return array{importadas:int, actualizadas:int, siguiente_cursor:?string}
     */
    public function importarHistoricas(int $limit = 25, ?string $after = null): array
    {
        $response = $this->client->fetchPublishedPosts($limit, $after);
        $importadas = 0;
        $actualizadas = 0;

        foreach ($response['data'] as $post) {
            $resultado = $this->upsertPost($post);

            if ($resultado === 'created') {
                $importadas++;
            } elseif ($resultado === 'updated') {
                $actualizadas++;
            }
        }

        return [
            'importadas' => $importadas,
            'actualizadas' => $actualizadas,
            'siguiente_cursor' => Arr::get($response, 'paging.cursors.after'),
        ];
    }

    protected function upsertPost(array $post): string
    {
        $facebookPostId = (string) ($post['id'] ?? '');
        $contenido = trim((string) ($post['message'] ?? ''));
        $titulo = $this->resolverTitulo($contenido, $facebookPostId);
        $imagen = $this->normalizarImagen($post['full_picture'] ?? null);
        $createdAt = isset($post['created_time']) ? Carbon::parse($post['created_time']) : now();
        $shares = (int) Arr::get($post, 'shares.count', 0);
        $comments = (int) Arr::get($post, 'comments.summary.total_count', 0);
        $reactions = (int) Arr::get($post, 'reactions.summary.total_count', 0);

        return DB::transaction(function () use (
            $facebookPostId,
            $titulo,
            $contenido,
            $imagen,
            $createdAt,
            $shares,
            $comments,
            $reactions,
            $post
        ): string {
            $publicacion = Publicacion::withTrashed()
                ->where('facebook_post_id', $facebookPostId)
                ->first();

            $payload = [
                'titulo' => $titulo,
                'contenido' => $contenido !== '' ? $contenido : 'Publicacion importada desde Facebook.',
                'tipo' => TipoPublicacion::Noticia->value,
                'imagen' => is_string($imagen) ? $imagen : null,
                'imagen_alt' => $titulo,
                'url_destino' => $post['permalink_url'] ?? null,
                'redes_objetivo' => ['facebook'],
                'resultado_publicacion' => [
                    'facebook' => [
                        'exito' => true,
                        'post_id' => $facebookPostId,
                        'red' => 'facebook',
                        'error' => null,
                        'importada' => true,
                    ],
                ],
                'publicado_en_redes' => true,
                'publicado_at' => $createdAt,
                'origen_publicacion' => 'facebook_importada',
                'facebook_post_id' => $facebookPostId,
                'facebook_page_id' => $this->client->pageId(),
                'facebook_permalink' => $post['permalink_url'] ?? null,
                'facebook_created_at' => $createdAt,
                'estado_sync_metricas' => 'pendiente',
                'metricas_facebook_json' => [
                    'reacciones' => $reactions,
                    'comentarios' => $comments,
                    'compartidos' => $shares,
                ],
                'ultima_respuesta_api' => $post,
                'activo' => true,
            ];

            if ($publicacion instanceof Publicacion) {
                if ($publicacion->trashed()) {
                    $publicacion->restore();
                }

                $publicacion->fill($payload);
                $publicacion->save();

                return 'updated';
            }

            Publicacion::create($payload);

            return 'created';
        });
    }

    protected function resolverTitulo(string $contenido, string $facebookPostId): string
    {
        if ($contenido !== '') {
            return Str::limit(str_replace(["\r", "\n"], ' ', $contenido), 140, '');
        }

        return 'Facebook importado ' . Str::afterLast($facebookPostId, '_');
    }

    protected function normalizarImagen(mixed $imagen): ?string
    {
        if (!is_string($imagen)) {
            return null;
        }

        $imagen = trim($imagen);

        if ($imagen === '' || mb_strlen($imagen) > 500) {
            return null;
        }

        return $imagen;
    }
}
