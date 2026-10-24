<?php

declare(strict_types=1);

namespace App\Filament\Resources\Roles\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\Roles\RoleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRole extends ViewRecord
{
    use HasBackAction;

    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackAction(),
            EditAction::make(),
        ];
    }
}
