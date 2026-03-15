<?php

namespace App\Filament\Resources\Promos\Pages;

use App\Filament\Resources\Promos\PromoResource;
use App\Models\Promo;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPromos extends ListRecords
{
    protected static string $resource = PromoResource::class;

    // ── Livewire reactive properties ──────────────────────────────────────
    public string $promoSearch = '';
    public string $promoFilter = 'active'; // 'active' | 'trashed' | 'all'

    // Delete modal state
    public bool    $showDeleteModal   = false;
    public ?int    $deleteTargetId    = null;
    public string  $deleteTargetName  = '';

    // Restore modal state
    public bool    $showRestoreModal  = false;
    public ?int    $restoreTargetId   = null;
    public string  $restoreTargetName = '';

    public function updatingPromoSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPromoFilter(): void
    {
        $this->resetPage();
    }

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
        if ($this->promoFilter === 'trashed') {
            $query = Promo::onlyTrashed();
        } elseif ($this->promoFilter === 'all') {
            $query = Promo::withTrashed();
        } else {
            $query = Promo::query();
        }

        $query->when(
            $this->promoSearch,
            fn ($q) => $q->where('name', 'like', '%'.$this->promoSearch.'%')
        );

        return [
            'promos' => $query->latest()->paginate(9),
        ];
    }

    // ── Toggle active ─────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $promo = Promo::withTrashed()->findOrFail($id);
        $promo->update(['is_active' => ! $promo->is_active]);

        Notification::make()
            ->title($promo->is_active ? 'Promotion activated' : 'Promotion deactivated')
            ->success()
            ->send();
    }

    // ── Delete modal ──────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $promo = Promo::find($id);

        if (! $promo) {
            Notification::make()->title('Promotion not found')->danger()->send();

            return;
        }

        $this->deleteTargetId   = $id;
        $this->deleteTargetName = $promo->name;
        $this->showDeleteModal  = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal  = false;
        $this->deleteTargetId   = null;
        $this->deleteTargetName = '';
    }

    public function deletePromo(): void
    {
        if (! $this->deleteTargetId) {
            return;
        }

        $promo = Promo::find($this->deleteTargetId);

        if (! $promo) {
            Notification::make()->title('Promotion not found')->danger()->send();
            $this->cancelDelete();

            return;
        }

        $name = $promo->name;
        $promo->delete();
        $this->cancelDelete();

        Notification::make()
            ->title("Promotion \"{$name}\" deleted")
            ->success()
            ->send();
    }

    // ── Restore modal ─────────────────────────────────────────────────────

    public function confirmRestore(int $id): void
    {
        $promo = Promo::withTrashed()->find($id);

        if (! $promo) {
            Notification::make()->title('Promotion not found')->danger()->send();

            return;
        }

        $this->restoreTargetId   = $id;
        $this->restoreTargetName = $promo->name;
        $this->showRestoreModal  = true;
    }

    public function cancelRestore(): void
    {
        $this->showRestoreModal  = false;
        $this->restoreTargetId   = null;
        $this->restoreTargetName = '';
    }

    public function restorePromo(): void
    {
        if (! $this->restoreTargetId) {
            return;
        }

        $promo = Promo::withTrashed()->find($this->restoreTargetId);

        if (! $promo) {
            Notification::make()->title('Promotion not found')->danger()->send();
            $this->cancelRestore();

            return;
        }

        $name = $promo->name;
        $promo->restore();
        $this->cancelRestore();

        Notification::make()
            ->title("Promotion \"{$name}\" restored")
            ->success()
            ->send();
    }
}
