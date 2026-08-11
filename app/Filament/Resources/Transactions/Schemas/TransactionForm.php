<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Filament\Resources\Transactions\Schemas\Steps\CustomerStep;
use App\Filament\Resources\Transactions\Schemas\Steps\ProductsStep;
use App\Filament\Resources\Transactions\Schemas\Steps\PromoShippingStep;
use App\Filament\Resources\Transactions\Schemas\Steps\SummaryStep;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    CustomerStep::make(),
                    ProductsStep::make(),
                    PromoShippingStep::make(),
                    SummaryStep::make(),
                ])
                    ->skippable()
                    ->columnSpanFull(),
            ]);
    }
}
