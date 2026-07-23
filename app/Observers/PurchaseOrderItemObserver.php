<?php

namespace App\Observers;

use App\Models\PurchaseOrderItem;
use App\Models\Product;

class PurchaseOrderItemObserver
{
    public function saved(PurchaseOrderItem $item): void
    {
        if (!$item->product_id) {
            return;
        }

        $product = Product::find($item->product_id);

        if (!$product) {
            return;
        }

        if (bccomp((string) $product->cost_price, (string) $item->cost_price, 2) !== 0) {
            $product->update(['cost_price' => $item->cost_price]);
        }
    }
}
