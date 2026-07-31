<?php

return [
    // En producción puede indicarse la ruta completa con XRF_GHOSTSCRIPT_BINARY.
    'ghostscript_binary' => env('XRF_GHOSTSCRIPT_BINARY'),
    'render_dpi' => (int) env('XRF_RENDER_DPI', 180),

    // Coordenadas relativas de la hoja generada por el equipo XRF.
    'crops' => [
        // Mantiene visibles la penúltima y última fila en los tres recortes del formato 04_03.
        'tabla_elementos' => ['x' => 0.054, 'y' => 0.225, 'width' => 0.410, 'height' => 0.400],
        'grafica_espectro' => ['x' => 0.575, 'y' => 0.105, 'width' => 0.395, 'height' => 0.305],
    ],
];
