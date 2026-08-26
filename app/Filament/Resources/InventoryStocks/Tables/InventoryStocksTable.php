<?php

namespace App\Filament\Resources\InventoryStocks\Tables;

use App\Helpers\RupiahHelper;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductSize;
use App\Models\ProductType;
use Filament\Tables\Table;
use Filament\Support\Enums\Width;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\HtmlString;

class InventoryStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.sku')
                    ->label('SKU')
                    ->fontFamily(FontFamily::Mono)
                    ->searchable()
                    ->icon(Heroicon::ClipboardDocumentList)
                    ->iconPosition(IconPosition::After)
                    ->copyable()
                    ->copyMessage('SKU copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('product.name')
                    ->label('Produk')
                    ->html()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {

                        $type  = $record->product?->type?->name ?? '';
                        $brand = $record->product?->brand?->name ?? '';

                        return new HtmlString("
                            <div class='flex flex-col'>
                                <span>{$state}</span>
                                <span class='text-sm text-gray-500'>{$type}</span>
                                <span class='text-sm text-gray-500'>{$brand}</span>
                            </div>
                        ");
                    }),
                TextColumn::make('product.category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Stok')
                    ->default(0)
                    ->alignCenter()
                    ->weight('bold')
                    ->sortable(),

                TextColumn::make('product.selling_price')
                    ->label('Harga Jual')
                    ->sortable()
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),

                TextColumn::make('total_value')
                    ->label('Total Nilai')
                    ->state(function ($record) {
                        return $record->quantity * ($record->product->selling_price ?? 0);
                    })
                    ->sortable()
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(fn($record) => $record->quantity <= 10 ? 'Low' : 'OK')
                    ->colors([
                        'success' => 'OK',
                        'warning' => 'Low',
                    ]),
            ])
            ->filters([
                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->options(fn() => Brand::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn($q, $value) => $q->whereHas(
                                'product',
                                fn($q2) => $q2->where('brand_id', $value)
                            )
                        );
                    }),

                SelectFilter::make('type_id')
                    ->label('Tipe')
                    ->options(fn() => ProductType::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn($q, $value) => $q->whereHas(
                                'product',
                                fn($q2) => $q2->where('type_id', $value)
                            )
                        );
                    }),

                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->options(fn() => Category::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn($q, $value) => $q->whereHas(
                                'product',
                                fn($q2) => $q2->where('category_id', $value)
                            )
                        );
                    }),

                SelectFilter::make('size_id')
                    ->label('Ukuran')
                    ->options(fn() => ProductSize::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->query(function ($query, array $data) {
                        return $query->when(
                            $data['value'] ?? null,
                            fn($q, $value) => $q->whereHas(
                                'product',
                                fn($q2) => $q2->where('size_id', $value)
                            )
                        );
                    }),
            ], layout: FiltersLayout::Modal)
            ->filtersFormColumns(2)
            ->filtersFormWidth(Width::TwoExtraLarge)
            ->defaultSort('quantity', 'desc');
    }
}
