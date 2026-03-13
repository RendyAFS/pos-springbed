<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\SizeProductEnum;
use App\Enums\TypeProductEnum;
use App\Models\InventoryStock;
use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

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
                            ->label('Brand')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('categories_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('size')
                            ->label('Size')
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
                            ->label('Type')
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
                            ->label('Name')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Detail Information')
                    ->icon(Heroicon::DocumentText)
                    ->schema([
                        TextInput::make('selling_price')
                            ->label('Selling Price')
                            ->numeric()
                            ->required()
                            ->prefix('Rp.'),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->default(null),
                        TextInput::make('weight')
                            ->label('Weight')
                            ->numeric()
                            ->default(null),
                        TextInput::make('color')
                            ->label('Color')
                            ->default(null),
                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->offIcon(Heroicon::XMark)
                            ->onIcon(Heroicon::Check)
                            ->offColor('danger')
                            ->onColor('success')
                            ->inline(false)
                            ->default(true),
                        Hidden::make('stock_adjustment_temp')
                            ->dehydrated(true)
                    ])->columns(2),
                Section::make('Inventory')
                    ->schema([
                        TextEntry::make('stock_info')
                            ->label('Stock Information')
                            ->html()
                            ->state(function ($get, $record) {

                                $temp = $get('stock_adjustment_temp');

                                if (!$record && !$temp) {
                                    return '<span class="text-gray-500">Stock not set yet</span>';
                                }

                                $storeId = Auth::user()?->store_setting_id;

                                if (!$storeId && $record) {
                                    $storeId = InventoryStock::query()
                                        ->where('product_id', $record->id)
                                        ->value('store_setting_id');
                                }

                                $store = StoreSetting::find($storeId);
                                $storeName = $store?->store_name ?? 'Unknown Store';

                                if ($temp) {
                                    $qty = $temp['quantity'];
                                    $storeName = StoreSetting::find($temp['store_setting_id'])?->store_name ?? 'Unknown Store';
                                } else {
                                    $qty = InventoryStock::query()
                                        ->where('product_id', $record->id)
                                        ->when($storeId, fn($q) => $q->where('store_setting_id', $storeId))
                                        ->sum('quantity');
                                }

                                if ($qty <= 5) {
                                    $bg = 'bg-danger-100';
                                    $text = 'text-danger-700';
                                } elseif ($qty <= 10) {
                                    $bg = 'bg-warning-100';
                                    $text = 'text-warning-700';
                                } else {
                                    $bg = 'bg-success-100';
                                    $text = 'text-success-700';
                                }

                                return "
                                    <div class='flex flex-col gap-1'>
                                        <span class='text-sm text-gray-500'>Store</span>
                                        <span class='font-semibold text-primary-600'>{$storeName}</span>
                                        <span class='text-sm text-gray-500 mt-2'>Current Stock</span>
                                        <span class='inline-flex items-center px-2 py-1 text-sm font-bold {$bg} {$text} rounded'>
                                            {$qty} pcs
                                        </span>
                                    </div>
                                ";
                            })
                            ->reactive(),
                    ])
                    ->headerActions([
                        Action::make('adjustStock')
                            ->label('Edit Stock')
                            ->color('primary')
                            ->icon('heroicon-o-pencil')
                            ->modalHeading('Stock Adjustment')
                            ->fillForm(function ($livewire) {
                                $state = $livewire->form->getState();
                                $temp = $state['stock_adjustment_temp'] ?? null;
                                if ($temp) {
                                    return $temp;
                                }
                                $userStore = Auth::user()->store_setting_id;
                                if ($userStore) {
                                    return [
                                        'store_setting_id' => $userStore,
                                    ];
                                }
                                $record = $livewire->record;
                                if (!$record) {
                                    return [];
                                }
                                $storeId = InventoryStock::where('product_id', $record->id)->value('store_setting_id');
                                return [
                                    'store_setting_id' => $storeId,
                                ];
                            })
                            ->schema([
                                Select::make('store_setting_id')
                                    ->relationship('storeSetting', 'store_name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->disabled(fn() => Auth::user()->store_setting_id !== null),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),
                                Textarea::make('reason')
                                    ->required(),
                            ])

                            ->action(function ($data, $livewire) {
                                $state = $livewire->form->getState();
                                $state['stock_adjustment_temp'] = $data;
                                $livewire->form->fill($state);
                                Notification::make()
                                    ->title('Stock adjustment saved')
                                    ->body('Stock temporary successfully saved. Data will be processed when the product is saved.')
                                    ->success()
                                    ->send();
                            })
                    ]),
                Repeater::make('productImages')
                    ->relationship()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images_product')
                            ->label('Image Product')
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
