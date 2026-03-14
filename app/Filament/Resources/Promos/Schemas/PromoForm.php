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
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class PromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Promo Information')
                    ->description('Basic information about the promotion')
                    ->schema([
                        TextInput::make('name')
                            ->label('Promo Name')
                            ->required()
                            ->maxLength(100),
                        Select::make('type')
                            ->label('Promo Type')
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
                            ->required()
                            ->label('Products Included')
                            ->helperText('Leave empty if promo applies to all products')
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make('Discount Configuration')
                    ->description('Configure how the discount will be applied')
                    ->schema([

                        Select::make('discount_type')
                            ->label('Discount Type')
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
                            ->label('Discount Value')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->live()
                            ->suffix(
                                fn($get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? '%'
                                    : null
                            )
                            ->prefix(
                                fn($get) =>
                                $get('discount_type') === PromoDiscountEnum::NOMINAL->value
                                    ? 'Rp'
                                    : null
                            )
                            ->minValue(
                                fn($get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? 0.1
                                    : 1
                            )
                            ->maxValue(
                                fn($get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? 100
                                    : null
                            )
                            ->step(
                                fn($get) =>
                                $get('discount_type') === PromoDiscountEnum::PERCENTAGE->value
                                    ? 0.1
                                    : 1
                            ),
                        TextInput::make('min_purchase')
                            ->label('Minimum Purchase')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->helperText('Minimum cart total required to activate this promo'),
                    ])
                    ->columns(2),

                Section::make('Schedule')
                    ->description('Set when the promo is active')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('start_date')
                                    ->label('Start Date')
                                    ->native(false)
                                    ->seconds(false)
                                    ->suffixIcon(Heroicon::Calendar)
                                    ->closeOnDateSelection(),
                                DateTimePicker::make('end_date')
                                    ->label('End Date')
                                    ->native(false)
                                    ->seconds(false)
                                    ->suffixIcon(Heroicon::Calendar)
                                    ->closeOnDateSelection(),
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

                Section::make('Usage Limit')
                    ->description('Limit how many times this promo can be used')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('usage_limit')
                                    ->label('Usage Limit')
                                    ->numeric()
                                    ->placeholder('Unlimited if empty'),
                                TextInput::make('usage_count')
                                    ->label('Usage Count')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),

                    ]),
            ]);
    }
}
