<?php

namespace App\Filament\Resources\Transactions\Schemas\Steps;

use App\Enums\TransactionStatusEnum;
use App\Helpers\WilayahHelper;
use App\Models\Customer;
use App\Models\StoreSetting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomerStep
{
    public static function make(): Step
    {
        return Step::make('Customer')
            ->label('Customer')
            ->description('Silahkan pilih customer dan tanggal transaksi.')
            ->icon(Heroicon::User)
            ->completedIcon(Heroicon::CheckCircle)
            ->schema([
                Section::make('Info Transaksi')
                    ->description('Masukkan data transaksi dan customer.')
                    ->icon(Heroicon::DocumentText)
                    ->columns(2)
                    ->schema([
                        TextInput::make('transaction_code')
                            ->label('Kode Transaksi')
                            ->default(fn(): string => 'TRX' . strtoupper(Str::random(8)) . now()->format('Ymd'))
                            ->disabled()
                            ->dehydrated(true)
                            ->columnSpan(1),
                        DatePicker::make('transaction_date')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->closeOnDateSelection()
                            ->columnSpan(1),
                        Select::make('customer_id')
                            ->label('Customer')
                            ->required()
                            ->relationship('customer', 'name')
                            ->searchable(['name', 'phone'])
                            ->preload()
                            ->live()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->label('Telepone')
                                    ->tel()
                                    ->maxLength(20),
                                Textarea::make('address')
                                    ->label('Alamat')
                                    ->rows(3),
                                Select::make('city_code')
                                    ->label('Kota/Kabupaten')
                                    ->options(fn() => WilayahHelper::getAllRegencies())
                                    ->searchable()
                                    ->live()
                                    ->native(false)
                                    ->afterStateUpdated(function (?string $state, callable $set) {
                                        $set('city_name', WilayahHelper::getAllRegencies()[$state] ?? null);
                                        $set('district_code', null);
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
                            ])
                            ->createOptionUsing(fn(array $data): int => Customer::create($data)->getKey())
                            ->editOptionForm([
                                TextInput::make('name')
                                    ->label('Full Name')
                                    ->required(),
                                TextInput::make('phone')
                                    ->label('Telepone'),
                                Textarea::make('address')
                                    ->label('Alamat'),
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
                            ])
                            ->updateOptionUsing(function (array $data, $record) {
                                $record->update($data);
                            })
                            ->placeholder('Search by name')
                            ->columnSpanFull(),

                        Select::make('status')
                            ->label('Status Transaksi')
                            ->options(
                                collect(TransactionStatusEnum::cases())
                                    ->mapWithKeys(fn($case) => [$case->value => $case->getLabel()])
                                    ->toArray()
                            )
                            ->default(TransactionStatusEnum::PENDING->value)
                            ->required()
                            ->native(false)
                            ->columnSpan(1),
                        Select::make('store_setting_id')
                            ->label('Toko')
                            ->options(fn() => StoreSetting::pluck('store_name', 'id')->toArray())
                            ->searchable()
                            ->required()
                            ->native(false)
                            ->live()
                            ->dehydrated(true)
                            ->visible(fn(): bool => is_null(Auth::user()?->store_setting_id))
                            ->columnSpan(1),
                    ]),
            ]);
    }
}
