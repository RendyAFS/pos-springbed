<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionPrintController extends Controller
{
    public function print(Transaction $transaction)
    {
        $transaction->load([
            'transactionItems.product',
            'transactionItems.bundle',
            'transactionPayment',
            'transactionShipment.courier',
            'customer',
            'storeSetting',
        ]);

        return view('prints.transaction', compact('transaction'));
    }
}
