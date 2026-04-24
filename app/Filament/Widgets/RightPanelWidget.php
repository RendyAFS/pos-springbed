<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\HasStoreFilter;
use App\Helpers\RupiahHelper;
use App\Models\InventoryStock;
use App\Models\Promo;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class RightPanelWidget extends Widget
{
    use HasStoreFilter;

    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 1;
    protected string $view = 'filament.widgets.right-panel-widget';

    public function getLowStockItems(): Collection
    {
        return $this->applyStoreFilter(
            InventoryStock::query()->with('product')->where('quantity', '<=', 5),
            'store_setting_id'
        )
            ->orderBy('quantity', 'asc')
            ->get();
    }

    // public function getActivePromotions(): Collection
    // {
    //     return $this->applyStoreFilter(
    //         Promo::query()
    //             ->where('is_active', true)
    //             ->where('start_date', '<=', Carbon::now())
    //             ->where('end_date', '>=', Carbon::now()),
    //         'store_setting_id'
    //     )
    //         ->orderBy('end_date', 'asc')
    //         ->get();
    // }

    public function getActivePromotions(): Collection
    {
        return Promo::query()
            ->where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now())
            ->orderBy('end_date', 'asc')
            ->get();
    }

    public function getPromoDescription(Promo $promo): string
    {
        if ($promo->discount_type?->value === 'percentage') {
            return $promo->discount_value . '% off';
        }

        if ($promo->discount_type?->value === 'nominal') {
            return RupiahHelper::format($promo->discount_value) . ' off';
        }

        return $promo->type?->getLabel() ?? '-';
    }

    public function getPromoIcon(Promo $promo): string
    {
        return match ($promo->type?->value) {
            'flash_sale'    => 'heroicon-o-bolt',
            'free_shipping' => 'heroicon-o-truck',
            default         => 'heroicon-o-tag',
        };
    }
}
