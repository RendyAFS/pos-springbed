@php
    $recordId = $record->getKey();
    $hasClickAction = $board->hasCardClickAction();
    $url = ! $hasClickAction ? $board->getRecordUrl($record) : null;
    $footerActions = $board->getCardFooterActions();
    $hasFooterActions = ! empty($footerActions);

    $columnValue = $column->value ?? '';
    $borderStyle = match($columnValue) {
        'pending'   => 'border-left: 4px solid #9ca3af;',
        'processed' => 'border-left: 4px solid #f59e0b;',
        'shipped'   => 'border-left: 4px solid #06b6d4;',
        'delivered' => 'border-left: 4px solid #22c55e;',
        'cancelled' => 'border-left: 4px solid #ef4444;',
        default     => 'border-left: 4px solid #d1d5db;',
    };
@endphp

<div
    data-kanban-card
    data-record-id="{{ $recordId }}"
    @if($url)
        x-on:dblclick="window.location.href = '{{ $url }}'"
    @endif
    role="listitem"
    aria-label="{{ $board->resolveCardTitle($record) }}"
    style="{{ $borderStyle }}"
    @class([
        'fi-kanban-card rounded-lg bg-white p-3 shadow-sm ring-1 ring-gray-950/5 transition-shadow hover:shadow-md dark:bg-gray-900 dark:ring-white/10',
        'cursor-pointer' => $hasClickAction,
        'cursor-grab' => ! $hasClickAction,
    ])
>
    <div class="text-sm font-semibold text-gray-950 dark:text-white" style="font-family: ui-monospace, monospace;">
        {{ $board->resolveCardTitle($record) }}
    </div>

    @if($description = $board->resolveCardDescription($record))
        <div class="mt-2 space-y-0.5">
            @foreach(explode("\n", $description) as $line)
                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ $line }}
                </p>
            @endforeach
        </div>
    @endif

    @if($badges = $board->resolveCardBadges($record))
        <div class="mt-2 flex flex-wrap gap-1">
            @foreach($badges as $badge)
                @php
                    $badgeColor = $badge['color'] ?? 'gray';
                    $badgeStyle = match($badgeColor) {
                        'success' => 'background:#dcfce7; color:#166534;',
                        'warning' => 'background:#fef9c3; color:#854d0e;',
                        'danger'  => 'background:#fee2e2; color:#991b1b;',
                        'info'    => 'background:#cffafe; color:#155e75;',
                        'primary' => 'background:#ede9fe; color:#5b21b6;',
                        default   => 'background:#f3f4f6; color:#374151;',
                    };
                @endphp
                <span
                    class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xs font-medium"
                    style="{{ $badgeStyle }}"
                >
                    {{ $badge['label'] }}
                </span>
            @endforeach
        </div>
    @endif

    @if($hasFooterActions)
        <div class="mt-2 flex items-center justify-end gap-1 border-t border-gray-100 pt-2 dark:border-white/5">
            @foreach($footerActions as $footerAction)
                @php
                    $actionUrl = $footerAction->record($record)->getUrl();
                    $actionColorClasses = match($footerAction->getColor() ?? 'gray') {
                        'primary' => 'text-primary-500 hover:bg-primary-50 dark:hover:bg-primary-400/10',
                        'danger'  => 'text-danger-500 hover:bg-danger-50 dark:hover:bg-danger-400/10',
                        'success' => 'text-success-500 hover:bg-success-50 dark:hover:bg-success-400/10',
                        'warning' => 'text-warning-500 hover:bg-warning-50 dark:hover:bg-warning-400/10',
                        default   => 'text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-300',
                    };
                @endphp

                @if($actionUrl)
                    <a
                        href="{{ $actionUrl }}"
                        @if($footerAction->shouldOpenUrlInNewTab()) target="_blank" @endif
                        class="rounded-md p-1 transition {{ $actionColorClasses }}"
                        title="{{ $footerAction->getLabel() }}"
                        x-on:click.stop
                    >
                        @if($footerAction->getIcon())
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
