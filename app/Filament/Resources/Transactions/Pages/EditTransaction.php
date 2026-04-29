<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\TransactionPayment;
use App\Models\TransactionShipment;
use App\Enums\StatusTransactionShipmentEnum;
use App\Services\ReferalService;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected array $extraData = [];

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

        $data['is_referal']          = $this->record->is_referal ?? false;
        $data['referal_customer_id'] = $this->record->referal_customer_id ?? null;
        $data['nominal_referal']     = $this->record->nominal_referal ?? 0;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->extraData = [
            'courier_id'          => $data['courier_id'] ?? null,
            'payment_method'      => $data['payment_method'] ?? null,
            'payment_amount'      => $data['payment_amount'] ?? null,
            'payment_status'      => $data['payment_status'] ?? null,
            'is_referal'          => filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'referal_customer_id' => $data['referal_customer_id'] ?? null,
            'nominal_referal'     => $data['nominal_referal'] ?? 0,
            'discount_referal'    => $data['discount_referal'] ?? 0,
        ];

        unset(
            $data['courier_id'],
            $data['payment_method'],
            $data['payment_amount'],
            $data['payment_status'],
            $data['discount_referal'],
        );

        if (isset($data['transactionItems'])) {
            foreach ($data['transactionItems'] as &$item) {
                unset($item['item_type']);
            }
            unset($item);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $extra = $this->extraData;

        if (filled($extra['payment_method'] ?? null) && filled($extra['payment_amount'] ?? null)) {
            TransactionPayment::updateOrCreate(
                ['transaction_id' => $this->record->id],
                [
                    'method'  => $extra['payment_method'],
                    'amount'  => $extra['payment_amount'],
                    'status'  => $extra['payment_status'] ?? null,
                    'paid_at' => now(),
                ]
            );
        }

        if (filled($extra['courier_id'] ?? null)) {
            TransactionShipment::updateOrCreate(
                ['transaction_id' => $this->record->id],
                [
                    'courier_id' => $extra['courier_id'],
                    'status'     => $this->record->transactionShipment?->status
                        ?? StatusTransactionShipmentEnum::PENDING,
                ]
            );
        } elseif ($this->record->transactionShipment) {
            $this->record->transactionShipment->delete();
        }

        /** @var ReferalService $referalService */
        $referalService = app(ReferalService::class);
        $referalService->processReferalOnEdit($this->record, $extra);
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
