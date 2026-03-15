<x-filament-panels::page>

    {{-- ── Delete Confirmation Modal ── --}}
    @if ($showDeleteModal)
        @include('filament.pages.bundles._modal-delete')
    @endif

    {{-- ── Restore Confirmation Modal ── --}}
    @if ($showRestoreModal)
        @include('filament.pages.bundles._modal-restore')
    @endif

    {{-- ── Toolbar ── --}}
    @include('filament.pages.bundles._toolbar')

    {{-- ── Grid / Empty state ── --}}
    @if ($bundles->isEmpty())
        @include('filament.pages.bundles._empty-state')
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @foreach ($bundles as $bundle)
                @include('filament.pages.bundles._bundle-card', ['bundle' => $bundle])
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bundles->links() }}
        </div>
    @endif

</x-filament-panels::page>
