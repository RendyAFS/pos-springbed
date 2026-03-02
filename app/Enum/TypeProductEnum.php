<?php

namespace App\Enum;

enum TypeProductEnum: string
{
    case SPRINGBED = 'springbed';
    case DIVAN     = 'divan';
    case HEADBOARD = 'headboard';
    case BUNDLE    = 'bundle';

    public function getLabel(): string
    {
        return match ($this) {
            self::SPRINGBED => 'Springbed',
            self::DIVAN     => 'Divan',
            self::HEADBOARD => 'Headboard',
            self::BUNDLE    => 'Bundle',
        };
    }
}
