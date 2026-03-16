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

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('supplier_name')
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
                TrashedFilter::make(),
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
