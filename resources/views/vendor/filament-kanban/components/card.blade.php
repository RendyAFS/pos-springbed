@php
    $recordId = $record->getKey();
    $hasClickAction = $board->hasCardClickAction();
    $url = !$hasClickAction ? $board->getRecordUrl($record) : null;
    $footerActions = $board->getCardFooterActions();
    $hasFooterActions = !empty($footerActions);

    $columnValue = $column->value ?? '';
    $cfg = match ($columnValue) {
        'pending' => [
            'title' => 'text-gray-500 dark:text-gray-300',
            'border' => 'fi-kanban-border-pending',
            'badgeColor' => 'fi-color fi-color-gray fi-text-color-600 dark:fi-text-color-400',
        ],

        'processed' => [
            'title' => 'text-amber-500',
            'border' => 'fi-kanban-border-processing',
            'badgeColor' => 'fi-color fi-color-warning fi-text-color-700 dark:fi-text-color-400',
        ],

        'shipped' => [
            'title' => 'text-blue-500',
            'border' => 'fi-kanban-border-shipped',
            'badgeColor' => 'fi-color fi-color-info fi-text-color-700 dark:fi-text-color-400',
        ],

        'delivered' => [
            'title' => 'text-green-500',
            'border' => 'fi-kanban-border-delivered',
            'badgeColor' => 'fi-color fi-color-success fi-text-color-700 dark:fi-text-color-400',
        ],

        'cancelled' => [
            'title' => 'text-red-500',
            'border' => 'fi-kanban-border-cancelled',
            'badgeColor' => 'fi-color fi-color-danger fi-text-color-700 dark:fi-text-color-400',
        ],

        default => [
            'title' => 'text-gray-500 dark:text-gray-300',
            'border' => 'fi-kanban-border-gray',
            'badgeColor' => 'fi-color fi-color-gray fi-text-color-600 dark:fi-text-color-400',
        ],
    };
@endphp

<div data-kanban-card data-record-id="{{ $recordId }}" wire:key="kanban-card-{{ $recordId }}"
    @if ($url) x-on:dblclick="window.location.href = '{{ $url }}'" @endif role="listitem"
    aria-label="{{ $board->resolveCardTitle($record) }}" @class([
        'fi-kanban-card rounded-xl bg-white dark:bg-gray-800 p-3 shadow-sm ring-1 ring-gray-950/5 transition-all hover:shadow-md dark:ring-white/10 border-s-4',
        $cfg['border'],
        'cursor-pointer' => $hasClickAction,
        'cursor-grab' => !$hasClickAction,
    ])>
    <div class="text-sm font-semibold {{ $cfg['title'] }}" style="font-family: ui-monospace, monospace;">
        {{ $board->resolveCardTitle($record) }}
    </div>

    @if ($description = $board->resolveCardDescription($record))
        <div class="mt-2 space-y-0.5">
            @foreach (explode("\n", $description) as $line)
                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ $line }}
                </p>
            @endforeach
        </div>
    @endif

    @if ($badges = $board->resolveCardBadges($record))
        <div class="mt-2 flex flex-wrap items-start gap-1">
            @foreach ($badges as $badge)
                @php
                    $badgeClass = match ($badge['color'] ?? 'gray') {
                        'success' => 'fi-color fi-color-success fi-text-color-700 dark:fi-text-color-400',
                        'warning' => 'fi-color fi-color-warning fi-text-color-700 dark:fi-text-color-400',
                        'danger' => 'fi-color fi-color-danger fi-text-color-700 dark:fi-text-color-400',
                        'info' => 'fi-color fi-color-info fi-text-color-700 dark:fi-text-color-400',
                        'primary' => 'fi-color fi-color-primary fi-text-color-700 dark:fi-text-color-400',
                        default => $cfg['badgeColor'],
                    };
                @endphp

                <span class="{{ $badgeClass }} fi-badge fi-size-sm self-start">
                    {!! nl2br(e($badge['label'])) !!}
                </span>
            @endforeach
        </div>
    @endif

    @php
        $iconActions = collect($footerActions)->filter(fn($action) => in_array($action->getName(), ['view', 'edit']));

        $dropdownActions = collect($footerActions)->reject(
            fn($action) => in_array($action->getName(), ['view', 'edit']),
        );
    @endphp

    @if ($hasFooterActions)
        <div x-data="{ open: false, dropdownStyle: '' }"
            class="mt-3 flex items-center justify-end gap-1 border-t border-gray-100 pt-2 dark:border-white/5 overflow-visible">

            {{-- View & Edit --}}
            @foreach ($iconActions as $footerAction)
                @php
                    $action = clone $footerAction;
                    $action->record($record);

                    $actionUrl = method_exists($action, 'getUrl') ? $action->getUrl() : null;

                    $iconColor = match ($action->getColor()) {
                        'primary' => 'text-primary-600 dark:text-primary-400',
                        'success' => 'text-success-600 dark:text-success-400',
                        'warning' => 'text-warning-600 dark:text-warning-400',
                        'danger' => 'text-danger-600 dark:text-danger-400',
                        'info' => 'text-info-600 dark:text-info-400',
                        default => 'text-gray-600 dark:text-gray-400',
                    };
                @endphp

                @if ($actionUrl)
                    <a href="{{ $actionUrl }}" @if ($action->shouldOpenUrlInNewTab()) target="_blank" @endif
                        title="{{ $action->getLabel() }}"
                        class="rounded-md p-1.5 transition hover:bg-gray-100 dark:hover:bg-white/10"
                        wire:key="kanban-icon-{{ $recordId }}-{{ $action->getName() }}">

                        <x-filament::icon :icon="$action->getIcon()" class="h-5 w-5 {{ $iconColor }}" />
                    </a>
                @else
                    <button type="button" title="{{ $action->getLabel() }}"
                        wire:click.stop="mountAction('{{ $action->getName() }}', { record: {{ $record->getKey() }} })"
                        class="rounded-md p-1.5 transition hover:bg-gray-100 dark:hover:bg-white/10"
                        wire:key="kanban-icon-{{ $recordId }}-{{ $action->getName() }}">

                        <x-filament::icon :icon="$action->getIcon()" class="h-5 w-5 {{ $iconColor }}" />
                    </button>
                @endif
            @endforeach

            {{-- Dropdown --}}
            @if ($dropdownActions->isNotEmpty())
                <div class="relative">

                    <button type="button" x-ref="dropdownTrigger_{{ $recordId }}"
                        @click.stop="
                if (!open) {
                    const rect = $el.getBoundingClientRect();
                    dropdownStyle = `top:${rect.bottom + 8}px; left:${rect.right - 224}px;`;
                }
                open = !open;
            "
                        @scroll.window="open = false" @resize.window="open = false"
                        class="rounded-md p-1.5 text-gray-500 transition hover:bg-gray-100 dark:hover:bg-white/10">

                        <x-filament::icon icon="heroicon-o-ellipsis-horizontal" class="h-5 w-5" />
                    </button>

                    <template x-teleport="body">
                        <div x-show="open" x-transition @click.outside="open = false" x-cloak
                            :style="`position: fixed; z-index: 9999; ${dropdownStyle}`"
                            class="w-56 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-900">

                            @foreach ($dropdownActions as $footerAction)
                                @php
                                    $action = clone $footerAction;
                                    $action->record($record);

                                    $actionUrl = method_exists($action, 'getUrl') ? $action->getUrl() : null;

                                    $iconColor = match ($action->getColor()) {
                                        'primary' => 'text-primary-600 dark:text-primary-400',
                                        'success' => 'text-success-600 dark:text-success-400',
                                        'warning' => 'text-warning-600 dark:text-warning-400',
                                        'danger' => 'text-danger-600 dark:text-danger-400',
                                        'info' => 'text-info-600 dark:text-info-400',
                                        default => 'text-gray-600 dark:text-gray-400',
                                    };
                                @endphp

                                @if ($actionUrl)
                                    <a href="{{ $actionUrl }}"
                                        @if ($action->shouldOpenUrlInNewTab()) target="_blank" @endif
                                        class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-800"
                                        wire:key="kanban-dropdown-{{ $recordId }}-{{ $action->getName() }}">

                                        <x-filament::icon :icon="$action->getIcon()" class="h-4 w-4 {{ $iconColor }}" />

                                        {{ $action->getLabel() }}
                                    </a>
                                @else
                                    <button type="button" @click="open = false"
                                        wire:click.stop="mountAction('{{ $action->getName() }}', { record: {{ $record->getKey() }} })"
                                        class="flex w-full items-center gap-3 px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-800"
                                        wire:key="kanban-dropdown-{{ $recordId }}-{{ $action->getName() }}">

                                        <x-filament::icon :icon="$action->getIcon()" class="h-4 w-4 {{ $iconColor }}" />

                                        {{ $action->getLabel() }}
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </template>
                </div>
            @endif
        </div>
    @endif
</div>
