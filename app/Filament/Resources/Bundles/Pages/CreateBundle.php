<?php

namespace App\Filament\Resources\Bundles\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\Bundles\BundleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBundle extends CreateRecord
{
    use HasBackAction;

    protected static string $resource = BundleResource::class;

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
