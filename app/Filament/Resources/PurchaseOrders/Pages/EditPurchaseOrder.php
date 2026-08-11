<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Services\PurchaseOrderService;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected static ?string $title = 'Edit Pesanan Pembelian';

    protected array $originalItems = [];

    protected function beforeSave(): void
    {
        $this->originalItems = $this->record->purchaseOrderItems()
            ->get(['id', 'product_id', 'qty_purchased'])
            ->keyBy('id')
            ->map(fn($item) => $item->toArray())
            ->toArray();
    }

    protected function afterSave(): void
    {
        app(PurchaseOrderService::class)->syncStock($this->record, $this->originalItems);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
