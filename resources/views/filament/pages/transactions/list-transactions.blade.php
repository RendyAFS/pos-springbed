<x-filament-panels::page>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <div x-data x-cloak class="w-full">

        <div wire:key="kanban-view" x-show="$wire.viewMode === 'kanban'">
            @php
                $board = $this->getKanbanBoard();
            @endphp

            @include($board->getBoardView(), [
                'board' => $board,
                'columns' => $this->getKanbanColumns(),
            ])
        </div>

        <div wire:key="table-view" x-show="$wire.viewMode === 'table'">
            {{ $this->table }}
        </div>

    </div>
</x-filament-panels::page>
