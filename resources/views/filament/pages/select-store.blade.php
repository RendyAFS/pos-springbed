<x-filament-panels::page>
    <form wire:submit="submit" class="max-w-4xl mx-auto space-y-8">

        {{-- Title --}}
        <div class="text-center space-y-2">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                Pilih Store
            </h2>
            <p class="text-base text-gray-500 dark:text-gray-400">
                Pilih store yang ingin kamu gunakan
            </p>
        </div>

        {{-- Store Cards --}}
        <div class="flex justify-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 justify-items-center content-center min-h-95 max-h-95 overflow-y-auto w-full px-10">
                @foreach ($this->getStores() as $id => $name)
                    <label class="cursor-pointer group flex justify-center w-full"
                        wire:click="$set('store_setting_id', {{ (int) $id }})">
                        <input type="radio" wire:model="store_setting_id" value="{{ (int) $id }}" class="hidden">

                        <div @class([
                            'relative w-full max-w-xs rounded-2xl border p-6 flex flex-col items-center text-center gap-4',
                            'transition-all duration-200 ease-in-out',
                            'bg-white dark:bg-gray-900',
                            'hover:shadow-xl hover:-translate-y-1 hover:border-primary-400',
                            'border-gray-200 dark:border-white/10',

                            // selected
                            'ring-2 ring-primary-500 border-primary-500 shadow-lg scale-[1.02]' =>
                                (int) $store_setting_id === (int) $id,
                        ])>

                            {{-- Selected badge --}}
                            @if ((int) $store_setting_id === (int) $id)
                                <div class="absolute top-3 right-3">
                                    <span
                                        class="w-7 h-7 flex items-center justify-center rounded-full bg-primary-500 text-white shadow">
                                        <x-heroicon-o-check class="w-4 h-4" />
                                    </span>
                                </div>
                            @endif

                            {{-- Icon --}}
                            <div
                                class="w-14 h-14 rounded-xl flex items-center justify-center
                        bg-primary-50 dark:bg-primary-950/40">
                                <x-heroicon-o-building-storefront
                                    class="w-7 h-7 text-primary-500 dark:text-primary-400" />
                            </div>

                            {{-- Store Name --}}
                            <div>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $name }}
                                </p>

                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Store ID: {{ $id }}
                                </p>
                            </div>

                        </div>
                    </label>
                @endforeach

            </div>
        </div>

        {{-- Submit --}}
        <div class="max-w-sm mx-auto">
            <x-filament::button type="submit" class="w-full" size="lg">
                Masuk ke Dashboard
            </x-filament::button>
        </div>

    </form>
</x-filament-panels::page>
