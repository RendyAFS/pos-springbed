<?php

namespace App\Services;

use App\Models\PurchaseOrder;

class PurchaseOrderService
{
    public function receiveStock(PurchaseOrder $purchaseOrder): void
    {
        $inventoryService = app(InventoryService::class);

        foreach ($purchaseOrder->purchaseOrderItems as $item) {
            $item->update([
                'qty_remaining' => $item->qty_purchased,
            ]);

            $inventoryService->increaseStock(
                $item->product_id,
                $item->qty_purchased,
                $purchaseOrder,
                $item->cost_price
            );
        }
    }

    public function syncStock(PurchaseOrder $purchaseOrder, array $originalItems): void
    {
        $inventoryService = app(InventoryService::class);
        $currentItems = $purchaseOrder->purchaseOrderItems()->get();
        $currentIds = $currentItems->pluck('id')->toArray();

        foreach ($currentItems as $item) {
            $old = $originalItems[$item->id] ?? null;
            $oldQty = $old['qty_purchased'] ?? 0;
            $newQty = $item->qty_purchased;
            $diff = $newQty - $oldQty;

            if ($diff !== 0) {
                $inventoryService->adjustStock($item->product_id, $diff, $purchaseOrder);
            }

            $newRemaining = $old === null
                ? $newQty
                : max(0, $item->qty_remaining + $diff);

            $item->update(['qty_remaining' => $newRemaining]);
        }

        foreach ($originalItems as $id => $old) {
            if (!in_array($id, $currentIds)) {
                $inventoryService->adjustStock($old['product_id'], -$old['qty_purchased'], $purchaseOrder);
            }
        }
    }

    public function revertStock(PurchaseOrder $purchaseOrder): void
    {
        $inventoryService = app(InventoryService::class);

        foreach ($purchaseOrder->purchaseOrderItems as $item) {
            $inventoryService->adjustStock($item->product_id, -$item->qty_purchased, $purchaseOrder);
        }
    }
}
