<?php

namespace App\Services;

use App\Models\PurchaseOrder;

class PurchaseOrderService
{
    public function receiveStock(PurchaseOrder $purchaseOrder)
    {
        $inventoryService = app(InventoryService::class);

        foreach ($purchaseOrder->purchaseOrderItems as $purchaseOrderItem) {

            $inventoryService->increaseStock(
                $purchaseOrderItem->product_id,
                $purchaseOrderItem->qty_purchased,
                $purchaseOrder,
                $purchaseOrderItem->cost_price
            );
        }
    }
}
