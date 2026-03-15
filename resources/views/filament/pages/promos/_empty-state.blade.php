{{-- _empty-state.blade.php --}}
<div class="flex flex-col items-center justify-center py-24 text-gray-400 dark:text-gray-600">
    @if ($promoFilter === 'trashed')
        <x-heroicon-o-trash class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">No deleted promotions</p>
        <p class="text-sm mt-1">All promotions are still active in the system.</p>
    @elseif ($promoSearch)
        <x-heroicon-o-magnifying-glass class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">Not found</p>
        <p class="text-sm mt-1">Try a different keyword.</p>
    @else
        <x-heroicon-o-tag class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">No promotions</p>
        <p class="text-sm mt-1">Create your first promotion to get started.</p>
    @endif
</div>
