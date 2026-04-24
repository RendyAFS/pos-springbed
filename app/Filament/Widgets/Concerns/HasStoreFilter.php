<?php

namespace App\Filament\Widgets\Concerns;

trait HasStoreFilter
{
    protected function isSuperAdmin(): bool
    {
        return auth()->user()?->hasRole('Super Admin') ?? false;
    }

    protected function getStoreSettingId(): ?int
    {
        return auth()->user()?->store_setting_id;
    }

    protected function applyStoreFilter(\Illuminate\Database\Eloquent\Builder $query, string $column = 'store_setting_id'): \Illuminate\Database\Eloquent\Builder
    {
        $storeId = $this->getStoreSettingId();

        if ($this->isSuperAdmin() && is_null($storeId)) {
            return $query;
        }

        return $query->where($column, $storeId);
    }
}
