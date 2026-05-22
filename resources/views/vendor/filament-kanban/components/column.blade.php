@php
    $colorFromBoard = $board->resolveColumnColor($column);
    $color = $colorFromBoard;

    if (! in_array($color, ['primary', 'success', 'warning', 'danger', 'info'])) {
        $color = match($column->value ?? '') {
            'pending'   => 'gray',
            'processed' => 'warning',
            'shipped'   => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default     => 'gray',
        };
    }

    $wipLimit = $board->getWipLimit($column->value);
    $isOverWip = $board->isOverWipLimit($column);
    $summary = $board->resolveColumnSummary($column);
    $isCollapsible = $board->isCollapsible();
    $hasHeaderAction = $board->hasColumnHeaderAction();
    $hasEmptyState = $board->hasEmptyState() && $column->count === 0;

    $columnBgStyle = match($color) {
        'warning' => 'background-color: #fffbeb; border-color: #fde68a;',
        'success' => 'background-color: #f0fdf4; border-color: #bbf7d0;',
        'danger'  => 'background-color: #fff1f2; border-color: #fecdd3;',
        'info'    => 'background-color: #ecfeff; border-color: #a5f3fc;',
        'primary' => 'background-color: #f5f3ff; border-color: #ddd6fe;',
        default   => 'background-color: #f9fafb; border-color: #e5e7eb;',
    };

    // Dark mode handled via CSS class fallback (Tailwind class tetap dipakai untuk dark)
    $columnDarkClass = match($color) {
        'warning' => 'dark:bg-warning-400/10 dark:border-warning-400/20',
        'success' => 'dark:bg-success-400/10 dark:border-success-400/20',
        'danger'  => 'dark:bg-danger-400/10 dark:border-danger-400/20',
        'info'    => 'dark:bg-info-400/10 dark:border-info-400/20',
        'primary' => 'dark:bg-primary-400/10 dark:border-primary-400/20',
        default   => 'dark:bg-white/5 dark:border-white/10',
    };

    $badgeBgStyle = $isOverWip
        ? 'background-color:#fee2e2; color:#991b1b;'
        : match($color) {
            'warning' => 'background-color:#fef3c7; color:#92400e;',
            'success' => 'background-color:#dcfce7; color:#166534;',
            'danger'  => 'background-color:#fee2e2; color:#991b1b;',
            'info'    => 'background-color:#cffafe; color:#155e75;',
            'primary' => 'background-color:#ede9fe; color:#5b21b6;',
            default   => 'background-color:#f3f4f6; color:#374151;',
        };

    $headerTextStyle = match($color) {
        'warning' => 'color:#92400e;',
        'success' => 'color:#166534;',
        'danger'  => 'color:#991b1b;',
        'info'    => 'color:#155e75;',
        'primary' => 'color:#5b21b6;',
        default   => '',
    };

    $topBorderStyle = match($color) {
        'warning' => 'border-top: 3px solid #f59e0b;',
        'success' => 'border-top: 3px solid #22c55e;',
        'danger'  => 'border-top: 3px solid #ef4444;',
        'info'    => 'border-top: 3px solid #06b6d4;',
        'primary' => 'border-top: 3px solid #8b5cf6;',
        default   => 'border-top: 3px solid #9ca3af;',
    };
@endphp

<div
    @class([
        'fi-kanban-column flex-shrink-0 rounded-xl border',
        $columnDarkClass,
        'ring-2 ring-red-500/50' => $isOverWip,
    ])
    style="{{ $columnBgStyle }} {{ $topBorderStyle }} width: {{ $board->getColumnWidth() }};"
    @if($isCollapsible)
        x-data="{ collapsed: localStorage.getItem('kanban-col-{{ $column->value }}') === '1' }"
    @endif
    role="group"
    aria-label="{{ $column->label }}"
>
    <div class="flex items-center justify-between gap-2 p-3">
        <div class="flex items-center gap-2 min-w-0">
            @if($isCollapsible)
                <button
                    type="button"
                    x-on:click="collapsed = !collapsed; localStorage.setItem('kanban-col-{{ $column->value }}', collapsed ? '1' : '0')"
                    class="shrink-0 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                    :aria-expanded="(!collapsed).toString()"
                    aria-label="Toggle {{ $column->label }}"
                >
                    <x-filament::icon
                        icon="heroicon-m-chevron-down"
                        class="h-4 w-4 transition-transform duration-200"
                        x-bind:class="collapsed ? '-rotate-90' : ''"
                    />
                </button>
            @endif

            @if($column->icon)
                <x-filament::icon
                    :icon="$column->icon"
                    class="h-5 w-5 shrink-0"
                    style="{{ $headerTextStyle }}"
                />
            @endif
            <h3 class="text-sm font-semibold truncate" style="{{ $headerTextStyle ?: 'color: rgb(3 7 18);' }}">
                {{ $column->label }}
            </h3>
        </div>

        <div class="flex items-center gap-1.5 shrink-0">
            <span
                class="inline-flex items-center justify-center rounded-full px-2 py-0.5 text-xs font-medium"
                style="{{ $badgeBgStyle }}"
                @if($isOverWip) title="Over WIP limit ({{ $wipLimit }})" @endif
            >
                {{ $column->count }}@if($wipLimit) / {{ $wipLimit }}@endif
            </span>

            @if($hasHeaderAction)
                <button
                    type="button"
                    wire:click="mountAction('kanbanColumn', { column: '{{ $column->value }}' })"
                    class="rounded-md p-0.5 text-gray-400 hover:text-gray-600 hover:bg-gray-200/50 dark:text-gray-500 dark:hover:text-gray-300 dark:hover:bg-white/10 transition"
                    aria-label="Add to {{ $column->label }}"
                >
                    <x-filament::icon icon="heroicon-m-plus" class="h-4 w-4" />
                </button>
            @endif
        </div>
    </div>

    @if($summary)
        <div class="px-3 pb-1 text-xs text-gray-500 dark:text-gray-400">
            {{ $summary }}
        </div>
    @endif

    <div
        data-kanban-column
        data-column-value="{{ $column->value }}"
        class="fi-kanban-cards space-y-2 p-2 min-h-15"
        @if($isCollapsible) x-show="!collapsed" @endif
        role="list"
    >
        @forelse($column->records as $record)
            @include($board->getCardView(), [
                'record' => $record,
                'board' => $board,
                'column' => $column,
            ])
        @empty
            @if($hasEmptyState)
                <div class="flex flex-col items-center justify-center py-6 text-center">
                    @if($board->getEmptyStateIcon())
                        <x-filament::icon
                            :icon="$board->getEmptyStateIcon()"
                            class="h-8 w-8 text-gray-300 dark:text-gray-600 mb-2"
                        />
                    @endif
                    <p class="text-xs font-medium text-gray-400 dark:text-gray-500">
                        {{ $board->getEmptyStateHeading() }}
                    </p>
                    @if($board->getEmptyStateDescription())
                        <p class="text-xs text-gray-400 dark:text-gray-600 mt-0.5">
                            {{ $board->getEmptyStateDescription() }}
                        </p>
                    @endif
                </div>
            @endif
        @endforelse
    </div>
</div>
