<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionStatusEnum;
use App\Helpers\RupiahHelper;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Total Sales Today
        $salesToday = Transaction::whereDate('transaction_date', $today)
            ->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
            ->sum('grand_total');

        $salesYesterday = Transaction::whereDate('transaction_date', $yesterday)
            ->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
            ->sum('grand_total');

        $salesTodayChange = $salesYesterday > 0
            ? (($salesToday - $salesYesterday) / $salesYesterday) * 100
            : 0;

        // Total Sales This Month
        $salesThisMonth = Transaction::whereBetween('transaction_date', [$thisMonth, Carbon::now()])
            ->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
            ->sum('grand_total');

        $salesLastMonth = Transaction::whereBetween('transaction_date', [$lastMonth, $lastMonthEnd])
            ->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
            ->sum('grand_total');

        $salesMonthChange = $salesLastMonth > 0
            ? (($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100
            : 0;

        // Total Transactions
        $totalTransactions = Transaction::whereNotIn('status', [TransactionStatusEnum::CANCELLED])
            ->count();

        $totalTransactionsLastMonth = Transaction::whereBetween('transaction_date', [$lastMonth, $lastMonthEnd])
            ->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
            ->count();

        $totalTransactionsThisMonth = Transaction::whereBetween('transaction_date', [$thisMonth, Carbon::now()])
            ->whereNotIn('status', [TransactionStatusEnum::CANCELLED])
            ->count();

        $transactionChange = $totalTransactionsLastMonth > 0
            ? (($totalTransactionsThisMonth - $totalTransactionsLastMonth) / $totalTransactionsLastMonth) * 100
            : 0;

        // Pending Orders
        $pendingOrders = Transaction::where('status', TransactionStatusEnum::PENDING)->count();

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
