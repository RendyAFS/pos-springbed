@php
    $board = $this->getKanbanBoard();
    $columns = $this->getKanbanColumns();
    $hasFilters = $board->hasFilters();
    $hasSearch = $board->isSearchable();
    $hasLoading = $board->hasLoading();
    $activeFilterCount = collect($this->kanbanFilters)->filter(fn($v) => filled($v))->count();
@endphp

<div x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('filament-kanban', 'wezlo/filament-kanban') }}"
    x-data="kanbanBoard({
        hasOrderColumn: @js(filled($board->getOrderColumn())),
        hasCardAction: @js($board->hasCardClickAction()),
        collapsible: @js($board->isCollapsible()),
        dragConstraints: @js($board->getDragConstraints()),
    })" class="fi-kanban-board">

    @if ($hasFilters || $hasSearch)
        <div class="mb-4 flex items-center gap-2 shrink-0">
            @if ($hasSearch)
                <div class="w-full p-2">
                    <div class="flex justify-end">
                        <div class="fi-input-wrp">
                            <div class="fi-input-wrp-prefix fi-input-wrp-prefix-has-content fi-inline">
                                <x-filament::icon icon="heroicon-m-magnifying-glass" class="fi-icon fi-size-md" />
                            </div>
                            <div class="fi-input-wrp-content-ctn">
                                <input type="search" wire:model.live.debounce.300ms="kanbanSearch" placeholder="Search"
                                    aria-label="Search board" autocomplete="off"
                                    class="fi-input fi-input-has-inline-prefix" />
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($hasFilters)
                <div x-data="{ open: false }" class="relative shrink-0">
                    <button type="button" x-on:click="open = !open" :aria-expanded="open.toString()"
                        aria-controls="kanban-filters-panel" aria-label="Toggle filters" @class([
                            'relative flex items-center justify-center rounded-lg p-2 text-sm shadow-sm ring-1 transition duration-75',
                            'bg-white ring-gray-950/10 text-gray-700 hover:bg-gray-50 dark:bg-white/5 dark:ring-white/20 dark:text-gray-300 dark:hover:bg-white/10' =>
                                $activeFilterCount === 0,
                            'bg-primary-50 ring-primary-200 text-primary-700 hover:bg-primary-100 dark:bg-primary-400/10 dark:ring-primary-400/30 dark:text-primary-400' =>
                                $activeFilterCount > 0,
                        ])>
                        <x-filament::icon icon="heroicon-m-funnel" class="h-5 w-5" />

                        @if ($activeFilterCount > 0)
                            <span
                                class="absolute -top-1 -inset-e-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary-500 text-[10px] font-bold text-white">
                                {{ $activeFilterCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" x-on:click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        id="kanban-filters-panel" role="dialog" aria-label="Filters"
                        style="position: absolute; right: 0; top: calc(100% + 8px); z-index: 50; width: min(384px, 90vw);"
                        class="rounded-xl bg-white p-4 shadow-lg ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                        x-cloak>
                        <div class="mb-3 flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-950 dark:text-white">
                                Filter
                            </h4>

                            @if ($activeFilterCount > 0)
                                <button type="button" wire:click="$set('kanbanFilters', [])"
                                    class="text-xs text-danger-600 hover:text-danger-500 dark:text-danger-400">
                                    Reset
                                </button>
                            @endif
                        </div>

                        {{ $this->kanbanFiltersForm }}
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if ($hasLoading)
        <div wire:loading.delay
            class="fi-kanban-loading absolute inset-0 z-10 flex items-center justify-center rounded-xl bg-white/60 dark:bg-gray-900/60">
            <x-filament::loading-indicator class="h-8 w-8 text-primary-500" />
        </div>
    @endif

    <div class="fi-kanban-scroll relative overflow-x-auto overflow-y-hidden pb-4" wire:ignore.self>
        <div class="flex min-w-max items-start gap-4" style="min-height: 60vh;">
            @foreach ($columns as $column)
                @include($board->getColumnView(), [
                    'column' => $column,
                    'board' => $board,
                ])
            @endforeach
        </div>
    </div>

    <x-filament-actions::modals />
</div>
