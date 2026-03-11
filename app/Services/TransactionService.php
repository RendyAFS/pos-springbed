<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function processTransaction(Transaction $transaction)
    {
        $inventoryService = app(InventoryService::class);

        foreach ($transaction->transactionItems as $transactionItem) {

            $inventoryService->decreaseStock(
                $transactionItem->product_id,
                $transactionItem->qty,
                $transaction
            );
        }
    }
}
