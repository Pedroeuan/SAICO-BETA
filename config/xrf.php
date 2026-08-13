<?php

return [
    // En producción puede indicarse la ruta completa con XRF_GHOSTSCRIPT_BINARY.
    'ghostscript_binary' => env('XRF_GHOSTSCRIPT_BINARY'),
    'render_dpi' => (int) env('XRF_RENDER_DPI', 180),

    // Coordenadas relativas de la hoja generada por el equipo XRF.
    'crops' => [
        // Recorta la tabla al paño del borde negro para que no viaje margen blanco al PDF final.
        'tabla_elementos' => ['x' => 0.0638, 'y' => 0.2333, 'width' => 0.3938, 'height' => 0.3786],
        'grafica_espectro' => ['x' => 0.575, 'y' => 0.105, 'width' => 0.395, 'height' => 0.305],
    ],
];
