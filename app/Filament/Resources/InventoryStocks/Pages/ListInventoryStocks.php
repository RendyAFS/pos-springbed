<?php

namespace App\Filament\Resources\InventoryStocks\Pages;

use App\Filament\Resources\InventoryStocks\InventoryStockResource;
use App\Filament\Resources\InventoryStocks\Widgets\InventoryStatsWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInventoryStocks extends ListRecords
{
    protected static string $resource = InventoryStockResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         CreateAction::make(),
    //     ];
    // }

    public ?int $filterStoreId = null;

    protected function getHeaderWidgets(): array
    {
        return [
            InventoryStatsWidget::class,
        ];
    }

    public function getHeaderWidgetsData(): array
    {
        $storeId = $this->tableFilters['store_setting_id']['value'] ?? null;

        return [
            InventoryStatsWidget::class => [
                'filterStoreId' => $storeId ? (int) $storeId : null,
            ],
        ];
    }
}
