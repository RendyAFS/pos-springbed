<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use App\Helpers\RupiahHelper;
use Filament\Actions\BulkActionGroup;
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
        return $table
            ->columns([
                TextColumn::make('supplier_name')
                    ->searchable(),
                // var
                TextColumn::make('storeSetting.store_name')
                    ->label('Store')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->visible(fn() => Auth::user()?->store_setting_id === null)
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
                    ->date()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
            ])
            ->filters([
                TrashedFilter::make()->native(false),
            ])
            ->recordActions([
                EditAction::make(),
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
