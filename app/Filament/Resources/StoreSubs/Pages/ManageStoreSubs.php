<?php

namespace App\Filament\Resources\StoreSubs\Pages;

use App\Filament\Resources\StoreSubs\StoreSubResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStoreSubs extends ManageRecords
{
    protected static string $resource = StoreSubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Sub Lokasi')
                ->modalHeading('Tambah Sub Lokasi Toko'),
        ];
    }
}
