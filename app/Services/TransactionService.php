<?php

namespace App\Services;

use App\Enums\TypeStockMovementEnum;
use App\Models\Bundle;
use App\Models\InventoryStock;
use App\Models\Promo;
use App\Models\PromoUsage;
use App\Models\StockMovement;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    public function processTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            if (! $transaction->store_setting_id) {
                throw new \Exception('Transaction store not set');
            }
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
                                'bundle_item_qty' => $item->qty,
                                'product_id'      => $bundleItem->product_id,
                                'bundleItem_qty'  => $bundleItem->qty,
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
                } elseif ($item->is_multi_store && !empty($item->source_stores)) {
                    // ── Multi-store: kurangi stok per toko sesuai source_stores ──
                    $isPreOrder = false;

                    foreach ($item->source_stores as $source) {
                        $srcStoreId = (int) $source['store_setting_id'];
                        $srcQty     = (int) $source['qty'];

                        if ($srcQty <= 0) {
                            continue;
                        }

                        $stock = InventoryStock::where([
                            'product_id'       => $item->product_id,
                            'store_setting_id' => $srcStoreId,
                        ])->lockForUpdate()->first();

                        if (!$stock || $stock->quantity < $srcQty) {
                            $isPreOrder = true;
                        }

                        if ($stock) {
                            $stock->quantity -= $srcQty;
                            $stock->save();
                        } else {
                            // Buat record stok dengan nilai negatif (pre-order)
                            InventoryStock::create([
                                'product_id'       => $item->product_id,
                                'store_setting_id' => $srcStoreId,
                                'quantity'         => -$srcQty,
                            ]);
                        }

                        StockMovement::create([
                            'product_id'       => $item->product_id,
                            'store_setting_id' => $srcStoreId,
                            'type'             => TypeStockMovementEnum::OUT,
                            'qty'              => $srcQty,
                            'reference_type'   => $transaction::class,
                            'reference_id'     => $transaction->id,
                        ]);

                        Log::info('[DEBUG] Multi-store decrease', [
                            'product_id'       => $item->product_id,
                            'store_setting_id' => $srcStoreId,
                            'qty'              => $srcQty,
                        ]);
                    }

                    if ($isPreOrder) {
                        $item->is_pre_order = true;
                        $item->save();
                    }
                } else {
                    // ── Single store: logika existing ──
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
