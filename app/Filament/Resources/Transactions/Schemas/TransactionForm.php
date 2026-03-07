<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Enums\TransactionStatusEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('customer_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('transaction_code')
                    ->default(null),
                TextInput::make('subtotal')
                    ->numeric()
                    ->default(null),
                TextInput::make('discount_total')
                    ->numeric()
                    ->default(null),
                TextInput::make('shiping_cost')
                    ->numeric()
                    ->default(null)
                    ->prefix('$'),
                TextInput::make('grand_total')
                    ->numeric()
                    ->default(null),
                Select::make('status')
                    ->options(TransactionStatusEnum::class)
                    ->default(null),
                TextInput::make('promo_id')
                    ->numeric()
                    ->default(null),
                DatePicker::make('transaction_date'),
                TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('updated_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('deleted_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
