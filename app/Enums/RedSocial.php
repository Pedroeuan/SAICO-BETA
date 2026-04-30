<?php

namespace App\Enums;

enum RedSocial: string
{
    case LinkedIn = 'linkedin';
    case Facebook = 'facebook';
    case Twitter = 'twitter';

    public function label(): string
    {
        return match ($this) {
            self::LinkedIn => 'LinkedIn',
            self::Facebook => 'Facebook',
            self::Twitter => 'X / Twitter',
        };
    }

    public function icono(): string
    {
        return match ($this) {
            self::LinkedIn => 'fab fa-linkedin',
            self::Facebook => 'fab fa-facebook',
            self::Twitter => 'fab fa-x-twitter',
        };
    }
}
