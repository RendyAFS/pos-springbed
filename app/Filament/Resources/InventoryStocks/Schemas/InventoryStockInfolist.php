<?php

namespace App\Filament\Resources\InventoryStocks\Schemas;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\InventoryStock;
use App\Models\StoreSub;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\Width;
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
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('product.name')
                            ->label('Produk')
                            ->html()
                            ->formatStateUsing(function ($state, $record) {
                                $type  = $record->product?->type?->name ?? '';
                                $brand = $record->product?->brand?->name ?? '';

                                return new HtmlString("
                                    <div class='flex flex-col gap-0.5'>
                                        <span class='text-xl font-bold text-gray-900 dark:text-white'>{$state}</span>
                                        <div class='flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400'>
                                            " . ($type ? "<span class='font-medium text-primary-600 dark:text-primary-400'>{$type}</span>" : "") . "
                                            " . ($type && $brand ? "<span>•</span>" : "") . "
                                            " . ($brand ? "<span>{$brand}</span>" : "") . "
                                        </div>
                                    </div>
                                ");
                            }),

                        Grid::make(5)
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
                    ->columnSpanFull()
                    ->schema([

                        RepeatableEntry::make('storeStocks')
                            ->label('')
                            ->state(
                                fn($record) =>
                                InventoryStock::with(['storeSetting', 'storeSub'])
                                    ->where('product_id', $record->product_id)
                                    ->get()
                            )
                            ->schema([

                                Grid::make(5)
                                    ->schema([

                                        TextEntry::make('storeSetting.store_name')
                                            ->label('Toko')
                                            ->badge()
                                            ->color('gray'),

                                        TextEntry::make('storeSub.name')
                                            ->label('Lokasi (Floor / Rack)')
                                            ->icon(Heroicon::MapPin)
                                            ->badge()
                                            ->color('info')
                                            ->default('Lantai 1')
                                            ->formatStateUsing(function ($state, $record) {
                                                $name = $record->storeSub?->name ?? 'Lantai 1';
                                                $type = $record->storeSub?->type?->value ?? ($record->storeSub?->type ?? 'Floor');
                                                $code = $record->storeSub?->code ? " ({$record->storeSub->code})" : '';
                                                return "{$name}{$code}";
                                            }),

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
                                            Action::make('editLocation')
                                                ->label('Edit Lokasi')
                                                ->icon(Heroicon::PencilSquare)
                                                ->color('warning')
                                                ->outlined()
                                                ->modalHeading(fn($record) => "Edit Lokasi Penyimpanan - {$record->storeSetting?->store_name}")
                                                ->modalWidth(Width::Large)
                                                ->fillForm(fn($record) => [
                                                    'store_sub_id' => $record->store_sub_id,
                                                ])
                                                ->schema([
                                                    Select::make('store_sub_id')
                                                        ->label('Lokasi Penyimpanan (Floor / Rack)')
                                                        ->options(function ($record) {
                                                            return StoreSub::where('store_id', $record->store_setting_id)
                                                                ->get()
                                                                ->mapWithKeys(function ($sub) {
                                                                    $typeLabel = $sub->type instanceof \BackedEnum ? $sub->type->value : $sub->type;
                                                                    return [$sub->id => "{$sub->name} ({$typeLabel}) - Kode: {$sub->code}"];
                                                                });
                                                        })
                                                        ->searchable()
                                                        ->preload()
                                                        ->required(),
                                                ])
                                                ->action(function ($record, array $data) {
                                                    $record->update([
                                                        'store_sub_id' => $data['store_sub_id'],
                                                    ]);

                                                    Notification::make()
                                                        ->title('Lokasi penyimpanan berhasil diperbarui')
                                                        ->success()
                                                        ->send();
                                                }),

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
                                            ->extraAttributes(['class' => 'flex items-center gap-2 h-full']),

                                    ]),
                            ]),

                    ]),

            ]);
    }
}
