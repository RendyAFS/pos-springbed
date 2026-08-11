<?php

namespace App\Filament\Resources\Transactions\Schemas\Concerns;

trait HasCurrencyHelpers
{
    protected static function parseCurrency(mixed $value): float
    {
        if (blank($value)) {
            return 0;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $stringValue = (string) $value;

        if (preg_match('/^\d+\.\d{1,2}$/', $stringValue)) {
            return (float) $stringValue;
        }

        $value = preg_replace('/[^0-9]/', '', $stringValue);

        return (float) $value;
    }
}
