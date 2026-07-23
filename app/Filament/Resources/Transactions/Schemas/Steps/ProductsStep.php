<?php

namespace App\Filament\Resources\Transactions\Schemas\Steps;

use App\Filament\Resources\Transactions\Schemas\Components\TransactionItemSchema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

class ProductsStep
{
    public static function make(): Step
    {
        return Step::make('Products')
            ->label('Produk')
            ->description('Pilih produk atau paket yang akan dibeli.')
            ->icon(Heroicon::ShoppingCart)
            ->completedIcon(Heroicon::CheckCircle)
            ->schema([
                Section::make('Produk')
                    ->description('Pilih produk atau paket yang akan dibeli.')
                    ->icon(Heroicon::CubeTransparent)
                    ->schema([
                        TransactionItemSchema::make(),
                    ]),
            ]);
    }
}
