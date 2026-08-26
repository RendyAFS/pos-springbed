<?php

namespace App\Filament\Resources\PurchaseOrders;

use App\Filament\Resources\PurchaseOrders\Pages\CreatePurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\EditPurchaseOrder;
use App\Filament\Resources\PurchaseOrders\Pages\ListPurchaseOrders;
use App\Filament\Resources\PurchaseOrders\Schemas\PurchaseOrderForm;
use App\Filament\Resources\PurchaseOrders\Tables\PurchaseOrdersTable;
use App\Helpers\RupiahHelper;
use App\Models\PurchaseOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;
    protected static ?string $navigationLabel = 'Pembelian Stok Produk';
    protected static ?string $heading = 'Pembelian Stok Produk';
    protected static ?string $pluralLabel = 'Pembelian Stok Produk';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-shopping-bag';

    public static function getGloballySearchableAttributes(): array
    {
        return ['supplier_name', 'invoice_number', 'delivery_order_number'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->supplier_name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Invoice Number' => $record->invoice_number,
            'No Surat Jalan' => $record->delivery_order_number,
            'Tanggal Pembelian' => $record->purchase_date,
            'Total' => RupiahHelper::format($record->total_amount),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && ! $user->hasAnyRole(['Super Admin', 'Owner'])) {
            $storeId = $user->store_setting_id;
            if ($storeId) {
                $query->whereHas('storeSetting', function ($q) use ($storeId) {
                    $q->where('id', $storeId);
                });
            }
        }

        return $query->orderByDesc('id');
    }

    public static function form(Schema $schema): Schema
    {
        return PurchaseOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PurchaseOrdersTable::configure($table);
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
            'index' => ListPurchaseOrders::route('/'),
            'create' => CreatePurchaseOrder::route('/create'),
            'edit' => EditPurchaseOrder::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
