<?php

namespace App\Enums;

enum TransactionStatusEnum: string
{
    case PENDING   = 'pending';
    case PROCESSED = 'processed';
    case SHIPPED   = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING   => 'Pending',
            self::PROCESSED => 'Processed',
            self::SHIPPED   => 'Shipped',
            self::DELIVERED => 'Delivered',
            self::CANCELLED => 'Cancelled',
        };
    }
}
