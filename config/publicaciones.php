<?php

return [
    'autopublicar' => env('PUBLICACIONES_AUTOPUBLICAR', false),
    'python_bin' => env('PUBLICACIONES_PYTHON_BIN', 'python'),
    'python_script' => env('PUBLICACIONES_PYTHON_SCRIPT', base_path('scripts-python/publicar_redes.py')),
];
