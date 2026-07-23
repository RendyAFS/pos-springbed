<?php

namespace App\Filament\Resources\Transactions\Schemas\Steps;

use App\Enums\PromoDiscountEnum;
use App\Filament\Resources\Transactions\Schemas\Concerns\HasTotalsCalculation;
use App\Models\Courier;
use App\Models\Promo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;

class PromoShippingStep
{
    use HasTotalsCalculation;

    public static function make(): Step
    {
        return Step::make('Promo & Shipping')
            ->label('Promo & Pengiriman')
            ->description('Terapkan promo dan pilih kurir')
            ->icon(Heroicon::Truck)
            ->completedIcon(Heroicon::CheckCircle)
            ->schema([
                Grid::make(2)->schema([
                    self::promoSection(),
                    self::shippingSection(),
                ]),
            ]);
    }

    protected static function promoSection(): Section
    {
        return Section::make('Promo / Voucher')
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
                        if (! $promoId) {
                            return '—';
                        }
                        $promo = Promo::find($promoId);
                        if (! $promo) {
                            return '—';
                        }

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
            ]);
    }

    protected static function shippingSection(): Section
    {
        return Section::make('Shipping')
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
            ]);
    }
}
