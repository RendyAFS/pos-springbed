<?php

namespace App\Enums;

enum PromoTypeEnum: string
{
    case FLASH_SALE    = 'flash_sale';
    case VOUCHER       = 'voucher';
    case FREE_SHIPPING = 'free_shipping';

    public function getLabel(): string
    {
        return match ($this) {
            self::FLASH_SALE    => 'Flash Sale',
            self::VOUCHER       => 'Voucher',
            self::FREE_SHIPPING => 'Free Shipping',
        };
    }
}
