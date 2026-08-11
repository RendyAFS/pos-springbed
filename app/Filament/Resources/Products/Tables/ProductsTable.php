<?php

namespace App\Filament\Resources\Products\Tables;

use App\Helpers\RupiahHelper;
use Filament\Actions\Action;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
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
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {

                        $type = $record->type?->name ?? '-';
                        $brand = $record->brand?->name ?? '-';

                        return new HtmlString("
                        <div class='flex flex-col'>
                            <span>{$state}</span>
                            <span class='text-sm text-gray-500'>{$type}</span>
                            <span class='text-sm text-gray-500'>{$brand}</span>
                        </div>
                    ");
                    }),
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
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('size.name')
                    ->label('Ukuran')
                    ->searchable(),
                TextColumn::make('selling_price')
                    ->label('Harga Jual')
                    ->sortable()
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->state(function ($record) {
                        $storeId = Auth::user()?->store_setting_id;

                        if ($storeId) {
                            return $record->inventoryStocks
                                ->where('store_setting_id', $storeId)
                                ->sum('quantity');
                        }

                        return $record->inventoryStocks->sum('quantity');
                    })
                    ->sortable(
                        query: function ($query, string $direction) {

                            $storeId = Auth::user()?->store_setting_id;

                            return $query
                                ->withSum(
                                    ['inventoryStocks as stock' => function ($q) use ($storeId) {
                                        if ($storeId) {
                                            $q->where('store_setting_id', $storeId);
                                        }
                                    }],
                                    'quantity'
                                )
                                ->orderBy('stock', $direction);
                        }
                    ),
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->offIcon(Heroicon::XMark)
                    ->onIcon(Heroicon::Check)
                    ->offColor('danger')
                    ->onColor('success'),
            ])
            ->filters([
                TrashedFilter::make()->native(false)->label('Data Yang di Tampilkan'),

                SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('type_id')
                    ->label('Tipe')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('size_id')
                    ->label('Ukuran')
                    ->relationship('size', 'name')
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::Modal)
            ->recordActions([
                ActionGroup::make([
                    Action::make('barcode')
                        ->label('Barcode')
                        ->icon(Heroicon::QrCode)
                        ->color('gray')
                        ->modalHeading(fn($record) => "Barcode Produk — {$record->name}")
                        ->modalWidth('sm')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->modalContent(function ($record) {

                            $record->loadMissing([
                                'brand',
                                'size',
                                'type',
                            ]);

                            $result = (new Builder(
                                writer: new PngWriter(),
                                data: (string) $record->id,
                                encoding: new Encoding('UTF-8'),
                                errorCorrectionLevel: ErrorCorrectionLevel::High,
                                size: 300,
                                margin: 10,
                            ))->build();

                            return view('filament.components.products.barcode-modal', [
                                'record'  => $record,
                                'dataUri' => $result->getDataUri(),
                            ]);
                        }),
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
