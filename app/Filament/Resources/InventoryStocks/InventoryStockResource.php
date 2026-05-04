<?php

namespace App\Filament\Resources\InventoryStocks;

use App\Filament\Resources\InventoryStocks\Pages\CreateInventoryStock;
use App\Filament\Resources\InventoryStocks\Pages\EditInventoryStock;
use App\Filament\Resources\InventoryStocks\Pages\ListInventoryStocks;
use App\Filament\Resources\InventoryStocks\Pages\ViewInventoryStock;
use App\Filament\Resources\InventoryStocks\Schemas\InventoryStockForm;
use App\Filament\Resources\InventoryStocks\Schemas\InventoryStockInfolist;
use App\Filament\Resources\InventoryStocks\Tables\InventoryStocksTable;
use App\Helpers\RupiahHelper;
use App\Models\InventoryStock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InventoryStockResource extends Resource
{
    protected static ?string $model = InventoryStock::class;
    protected static ?string $navigationLabel = 'Inventory';
    protected static ?string $pluralLabel = 'Inventory';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'product.name',
            'product.sku',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->product?->name ?? '-';
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $product = $record->product;

        return [
            'Store'         => $product?->storeSetting?->store_name,
            'SKU'           => $product?->sku,
            'Type'          => $product?->type?->name,
            'Category'      => $product?->category?->name,
            'Brand'         => $product?->brand?->name,
            'Stock'         => ($record->quantity ?? 0) . ' pcs',
            'Selling Price' => RupiahHelper::format($product?->selling_price ?? 0),
            'Total Value'   => RupiahHelper::format(($record->quantity ?? 0) * ($product?->selling_price ?? 0)),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'product.brand',
                'product.category',
            ]);

        $storeId = Auth::user()?->store_setting_id;

        if ($storeId) {
            $query->where('store_setting_id', $storeId);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return InventoryStockForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InventoryStockInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryStocksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryStocks::route('/'),
            'create' => CreateInventoryStock::route('/create'),
            'view' => ViewInventoryStock::route('/{record}'),
            'edit' => EditInventoryStock::route('/{record}/edit'),
        ];
    }
}
