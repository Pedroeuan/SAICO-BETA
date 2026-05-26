<?php

return [
    'autopublicar' => env('PUBLICACIONES_AUTOPUBLICAR', false),
    'solo_lectura_analytics' => env('PUBLICACIONES_SOLO_LECTURA_ANALYTICS', true),
    'python_bin' => env('PUBLICACIONES_PYTHON_BIN', 'python'),
    'python_script' => env('PUBLICACIONES_PYTHON_SCRIPT', base_path('scripts-python/publicar_redes.py')),
    'python_timeout' => (int) env('PUBLICACIONES_PYTHON_TIMEOUT', 90),
    'redes_habilitadas' => array_values(array_filter(array_map('trim', explode(',', env('PUBLICACIONES_REDES_HABILITADAS', 'facebook'))))),
    'facebook' => [
        'base_url' => env('FACEBOOK_GRAPH_BASE_URL', 'https://graph.facebook.com/v25.0'),
        'page_id' => env('FACEBOOK_PAGE_ID'),
        'page_token' => env('FACEBOOK_PAGE_TOKEN'),
        'timeout' => (int) env('FACEBOOK_GRAPH_TIMEOUT', 20),
        'insights_metrics' => array_values(array_filter(array_map(
            'trim',
            explode(',', env('FACEBOOK_INSIGHTS_METRICS', 'post_impressions,post_impressions_unique,post_engaged_users'))
        ))),
    ],
];
