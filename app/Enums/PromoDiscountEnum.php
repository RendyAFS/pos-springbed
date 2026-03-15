<?php

namespace App\Enums;

enum PromoDiscountEnum: string
{
    case PERCENTAGE = 'percentage';
    case NOMINAL      = 'nominal';

    public function getLabel(): string
    {
        return match ($this) {
            self::PERCENTAGE => 'Percentage',
            self::NOMINAL    => 'Nominal',
        };
    }
}
