<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\StoreSetting;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->resolveStoreSettingId($data);

        return $data;
    }

    private function resolveStoreSettingId(array $data): array
    {
        $selected = $data['selected_store'] ?? [];

        if (count($selected) === 1) {
            $store = StoreSetting::where('store_name', reset($selected))->first();
            $data['store_setting_id'] = $store?->id;
        }

        return $data;
    }
}
