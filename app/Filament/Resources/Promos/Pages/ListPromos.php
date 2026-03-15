<?php

namespace App\Filament\Resources\Promos\Pages;

use App\Filament\Resources\Promos\PromoResource;
use App\Models\Promo;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPromos extends ListRecords
{
    protected static string $resource = PromoResource::class;

    // ── Livewire public properties (reactive via wire:model) ──────────────
    public string $promoSearch = '';

    /**
     * Filter state:
     *   'active'  → hanya data tidak dihapus  (default)
     *   'trashed' → hanya data yang dihapus
     *   'all'     → semua data
     */
    public string $promoFilter = 'active';

    // ─────────────────────────────────────────────────────────────────────

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getView(): string
    {
        return 'filament.pages.promos.index';
    }

    public function getViewData(): array
    {
        $query = Promo::query();

        // Apply soft-delete filter
        match ($this->promoFilter) {
            'trashed' => $query->onlyTrashed(),
            'all'     => $query->withTrashed(),
            default   => $query, // 'active' → default (no trashed)
        };

        // Apply search
        $query->when(
            $this->promoSearch,
            fn($q) => $q->where('name', 'like', '%' . $this->promoSearch . '%')
        );

        return [
            'promos' => $query->latest()->paginate(12),
        ];
    }

    // ── Toggle active ─────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $promo = Promo::withTrashed()->findOrFail($id);
        $promo->update(['is_active' => ! $promo->is_active]);

        Notification::make()
            ->title($promo->is_active ? 'Promosi diaktifkan' : 'Promosi dinonaktifkan')
            ->success()
            ->send();
    }

    // ── Delete (via Filament Action modal — konsisten style) ──────────────

    /**
     * Action ini di-mount ke halaman dan dipanggil dari Blade via:
     *   $this->mountAction('deletePromo', ['id' => $promo->id])
     * sehingga modal konfirmasi sepenuhnya menggunakan style Filament.
     */
    public function deletePromoAction(): Action
    {
        return Action::make('deletePromo')
            ->requiresConfirmation()
            ->modalHeading('Hapus Promosi')
            ->modalDescription('Yakin ingin menghapus promosi ini? Data masih bisa dipulihkan setelahnya.')
            ->modalSubmitActionLabel('Ya, Hapus')
            ->modalCancelActionLabel('Batal')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->action(function (array $arguments): void {
                $promo = Promo::findOrFail($arguments['id']);
                $promo->delete();

                Notification::make()
                    ->title('Promosi dihapus')
                    ->success()
                    ->send();
            });
    }

    // ── Restore ───────────────────────────────────────────────────────────

    public function restorePromoAction(): Action
    {
        return Action::make('restorePromo')
            ->requiresConfirmation()
            ->modalHeading('Pulihkan Promosi')
            ->modalDescription('Promosi ini akan dipulihkan dan bisa diaktifkan kembali.')
            ->modalSubmitActionLabel('Ya, Pulihkan')
            ->modalCancelActionLabel('Batal')
            ->color('success')
            ->icon('heroicon-o-arrow-path')
            ->action(function (array $arguments): void {
                $promo = Promo::withTrashed()->findOrFail($arguments['id']);
                $promo->restore();

                Notification::make()
                    ->title('Promosi dipulihkan')
                    ->success()
                    ->send();
            });
    }
}
