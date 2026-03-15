<?php

namespace App\Enums;

enum StatusTransactionShipmentEnum: string
{
    case PENDING   = 'pending';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING   => 'Pending',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
        };
    }
}
