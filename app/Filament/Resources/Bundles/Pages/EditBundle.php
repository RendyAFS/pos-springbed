<?php

namespace App\Filament\Resources\Bundles\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\Bundles\BundleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBundle extends EditRecord
{
    use HasBackAction;

    protected static string $resource = BundleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackAction(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
