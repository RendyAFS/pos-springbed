<?php

namespace App\Filament\Resources\Products\Tables;

use App\Helpers\RupiahHelper;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {

                        $type = $record->type?->getLabel() ?? '-';
                        $brand = $record->brand?->name ?? '-';

                        return new HtmlString("
                        <div class='flex flex-col'>
                            <span>{$state}</span>
                            <span class='text-sm text-gray-500'>{$type}</span>
                            <span class='text-sm text-gray-500'>{$brand}</span>
                        </div>
                    ");
                    }),
                TextColumn::make('storeSetting.store_name')
                    ->label('Store')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->fontFamily(FontFamily::Mono)
                    ->searchable()
                    ->icon(Heroicon::ClipboardDocumentList)
                    ->iconPosition(IconPosition::After)
                    ->copyable()
                    ->copyMessage('SKU copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn($state) => $state?->getLabel())
                    ->searchable(),
                TextColumn::make('selling_price')
                    ->label('Selling Price')
                    ->sortable()
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                TextColumn::make('inventoryStocks.quantity')
                    ->label('Stock')
                    ->badge()
                    ->color(function ($state) {
                        if ($state <= 5) {
                            return 'danger';
                        }
                        if ($state <= 10) {
                            return 'warning';
                        }
                        return 'success';
                    })
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->offIcon(Heroicon::XMark)
                    ->onIcon(Heroicon::Check)
                    ->offColor('danger')
                    ->onColor('success'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])
                    ->icon(Heroicon::OutlinedEllipsisHorizontal)
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
