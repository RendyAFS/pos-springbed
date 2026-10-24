<?php

namespace App\Filament\Resources\InventoryStocks\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\InventoryStocks\InventoryStockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInventoryStock extends CreateRecord
{
    use HasBackAction;

    protected static string $resource = InventoryStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
