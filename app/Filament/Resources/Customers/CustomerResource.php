<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\ManageCustomers;
use App\Helpers\RupiahHelper;
use App\Helpers\WilayahHelper;
use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $navigationLabel = 'Customers';
    protected static ?string $pluralLabel = 'Customers';
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
            'Telepone' => $record->phone,
            'Alamat' => $record->address,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([

                Grid::make(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),

                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->required(),

                        Select::make('city_code')
                            ->label('Kota/Kabupaten')
                            ->options(fn() => WilayahHelper::getAllRegencies())
                            ->searchable()
                            ->live()
                            ->native(false)
                            ->afterStateUpdated(function (?string $state, callable $set) {
                                $regencies = WilayahHelper::getAllRegencies();
                                $provinceCode = WilayahHelper::provinceCodeFromRegencyCode($state);

                                $set('city_name', $regencies[$state] ?? null);
                                $set('province_code', $provinceCode);
                                $set('province_name', $provinceCode ? (WilayahHelper::getProvinces()[$provinceCode] ?? null) : null);

                                $set('district_name', null);
                            }),

                        Select::make('district_code')
                            ->label('Kecamatan')
                            ->options(fn(callable $get) => WilayahHelper::getDistricts($get('city_code')))
                            ->searchable()
                            ->live()
                            ->native(false)
                            ->placeholder(fn(callable $get) => blank($get('city_code'))
                                ? 'Pilih Kota terlebih dahulu'
                                : 'Pilih Kecamatan')
                            ->disabled(fn(callable $get) => blank($get('city_code')))
                            ->afterStateUpdated(fn(?string $state, callable $get, callable $set) => $set(
                                'district_name',
                                WilayahHelper::getDistricts($get('city_code'))[$state] ?? null
                            )),


                        Textarea::make('address')
                            ->label('Alamat')
                            ->helperText('Detail alamat (jalan, No. rumah, RT/RW, patokan, dll) yang tidak tercakup di data Kecamatan/Kota di atas.')
                            ->rows(3)
                            ->default(null),

                        Hidden::make('province_code'),
                        Hidden::make('province_name'),
                        Hidden::make('city_name'),
                        Hidden::make('district_name'),
                    ])->columnSpan(fn($record) => $record === null ? 'full' : 1),
                Section::make('Referal')
                    ->visible(fn($record) => $record !== null)
                    ->relationship('referal')
                    ->description('Informasi referal customer')
                    ->collapsible()
                    ->schema([
                        TextEntry::make('referal_code')
                            ->label('Kode Referal')
                            ->default('-'),

                        TextEntry::make('discount_amount')
                            ->label('Saldo Diskon')
                            ->numeric()
                            ->formatStateUsing(fn($state) => RupiahHelper::format($state)),
                    ]),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Customer')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable(),
                TextColumn::make('district_name')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('city_name')
                    ->label('Kota/Kabupaten')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->searchable()
                    ->limit(20),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                TrashedFilter::make()->native(false)->label('Data Yang di Tampilkan'),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Customer'),
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
            'index' => ManageCustomers::route('/'),
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
