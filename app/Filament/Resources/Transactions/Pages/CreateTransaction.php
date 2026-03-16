<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Enums\StatusTransactionShipmentEnum;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Bundle;
use App\Models\TransactionPayment;
use App\Models\TransactionShipment;
use App\Services\TransactionService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['store_setting_id'] = Auth::user()?->store_setting_id;

        unset(
            $data['courier_id'],
            $data['payment_method'],
            $data['payment_amount'],
            $data['payment_status'],
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
        $data = $this->data;

        if (filled($data['payment_method'] ?? null) && filled($data['payment_amount'] ?? null)) {
            TransactionPayment::create([
                'transaction_id' => $this->record->id,
                'method'         => $data['payment_method'],
                'amount'         => $data['payment_amount'],
                'status'         => $data['payment_status'] ?? null,
                'paid_at'        => now(),
            ]);
        }

        if (filled($data['courier_id'] ?? null)) {
            TransactionShipment::create([
                'transaction_id' => $this->record->id,
                'courier_id'     => $data['courier_id'],
                'status'         => StatusTransactionShipmentEnum::PENDING,
            ]);
        }

        /** @var TransactionService $service */
        $service = app(TransactionService::class);
        $service->processTransaction($this->record);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
