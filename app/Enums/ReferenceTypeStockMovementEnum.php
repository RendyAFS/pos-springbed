<?php

namespace App\Enums;

enum ReferenceTypeStockMovementEnum: string
{
    case PURCHASE    = 'purchase';
    case TRANSACTION = 'transaction';
    case ADJUSTMENT  = 'adjustment';

    public function getLabel(): string
    {
        return match ($this) {
            self::PURCHASE    => 'Purchase',
            self::TRANSACTION => 'Transaction',
            self::ADJUSTMENT  => 'Adjustment',
        };
    }
}
