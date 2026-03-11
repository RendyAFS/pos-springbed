<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function processTransaction(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {

            $inventoryService = app(InventoryService::class);

            foreach ($transaction->transactionItems as $item) {

                $inventoryService->decreaseStock(
                    $item->product_id,
                    $item->qty,
                    $transaction
                );
            }
        });
    }
}
