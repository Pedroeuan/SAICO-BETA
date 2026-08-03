<?php

return [
    // Carpeta raíz de la instalación portable de Fiji utilizada por defecto en Windows.
    'home' => env('IMAGEJ_HOME', 'C:\\Fiji.app'),

    // Rutas opcionales para producción. Si están vacías, el servicio las descubre dentro de IMAGEJ_HOME.
    'java' => env('IMAGEJ_JAVA'),
    'jar' => env('IMAGEJ_JAR'),

    // Tiempo máximo permitido para que un macro de ImageJ termine su procesamiento.
    'timeout' => (int) env('IMAGEJ_TIMEOUT', 300),
];
