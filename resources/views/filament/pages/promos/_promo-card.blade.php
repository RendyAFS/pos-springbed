@php
    use App\Filament\Resources\Promos\PromoResource;

    $cfg = match ($promo->type->value) {
        'flash_sale' => [
            'bg' => 'bg-red-50 dark:bg-red-950/40',
            'border' => 'border-red-200 dark:border-red-900/60',
            'iconBg' => 'bg-red-100 dark:bg-red-900/50',
            'iconColor' => 'text-red-500 dark:text-red-400',
            'titleColor' => 'text-red-600 dark:text-red-400',
            'valueColor' => 'text-red-600 dark:text-red-400',
            'metaColor' => 'text-red-500 dark:text-red-400',
            'lblColor' => 'text-red-500 dark:text-red-400',
            'barColor' => 'bg-red-500 dark:bg-red-400',
        ],
        'voucher' => [
            'bg' => 'bg-blue-50 dark:bg-blue-950/40',
            'border' => 'border-blue-200 dark:border-blue-900/60',
            'iconBg' => 'bg-blue-100 dark:bg-blue-900/50',
            'iconColor' => 'text-blue-500 dark:text-blue-400',
            'titleColor' => 'text-blue-600 dark:text-blue-400',
            'valueColor' => 'text-blue-600 dark:text-blue-400',
            'metaColor' => 'text-blue-500 dark:text-blue-400',
            'lblColor' => 'text-blue-500 dark:text-blue-400',
            'barColor' => 'bg-blue-500 dark:bg-blue-400',
        ],
        'free_shipping' => [
            'bg' => 'bg-green-50 dark:bg-green-950/40',
            'border' => 'border-green-200 dark:border-green-900/60',
            'iconBg' => 'bg-green-100 dark:bg-green-900/50',
            'iconColor' => 'text-green-500 dark:text-green-400',
            'titleColor' => 'text-green-700 dark:text-green-400',
            'valueColor' => 'text-green-700 dark:text-green-400',
            'metaColor' => 'text-green-500 dark:text-green-400',
            'lblColor' => 'text-green-500 dark:text-green-400',
            'barColor' => 'bg-green-500 dark:bg-green-400',
        ],
        default => [
            'bg' => 'bg-gray-50 dark:bg-gray-800/40',
            'border' => 'border-gray-200 dark:border-gray-700/60',
            'iconBg' => 'bg-gray-100 dark:bg-gray-700/50',
            'iconColor' => 'text-gray-500 dark:text-gray-400',
            'titleColor' => 'text-gray-700 dark:text-gray-300',
            'valueColor' => 'text-gray-700 dark:text-gray-300',
            'metaColor' => 'text-gray-500 dark:text-gray-400',
            'lblColor' => 'text-gray-500 dark:text-gray-400',
            'barColor' => 'bg-gray-400 dark:bg-gray-500',
        ],
    };

    if ($promo->type->value === 'free_shipping') {
        $discountDisplay = 'FREE';
        $discountLabel = 'Ongkos Kirim';
        $discountNominal =
            $promo->discount_type->value === 'percentage'
                ? number_format($promo->discount_value, 0) . '%'
                : 'Rp ' . number_format($promo->discount_value, 0, ',', '.');
    } elseif ($promo->discount_type->value === 'percentage') {
        $discountDisplay = number_format($promo->discount_value, 0) . '%';
        $discountLabel = 'Diskon';
        $discountNominal = null;
    } else {
        $discountDisplay = 'Rp ' . number_format($promo->discount_value, 0, ',', '.');
        $discountLabel = 'Diskon';
        $discountNominal = null;
    }

    $usagePercent = $promo->usage_limit > 0 ? min(100, ($promo->usage_count / $promo->usage_limit) * 100) : 0;

    $editUrl = PromoResource::getUrl('edit', ['record' => $promo]);
@endphp

<div @class([
    'relative rounded-xl border p-5 shadow flex flex-col gap-4',
    'transition-transform duration-200 hover:shadow-lg hover:-translate-y-1',
    $cfg['bg'],
    $cfg['border'],
    'opacity-60 ring-1 ring-inset ring-danger-300 dark:ring-danger-800' => $promo->trashed(),
])>

    {{-- Trashed badge --}}
    @if ($promo->trashed())
        <span
            class="absolute top-3 right-10 inline-flex items-center gap-1 px-2 py-0.5 rounded-full
                     text-xs font-semibold bg-danger-100 text-danger-700
                     dark:bg-danger-900 dark:text-danger-300">
            <x-heroicon-o-trash class="w-3 h-3" />
            Deleted
        </span>
    @endif

    {{-- ── Header ── --}}
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
                <p class="text-base font-bold {{ $cfg['titleColor'] }} leading-snug">{{ $promo->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $promo->type->getLabel() }}</p>
            </div>
        </div>

        {{-- Dropdown --}}
        <div x-data="{ open: false }" class="relative shrink-0">
            <button @click.stop="open = !open" type="button"
                class="w-8 h-8 rounded-md flex items-center justify-center
                       text-gray-400 dark:text-gray-500
                       hover:bg-black/5 dark:hover:bg-white/10 transition-colors"
                title="Aksi">
                <x-heroicon-o-ellipsis-horizontal class="w-5 h-5" />
            </button>

            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.outside="open = false"
                class="absolute right-0 mt-1 min-w-40
                       bg-white dark:bg-gray-800
                       border border-gray-200 dark:border-white/10
                       rounded-xl shadow-lg overflow-hidden z-50"
                style="display:none;">

                @unless ($promo->trashed())
                    {{-- Edit --}}
                    <a href="{{ $editUrl }}"
                        class="flex items-center gap-2 px-4 py-2 text-sm
                               text-gray-700 dark:text-gray-200
                               hover:bg-gray-50 dark:hover:bg-white/5">
                        <x-heroicon-o-pencil-square class="w-4 h-4 text-primary-500" />
                        Edit
                    </a>
                @endunless

                @if ($promo->trashed())
                    {{-- Restore --}}
                    <button type="button" wire:click.stop="confirmRestore({{ $promo->id }})" @click="open = false"
                        class="flex items-center gap-2 px-4 py-2 text-sm w-full text-left
                               text-success-600 dark:text-success-400
                               hover:bg-success-50 dark:hover:bg-success-950/40">
                        <x-heroicon-o-arrow-path class="w-4 h-4" />
                        Restore
                    </button>
                @else
                    {{-- Delete --}}
                    <button type="button" wire:click.stop="confirmDelete({{ $promo->id }})" @click="open = false"
                        class="flex items-center gap-2 px-4 py-2 text-sm w-full text-left
                               text-danger-600 dark:text-danger-400
                               hover:bg-danger-50 dark:hover:bg-danger-950/40">
                        <x-heroicon-o-trash class="w-4 h-4" />
                        Delete
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Discount ── --}}
    <div class="text-center py-2">
        <p class="text-3xl font-extrabold {{ $cfg['valueColor'] }} leading-tight">{{ $discountDisplay }}</p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">{{ $discountLabel }}</p>
        @if ($discountNominal)
            <p class="text-[12px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $discountNominal }}</p>
        @endif
    </div>

    {{-- ── Meta ── --}}
    <div class="flex flex-col gap-1 text-sm">
        <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Min. Purchase</span>
            <span class="font-semibold {{ $cfg['metaColor'] }}">
                Rp {{ number_format($promo->min_purchase, 0, ',', '.') }}
            </span>
        </div>
        <div class="flex justify-between items-center">
            <span class="text-gray-500 dark:text-gray-400">Periode</span>
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
                <div class="h-full rounded-full {{ $cfg['barColor'] }}" style="width:{{ $usagePercent }}%"></div>
            </div>
        </div>
    @endif

    <hr class="border-t border-gray-200 dark:border-white/10 my-1" />

    {{-- ── Active Toggle ── --}}
    <div class="flex justify-between items-center">
        <span class="text-sm font-semibold {{ $cfg['lblColor'] }}">
            {{ $promo->is_active ? 'Active' : 'Inactive' }}
        </span>
        <button type="button" wire:click="toggleActive({{ $promo->id }})" wire:loading.attr="disabled"
            wire:target="toggleActive({{ $promo->id }})" @disabled($promo->trashed()) @class([
                'relative w-11 h-6 flex items-center rounded-full p-0.5 transition-colors duration-200',
                'focus:outline-none focus:ring-2 focus:ring-primary-500',
                'disabled:opacity-60 disabled:cursor-not-allowed',
                'bg-primary-500 dark:bg-primary-600' => $promo->is_active,
                'bg-gray-300 dark:bg-gray-600' => !$promo->is_active,
                'cursor-not-allowed opacity-40' => $promo->trashed(),
            ])>
            <span @class([
                'bg-white w-5 h-5 rounded-full shadow transition-transform duration-200',
                'translate-x-5' => $promo->is_active,
                'translate-x-0' => !$promo->is_active,
            ])></span>
        </button>
    </div>

</div>
