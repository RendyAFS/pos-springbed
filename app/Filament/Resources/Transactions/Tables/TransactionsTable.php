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
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

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
                    ->copyMessageDuration(1500),
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
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->sortable()
                    ->alignRight()
                    ->weight('medium')
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
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
                    ->label('Pembayaran')
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
                    ->label('Pengiriman')
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
                    ->label('Courier')
                    ->default('')
                    ->limit(20),
            ])
            ->defaultSort('transaction_date', 'desc')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(
                        collect(TransactionStatusEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    ),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('updateAllStatus')
                        ->label('Update Status')
                        ->icon(Heroicon::ArrowPath)
                        ->color('warning')
                        ->modalHeading('Update Status Transaksi')
                        ->modalDescription(fn($record) => "Transaksi: {$record->transaction_code}")
                        ->modalWidth('sm')
                        ->schema([
                            Select::make('status')
                                ->label('Status Transaksi')
                                ->options(
                                    collect(TransactionStatusEnum::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                        ->toArray()
                                )
                                ->default(fn($record) => $record->status?->value)
                                ->native(false),

                            Select::make('payment_status')
                                ->label('Status Pembayaran')
                                ->options(
                                    collect(TransactionPaymentStatusEnum::cases())
                                        ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                        ->toArray()
                                )
                                ->default(fn($record) => $record->transactionPayment?->status?->value)
                                ->visible(fn($record) => $record->transactionPayment !== null)
                                ->native(false),

                            Select::make('shipment_status')
                                ->label('Status Pengiriman')
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
                                ]);
                            }
                        })
                        ->modalSubmitActionLabel('Simpan'),
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
