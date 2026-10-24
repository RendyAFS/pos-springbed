<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Services\PurchaseOrderService;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrder extends CreateRecord
{
    use HasBackAction;

    protected static string $resource = PurchaseOrderResource::class;

    protected static ?string $title = 'Buat Pesanan Pembelian';

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackAction(),
        ];
    }

    protected function afterCreate(): void
    {
        app(PurchaseOrderService::class)->receiveStock($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
