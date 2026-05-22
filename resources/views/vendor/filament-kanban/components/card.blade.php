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
            'border' => 'border-s-gray-400',
            'badgeColor' => 'fi-color fi-color-gray fi-text-color-600 dark:fi-text-color-400',
        ],

        'processed' => [
            'title' => 'text-primary-500',
            'border' => 'border-s-primary-500',
            'badgeColor' => 'fi-color fi-color-primary fi-text-color-700 dark:fi-text-color-400',
        ],

        'shipped' => [
            'title' => 'text-blue-500',
            'border' => 'border-s-blue-500',
            'badgeColor' => 'fi-color fi-color-info fi-text-color-700 dark:fi-text-color-400',
        ],

        'delivered' => [
            'title' => 'text-green-500',
            'border' => 'border-s-green-500',
            'badgeColor' => 'fi-color fi-color-success fi-text-color-700 dark:fi-text-color-400',
        ],

        'cancelled' => [
            'title' => 'text-red-500',
            'border' => 'border-s-red-500',
            'badgeColor' => 'fi-color fi-color-danger fi-text-color-700 dark:fi-text-color-400',
        ],

        default => [
            'title' => 'text-gray-500 dark:text-gray-300',
            'border' => 'border-s-gray-300',
            'badgeColor' => 'fi-color fi-color-gray fi-text-color-600 dark:fi-text-color-400',
        ],
    };
@endphp

<div data-kanban-card data-record-id="{{ $recordId }}"
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
        <div class="mt-2 flex flex-wrap gap-1">
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

                <span class="{{ $badgeClass }} fi-badge fi-size-sm">
                    {{ $badge['label'] }}
                </span>
            @endforeach
        </div>
    @endif

    @if ($hasFooterActions)
        <div class="mt-2 flex items-center justify-end gap-1 border-t border-gray-100 pt-2 dark:border-white/5">
            @foreach ($footerActions as $footerAction)
                @php
                    $actionUrl = $footerAction->record($record)->getUrl();
                    $actionColorClasses = match ($footerAction->getColor() ?? 'gray') {
                        'primary' => 'text-primary-500 hover:bg-primary-50 dark:hover:bg-primary-400/10',
                        'danger' => 'text-danger-500 hover:bg-danger-50 dark:hover:bg-danger-400/10',
                        'success' => 'text-success-500 hover:bg-success-50 dark:hover:bg-success-400/10',
                        'warning' => 'text-warning-500 hover:bg-warning-50 dark:hover:bg-warning-400/10',
                        default
                            => 'text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-300',
                    };
                @endphp

                @if ($actionUrl)
                    <a href="{{ $actionUrl }}" @if ($footerAction->shouldOpenUrlInNewTab()) target="_blank" @endif
                        class="rounded-md p-1 transition {{ $actionColorClasses }}"
                        title="{{ $footerAction->getLabel() }}" x-on:click.stop>
                        @if ($footerAction->getIcon())
                            <x-filament::icon :icon="$footerAction->getIcon()" class="h-4 w-4" />
                        @else
                            <span class="text-xs">{{ $footerAction->getLabel() }}</span>
                        @endif
                    </a>
                @else
                    {{ $footerAction }}
                @endif
            @endforeach
        </div>
    @endif
</div>
