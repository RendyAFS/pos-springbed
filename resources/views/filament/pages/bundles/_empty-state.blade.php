<div class="flex flex-col items-center justify-center py-24 text-gray-400 dark:text-gray-600">
    @if ($bundleFilter === 'trashed')
        <x-heroicon-o-trash class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">No deleted bundles</p>
        <p class="text-sm mt-1">All bundles are currently active.</p>
    @elseif ($bundleSearch)
        <x-heroicon-o-magnifying-glass class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">No results found</p>
        <p class="text-sm mt-1">Try a different keyword.</p>
    @else
        <x-heroicon-o-cube-transparent class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">No bundles yet</p>
        <p class="text-sm mt-1">Create your first bundle to get started.</p>
    @endif
</div>
