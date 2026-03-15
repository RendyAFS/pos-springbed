<?php

namespace App\Enums;

enum TransactionPaymentMethodEnum: string
{
    case TRANSFER = 'transfer';
    case QRIS     = 'qris';
    case COD      = 'cod';

    public function getLabel(): string
    {
        return match ($this) {
            self::TRANSFER => 'Transfer',
            self::QRIS     => 'QRIS',
            self::COD      => 'COD',
        };
    }
}
