<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use STS\FilamentImpersonate\Actions\Impersonate;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereDoesntHave('roles', function (Builder $q) {
                    $q->where('name', 'Super Admin');
                });
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),

                TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->offIcon(Heroicon::XMark)
                    ->onIcon(Heroicon::Check)
                    ->offColor('danger')
                    ->onColor('success'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Impersonate::make()
                        ->redirectTo(route('filament.admin.pages.dashboard')),
                    DeleteAction::make(),
                ])
                    ->icon(Heroicon::OutlinedEllipsisHorizontal)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
