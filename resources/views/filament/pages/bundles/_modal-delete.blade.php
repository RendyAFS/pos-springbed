<div class="fixed inset-0 z-9999 flex items-center justify-center" @keydown.escape.window="$wire.cancelDelete()">

    <div class="absolute inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm" wire:click="cancelDelete"></div>

    <div class="relative z-10 w-full max-w-md mx-4 rounded-xl shadow-2xl overflow-hidden
                bg-white dark:bg-gray-900
                border border-gray-200 dark:border-white/10
                text-center"
        role="alertdialog" aria-modal="true">

        <div class="flex flex-col items-center gap-4 px-6 pt-6 pb-4">
            <div
                class="w-16 h-16 rounded-full flex items-center justify-center
                bg-danger-50 dark:bg-danger-950">
                <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-danger-600 dark:text-danger-400" />
            </div>

            <h3 class="text-lg font-semibold text-gray-950 dark:text-white leading-tight">
                Delete Bundle
            </h3>

            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Are you sure you want to delete
                <span class="font-semibold text-gray-800 dark:text-gray-200">
                    "{{ $deleteTargetName }}"
                </span>?
                It can be restored later.
            </p>
        </div>

        <div class="h-px bg-gray-100 dark:bg-white/10 mx-6"></div>

        <div class="flex items-center gap-3 px-6 py-4">
            <button wire:click="cancelDelete" type="button"
                class="flex w-full items-center justify-center rounded-lg px-4 py-3 text-sm font-medium
                       bg-white dark:bg-white/5
                       text-gray-700 dark:text-gray-300
                       border border-gray-300 dark:border-white/10
                       shadow-sm hover:bg-gray-50 dark:hover:bg-white/10
                       focus:outline-none focus:ring-2 focus:ring-primary-500 transition">
                Cancel
            </button>

            <button wire:click="deleteBundle" wire:loading.attr="disabled" wire:target="deleteBundle" type="button"
                class="flex w-full flex-row items-center justify-center gap-2 px-4 py-3 bg-danger-600 hover:bg-danger-700 text-white rounded-lg text-sm font-medium shadow-sm disabled:opacity-70 disabled:cursor-not-allowed transition">

                <span wire:loading.remove wire:target="deleteBundle" class="inline-flex items-center space-x-2">
                    <x-heroicon-o-trash class="w-5 h-5 inline-block" />
                    <span>Delete</span>
                </span>

                <span wire:loading wire:target="deleteBundle" class="inline-flex items-center space-x-2">
                    <svg class="animate-spin w-5 h-5 inline-block" viewBox="0 0 24 24" fill="none">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z" />
                    </svg>
                    <span>Deleting…</span>
                </span>

            </button>
        </div>
    </div>
</div>
