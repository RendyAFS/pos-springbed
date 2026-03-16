<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\PromoDiscountEnum;
use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Bundle;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\Promo;
use App\Models\StoreSetting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([

                    // ─── STEP 1: Customer ──────────────────────────────────────────────
                    Step::make('Customer')
                        ->label('Customer')
                        ->description('Pick a customer & set the transaction date')
                        ->icon(Heroicon::User)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Section::make('Transaction Info')
                                ->description('Fill in the customer details and transaction time.')
                                ->icon(Heroicon::DocumentText)
                                ->columns(2)
                                ->schema([
                                    TextInput::make('transaction_code')
                                        ->label('Transaction Code')
                                        ->default(fn(): string => 'TRX' . strtoupper(Str::random(8)) . now()->format('Ymd'))
                                        ->disabled()
                                        ->dehydrated(true)
                                        ->columnSpan(1),
                                    DatePicker::make('transaction_date')
                                        ->label('Transaction Date')
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
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->label('Full Name')
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('phone')
                                                ->label('Phone Number')
                                                ->tel()
                                                ->maxLength(20),
                                            TextInput::make('email')
                                                ->label('Email')
                                                ->email()
                                                ->maxLength(255),
                                            Textarea::make('address')
                                                ->label('Address')
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
                                                ->label('Phone Number'),
                                            TextInput::make('email')
                                                ->label('Email')
                                                ->email(),
                                            Textarea::make('address')
                                                ->label('Address'),
                                        ])
                                        ->updateOptionUsing(function (array $data, $record) {
                                            $record->update($data);
                                        })
                                        ->placeholder('Search by name, phone, or email...')
                                        ->columnSpanFull(),

                                    Select::make('status')
                                        ->label('Transaction Status')
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
                                        ->label('Store')
                                        ->options(fn() => StoreSetting::pluck('store_name', 'id')->toArray())
                                        ->searchable()
                                        ->required()
                                        ->native(false)
                                        ->live()
                                        ->dehydrated(true)
                                        ->visible(fn(): bool => is_null(Auth::user()?->store_setting_id))
                                        ->columnSpan(1),
                                ]),
                        ]),

                    // ─── STEP 2: Products ──────────────────────────────────────────────
                    Step::make('Products')
                        ->label('Products')
                        ->description('Add the items being purchased')
                        ->icon(Heroicon::ShoppingCart)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Section::make('Product Items')
                                ->description('Choose individual products or bundles.')
                                ->icon(Heroicon::CubeTransparent)
                                ->schema([
                                    Repeater::make('transactionItems')
                                        ->label('')
                                        ->relationship('transactionItems')
                                        ->schema([
                                            Section::make()
                                                ->schema([
                                                    // ── Item Type Toggle ───────────────────────────────────────
                                                    Radio::make('item_type')
                                                        ->label('Item Type')
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

                                                    // ── Single Product ─────────────────────────────────────────
                                                    Select::make('product_id')
                                                        ->label('Product')
                                                        ->options(function (): array {
                                                            $storeId = Auth::user()?->store_setting_id;
                                                            $query   = Product::query()->where('is_active', true);
                                                            if (! is_null($storeId)) {
                                                                $query->where('store_setting_id', $storeId);
                                                            }
                                                            return $query->pluck('name', 'id')->toArray();
                                                        })
                                                        ->searchable()
                                                        ->preload()
                                                        ->live()
                                                        ->visible(fn(Get $get): bool => $get('item_type') !== 'bundle')
                                                        ->required(fn(Get $get): bool => $get('item_type') !== 'bundle')
                                                        ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                                                            if ($state) {
                                                                $product = Product::find($state);
                                                                if ($product) {
                                                                    $set('selling_price', $product->selling_price);
                                                                    self::recalculateItemSubtotal($get, $set);
                                                                }
                                                            }
                                                            $set('qty', 1);
                                                        }),

                                                    // ── Bundle ─────────────────────────────────────────────────
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
                                                                    $set('selling_price', $bundle->bundle_price);
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
                                                                $stockLabel = $stock > 0 ? "stock: {$stock}" : '⚠️ out of stock';
                                                                return "{$bi->product->name} × {$bi->qty} ({$stockLabel})";
                                                            })->implode(' | ');

                                                            return "Bundle contents: {$items}";
                                                        }),

                                                    // ── Qty ────────────────────────────────────────────────────
                                                    TextInput::make('qty')
                                                        ->label('Qty')
                                                        ->numeric()
                                                        ->default(1)
                                                        ->minValue(1)
                                                        ->required()
                                                        ->debounce(500)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->suffix('pcs')
                                                        ->rules([
                                                            fn(Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get): void {
                                                                $storeId = Auth::user()?->store_setting_id;

                                                                if ($get('item_type') === 'product') {
                                                                    $productId = $get('product_id');
                                                                    if (! $productId || ! $value) return;
                                                                    $stock = self::getAvailableStock($productId, $storeId);
                                                                    if ((int) $value > $stock) {
                                                                        $fail("Qty exceeds available stock ({$stock} pcs).");
                                                                    }
                                                                } elseif ($get('item_type') === 'bundle') {
                                                                    $bundleId = $get('bundle_id');
                                                                    if (! $bundleId || ! $value) return;
                                                                    $bundle = Bundle::with('bundleItems')->find($bundleId);
                                                                    if (! $bundle) return;
                                                                    foreach ($bundle->bundleItems as $bi) {
                                                                        $needed = $bi->qty * (int) $value;
                                                                        $stock  = self::getAvailableStock($bi->product_id, $storeId);
                                                                        if ($needed > $stock) {
                                                                            $fail("Not enough stock for {$bi->product->name}. Need: {$needed}, available: {$stock}.");
                                                                        }
                                                                    }
                                                                }
                                                            },
                                                        ])
                                                        ->helperText(function (Get $get): string {
                                                            $storeId = Auth::user()?->store_setting_id;

                                                            if ($get('item_type') === 'product') {
                                                                $productId = $get('product_id');
                                                                if (! $productId) return 'Select a product first.';
                                                                $stock = self::getAvailableStock($productId, $storeId);
                                                                return $stock > 0 ? "Available stock: {$stock} pcs" : '⚠️ Out of stock';
                                                            }

                                                            if ($get('item_type') === 'bundle') {
                                                                $bundleId = $get('bundle_id');
                                                                if (! $bundleId) return 'Select a bundle first.';

                                                                $bundle = Bundle::with('bundleItems.product')->find($bundleId);
                                                                if (! $bundle) return '';

                                                                $stockLines = $bundle->bundleItems->map(function ($bi) use ($storeId) {
                                                                    $stock  = self::getAvailableStock($bi->product_id, $storeId);
                                                                    $maxSet = $bi->qty > 0 ? floor($stock / $bi->qty) : 0;
                                                                    $icon   = $stock > 0 ? '✅' : '⚠️';
                                                                    return "{$icon} {$bi->product->name}: {$stock} pcs (max {$maxSet} sets)";
                                                                })->implode(' | ');

                                                                $minQty = $bundle->bundleItems->min(function ($bi) use ($storeId) {
                                                                    $stock = self::getAvailableStock($bi->product_id, $storeId);
                                                                    return $bi->qty > 0 ? floor($stock / $bi->qty) : 0;
                                                                });

                                                                return "{$stockLines} — Max bundle sets: {$minQty}";
                                                            }

                                                            return '';
                                                        }),

                                                    TextInput::make('selling_price')
                                                        ->label('Unit Price')
                                                        ->numeric()
                                                        ->required()
                                                        ->debounce(500)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->prefix('Rp'),

                                                    TextInput::make('discount')
                                                        ->label('Discount (Rp)')
                                                        ->numeric()
                                                        ->default(0)
                                                        ->debounce(500)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->prefix('Rp'),

                                                    TextInput::make('subtotal')
                                                        ->label('Subtotal')
                                                        ->numeric()
                                                        ->readOnly()
                                                        ->prefix('Rp')
                                                        ->columnSpanFull(),
                                                ])
                                                ->columns(2),
                                        ])
                                        ->addActionLabel('+ Add Item')
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

                    // ─── STEP 3: Promo & Shipping ──────────────────────────────────────
                    Step::make('Promo & Shipping')
                        ->label('Promo & Ship')
                        ->description('Apply a promo and pick a courier')
                        ->icon(Heroicon::Truck)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Section::make('Promo / Voucher')
                                        ->description('Pick a promo that applies to this transaction.')
                                        ->icon(Heroicon::Tag)
                                        ->schema([
                                            Select::make('promo_id')
                                                ->label('Promo Code')
                                                ->placeholder('Select a promo...')
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
                                                ->helperText('Only active & currently valid promos are shown. Promos with no product restriction apply to all items.'),

                                            TextEntry::make('promo_detail_info')
                                                ->label('Promo Detail')
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
                                                ->label('Courier')
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
                                                            $set('shiping_cost', $courier->shipping_cost);
                                                        }
                                                    } else {
                                                        $set('shiping_cost', 0);
                                                    }
                                                    self::recalculateTotals($get, $set);
                                                }),

                                            TextInput::make('shiping_cost')
                                                ->label('Shipping Cost')
                                                ->numeric()
                                                ->default(0)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotals($get, $set))
                                                ->prefix('Rp')
                                                ->helperText('You can adjust this manually if needed.'),
                                        ]),
                                ]),
                        ]),

                    // ─── STEP 4: Summary & Payment ────────────────────────────────────
                    Step::make('Summary')
                        ->label('Summary')
                        ->description('Review & confirm the transaction')
                        ->icon(Heroicon::ClipboardDocumentCheck)
                        ->completedIcon(Heroicon::CheckBadge)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Section::make('Price Breakdown')
                                        ->icon(Heroicon::ReceiptRefund)
                                        ->schema([
                                            TextInput::make('subtotal')->label('Subtotal + Discount')->numeric()->readOnly()->prefix('Rp'),
                                            TextInput::make('promo_total')->label('Promo Discount')->numeric()->readOnly()->prefix('Rp'),
                                            TextInput::make('shiping_cost')->label('Shipping Cost')->numeric()->readOnly()->prefix('Rp'),
                                            TextInput::make('grand_total')->label('Grand Total')->numeric()->readOnly()->prefix('Rp'),
                                        ]),

                                    Section::make('Product Items')
                                        ->icon(Heroicon::ShoppingBag)
                                        ->schema([
                                            TextEntry::make('items_summary')
                                                ->label('Selected Products')
                                                ->listWithLineBreaks()
                                                ->state(function (Get $get): array {
                                                    $items = $get('transactionItems') ?? [];
                                                    if (empty($items)) return ['No products added yet.'];
                                                    $lines = [];
                                                    foreach ($items as $item) {
                                                        $qty          = (int) ($item['qty'] ?? 0);
                                                        $sellingPrice = (float) ($item['selling_price'] ?? 0);
                                                        $discount     = (float) ($item['discount'] ?? 0);
                                                        $subtotal     = (float) ($item['subtotal'] ?? 0);

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
                                                            ? ' − Rp ' . number_format($discount, 0, ',', '.') . ' (disc)'
                                                            : '';

                                                        $lines[] = "• {$name} × {$qty} @ Rp " . number_format($sellingPrice, 0, ',', '.') . "{$discountPart} = Rp " . number_format($subtotal, 0, ',', '.');
                                                    }
                                                    return $lines;
                                                }),
                                        ]),
                                ]),

                            Section::make('Payment')
                                ->description('Fill in the payment details for this transaction.')
                                ->icon(Heroicon::CreditCard)
                                ->columns(3)
                                ->schema([
                                    Select::make('payment_method')
                                        ->label('Payment Method')
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
                                        ->label('Amount Paid')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->dehydrated(true)
                                        ->afterStateHydrated(function (TextInput $component, $record): void {
                                            if ($record && $record->transactionPayment) {
                                                $component->state($record->transactionPayment->amount);
                                            }
                                        }),

                                    Select::make('payment_status')
                                        ->label('Payment Status')
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
                                ]),
                        ]),

                ])
                    ->skippable(false)
                    ->columnSpanFull(),
            ]);
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
        $qty      = (float) ($get('qty') ?? 0);
        $price    = (float) ($get('selling_price') ?? 0);
        $discount = (float) ($get('discount') ?? 0);
        $set('subtotal', round(max(0, ($qty * $price) - $discount), 2));
    }

    protected static function recalculateTotals(Get $get, Set $set): void
    {
        $items    = $get('transactionItems') ?? [];
        $subtotal = collect($items)->sum(fn($i) => (float) ($i['subtotal'] ?? 0));

        $promoTotal = 0.0;
        $promoId    = $get('promo_id');

        if ($promoId) {
            $promo = Promo::find($promoId);
            if ($promo && $subtotal >= (float) ($promo->min_purchase ?? 0)) {
                $promoTotal = $promo->discount_type === PromoDiscountEnum::PERCENTAGE
                    ? round($subtotal * ($promo->discount_value / 100), 2)
                    : min((float) $promo->discount_value, $subtotal);
            }
        }

        $shippingCost = (float) ($get('shiping_cost') ?? 0);
        $set('subtotal',    round($subtotal, 2));
        $set('promo_total', round($promoTotal, 2));
        $set('grand_total', round(max(0, $subtotal - $promoTotal + $shippingCost), 2));
    }
}
