<?php

namespace App\Enums;

enum TypeStockMovementEnum: string
{
    case IN         = 'in';
    case OUT        = 'out';
    case ADJUSTMENT = 'adjustment';

    public function getLabel(): string
    {
        return match ($this) {
            self::IN         => 'In',
            self::OUT        => 'Out',
            self::ADJUSTMENT => 'Adjustment',
        };
    }
}
