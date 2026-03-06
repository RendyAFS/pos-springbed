<?php

namespace App\Filament\Resources\Bundles\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BundleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('bundle_price')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp.'),
                Toggle::make('is_active')
                    ->required(),
                Repeater::make('bundleItems')
                    ->relationship()
                    ->label('Bundle Items')
                    ->schema([
                        Select::make('product_id')
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('qty')
                            ->numeric()
                            ->required()
                            ->default(0),
                    ])
                    ->required()
                    ->defaultItems(1),
            ]);
    }
}
