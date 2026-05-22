<x-filament-panels::page>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div x-data x-cloak class="w-full">

        @if ($viewMode === 'kanban')
            @php
                $board = $this->getKanbanBoard();
            @endphp

            @include($board->getBoardView(), [
                'board' => $board,
                'columns' => $this->getKanbanColumns(),
            ])
        @endif

        @if ($viewMode === 'table')
            {{ $this->table }}
        @endif

    </div>
</x-filament-panels::page>
