<x-filament-panels::page>
    @if ($this->viewMode === 'kanban')
        @php
            $board = $this->getKanbanBoard();
        @endphp

        @include($board->getBoardView(), [
            'board' => $board,
            'columns' => $this->getKanbanColumns(),
        ])
    @else
        {{ $this->table }}
    @endif
</x-filament-panels::page>
