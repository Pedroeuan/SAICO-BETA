<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;

class ArchivosReportePt
{
    /** Conserva nombres habituales y evita separadores, recorridos y colisiones al sustituirlos. */
    public static function componente($valor): string
    {
        $valor = (string) $valor;
        if ($valor !== '' && $valor !== '.' && $valor !== '..'
            && !preg_match('/[\\\\\/\x00-\x1f<>:"|?*]/u', $valor)
            && !preg_match('/[. ]$/u', $valor)) {
            return $valor;
        }

        $limpio = preg_replace('/[\\\\\/\x00-\x1f<>:"|?*]+/u', '_', $valor) ?? '';
        $limpio = trim($limpio, ". ");
        return ($limpio !== '' ? $limpio : 'sin_dato') . '_' . substr(hash('sha256', $valor), 0, 12);
    }

    public static function imagenes($imagenes): array
    {
        if ($imagenes === null) return [];
        if (!is_array($imagenes)) {
            throw ValidationException::withMessages(['images_base64' => 'Las imágenes deben enviarse como una lista.']);
        }
        $resultado = [];
        foreach ($imagenes as $indice => $valor) {
            if ($valor === null || $valor === '') continue;
            if (!is_string($valor)) {
                throw ValidationException::withMessages(["images_base64.$indice" => 'La imagen no es válida.']);
            }
            $contenido = preg_replace('/^data:image\/[a-zA-Z0-9.+-]+;base64,/i', '', trim($valor));
            $contenido = preg_replace('/\s+/', '', $contenido);
            $bytes = base64_decode($contenido, true);
            if ($bytes === false || $bytes === '' || @getimagesizefromstring($bytes) === false) {
                throw ValidationException::withMessages(["images_base64.$indice" => 'La imagen no es válida.']);
            }
            $resultado[$indice] = $bytes;
        }
        return $resultado;
    }
}
