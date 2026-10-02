<?php

namespace App\Services;

class ServicioJuntasReporteIM
{
    /**
     * Datos técnicos que solo existen en algunos formatos (Fiji y conteo lineal de 04_03).
     * Viajan en el mismo grupo para que Detalles_Generales conserve únicamente lo administrativo.
     */
    private const CLAVES_EXTRAS = ['ANALISIS_IMAGEN', 'CONTEO_GRANOS'];

    /**
     * 02_B/04 guarda junto a la tabla de juntas los resultados de dureza: lo medido, los
     * promedios calculados, la etapa y el acomodo de celdas del PDF. Antes vivian en
     * Reportes.Datos_Equipo, junto con el equipo, lo que mezclaba el equipo con el resultado.
     */
    public const CLAVES_DUREZA = [
        'DUREZA_PROMEDIO',
        'DUREZA_ROWS',
        'DUREZA_MERGE_CONFIG',
        'DUREZA_ETAPA',
    ];

    /**
     * Devuelve siempre ['bloques' => array, 'Norma_IM' => ?array, 'Patron_Grano' => ?array]
     * mas una entrada por clave extra presente, ya sea en el grupo o heredada de Detalles_Generales.
     * Acepta el formato nuevo (claves) y el antiguo (lista de bloques).
     * Solo usa Detalles_Generales cuando la clave NO existe en Juntas
     * (reporte anterior); si existe con valor null, significa "sin dato".
     */
    public function normalizar(?string $juntasJson, array $detallesGenerales = []): array
    {
        $juntas = $juntasJson ? json_decode($juntasJson, true) : [];
        $juntas = is_array($juntas) ? $juntas : [];

        $formatoNuevo = array_key_exists('bloques', $juntas)
            || array_key_exists('Norma_IM', $juntas)
            || array_key_exists('Patron_Grano', $juntas);

        $bloques = $formatoNuevo ? ($juntas['bloques'] ?? []) : $juntas;

        $norma = array_key_exists('Norma_IM', $juntas)
            ? $juntas['Norma_IM']
            : ($detallesGenerales['Norma_IM'] ?? null);

        $patron = array_key_exists('Patron_Grano', $juntas)
            ? $juntas['Patron_Grano']
            : ($detallesGenerales['PATRON_GRANO'] ?? null);

        $normalizado = [
            'bloques' => is_array($bloques) ? $bloques : [],
            'Norma_IM' => is_array($norma) ? $norma : null,
            'Patron_Grano' => is_array($patron) ? $patron : null,
        ];

        foreach (self::CLAVES_EXTRAS as $clave) {
            $valor = array_key_exists($clave, $juntas)
                ? $juntas[$clave]
                : ($detallesGenerales[$clave] ?? null);

            $normalizado[$clave] = is_array($valor) ? $valor : null;
        }

        return $normalizado;
    }

    /**
     * Las claves extras se omiten cuando no hay valor para no alterar el JSON de los formatos
     * que solo manejan bloques, norma y patron.
     */
    public function armar(array $bloques, ?array $normaIM, ?array $patronGrano, array $extras = []): string
    {
        $contenido = [
            'bloques' => $bloques,
            'Norma_IM' => $normaIM,
            'Patron_Grano' => $patronGrano,
        ];

        foreach (self::CLAVES_EXTRAS as $clave) {
            if (array_key_exists($clave, $extras)) {
                $contenido[$clave] = $extras[$clave];
            }
        }

        return json_encode($contenido, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Lectura de 02_B/04. Acepta el grupo nuevo (con 'bloques' y claves DUREZA) y el antiguo,
     * que era una simple lista de bloques con la dureza todavia en Datos_Equipo.
     * Devuelve siempre 'bloques' mas una entrada por clave de dureza.
     */
    public function normalizarDureza(?string $juntasJson, array $datosEquipo = []): array
    {
        $juntas = $juntasJson ? json_decode($juntasJson, true) : [];
        $juntas = is_array($juntas) ? $juntas : [];

        $formatoNuevo = array_key_exists('bloques', $juntas)
            || array_key_exists('DUREZA_PROMEDIO', $juntas);

        $normalizado = [
            'bloques' => $formatoNuevo
                ? (is_array($juntas['bloques'] ?? null) ? $juntas['bloques'] : [])
                : $juntas,
        ];

        foreach (self::CLAVES_DUREZA as $clave) {
            // Igual que en normalizar(): Datos_Equipo solo rescues reportes anteriores.
            $normalizado[$clave] = array_key_exists($clave, $juntas)
                ? $juntas[$clave]
                : ($datosEquipo[$clave] ?? null);
        }

        return $normalizado;
    }

    /**
     * 02_B/04 no tiene norma ni patron, asi que arma un grupo propio con solo bloques y dureza.
     */
    public function armarDureza(array $bloques, array $dureza = []): string
    {
        $contenido = ['bloques' => $bloques];

        foreach (self::CLAVES_DUREZA as $clave) {
            if (array_key_exists($clave, $dureza)) {
                $contenido[$clave] = $dureza[$clave];
            }
        }

        return json_encode($contenido, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Al clonar o al abrir la segunda etapa se conservan los promedios ANTES y se reinician
     * los resultados de la etapa siguiente, igual que hacia la segunda captura.
     */
    public function reiniciarDurezaSegundaEtapa(?array $promedios): array
    {
        $promedios = is_array($promedios) ? $promedios : [];

        foreach (['DESPUES_A', 'DESPUES_B', 'DESPUES_C', 'DESPUES_B1', 'DESPUES_BM'] as $campo) {
            $promedios[$campo] = '---';
        }

        return $promedios;
    }
}