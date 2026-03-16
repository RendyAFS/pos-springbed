<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\Promo;
use App\Models\PromoUsage;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function processTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {

            /** @var InventoryService $inventoryService */
            $inventoryService = app(InventoryService::class);

            $transaction->load('transactionItems.bundle.bundleItems');

            foreach ($transaction->transactionItems as $item) {

                if ($item->bundle_id) {
                    $bundle = Bundle::with('bundleItems')->find($item->bundle_id);

                    if ($bundle) {
                        foreach ($bundle->bundleItems as $bundleItem) {
                            // qty bundle × qty per item dalam bundle
                            $totalQty = $item->qty * $bundleItem->qty;

                            // ✅ Kurangi stok menggunakan $item yang sudah ada
                            // JANGAN buat TransactionItem baru & JANGAN delete $item
                            $inventoryService->decreaseStock(
                                productId: $bundleItem->product_id,
                                qty: $totalQty,
                                storeReference: $transaction,
                                transactionItem: $item // pakai item yang ada sebagai referensi
                            );
                        }
                    }
                } else {
                    $inventoryService->decreaseStock(
                        productId: $item->product_id,
                        qty: $item->qty,
                        storeReference: $transaction,
                        transactionItem: $item
                    );
                }
            }

            // ── Promo usage (tidak berubah) ──────────────────────────────────
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
