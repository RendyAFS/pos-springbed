<?php

namespace App\Filament\Resources\Promos\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\Promos\PromoResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePromo extends CreateRecord
{
    use HasBackAction;

    protected static string $resource = PromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
