<?php

namespace App\Filament\Resources\Promos\Schemas;

use App\Enums\PromoDiscountEnum;
use App\Enums\PromoTypeEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\Auth;

class PromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Promo')
                    ->description('Informasi dasar tentang promo')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Promo')
                            ->required()
                            ->maxLength(100),
                        Select::make('type')
                            ->label('Jenis Promo')
                            ->options(
                                collect(PromoTypeEnum::cases())
                                    ->mapWithKeys(fn($case) => [
                                        $case->value => $case->getLabel()
                                    ])
                                    ->toArray()
                            )
                            ->required()
                            ->native(false),
                        Select::make('products')
                            ->relationship(
                                name: 'products',
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query) {
                                    $storeId = Auth::user()?->store_setting_id;
                                    if ($storeId) {
                                        $query->where('store_setting_id', $storeId);
                                    }
                                }
                            )
                            ->preload()
                            ->searchable()
                            ->nullable()
                            ->label('Produk')
                            ->helperText('Biarkan kosong jika promo berlaku untuk semua produk')
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make('Pengaturan Diskon')
                    ->description('Atur bagaimana diskon akan diterapkan')
                    ->schema([
                        Select::make('discount_type')
                            ->label('Jenis Diskon')
                            ->options(
                                collect(PromoDiscountEnum::cases())
                                    ->mapWithKeys(fn($case) => [
                                        $case->value => $case->getLabel()
                                    ])
                                    ->toArray()
                            )
                            ->required()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($set) {
                                $set('discount_value', 0);
                            })
                            ->columnSpanFull(),
                        TextInput::make('discount_value')
                            ->label('Nilai Diskon')
                            ->required()
                            ->default(0)
                            ->live()
                            ->mask(
                                fn(Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::NOMINAL->value
                                    ? RawJs::make('$money($input, \',\', \'.\', 0)')
                                    : null
                            )
                            ->dehydrateStateUsing(
                                fn($state, Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::NOMINAL->value
                                    ? ($state ? (float) str_replace('.', '', $state) : null)
                                    : $state
                            )
                            ->formatStateUsing(
                                fn($state, Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::NOMINAL->value
                                    ? ($state ? number_format((float) $state, 0, ',', '.') : null)
                                    : $state
                            )
                            ->suffix(
                                fn(Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? '%'
                                    : null
                            )
                            ->prefix(
                                fn(Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::NOMINAL->value
                                    ? 'Rp.'
                                    : null
                            )
                            ->minValue(
                                fn(Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? 0.1
                                    : 1
                            )
                            ->maxValue(
                                fn(Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? 100
                                    : null
                            )
                            ->step(
                                fn(Get $get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? 0.1
                                    : 1
                            ),
                        TextInput::make('min_purchase')
                            ->label('Minimum Pembelian')
                            ->mask(RawJs::make('$money($input, \',\', \'.\', 0)'))
                            ->dehydrateStateUsing(fn($state) => $state ? (float) str_replace('.', '', $state) : null)
                            ->formatStateUsing(fn($state) => $state ? number_format((float) $state, 0, ',', '.') : null)
                            ->prefix('Rp.')
                            ->default(0)
                            ->helperText('Jumlah minimum pembelian untuk mengaktifkan promo ini'),
                    ])
                    ->columns(2),

                Section::make('Jadwal')
                    ->description('Atur kapan promo aktif')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('start_date')
                                    ->label('Tanggal Mulai')
                                    ->native(false)
                                    ->seconds(false)
                                    ->suffixIcon(Heroicon::Calendar)
                                    ->closeOnDateSelection()
                                    ->live(),

                                DateTimePicker::make('end_date')
                                    ->label('Tanggal Berakhir')
                                    ->native(false)
                                    ->seconds(false)
                                    ->suffixIcon(Heroicon::Calendar)
                                    ->closeOnDateSelection()
                                    ->after('start_date')
                                    ->minDate(fn(Get $get) => $get('start_date')),
                            ]),
                        Toggle::make('is_active')
                            ->label('Active Promo')
                            ->offIcon(Heroicon::XMark)
                            ->onIcon(Heroicon::Check)
                            ->offColor('danger')
                            ->onColor('success')
                            ->inline(false)
                            ->default(true),
                    ]),

                Section::make('Pembatasan Penggunaan')
                    ->description('Batas penggunaan promo')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('usage_limit')
                                    ->label('Batas Penggunaan')
                                    ->numeric()
                                    ->placeholder('Unlimited if empty'),
                                TextInput::make('usage_count')
                                    ->label('Jumlah Penggunaan')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),

                    ]),
            ]);
    }
}
