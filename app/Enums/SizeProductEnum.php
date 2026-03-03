<?php

namespace App\Enums;

enum SizeProductEnum: string
{
    case SINGLE = 'single';
    case QUEEN  = 'queen';
    case KING   = 'king';
    case CUSTOM = 'custom';

    public function getLabel(): string
    {
        return match ($this) {
            self::SINGLE => 'Single',
            self::QUEEN  => 'Queen',
            self::KING   => 'King',
            self::CUSTOM => 'Custom',
        };
    }
}
