<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->helperText('Leave empty if you do not want to change the password')
                            ->dehydrated(fn($state) => filled($state))
                            ->dehydrateStateUsing(
                                fn($state) => filled($state)
                                    ? Hash::make($state)
                                    : null
                            )
                            ->required(fn(string $context) => $context === 'create')
                            ->afterStateHydrated(fn($component) => $component->state(null)),

                        TextInput::make('password_confirmation')
                            ->password()
                            ->label('Confirm Password')
                            ->dehydrated(fn($state) => filled($state))
                            ->same('password')
                            ->required(fn(string $context) => $context === 'create')
                            ->afterStateHydrated(fn($component) => $component->state(null)),
                    ]),

                Section::make('Access & Security')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Select::make('roles')
                            ->label('Role')
                            ->relationship(
                                name: 'roles',
                                titleAttribute: 'name'
                            )
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->offIcon(Heroicon::XMark)
                            ->onIcon(Heroicon::Check)
                            ->offColor('danger')
                            ->onColor('success')
                            ->inline(false)
                            ->default(true),
                    ])
                    ->footerActions([
                        Action::make('resetPassword')
                            ->label('Reset Password')
                            ->tooltip('Reset Password ke default "12345678"')
                            ->icon('heroicon-o-arrow-path')
                            ->color('warning')
                            ->outlined()
                            ->requiresConfirmation()
                            ->visible(fn(string $context) => $context === 'edit')
                            ->action(function ($record) {
                                $record->update([
                                    'password' => Hash::make('12345678'),
                                ]);
                            }),
                    ])->footerActionsAlignment(Alignment::Right),
            ]);
    }
}
