<?php

namespace App\Filament\Resources\Bundles\Schemas;

use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BundleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bundle Information')
                    ->icon(Heroicon::ArchiveBox)
                    ->schema([
                        TextInput::make('name')
                            ->label('Bundle Name')
                            ->required(),
                        TextInput::make('bundle_price')
                            ->label('Bundle Price')
                            ->numeric()
                            ->prefix('Rp.')
                            ->default(0)
                            ->dehydrated(),
                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(1),

                Section::make('Bundle Items')
                    ->icon(Heroicon::Squares2x2)
                    ->schema([
                        Repeater::make('bundleItems')
                            ->relationship()
                            ->label('Items')
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateHydrated(function ($state, Set $set) {
                                        if ($state) {
                                            $price = Product::find($state)?->selling_price ?? 0;
                                            $set('price', $price);
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = Product::find($state)?->selling_price ?? 0;
                                        $set('price', $price);
                                        self::updateBundlePrice($get, $set);
                                    }),
                                TextInput::make('price')
                                    ->label('Price')
                                    ->numeric()
                                    ->prefix('Rp.')
                                    ->readOnly()
                                    ->dehydrated(false),
                                TextInput::make('qty')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->live()
                                    ->debounce(500)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateBundlePrice($get, $set);
                                    }),
                            ])
                            ->columns(3)
                            ->required()
                            ->defaultItems(1)
                            ->addActionLabel('Add Product')
                            ->collapsible()
                            ->reorderable()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateBundlePrice($get, $set);
                            })
                            ->columnSpanFull(),
                    ])
                    ->columnSpan(2)
            ])
            ->columns(3);
    }

    protected static function updateBundlePrice(Get $get, Set $set): void
    {
        $items = $get('../../bundleItems') ?? [];

        $total = collect($items)
            ->filter(fn($item) => is_array($item))
            ->sum(function ($item) {

                $price = isset($item['price']) && is_numeric($item['price'])
                    ? (float) $item['price']
                    : 0;

                $qty = isset($item['qty']) && is_numeric($item['qty'])
                    ? (int) $item['qty']
                    : 0;

                return $price * $qty;
            });

        $set('../../bundle_price', $total);
    }
}
