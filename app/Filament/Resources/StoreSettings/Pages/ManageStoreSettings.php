<?php

namespace App\Filament\Resources\StoreSettings\Pages;

use App\Filament\Resources\StoreSettings\StoreSettingResource;
use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\Width;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;

class ManageStoreSettings extends ManageRecords
{
    protected static string $resource = StoreSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('update_all_max_reward')
                ->label('Set Max Reward')
                ->icon(Heroicon::CurrencyDollar)
                ->color('warning')
                ->schema([
                    TextInput::make('set_max_reward')
                        ->label('Max Reward (Rp)')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->prefix('Rp')
                        ->helperText('Nilai ini akan diterapkan ke semua store yang ada.'),
                ])
                ->modalHeading('Update Max Reward Semua Store')
                ->modalDescription('Masukkan nilai max reward yang akan diterapkan ke seluruh store.')
                ->modalSubmitActionLabel('Terapkan ke Semua Store')
                ->modalIcon(Heroicon::CurrencyDollar)
                ->modalWidth(Width::ExtraLarge)
                ->action(function (array $data): void {
                    StoreSetting::query()->update([
                        'set_max_reward' => $data['set_max_reward'],
                    ]);

                    Notification::make()
                        ->title('Berhasil')
                        ->body('Max reward semua store berhasil diperbarui.')
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
