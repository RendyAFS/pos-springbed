<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\PromoDiscountEnum;
use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Courier;
use App\Models\Customer;
use App\Models\InventoryStock;
use App\Models\Product;
use App\Models\Promo;
use Filament\Forms\Components\DatePicker;
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

                    // ─── STEP 1: Pelanggan ─────────────────────────────────────────────
                    Step::make('Pelanggan')
                        ->label('Pelanggan')
                        ->description('Pilih pelanggan & tanggal transaksi')
                        ->icon(Heroicon::User)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Section::make('Informasi Transaksi')
                                ->description('Isi data pelanggan dan waktu transaksi.')
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
                                        ->label('Pelanggan')
                                        ->options(Customer::query()->get()->mapWithKeys(fn($c) => [$c->id => $c->name]))
                                        ->getOptionLabelUsing(fn($value): ?string => Customer::find($value)?->name)
                                        ->getSearchResultsUsing(function (string $search): array {
                                            return Customer::query()
                                                ->where('name', 'like', "%{$search}%")
                                                ->orWhere('phone', 'like', "%{$search}%")
                                                ->orWhere('email', 'like', "%{$search}%")
                                                ->limit(50)
                                                ->get()
                                                ->mapWithKeys(function ($c): array {
                                                    $desc  = collect([$c->phone, $c->email])->filter()->implode(' | ');
                                                    return [$c->id => $c->name . ($desc ? " — {$desc}" : '')];
                                                })
                                                ->toArray();
                                        })
                                        ->searchable()
                                        ->createOptionForm([
                                            TextInput::make('name')->label('Nama')->required()->maxLength(255),
                                            TextInput::make('phone')->label('No. Telepon')->tel()->maxLength(20),
                                            TextInput::make('email')->label('Email')->email()->maxLength(255),
                                            Textarea::make('address')->label('Alamat')->rows(3),
                                        ])
                                        ->placeholder('Cari nama, telepon, atau email...')
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
                                ]),
                        ]),

                    // ─── STEP 2: Produk ────────────────────────────────────────────────
                    Step::make('Produk')
                        ->label('Produk')
                        ->description('Tambahkan item produk yang dibeli')
                        ->icon(Heroicon::ShoppingCart)
                        ->completedIcon(Heroicon::CheckCircle)
                        ->schema([
                            Section::make('Daftar Item Produk')
                                ->description('Pilih produk dan tentukan jumlah serta harga jual.')
                                ->icon(Heroicon::CubeTransparent)
                                ->schema([
                                    Repeater::make('transactionItems')
                                        ->label('')
                                        ->relationship('transactionItems')
                                        ->schema([
                                            Grid::make(12)
                                                ->schema([
                                                    Select::make('product_id')
                                                        ->label('Produk')
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
                                                        ->required()
                                                        ->live()
                                                        ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                                                            if ($state) {
                                                                $product = Product::find($state);
                                                                if ($product) {
                                                                    $set('selling_price', $product->selling_price);
                                                                    self::recalculateItemSubtotal($get, $set);
                                                                }
                                                            }
                                                            $set('qty', 1);
                                                        })
                                                        ->columnSpan(5),

                                                    TextInput::make('qty')
                                                        ->label('Qty')
                                                        ->numeric()
                                                        ->default(1)
                                                        ->minValue(1)
                                                        ->required()
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->suffix('pcs')
                                                        ->rules([
                                                            fn(Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get): void {
                                                                $productId = $get('product_id');
                                                                if (! $productId || ! $value) return;
                                                                $stock = self::getAvailableStock($productId, Auth::user()?->store_setting_id);
                                                                if ((int) $value > $stock) {
                                                                    $fail("Qty melebihi stok tersedia ({$stock} pcs).");
                                                                }
                                                            },
                                                        ])
                                                        ->helperText(function (Get $get): string {
                                                            $productId = $get('product_id');
                                                            if (! $productId) return 'Pilih produk terlebih dahulu.';
                                                            $stock = self::getAvailableStock($productId, Auth::user()?->store_setting_id);
                                                            return $stock > 0 ? "Stok tersedia: {$stock} pcs" : '⚠️ Stok habis';
                                                        })
                                                        ->columnSpan(2),

                                                    TextInput::make('selling_price')
                                                        ->label('Harga Satuan')
                                                        ->numeric()
                                                        ->required()
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->prefix('Rp')
                                                        ->columnSpan(3),

                                                    TextInput::make('discount')
                                                        ->label('Diskon (Rp)')
                                                        ->numeric()
                                                        ->default(0)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateItemSubtotal($get, $set))
                                                        ->prefix('Rp')
                                                        ->columnSpan(2),

                                                    TextInput::make('subtotal')
                                                        ->label('Subtotal')
                                                        ->numeric()
                                                        ->readOnly()
                                                        ->prefix('Rp')
                                                        ->columnSpanFull(),
                                                ]),
                                        ])
                                        ->addActionLabel('+ Tambah Produk')
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

                    // ─── STEP 3: Promo & Pengiriman ────────────────────────────────────
                    Step::make('Promo & Pengiriman')
                        ->label('Promo & Kirim')
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
                                                ->options(function (): array {
                                                    return Promo::query()
                                                        ->where('is_active', true)
                                                        ->where('start_date', '<=', now())
                                                        ->where('end_date', '>=', now())
                                                        ->whereColumn('usage_count', '<', 'usage_limit')
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
                                                ->helperText('Hanya promo aktif & masih berlaku yang ditampilkan.'),

                                            TextEntry::make('promo_detail_info')
                                                ->label('Detail Promo')
                                                ->state(function (Get $get): string {
                                                    $promoId = $get('promo_id');
                                                    if (! $promoId) return '—';
                                                    $promo = Promo::find($promoId);
                                                    if (! $promo) return '—';
                                                    $type = $promo->discount_type === PromoDiscountEnum::PERCENTAGE
                                                        ? "Diskon {$promo->discount_value}%"
                                                        : 'Potongan Rp ' . number_format($promo->discount_value, 0, ',', '.');
                                                    $minPurchase = $promo->min_purchase
                                                        ? 'Min. beli Rp ' . number_format($promo->min_purchase, 0, ',', '.')
                                                        : 'Tanpa minimum pembelian';
                                                    $sisa = $promo->usage_limit - $promo->usage_count;
                                                    return "✅ {$type} | {$minPurchase} | Sisa kuota: {$sisa}x";
                                                })
                                                ->visible(fn(Get $get): bool => filled($get('promo_id'))),
                                        ]),

                                    Section::make('Pengiriman')
                                        ->description('Pilih kurir untuk pengiriman.')
                                        ->icon(Heroicon::Truck)
                                        ->schema([
                                            // ✅ dehydrated(true) — nilai courier_id tersedia di $this->data afterCreate()
                                            Select::make('courier_id')
                                                ->label('Kurir')
                                                ->placeholder('Pilih kurir...')
                                                ->options(Courier::where('is_active', true)->pluck('name', 'id'))
                                                ->searchable()
                                                ->nullable()
                                                ->live()
                                                ->dehydrated(true)
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
                                                ->label('Ongkos Kirim')
                                                ->numeric()
                                                ->default(0)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(fn(Get $get, Set $set) => self::recalculateTotals($get, $set))
                                                ->prefix('Rp')
                                                ->helperText('Dapat diubah manual jika diperlukan.'),
                                        ]),
                                ]),
                        ]),

                    // ─── STEP 4: Ringkasan & Pembayaran ───────────────────────────────
                    Step::make('Ringkasan')
                        ->label('Ringkasan')
                        ->description('Review & konfirmasi transaksi')
                        ->icon(Heroicon::ClipboardDocumentCheck)
                        ->completedIcon(Heroicon::CheckBadge)
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    Section::make('Ringkasan Harga')
                                        ->icon(Heroicon::ReceiptRefund)
                                        ->schema([
                                            TextInput::make('subtotal')->label('Subtotal')->numeric()->readOnly()->prefix('Rp'),
                                            TextInput::make('discount_total')->label('Total Diskon')->numeric()->readOnly()->prefix('Rp'),
                                            TextInput::make('shiping_cost')->label('Ongkos Kirim')->numeric()->readOnly()->prefix('Rp'),
                                            TextInput::make('grand_total')->label('Grand Total')->numeric()->readOnly()->prefix('Rp'),
                                        ]),

                                    Section::make('Item Produk')
                                        ->icon(Heroicon::ShoppingBag)
                                        ->schema([
                                            TextEntry::make('items_summary')
                                                ->label('Produk Dipilih')
                                                ->listWithLineBreaks()
                                                ->state(function (Get $get): array {
                                                    $items = $get('transactionItems') ?? [];
                                                    if (empty($items)) return ['Belum ada produk ditambahkan.'];
                                                    $lines = [];
                                                    foreach ($items as $item) {
                                                        $productId   = $item['product_id'] ?? null;
                                                        $qty         = (int) ($item['qty'] ?? 0);
                                                        $subtotal    = (float) ($item['subtotal'] ?? 0);
                                                        $productName = $productId
                                                            ? (Product::find($productId)?->name ?? 'Produk tidak ditemukan')
                                                            : '—';
                                                        $lines[] = "• {$productName} × {$qty} = Rp " . number_format($subtotal, 0, ',', '.');
                                                    }
                                                    return $lines;
                                                }),
                                        ]),
                                ]),

                            Section::make('Pembayaran')
                                ->description('Isi detail pembayaran untuk transaksi ini.')
                                ->icon(Heroicon::CreditCard)
                                ->columns(3)
                                ->schema([
                                    // ✅ dehydrated(true) pada semua field virtual payment
                                    Select::make('payment_method')
                                        ->label('Metode Pembayaran')
                                        ->options(
                                            collect(TransactionPaymentMethodEnum::cases())
                                                ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                                ->toArray()
                                        )
                                        ->required()
                                        ->native(false)
                                        ->dehydrated(true),

                                    TextInput::make('payment_amount')
                                        ->label('Jumlah Bayar')
                                        ->numeric()
                                        ->required()
                                        ->prefix('Rp')
                                        ->dehydrated(true),

                                    Select::make('payment_status')
                                        ->label('Status Pembayaran')
                                        ->options(
                                            collect(TransactionPaymentStatusEnum::cases())
                                                ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                                ->toArray()
                                        )
                                        ->required()
                                        ->native(false)
                                        ->dehydrated(true),
                                ]),
                        ]),

                ])
                    ->skippable(false)
                    ->columnSpanFull(),
            ]);
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

        $discountTotal = 0.0;
        $promoId       = $get('promo_id');
        if ($promoId) {
            $promo = Promo::find($promoId);
            if ($promo && $subtotal >= (float) ($promo->min_purchase ?? 0)) {
                $discountTotal = $promo->discount_type === PromoDiscountEnum::PERCENTAGE
                    ? round($subtotal * ($promo->discount_value / 100), 2)
                    : min((float) $promo->discount_value, $subtotal);
            }
        }

        $shippingCost = (float) ($get('shiping_cost') ?? 0);
        $set('subtotal',       round($subtotal, 2));
        $set('discount_total', round($discountTotal, 2));
        $set('grand_total',    round(max(0, $subtotal - $discountTotal + $shippingCost), 2));
    }
}
