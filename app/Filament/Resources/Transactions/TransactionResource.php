<?php

namespace App\Filament\Resources\Transactions;

use App\Filament\Resources\Transactions\Pages\CreateTransaction;
use App\Filament\Resources\Transactions\Pages\EditTransaction;
use App\Filament\Resources\Transactions\Pages\ListTransactions;
use App\Filament\Resources\Transactions\Schemas\TransactionForm;
use App\Filament\Resources\Transactions\Tables\TransactionsTable;
use App\Helpers\RupiahHelper;
use App\Models\Transaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?string $pluralLabel = 'Transactions';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function getGloballySearchableAttributes(): array
    {
        return ['transaction_code', 'customer.name', 'customer.phone'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->transaction_code;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Customer'        => $record->customer?->name,
            'Phone'           => $record->customer?->phone,
            'Total'           => RupiahHelper::format($record->grand_total),
            'Status'          => $record->status->getLabel(),
            'Courier'         => $record->transactionShipment?->courier?->name,
            'Payment Method'  => $record->transactionPayment?->method->getLabel(),
            'Payment Status'  => $record->transactionPayment?->status->getLabel(),
            'Shipment Status' => $record->transactionShipment?->status->getLabel(),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);

        $storeId = Auth::user()?->store_setting_id;

        if (! is_null($storeId)) {
            $query->where('store_setting_id', $storeId);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return TransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TransactionsTable::configure($table);
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
            'index' => ListTransactions::route('/'),
            'create' => CreateTransaction::route('/create'),
            'edit' => EditTransaction::route('/{record}/edit'),
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
