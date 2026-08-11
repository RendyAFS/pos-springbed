<?php

namespace App\Filament\Resources\Transactions\Schemas\Concerns;

use App\Models\Bundle;
use App\Models\InventoryStock;
use Filament\Schemas\Components\Utilities\Get;
use Illuminate\Support\Facades\Auth;

trait HasStockCalculations
{
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
}
