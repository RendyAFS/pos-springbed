<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\InventoryStock;
use App\Models\StockAdjustment;
use App\Services\StockAdjustmentService;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $data = $this->form->getState()['stock_adjustment_temp'] ?? null;

        if (!$data) return;

        $product = $this->record;

        // Support both old format (single array) and new format (array of stocks)
        $stocks = isset($data[0]) ? $data : [$data];

        foreach ($stocks as $item) {
            if (empty($item['store_setting_id'])) continue;

            $stock = InventoryStock::where([
                'product_id'       => $product->id,
                'store_setting_id' => $item['store_setting_id'],
            ])->first();

            $qtyBefore  = $stock?->quantity ?? 0;
            $qtyAfter   = (int) $item['quantity'];
            $difference = $qtyAfter - $qtyBefore;

            if ($difference === 0) continue;

            $adjustment = StockAdjustment::create([
                'store_setting_id' => $item['store_setting_id'],
                'product_id'       => $product->id,
                'qty_before'       => $qtyBefore,
                'qty_after'        => $qtyAfter,
                'qty_difference'   => $difference,
                'reason'           => $item['reason'],
            ]);

            app(StockAdjustmentService::class)->adjust($adjustment);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
