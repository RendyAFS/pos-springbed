<?php

namespace App\Filament\Resources\Transactions\Schemas\Steps;

use App\Enums\TransactionStatusEnum;
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
