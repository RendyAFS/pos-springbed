<?php

namespace App\Services;

use App\Models\InventoryStock;
use App\Models\StockMovement;
use App\Enums\TypeStockMovementEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    private function getStoreId(): int
    {
        return Auth::user()->store_setting_id;
    }

    public function increaseStock(
        int $productId,
        int $qty,
        Model $reference,
        ?float $costPrice = null
    ): void {

        $storeId = $this->getStoreId();

        DB::transaction(function () use ($productId, $storeId, $qty, $reference, $costPrice) {

            $stock = InventoryStock::firstOrCreate(
                [
                    'product_id' => $productId,
                    'store_setting_id' => $storeId,
                ],
                [
                    'quantity' => 0
                ]
            );

            $stock->increment('quantity', $qty);

            StockMovement::create([
                'product_id' => $productId,
                'store_setting_id' => $storeId,
                'type' => TypeStockMovementEnum::IN,
                'qty' => $qty,
                'cost_price_snapshot' => $costPrice,
                'reference_type' => $reference::class,
                'reference_id' => $reference->id,
            ]);
        });
    }

    public function decreaseStock(
        int $productId,
        int $qty,
        Model $reference
    ): void {

        $storeId = $this->getStoreId();

        DB::transaction(function () use ($productId, $storeId, $qty, $reference) {

            $stock = InventoryStock::where([
                'product_id' => $productId,
                'store_setting_id' => $storeId
            ])->lockForUpdate()->first();

            if (!$stock || $stock->quantity < $qty) {
                throw new \Exception('Stock tidak cukup');
            }

            $stock->decrement('quantity', $qty);

            StockMovement::create([
                'product_id' => $productId,
                'store_setting_id' => $storeId,
                'type' => TypeStockMovementEnum::OUT,
                'qty' => $qty,
                'reference_type' => $reference::class,
                'reference_id' => $reference->id,
            ]);
        });
    }

    public function adjustStock(
        int $productId,
        int $difference,
        Model $reference
    ): void {

        $storeId = $this->getStoreId();

        DB::transaction(function () use ($productId, $storeId, $difference, $reference) {

            $stock = InventoryStock::firstOrCreate(
                [
                    'product_id' => $productId,
                    'store_setting_id' => $storeId,
                ],
                [
                    'quantity' => 0
                ]
            );

            $stock->increment('quantity', $difference);

            StockMovement::create([
                'product_id' => $productId,
                'store_setting_id' => $storeId,
                'type' => TypeStockMovementEnum::ADJUSTMENT,
                'qty' => $difference,
                'reference_type' => $reference::class,
                'reference_id' => $reference->id,
            ]);
        });
    }
}
