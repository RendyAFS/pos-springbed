<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('supplier_name')
                    ->default(null),
                TextInput::make('invoice_number')
                    ->default(null),
                DateTimePicker::make('purchase_date'),
                TextInput::make('total_amount')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
