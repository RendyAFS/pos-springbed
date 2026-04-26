<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RightPanelWidget;
use App\Filament\Widgets\SalesOverviewChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            SalesOverviewChartWidget::class,
            RightPanelWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 3;
    }
}
