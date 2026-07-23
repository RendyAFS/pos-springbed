<?php

namespace App\Filament\Resources\Transactions\Schemas\Components;

use App\Filament\Resources\Transactions\Schemas\Concerns\HasTotalsCalculation;
use App\Models\Bundle;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\StoreSetting;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Auth;

class TransactionItemSchema
{
    use HasTotalsCalculation;

    public static function make(): Repeater
    {
        return Repeater::make('transactionItems')
            ->label('Item Transaksi')
            ->relationship('transactionItems')
            ->schema([
                Grid::make(3)->schema([
                    self::itemTypeField(),
                    self::productField(),
                    self::bundleField(),
                    self::multiStoreToggle(),
                    self::noteField(),
                    self::qtyField(),
                    SourceStoreSchema::make(),
                    self::sellingPriceField(),
                    self::discountField(),
                    self::subtotalField(),
                ]),
            ])
            ->addActionLabel('+ Tambah Item')
            ->reorderable()
            ->collapsible()
            ->cloneable()
            ->live()
            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotals($get, $set))
            ->deleteAction(
                fn($action) => $action->after(fn(Get $get, Set $set) => self::recalculateTotals($get, $set))
            );
    }

    protected static function itemTypeField(): Radio
    {
        return Radio::make('item_type')
            ->label('Tipe Item')
            ->options([
                'product' => 'Single Product',
                'bundle'  => 'Bundle',
            ])
            ->default('product')
            ->inline()
            ->live()
            ->afterStateHydrated(function (Radio $component, $state, Get $get): void {
                if (empty($state)) {
                    $bundleId = $get('bundle_id');
                    $component->state($bundleId ? 'bundle' : 'product');
                }
            })
            ->afterStateUpdated(function (Set $set): void {
                $set('product_id', null);
                $set('bundle_id', null);
                $set('selling_price', null);
                $set('qty', 1);
                $set('discount', 0);
                $set('subtotal', 0);
            })
            ->columnSpanFull();
    }

    protected static function productField(): Select
    {
        return Select::make('product_id')
            ->label('Produk')
            ->options(function (): array {
                return Product::query()
                    ->where('is_active', true)
                    ->pluck('name', 'id')
                    ->toArray();
            })
            ->searchable()
            ->preload()
            ->live()
            ->columnSpan(2)
            ->visible(fn(Get $get): bool => $get('item_type') !== 'bundle')
            ->required(fn(Get $get): bool => $get('item_type') !== 'bundle')
            ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                if ($state) {
                    $product = Product::find($state);
                    if ($product) {
                        $set('selling_price', number_format($product->selling_price, 0, ',', '.'));
                        self::recalculateItemSubtotal($get, $set);
                    }
                }
                $set('qty', 0);
            });
    }

    protected static function bundleField(): Select
    {
        return Select::make('bundle_id')
            ->label('Bundle')
            ->options(
                Bundle::where('is_active', true)
                    ->with('bundleItems.product')
                    ->get()
                    ->mapWithKeys(fn($b) => [$b->id => $b->name])
            )
            ->searchable()
            ->live()
            ->visible(fn(Get $get): bool => $get('item_type') === 'bundle')
            ->required(fn(Get $get): bool => $get('item_type') === 'bundle')
            ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                if ($state) {
                    $bundle = Bundle::with('bundleItems.product')->find($state);
                    if ($bundle) {
                        $set('selling_price', number_format($bundle->bundle_price, 0, ',', '.'));
                        $set('product_id', null);
                        $set('qty', 1);
                        self::recalculateItemSubtotal($get, $set);
                    }
                }
            })
            ->helperText(function (Get $get): string {
                $bundleId = $get('bundle_id');
                if (! $bundleId) {
                    return '';
                }

                $bundle = Bundle::with('bundleItems.product')->find($bundleId);
                if (! $bundle) {
                    return '';
                }

                $storeId = Auth::user()?->store_setting_id;

                $items = $bundle->bundleItems->map(function ($bi) use ($storeId) {
                    $stock      = self::getAvailableStock($bi->product_id, $storeId);
                    $stockLabel = $stock > 0 ? "stock: {$stock}" : '⚠️ stok habis';
                    return "{$bi->product->name} × {$bi->qty} ({$stockLabel})";
                })->implode(' | ');

                return "Detail Bundle: {$items}";
            });
    }

    protected static function multiStoreToggle(): Toggle
    {
        return Toggle::make('is_multi_store')
            ->label('Multi Store / Other Store Stock')
            ->inline(false)
            ->default(false)
            ->dehydrated(true)
            ->live()
            ->afterStateUpdated(function (Set $set, Get $get, $state): void {
                if (! $state) {
                    $set('source_stores', []);
                    $set('qty', 0);
                } else {
                    $set('qty', 0);
                }
                self::recalculateItemSubtotal($get, $set);
            });
    }

    protected static function noteField(): Textarea
    {
        return Textarea::make('note_product')
            ->label('Catatan Produk')
            ->placeholder('Contoh: Warna krem, ukuran custom, kirim sore, dll.')
            ->rows(3)
            ->columnSpanFull();
    }

    protected static function qtyField(): TextInput
    {
        return TextInput::make('qty')
            ->label('Qty')
            ->numeric()
            ->default(0)
            ->minValue(fn(Get $get): int => (bool) $get('is_multi_store') ? 0 : 1)
            ->columnSpan(2)
            ->required()
            ->dehydrated(true)
            ->dehydrateStateUsing(function ($state, Get $get): int {
                if ((bool) $get('is_multi_store')) {
                    $sources = $get('source_stores') ?? [];
                    $total   = collect($sources)->sum(fn($s) => (int) ($s['qty'] ?? 0));
                    return max(1, $total);
                }
                return max(1, (int) $state);
            })
            ->disabled(fn(Get $get): bool => (bool) $get('is_multi_store'))
            ->debounce(500)
            ->live(onBlur: true)
            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
            ->suffix('pcs')
            ->helperText(fn(Get $get, $component) => self::qtyHelperText($get, $component));
    }

    protected static function qtyHelperText(Get $get, $component): string
    {
        $storeId = Auth::user()?->store_setting_id
            ?? ($get('../../store_setting_id') ? (int) $get('../../store_setting_id') : null);

        $statePath  = $component->getStatePath();
        $parts      = explode('.', $statePath);
        $currentKey = $parts[count($parts) - 2] ?? null;

        $qty          = (int) ($get('qty') ?? 1);
        $isMulti      = (bool) $get('is_multi_store');
        $sourceStores = $get('source_stores') ?? [];

        // ─── MODE MULTI-STORE ────────────────────────────────────────────
        if ($isMulti) {
            if (empty($sourceStores)) {
                return '📦 Tambahkan alokasi store di bawah.';
            }

            $totalAllocated = collect($sourceStores)->sum(fn($s) => (int) ($s['qty'] ?? 0));
            return "✅ Total alokasi: {$totalAllocated} pcs dari " . count($sourceStores) . " toko.";
        }

        // ─── MODE SINGLE STORE ───────────────────────────────────────────
        if ($get('item_type') === 'product') {
            return self::singleStoreProductStockText($get, $productId = $get('product_id'), $storeId, $qty, $currentKey);
        }

        if ($get('item_type') === 'bundle') {
            return self::singleStoreBundleStockText($get('bundle_id'));
        }

        return '';
    }

    protected static function singleStoreProductStockText(Get $get, $productId, ?int $storeId, int $qty, ?string $currentKey): string
    {
        if (! $productId) {
            return 'Select a product first.';
        }

        $allStores  = StoreSetting::all();
        $stockLines = $allStores->map(function ($store) use ($productId) {
            $stock = InventoryStock::where('product_id', $productId)
                ->where('store_setting_id', $store->id)
                ->value('quantity') ?? 0;
            $icon = $stock > 0 ? '✅' : '⚠️';
            return "{$icon} {$store->store_name}: {$stock} pcs";
        })->implode(' | ');

        $effectiveInfo = '';
        if ($storeId) {
            $effectiveStock = self::getEffectiveStock((int) $productId, $storeId, $get, $currentKey);

            if ($effectiveStock <= 0) {
                $effectiveInfo = $effectiveStock < 0
                    ? " — ⚠️ stok kurang " . abs($effectiveStock) . " pcs (pre-order)"
                    : " — ⚠️ stok tidak cukup (pre-order)";
            } elseif ($qty > $effectiveStock) {
                $deficit       = $qty - $effectiveStock;
                $effectiveInfo = " — ⚠️ {$deficit} pcs akan menjadi pre-order";
            }
        }

        return "{$stockLines}{$effectiveInfo}";
    }

    protected static function singleStoreBundleStockText($bundleId): string
    {
        if (! $bundleId) {
            return 'Select a bundle first.';
        }

        $bundle = Bundle::with('bundleItems.product')->find($bundleId);
        if (! $bundle) {
            return '';
        }

        $allStores = StoreSetting::all();
        $lines     = [];

        foreach ($bundle->bundleItems as $bi) {
            $storeParts = $allStores->map(function ($store) use ($bi) {
                $stock = InventoryStock::where('product_id', $bi->product_id)
                    ->where('store_setting_id', $store->id)
                    ->value('quantity') ?? 0;
                $icon = $stock > 0 ? '✅' : '⚠️';
                return "{$icon} {$store->store_name}: {$stock} pcs";
            })->implode(' | ');

            $lines[] = "📦 {$bi->product->name} × {$bi->qty} → {$storeParts}";
        }

        return implode("\n", $lines);
    }

    protected static function sellingPriceField(): TextInput
    {
        return TextInput::make('selling_price')
            ->label('Harga Jual')
            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
            ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
            ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
            ->required()
            ->columnSpan(1)
            ->debounce(500)
            ->live(onBlur: true)
            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
            ->prefix('Rp');
    }

    protected static function discountField(): TextInput
    {
        return TextInput::make('discount')
            ->label('Discount (Rp)')
            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
            ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
            ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
            ->default(0)
            ->debounce(500)
            ->live(onBlur: true)
            ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
            ->prefix('Rp')
            ->columnSpan(1);
    }

    protected static function subtotalField(): TextInput
    {
        return TextInput::make('subtotal')
            ->label('Subtotal')
            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
            ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
            ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
            ->afterStateHydrated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
            ->readOnly()
            ->prefix('Rp')
            ->columnSpan(2);
    }
}
