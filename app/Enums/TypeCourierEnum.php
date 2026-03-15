<?php

namespace App\Enums;

enum TypeCourierEnum: string
{
    case INTERNAL = 'internal';
    case EXTERNAL = 'external';

    public function getLabel(): string
    {
        return match ($this) {
            self::INTERNAL => 'Internal',
            self::EXTERNAL => 'External',
        };
    }
}
