<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionPrintController extends Controller
{
    public function print(Transaction $transaction)
    {
        $transaction->load([
            'transactionItems.product',
            'transactionItems.bundle.bundleItems.product',
            'transactionPayment',
            'transactionShipment.courier',
            'customer',
            'storeSetting',
        ]);

        $pdf = Pdf::loadView('prints.transaction', compact('transaction'))
            ->setPaper('a5', 'portrait');

        return $pdf->stream('nota-' . $transaction->transaction_code . '.pdf');
    }
}
