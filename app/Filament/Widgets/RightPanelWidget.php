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

    private ?Collection $lowStockCache    = null;
    private ?Collection $promotionsCache  = null;

    public function getLowStockItems(): Collection
    {
        if ($this->lowStockCache !== null) {
            return $this->lowStockCache;
        }

        return $this->lowStockCache = $this->applyStoreFilter(
            InventoryStock::query()
                ->select(['id', 'store_setting_id', 'product_id', 'quantity'])
                ->with([
                    'product:id,name,sku',
                ])
                ->where('quantity', '<=', 5),
            'store_setting_id'
        )
            ->orderBy('quantity', 'asc')
            ->limit(20)
            ->get();
    }

    public function getActivePromotions(): Collection
    {
        if ($this->promotionsCache !== null) {
            return $this->promotionsCache;
        }

        $now = Carbon::now();

        return $this->promotionsCache = Promo::query()
            ->select(['id', 'name', 'type', 'discount_type', 'discount_value', 'usage_count', 'usage_limit', 'end_date'])
            ->where('is_active', true)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderBy('end_date', 'asc')
            ->limit(10)
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
