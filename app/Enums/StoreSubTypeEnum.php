<?php

namespace App\Enums;

enum StoreSubTypeEnum: string
{
    case FLOOR = 'Floor';
    case RACK  = 'Rack';

    public function getLabel(): string
    {
        return match ($this) {
            self::FLOOR => 'Lantai (Floor)',
            self::RACK  => 'Rak (Rack)',
        };
    }
}
