<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Filament\Resources\Transactions\Support\TransactionActions;
use Filament\Support\Enums\Width;
use App\Helpers\RupiahHelper;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\TernaryFilter;

class TransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_code')
                    ->label('Transaction ID')
                    ->badge()
                    ->color('primary')
                    ->fontFamily(FontFamily::Mono)
                    ->searchable()
                    ->icon(Heroicon::ClipboardDocumentList)
                    ->iconPosition(IconPosition::After)
                    ->copyable()
                    ->copyMessage('Transaction ID copied')
                    ->copyMessageDuration(1500)
                    ->tooltip(function ($record): ?\Illuminate\Support\HtmlString {
                        $preOrderItems = $record->transactionItems
                            ->where('is_pre_order', true);

                        if ($preOrderItems->isEmpty()) {
                            return null;
                        }

                        $content = $preOrderItems
                            ->map(function ($item) {
                                $isBundle = $item->bundle_id && $item->bundle;

                                $type = $isBundle ? 'Bundle' : 'Product';

                                $name = $isBundle
                                    ? $item->bundle->name
                                    : ($item->product?->name ?? "Product #{$item->product_id}");

                                return "<strong>{$type}:</strong> {$name} • Qty: {$item->qty}";
                            })
                            ->implode('<br>');

                        return new \Illuminate\Support\HtmlString($content);
                    })
                    ->description(function ($record): ?\Illuminate\Support\HtmlString {
                        $badges = [];

                        $preOrderItems = $record->transactionItems
                            ->where('is_pre_order', true);

                        if ($preOrderItems->isNotEmpty()) {
                            $badges[] = '
                            <span class="inline-flex items-center gap-x-1 px-1.5 py-0.5 text-xs font-medium text-warning-600 ring-1 ring-inset ring-warning-600/20 rounded-md">
                                Pre-Order
                            </span>';
                        }

                        if ($record->channelSale) {
                            $badges[] = '
                            <span class="inline-flex items-center gap-x-1 px-1.5 py-0.5 text-xs font-medium text-info-600 ring-1 ring-inset ring-info-600/20 rounded-md">
                                ' . e($record->channelSale->channel) . '
                            </span>';
                        }

                        return empty($badges)
                            ? null
                            : new \Illuminate\Support\HtmlString(implode(' ', $badges));
                    }),
                TextColumn::make('transaction_date')
                    ->label('Date')
                    ->date('Y-m-d')
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold')
                    ->description(fn($record): string => $record->customer?->phone ?? '—'),
                TextColumn::make('transactionPayment.amount')
                    ->label('Total')
                    ->sortable()
                    ->alignRight()
                    ->weight('medium')
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state))
                    ->description(function ($record) {
                        if (! $record->is_down_payment) {
                            return null;
                        }

                        $totalDownPayment = $record->transactionDownPayments->sum('amount')
                            + (float) ($record->transactionPayment?->amount ?? 0);
                        $grandTotal = (float) $record->grand_total;
                        $isPaid     = $totalDownPayment >= $grandTotal;
                        $remaining  = $grandTotal - $totalDownPayment;

                        $downPaymentBadge = '<span class="inline-flex items-center fi-badge fi-size-sm font-medium text-warning-600 ring-1 ring-inset ring-warning-600/20">Down Payment</span>';

                        if ($isPaid) {
                            $statusBadge = '<span class="inline-flex items-center fi-badge fi-size-sm font-medium text-success-600 ring-1 ring-inset ring-success-600/20">Paid</span>';
                        } else {
                            $formatted   = RupiahHelper::format($remaining);
                            $statusBadge = '<span class="inline-flex items-center fi-badge fi-size-sm font-medium text-danger-600 ring-1 ring-inset ring-danger-600/20">Remaining ' . e($formatted) . '</span>';
                        }

                        return new \Illuminate\Support\HtmlString(
                            $downPaymentBadge . ' ' . $statusBadge
                        );
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof TransactionStatusEnum
                            ? $state->getLabel()
                            : $state
                    )
                    ->color(fn($state): string => match ($state instanceof TransactionStatusEnum ? $state : TransactionStatusEnum::tryFrom($state)) {
                        TransactionStatusEnum::DELIVERED  => 'success',
                        TransactionStatusEnum::SHIPPED    => 'info',
                        TransactionStatusEnum::PROCESSED  => 'warning',
                        TransactionStatusEnum::PENDING    => 'gray',
                        TransactionStatusEnum::CANCELLED  => 'danger',
                        default                           => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transactionPayment.status')
                    ->label('Payment')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof TransactionPaymentStatusEnum
                            ? $state->getLabel()
                            : $state
                    )
                    ->color(fn($state): string => match ($state instanceof TransactionPaymentStatusEnum ? $state : TransactionPaymentStatusEnum::tryFrom($state)) {
                        TransactionPaymentStatusEnum::PENDING => 'warning',
                        TransactionPaymentStatusEnum::PAID    => 'success',
                        TransactionPaymentStatusEnum::FAILED  => 'danger',
                        default                               => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transactionShipment.courier.name')
                    ->label('Kurir')
                    ->default('')
                    ->limit(20),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                TrashedFilter::make()->native(false)->label('Data Yang di Tampilkan')
                    ->columnSpanFull(),
                TernaryFilter::make('is_down_payment')
                    ->label('Down Payment')
                    ->native(false)
                    ->placeholder('All')
                    ->trueLabel('Down Payment')
                    ->falseLabel('Non-Down Payment')
                    ->columnSpanFull(),
                SelectFilter::make('status'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->searchable()
                    ->options(
                        collect(TransactionStatusEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    ),
                SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->searchable()
                    ->options(
                        collect(TransactionPaymentStatusEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    )
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn($query, $value) =>
                            $query->whereHas('transactionPayment', function ($q) use ($value) {
                                $q->where('status', $value);
                            })
                        );
                    }),
            ], layout: FiltersLayout::Modal)
            ->filtersFormWidth(Width::ExtraLarge)
            ->filtersFormColumns(2)
            ->recordActions([
                TransactionActions::table(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
