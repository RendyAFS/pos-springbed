<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionStatusEnum;
use App\Filament\Widgets\Concerns\HasStoreFilter;
use App\Helpers\RupiahHelper;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    use HasStoreFilter;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today        = Carbon::today();
        $yesterday    = Carbon::yesterday();
        $thisMonth    = Carbon::now()->startOfMonth();
        $lastMonth    = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $row = $this->applyStoreFilter(
            Transaction::query()->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
        )
            ->selectRaw("
                SUM(CASE WHEN DATE(transaction_date) = ? THEN grand_total ELSE 0 END)           AS sales_today,
                SUM(CASE WHEN DATE(transaction_date) = ? THEN grand_total ELSE 0 END)           AS sales_yesterday,
                SUM(CASE WHEN transaction_date >= ? AND transaction_date <= NOW() THEN grand_total ELSE 0 END) AS sales_this_month,
                SUM(CASE WHEN transaction_date BETWEEN ? AND ? THEN grand_total ELSE 0 END)     AS sales_last_month,
                COUNT(*)                                                                         AS total_transactions,
                SUM(CASE WHEN transaction_date >= ? AND transaction_date <= NOW() THEN 1 ELSE 0 END) AS tx_this_month,
                SUM(CASE WHEN transaction_date BETWEEN ? AND ? THEN 1 ELSE 0 END)               AS tx_last_month
            ", [
                $today->toDateString(),
                $yesterday->toDateString(),
                $thisMonth->toDateTimeString(),
                $lastMonth->toDateTimeString(),
                $lastMonthEnd->toDateTimeString(),
                $thisMonth->toDateTimeString(),
                $lastMonth->toDateTimeString(),
                $lastMonthEnd->toDateTimeString(),
            ])
            ->first();

        $pendingOrders = $this->applyStoreFilter(
            Transaction::query()->where('status', TransactionStatusEnum::PENDING)
        )->count();

        $pct = fn($now, $prev) => $prev > 0 ? (($now - $prev) / $prev) * 100 : 0;

        $salesTodayChange    = $pct($row->sales_today, $row->sales_yesterday);
        $salesMonthChange    = $pct($row->sales_this_month, $row->sales_last_month);
        $transactionChange   = $pct($row->tx_this_month, $row->tx_last_month);

        $desc = fn($pct) => ($pct >= 0 ? '↑ ' : '↓ ') . abs(round($pct)) . '% vs last period';

        return [
            Stat::make('Total Sales Today', RupiahHelper::format($row->sales_today))
                ->description($desc($salesTodayChange))
                ->descriptionColor($salesTodayChange >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Sales This Month', RupiahHelper::format($row->sales_this_month))
                ->description($desc($salesMonthChange))
                ->descriptionColor($salesMonthChange >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Transactions', number_format($row->total_transactions, 0, ',', '.'))
                ->description($desc($transactionChange))
                ->descriptionColor($transactionChange >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Orders awaiting processing')
                ->descriptionColor('warning')
                ->icon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
