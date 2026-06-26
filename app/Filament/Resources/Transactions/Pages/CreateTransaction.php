<?php

namespace App\Filament\Resources\Transactions\Pages;

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

    protected static ?string $title = 'Buat Transaksi';

    protected array $extraData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $allowedStores = $user?->selected_store ?? [];

        if (empty($data['store_setting_id'])) {
            if (count($allowedStores) === 1) {
                $data['store_setting_id'] = $allowedStores[0];
            } else {
                abort(422, 'Store wajib dipilih');
            }
        }

        if (! in_array($data['store_setting_id'], $allowedStores)) {
            abort(403, 'Store tidak diizinkan');
        }


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
