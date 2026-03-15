<?php

namespace App\Services;

use App\Models\PurchaseOrder;

class PurchaseOrderService
{
    public function receiveStock(PurchaseOrder $purchaseOrder)
    {
        $inventoryService = app(InventoryService::class);

        foreach ($purchaseOrder->purchaseOrderItems as $item) {

            $item->update([
                'qty_remaining' => $item->qty_purchased
            ]);

            $inventoryService->increaseStock(
                $item->product_id,
                $item->qty_purchased,
                $purchaseOrder,
                $item->cost_price
            );
        }
    }
}
