<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RightPanelWidget;
use App\Filament\Widgets\SalesOverviewChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Models\ChannelSale;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?string $navigationLabel = 'Dashboard';
    protected ?string $heading = 'Dashboard';

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

    public function mount(): void
    {
        $this->filters['startDate']    ??= now()->startOfMonth()->toDateString();
        $this->filters['endDate']      ??= now()->toDateString();
        $this->filters['chart_period'] ??= 'daily';
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        Select::make('channel_sale_ids')
                            ->label('Channel Penjualan')
                            ->multiple()
                            ->options(fn() => ChannelSale::query()->orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->native(false)
                            ->placeholder('Semua Channel'),

                        DatePicker::make('startDate')
                            ->label('Tanggal Mulai')
                            ->default(now()->startOfMonth())
                            ->maxDate(now())
                            ->native(false)
                            ->suffixIcon(Heroicon::Calendar)
                            ->closeOnDateSelection(),

                        DatePicker::make('endDate')
                            ->label('Tanggal Akhir')
                            ->default(now())
                            ->maxDate(now())
                            ->native(false)
                            ->suffixIcon(Heroicon::Calendar)
                            ->closeOnDateSelection(),

                        Select::make('chart_period')
                            ->label('Granularitas Chart')
                            ->native(false)
                            ->options([
                                'daily'   => 'Harian',
                                'weekly'  => 'Mingguan',
                                'monthly' => 'Bulanan',
                            ])
                            ->default('daily'),
                    ])
                    ->columns(4),
            ]);
    }
}
