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
                    ->label('Name')
                    ->required(),
                TextInput::make('bundle_price')
                    ->label('Bundle Price')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp.'),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->required(),
                Repeater::make('bundleItems')
                    ->relationship()
                    ->label('Bundle Items')
                    ->schema([
                        Select::make('product_id')
                            ->label('Product')
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('qty')
                            ->label('Quantity')
                            ->required()
                            ->default(0),
                    ])
                    ->required()
                    ->defaultItems(1),
            ]);
    }
}
