<?php

namespace App\Filament\Resources\Couriers;

use App\Enums\TypeCourierEnum;
use App\Filament\Resources\Couriers\Pages\ManageCouriers;
use App\Helpers\RupiahHelper;
use App\Models\Courier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CourierResource extends Resource
{
    protected static ?string $model = Courier::class;
    protected static ?string $slug = 'couriers';
    protected static ?string $navigationLabel = 'Courier';
    protected static ?string $pluralLabel = 'Courier';
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Is Active'  => $record->is_active ? 'Active' : 'Inactive',
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->options(
                        collect(TypeCourierEnum::cases())
                            ->mapWithKeys(fn($case) => [
                                $case->value => $case->getLabel()
                            ])
                            ->toArray()
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('shipping_cost')
                    ->numeric()
                    ->required()
                    ->prefix('Rp.'),
                Toggle::make('is_active')
                    ->label('Is Active')
                    ->offIcon(Heroicon::XMark)
                    ->onIcon(Heroicon::Check)
                    ->offColor('danger')
                    ->onColor('success')
                    ->inline(false)
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Courier')
            ->columns([
                TextColumn::make('name')
                    ->label('Courier')
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn($state) => $state?->getLabel())
                    ->badge()
                    ->color('primary'),
                TextColumn::make('shipping_cost')
                    ->label('Shipping Cost')
                    ->sortable()
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
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
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCouriers::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
