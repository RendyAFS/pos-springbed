<?php

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

trait HasBackAction
{
    protected function getBackAction(): Action
    {
        return Action::make('back')
            ->label('Kembali')
            ->icon(Heroicon::ArrowLeft)
            ->color('gray')
            ->url(fn (): string => static::getResource()::getUrl('index'));
    }
}
