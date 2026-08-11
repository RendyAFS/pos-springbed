<?php

namespace App\Enums;

enum PaymentMethodDpEnum: string
{
    case TRANSFER         = 'transfer';
    case QRIS             = 'qris';
    case CASH             = 'cash';
    case EDC              = 'edc';
    case TRANSFER_BCA     = 'transfer_bca';
    case TRANSFER_MANDIRI = 'transfer_mandiri';
    case SHOPEEPAYLATER   = 'shopeepaylater';

    public function getLabel(): string
    {
        return match ($this) {
            self::TRANSFER         => 'Transfer',
            self::QRIS             => 'QRIS',
            self::CASH             => 'Cash / Tunai',
            self::EDC              => 'EDC',
            self::TRANSFER_BCA     => 'Transfer BCA',
            self::TRANSFER_MANDIRI => 'Transfer Mandiri',
            self::SHOPEEPAYLATER   => 'Shopeepaylater',
        };
    }
}
