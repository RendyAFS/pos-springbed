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
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->visible(fn() => $user?->hasAnyRole(['Super Admin', 'Owner']) || $user?->store_setting_id === null)
                    ->searchable(),
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
