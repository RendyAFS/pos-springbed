<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\InventoryStock;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
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
                            ->label('Store')
                            ->relationship('storeSetting', 'store_name')
                            ->searchable()
                            ->preload()
                            ->default(fn() => Auth::user()?->store_setting_id)
                            ->disabled(fn() => Auth::user()?->store_setting_id !== null)
                            ->dehydrated(fn() => true)
                            ->reactive()
                            ->debounce(500)
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

                                    $set("purchaseOrderItems.$key.qty_remaining", $stock ?? 0);
                                    $set("purchaseOrderItems.$key.selling_price", $sellingPrice ?? 0);
                                }
                            })
                            ->required(),
                        TextInput::make('supplier_name')
                            ->label('Supplier Name')
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
                            ->label('Purchase Date')
                            ->native(false)
                            ->suffixIcon(Heroicon::Calendar)
                            ->closeOnDateSelection()
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),
                Section::make('Summary')
                    ->icon(Heroicon::DocumentText)
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
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
                                    ->label('Product')
                                    ->relationship(
                                        name: 'product',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: function ($query) {
                                            $storeId = Auth::user()?->store_setting_id;

                                            if ($storeId) {
                                                $query->whereHas('inventoryStocks', function ($q) use ($storeId) {
                                                    $q->where('store_setting_id', $storeId);
                                                });
                                            }
                                        }
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->reactive()
                                    ->debounce(500)
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {

                                        $storeId = $get('../../store_setting_id');

                                        if (!$state || !$storeId) {
                                            $set('qty_remaining', 0);
                                            $set('selling_price', 0);
                                            return;
                                        }

                                        $stock = InventoryStock::query()
                                            ->where('product_id', $state)
                                            ->where('store_setting_id', $storeId)
                                            ->value('quantity');

                                        $sellingPrice = Product::query()
                                            ->where('id', $state)
                                            ->value('selling_price');

                                        $set('qty_remaining', $stock ?? 0);
                                        $set('selling_price', $sellingPrice ?? 0);
                                    })
                                    ->columnSpanFull(),
                                TextInput::make('qty_purchased')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->debounce(500)
                                    ->minValue(0)
                                    ->default(0)
                                    ->afterStateUpdated(function (Get $get, Set $set) {

                                        $items = $get('../../purchaseOrderItems') ?? [];

                                        $total = collect($items)->sum(function ($item) {
                                            $qty = (float) ($item['qty_purchased'] ?? 0);
                                            $price = (float) ($item['cost_price'] ?? 0);

                                            return $qty * $price;
                                        });

                                        $set('../../total_amount', $total);
                                    }),
                                TextInput::make('cost_price')
                                    ->label('Cost Price')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0)
                                    ->prefix('Rp')
                                    ->required()
                                    ->reactive()
                                    ->debounce(500)
                                    ->afterStateUpdated(function (Get $get, Set $set) {

                                        $items = $get('../../purchaseOrderItems') ?? [];

                                        $total = collect($items)->sum(function ($item) {
                                            $qty = (float) ($item['qty_purchased'] ?? 0);
                                            $price = (float) ($item['cost_price'] ?? 0);

                                            return $qty * $price;
                                        });

                                        $set('../../total_amount', $total);
                                    }),
                                TextInput::make('qty_remaining')
                                    ->label('Current Stock')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(true)
                                    ->default(0)
                                    ->helperText('Current stock in selected store')
                                    ->columnSpan(1),
                                TextInput::make('selling_price')
                                    ->label('Current Selling Price')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->default(0)
                                    ->helperText('Current selling price in selected store')
                                    ->afterStateHydrated(function (Get $get, Set $set) {
                                        $productId = $get('product_id');
                                        if (!$productId) {
                                            $set('selling_price', 0);
                                            return;
                                        }
                                        $price = Product::query()
                                            ->where('id', $productId)
                                            ->value('selling_price');
                                        $set('selling_price', $price ?? 0);
                                    })
                                    ->columnSpan(1)
                            ])->columns(2),
                    ])
                    ->addActionLabel('Add Item')
                    ->reorderable()
                    ->reorderableWithDragAndDrop()
                    ->collapsible()
                    ->grid(2)
                    ->columnSpanFull()
            ])
            ->columns(2);
    }
}
