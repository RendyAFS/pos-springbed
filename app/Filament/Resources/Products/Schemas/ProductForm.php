<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\SizeProductEnum;
use App\Enums\TypeProductEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->icon(Heroicon::ArchiveBox)
                    ->schema([
                        Select::make('brands_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Select::make('categories_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('size')
                            ->options(
                                collect(SizeProductEnum::cases())
                                    ->mapWithKeys(fn($case) => [
                                        $case->value => $case->getLabel()
                                    ])
                                    ->toArray()
                            )
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->options(
                                collect(TypeProductEnum::cases())
                                    ->mapWithKeys(fn($case) => [
                                        $case->value => $case->getLabel()
                                    ])
                                    ->toArray()
                            )
                            ->searchable()
                            ->required(),
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Detail Information')
                    ->icon(Heroicon::DocumentText)
                    ->schema([
                        TextInput::make('selling_price')
                            ->numeric()
                            ->required()
                            ->prefix('Rp.'),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->default(null),
                        TextInput::make('weight')
                            ->numeric()
                            ->default(null),
                        TextInput::make('color')
                            ->default(null),
                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->offIcon(Heroicon::XMark)
                            ->onIcon(Heroicon::Check)
                            ->offColor('danger')
                            ->onColor('success')
                            ->inline(false)
                            ->default(true),
                    ])->columns(2),
                Repeater::make('productImages')
                    ->relationship()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images_product')
                            ->nullable()
                            ->image()
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->disk('public')
                            ->collection('images_product')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        Toggle::make('is_primary')
                            ->label('Is Primary')
                            ->offIcon(Heroicon::XMark)
                            ->onIcon(Heroicon::Check)
                            ->offColor('danger')
                            ->onColor('success')
                            ->inline(false)
                            ->default(false),
                    ])
                    ->addActionLabel('Add Image')
                    ->reorderable()
                    ->reorderableWithDragAndDrop()
                    ->collapsible()
                    ->grid(2)
                    ->columnSpanFull()
            ])->columns(2);
    }
}
