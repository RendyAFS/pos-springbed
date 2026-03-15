<x-filament-panels::page>

    {{-- ── Delete Confirmation Modal ───────────────────────────────────── --}}
    @if ($showDeleteModal)
        @include('filament.pages.promos._modal-delete')
    @endif

    {{-- ── Restore Confirmation Modal ──────────────────────────────────── --}}
    @if ($showRestoreModal)
        @include('filament.pages.promos._modal-restore')
    @endif

    {{-- ── Toolbar ──────────────────────────────────────────────────────── --}}
    @include('filament.pages.promos._toolbar')

    {{-- ── Grid / Empty state ──────────────────────────────────────────── --}}
    @if ($promos->isEmpty())
        @include('filament.pages.promos._empty-state')
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($promos as $promo)
                @include('filament.pages.promos._promo-card', ['promo' => $promo])
            @endforeach
        </div>

        <div class="mt-6">
            {{ $promos->links() }}
        </div>
    @endif

</x-filament-panels::page>
