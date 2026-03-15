@php
    use App\Filament\Resources\Bundles\BundleResource;

    $editUrl = BundleResource::getUrl('edit', ['record' => $bundle]);

    // Calculate original price = sum of (product selling_price × qty)
    $originalPrice = $bundle->bundleItems->sum(fn($item) => ($item->product->selling_price ?? 0) * $item->qty);

    $bundlePrice = (float) $bundle->bundle_price;
    $savings = max(0, $originalPrice - $bundlePrice);
    $itemCount = $bundle->bundleItems->count();
@endphp

<div @class([
    'relative bg-white dark:bg-gray-900 rounded-xl border shadow-sm flex flex-col',
    'border-gray-200 dark:border-white/10',
    'opacity-60 ring-1 ring-inset ring-danger-300 dark:ring-danger-800' => $bundle->trashed(),
])>

    {{-- ── Header ─────────────────────────────────────────────────────── --}}
    <div class="flex items-start justify-between p-5 pb-4">

        {{-- Icon + name + item count --}}
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded-lg flex items-center justify-center
                        bg-primary-50 dark:bg-primary-950/40 shrink-0">
                <x-heroicon-o-cube-transparent class="w-5 h-5 text-primary-500 dark:text-primary-400" />
            </div>
            <div>
                <p class="text-base font-bold text-gray-900 dark:text-white leading-snug">
                    {{ $bundle->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ $itemCount }} {{ Str::plural('item', $itemCount) }} in bundle
                </p>
            </div>
        </div>

        {{-- Toggle + Active badge --}}
        <div class="flex items-center gap-2 shrink-0">
            {{-- Toggle --}}
            <button type="button" wire:click="toggleActive({{ $bundle->id }})" wire:loading.attr="disabled"
                wire:target="toggleActive({{ $bundle->id }})" @disabled($bundle->trashed())
                @class([
                    'relative w-11 h-6 flex items-center rounded-full p-0.5',
                    'transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500',
                    'disabled:opacity-60 disabled:cursor-not-allowed',
                    'bg-primary-500 dark:bg-primary-600' => $bundle->is_active,
                    'bg-gray-300 dark:bg-gray-600' => !$bundle->is_active,
                    'cursor-not-allowed opacity-40' => $bundle->trashed(),
                ])>
                <span @class([
                    'bg-white w-5 h-5 rounded-full shadow transition-transform duration-200',
                    'translate-x-5' => $bundle->is_active,
                    'translate-x-0' => !$bundle->is_active,
                ])></span>
            </button>

            {{-- Status badge --}}
            @if ($bundle->trashed())
                <span
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold
                             bg-danger-100 text-danger-700 dark:bg-danger-900 dark:text-danger-300">
                    <x-heroicon-o-trash class="w-3 h-3" />
                    Deleted
                </span>
            @elseif ($bundle->is_active)
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                             bg-primary-100 text-primary-700 dark:bg-primary-900/50 dark:text-primary-300">
                    Active
                </span>
            @else
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                             bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                    Inactive
                </span>
            @endif
        </div>
    </div>

    {{-- ── Included Products ──────────────────────────────────────────── --}}
    <div class="px-5 pb-3">
        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2">
            Included Products:
        </p>

        <div class="flex flex-col divide-y divide-gray-100 dark:divide-white/5">
            @forelse ($bundle->bundleItems as $item)
                @php
                    $storeId = auth()->user()?->store_setting_id;
                    $product = $item->product;
                    $stocks = $product?->inventoryStocks ?? collect();
                    $stock = $storeId
                        ? $stocks->where('store_setting_id', $storeId)->sum('quantity')
                        : $stocks->sum('quantity');
                    $price = ($product?->selling_price ?? 0) * $item->qty;
                @endphp
                <div class="flex items-center justify-between py-2.5 gap-3">
                    {{-- Product icon + name --}}
                    <div class="flex items-center gap-2 min-w-0">
                        <x-heroicon-o-cube class="w-4 h-4 text-gray-400 dark:text-gray-500 shrink-0" />
                        <span class="text-sm text-gray-700 dark:text-gray-200 truncate">
                            {{ $product?->name ?? '—' }}
                            @if ($item->qty > 1)
                                <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">×{{ $item->qty }}</span>
                            @endif
                        </span>
                    </div>

                    {{-- Stock pill + price --}}
                    <div class="flex items-center gap-3 shrink-0">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                     border border-gray-300 dark:border-white/20
                                     text-gray-700 dark:text-gray-300 bg-white dark:bg-white/5">
                            Stock: {{ $stock }}
                        </span>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-200 whitespace-nowrap">
                            Rp {{ number_format($price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 dark:text-gray-500 py-2 italic">No items added yet.</p>
            @endforelse
        </div>
    </div>

    {{-- ── Pricing Summary ─────────────────────────────────────────────── --}}
    <div class="mx-5 mb-4 pt-3 border-t border-gray-100 dark:border-white/10 flex flex-col gap-1">

        {{-- Original price --}}
        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500 dark:text-gray-400">Original Price:</span>
            <span class="text-sm text-gray-400 dark:text-gray-500 line-through">
                Rp {{ number_format($originalPrice, 0, ',', '.') }}
            </span>
        </div>

        {{-- Bundle price --}}
        <div class="flex justify-between items-center">
            <span class="text-sm font-bold text-gray-900 dark:text-white">Bundle Price:</span>
            <span class="text-xl font-extrabold text-primary-600 dark:text-primary-400">
                Rp {{ number_format($bundlePrice, 0, ',', '.') }}
            </span>
        </div>

        {{-- Customer saves --}}
        @if ($savings > 0)
            <div class="flex justify-between items-center">
                <span class="text-sm font-medium text-success-600 dark:text-success-400">Customer Saves:</span>
                <span class="text-sm font-semibold text-success-600 dark:text-success-400">
                    Rp {{ number_format($savings, 0, ',', '.') }}
                </span>
            </div>
        @endif
    </div>

    {{-- ── Footer: Edit + Delete/Restore ──────────────────────────────── --}}
    <div class="flex items-center gap-2 px-5 pb-5 mt-auto">

        @if ($bundle->trashed())
            {{-- When trashed: restore takes the full width --}}
            <button type="button" wire:click.stop="confirmRestore({{ $bundle->id }})"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                       text-sm font-medium border
                       border-success-300 dark:border-success-700
                       text-success-700 dark:text-success-300
                       bg-success-50 dark:bg-success-950/30
                       hover:bg-success-100 dark:hover:bg-success-900/40
                       focus:outline-none focus:ring-2 focus:ring-success-500 transition">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                Restore
            </button>
        @else
            {{-- Edit button (wide) --}}
            <a href="{{ $editUrl }}"
                class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                       text-sm font-medium border
                       border-gray-300 dark:border-white/10
                       text-gray-700 dark:text-gray-200
                       bg-white dark:bg-white/5
                       hover:bg-gray-50 dark:hover:bg-white/10
                       focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                <x-heroicon-o-pencil-square class="w-4 h-4" />
                Edit
            </a>

            {{-- Delete button (icon only, red) --}}
            <button type="button" wire:click.stop="confirmDelete({{ $bundle->id }})"
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg
                       border border-danger-200 dark:border-danger-800
                       text-danger-600 dark:text-danger-400
                       bg-white dark:bg-white/5
                       hover:bg-danger-50 dark:hover:bg-danger-950/30
                       focus:outline-none focus:ring-2 focus:ring-danger-500 transition"
                title="Delete bundle">
                <x-heroicon-o-trash class="w-4 h-4" />
            </button>
        @endif

    </div>

</div>
