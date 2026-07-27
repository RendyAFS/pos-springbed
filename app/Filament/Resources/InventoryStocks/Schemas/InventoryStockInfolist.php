<?php

namespace App\Filament\Resources\InventoryStocks\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class InventoryStockInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Produk')
                    ->icon(Heroicon::ShoppingBag)
                    ->schema([
                        TextEntry::make('storeSetting.store_name')
                            ->label('Toko')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('product.name')
                            ->label('Produk')
                            ->html()
                            ->formatStateUsing(function ($state, $record) {

                                $type  = $record->product?->type?->name ?? '';
                                $brand = $record->product?->brand?->name ?? '';

                                return new HtmlString("
                                    <div class='flex flex-col'>
                                        <span class='text-lg font-semibold'>{$state}</span>
                                        <span class='text-sm text-gray-500'>{$type}</span>
                                        <span class='text-sm text-gray-400'>{$brand}</span>
                                    </div>
                                ");
                            }),

                        Grid::make(3)
                            ->schema([

                                TextEntry::make('product.sku')
                                    ->label('SKU')
                                    ->fontFamily(FontFamily::Mono)
                                    ->icon(Heroicon::ClipboardDocumentList)
                                    ->copyable()
                                    ->copyMessage('SKU copied'),

                                TextEntry::make('product.category.name')
                                    ->label('Kategori')
                                    ->badge()
                                    ->color('gray')
                                    ->icon(Heroicon::Tag),

                                TextEntry::make('status')
                                    ->label('Status Stok')
                                    ->badge()
                                    ->state(fn($record) => $record->quantity <= 10 ? 'Low Stock' : 'In Stock')
                                    ->colors([
                                        'success' => 'In Stock',
                                        'warning' => 'Low Stock',
                                    ])
                                    ->icon(
                                        fn($record) =>
                                        $record->quantity <= 10
                                            ? Heroicon::ExclamationTriangle
                                            : Heroicon::CheckCircle
                                    ),

                            ]),
                    ]),

                Section::make('Inventory Summary')
                    ->icon(Heroicon::ArchiveBox)
                    ->schema([

                        Grid::make(3)
                            ->schema([

                                TextEntry::make('quantity')
                                    ->label('Kuantitas Stok')
                                    ->icon(Heroicon::Cube)
                                    ->formatStateUsing(fn($state) => "{$state} pcs")
                                    ->color(fn($state) => $state <= 10 ? 'warning' : 'success'),

                                TextEntry::make('product.selling_price')
                                    ->label('Harga Jual')
                                    ->icon(Heroicon::Banknotes)
                                    ->money('IDR', locale: 'id'),

                                TextEntry::make('total_value')
                                    ->label('Nilai Total Inventory')
                                    ->state(
                                        fn($record) =>
                                        $record->quantity * ($record->product->selling_price ?? 0)
                                    )
                                    ->icon(Heroicon::ChartBar)
                                    ->money('IDR', locale: 'id'),

                            ]),

                    ]),

            ]);
    }
}
