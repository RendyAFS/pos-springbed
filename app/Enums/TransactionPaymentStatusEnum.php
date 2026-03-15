<?php

namespace App\Enums;

enum TransactionPaymentStatusEnum: string
{
    case PENDING = 'pending';
    case PAID    = 'paid';
    case FAILED  = 'failed';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID    => 'Paid',
            self::FAILED  => 'Failed',
        };
    }
}
