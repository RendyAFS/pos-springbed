<?php

namespace App\Filament\Resources\Promos\Pages;

use App\Filament\Resources\Promos\PromoResource;
use App\Models\Promo;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListPromos extends ListRecords
{
    protected static string $resource = PromoResource::class;

    /**
     * Livewire property bound to the search input in the blade view.
     * Named "promoSearch" to avoid conflict with Filament's own $tableSearch.
     */
    public string $promoSearch = '';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Promotion')
                ->icon('heroicon-o-plus'),
        ];
    }

    // ─────────────────────────────────────────────
    // Override view
    // ─────────────────────────────────────────────

    public function getView(): string
    {
        return 'filament.resources.promos.pages.list-promos';
    }

    public function getViewData(): array
    {
        return [
            'promos' => Promo::withTrashed()
                ->when(
                    $this->promoSearch,
                    fn($q) => $q->where('name', 'like', '%' . $this->promoSearch . '%')
                )
                ->latest()
                ->paginate(12),
        ];
    }

    // ─────────────────────────────────────────────
    // Livewire actions called from blade
    // ─────────────────────────────────────────────

    /**
     * Toggle is_active for a promo.
     */
    public function toggleActive(int $id): void
    {
        $promo = Promo::withTrashed()->findOrFail($id);
        $promo->update(['is_active' => ! $promo->is_active]);

        Notification::make()
            ->title($promo->is_active ? 'Promotion activated' : 'Promotion deactivated')
            ->success()
            ->send();
    }

    /**
     * Soft-delete a promo.
     */
    public function deletePromo(int $id): void
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        Notification::make()
            ->title('Promotion deleted')
            ->success()
            ->send();
    }

    /**
     * Restore a soft-deleted promo.
     */
    public function restorePromo(int $id): void
    {
        $promo = Promo::withTrashed()->findOrFail($id);
        $promo->restore();

        Notification::make()
            ->title('Promotion restored')
            ->success()
            ->send();
    }
}
