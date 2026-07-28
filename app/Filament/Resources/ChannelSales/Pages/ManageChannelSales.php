<?php

namespace App\Filament\Resources\ChannelSales\Pages;

use App\Filament\Resources\ChannelSales\ChannelSaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageChannelSales extends ManageRecords
{
    protected static string $resource = ChannelSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Channel Penjualan')
                ->modalHeading('Tambah Channel Penjualan'),
        ];
    }
}
