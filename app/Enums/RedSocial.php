<?php

namespace App\Enums;

enum RedSocial: string
{
    case Facebook = 'facebook';
    case Instagram = 'instagram';
    case LinkedIn = 'linkedin';

    public function label(): string
    {
        return match ($this) {
            self::Facebook => 'Facebook',
            self::Instagram => 'Instagram',
            self::LinkedIn => 'LinkedIn',
        };
    }

    public function icono(): string
    {
        return match ($this) {
            self::Facebook => 'fab fa-facebook',
            self::Instagram => 'fab fa-instagram',
            self::LinkedIn => 'fab fa-linkedin',
        };
    }

    public function descripcion(): string
    {
        return match ($this) {
            self::Facebook => 'Ideal para alcance visual, comunidad y difusion general.',
            self::Instagram => 'Recomendado para contenido visual de marca, vacantes y campañas.',
            self::LinkedIn => 'Enfocado en reputacion corporativa, servicios y audiencia B2B.',
        };
    }
}
