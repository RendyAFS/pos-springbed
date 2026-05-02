<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\StoreSetting;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->resolveStoreSettingId($data);

        return $data;
    }

    private function resolveStoreSettingId(array $data): array
    {
        $selected = $data['store_selected'] ?? [];

        if (count($selected) === 1) {
            $store = StoreSetting::where('store_name', reset($selected))->first();
            $data['store_setting_id'] = $store?->id;
        }

        return $data;
    }
}
