<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\TransactionPayment;
use App\Models\TransactionShipment;
use App\Services\ReferalService;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected static ?string $title = 'Buat Transaksi';

    protected array $extraData = [];

    protected array $oldReferalData = [];

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
        $this->oldReferalData = [
            'is_referal'          => (bool) $this->record->is_referal,
            'referal_customer_id' => $this->record->referal_customer_id,
            'nominal_referal'     => (float) ($this->record->nominal_referal ?? 0),
        ];

        $this->extraData = [
            'courier_id'           => $data['courier_id'] ?? null,
            'payment_method'       => $data['payment_method'] ?? null,
            'payment_amount'       => $data['payment_amount'] ?? null,
            'payment_status'       => $data['payment_status'] ?? null,
            'is_referal'           => filter_var($data['is_referal'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'referal_customer_id'  => $data['referal_customer_id'] ?? null,
            'nominal_referal'      => $data['nominal_referal'] ?? 0,
            'use_discount_referal' => $data['use_discount_referal'] ?? 0,
        ];

        unset(
            $data['courier_id'],
            $data['payment_method'],
            $data['payment_amount'],
            $data['payment_status'],
        );

        if (isset($data['transactionItems'])) {
            foreach ($data['transactionItems'] as &$item) {
                unset($item['item_type']);

                if (!empty($item['is_multi_store']) && !empty($item['source_stores'])) {
                    $totalQty = collect($item['source_stores'])
                        ->sum(fn($s) => (int)($s['qty'] ?? 0));
                    $item['qty'] = max(1, $totalQty);
                }
            }
            unset($item);
        }

        $user = Auth::user();
        $allowedStores = $user?->selected_store ?? [];

        if (
            ! empty($data['store_setting_id']) &&
            ! in_array($data['store_setting_id'], $allowedStores)
        ) {
            abort(403);
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
                ]
            );
        } elseif ($this->record->transactionShipment) {
            $this->record->transactionShipment->delete();
        }

        /** @var ReferalService $referalService */
        $referalService = app(ReferalService::class);
        $referalService->processReferalOnEdit($this->record, $extra, $this->oldReferalData);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
