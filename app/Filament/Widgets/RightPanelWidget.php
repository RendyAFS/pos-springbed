<?php

namespace App\Filament\Widgets;

use App\Models\InventoryStock;
use App\Models\Promo;
use App\Helpers\RupiahHelper;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class RightPanelWidget extends Widget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected string $view = 'filament.widgets.right-panel-widget';

    public function getLowStockItems(): \Illuminate\Database\Eloquent\Collection
    {
        return InventoryStock::query()
            ->with('product')
            ->where('quantity', '<=', 5)
            ->orderBy('quantity', 'asc')
            ->get();
    }

    public function getActivePromotions(): \Illuminate\Database\Eloquent\Collection
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
