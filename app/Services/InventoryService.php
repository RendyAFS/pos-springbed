<?php

namespace App\Services;

use App\Models\InventoryStock;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use App\Models\TransactionItemCost;
use App\Enums\TypeStockMovementEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryService
{
    private function getStoreId(Model $reference): int
    {
        if (!isset($reference->store_setting_id)) {
            throw new Exception('Reference tidak memiliki store_setting_id');
        }

        return $reference->store_setting_id;
    }

    private function ensureProductStore(int $productId, int $storeId): void
    {
        Product::where('id', $productId)
            ->whereNull('store_setting_id')
            ->update([
                'store_setting_id' => $storeId,
            ]);
    }

    private function validateQty(int $qty): void
    {
        if ($qty <= 0) {
            throw new Exception('Qty harus lebih dari 0');
        }
    }

    private function getStock(int $productId, int $storeId): InventoryStock
    {
        $stock = InventoryStock::where([
            'product_id' => $productId,
            'store_setting_id' => $storeId,
        ])
            ->lockForUpdate()
            ->first();

        if (!$stock) {
            $stock = InventoryStock::create([
                'product_id' => $productId,
                'store_setting_id' => $storeId,
                'quantity' => 0,
            ]);
        }

        return $stock;
    }

    public function increaseStock(
        int $productId,
        int $qty,
        Model $reference,
        ?float $costPrice = null
    ): void {

        $this->validateQty($qty);

        DB::transaction(function () use ($productId, $qty, $reference, $costPrice) {

            $storeId = $this->getStoreId($reference);
            $this->ensureProductStore($productId, $storeId);

            $stock = $this->getStock($productId, $storeId);

            $stock->quantity += $qty;
            $stock->save();

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

        $this->validateQty($qty);

        DB::transaction(function () use ($productId, $qty, $reference) {

            $storeId = $this->getStoreId($reference);
            $this->ensureProductStore($productId, $storeId);

            $stock = $this->getStock($productId, $storeId);

            if ($stock->quantity < $qty) {
                throw new Exception('Stock tidak cukup');
            }

            $stock->quantity -= $qty;
            $stock->save();

            $this->consumeFIFO($productId, $qty, $reference);

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

        DB::transaction(function () use ($productId, $difference, $reference) {

            $storeId = $this->getStoreId($reference);
            $this->ensureProductStore($productId, $storeId);

            $stock = InventoryStock::where([
                'product_id' => $productId,
                'store_setting_id' => $storeId,
            ])
                ->lockForUpdate()
                ->first();

            $qtyBefore = $stock?->quantity ?? 0;
            $qtyAfter = $qtyBefore + $difference;

            if ($qtyAfter < 0) {
                throw new Exception('Stock tidak boleh minus');
            }

            if ($stock) {
                $stock->delete();
            }

            InventoryStock::create([
                'product_id' => $productId,
                'store_setting_id' => $storeId,
                'quantity' => $qtyAfter,
            ]);

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

    /**
     * FIFO consumption
     */
    private function consumeFIFO(
        int $productId,
        int $qty,
        Model $reference
    ): void {

        $storeId = $this->getStoreId($reference);
        $remaining = $qty;

        $purchaseItems = PurchaseOrderItem::where('product_id', $productId)
            ->where('qty_remaining', '>', 0)
            ->whereHas('purchaseOrder', function ($q) use ($storeId) {
                $q->where('store_setting_id', $storeId);
            })
            ->orderBy('created_at')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();


        /** @var PurchaseOrderItem $purchaseItem */
        foreach ($purchaseItems as $purchaseItem) {

            if ($remaining <= 0) {
                break;
            }

            $available = $purchaseItem->qty_remaining;
            $deduct = min($available, $remaining);
            $purchaseItem->qty_remaining -= $deduct;
            $purchaseItem->save();

            TransactionItemCost::create([
                'purchase_order_item_id' => $purchaseItem->id,
                'transaction_item_id' => $reference->id,
                'qty' => $deduct,
                'cost_price' => $purchaseItem->cost_price,
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            throw new Exception('FIFO stock tidak cukup');
        }
    }
}
