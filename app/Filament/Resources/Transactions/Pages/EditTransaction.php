<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\TransactionPayment;
use App\Models\TransactionShipment;
use App\Enums\StatusTransactionShipmentEnum;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function resolveRecord(int | string $key): Model
    {
        return parent::resolveRecord($key)
            ->load(['transactionPayment', 'transactionShipment', 'transactionItems']);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['courier_id'] = $this->record->transactionShipment?->courier_id;

        if ($payment = $this->record->transactionPayment) {
            $data['payment_method'] = $payment->method instanceof BackedEnum
                ? $payment->method->value
                : $payment->method;

            $data['payment_amount'] = $payment->amount;

            $data['payment_status'] = $payment->status instanceof BackedEnum
                ? $payment->status->value
                : $payment->status;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $data = $this->data;

        if (filled($data['payment_method'] ?? null) && filled($data['payment_amount'] ?? null)) {
            TransactionPayment::updateOrCreate(
                ['transaction_id' => $this->record->id],
                [
                    'method'  => $data['payment_method'],
                    'amount'  => $data['payment_amount'],
                    'status'  => $data['payment_status'] ?? null,
                    'paid_at' => now(),
                ]
            );
        }

        if (filled($data['courier_id'] ?? null)) {
            TransactionShipment::updateOrCreate(
                ['transaction_id' => $this->record->id],
                [
                    'courier_id' => $data['courier_id'],
                    'status'     => $this->record->transactionShipment?->status
                        ?? StatusTransactionShipmentEnum::PENDING,
                ]
            );
        } elseif ($this->record->transactionShipment) {
            $this->record->transactionShipment->delete();
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['transactionItems'])) {
            foreach ($data['transactionItems'] as &$item) {
                unset($item['item_type']);
            }
            unset($item);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
