<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class UserStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $baseQuery = User::query()
            ->whereDoesntHave('roles', function (Builder $query) {
                $query->where('name', 'Super Admin');
            });

        return [
            Stat::make('Total User', (clone $baseQuery)->count())
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->description('Users')
                ->color('primary'),

            Stat::make('Total Active',
                (clone $baseQuery)
                    ->where('is_active', true)
                    ->count()
            )
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before)
                ->description('Active users')
                ->color('success'),

            Stat::make('Total Admin',
                (clone $baseQuery)
                    ->whereHas('roles', function (Builder $query) {
                        $query->where('name', 'Admin');
                    })
                    ->count()
            )
                ->descriptionIcon('heroicon-o-shield-check', IconPosition::Before)
                ->description('Admin users')
                ->color('warning'),
        ];
    }
}
