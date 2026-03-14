<x-filament-panels::page>
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-9999 flex items-center justify-center" x-data x-init="$el.focus()"
            @keydown.escape.window="$wire.cancelDelete()">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm" wire:click="cancelDelete"></div>

            {{-- Dialog Panel --}}
            <div class="relative z-10 w-full max-w-md mx-4 rounded-xl shadow-2xl
                       bg-white dark:bg-gray-900
                       border border-gray-200 dark:border-white/10
                       overflow-hidden"
                role="alertdialog" aria-modal="true">
                {{-- Header --}}
                <div class="flex items-start gap-4 px-6 pt-6 pb-4">
                    <div
                        class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                                bg-danger-50 dark:bg-danger-950">
                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-danger-600 dark:text-danger-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-950 dark:text-white leading-tight">
                            Delete Promotion
                        </h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Are you sure you want to delete this promotion? This action can be undone later by restoring
                            it.
                        </p>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="h-px bg-gray-100 dark:bg-white/10 mx-6"></div>

                {{-- Footer Buttons --}}
                <div class="flex items-center justify-end gap-3 px-6 py-4">
                    <button wire:click="cancelDelete" type="button"
                        class="inline-flex items-center justify-center rounded-lg
                               px-4 py-2 text-sm font-medium
                               bg-white dark:bg-white/5
                               text-gray-700 dark:text-gray-300
                               border border-gray-300 dark:border-white/10
                               shadow-sm
                               hover:bg-gray-50 dark:hover:bg-white/10
                               focus:outline-none focus:ring-2 focus:ring-primary-500
                               transition">
                        Cancel
                    </button>
                    <button wire:click="deletePromo" wire:loading.attr="disabled" wire:target="deletePromo"
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-lg
                               px-4 py-2 text-sm font-medium
                               bg-danger-600 hover:bg-danger-700
                               dark:bg-danger-500 dark:hover:bg-danger-600
                               text-white
                               shadow-sm
                               focus:outline-none focus:ring-2 focus:ring-danger-500
                               disabled:opacity-70 disabled:cursor-not-allowed
                               transition">
                        <span wire:loading.remove wire:target="deletePromo">
                            <x-heroicon-o-trash class="w-4 h-4 inline-block -mt-0.5 mr-1" />
                            Delete
                        </span>
                        <span wire:loading wire:target="deletePromo" class="flex items-center gap-1.5">
                            <svg class="animate-spin w-4 h-4" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
                            </svg>
                            Deleting…
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center gap-3 mb-6">
        <div class="relative flex-1 max-w-xs">
            {{-- Search Icon --}}
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                <x-heroicon-o-magnifying-glass class="w-4 h-4 text-gray-400 dark:text-gray-500"
                    wire:loading.class="opacity-0" wire:target="promoSearch" />
                <svg wire:loading wire:target="promoSearch" class="animate-spin w-4 h-4 text-primary-500 absolute"
                    viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
                </svg>
            </span>

            <input type="text" wire:model.live.debounce.400ms="promoSearch" placeholder="Search promotions…"
                class="w-full pl-9 pr-10 py-2
                       rounded-lg
                       border border-gray-300 dark:border-white/10
                       bg-white dark:bg-white/5
                       text-gray-900 dark:text-white
                       placeholder-gray-400 dark:placeholder-gray-500
                       text-sm shadow-sm
                       outline-none
                       focus:ring-2 focus:ring-primary-500
                       focus:border-primary-500
                       transition" />

            {{-- Clear button --}}
            @if ($promoSearch)
                <button wire:click="$set('promoSearch', '')"
                    class="absolute inset-y-0 right-2 flex items-center px-1
                           text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    title="Clear search">
                    <x-heroicon-o-x-circle class="w-4 h-4" />
                </button>
            @endif
        </div>
    </div>

    @if ($promos->isEmpty())
        <div
            class="flex flex-col items-center justify-center py-24
                    text-gray-400 dark:text-gray-600">
            <x-heroicon-o-tag class="w-16 h-16 mb-4 opacity-25" />
            <p class="text-lg font-semibold">No promotions found</p>
            <p class="text-sm mt-1">Create your first promotion to get started.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($promos as $promo)
                @php
                    // Light mode classes  →  Dark mode classes kept in separate key
                    $cfg = match ($promo->type->value) {
                        'flash_sale' => [
                            'bg' => 'bg-red-50         dark:bg-red-950/40',
                            'border' => 'border-red-200    dark:border-red-900/60',
                            'iconBg' => 'bg-red-100        dark:bg-red-900/50',
                            'iconColor' => 'text-red-500      dark:text-red-400',
                            'titleColor' => 'text-red-600      dark:text-red-400',
                            'valueColor' => 'text-red-600      dark:text-red-400',
                            'metaColor' => 'text-red-500      dark:text-red-400',
                            'lblColor' => 'text-red-500      dark:text-red-400',
                            'barColor' => 'bg-red-500        dark:bg-red-400',
                        ],
                        'voucher' => [
                            'bg' => 'bg-blue-50        dark:bg-blue-950/40',
                            'border' => 'border-blue-200   dark:border-blue-900/60',
                            'iconBg' => 'bg-blue-100       dark:bg-blue-900/50',
                            'iconColor' => 'text-blue-500     dark:text-blue-400',
                            'titleColor' => 'text-blue-600     dark:text-blue-400',
                            'valueColor' => 'text-blue-600     dark:text-blue-400',
                            'metaColor' => 'text-blue-500     dark:text-blue-400',
                            'lblColor' => 'text-blue-500     dark:text-blue-400',
                            'barColor' => 'bg-blue-500       dark:bg-blue-400',
                        ],
                        'free_shipping' => [
                            'bg' => 'bg-green-50       dark:bg-green-950/40',
                            'border' => 'border-green-200  dark:border-green-900/60',
                            'iconBg' => 'bg-green-100      dark:bg-green-900/50',
                            'iconColor' => 'text-green-500    dark:text-green-400',
                            'titleColor' => 'text-green-700    dark:text-green-400',
                            'valueColor' => 'text-green-700    dark:text-green-400',
                            'metaColor' => 'text-green-500    dark:text-green-400',
                            'lblColor' => 'text-green-500    dark:text-green-400',
                            'barColor' => 'bg-green-500      dark:bg-green-400',
                        ],
                        default => [
                            'bg' => 'bg-gray-50        dark:bg-gray-800/40',
                            'border' => 'border-gray-200   dark:border-gray-700/60',
                            'iconBg' => 'bg-gray-100       dark:bg-gray-700/50',
                            'iconColor' => 'text-gray-500     dark:text-gray-400',
                            'titleColor' => 'text-gray-700     dark:text-gray-300',
                            'valueColor' => 'text-gray-700     dark:text-gray-300',
                            'metaColor' => 'text-gray-500     dark:text-gray-400',
                            'lblColor' => 'text-gray-500     dark:text-gray-400',
                            'barColor' => 'bg-gray-400       dark:bg-gray-500',
                        ],
                    };

                    if ($promo->type->value === 'free_shipping') {
                        $discountDisplay = 'FREE';
                        $discountLabel = 'Shipping';

                        if ($promo->discount_type->value === 'percentage') {
                            $discountNominal = number_format($promo->discount_value, 0) . '%';
                        } else {
                            $discountNominal = 'Rp ' . number_format($promo->discount_value, 0, ',', '.');
                        }
                    } elseif ($promo->discount_type->value === 'percentage') {
                        $discountDisplay = number_format($promo->discount_value, 0) . '%';
                        $discountLabel = 'Discount';
                        $discountNominal = null;
                    } else {
                        $discountDisplay = 'Rp ' . number_format($promo->discount_value, 0, ',', '.');
                        $discountLabel = 'Discount';
                        $discountNominal = null;
                    }

                    $usagePercent =
                        $promo->usage_limit > 0 ? min(100, ($promo->usage_count / $promo->usage_limit) * 100) : 0;

                    $editUrl = \App\Filament\Resources\Promos\PromoResource::getUrl('edit', ['record' => $promo]);
                @endphp

                <div
                    class="relative rounded-xl border p-5 shadow flex flex-col gap-4
                            transition-transform duration-200
                            hover:shadow-lg hover:-translate-y-1
                            {{ $promo->trashed() ? 'opacity-50' : '' }}
                            {{ $cfg['bg'] }} {{ $cfg['border'] }}">

                    {{-- ── Header: Icon + Name + Dropdown ── --}}
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
                                <p class="text-base font-bold {{ $cfg['titleColor'] }} leading-snug">
                                    {{ $promo->name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $promo->type->getLabel() }}
                                </p>
                            </div>
                        </div>

                        {{-- Dropdown (Alpine.js) --}}
                        <div x-data="{ open: false }" class="relative shrink-0">
                            <button @click.stop="open = !open" @click.outside="open = false" type="button"
                                class="w-8 h-8 rounded-md flex items-center justify-center
                                       text-gray-400 dark:text-gray-500
                                       hover:bg-black/5 dark:hover:bg-white/10
                                       transition-colors"
                                title="Actions">
                                <x-heroicon-o-ellipsis-horizontal class="w-5 h-5" />
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95" @click.outside="open = false"
                                class="absolute right-0 mt-1 min-w-40
                                       bg-white dark:bg-gray-800
                                       border border-gray-200 dark:border-white/10
                                       rounded-xl shadow-lg overflow-hidden z-50"
                                style="display:none;">
                                {{-- Edit --}}
                                <a href="{{ $editUrl }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm
                                           text-gray-700 dark:text-gray-200
                                           hover:bg-gray-50 dark:hover:bg-white/5">
                                    <x-heroicon-o-pencil-square class="w-4 h-4 text-primary-500" />
                                    Edit
                                </a>

                                @if ($promo->trashed())
                                    {{-- Restore --}}
                                    <button type="button" wire:click.stop="restorePromo({{ $promo->id }})"
                                        @click="open = false"
                                        class="flex items-center gap-2 px-4 py-2 text-sm
                                               text-success-600 dark:text-success-400
                                               hover:bg-success-50 dark:hover:bg-success-950/40
                                               w-full text-left">
                                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                                        Restore
                                    </button>
                                @else
                                    {{-- Delete — opens custom modal --}}
                                    <button type="button" wire:click.stop="confirmDeletePromo({{ $promo->id }})"
                                        @click="open = false"
                                        class="flex items-center gap-2 px-4 py-2 text-sm
                                               text-danger-600 dark:text-danger-400
                                               hover:bg-danger-50 dark:hover:bg-danger-950/40
                                               w-full text-left">
                                        <x-heroicon-o-trash class="w-4 h-4" />
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── Discount Display ── --}}
                    <div class="text-center py-2">
                        <p class="text-3xl font-extrabold {{ $cfg['valueColor'] }} leading-tight">
                            {{ $discountDisplay }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                            {{ $discountLabel }}
                        </p>
                        @if ($discountNominal)
                            <p class="text-[12px] text-gray-400 dark:text-gray-500 mt-0.5">
                                {{ $discountNominal }}
                            </p>
                        @endif
                    </div>

                    {{-- ── Meta Info ── --}}
                    <div class="flex flex-col gap-1 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400">Min. Purchase</span>
                            <span class="font-semibold {{ $cfg['metaColor'] }}">
                                Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 dark:text-gray-400">Valid Period</span>
                            <span class="font-medium {{ $cfg['metaColor'] }} text-xs">
                                {{ $promo->start_date?->format('Y-m-d') }} – {{ $promo->end_date?->format('Y-m-d') }}
                            </span>
                        </div>
                    </div>

                    {{-- ── Usage Progress ── --}}
                    @if ($promo->usage_limit > 0)
                        <div class="flex flex-col gap-1">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-gray-500 dark:text-gray-400">Usage</span>
                                <span class="font-semibold {{ $cfg['metaColor'] }}">
                                    {{ $promo->usage_count }} / {{ $promo->usage_limit }}
                                </span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                                <div class="h-full rounded-full {{ $cfg['barColor'] }}"
                                    style="width:{{ $usagePercent }}%"></div>
                            </div>
                        </div>
                    @endif

                    <hr class="border-t border-gray-200 dark:border-white/10 my-1" />

                    {{-- ── Active Toggle ── --}}
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-semibold {{ $cfg['lblColor'] }}">
                            {{ $promo->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        {{-- Toggle button — wire:click must NOT be inside Alpine's @click.stop scope --}}
                        <button type="button" wire:click="toggleActive({{ $promo->id }})"
                            wire:loading.attr="disabled" wire:target="toggleActive({{ $promo->id }})"
                            aria-label="{{ $promo->is_active ? 'Deactivate promotion' : 'Activate promotion' }}"
                            class="relative w-11 h-6 flex items-center rounded-full p-0.5
                                   transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500
                                   disabled:opacity-60 disabled:cursor-not-allowed
                                   {{ $promo->is_active ? 'bg-primary-500 dark:bg-primary-600' : 'bg-gray-300 dark:bg-gray-600' }}">
                            <span
                                class="bg-white w-5 h-5 rounded-full shadow
                                       transition-transform duration-200
                                       {{ $promo->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </div>

                </div>
            @endforeach
        </div>

        {{-- ── Pagination ── --}}
        <div class="mt-6">
            {{ $promos->links() }}
        </div>
    @endif

</x-filament-panels::page>
