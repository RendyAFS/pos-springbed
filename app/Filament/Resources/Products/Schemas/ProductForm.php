<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\SizeProductEnum;
use App\Enums\TypeProductEnum;
use App\Models\InventoryStock;
use App\Models\StoreSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
// use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                    ->icon(Heroicon::ArchiveBox)
                    ->description('Manage stock per store location')
                    ->schema([
                        ViewField::make('stock_summary')
                            ->view('filament.components.products.stock-summary')
                            ->viewData(function ($record, $get) {
                                $temp        = $get('stock_adjustment_temp') ?? [];
                                $userStoreId = Auth::user()?->store_setting_id;

                                $dbStocks = $record
                                    ? InventoryStock::with('storeSetting')
                                    ->where('product_id', $record->id)
                                    ->when($userStoreId, fn($q) => $q->where('store_setting_id', $userStoreId))
                                    ->get()
                                    ->keyBy('store_setting_id')
                                    : collect();

                                if ($temp === [] && $dbStocks->isEmpty()) {
                                    return ['stocks' => collect(), 'pending' => []];
                                }

                                $pendingMap = collect($temp)->keyBy('store_setting_id');

                                $storeIds = $pendingMap->keys()
                                    ->merge($dbStocks->keys())
                                    ->unique();

                                $stocks = $storeIds->map(function ($storeId) use ($dbStocks, $pendingMap) {
                                    $dbQty      = $dbStocks->get($storeId)?->quantity ?? 0;
                                    $pending    = $pendingMap->get($storeId);
                                    $pendingQty = $pending ? (int) $pending['quantity'] : null;

                                    return (object) [
                                        'store_setting_id' => $storeId,
                                        'quantity'         => $pendingQty ?? $dbQty,
                                        'qty_before'       => $dbQty,
                                        'has_pending'      => $pendingQty !== null && $pendingQty !== $dbQty,
                                        'storeSetting'     => StoreSetting::find($storeId),
                                    ];
                                })->values();

                                return [
                                    'stocks'  => $stocks,
                                    'pending' => $pendingMap->toArray(),
                                ];
                            })
                            ->live()
                            ->columnSpanFull()
                            ->hidden(fn($record, $get) => !$record && empty($get('stock_adjustment_temp'))),

                        Hidden::make('stock_adjustment_temp')
                            ->dehydrated(true),
                    ])
                    ->headerActions([
                        Action::make('adjustStock')
                            ->label('Edit Stock')
                            ->color('primary')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->modalHeading('Stock Adjustment')
                            ->modalWidth('2xl')
                            ->fillForm(function ($livewire) {
                                $state = $livewire->form->getState();
                                $temp  = $state['stock_adjustment_temp'] ?? null;

                                if ($temp) {
                                    return ['stocks' => $temp];
                                }

                                $userStoreId = Auth::user()?->store_setting_id;
                                $record      = $livewire->record;

                                if ($userStoreId) {
                                    $qty = $record
                                        ? InventoryStock::where('product_id', $record->id)
                                        ->where('store_setting_id', $userStoreId)
                                        ->value('quantity') ?? 0
                                        : 0;

                                    return [
                                        'stocks' => [[
                                            'store_setting_id' => $userStoreId,
                                            'quantity'         => $qty,
                                            'reason'           => '',
                                        ]],
                                    ];
                                }

                                $stores = StoreSetting::all();

                                return [
                                    'stocks' => $stores->map(function ($store) use ($record) {
                                        $qty = $record
                                            ? InventoryStock::where('product_id', $record->id)
                                            ->where('store_setting_id', $store->id)
                                            ->value('quantity') ?? 0
                                            : 0;

                                        return [
                                            'store_setting_id' => $store->id,
                                            'quantity'         => $qty,
                                            'reason'           => '',
                                        ];
                                    })->toArray(),
                                ];
                            })
                            ->schema([
                                Repeater::make('stocks')
                                    ->label('')
                                    ->schema([
                                        Select::make('store_setting_id')
                                            ->label('Store')
                                            ->options(StoreSetting::pluck('store_name', 'id'))
                                            ->disabled()
                                            ->dehydrated(true)
                                            ->columnSpan(2),

                                        TextInput::make('quantity')
                                            ->label('New Stock Quantity')
                                            ->numeric()
                                            ->minValue(0)
                                            ->required()
                                            ->suffix('pcs')
                                            ->columnSpan(1),

                                        Textarea::make('reason')
                                            ->label('Reason')
                                            ->nullable()
                                            ->rows(2)
                                            ->columnSpan(3),
                                    ])
                                    ->columns(3)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->itemLabel(
                                        fn($state) =>
                                        StoreSetting::find($state['store_setting_id'])?->store_name ?? 'Store'
                                    )
                                    ->collapsible()
                            ])
                            ->action(function ($data, $livewire) {
                                $state = $livewire->form->getState();
                                $state['stock_adjustment_temp'] = $data['stocks'];
                                $livewire->form->fill($state);

                                Notification::make()
                                    ->title('Stock adjustment staged')
                                    ->body('Stock data will be saved when product is submitted.')
                                    ->success()
                                    ->send();
                            }),
                    ])->columnSpanFull(),
                Repeater::make('productImages')
                    ->relationship()
                    ->schema([
                        FileUpload::make('image_product')
                            ->label('Image Product')
                            ->nullable()
                            ->image()
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->disk('public')
                            ->directory('images-product')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        Toggle::make('is_primary')
                            ->label('Is Primary')
                            ->offIcon(Heroicon::XMark)
                            ->onIcon(Heroicon::Check)
                            ->offColor('danger')
                            ->onColor('success')
                            ->inline(false)
                            ->default(false)
                            ->reactive()
                            ->afterStateUpdated(function ($state, $livewire, Get $get, Set $set) {
                                if ($state === true) {
                                    $items = $get('../../productImages');
                                    if (is_array($items)) {
                                        foreach ($items as $key => $item) {
                                            $set("../../productImages.{$key}.is_primary", false);
                                        }
                                    }
                                    $set('is_primary', true);
                                }
                            }),
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
