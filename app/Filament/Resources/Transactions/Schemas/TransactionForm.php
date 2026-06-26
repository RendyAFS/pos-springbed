<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\PromoDiscountEnum;
use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Helpers\RupiahHelper;
use App\Models\Bundle;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Referal;
use App\Models\StoreSetting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([

                    Step::make('Customer')
                        ->label('Customer')
                        ->description('Silahkan pilih customer dan tanggal transaksi.')
                        ->icon(Heroicon::User)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Section::make('Info Transaksi')
                                ->description('Masukkan data transaksi dan customer.')
                                ->icon(Heroicon::DocumentText)
                                ->columns(2)
                                ->schema([
                                    TextInput::make('transaction_code')
                                        ->label('Kode Transaksi')
                                        ->default(fn(): string => 'TRX' . strtoupper(Str::random(8)) . now()->format('Ymd'))
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->columnSpan(1),
                                    DatePicker::make('transaction_date')
                                        ->label('Tanggal Transaksi')
                                        ->required()
                                        ->default(now())
                                        ->native(false)
                                        ->closeOnDateSelection()
                                        ->columnSpan(1),
                                    Select::make('customer_id')
                                        ->label('Customer')
                                        ->required()
                                        ->relationship('customer', 'name')
                                        ->searchable(['name', 'phone', 'email'])
                                        ->preload()
                                        ->live()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->label('Full Name')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('phone')
                                                ->label('Telepone')
                                                ->tel()
                                                ->maxLength(20),
                                            TextInput::make('email')
                                                ->label('Email')
                                                ->email()
                                                ->maxLength(255),
                                            Textarea::make('address')
                                                ->label('Alamat')
                                                ->rows(3),
                                        ])
                                        ->createOptionUsing(function (array $data): int {
                                            return Customer::create($data)->getKey();
                                        })
                                        ->editOptionForm([
                                            TextInput::make('name')
                                                ->label('Full Name')
                                                ->required(),
                                            TextInput::make('phone')
                                                ->label('Telepone'),
                                            TextInput::make('email')
                                                ->label('Email')
                                                ->email(),
                                            Textarea::make('address')
                                                ->label('Alamat'),
                                        ])
                                        ->updateOptionUsing(function (array $data, $record) {
                                            $record->update($data);
                                        })
                                        ->placeholder('Search by name, phone, or email...')
                                        ->columnSpanFull(),

                                    Select::make('status')
                                        ->label('Status Transaksi')
                                        ->options(
                                            collect(TransactionStatusEnum::cases())
                                                ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                                ->toArray()
                                        )
                                        ->default(TransactionStatusEnum::PENDING->value)
                                        ->required()
                                        ->native(false)
                                        ->columnSpan(1),
                                    Select::make('store_setting_id')
                                        ->label('Toko')
                                        ->options(function () {
                                            $user = Auth::user();

                                            $allowedStores = $user?->selected_store ?? [];

                                            return StoreSetting::query()
                                                ->whereIn('id', $allowedStores)
                                                ->pluck('store_name', 'id')
                                                ->toArray();
                                        })
                                        ->searchable()
                                        ->required()
                                        ->native(false)
                                        ->live()
                                        ->dehydrated(true)
                                        ->visible(function (): bool {
                                            $user = Auth::user();

                                            return $user
                                                && ! is_null($user->store_setting_id)
                                                && is_array($user->selected_store)
                                                && count($user->selected_store) > 1;
                                        })
                                        ->columnSpan(1),
                                ]),
                        ]),

                    Step::make('Products')
                        ->label('Produk')
                        ->description('Pilih produk atau paket yang akan dibeli.')
                        ->icon(Heroicon::ShoppingCart)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Section::make('Produk')
                                ->description('Pilih produk atau paket yang akan dibeli.')
                                ->icon(Heroicon::CubeTransparent)
                                ->schema([
                                    Repeater::make('transactionItems')
                                        ->label('Item Transaksi')
                                        ->relationship('transactionItems')
                                        ->schema([
                                            Grid::make(3)
                                                ->schema([
                                                    Radio::make('item_type')
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
                                                        ->columnSpanFull(),

                                                    Select::make('product_id')
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
                                                        }),

                                                    Select::make('bundle_id')
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
                                                            if (! $bundleId) return '';

                                                            $bundle = Bundle::with('bundleItems.product')->find($bundleId);
                                                            if (! $bundle) return '';

                                                            $storeId = Auth::user()?->store_setting_id;

                                                            $items = $bundle->bundleItems->map(function ($bi) use ($storeId) {
                                                                $stock      = self::getAvailableStock($bi->product_id, $storeId);
                                                                $stockLabel = $stock > 0 ? "stock: {$stock}" : '⚠️ stok habis';
                                                                return "{$bi->product->name} × {$bi->qty} ({$stockLabel})";
                                                            })->implode(' | ');

                                                            return "Detail Bundle: {$items}";
                                                        }),

                                                    Toggle::make('is_multi_store')
                                                        ->label('Multi Store (Parsial)')
                                                        ->inline(false)
                                                        ->default(false)
                                                        ->dehydrated(true)
                                                        ->live()
                                                        ->afterStateUpdated(function (Set $set, Get $get, $state): void {
                                                            if (!$state) {
                                                                $set('source_stores', []);
                                                                $set('qty', 0);
                                                            } else {
                                                                $set('qty', 0);
                                                            }
                                                            self::recalculateItemSubtotal($get, $set);
                                                        }),

                                                    TextInput::make('qty')
                                                        ->label('Qty')
                                                        ->numeric()
                                                        ->default(0)
                                                        ->minValue(fn(Get $get): int => (bool)$get('is_multi_store') ? 0 : 1)
                                                        ->columnSpan(2)
                                                        ->required()
                                                        ->dehydrated(true)
                                                        ->dehydrateStateUsing(function ($state, Get $get): int {
                                                            if ((bool) $get('is_multi_store')) {
                                                                $sources = $get('source_stores') ?? [];
                                                                $total   = collect($sources)->sum(fn($s) => (int)($s['qty'] ?? 0));
                                                                return max(1, $total);
                                                            }
                                                            return max(1, (int) $state);
                                                        })
                                                        ->disabled(fn(Get $get): bool => (bool)$get('is_multi_store'))
                                                        ->debounce(500)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->suffix('pcs')
                                                        ->helperText(function (Get $get, $component): string {
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

                                                                $totalAllocated = collect($sourceStores)->sum(fn($s) => (int)($s['qty'] ?? 0));
                                                                return "✅ Total alokasi: {$totalAllocated} pcs dari " . count($sourceStores) . " toko.";
                                                            }

                                                            // ─── MODE SINGLE STORE ───────────────────────────────────────────
                                                            if ($get('item_type') === 'product') {
                                                                $productId = $get('product_id');
                                                                if (!$productId) return 'Select a product first.';

                                                                // Tampilkan stok di semua toko
                                                                $allStores = StoreSetting::all();
                                                                $stockLines = $allStores->map(function ($store) use ($productId) {
                                                                    $stock = InventoryStock::where('product_id', $productId)
                                                                        ->where('store_setting_id', $store->id)
                                                                        ->value('quantity') ?? 0;
                                                                    $icon = $stock > 0 ? '✅' : '⚠️';
                                                                    return "{$icon} {$store->store_name}: {$stock} pcs";
                                                                })->implode(' | ');

                                                                // Effective stock untuk store aktif (jika ada)
                                                                $effectiveInfo = '';
                                                                if ($storeId) {
                                                                    $effectiveStock = self::getEffectiveStock((int) $productId, $storeId, $get, $currentKey);

                                                                    if ($effectiveStock <= 0) {
                                                                        $effectiveInfo = $effectiveStock < 0
                                                                            ? " — ⚠️ stok kurang " . abs($effectiveStock) . " pcs (pre-order)"
                                                                            : " — ⚠️ stok tidak cukup (pre-order)";
                                                                    } elseif ($qty > $effectiveStock) {
                                                                        $deficit = $qty - $effectiveStock;
                                                                        $effectiveInfo = " — ⚠️ {$deficit} pcs akan menjadi pre-order";
                                                                    }
                                                                }

                                                                return "{$stockLines}{$effectiveInfo}";
                                                            }

                                                            if ($get('item_type') === 'bundle') {
                                                                $bundleId = $get('bundle_id');
                                                                if (!$bundleId) return 'Select a bundle first.';

                                                                $bundle = Bundle::with('bundleItems.product')->find($bundleId);
                                                                if (!$bundle) return '';

                                                                $allStores = StoreSetting::all();
                                                                $lines = [];

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

                                                            return '';
                                                        }),

                                                    Repeater::make('source_stores')
                                                        ->label('Sumber Stok per Toko')
                                                        ->schema([
                                                            Select::make('store_setting_id')
                                                                ->label('Toko')
                                                                ->options(fn() => StoreSetting::pluck('store_name', 'id')->toArray())
                                                                ->required()
                                                                ->live()
                                                                ->searchable(),
                                                            TextInput::make('qty')
                                                                ->label('Qty dari Toko Ini')
                                                                ->numeric()
                                                                ->default(1)
                                                                ->minValue(1)
                                                                ->required()
                                                                ->live(onBlur: true)
                                                                ->suffix('pcs')
                                                                ->helperText(function (Get $get): string {
                                                                    $storeId   = $get('store_setting_id');
                                                                    $productId = $get('../../product_id');

                                                                    if (!$storeId || !$productId) return '';

                                                                    $stock = InventoryStock::where('product_id', $productId)
                                                                        ->where('store_setting_id', $storeId)
                                                                        ->value('quantity') ?? 0;

                                                                    $inputQty = (int) ($get('qty') ?? 1);

                                                                    if ($stock <= 0) {
                                                                        return "⚠️ Stok kosong di toko ini — {$inputQty} pcs akan jadi pre-order.";
                                                                    }

                                                                    if ($inputQty > $stock) {
                                                                        $preOrder = $inputQty - $stock;
                                                                        return "Stok tersedia: {$stock} pcs — ⚠️ {$preOrder} pcs akan jadi pre-order.";
                                                                    }

                                                                    return "✅ Stok tersedia: {$stock} pcs";
                                                                }),
                                                        ])
                                                        ->columns(2)
                                                        ->addActionLabel('+ Tambah Toko')
                                                        ->live()
                                                        ->visible(fn(Get $get): bool => (bool)$get('is_multi_store'))
                                                        ->afterStateUpdated(function (Get $get, Set $set): void {
                                                            $sources  = $get('source_stores') ?? [];
                                                            $totalQty = collect($sources)->sum(fn($s) => (int)($s['qty'] ?? 0));
                                                            $set('qty', max(1, $totalQty));
                                                            self::recalculateItemSubtotal($get, $set);
                                                        })
                                                        ->collapsible()
                                                        ->columnSpan(2),

                                                    TextInput::make('selling_price')
                                                        ->label('Harga Jual')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                                                        ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                                                        ->required()
                                                        ->columnSpan(1)
                                                        ->debounce(500)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->prefix('Rp'),

                                                    TextInput::make('discount')
                                                        ->label('Discount (Rp)')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                                                        ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                                                        ->default(0)
                                                        ->debounce(500)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->prefix('Rp')
                                                        ->columnSpan(1),

                                                    TextInput::make('subtotal')
                                                        ->label('Subtotal')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                                                        ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                                                        ->afterStateHydrated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->readOnly()
                                                        ->prefix('Rp')
                                                        ->columnSpan(2),
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
                                        ),
                                ]),
                        ]),

                    Step::make('Promo & Shipping')
                        ->label('Promo & Pengiriman')
                        ->description('Terapkan promo dan pilih kurir')
                        ->icon(Heroicon::Truck)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Section::make('Promo / Voucher')
                                        ->description('Pilih promo yang berlaku untuk transaksi ini.')
                                        ->icon(Heroicon::Tag)
                                        ->schema([
                                            Select::make('promo_id')
                                                ->label('Kode Promo')
                                                ->placeholder('Pilih promo...')
                                                ->options(function (Get $get): array {
                                                    $items      = $get('transactionItems') ?? [];
                                                    $productIds = collect($items)
                                                        ->pluck('product_id')
                                                        ->filter()
                                                        ->unique()
                                                        ->values();

                                                    return Promo::query()
                                                        ->where('is_active', true)
                                                        ->where('start_date', '<=', now())
                                                        ->where('end_date', '>=', now())
                                                        ->whereColumn('usage_count', '<', 'usage_limit')
                                                        ->where(function ($query) use ($productIds) {
                                                            $query->whereDoesntHave('promoProducts');

                                                            if ($productIds->isNotEmpty()) {
                                                                $query->orWhereHas('promoProducts', function ($q) use ($productIds) {
                                                                    $q->whereIn('product_id', $productIds);
                                                                });
                                                            }
                                                        })
                                                        ->get()
                                                        ->mapWithKeys(fn($promo) => [
                                                            $promo->id => "{$promo->name} – " .
                                                                ($promo->discount_type === PromoDiscountEnum::PERCENTAGE
                                                                    ? "{$promo->discount_value}%"
                                                                    : 'Rp ' . number_format($promo->discount_value, 0, ',', '.')),
                                                        ])
                                                        ->toArray();
                                                })
                                                ->searchable()
                                                ->nullable()
                                                ->live()
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotals($get, $set))
                                                ->helperText('Promo yang ditampilkan hanya yang aktif dan berlaku saat ini. Promo tanpa batasan produk berlaku untuk semua item.'),

                                            TextEntry::make('promo_detail_info')
                                                ->label('Detail Promo')
                                                ->state(function (Get $get): string {
                                                    $promoId = $get('promo_id');
                                                    if (! $promoId) return '—';
                                                    $promo = Promo::find($promoId);
                                                    if (! $promo) return '—';

                                                    $type = $promo->discount_type === PromoDiscountEnum::PERCENTAGE
                                                        ? "{$promo->discount_value}% off"
                                                        : 'Rp ' . number_format($promo->discount_value, 0, ',', '.') . ' off';

                                                    $minPurchase = $promo->min_purchase
                                                        ? 'Min. purchase Rp ' . number_format($promo->min_purchase, 0, ',', '.')
                                                        : 'No minimum purchase';

                                                    $productScope = $promo->promoProducts()->exists()
                                                        ? 'Specific products only'
                                                        : 'All products';

                                                    $remaining = $promo->usage_limit - $promo->usage_count;

                                                    return "✅ {$type} | {$minPurchase} | Scope: {$productScope} | Uses left: {$remaining}x";
                                                })
                                                ->visible(fn(Get $get): bool => filled($get('promo_id'))),
                                        ]),

                                    Section::make('Shipping')
                                        ->description('Pick a courier for delivery.')
                                        ->icon(Heroicon::Truck)
                                        ->schema([
                                            Select::make('courier_id')
                                                ->label('Kurir')
                                                ->placeholder('Pick a courier...')
                                                ->options(Courier::where('is_active', true)->pluck('name', 'id'))
                                                ->searchable()
                                                ->nullable()
                                                ->live()
                                                ->dehydrated(true)
                                                ->afterStateHydrated(function (Select $component, $record): void {
                                                    if ($record && $record->transactionShipment) {
                                                        $component->state($record->transactionShipment->courier_id);
                                                    }
                                                })
                                                ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                                                    if ($state) {
                                                        $courier = Courier::find($state);

                                                        if ($courier) {
                                                            $set('shiping_cost', (float) $courier->shipping_cost);
                                                        }
                                                    } else {
                                                        $set('shiping_cost', 0);
                                                    }

                                                    self::recalculateTotals($get, $set);
                                                }),

                                            TextInput::make('shiping_cost')
                                                ->label('Biaya Pengiriman')
                                                ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                                ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                                ->default(0)
                                                ->live(onBlur: true)
                                                ->afterStateHydrated(fn(Get $get, Set $set) => self::recalculateTotals($get, $set))
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotals($get, $set))
                                                ->prefix('Rp')
                                                ->helperText('Anda bisa mengubah biaya pengiriman jika diperlukan.'),
                                        ]),
                                ]),
                        ]),

                    Step::make('Summary')
                        ->label('Ringkasan')
                        ->description('Ulasan & konfirmasi transaksi')
                        ->icon(Heroicon::ClipboardDocumentCheck)
                        ->completedIcon(Heroicon::CheckBadge)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Grid::make(1)
                                        ->schema([
                                            Section::make('Rincian Harga')
                                                ->icon(Heroicon::ReceiptRefund)
                                                ->schema([
                                                    TextInput::make('subtotal')
                                                        ->label('Subtotal + Discount')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                                        ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                                        ->readOnly()
                                                        ->prefix('Rp'),

                                                    TextInput::make('promo_total')
                                                        ->label('Promo Discount')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                                        ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                                        ->readOnly()
                                                        ->prefix('Rp'),

                                                    TextInput::make('shiping_cost')
                                                        ->label('Biaya Pengiriman')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                                        ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                                        ->readOnly()
                                                        ->prefix('Rp'),

                                                    TextInput::make('grand_total')
                                                        ->label('Grand Total')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                                        ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                                        ->readOnly()
                                                        ->prefix('Rp'),
                                                ]),
                                            Section::make('Detail Pesanan')
                                                ->icon(Heroicon::ShoppingBag)
                                                ->schema([
                                                    TextEntry::make('items_summary')
                                                        ->label('Produk Dipilih')
                                                        ->listWithLineBreaks()
                                                        ->state(function (Get $get): array {
                                                            $items = $get('transactionItems') ?? [];
                                                            if (empty($items)) return ['Tidak ada produk yang ditambahkan.'];

                                                            $lines = [];
                                                            foreach ($items as $item) {
                                                                $qty          = (int) ($item['qty'] ?? 0);
                                                                $sellingPrice = self::parseCurrency($item['selling_price'] ?? 0);
                                                                $discount     = self::parseCurrency($item['discount'] ?? 0);
                                                                $subtotal     = self::parseCurrency($item['subtotal'] ?? 0);

                                                                if (($item['item_type'] ?? 'product') === 'bundle' && ! empty($item['bundle_id'])) {
                                                                    $bundle = Bundle::find($item['bundle_id']);
                                                                    $name   = $bundle ? "[Bundle] {$bundle->name}" : 'Bundle not found';
                                                                } else {
                                                                    $productId = $item['product_id'] ?? null;
                                                                    $name      = $productId
                                                                        ? (Product::find($productId)?->name ?? 'Product not found')
                                                                        : '—';
                                                                }

                                                                $discountPart = $discount > 0
                                                                    ? ' - Rp ' . number_format($discount, 0, ',', '.') . ' (disc)'
                                                                    : '';

                                                                // Tampilkan info multi-store jika aktif
                                                                $multiStorePart = '';
                                                                if (!empty($item['is_multi_store']) && !empty($item['source_stores'])) {
                                                                    $storeParts = collect($item['source_stores'])
                                                                        ->filter(fn($s) => !empty($s['store_setting_id']) && (int)($s['qty'] ?? 0) > 0)
                                                                        ->map(function ($s) {
                                                                            $storeName = StoreSetting::find($s['store_setting_id'])?->store_name ?? 'Unknown Store';
                                                                            return "{$storeName}: {$s['qty']} pcs";
                                                                        })
                                                                        ->implode(' + ');

                                                                    $multiStorePart = $storeParts ? " [Multi-store: {$storeParts}]" : '';
                                                                }

                                                                $lines[] = "• {$name} * {$qty} @ Rp " . number_format($sellingPrice, 0, ',', '.') . "{$discountPart}{$multiStorePart} = Rp " . number_format($subtotal, 0, ',', '.');
                                                            }

                                                            $discountReferal = self::parseCurrency($get('use_discount_referal') ?? 0);
                                                            if ($discountReferal > 0) {
                                                                $lines[] = '──────────────────────────';
                                                                $lines[] = '🎁 Discount Referal: − Rp ' . number_format($discountReferal, 0, ',', '.');
                                                            }

                                                            return $lines;
                                                        }),
                                                ]),
                                        ]),
                                    Grid::make(1)
                                        ->schema([
                                            Section::make('Referal')
                                                ->description('Tambahkan referral customer jika transaksi ini berasal dari referral.')
                                                ->icon(Heroicon::UserGroup)
                                                ->schema([
                                                    Toggle::make('is_referal')
                                                        ->label('Gunakan Referal?')
                                                        ->default(false)
                                                        ->live()
                                                        ->dehydrated(true)
                                                        ->afterStateUpdated(function (Set $set, $state): void {
                                                            if (! $state) {
                                                                $set('referal_customer_id', null);
                                                                $set('nominal_referal', null);
                                                            }
                                                        }),

                                                    Select::make('referal_customer_id')
                                                        ->label('Customer Referal')
                                                        ->placeholder('Cari customer yang mereferralkan...')
                                                        ->options(function (Get $get): array {
                                                            $currentCustomerId = $get('customer_id');

                                                            return Customer::query()
                                                                ->when($currentCustomerId, fn($q) => $q->where('id', '!=', $currentCustomerId))
                                                                ->get()
                                                                ->mapWithKeys(function (Customer $customer): array {
                                                                    $referal = Referal::where('customer_id', $customer->id)->first();
                                                                    $label   = $customer->name;

                                                                    if ($referal && $referal->discount_amount > 0) {
                                                                        $label .= ' — Saldo: ' . RupiahHelper::format($referal->discount_amount);
                                                                    }

                                                                    return [$customer->id => $label];
                                                                })
                                                                ->toArray();
                                                        })
                                                        ->searchable()
                                                        ->nullable()
                                                        ->live()
                                                        ->dehydrated(true)
                                                        ->visible(fn(Get $get): bool => (bool) $get('is_referal'))
                                                        ->required(fn(Get $get): bool => (bool) $get('is_referal'))
                                                        ->helperText('Pilih customer yang mereferralkan transaksi ini. Nominal referal akan ditambahkan ke saldo mereka.'),

                                                    TextInput::make('nominal_referal')
                                                        ->label('Nominal Referal')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                                        ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                                        ->default(0)
                                                        ->minValue(0)
                                                        ->prefix('Rp')
                                                        ->live(onBlur: true)
                                                        ->dehydrated(true)
                                                        ->visible(fn(Get $get): bool => (bool) $get('is_referal'))
                                                        ->required(fn(Get $get): bool => (bool) $get('is_referal'))
                                                        ->helperText('Jumlah discount (Rupiah) yang akan ditambahkan ke saldo referal customer referral.'),
                                                ]),

                                            Section::make('Discount Referal')
                                                ->description('Gunakan saldo referal milik customer ini untuk potongan harga.')
                                                ->icon(Heroicon::GiftTop)
                                                ->visible(function (Get $get): bool {
                                                    $customerId = $get('customer_id');
                                                    if (! $customerId) return false;

                                                    $referal = Referal::where('customer_id', $customerId)->first();
                                                    return $referal && $referal->discount_amount > 0;
                                                })
                                                ->schema([
                                                    TextEntry::make('referal_balance_info')
                                                        ->label('Saldo Referal Anda')
                                                        ->state(function (Get $get): string {
                                                            $customerId = $get('customer_id');
                                                            if (! $customerId) return '—';

                                                            $referal = Referal::where('customer_id', $customerId)->first();
                                                            if (! $referal || $referal->discount_amount <= 0) {
                                                                return 'Tidak ada saldo referal.';
                                                            }

                                                            $maxReward = self::getMaxReward($get);

                                                            return '✅ Saldo tersedia: ' . RupiahHelper::format($referal->discount_amount)
                                                                . ' (Maks. penggunaan: ' . RupiahHelper::format($maxReward) . ' per transaksi)';
                                                        }),

                                                    TextInput::make('use_discount_referal')
                                                        ->label('Discount Referal')
                                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                                        ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                                        ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                                        ->default(0)
                                                        ->prefix('Rp')
                                                        ->dehydrated(true)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function (Get $get, Set $set, $state): void {

                                                            $customerId = $get('customer_id');
                                                            $maxUsage   = self::getMaxReward($get);

                                                            $state = self::parseCurrency($state);

                                                            if ($state > $maxUsage) {
                                                                $set(
                                                                    'use_discount_referal',
                                                                    number_format($maxUsage, 0, ',', '.')
                                                                );

                                                                $state = $maxUsage;
                                                            }

                                                            if ($customerId) {
                                                                $referal = Referal::where('customer_id', $customerId)->first();

                                                                if ($referal && $state > $referal->discount_amount) {

                                                                    $set(
                                                                        'use_discount_referal',
                                                                        number_format($referal->discount_amount, 0, ',', '.')
                                                                    );
                                                                }
                                                            }

                                                            self::recalculateTotals($get, $set);
                                                        })
                                                        ->helperText(function (Get $get): string {
                                                            $customerId = $get('customer_id');
                                                            if (! $customerId) return '';

                                                            $referal  = Referal::where('customer_id', $customerId)->first();
                                                            $balance  = $referal ? (float) $referal->discount_amount : 0;
                                                            $maxReward = self::getMaxReward($get);
                                                            $maxUse   = min($maxReward, $balance);

                                                            return 'Maksimal penggunaan: ' . RupiahHelper::format($maxUse)
                                                                . '. Saldo saat ini: ' . RupiahHelper::format($balance);
                                                        }),
                                                ]),
                                        ])
                                ]),

                            Section::make('Pembayaran')
                                ->description('Input detail pembayaran transaksi ini.')
                                ->icon(Heroicon::CreditCard)
                                ->columns(3)
                                ->schema([
                                    Select::make('payment_method')
                                        ->label('Metode Pembayaran')
                                        ->options(
                                            collect(TransactionPaymentMethodEnum::cases())
                                                ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                                ->toArray()
                                        )
                                        ->required()
                                        ->native(false)
                                        ->dehydrated(true)
                                        ->afterStateHydrated(function (Select $component, $record): void {
                                            if ($record && $record->transactionPayment) {
                                                $component->state($record->transactionPayment->method?->value ?? $record->transactionPayment->method);
                                            }
                                        }),
                                    TextInput::make('payment_amount')
                                        ->label('Jumlah Terbayar')
                                        ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                                        ->dehydrateStateUsing(fn($state) => self::parseCurrency($state))
                                        ->formatStateUsing(fn($state) => number_format(self::parseCurrency($state), 0, ',', '.'))
                                        ->required()
                                        ->prefix('Rp')
                                        ->dehydrated(true)
                                        ->afterStateHydrated(function (TextInput $component, $record): void {
                                            if ($record && $record->transactionPayment) {
                                                $amount = (float) $record->transactionPayment->amount;
                                                $component->state(number_format($amount, 0, ',', '.'));
                                            }
                                        }),
                                    Select::make('payment_status')
                                        ->label('Status Pembayaran')
                                        ->options(
                                            collect(TransactionPaymentStatusEnum::cases())
                                                ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                                ->toArray()
                                        )
                                        ->required()
                                        ->native(false)
                                        ->dehydrated(true)
                                        ->afterStateHydrated(function (Select $component, $record): void {
                                            if ($record && $record->transactionPayment) {
                                                $component->state($record->transactionPayment->status?->value ?? $record->transactionPayment->status);
                                            }
                                        }),
                                    Toggle::make('is_down_payment')
                                        ->label('Is Down Payment')
                                        ->offIcon(Heroicon::XMark)
                                        ->onIcon(Heroicon::Check)
                                        ->nullable()
                                        ->offColor('danger')
                                        ->onColor('success')
                                        ->inline(false)
                                        ->default(false),
                                    DateTimePicker::make('due_date_down_payment')
                                        ->label('Jatuh Tempo Down Payment')
                                        ->native(false)
                                        ->suffixIcon(Heroicon::Calendar)
                                        ->closeOnDateSelection()
                                        ->nullable(),
                                ]),
                        ]),

                ])
                    ->skippable()
                    ->columnSpanFull(),
            ]);
    }

    protected static function parseCurrency(mixed $value): float
    {
        if (blank($value)) {
            return 0;
        }

        if (is_int($value) || is_float($value)) {
            return (float) $value;
        }

        $stringValue = (string) $value;

        if (preg_match('/^\d+\.\d{1,2}$/', $stringValue)) {
            return (float) $stringValue;
        }

        $value = preg_replace('/[^0-9]/', '', $stringValue);

        return (float) $value;
    }

    protected static function getStoreId(Get $get): ?int
    {
        $storeId = Auth::user()?->store_setting_id;
        return $storeId ?? ($get('store_setting_id') ? (int) $get('store_setting_id') : null);
    }

    protected static function getAvailableStock(int $productId, ?int $storeId): int
    {
        $query = InventoryStock::where('product_id', $productId);
        if (! is_null($storeId)) {
            $query->where('store_setting_id', $storeId);
        }
        return (int) $query->sum('quantity');
    }

    protected static function recalculateItemSubtotal(Get $get, Set $set): void
    {
        $qty = (float) ($get('qty') ?? 0);
        $price = self::parseCurrency($get('selling_price'));
        $discount = self::parseCurrency($get('discount'));
        $subtotal = max(0, ($qty * $price) - $discount);
        $set('subtotal', number_format($subtotal, 0, ',', '.'));
    }


    protected static function getEffectiveStock(int $productId, ?int $storeId, Get $get, ?string $currentItemKey = null): int
    {
        $actualStock = self::getAvailableStock($productId, $storeId);

        $allItems = $get('../../transactionItems') ?? [];

        $usedQty = 0;
        foreach ($allItems as $key => $item) {
            if ($currentItemKey !== null && $key === $currentItemKey) {
                continue;
            }

            $itemType = $item['item_type'] ?? 'product';
            $itemQty  = (int) ($item['qty'] ?? 0);

            if ($itemType === 'product' && isset($item['product_id']) && (int) $item['product_id'] === $productId) {
                $usedQty += $itemQty;
            }

            if ($itemType === 'bundle' && isset($item['bundle_id'])) {
                $bundle = Bundle::with('bundleItems')->find($item['bundle_id']);
                if ($bundle) {
                    foreach ($bundle->bundleItems as $bi) {
                        if ((int) $bi->product_id === $productId) {
                            $usedQty += $bi->qty * $itemQty;
                        }
                    }
                }
            }
        }

        return $actualStock - $usedQty;
    }

    protected static function recalculateTotals(Get $get, Set $set): void
    {
        $items = $get('transactionItems') ?? [];

        $subtotal = collect($items)->sum(function ($i) {
            $qty = (float) ($i['qty'] ?? 0);
            $price = self::parseCurrency($i['selling_price'] ?? 0);
            $discount = self::parseCurrency($i['discount'] ?? 0);
            return max(0, ($qty * $price) - $discount);
        });

        $promoTotal = 0;
        $promoId = $get('promo_id');

        if ($promoId) {
            $promo = Promo::find($promoId);

            if ($promo && $subtotal >= (float) ($promo->min_purchase ?? 0)) {
                $promoTotal = $promo->discount_type === PromoDiscountEnum::PERCENTAGE
                    ? round($subtotal * ($promo->discount_value / 100), 2)
                    : min((float) $promo->discount_value, $subtotal);
            }
        }

        $discountReferal = self::parseCurrency($get('use_discount_referal') ?? 0);
        $shippingCost = self::parseCurrency($get('shiping_cost') ?? 0);
        $grandTotal = max(0, $subtotal - $promoTotal - $discountReferal + $shippingCost);

        $set('subtotal', number_format($subtotal, 0, ',', '.'));
        $set('promo_total', number_format($promoTotal, 0, ',', '.'));
        $set('grand_total', number_format($grandTotal, 0, ',', '.'));
    }

    protected static function getMaxReward(Get $get): float
    {
        $storeId = self::getStoreId($get);
        if (!$storeId) return 200000;

        $store = StoreSetting::find($storeId);
        return (float) ($store?->set_max_reward ?? 200000);
    }
}
