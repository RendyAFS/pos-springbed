<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use App\Helpers\RupiahHelper;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        /** @var \App\Models\User|null $user */
           $user = Auth::user();
        return $table
            ->columns([
                TextColumn::make('supplier_name')
                ->label('Nama Supplier')
                ->searchable(),
                TextColumn::make('storeSetting.store_name')
                    ->label('Toko')
                    ->html()
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('store_setting_id', $direction))
                    ->visible(fn() => $user?->hasAnyRole(['Super Admin', 'Owner']) || $user?->store_setting_id === null)
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('storeSetting', fn ($q) => $q->where('store_name', 'like', "%{$search}%"))
                            ->orWhereHas('storeSub', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                    })
                    ->formatStateUsing(function ($state, $record) {
                        $storeName = $record->storeSetting?->store_name;
                        $subName   = $record->storeSub?->name;

                        if (!$storeName && !$subName) {
                            return '-';
                        }

                        $html = "<div class='flex flex-col gap-1 items-start py-1'>";
                        if ($storeName) {
                            $html .= "<span class='fi-badge inline-flex items-center justify-center rounded-md text-xs font-medium ring-1 ring-inset px-2 py-0.5 bg-gray-50 text-gray-600 ring-gray-600/20 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20'>{$storeName}</span>";
                        }
                        if ($subName) {
                            $html .= "<span class='fi-badge inline-flex items-center justify-center rounded-md text-xs font-medium ring-1 ring-inset px-2 py-0.5 bg-sky-50 text-sky-700 ring-sky-600/20 dark:bg-sky-400/10 dark:text-sky-400 dark:ring-sky-400/30'>{$subName}</span>";
                        }
                        $html .= "</div>";

                        return new HtmlString($html);
                    }),
                TextColumn::make('invoice_number')
                    ->fontFamily(FontFamily::Mono)
                    ->searchable()
                    ->badge()
                    ->color('primary')
                    ->icon(Heroicon::ClipboardDocumentList)
                    ->iconPosition(IconPosition::After)
                    ->copyable()
                    ->copyMessage('SKU copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('purchase_date')
                    ->label('Tanggal Pesanan')
                    ->date()
                    ->sortable(),
                TextColumn::make('delivery_order_number')
                    ->label('No. Surat Jalan')
                    ->searchable()
                    ->badge()
                    ->color('primary')
                    ->icon(Heroicon::Document)
                    ->iconPosition(IconPosition::After)
                    ->copyable()
                    ->copyMessage('Nomor Surat Jalan copied')
                    ->copyMessageDuration(1500)
                    ->description(
                        fn($record) => $record->taxpayer_name
                    ),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->visible(fn() => ! $user?->hasRole('Staff'))
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
            ])
            ->filters([
                TrashedFilter::make()->native(false)->label('Data Yang di Tampilkan'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
