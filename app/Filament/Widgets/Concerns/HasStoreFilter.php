<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasStoreFilter
{
    protected function isSuperAdmin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        return $user?->hasRole('Super Admin') ?? false;
    }

    protected function isStaff(): bool
    {
        return Auth::user()?->roles->contains('id', 4) ?? false;
    }

    protected function getStoreSettingId(): ?int
    {
        return Auth::user()?->store_setting_id;
    }

    protected function applyStoreFilter(
        \Illuminate\Database\Eloquent\Builder $query,
        string $column = 'store_setting_id',
        bool $applyCreatedBy = false
    ): \Illuminate\Database\Eloquent\Builder {
        $storeId = $this->getStoreSettingId();

        if ($this->isSuperAdmin() && is_null($storeId)) {
            return $query;
        }

        $query = $query->where($column, $storeId);

        if ($applyCreatedBy && $this->isStaff()) {
            $query = $query->where('created_by', Auth::id());
        }

        return $query;
    }
}
