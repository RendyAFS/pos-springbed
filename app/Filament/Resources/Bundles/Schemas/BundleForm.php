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
use Filament\Support\RawJs;

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
                            ->prefix('Rp.')
                            ->default(0)
                            ->readOnly()
                            ->dehydrateStateUsing(fn($state) => (float) str_replace('.', '', $state ?? 0))
                            ->formatStateUsing(fn($state) => number_format((float) ($state ?? 0), 0, ',', '.'))
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
                                    ->afterStateHydrated(function ($state, Get $get, Set $set) {
                                        if ($get('price')) {
                                            return;
                                        }

                                        if ($state) {
                                            $price = Product::find($state)?->selling_price ?? 0;
                                            $set('price', number_format((float) $price, 0, ',', '.'));
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = Product::find($state)?->selling_price ?? 0;
                                        $set('price', number_format((float) $price, 0, ',', '.'));
                                        self::updateBundlePrice($get, $set);
                                    })
                                    ->columnSpanFull(),
                                TextInput::make('price')
                                    ->label('Price')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->dehydrateStateUsing(fn($state) => (float) str_replace('.', '', $state ?? 0))
                                    ->formatStateUsing(fn($state) => number_format((float) ($state ?? 0), 0, ',', '.'))
                                    ->prefix('Rp.')
                                    ->live()
                                    ->debounce(500)
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        self::updateBundlePrice($get, $set);
                                    })
                                    ->columns(1),
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
                                    })
                                    ->columns(1),
                            ])
                            ->columns(2)
                            ->required()
                            ->defaultItems(1)
                            ->addActionLabel('Add Product')
                            ->collapsible()
                            ->reorderable()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateBundlePrice($get, $set);
                            }),
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

                $price = (float) str_replace(
                    '.',
                    '',
                    $item['price'] ?? 0
                );

                $qty = (int) ($item['qty'] ?? 0);

                return $price * $qty;
            });

        $set(
            '../../bundle_price',
            number_format($total, 0, ',', '.')
        );
    }
}
