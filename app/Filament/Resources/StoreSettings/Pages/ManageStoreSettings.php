<?php

namespace App\Filament\Resources\StoreSettings\Pages;

use App\Filament\Resources\StoreSettings\StoreSettingResource;
use Spatie\Permission\Models\Role;
use App\Models\RoleAccessHour;
use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Enums\Width;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Icons\Heroicon;

class ManageStoreSettings extends ManageRecords
{
    protected static string $resource = StoreSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('set_role_access_hour')
                ->label('Jam Akses Role')
                ->modalWidth(Width::FiveExtraLarge)
                ->icon(Heroicon::Clock)
                ->color('info')
                ->fillForm(function (): array {
                    return [
                        'access_hours' => RoleAccessHour::query()
                            ->with('role')
                            ->get()
                            ->map(fn(RoleAccessHour $item) => [
                                'role_id' => $item->role_id,
                                'is_active' => $item->is_active,
                                'start_time' => $item->start_time,
                                'end_time' => $item->end_time,
                            ])
                            ->toArray(),
                    ];
                })
                ->schema([
                    Repeater::make('access_hours')
                        ->label('Setting Jam Akses per Role')
                        ->schema([
                            Select::make('role_id')
                                ->label('Role')
                                ->options(
                                    fn() => Role::query()
                                        ->where('name', '!=', 'Super Admin')
                                        ->pluck('name', 'id')
                                )
                                ->searchable()
                                ->required()
                                ->distinct()
                                ->live()
                                ->columnSpan(4),
                            Toggle::make('is_active')
                                ->label('Aktifkan ')
                                ->live()
                                ->inline(false)
                                ->default(true)
                                ->columnSpan(2),
                            TimePicker::make('start_time')
                                ->label('Jam Mulai')
                                ->seconds(false)
                                ->native(false)
                                ->required(fn(Get $get) => $get('is_active'))
                                ->visible(fn(Get $get) => $get('is_active'))
                                ->columnSpan(3),
                            TimePicker::make('end_time')
                                ->label('Jam Selesai')
                                ->seconds(false)
                                ->native(false)
                                ->required(fn(Get $get) => $get('is_active'))
                                ->visible(fn(Get $get) => $get('is_active'))
                                ->columnSpan(3),
                        ])
                        ->columns(12)
                        ->addActionLabel('Tambah Role')
                        ->reorderable(false)
                        ->deleteAction(
                            fn($action) => $action->requiresConfirmation()
                        ),
                ])
                ->modalHeading('Setting Jam Akses Login per Role')
                ->modalDescription('Role yang tidak masuk daftar di sini dianggap tidak dibatasi jam aksesnya (bebas login kapan saja).')
                ->modalSubmitActionLabel('Simpan')
                ->modalIcon(Heroicon::Clock)
                ->action(function (array $data): void {
                    $submittedRoleIds = collect($data['access_hours'] ?? [])->pluck('role_id')->filter();

                    foreach ($data['access_hours'] ?? [] as $row) {
                        if (empty($row['role_id'])) {
                            continue;
                        }

                        RoleAccessHour::updateOrCreate(
                            ['role_id' => $row['role_id']],
                            [
                                'is_active' => $row['is_active'] ?? false,
                                'start_time' => ($row['is_active'] ?? false) ? $row['start_time'] : null,
                                'end_time' => ($row['is_active'] ?? false) ? $row['end_time'] : null,
                            ]
                        );
                    }

                    RoleAccessHour::query()
                        ->whereNotIn('role_id', $submittedRoleIds)
                        ->delete();

                    Notification::make()
                        ->title('Berhasil')
                        ->body('Jam akses login per role berhasil disimpan.')
                        ->success()
                        ->send();
                }),
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

            CreateAction::make()
                ->label('Tambah Toko')
                ->modalHeading('Tambah Toko'),
        ];
    }
}
