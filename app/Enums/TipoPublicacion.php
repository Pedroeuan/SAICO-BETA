<?php

namespace App\Enums;

enum TipoPublicacion: string
{
    case Servicio = 'servicio';
    case Logro = 'logro';
    case Noticia = 'noticia';
    case Promocion = 'promocion';

    public function label(): string
    {
        return match ($this) {
            self::Servicio => '🛠 Servicio',
            self::Logro => '🏆 Logro',
            self::Noticia => '📰 Noticia',
            self::Promocion => '📣 Promocion',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Servicio => 'primary',
            self::Logro => 'success',
            self::Noticia => 'info',
            self::Promocion => 'warning text-dark',
        };
    }
}
