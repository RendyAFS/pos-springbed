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

    <x-filament::modal id="down-payment-modal" width="4xl">
        <x-slot name="heading">
            Tambah Down Payment
        </x-slot>

        <form wire:submit="saveDownPayment" class="space-y-4">

            {{ $this->downPaymentForm }}

            <div class="flex justify-end">
                <x-filament::button type="submit">
                    Simpan
                </x-filament::button>
            </div>

        </form>
    </x-filament::modal>

</x-filament-panels::page>
