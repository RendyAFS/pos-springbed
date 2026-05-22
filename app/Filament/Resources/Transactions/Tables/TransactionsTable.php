<?php

namespace App\Filament\Resources\Transactions\Tables;

use App\Enums\StatusTransactionShipmentEnum;
use App\Enums\TransactionPaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Helpers\RupiahHelper;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                        $preOrderItems = $record->transactionItems
                            ->where('is_pre_order', true);

                        if ($preOrderItems->isEmpty()) {
                            return null;
                        }

                        $badge = '
                        <span class="inline-flex items-center gap-x-1 px-1.5 py-0.5 text-xs font-medium text-warning-600 ring-1 ring-inset ring-warning-600/20 rounded-md">
                            Pre-Order
                        </span>';

                        return new \Illuminate\Support\HtmlString($badge);
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
                TextColumn::make('transactionShipment.status')
                    ->label('Delivery')
                    ->badge()
                    ->formatStateUsing(
                        fn($state) => $state instanceof StatusTransactionShipmentEnum
                            ? $state->getLabel()
                            : $state
                    )
                    ->color(fn($state): string => match ($state instanceof StatusTransactionShipmentEnum ? $state : StatusTransactionShipmentEnum::tryFrom($state)) {
                        StatusTransactionShipmentEnum::PENDING   => 'warning',
                        StatusTransactionShipmentEnum::DELIVERED => 'success',
                        StatusTransactionShipmentEnum::CANCELLED => 'danger',
                        default                                  => 'gray',
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
                TrashedFilter::make()->native(false)->label('Data Yang di Tampilkan'),
                TernaryFilter::make('is_down_payment')
                    ->label('Down Payment')
                    ->native(false)
                    ->placeholder('All')
                    ->trueLabel('Down Payment')
                    ->falseLabel('Non-Down Payment'),
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
                SelectFilter::make('shiping_status')
                    ->label('Status Pengiriman')
                    ->searchable()
                    ->options(
                        collect(StatusTransactionShipmentEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    )
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn($query, $value) =>
                            $query->whereHas('transactionShipment', function ($q) use ($value) {
                                $q->where('status', $value);
                            })
                        );
                    }),
            ], layout: FiltersLayout::Modal)
            ->recordActions([
                ActionGroup::make([
                    Action::make('updateAllStatus')
                        ->label('Update Status')
                        ->icon(Heroicon::ArrowPath)
                        ->color('warning')
                        ->modalHeading('Update Status Transaction')
                        ->modalDescription(fn($record) => "Transaction: {$record->transaction_code}")
                        ->modalWidth('sm')
                        ->schema([
                            Select::make('status')
                                ->label('Status Transaction')
                                ->options(
                                    collect(TransactionStatusEnum::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                        ->toArray()
                                )
                                ->default(fn($record) => $record->status?->value)
                                ->native(false),

                            Select::make('payment_status')
                                ->label('Status Payment')
                                ->options(
                                    collect(TransactionPaymentStatusEnum::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                        ->toArray()
                                )
                                ->default(fn($record) => $record->transactionPayment?->status?->value)
                                ->visible(fn($record) => $record->transactionPayment !== null)
                                ->native(false),

                            TextInput::make('tracking_number')
                                ->label('No. Resi')
                                ->default(fn($record) => $record->transactionShipment?->tracking_number)
                                ->visible(fn($record) => $record->transactionShipment !== null),

                            Select::make('shipment_status')
                                ->label('Status Delivery')
                                ->options(
                                    collect(StatusTransactionShipmentEnum::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                        ->toArray()
                                )
                                ->default(fn($record) => $record->transactionShipment?->status?->value)
                                ->visible(fn($record) => $record->transactionShipment !== null)
                                ->native(false),
                        ])
                        ->action(function ($record, array $data): void {

                            if (!empty($data['status'])) {
                                $record->update([
                                    'status' => $data['status'],
                                ]);
                            }

                            if (!empty($data['payment_status']) && $record->transactionPayment) {
                                $record->transactionPayment->update([
                                    'status' => $data['payment_status'],
                                ]);
                            }

                            if (!empty($data['shipment_status']) && $record->transactionShipment) {
                                $record->transactionShipment->update([
                                    'status' => $data['shipment_status'],
                                    'tracking_number' => $data['tracking_number'],
                                ]);
                            }
                            Notification::make()
                                ->title('Update status transaction successfully')
                                ->body('Status transaction successfully updated.')
                                ->success()
                                ->send();
                        })
                        ->modalSubmitActionLabel('Simpan'),
                    Action::make('print')
                        ->label('Cetak Struk')
                        ->icon(Heroicon::Printer)
                        ->color('success')
                        ->url(fn($record) => route('transactions.print', $record))
                        ->openUrlInNewTab(),
                    Action::make('invoice_a5')
                        ->label('Invoice A5')
                        ->icon(Heroicon::DocumentText)
                        ->color('info')
                        ->url(fn($record) => route('transactions.invoice', $record) . '?paper=a5')
                        ->openUrlInNewTab(),
                    Action::make('invoice_a4')
                        ->label('Invoice A4')
                        ->icon(Heroicon::DocumentText)
                        ->color('primary')
                        ->url(fn($record) => route('transactions.invoice', $record) . '?paper=a4')
                        ->openUrlInNewTab(),
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
                    ->icon(Heroicon::OutlinedEllipsisHorizontal),
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
