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

    private function transactionQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->applyStoreFilter(
            Transaction::query()->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
        );
    }

    protected function getStats(): array
    {
        $today        = Carbon::today();
        $yesterday    = Carbon::yesterday();
        $thisMonth    = Carbon::now()->startOfMonth();
        $lastMonth    = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Sales Today
        $salesToday     = (clone $this->transactionQuery())->whereDate('transaction_date', $today)->sum('grand_total');
        $salesYesterday = (clone $this->transactionQuery())->whereDate('transaction_date', $yesterday)->sum('grand_total');
        $salesTodayChange = $salesYesterday > 0
            ? (($salesToday - $salesYesterday) / $salesYesterday) * 100
            : 0;

        // Sales This Month
        $salesThisMonth = (clone $this->transactionQuery())->whereBetween('transaction_date', [$thisMonth, Carbon::now()])->sum('grand_total');
        $salesLastMonth = (clone $this->transactionQuery())->whereBetween('transaction_date', [$lastMonth, $lastMonthEnd])->sum('grand_total');
        $salesMonthChange = $salesLastMonth > 0
            ? (($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100
            : 0;

        // Total Transactions
        $totalTransactions      = (clone $this->transactionQuery())->count();
        $transactionsThisMonth  = (clone $this->transactionQuery())->whereBetween('transaction_date', [$thisMonth, Carbon::now()])->count();
        $transactionsLastMonth  = (clone $this->transactionQuery())->whereBetween('transaction_date', [$lastMonth, $lastMonthEnd])->count();
        $transactionChange = $transactionsLastMonth > 0
            ? (($transactionsThisMonth - $transactionsLastMonth) / $transactionsLastMonth) * 100
            : 0;

        // Pending Orders
        $pendingOrders = $this->applyStoreFilter(
            Transaction::query()->where('status', TransactionStatusEnum::PENDING)
        )->count();

        return [
            Stat::make('Total Sales Today', RupiahHelper::format($salesToday))
                ->description(($salesTodayChange >= 0 ? '↑ ' : '↓ ') . abs(round($salesTodayChange)) . '% vs last period')
                ->descriptionColor($salesTodayChange >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Sales This Month', RupiahHelper::format($salesThisMonth))
                ->description(($salesMonthChange >= 0 ? '↑ ' : '↓ ') . abs(round($salesMonthChange)) . '% vs last period')
                ->descriptionColor($salesMonthChange >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Transactions', number_format($totalTransactions, 0, ',', '.'))
                ->description(($transactionChange >= 0 ? '↑ ' : '↓ ') . abs(round($transactionChange)) . '% vs last period')
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
