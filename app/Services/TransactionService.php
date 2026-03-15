<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\PromoUsage;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function processTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            /** @var InventoryService $inventoryService */
            $inventoryService = app(InventoryService::class);

            $transaction->load('transactionItems');

            foreach ($transaction->transactionItems as $item) {
                $inventoryService->decreaseStock(
                    productId: $item->product_id,
                    qty: $item->qty,
                    storeReference: $transaction,
                    transactionItem: $item
                );
            }

            // ── Catat pemakaian promo & tambah usage_count ──────────────────
            if ($transaction->promo_id) {
                $promo = Promo::lockForUpdate()->find($transaction->promo_id);

                if ($promo) {
                    PromoUsage::create([
                        'promo_id'        => $promo->id,
                        'transaction_id'  => $transaction->id,
                        'discount_amount' => $transaction->discount_total ?? 0,
                    ]);

                    $promo->increment('usage_count');
                }
            }
        });
    }
}
