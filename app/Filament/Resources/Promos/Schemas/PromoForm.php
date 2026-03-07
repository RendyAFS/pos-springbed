<?php

namespace App\Filament\Resources\Promos\Schemas;

use App\Enums\PromoDiscountEnum;
use App\Enums\PromoTypeEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->default(null),
                Select::make('type')
                    ->options(PromoTypeEnum::class)
                    ->default(null),
                Select::make('discount_type')
                    ->options(PromoDiscountEnum::class)
                    ->default(null),
                TextInput::make('discount_value')
                    ->numeric()
                    ->default(null),
                TextInput::make('min_purchase')
                    ->numeric()
                    ->default(null),
                TextInput::make('usage_limit')
                    ->numeric()
                    ->default(null),
                TextInput::make('usage_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('start_date'),
                DateTimePicker::make('end_date'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
