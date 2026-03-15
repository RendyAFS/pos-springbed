<div class="flex flex-wrap items-center gap-3 mb-6">
    {{-- Search --}}
    <div class="relative flex-1 max-w-xs">
        <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
            <x-heroicon-o-magnifying-glass class="w-4 h-4 text-gray-400 dark:text-gray-500" wire:loading.class="opacity-0"
                wire:target="promoSearch" />
            <svg wire:loading wire:target="promoSearch" class="animate-spin w-4 h-4 text-primary-500 absolute"
                viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
            </svg>
        </span>

        <input type="text" wire:model.live.debounce.400ms="promoSearch" placeholder="Cari promosi…"
            class="w-full pl-9 pr-10 py-2 rounded-lg border border-gray-300 dark:border-white/10
                   bg-white dark:bg-white/5 text-gray-900 dark:text-white
                   placeholder-gray-400 dark:placeholder-gray-500
                   text-sm shadow-sm outline-none
                   focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" />

        @if ($promoSearch)
            <button wire:click="$set('promoSearch', '')"
                class="absolute inset-y-0 right-2 flex items-center px-1
                       text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                title="Hapus pencarian">
                <x-heroicon-o-x-circle class="w-4 h-4" />
            </button>
        @endif
    </div>

    {{-- Filter Toggle: Active / Trashed / All
         Menggunakan x-filament::button agar konsisten dengan Filament style.
         wire:click mengubah $promoFilter di ListPromos.php (Livewire property).
    --}}
    <div class="flex items-center gap-1 p-1 rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5"
        x-data x-tooltip.raw="Filter data promosi">

        @php
            $filters = [
                'active' => ['label' => 'Aktif', 'icon' => 'heroicon-o-check-circle'],
                'trashed' => ['label' => 'Dihapus', 'icon' => 'heroicon-o-trash'],
                'all' => ['label' => 'Semua', 'icon' => 'heroicon-o-squares-2x2'],
            ];
        @endphp

        @foreach ($filters as $value => $cfg)
            <button wire:click="$set('promoFilter', '{{ $value }}')" type="button" title="{{ $cfg['label'] }}"
                @class([
                    'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-sm font-medium transition-all',
                    // Active state
                    'bg-white dark:bg-white/10 text-gray-900 dark:text-white shadow-sm ring-1 ring-gray-200 dark:ring-white/10' =>
                        $promoFilter === $value,
                    // Inactive state
                    'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' =>
                        $promoFilter !== $value,
                ])>
                <x-dynamic-component :component="$cfg['icon']" class="w-4 h-4" />
                <span>{{ $cfg['label'] }}</span>
            </button>
        @endforeach
    </div>

</div>
