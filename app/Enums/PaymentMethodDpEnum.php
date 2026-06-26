<?php

namespace App\Enums;

enum PaymentMethodDpEnum: string
{
    case TRANSFER = 'transfer';
    case QRIS     = 'qris';
    case CASH     = 'cash';

    public function getLabel(): string
    {
        return match ($this) {
            self::TRANSFER => 'Transfer',
            self::QRIS     => 'QRIS',
            self::CASH     => 'Cash',
        };
    }
}
