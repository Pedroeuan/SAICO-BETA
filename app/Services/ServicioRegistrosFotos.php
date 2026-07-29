<?php

namespace App\Services;

class ServicioRegistrosFotos
{
    /**
     * Conserva un solo registro por ruta de imagen o por cuadro de texto
     * ubicado en la misma página y posición con el mismo contenido.
     */
    public static function deduplicar(array $imagenes): array
    {
        $unicas = [];
        $claves = [];

        foreach ($imagenes as $imagen) {
            if (!is_array($imagen)) {
                continue;
            }

            $ruta = trim((string) ($imagen['ruta'] ?? ''));
            if ($ruta !== '') {
                $clave = 'ruta|' . strtolower($ruta);
            } elseif (!empty($imagen['es_cuadro_texto'])) {
                $clave = 'texto|'
                    . (int) ($imagen['pagina'] ?? 1) . '|'
                    . (string) ($imagen['posicion'] ?? '') . '|'
                    . sha1(trim((string) ($imagen['comentario'] ?? '')));
            } else {
                $clave = 'registro|' . sha1(json_encode($imagen));
            }

            if (isset($claves[$clave])) {
                continue;
            }

            $claves[$clave] = true;
            $unicas[] = $imagen;
        }

        return $unicas;
    }
}
