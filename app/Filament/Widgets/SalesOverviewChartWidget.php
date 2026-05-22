<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionStatusEnum;
use App\Filament\Widgets\Concerns\HasStoreFilter;
use App\Models\Transaction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Illuminate\Support\Carbon;

class SalesOverviewChartWidget extends ChartWidget
{
    use HasFiltersSchema;
    use HasStoreFilter;

    protected static ?int $sort            = 2;
    protected ?string $heading             = 'Ringkasan Penjualan';
    protected string $color                = 'primary';
    protected int|string|array $columnSpan = 2;
    protected ?string $pollingInterval     = '30s';

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('period')
                ->label('Periode')
                ->native(false)
                ->options([
                    'daily'   => 'Harian',
                    'monthly' => 'Bulanan',
                ])
                ->default('daily')
                ->live()
                ->afterStateUpdated(function ($state) {
                    if ($state === 'daily') {
                        $this->filters['startDate'] = now()->subDays(6)->format('Y-F-d');
                        $this->filters['endDate']   = now()->format('Y-F-d');
                    } else {
                        $this->filters['startDate'] = now()->subMonths(11)->startOfMonth()->format('Y-F-d');
                        $this->filters['endDate']   = now()->format('Y-F-d');
                    }
                }),

            DatePicker::make('startDate')
                ->label('Tanggal Mulai')
                ->default(now()->subDays(6))
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
        ]);
    }

    private function baseQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->applyStoreFilter(
            Transaction::query()->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
        );
    }

    protected function getData(): array
    {
        $period = $this->filters['period'] ?? 'daily';

        $startDate = isset($this->filters['startDate'])
            ? Carbon::parse($this->filters['startDate'])->startOfDay()
            : ($period === 'daily'
                ? Carbon::now()->subDays(6)->startOfDay()
                : Carbon::now()->subMonths(11)->startOfMonth()->startOfDay());

        $endDate = isset($this->filters['endDate'])
            ? Carbon::parse($this->filters['endDate'])->endOfDay()
            : Carbon::now()->endOfDay();

        if ($period === 'daily') {
            $rows = $this->baseQuery()
                ->selectRaw('DATE(transaction_date) AS period_key, SUM(grand_total) AS total')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->groupByRaw('DATE(transaction_date)')
                ->pluck('total', 'period_key');

            $labels = [];
            $data   = [];
            $current = $startDate->copy();
            while ($current->lte($endDate)) {
                $key      = $current->toDateString();
                $labels[] = $current->translatedFormat('d F');
                $data[]   = (float) ($rows[$key] ?? 0);
                $current->addDay();
            }
        } else {
            $rows = $this->baseQuery()
                ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') AS period_key, SUM(grand_total) AS total")
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->groupByRaw("DATE_FORMAT(transaction_date, '%Y-%m')")
                ->pluck('total', 'period_key');

            $labels  = [];
            $data    = [];
            $current = $startDate->copy()->startOfMonth();
            $end     = $endDate->copy()->startOfMonth();
            while ($current->lte($end)) {
                $key      = $current->format('Y-F');
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
