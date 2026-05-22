@php
    $colorFromBoard = $board->resolveColumnColor($column);
    $color = $colorFromBoard;

    $cfg = match ($color) {
        'warning' => [
            'bg' => 'bg-primary-50 dark:bg-primary-950/40',
            'title' => 'text-primary-700 dark:text-primary-50',
            'badge' => 'bg-primary-100 text-primary-700',
        ],

        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-950/40',
            'title' => 'text-green-700 dark:text-green-50',
            'badge' => 'bg-green-100 text-green-700',
        ],

        'danger' => [
            'bg' => 'bg-red-50 dark:bg-red-950/40',
            'title' => 'text-red-700 dark:text-red-50',
            'badge' => 'bg-red-100 text-red-700',
        ],

        'info' => [
            'bg' => 'bg-blue-50 dark:bg-blue-950/40',
            'title' => 'text-blue-500 dark:text-blue-50',
            'badge' => 'bg-blue-100 text-blue-500',
        ],

        'gray' => [
            'bg' => 'bg-gray-50 dark:bg-gray-800/40',
            'title' => 'text-gray-700 dark:text-gray-50',
            'badge' => 'bg-gray-100 text-gray-700',
        ],
    };

    $wipLimit = $board->getWipLimit($column->value);
    $isOverWip = $board->isOverWipLimit($column);
    $summary = $board->resolveColumnSummary($column);
    $isCollapsible = $board->isCollapsible();
    $hasHeaderAction = $board->hasColumnHeaderAction();
    $hasEmptyState = $board->hasEmptyState() && $column->count === 0;
@endphp

<div @class([
    'fi-kanban-column flex-shrink-0 rounded-xl',
    $cfg['bg'],
    'ring-2 ring-danger-500/40' => $isOverWip,
]) style="width: {{ $board->getColumnWidth() }};"
    @if ($isCollapsible) x-data="{ collapsed: localStorage.getItem('kanban-col-{{ $column->value }}') === '1' }" @endif
    role="group" aria-label="{{ $column->label }}">
    <div class="flex items-center justify-between gap-2 p-3">
        <div class="flex items-center gap-2 min-w-0">
            @if ($isCollapsible)
                <button type="button"
                    x-on:click="collapsed = !collapsed; localStorage.setItem('kanban-col-{{ $column->value }}', collapsed ? '1' : '0')"
                    class="shrink-0 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                    :aria-expanded="(!collapsed).toString()" aria-label="Toggle {{ $column->label }}">
                    <x-filament::icon icon="heroicon-m-chevron-down" class="h-4 w-4 transition-transform duration-200"
                        x-bind:class="collapsed ? '-rotate-90' : ''" />
                </button>
            @endif

            @if ($column->icon)
                <x-filament::icon :icon="$column->icon" class="h-5 w-5 shrink-0 {{ $cfg['title'] }}" />
            @endif
            <h3 class="text-sm font-semibold truncate {{ $cfg['title'] }}">
                {{ $column->label }}
            </h3>
        </div>

        <div class="flex items-center gap-1.5 shrink-0">
            <span @class([
                'inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-medium',
                $isOverWip
                    ? 'bg-danger-100 text-danger-700 dark:bg-danger-500/15 dark:text-danger-400'
                    : $cfg['badge'],
            ])
                @if ($isOverWip) title="Over WIP limit ({{ $wipLimit }})" @endif>
                {{ $column->count }}@if ($wipLimit)
                    / {{ $wipLimit }}
                @endif
            </span>

            @if ($hasHeaderAction)
                <button type="button" wire:click="mountAction('kanbanColumn', { column: '{{ $column->value }}' })"
                    class="rounded-md p-0.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200/50 dark:text-gray-500 dark:hover:text-gray-300 dark:hover:bg-white/10 transition"
                    aria-label="Add to {{ $column->label }}">
                    <x-filament::icon icon="heroicon-m-plus" class="h-4 w-4" />
                </button>
            @endif
        </div>
    </div>

    @if ($summary)
        <div class="px-3 pb-1 text-xs text-gray-500 dark:text-gray-400">
            {{ $summary }}
        </div>
    @endif

    <div data-kanban-column data-column-value="{{ $column->value }}" class="fi-kanban-cards space-y-2 p-2 min-h-15"
        @if ($isCollapsible) x-show="!collapsed" @endif role="list">
        @forelse($column->records as $record)
            @include($board->getCardView(), [
                'record' => $record,
                'board' => $board,
                'column' => $column,
            ])
        @empty
            @if ($hasEmptyState)
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    @if ($board->getEmptyStateIcon())
                        <x-filament::icon :icon="$board->getEmptyStateIcon()" class="h-8 w-8 text-gray-300 dark:text-gray-600 mb-2" />
                    @endif
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500">
                        {{ $board->getEmptyStateHeading() }}
                    </p>
                    @if ($board->getEmptyStateDescription())
                        <p class="text-xs text-gray-400 dark:text-gray-600 mt-0.5">
                            {{ $board->getEmptyStateDescription() }}
                        </p>
                    @endif
                </div>
            @endif
        @endforelse
    </div>
</div>
