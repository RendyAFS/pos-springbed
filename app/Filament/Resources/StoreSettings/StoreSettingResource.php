<?php

namespace App\Filament\Resources\StoreSettings;

use App\Filament\Resources\StoreSettings\Pages\ManageStoreSettings;
use App\Helpers\RupiahHelper;
use App\Models\StoreSetting;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class StoreSettingResource extends Resource
{
    protected static ?string $model = StoreSetting::class;
    protected static ?string $navigationLabel = 'Store Settings';
    protected static ?string $pluralLabel = 'Store Settings';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getGloballySearchableAttributes(): array
    {
        return ['store_name'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->store_name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Phone'  => $record->phone,
            'Email'  => $record->email,
            'Address'  => $record->address,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Store Identity')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('store_logo')
                            ->label('Store Logo')
                            ->nullable()
                            ->image()
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->disk('public')
                            ->collection('store_logo')
                            ->maxSize(2048)
                            ->columnSpan(1),
                        TextInput::make('company_name')
                            ->label('Company Name')
                            ->default('Serba Indah')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('store_name')
                            ->label('Active Store Name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Grid::make(1)
                    ->schema([
                        TextInput::make('phone')
                            ->label('Phone')
                            ->numeric()
                            ->nullable()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->nullable()
                            ->maxLength(255),
                    ]),
                Grid::make(1)
                    ->schema([
                        TextInput::make('set_max_reward')
                            ->label('Set Max Reward')
                            ->numeric()
                            ->required(),
                        Textarea::make('address')
                            ->label('Address')
                            ->nullable()
                            ->required(),
                    ]),
                SpatieMediaLibraryFileUpload::make('homepage_banner')
                    ->label('Homepage Banner')
                    ->nullable()
                    ->image()
                    ->imageEditor()
                    ->openable()
                    ->downloadable()
                    ->disk('public')
                    ->collection('homepage_banner')
                    ->maxSize(2048)
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('store_logo')
                    ->label('Logo')
                    ->defaultImageUrl(asset('assets/images/favicon.png'))
                    ->extraImgAttributes([
                        'class' => 'rounded-md'
                    ])
                    ->limit(1)
                    ->disk('public')
                    ->collection('store_logo')
                    ->imageWidth(44)
                    ->imageHeight(44),
                TextColumn::make('company_name')
                    ->label('Company Name')
                    ->searchable(),
                TextColumn::make('store_name')
                    ->label('Active Store')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),
                TextColumn::make('email')
                    ->badge()
                    ->color('primary')
                    ->label('Email')
                    ->searchable()
                    ->icon(Heroicon::ClipboardDocumentList)
                    ->iconPosition(IconPosition::After)
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('address')
                    ->label('Address')
                    ->limit(25)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('set_max_reward')
                    ->label('Maximal Reward')
                    ->alignCenter()
                    ->weight('medium')
                    ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                SpatieMediaLibraryImageColumn::make('homepage_banner')
                    ->defaultImageUrl(asset('assets/images/logo-no-image.png'))
                    ->extraImgAttributes([
                        'class' => 'rounded-md'
                    ])
                    ->limit(1)
                    ->disk('public')
                    ->collection('homepage_banner')
                    ->imageWidth(110)
                    ->imageHeight(100),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => ManageStoreSettings::route('/'),
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
