<?php

namespace App\Filament\Resources\StoreSubs;

use App\Enums\StoreSubTypeEnum;
use App\Filament\Resources\StoreSubs\Pages\ManageStoreSubs;
use App\Models\StoreSetting;
use App\Models\StoreSub;
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
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use BackedEnum;
use UnitEnum;

class StoreSubResource extends Resource
{
    protected static ?string $model = StoreSub::class;
    protected static ?string $navigationLabel = 'Sub Lokasi Toko';
    protected static ?string $pluralLabel = 'Sub Lokasi Toko';
    protected static string | UnitEnum | null $navigationGroup = 'Master Data';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-office-2';

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        $query = parent::getEloquentQuery()
            ->with(['store', 'parent']);

        if ($user && ! $user->hasAnyRole(['Super Admin', 'Owner'])) {
            if ($user->store_setting_id) {
                $query->where('store_id', $user->store_setting_id);
            }
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $schema
            ->components([
                Select::make('store_id')
                    ->label('Toko')
                    ->options(fn() => StoreSetting::pluck('store_name', 'id'))
                    ->default(fn() => $user?->store_setting_id)
                    ->searchable()
                    ->preload()
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $get, callable $set, ?StoreSub $record) {
                        if (! $record) {
                            $set('code', StoreSub::generateCode($state, $get('type')));
                        }
                    }),

                Select::make('type')
                    ->label('Tipe Sub Lokasi')
                    ->options(
                        collect(StoreSubTypeEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    )
                    ->default(StoreSubTypeEnum::FLOOR->value)
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function ($state, callable $get, callable $set, ?StoreSub $record) {
                        if (! $record) {
                            $set('code', StoreSub::generateCode($get('store_id'), $state));
                        }
                    }),

                TextInput::make('name')
                    ->label('Nama Sub Lokasi')
                    ->placeholder('Contoh: Lantai 1, Rak A1, dsb.')
                    ->required()
                    ->maxLength(255),

                TextInput::make('code')
                    ->label('Kode Sub Lokasi')
                    ->disabled()
                    ->dehydrated()
                    ->default(function (callable $get) use ($user) {
                        $storeId = $get('store_id') ?? $user?->store_setting_id ?? StoreSetting::first()?->id ?? 1;
                        $type = $get('type') ?? StoreSubTypeEnum::FLOOR->value;
                        return StoreSub::generateCode($storeId, $type);
                    }),

                Select::make('parent_id')
                    ->label('Parent / Induk (Opsional)')
                    ->options(function (callable $get, ?StoreSub $record) {
                        $storeId = $get('store_id');
                        if (! $storeId) {
                            return [];
                        }

                        return StoreSub::query()
                            ->where('store_id', $storeId)
                            ->when($record, fn($q) => $q->where('id', '!=', $record->id))
                            ->pluck('name', 'id');
                    })
                    ->placeholder('Pilih Induk jika ini adalah sub-rak/lantai')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->fontFamily(FontFamily::Mono)
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('store.store_name')
                    ->label('Toko')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Sub Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state instanceof StoreSubTypeEnum ? $state->getLabel() : (StoreSubTypeEnum::tryFrom($state)?->getLabel() ?? $state))
                    ->color(fn($state) => match ($state instanceof StoreSubTypeEnum ? $state->value : $state) {
                        'Floor' => 'primary',
                        'Rack'  => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label('Induk (Parent)')
                    ->default('-')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('store_id')
                    ->label('Toko')
                    ->options(fn() => StoreSetting::pluck('store_name', 'id'))
                    ->native(false),

                SelectFilter::make('type')
                    ->label('Tipe')
                    ->options(
                        collect(StoreSubTypeEnum::cases())
                            ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                            ->toArray()
                    )
                    ->native(false),

                TrashedFilter::make()->native(false)->label('Data Yang Ditampilkan'),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Sub Lokasi Toko'),
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
            'index' => ManageStoreSubs::route('/'),
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
