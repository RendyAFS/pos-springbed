<?php

namespace App\Filament\Resources\InventoryStocks\Tables;

use App\Helpers\RupiahHelper;
use App\Models\StoreSetting;
use Filament\Tables\Table;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class InventoryStocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('storeSetting.store_name')
                    ->label('Toko')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->visible(fn() => Auth::user()?->store_setting_id === null),

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
                    ->weight('bold'),

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
                SelectFilter::make('store_setting_id')
                    ->label('Toko')
                    ->relationship('storeSetting', 'store_name')
                    ->searchable()
                    ->preload()
                    ->visible(function () {
                        /** @var User $user */
                        $user = Auth::user();

                        return $user?->hasAnyRole(['Super Admin', 'Owner'])
                            || $user?->store_setting_id === null;
                    }),
            ])
            ->defaultSort('quantity', 'desc');
    }
}
