<?php

namespace App\Services;

use App\Models\Bundle;
use App\Models\InventoryStock;
use App\Models\Promo;
use App\Models\PromoUsage;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                        $isPreOrder = false;

                        foreach ($bundle->bundleItems as $bundleItem) {
                            $totalQty = $item->qty * $bundleItem->qty;

                            Log::info('[DEBUG] Bundle decrease', [
                                'bundle_id'       => $item->bundle_id,
                                'bundle_item_qty' => $item->qty,        // qty bundle di transaksi
                                'product_id'      => $bundleItem->product_id,
                                'bundleItem_qty'  => $bundleItem->qty,  // qty per produk dalam bundle
                                'totalQty'        => $totalQty,
                            ]);


                            $stock = InventoryStock::where([
                                'product_id'       => $bundleItem->product_id,
                                'store_setting_id' => $transaction->store_setting_id,
                            ])->first();

                            if (!$stock || $stock->quantity < $totalQty) {
                                $isPreOrder = true;
                            }

                            $inventoryService->decreaseStock(
                                productId: $bundleItem->product_id,
                                qty: $totalQty,
                                storeReference: $transaction,
                                transactionItem: $item,
                                allowNegative: true
                            );
                        }

                        if ($isPreOrder) {
                            $item->is_pre_order = true;
                            $item->save();
                        }
                    }
                } else {
                    $stock = InventoryStock::where([
                        'product_id'       => $item->product_id,
                        'store_setting_id' => $transaction->store_setting_id,
                    ])->first();

                    $isPreOrder = !$stock || $stock->quantity < $item->qty;

                    $inventoryService->decreaseStock(
                        productId: $item->product_id,
                        qty: $item->qty,
                        storeReference: $transaction,
                        transactionItem: $item,
                        allowNegative: true
                    );

                    if ($isPreOrder) {
                        $item->is_pre_order = true;
                        $item->save();
                    }
                }
            }

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
