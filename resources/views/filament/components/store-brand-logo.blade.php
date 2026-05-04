<div class="flex items-center gap-3">
    <img
        src="{{ $logoUrl }}"
        alt="{{ $brandName }}"
        class="h-10 w-10 rounded-md object-contain"
    />

    <div class="flex min-w-0 flex-col">
        <span class="truncate text-base font-semibold leading-5 text-gray-950 dark:text-white">
            {{ $brandName }}
        </span>

        <span class="truncate text-xs font-medium leading-4 text-gray-500 dark:text-gray-400">
            {{ $storeName }}
        </span>
    </div>
</div>
