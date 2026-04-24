<div class="flex flex-col gap-4">

    {{-- Low Stock Alert --}}
    <x-filament::section>
        <x-slot name="heading">
            ⚠️ Low Stock Alert
        </x-slot>

        @php $lowStockItems = $this->getLowStockItems() @endphp

        @if ($lowStockItems->isEmpty())
            <div class="py-6 text-center text-sm text-gray-400">
                No low stock items
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-white/5">
                @foreach ($lowStockItems as $stock)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $stock->product?->name ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $stock->product?->sku ?? '-' }}
                            </p>
                        </div>
                        <span @class([
                            'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
                            'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' =>
                                $stock->quantity <= 2,
                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400' =>
                                $stock->quantity > 2 && $stock->quantity <= 5,
                            'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' =>
                                $stock->quantity > 5,
                        ])>
                            {{ $stock->quantity }} left
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>

    {{-- Active Promotions --}}
    <x-filament::section>
        <x-slot name="heading">
            Active Promotions
        </x-slot>

        @php $promotions = $this->getActivePromotions() @endphp

        @if ($promotions->isEmpty())
            <div class="py-6 text-center text-sm text-gray-400">
                No active promotions
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-white/5">
                @foreach ($promotions as $promo)
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-2">
                            <x-filament::icon :icon="$this->getPromoIcon($promo)" class="h-4 w-4 text-gray-400" />
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $promo->name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $this->getPromoDescription($promo) }}
                                </p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-white/10 dark:text-gray-300">
                            @if ($promo->usage_limit)
                                {{ $promo->usage_count }}/{{ $promo->usage_limit }}
                            @else
                                {{ $promo->usage_count }}/∞
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::section>

</div>
