<?php

namespace App\Filament\Resources\Transactions\Schemas\Concerns;

use App\Enums\PromoDiscountEnum;
use App\Models\Promo;
use App\Models\StoreSetting;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

trait HasTotalsCalculation
{
    use HasCurrencyHelpers;
    use HasStockCalculations;

    protected static function recalculateItemSubtotal(Get $get, Set $set): void
    {
        $qty      = (float) ($get('qty') ?? 0);
        $price    = self::parseCurrency($get('selling_price'));
        $discount = self::parseCurrency($get('discount'));
        $subtotal = max(0, ($qty * $price) - $discount);
        $set('subtotal', number_format($subtotal, 0, ',', '.'));
    }

    protected static function recalculateTotals(Get $get, Set $set): void
    {
        $items = $get('transactionItems') ?? [];

        $subtotal = collect($items)->sum(function ($i) {
            $qty      = (float) ($i['qty'] ?? 0);
            $price    = self::parseCurrency($i['selling_price'] ?? 0);
            $discount = self::parseCurrency($i['discount'] ?? 0);
            return max(0, ($qty * $price) - $discount);
        });

        $promoTotal = 0;
        $promoId    = $get('promo_id');

        if ($promoId) {
            $promo = Promo::find($promoId);

            if ($promo && $subtotal >= (float) ($promo->min_purchase ?? 0)) {
                $promoTotal = $promo->discount_type === PromoDiscountEnum::PERCENTAGE
                    ? round($subtotal * ($promo->discount_value / 100), 2)
                    : min((float) $promo->discount_value, $subtotal);
            }
        }

        $discountReferal = self::parseCurrency($get('use_discount_referal') ?? 0);
        $shippingCost     = self::parseCurrency($get('shiping_cost') ?? 0);
        $grandTotal       = max(0, $subtotal - $promoTotal - $discountReferal + $shippingCost);

        $set('subtotal', number_format($subtotal, 0, ',', '.'));
        $set('promo_total', number_format($promoTotal, 0, ',', '.'));
        $set('grand_total', number_format($grandTotal, 0, ',', '.'));

        if (! (bool) $get('is_down_payment')) {
            $set('payment_amount', number_format($grandTotal, 0, ',', '.'));
        }
    }

    protected static function getMaxReward(Get $get): float
    {
        $storeId = self::getStoreId($get);
        if (! $storeId) {
            return 200000;
        }

        $store = StoreSetting::find($storeId);
        return (float) ($store?->set_max_reward ?? 200000);
    }
}
