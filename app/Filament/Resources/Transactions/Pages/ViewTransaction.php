<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Concerns\HasBackAction;
use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    use HasBackAction;

    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getBackAction(),
            EditAction::make(),
        ];
    }
}
