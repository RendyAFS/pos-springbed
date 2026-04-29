<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\StatusTransactionShipmentEnum;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\TransactionPayment;
use App\Models\TransactionShipment;
use App\Services\ReferalService;
use App\Services\TransactionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected array $extraData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['store_setting_id'] = Auth::user()?->store_setting_id;

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

    protected function afterCreate(): void
    {
        $extra = $this->extraData;

        if (filled($extra['payment_method'] ?? null) && filled($extra['payment_amount'] ?? null)) {
            TransactionPayment::create([
                'transaction_id' => $this->record->id,
                'method'         => $extra['payment_method'],
                'amount'         => $extra['payment_amount'],
                'status'         => $extra['payment_status'] ?? null,
                'paid_at'        => now(),
            ]);
        }

        if (filled($extra['courier_id'] ?? null)) {
            TransactionShipment::create([
                'transaction_id' => $this->record->id,
                'courier_id'     => $extra['courier_id'],
                'status'         => StatusTransactionShipmentEnum::PENDING,
            ]);
        }

        /** @var TransactionService $service */
        $service = app(TransactionService::class);
        $service->processTransaction($this->record);

        /** @var ReferalService $referalService */
        $referalService = app(ReferalService::class);
        $referalService->processReferal($this->record, $extra);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
