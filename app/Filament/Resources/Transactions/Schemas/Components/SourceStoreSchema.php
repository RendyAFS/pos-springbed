<?php

namespace App\Filament\Resources\Transactions\Schemas\Components;

use App\Filament\Resources\Transactions\Schemas\Concerns\HasTotalsCalculation;
use App\Models\InventoryStock;
use App\Models\StoreSetting;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class SourceStoreSchema
{
    use HasTotalsCalculation;

    public static function make(): Repeater
    {
        return Repeater::make('source_stores')
            ->label('Sumber Stok per Toko')
            ->schema([
                Select::make('store_setting_id')
                    ->label('Toko')
                    ->options(fn() => StoreSetting::pluck('store_name', 'id')->toArray())
                    ->required()
                    ->live()
                    ->searchable(),
                TextInput::make('qty')
                    ->label('Qty dari Toko Ini')
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->required()
                    ->live(onBlur: true)
                    ->suffix('pcs')
                    ->helperText(function (Get $get): string {
                        $storeId   = $get('store_setting_id');
                        $productId = $get('../../product_id');

                        if (! $storeId || ! $productId) {
                            return '';
                        }

                        $stock = InventoryStock::where('product_id', $productId)
                            ->where('store_setting_id', $storeId)
                            ->value('quantity') ?? 0;

                        $inputQty = (int) ($get('qty') ?? 1);

                        if ($stock <= 0) {
                            return "⚠️ Stok kosong di toko ini — {$inputQty} pcs akan jadi pre-order.";
                        }

                        if ($inputQty > $stock) {
                            $preOrder = $inputQty - $stock;
                            return "Stok tersedia: {$stock} pcs — ⚠️ {$preOrder} pcs akan jadi pre-order.";
                        }

                        return "✅ Stok tersedia: {$stock} pcs";
                    }),
            ])
            ->columns(2)
            ->addActionLabel('+ Tambah Toko')
            ->live()
            ->visible(fn(Get $get): bool => (bool) $get('is_multi_store'))
            ->afterStateUpdated(function (Get $get, Set $set): void {
                $sources  = $get('source_stores') ?? [];
                $totalQty = collect($sources)->sum(fn($s) => (int) ($s['qty'] ?? 0));
                $set('qty', max(1, $totalQty));
                self::recalculateItemSubtotal($get, $set);
            })
            ->collapsible()
            ->columnSpan(2);
    }
}
