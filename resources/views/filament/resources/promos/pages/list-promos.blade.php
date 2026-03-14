@vite(['resources/css/app.css', 'resources/js/app.js'])
<x-filament-panels::page>
    {{-- ── Search Bar ── --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1 max-w-xs">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <x-heroicon-o-magnifying-glass class="w-4 h-4" />
            </span>
            <input type="text" wire:model.live.debounce.400ms="promoSearch" placeholder="Search promotions…"
                class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm outline-none" />
        </div>
    </div>

    {{-- ── Empty State ── --}}
    @if ($promos->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-gray-400">
            <x-heroicon-o-tag class="w-16 h-16 mb-4 opacity-25" />
            <p class="text-lg font-semibold">No promotions found</p>
            <p class="text-sm mt-1">Create your first promotion to get started.</p>
        </div>
    @else
        {{-- ── Grid Cards ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($promos as $promo)
                @php
                    $cfg = match ($promo->type->value) {
                        'flash_sale' => [
                            'bg' => 'bg-red-50',
                            'border' => 'border-red-200',
                            'iconBg' => 'bg-red-100',
                            'iconColor' => 'text-red-500',
                            'titleColor' => 'text-red-600',
                            'valueColor' => 'text-red-600',
                            'metaColor' => 'text-red-500',
                            'lblColor' => 'text-red-500',
                        ],
                        'voucher' => [
                            'bg' => 'bg-blue-50',
                            'border' => 'border-blue-200',
                            'iconBg' => 'bg-blue-100',
                            'iconColor' => 'text-blue-500',
                            'titleColor' => 'text-blue-600',
                            'valueColor' => 'text-blue-600',
                            'metaColor' => 'text-blue-500',
                            'lblColor' => 'text-blue-500',
                        ],
                        'free_shipping' => [
                            'bg' => 'bg-green-50',
                            'border' => 'border-green-200',
                            'iconBg' => 'bg-green-100',
                            'iconColor' => 'text-green-500',
                            'titleColor' => 'text-green-700',
                            'valueColor' => 'text-green-700',
                            'metaColor' => 'text-green-500',
                            'lblColor' => 'text-green-500',
                        ],
                        default => [
                            'bg' => 'bg-gray-50',
                            'border' => 'border-gray-200',
                            'iconBg' => 'bg-gray-100',
                            'iconColor' => 'text-gray-500',
                            'titleColor' => 'text-gray-700',
                            'valueColor' => 'text-gray-700',
                            'metaColor' => 'text-gray-500',
                            'lblColor' => 'text-gray-500',
                        ],
                    };

                    if ($promo->type->value === 'free_shipping') {
                        $discountDisplay = 'FREE';
                        $discountLabel = 'Shipping';
                    } elseif ($promo->discount_type->value === 'percentage') {
                        $discountDisplay = number_format($promo->discount_value, 0) . '%';
                        $discountLabel = 'Discount';
                    } else {
                        $discountDisplay = 'Rp ' . number_format($promo->discount_value, 0, ',', '.');
                        $discountLabel = 'Discount';
                    }

                    $usagePercent =
                        $promo->usage_limit > 0 ? min(100, ($promo->usage_count / $promo->usage_limit) * 100) : 0;
                    $editUrl = \App\Filament\Resources\Promos\PromoResource::getUrl('edit', ['record' => $promo]);
                @endphp

                <div
                    class="relative rounded-xl border p-5 shadow flex flex-col gap-4 transition-transform duration-200 hover:shadow-lg hover:-translate-y-1 {{ $promo->trashed() ? 'opacity-50' : '' }} {{ $cfg['bg'] }} {{ $cfg['border'] }}">

                    {{-- Header: Icon + Name + Dropdown --}}
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $cfg['iconBg'] }}">
                                @if ($promo->type->value === 'flash_sale')
                                    <x-heroicon-o-tag class="w-5 h-5 {{ $cfg['iconColor'] }}" />
                                @elseif ($promo->type->value === 'voucher')
                                    <x-heroicon-o-ticket class="w-5 h-5 {{ $cfg['iconColor'] }}" />
                                @else
                                    <x-heroicon-o-truck class="w-5 h-5 {{ $cfg['iconColor'] }}" />
                                @endif
                            </div>
                            <div>
                                <p class="text-base font-bold {{ $cfg['titleColor'] }} leading-snug">{{ $promo->name }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $promo->type->getLabel() }}</p>
                            </div>
                        </div>

                        {{-- Dropdown --}}
                        <div x-data="{ open: false }" class="relative flex-shrink-0">
                            <button @click="open = !open" @click.outside="open = false"
                                class="w-8 h-8 rounded-md flex items-center justify-center text-gray-400 hover:bg-gray-100 transition-colors"
                                title="Actions">
                                <x-heroicon-o-ellipsis-horizontal class="w-5 h-5" />
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-1 min-w-[10rem] bg-white border rounded-xl shadow-lg overflow-hidden z-50"
                                style="display:none;">
                                <a href="{{ $editUrl }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <x-heroicon-o-pencil-square class="w-4 h-4 text-indigo-500" />
                                    Edit
                                </a>
                                @if ($promo->trashed())
                                    <button wire:click="restorePromo({{ $promo->id }})"
                                        wire:confirm="Restore this promotion?"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-green-600 hover:bg-green-50 w-full text-left">
                                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        Restore
                                    </button>
                                @else
                                    <button wire:click="deletePromo({{ $promo->id }})"
                                        wire:confirm="Are you sure you want to delete this promotion?"
                                        class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Discount Display --}}
                    <div class="text-center py-2">
                        <p class="text-3xl font-extrabold {{ $cfg['valueColor'] }} leading-tight">
                            {{ $discountDisplay }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $discountLabel }}</p>
                    </div>

                    {{-- Meta Info --}}
                    <div class="flex flex-col gap-1 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Min. Purchase</span>
                            <span class="font-semibold {{ $cfg['metaColor'] }}">Rp
                                {{ number_format($promo->min_purchase, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Valid Period</span>
                            <span class="font-medium {{ $cfg['metaColor'] }} text-xs">
                                {{ $promo->start_date?->format('Y-m-d') }} – {{ $promo->end_date?->format('Y-m-d') }}
                            </span>
                        </div>
                    </div>

                    {{-- Usage Progress --}}
                    @if ($promo->usage_limit > 0)
                        <div class="flex flex-col gap-1">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500">Usage</span>
                                <span class="font-semibold {{ $cfg['metaColor'] }}">{{ $promo->usage_count }} /
                                    {{ $promo->usage_limit }}</span>
                            </div>
                            <div class="w-full h-1 rounded-full bg-gray-200 overflow-hidden">
                                <div class="h-full rounded-full {{ $cfg['valueColor'] }}"
                                    style="width:{{ $usagePercent }}%"></div>
                            </div>
                        </div>
                    @endif

                    <hr class="border-t border-gray-200 my-2" />

                    {{-- Active Toggle --}}
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold {{ $cfg['lblColor'] }}">
                            {{ $promo->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <button wire:click="toggleActive({{ $promo->id }})"
                            class="w-11 h-6 flex items-center rounded-full p-1 transition-colors {{ $promo->is_active ? 'bg-blue-500' : 'bg-gray-300' }}">
                            <span class="bg-white w-4 h-4 rounded-full shadow transform transition-transform"
                                style="transform: translateX({{ $promo->is_active ? '1.25rem' : '0.25rem' }});"></span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $promos->links() }}
        </div>
    @endif

</x-filament-panels::page>
