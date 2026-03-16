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
                    $bundle = $item->bundle;

                    if ($bundle) {
                        foreach ($bundle->bundleItems as $bundleItem) {
                            $totalQty = $item->qty * $bundleItem->qty;

                            $childItem = TransactionItem::create([
                                'transaction_id' => $transaction->id,
                                'product_id'     => $bundleItem->product_id,
                                'bundle_id'      => $item->bundle_id,
                                'qty'            => $totalQty,
                                'selling_price'  => $bundleItem->price,
                                'discount'       => $item['discount'],
                                'subtotal'       => $item['subtotal'],
                            ]);

                            $inventoryService->decreaseStock(
                                productId: $bundleItem->product_id,
                                qty: $totalQty,
                                storeReference: $transaction,
                                transactionItem: $childItem
                            );
                        }
                        $item->delete();
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
