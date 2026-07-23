<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\InventoryStock;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase Order Information')
                    ->icon(Heroicon::ShoppingCart)
                    ->schema([
                        Select::make('store_setting_id')
                            ->label('Toko')
                            ->relationship('storeSetting', 'store_name')
                            ->searchable()
                            ->preload()
                            ->default(fn() => Auth::user()?->store_setting_id)
                            ->disabled(fn() => Auth::user()?->store_setting_id !== null)
                            ->dehydrated(fn() => true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                $storeId = $get('store_setting_id');
                                $items = $get('purchaseOrderItems') ?? [];
                                foreach ($items as $key => $item) {
                                    if (!isset($item['product_id'])) {
                                        $set("purchaseOrderItems.$key.qty_remaining", 0);
                                        continue;
                                    }
                                    $stock = InventoryStock::query()
                                        ->where('product_id', $item['product_id'])
                                        ->where('store_setting_id', $storeId)
                                        ->value('quantity');

                                    $sellingPrice = Product::query()
                                        ->where('id', $item['product_id'])
                                        ->value('selling_price');

                                    $set("purchaseOrderItems.$key.qty_remaining", number_format($stock ?? 0, 0, ',', '.'));
                                    $set("purchaseOrderItems.$key.selling_price", number_format($sellingPrice ?? 0, 0, ',', '.'));
                                }
                            })
                            ->required(),
                        TextInput::make('supplier_name')
                            ->label('Nama Supplier')
                            ->required(),
                        TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->required()
                            ->default(function () {
                                $random = strtoupper(Str::random(8));
                                $timestamp = now()->timestamp;
                                return "INV{$random}{$timestamp}";
                            })
                            ->disabled()
                            ->dehydrated(true),
                        DatePicker::make('purchase_date')
                            ->label('Tanggal Pemesanan')
                            ->native(false)
                            ->suffixIcon(Heroicon::Calendar)
                            ->closeOnDateSelection()
                            ->required()
                            ->default(now()),
                        TextInput::make('delivery_order_number')
                            ->label('Nomor Surat Jalan')
                            ->required(),
                        TextInput::make('taxpayer_name')
                            ->label('Nama Wajib Pajak')
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Summary')
                    ->icon(Heroicon::DocumentText)
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                            ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                            ->prefix('Rp')
                            ->readOnly()
                            ->default(0),
                    ])
                    ->columns(1),
                Repeater::make('purchaseOrderItems')
                    ->relationship()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Produk')
                                    ->relationship(
                                        name: 'product',
                                        titleAttribute: 'name',
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {

                                        if (!$state) {
                                            $set('qty_remaining', 0);
                                            $set('selling_price', 0);
                                            $set('cost_price', number_format(0, 0, ',', '.'));
                                            return;
                                        }

                                        $product = Product::query()->find($state);
                                        $storeId = $get('../../store_setting_id');

                                        $stock = $storeId
                                            ? InventoryStock::query()
                                            ->where('product_id', $state)
                                            ->where('store_setting_id', $storeId)
                                            ->value('quantity')
                                            : null;

                                        $set('qty_remaining', number_format($stock ?? 0, 0, ',', '.'));
                                        $set('selling_price', number_format($product->selling_price ?? 0, 0, ',', '.'));
                                        $set('cost_price', number_format($product->cost_price ?? 0, 0, ',', '.'));

                                        $items = $get('../../purchaseOrderItems') ?? [];
                                        $total = collect($items)->sum(function ($item) {
                                            $qty   = (float) ($item['qty_purchased'] ?? 0);
                                            $price = (float) str_replace('.', '', $item['cost_price'] ?? 0);
                                            return $qty * $price;
                                        });
                                        $set('../../total_amount', number_format($total, 0, ',', '.'));
                                    })
                                    ->columnSpanFull(),
                                TextInput::make('qty_purchased')
                                    ->label('Kuantitas')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->minValue(0)
                                    ->default(0)
                                    ->afterStateUpdated(function (Get $get, Set $set) {

                                        $items = $get('../../purchaseOrderItems') ?? [];

                                        $total = collect($items)->sum(function ($item) {
                                            $qty = (float) ($item['qty_purchased'] ?? 0);

                                            $price = (float) str_replace(
                                                '.',
                                                '',
                                                $item['cost_price'] ?? 0
                                            );

                                            return $qty * $price;
                                        });

                                        $set('../../total_amount', number_format($total, 0, ',', '.'));
                                    }),
                                TextInput::make('cost_price')
                                    ->label('Harga Beli')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                                    ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                                    ->minValue(0)
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Get $get, Set $set) {

                                        $items = $get('../../purchaseOrderItems') ?? [];

                                        $total = collect($items)->sum(function ($item) {
                                            $qty = (float) ($item['qty_purchased'] ?? 0);

                                            $price = (float) str_replace(
                                                '.',
                                                '',
                                                $item['cost_price'] ?? 0
                                            );

                                            return $qty * $price;
                                        });

                                        $set('../../total_amount', number_format($total, 0, ',', '.'));
                                    }),
                                TextInput::make('qty_remaining')
                                    ->label('Stok saat ini')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->dehydrateStateUsing(fn($state) => (float) str_replace('.', '', $state ?? 0))
                                    ->formatStateUsing(fn($state) => number_format((float) ($state ?? 0), 0, ',', '.'))
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->nullable()
                                    ->helperText('Current stock in selected store')
                                    ->columnSpan(1),
                                TextInput::make('selling_price')
                                    ->label('Harga Jual saat ini')
                                    ->prefix('Rp')
                                    ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                    ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                                    ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->default(0)
                                    ->helperText('Current selling price in selected store')
                                    ->afterStateHydrated(function (Get $get, Set $set, $state) {
                                        // Jika state sudah ada (dari DB), format langsung dari $state
                                        if ($state) {
                                            $set('selling_price', number_format((float) $state, 0, ',', '.'));
                                            return;
                                        }

                                        $productId = $get('product_id');
                                        if (!$productId) {
                                            $set('selling_price', '0');
                                            return;
                                        }

                                        $price = Product::query()
                                            ->where('id', $productId)
                                            ->value('selling_price');

                                        $set('selling_price', number_format((float) ($price ?? 0), 0, ',', '.'));
                                    })
                                    ->columnSpan(1),
                                DateTimePicker::make('date_product_order')
                                    ->label('Date Product Order')
                                    ->native(false)
                                    ->suffixIcon(Heroicon::Calendar)
                                    ->closeOnDateSelection()
                                    ->required()
                                    ->default(now())
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])
                    ->addActionLabel('Tambah Item')
                    ->reorderable()
                    ->reorderableWithDragAndDrop()
                    ->collapsible()
                    ->grid(2)
                    ->columnSpanFull()
            ])
            ->columns(2);
    }
}
