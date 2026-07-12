<?php

namespace App\Filament\Resources\Transactions\Concerns;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

trait HasBarcodeScanner
{
    protected function getScanBarcodeAction(): Action
    {
        return Action::make('scanBarcode')
            ->label('Scan Barcode')
            ->icon(Heroicon::QrCode)
            ->color('primary')
            ->modalHeading('Scan Barcode Produk')
            ->modalDescription('Arahkan kamera ke barcode, atau upload gambar barcode.')
            ->modalContent(fn() => view('filament.components.transaction.barcode-scanner'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Tutup')
            ->extraAttributes([
                'class' => 'fixed bottom-6 right-6 z-50 shadow-lg rounded-full',
            ]);
    }

    #[On('barcode-scanned')]
    public function handleBarcodeScanned(string $code): void
    {
        $productId = (int) preg_replace('/[^0-9]/', '', $code);

        $product = Product::query()
            ->where('is_active', true)
            ->find($productId);

        if (! $product) {
            Notification::make()
                ->danger()
                ->title('Produk tidak ditemukan')
                ->body("Barcode: {$code}")
                ->send();

            $this->unmountAction();

            return;
        }

        $items = $this->data['transactionItems'] ?? [];

        // 1. Cek apakah produk yang sama sudah ada di repeater -> qty + 1
        $existingKey = null;
        $emptyRowKey = null;

        foreach ($items as $key => $item) {
            $itemType = $item['item_type'] ?? 'product';

            if ($itemType === 'product' && (int) ($item['product_id'] ?? 0) === $product->id) {
                $existingKey = $key;
                break;
            }

            // Simpan row kosong pertama yang ditemukan (belum pilih produk/bundle sama sekali)
            if (
                $emptyRowKey === null
                && $itemType === 'product'
                && empty($item['product_id'])
                && empty($item['bundle_id'])
            ) {
                $emptyRowKey = $key;
            }
        }

        if ($existingKey !== null) {
            // Produk sudah ada -> tambah qty
            $qty = (int) ($items[$existingKey]['qty'] ?? 0) + 1;
            $items[$existingKey]['qty'] = $qty;
            $items[$existingKey]['subtotal'] = $this->calcItemSubtotal(
                $qty,
                $items[$existingKey]['selling_price'] ?? 0,
                $items[$existingKey]['discount'] ?? 0,
            );
            $finalQty = $qty;
        } elseif ($emptyRowKey !== null) {
            // Ada row kosong (default) -> isi row itu, jangan buat row baru
            $items[$emptyRowKey]['item_type']     = 'product';
            $items[$emptyRowKey]['product_id']    = $product->id;
            $items[$emptyRowKey]['bundle_id']     = null;
            $items[$emptyRowKey]['selling_price'] = number_format((float) $product->selling_price, 0, ',', '.');
            $items[$emptyRowKey]['qty']           = 1;
            $items[$emptyRowKey]['discount']      = $items[$emptyRowKey]['discount'] ?? 0;
            $items[$emptyRowKey]['subtotal']      = number_format((float) $product->selling_price, 0, ',', '.');
            $items[$emptyRowKey]['is_multi_store'] = $items[$emptyRowKey]['is_multi_store'] ?? false;
            $items[$emptyRowKey]['source_stores']  = $items[$emptyRowKey]['source_stores'] ?? [];
            $finalQty = 1;
        } else {
            // Semua row terisi produk lain -> tambah row baru
            $newKey = (string) Str::uuid();
            $items[$newKey] = [
                'item_type'      => 'product',
                'product_id'     => $product->id,
                'bundle_id'      => null,
                'selling_price'  => number_format((float) $product->selling_price, 0, ',', '.'),
                'qty'            => 1,
                'discount'       => 0,
                'subtotal'       => number_format((float) $product->selling_price, 0, ',', '.'),
                'is_multi_store' => false,
                'source_stores'  => [],
                'note_product'   => null,
            ];
            $finalQty = 1;
        }

        $this->data['transactionItems'] = $items;

        $this->recalculateTotalsFromArray();

        Notification::make()
            ->success()
            ->title("{$product->name} ditambahkan")
            ->body("Qty sekarang: {$finalQty}")
            ->send();

        $this->unmountAction();
    }

    protected function calcItemSubtotal(int $qty, mixed $price, mixed $discount): string
    {
        $price = (float) str_replace('.', '', (string) $price);
        $discount = (float) str_replace('.', '', (string) $discount);

        return number_format(max(0, ($qty * $price) - $discount), 0, ',', '.');
    }

    protected function recalculateTotalsFromArray(): void
    {
        $items = $this->data['transactionItems'] ?? [];

        $subtotal = collect($items)->sum(function ($i) {
            $qty = (float) ($i['qty'] ?? 0);
            $price = (float) str_replace('.', '', (string) ($i['selling_price'] ?? 0));
            $discount = (float) str_replace('.', '', (string) ($i['discount'] ?? 0));

            return max(0, ($qty * $price) - $discount);
        });

        $shipping = (float) str_replace('.', '', (string) ($this->data['shiping_cost'] ?? 0));
        $promoTotal = (float) str_replace('.', '', (string) ($this->data['promo_total'] ?? 0));
        $discountReferal = (float) str_replace('.', '', (string) ($this->data['use_discount_referal'] ?? 0));

        $grandTotal = max(0, $subtotal - $promoTotal - $discountReferal + $shipping);

        $this->data['subtotal'] = number_format($subtotal, 0, ',', '.');
        $this->data['grand_total'] = number_format($grandTotal, 0, ',', '.');
    }
}
