<?php

namespace App\Filament\Resources\InventoryStocks\Widgets;

use App\Helpers\RupiahHelper;
use App\Models\InventoryStock;
use App\Models\StoreSetting;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class InventoryStatsWidget extends StatsOverviewWidget
{
    public ?int $filterStoreId = null;

    protected function getStats(): array
    {
        $userStoreId = Auth::user()?->store_setting_id;


        $storeId = $userStoreId ?? ($this->filterStoreId ?: null);

        $query = InventoryStock::query()
            ->with('product')
            ->when($storeId, fn($q) => $q->where('store_setting_id', $storeId));

        $totalProducts = (clone $query)->count();
        $lowStockCount = (clone $query)->where('quantity', '<=', 10)->count();
        $totalValue    = (clone $query)->get()
            ->sum(fn($s) => $s->quantity * ($s->product?->selling_price ?? 0));

        $storeLabel = $storeId
            ? StoreSetting::find($storeId)?->store_name ?? 'Store'
            : 'All Stores';

        return [
            Stat::make('Total Products', $totalProducts)
                ->description($storeLabel)
                ->descriptionIcon('heroicon-o-square-3-stack-3d')
                ->color('primary'),

            Stat::make('Low Stock', $lowStockCount)
                ->description('Items with stock ≤ 10')
                ->descriptionIcon('heroicon-o-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'warning' : 'success'),

            Stat::make('Total Inventory Value', RupiahHelper::format($totalValue))
                ->description('Based on selling price · ' . $storeLabel)
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
