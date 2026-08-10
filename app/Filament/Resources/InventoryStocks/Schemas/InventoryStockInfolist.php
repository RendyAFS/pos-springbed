<?php

namespace App\Filament\Resources\InventoryStocks\Schemas;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\InventoryStock;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Actions;
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

                        Grid::make(2)
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

                            ]),

                        Grid::make(3)
                            ->schema([

                                TextEntry::make('total_quantity')
                                    ->label('Total Stok (Semua Toko)')
                                    ->icon(Heroicon::Cube)
                                    ->state(
                                        fn($record) =>
                                        InventoryStock::where('product_id', $record->product_id)->sum('quantity')
                                    )
                                    ->formatStateUsing(fn($state) => "{$state} pcs")
                                    ->color(fn($state) => $state <= 10 ? 'warning' : 'success'),

                                TextEntry::make('product.selling_price')
                                    ->label('Harga Jual')
                                    ->icon(Heroicon::Banknotes)
                                    ->money('IDR', locale: 'id'),

                                TextEntry::make('total_value')
                                    ->label('Nilai Total Inventory')
                                    ->state(function ($record) {
                                        $totalQty = InventoryStock::where('product_id', $record->product_id)->sum('quantity');

                                        return $totalQty * ($record->product->selling_price ?? 0);
                                    })
                                    ->icon(Heroicon::ChartBar)
                                    ->money('IDR', locale: 'id'),

                            ]),
                    ]),

                Section::make('Stok per Toko')
                    ->icon(Heroicon::BuildingStorefront)
                    ->schema([

                        RepeatableEntry::make('storeStocks')
                            ->label('')
                            ->state(
                                fn($record) =>
                                InventoryStock::with('storeSetting')
                                    ->where('product_id', $record->product_id)
                                    ->get()
                            )
                            ->schema([

                                Grid::make(4)
                                    ->schema([

                                        TextEntry::make('storeSetting.store_name')
                                            ->label('Toko')
                                            ->badge()
                                            ->color('gray'),

                                        TextEntry::make('quantity')
                                            ->label('Stok')
                                            ->formatStateUsing(fn($state) => "{$state} pcs")
                                            ->color(fn($state) => $state <= 10 ? 'warning' : 'success'),

                                        TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->state(fn($record) => $record->quantity <= 10 ? 'Low Stock' : 'In Stock')
                                            ->colors([
                                                'success' => 'In Stock',
                                                'warning' => 'Low Stock',
                                            ]),

                                        Actions::make([
                                            Action::make('createPurchaseOrder')
                                                ->label('Buat PO')
                                                ->icon(Heroicon::ShoppingCart)
                                                ->color('gray')
                                                ->outlined()
                                                ->url(fn($record) => PurchaseOrderResource::getUrl('create', [
                                                    'product_id' => $record->product_id,
                                                    'store_setting_id' => $record->store_setting_id,
                                                ]))
                                                ->openUrlInNewTab(),
                                        ])
                                            ->alignEnd()
                                            ->extraAttributes(['class' => 'flex items-center h-full']),

                                    ]),
                            ]),

                    ]),

            ]);
    }
}
