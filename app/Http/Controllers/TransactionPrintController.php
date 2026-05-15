<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

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

    public function invoice(Transaction $transaction, Request $request)
    {
        $transaction->load([
            'transactionItems.product',
            'transactionItems.bundle.bundleItems.product',
            'transactionPayment',
            'transactionShipment.courier',
            'customer',
            'storeSetting',
        ]);

        $paperSize = $request->input('paper', 'a5');

        $pdf = Pdf::loadView(
            'prints.transaction-invoice',
            compact('transaction', 'paperSize')
        )->setPaper($paperSize, 'portrait');

        return $pdf->stream('invoice-' . $transaction->transaction_code . '.pdf');
    }
}
