<?php

namespace App\Services\Publicaciones;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class FacebookGraphClient
{
    public function tokenConfigured(): bool
    {
        return $this->pageId() !== '' && $this->token() !== '';
    }

    public function pageId(): string
    {
        return trim((string) config('publicaciones.facebook.page_id', ''));
    }

    /**
     * @return array{data: array<int, array<string, mixed>>, paging: array<string, mixed>}
     */
    public function fetchPublishedPosts(int $limit = 25, ?string $after = null): array
    {
        $this->ensureConfigured();

        $query = [
            'fields' => implode(',', [
                'id',
                'message',
                'created_time',
                'permalink_url',
                'full_picture',
            ]),
            'limit' => max(1, min($limit, 100)),
        ];

        if ($after !== null && $after !== '') {
            $query['after'] = $after;
        }

        $payload = $this->get(sprintf('/%s/published_posts', $this->pageId()), $query);

        return [
            'data' => Arr::wrap($payload['data'] ?? []),
            'paging' => is_array($payload['paging'] ?? null) ? $payload['paging'] : [],
        ];
    }

    /**
     * @return array{post: array<string, mixed>}
     */
    public function fetchPostMetrics(string $postId): array
    {
        $this->ensureConfigured();

        $post = $this->get('/' . $postId, [
            'fields' => implode(',', [
                'id',
                'permalink_url',
                'created_time',
                'shares',
                'comments.summary(true)',
                'reactions.summary(true)',
            ]),
        ]);

        return [
            'post' => $post,
        ];
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    protected function get(string $path, array $query = []): array
    {
        $response = Http::timeout((int) config('publicaciones.facebook.timeout', 20))
            ->acceptJson()
            ->get($this->baseUrl() . $path, array_merge($query, [
                'access_token' => $this->token(),
            ]));

        try {
            $response->throw();
        } catch (RequestException $exception) {
            $body = $response->json();
            $message = is_array($body) ? json_encode($body, JSON_UNESCAPED_UNICODE) : $response->body();

            throw new RuntimeException('Facebook Graph API respondio con error: ' . $message, previous: $exception);
        }

        $payload = $response->json();

        if (!is_array($payload)) {
            throw new RuntimeException('Facebook Graph API devolvio una respuesta invalida.');
        }

        return $payload;
    }

    protected function ensureConfigured(): void
    {
        if (!$this->tokenConfigured()) {
            throw new RuntimeException('No se encontro la configuracion de Facebook Page ID o Page Token.');
        }
    }

    protected function baseUrl(): string
    {
        return rtrim((string) config('publicaciones.facebook.base_url', 'https://graph.facebook.com/v25.0'), '/');
    }

    protected function token(): string
    {
        return trim((string) config('publicaciones.facebook.page_token', ''));
    }
}
