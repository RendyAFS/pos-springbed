<div class="flex flex-col items-center justify-center py-24 text-gray-400 dark:text-gray-600">
    @if ($promoFilter === 'trashed')
        <x-heroicon-o-trash class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">Tidak ada promosi yang dihapus</p>
        <p class="text-sm mt-1">Semua promosi masih aktif di sistem.</p>
    @elseif ($promoSearch)
        <x-heroicon-o-magnifying-glass class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">Tidak ditemukan</p>
        <p class="text-sm mt-1">Coba kata kunci lain.</p>
    @else
        <x-heroicon-o-tag class="w-16 h-16 mb-4 opacity-25" />
        <p class="text-lg font-semibold">Belum ada promosi</p>
        <p class="text-sm mt-1">Buat promosi pertamamu untuk memulai.</p>
    @endif
</div>
