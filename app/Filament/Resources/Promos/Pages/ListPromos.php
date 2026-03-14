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
    public string $promoSearch = '';
    public bool $showDeleteModal = false;
    public ?int $deleteTargetId = null;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
        ];
    }

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

    public function toggleActive(int $id): void
    {
        $promo = Promo::withTrashed()->findOrFail($id);
        $promo->update(['is_active' => ! $promo->is_active]);

        Notification::make()
            ->title($promo->is_active ? 'Promotion activated' : 'Promotion deactivated')
            ->success()
            ->send();
    }

    public function confirmDeletePromo(int $id): void
    {
        $this->deleteTargetId = $id;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deleteTargetId = null;
    }

    public function deletePromo(): void
    {
        if (! $this->deleteTargetId) {
            return;
        }

        $promo = Promo::findOrFail($this->deleteTargetId);
        $promo->delete();

        $this->showDeleteModal = false;
        $this->deleteTargetId = null;

        Notification::make()
            ->title('Promotion deleted')
            ->success()
            ->send();
    }

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
