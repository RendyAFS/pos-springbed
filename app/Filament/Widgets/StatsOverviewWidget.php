<?php

namespace App\Filament\Widgets;

use App\Enums\TransactionStatusEnum;
use App\Filament\Widgets\Concerns\HasDashboardFilters;
use App\Filament\Widgets\Concerns\HasStoreFilter;
use App\Helpers\RupiahHelper;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    use HasStoreFilter;
    use HasDashboardFilters;
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    /**
     * Base query with store + channel filter applied (not yet scoped by date).
     */
    private function filteredQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->applyChannelFilter(
            $this->applyStoreFilter(
                Transaction::query()->whereNotIn('status', [TransactionStatusEnum::CANCELLED]),
                'store_setting_id',
                true
            )
        );
    }

    protected function getStats(): array
    {
        [$startDate, $endDate] = $this->getFilterDateRange();

        $daysInRange   = $startDate->diffInDays($endDate) + 1;
        $prevEndDate   = $startDate->copy()->subDay()->endOfDay();
        $prevStartDate = $prevEndDate->copy()->subDays($daysInRange - 1)->startOfDay();

        $currentRow = $this->filteredQuery()
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->selectRaw('COALESCE(SUM(grand_total), 0) AS total_sales, COUNT(*) AS total_transactions')
            ->first();

        $previousRow = $this->filteredQuery()
            ->whereBetween('transaction_date', [$prevStartDate, $prevEndDate])
            ->selectRaw('COALESCE(SUM(grand_total), 0) AS total_sales, COUNT(*) AS total_transactions')
            ->first();

        $pendingOrders = $this->applyChannelFilter(
            $this->applyStoreFilter(
                Transaction::query()->where('status', TransactionStatusEnum::PENDING)
            )
        )
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->count();

        $pct = fn($now, $prev) => $prev > 0 ? (($now - $prev) / $prev) * 100 : 0;

        $salesChange       = $pct($currentRow->total_sales, $previousRow->total_sales);
        $transactionChange = $pct($currentRow->total_transactions, $previousRow->total_transactions);

        $desc = fn($pct) => ($pct >= 0 ? '↑ ' : '↓ ') . abs(round($pct)) . '% vs Periode Sebelumnya';

        $periodLabel = $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y');

        $revenue = $currentRow->total_sales;

        $cogs = TransactionItem::query()
            ->join('products', 'products.id', '=', 'transaction_items.product_id')
            ->whereIn(
                'transaction_items.transaction_id',
                $this->filteredQuery()->whereBetween('transaction_date', [$startDate, $endDate])->select('transactions.id')
            )
            ->selectRaw('COALESCE(SUM(transaction_items.qty * products.cost_price), 0) AS total_cogs')
            ->value('total_cogs');

        $netProfit       = $revenue - $cogs;
        $netProfitMargin = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0;

        return [
            Stat::make('Total Penjualan', RupiahHelper::format($currentRow->total_sales))
                ->description($periodLabel . ' · ' . $desc($salesChange))
                ->descriptionColor($salesChange >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Transaksi', number_format($currentRow->total_transactions, 0, ',', '.'))
                ->description($desc($transactionChange))
                ->descriptionColor($transactionChange >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-document-text')
                ->color('info'),

            Stat::make('Pesanan Pending', $pendingOrders)
                ->description('Dalam periode terpilih')
                ->descriptionColor('warning')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Net Profit', RupiahHelper::format($netProfit))
                ->description('Margin ' . round($netProfitMargin) . '%')
                ->descriptionColor($netProfit >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes')
                ->color($netProfit >= 0 ? 'success' : 'danger'),
        ];
    }
}
