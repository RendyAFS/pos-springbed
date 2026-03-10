<?php

namespace App\Services;

use App\Models\StockAdjustment;

class StockAdjustmentService
{
    public function adjust(StockAdjustment $adjustment)
    {
        $inventoryService = app(InventoryService::class);

        $inventoryService->adjustStock(
            $adjustment->product_id,
            $adjustment->store_setting_id,
            $adjustment->qty_difference,
            $adjustment
        );
    }
}
