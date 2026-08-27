<?php

namespace App\Filament\Resources\InventoryStocks\Schemas;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\InventoryStock;
use App\Models\StoreSetting;
use App\Models\StoreSub;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                                                ->icon(Heroicon::ArrowsRightLeft)
                                                ->color('warning')
                                                ->outlined()
                                                ->modalHeading(fn($record) => "Pindah Lokasi / Toko Stock - {$record->product?->name}")
                                                ->modalWidth(Width::Large)
                                                ->fillForm(fn($record) => [
                                                    'store_setting_id' => $record->store_setting_id,
                                                    'store_sub_id'     => $record->store_sub_id,
                                                ])
                                                ->schema([
                                                    Select::make('store_setting_id')
                                                        ->label('Toko')
                                                        ->options(fn() => StoreSetting::pluck('store_name', 'id'))
                                                        ->searchable()
                                                        ->preload()
                                                        ->required()
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Set $set) => $set('store_sub_id', null)),

                                                    Select::make('store_sub_id')
                                                        ->label('Sub Lokasi Toko (Floor / Rack)')
                                                        ->options(function (Get $get, $record) {
                                                            $storeId = $get('store_setting_id') ?? $record->store_setting_id;
                                                            if (!$storeId) {
                                                                return [];
                                                            }
                                                            return StoreSub::where('store_id', $storeId)
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
                                                    $targetStoreId = (int) $data['store_setting_id'];
                                                    $targetSubId   = (int) $data['store_sub_id'];

                                                    if ($targetStoreId !== (int) $record->store_setting_id) {
                                                        $existingStock = InventoryStock::where('product_id', $record->product_id)
                                                            ->where('store_setting_id', $targetStoreId)
                                                            ->where('id', '!=', $record->id)
                                                            ->first();

                                                        if ($existingStock) {
                                                            $existingStock->quantity += $record->quantity;
                                                            $existingStock->store_sub_id = $targetSubId;
                                                            $existingStock->save();
                                                            $record->delete();
                                                        } else {
                                                            $record->update([
                                                                'store_setting_id' => $targetStoreId,
                                                                'store_sub_id'     => $targetSubId,
                                                            ]);
                                                        }
                                                    } else {
                                                        $record->update([
                                                            'store_sub_id' => $targetSubId,
                                                        ]);
                                                    }

                                                    Notification::make()
                                                        ->title('Lokasi & Toko stock berhasil diperbarui')
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
