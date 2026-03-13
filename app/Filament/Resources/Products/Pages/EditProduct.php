<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\InventoryStock;
use App\Models\StockAdjustment;
use App\Services\StockAdjustmentService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $data = $this->form->getState()['stock_adjustment_temp'] ?? null;

        if (!$data) {
            return;
        }

        $product = $this->record;

        $stock = InventoryStock::where([
            'product_id' => $product->id,
            'store_setting_id' => $data['store_setting_id']
        ])->first();

        $qtyBefore = $stock?->quantity ?? 0;
        $qtyAfter = $data['quantity'];

        $adjustment = StockAdjustment::create([
            'store_setting_id' => $data['store_setting_id'],
            'product_id' => $product->id,
            'qty_before' => $qtyBefore,
            'qty_after' => $qtyAfter,
            'qty_difference' => $qtyAfter - $qtyBefore,
            'reason' => $data['reason'],
        ]);

        app(StockAdjustmentService::class)->adjust($adjustment);
    }
}
