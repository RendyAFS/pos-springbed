<?php

namespace App\Filament\Resources\Transactions\Schemas\Steps;

use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Filament\Resources\Transactions\Schemas\Concerns\HasTotalsCalculation;
use App\Helpers\RupiahHelper;
use App\Models\Bundle;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Referal;
use App\Models\StoreSetting;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;

class SummaryStep
{
    use HasTotalsCalculation;

    public static function make(): Step
    {
        return Step::make('Summary')
            ->label('Ringkasan')
            ->description('Ulasan & konfirmasi transaksi')
            ->icon(Heroicon::ClipboardDocumentCheck)
            ->completedIcon(Heroicon::CheckBadge)
            ->schema([
                Grid::make(2)->schema([
                    Grid::make(1)->schema([
                        self::priceBreakdownSection(),
                        self::orderDetailSection(),
                    ]),
                    Grid::make(1)->schema([
                        self::referalSection(),
                        self::referalDiscountSection(),
                    ]),
                ]),
                self::paymentSection(),
            ]);
    }

    protected static function priceBreakdownSection(): Section
    {
        return Section::make('Rincian Harga')
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
            ]);
    }

    protected static function orderDetailSection(): Section
    {
        return Section::make('Detail Pesanan')
            ->icon(Heroicon::ShoppingBag)
            ->schema([
                TextEntry::make('items_summary')
                    ->label('Produk Dipilih')
                    ->listWithLineBreaks()
                    ->state(fn(Get $get): array => self::buildItemsSummary($get)),
            ]);
    }

    protected static function buildItemsSummary(Get $get): array
    {
        $items = $get('transactionItems') ?? [];
        if (empty($items)) {
            return ['Tidak ada produk yang ditambahkan.'];
        }

        $lines = [];
        foreach ($items as $item) {
            $lines[] = self::formatItemSummaryLine($item);
        }

        $discountReferal = self::parseCurrency($get('use_discount_referal') ?? 0);
        if ($discountReferal > 0) {
            $lines[] = '──────────────────────────';
            $lines[] = '🎁 Discount Referal: − Rp ' . number_format($discountReferal, 0, ',', '.');
        }

        return $lines;
    }

    protected static function formatItemSummaryLine(array $item): string
    {
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

        $multiStorePart = '';
        if (! empty($item['is_multi_store']) && ! empty($item['source_stores'])) {
            $storeParts = collect($item['source_stores'])
                ->filter(fn($s) => ! empty($s['store_setting_id']) && (int) ($s['qty'] ?? 0) > 0)
                ->map(function ($s) {
                    $storeName = StoreSetting::find($s['store_setting_id'])?->store_name ?? 'Unknown Store';
                    return "{$storeName}: {$s['qty']} pcs";
                })
                ->implode(' + ');

            $multiStorePart = $storeParts ? " [Multi-store: {$storeParts}]" : '';
        }

        return "• {$name} * {$qty} @ Rp " . number_format($sellingPrice, 0, ',', '.') . "{$discountPart}{$multiStorePart} = Rp " . number_format($subtotal, 0, ',', '.');
    }

    protected static function referalSection(): Section
    {
        return Section::make('Referal')
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
            ]);
    }

    protected static function referalDiscountSection(): Section
    {
        return Section::make('Discount Referal')
            ->description('Gunakan saldo referal milik customer ini untuk potongan harga.')
            ->icon(Heroicon::GiftTop)
            ->visible(function (Get $get): bool {
                $customerId = $get('customer_id');
                if (! $customerId) {
                    return false;
                }

                $referal = Referal::where('customer_id', $customerId)->first();
                return $referal && $referal->discount_amount > 0;
            })
            ->schema([
                TextEntry::make('referal_balance_info')
                    ->label('Saldo Referal Anda')
                    ->state(function (Get $get): string {
                        $customerId = $get('customer_id');
                        if (! $customerId) {
                            return '—';
                        }

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
                            $set('use_discount_referal', number_format($maxUsage, 0, ',', '.'));
                            $state = $maxUsage;
                        }

                        if ($customerId) {
                            $referal = Referal::where('customer_id', $customerId)->first();

                            if ($referal && $state > $referal->discount_amount) {
                                $set('use_discount_referal', number_format($referal->discount_amount, 0, ',', '.'));
                            }
                        }

                        self::recalculateTotals($get, $set);
                    })
                    ->helperText(function (Get $get): string {
                        $customerId = $get('customer_id');
                        if (! $customerId) {
                            return '';
                        }

                        $referal   = Referal::where('customer_id', $customerId)->first();
                        $balance   = $referal ? (float) $referal->discount_amount : 0;
                        $maxReward = self::getMaxReward($get);
                        $maxUse    = min($maxReward, $balance);

                        return 'Maksimal penggunaan: ' . RupiahHelper::format($maxUse)
                            . '. Saldo saat ini: ' . RupiahHelper::format($balance);
                    }),
            ]);
    }

    protected static function paymentSection(): Section
    {
        return Section::make('Pembayaran')
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
                    ->live(onBlur: true)
                    ->afterStateHydrated(function (TextInput $component, $record): void {
                        if ($record && $record->transactionPayment) {
                            $amount = (float) $record->transactionPayment->amount;
                            $component->state(number_format($amount, 0, ',', '.'));
                        }
                    })
                    ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                        $amount     = self::parseCurrency($state);
                        $grandTotal = self::parseCurrency($get('grand_total'));

                        // Jika dibayar kurang dari total, otomatis tandai sebagai Down Payment.
                        // Jika sudah dibayar penuh/lebih, otomatis lepas tanda Down Payment.
                        // Toggle tetap bisa diubah manual oleh user kapan pun.
                        $set('is_down_payment', $grandTotal > 0 && $amount < $grandTotal);
                    })
                    ->helperText('Otomatis terisi sesuai Grand Total. Ubah manual jika ini pembayaran Down Payment.'),
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
                    ->default(false)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set, $state): void {
                        // Kalau user manual matikan toggle, jumlah terbayar
                        // langsung disinkronkan ulang ke Grand Total.
                        if (! $state) {
                            self::recalculateTotals($get, $set);
                        }
                    }),
                DateTimePicker::make('due_date_down_payment')
                    ->label('Jatuh Tempo Down Payment')
                    ->native(false)
                    ->suffixIcon(Heroicon::Calendar)
                    ->closeOnDateSelection()
                    ->nullable(),
            ]);
    }
}
