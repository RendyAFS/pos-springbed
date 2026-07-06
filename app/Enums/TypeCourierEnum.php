<?php

namespace App\Enums;

enum TypeCourierEnum: string
{
    case INTERNAL        = 'internal';
    case EXTERNAL        = 'external';
    case KURIR_TOKO      = 'kurir_toko';
    case KURIR_LUAR_KOTA = 'kurir_luar_kota';
    case EKSPEDISI       = 'ekspedisi';
    case AMBIL_DITEMPAT  = 'ambil_ditempat';

    public function getLabel(): string
    {
        return match ($this) {
            self::INTERNAL        => 'Internal',
            self::EXTERNAL        => 'External',
            self::KURIR_TOKO      => 'Kurir Toko',
            self::KURIR_LUAR_KOTA => 'Kurir Luar Kota',
            self::EKSPEDISI       => 'Ekspedisi',
            self::AMBIL_DITEMPAT  => 'Ambil Ditempat',
        };
    }
}
