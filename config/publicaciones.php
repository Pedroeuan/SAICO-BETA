<?php

return [
    'autopublicar' => env('PUBLICACIONES_AUTOPUBLICAR', false),
    'python_bin' => env('PUBLICACIONES_PYTHON_BIN', 'python'),
    'python_script' => env('PUBLICACIONES_PYTHON_SCRIPT', base_path('scripts-python/publicar_redes.py')),
    'python_timeout' => (int) env('PUBLICACIONES_PYTHON_TIMEOUT', 90),
    'redes_habilitadas' => array_values(array_filter(array_map('trim', explode(',', env('PUBLICACIONES_REDES_HABILITADAS', 'facebook'))))),
];
