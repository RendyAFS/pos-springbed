<?php

namespace App\Filament\Resources\Products\Schemas;

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
                        Select::make('size_id')
                            ->label('Size')
                            ->relationship('size', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('type_id')
                            ->label('Type')
                            ->relationship('type', 'name')
                            ->searchable()
                            ->preload()
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
                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->offIcon(Heroicon::XMark)
                            ->onIcon(Heroicon::Check)
                            ->offColor('danger')
                            ->onColor('success')
                            ->inline(false),
                        Select::make('color')
                            ->label('Color')
                            ->options([
                                'merah' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #ef4444"></span>Merah</span>',
                                'pink' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #ec4899"></span>Pink</span>',
                                'kuning' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #eab308"></span>Kuning</span>',
                                'orange' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #f97316"></span>Orange</span>',
                                'biru' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #3b82f6"></span>Biru</span>',
                                'hijau' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #22c55e"></span>Hijau</span>',
                                'ungu' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #8b5cf6"></span>Ungu</span>',
                                'coklat' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #92400e"></span>Coklat</span>',
                                'hitam' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #111827"></span>Hitam</span>',
                                'putih' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #ffffff"></span>Putih</span>',
                                'abu_abu' => '<span class="flex items-center gap-2"><span class="h-4 w-4 rounded-full border border-gray-300" style="background-color: #9ca3af"></span>Abu-abu</span>',
                            ])
                            ->allowHtml()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->dehydrated(true),
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
                            ->live(onBlur: true)
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
