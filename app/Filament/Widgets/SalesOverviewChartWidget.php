<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionStatusEnum;
use App\Filament\Widgets\Concerns\HasDashboardFilters;
use App\Filament\Widgets\Concerns\HasStoreFilter;
use App\Models\Transaction;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class SalesOverviewChartWidget extends ChartWidget
{
    use HasStoreFilter;
    use HasDashboardFilters;
    use InteractsWithPageFilters;

    protected static ?int $sort            = 2;
    protected ?string $heading             = 'Ringkasan Penjualan';
    protected string $color                = 'primary';
    protected int|string|array $columnSpan = 2;
    protected ?string $pollingInterval     = '30s';

    private function baseQuery(): Builder
    {
        return $this->applyDateRangeFilter(
            $this->applyChannelFilter(
                $this->applyStoreFilter(
                    Transaction::query()->whereNotIn('status', [TransactionStatusEnum::CANCELLED]),
                    'store_setting_id',
                    true
                )
            )
        );
    }

    /**
     * Resolve daily/weekly/monthly grouping from the "chart_period" filter.
     */
    private function resolvePeriod(): string
    {
        $period = $this->filters['chart_period'] ?? 'daily';

        return in_array($period, ['daily', 'weekly', 'monthly'], true) ? $period : 'daily';
    }

    protected function getData(): array
    {
        [$startDate, $endDate] = $this->getFilterDateRange();

        $period = $this->resolvePeriod();

        if ($period === 'daily') {
            $rows = $this->baseQuery()
                ->selectRaw('DATE(transaction_date) AS period_key, SUM(grand_total) AS total')
                ->groupByRaw('DATE(transaction_date)')
                ->pluck('total', 'period_key');

            $labels  = [];
            $data    = [];
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                $key      = $current->toDateString();
                $labels[] = $current->translatedFormat('d F');
                $data[]   = (float) ($rows[$key] ?? 0);
                $current->addDay();
            }
        } elseif ($period === 'weekly') {
            $rows = $this->baseQuery()
                ->selectRaw('YEARWEEK(transaction_date, 1) AS period_key, SUM(grand_total) AS total')
                ->groupByRaw('YEARWEEK(transaction_date, 1)')
                ->pluck('total', 'period_key');

            $labels     = [];
            $data       = [];
            $current    = $startDate->copy()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
            $end        = $endDate->copy()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
            $weekNumber = 1;
            while ($current->lte($end)) {
                $key      = (int) $current->format('oW');
                $labels[] = 'W-' . $weekNumber;
                $data[]   = (float) ($rows[$key] ?? 0);
                $current->addWeek();
                $weekNumber++;
            }
        } else {
            $rows = $this->baseQuery()
                ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') AS period_key, SUM(grand_total) AS total")
                ->groupByRaw("DATE_FORMAT(transaction_date, '%Y-%m')")
                ->pluck('total', 'period_key');

            $labels  = [];
            $data    = [];
            $current = $startDate->copy()->startOfMonth();
            $end     = $endDate->copy()->startOfMonth();
            while ($current->lte($end)) {
                $key      = $current->format('Y-m');
                $labels[] = $current->translatedFormat('F Y');
                $data[]   = (float) ($rows[$key] ?? 0);
                $current->addMonth();
            }
        }

        return [
            'datasets' => [[
                'label'                     => 'Penjualan (Rp)',
                'data'                      => $data,
                'fill'                      => true,
                'tension'                   => 0.4,
                'pointRadius'               => 5,
                'pointHoverRadius'          => 8,
                'pointHoverBackgroundColor' => '#ffffff',
                'pointHoverBorderWidth'     => 3,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                interaction: { mode: 'index', intersect: false },
                hover: { mode: 'index', intersect: false },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 12 }, maxRotation: 45 } },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4] },
                        ticks: {
                            callback: (value) => {
                                if (value >= 1000000000) return (value / 1000000000) + 'M';
                                if (value >= 1000000)    return (value / 1000000) + 'JT';
                                if (value >= 1000)       return (value / 1000) + 'K';
                                return value;
                            },
                        },
                    },
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#f9fafb',
                        bodyColor: '#d1d5db',
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            title: (items) => items[0]?.label ?? '',
                            label: (context) => '  Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y),
                        },
                    },
                },
            }
        JS);
    }
}
