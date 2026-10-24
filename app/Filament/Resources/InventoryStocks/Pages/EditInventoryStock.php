<?php

namespace App\Filament\Resources\InventoryStocks\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\InventoryStocks\InventoryStockResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditInventoryStock extends EditRecord
{
    use HasBackAction;

    protected static string $resource = InventoryStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackAction(),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
