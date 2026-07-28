<?php

namespace App\Filament\Widgets\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait HasDashboardFilters
{
    protected function applyChannelFilter(Builder $query, string $column = 'channel_sale_id'): Builder
    {
        $channelIds = $this->filters['channel_sale_ids'] ?? null;

        if (! empty($channelIds)) {
            $query->whereIn($column, $channelIds);
        }

        return $query;
    }

    protected function applyDateRangeFilter(Builder $query, string $column = 'transaction_date'): Builder
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate   = $this->filters['endDate'] ?? null;

        if ($startDate) {
            $query->where($column, '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate) {
            $query->where($column, '<=', Carbon::parse($endDate)->endOfDay());
        }

        return $query;
    }

    protected function getFilterDateRange(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate   = $this->filters['endDate'] ?? null;

        return [
            $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfMonth(),
            $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay(),
        ];
    }
}
