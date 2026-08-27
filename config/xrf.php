<?php

return [
    // En producción puede indicarse la ruta completa con XRF_GHOSTSCRIPT_BINARY.
    'ghostscript_binary' => env('XRF_GHOSTSCRIPT_BINARY'),
    'render_dpi' => (int) env('XRF_RENDER_DPI', 180),
    // Olympus imprime texto más pequeño; se rasteriza con más resolución para
    // conservar nitidez cuando el recorte se ajusta a la celda del reporte.
    'olympus_render_dpi' => (int) env('XRF_OLYMPUS_RENDER_DPI', 360),

    // Coordenadas relativas de la hoja generada por el equipo XRF.
    'crops' => [
        // Recorta la tabla al paño del borde negro para que no viaje margen blanco al PDF final.
        'tabla_elementos' => ['x' => 0.0638, 'y' => 0.2333, 'width' => 0.3938, 'height' => 0.3786],
        'grafica_espectro' => ['x' => 0.575, 'y' => 0.105, 'width' => 0.395, 'height' => 0.305],
    ],

    /*
     * El reporte Olympus distribuye la tabla y el espectro en zonas distintas
     * y no dibuja una cuadrícula alrededor de los elementos. Se mantiene en un
     * perfil independiente para no alterar los recortes del equipo actual.
     */
    'olympus_crops' => [
        'tabla_elementos' => ['x' => 0.015, 'y' => 0.150, 'width' => 0.205, 'height' => 0.190],
        'grafica_espectro' => ['x' => 0.075, 'y' => 0.385, 'width' => 0.640, 'height' => 0.062],
    ],
];
