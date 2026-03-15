<?php

namespace App\Filament\Resources\Bundles\Pages;

use App\Filament\Resources\Bundles\BundleResource;
use App\Models\Bundle;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListBundles extends ListRecords
{
    protected static string $resource = BundleResource::class;

    // ── Livewire reactive properties ──────────────────────────────────────
    public string $bundleSearch = '';
    public string $bundleFilter = 'active'; // 'active' | 'trashed' | 'all'

    // Delete modal state
    public bool   $showDeleteModal  = false;
    public ?int   $deleteTargetId   = null;
    public string $deleteTargetName = '';

    // Restore modal state
    public bool   $showRestoreModal  = false;
    public ?int   $restoreTargetId   = null;
    public string $restoreTargetName = '';

    // ── Reset to page 1 when search / filter changes ──────────────────────
    public function updatingBundleSearch(): void
    {
        $this->resetPage();
    }

    public function updatingBundleFilter(): void
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
        return 'filament.pages.bundles.index';
    }

    public function getViewData(): array
    {
        if ($this->bundleFilter === 'trashed') {
            $query = Bundle::onlyTrashed();
        } elseif ($this->bundleFilter === 'all') {
            $query = Bundle::withTrashed();
        } else {
            $query = Bundle::query();
        }

        $query
            ->when(
                $this->bundleSearch,
                fn ($q) => $q->where('name', 'like', '%'.$this->bundleSearch.'%')
            )
            ->with([
                // Eager-load items → product → latest inventoryStock
                // so the card blade doesn't trigger N+1 queries
                'bundleItems.product.inventoryStocks',
            ]);

        return [
            'bundles' => $query->latest()->paginate(9),
        ];
    }

    // ── Toggle active ─────────────────────────────────────────────────────

    public function toggleActive(int $id): void
    {
        $bundle = Bundle::withTrashed()->findOrFail($id);
        $bundle->update(['is_active' => ! $bundle->is_active]);

        Notification::make()
            ->title($bundle->is_active ? 'Bundle activated' : 'Bundle deactivated')
            ->success()
            ->send();
    }

    // ── Delete modal ──────────────────────────────────────────────────────

    public function confirmDelete(int $id): void
    {
        $bundle = Bundle::find($id);

        if (! $bundle) {
            Notification::make()->title('Bundle not found')->danger()->send();

            return;
        }

        $this->deleteTargetId   = $id;
        $this->deleteTargetName = $bundle->name;
        $this->showDeleteModal  = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal  = false;
        $this->deleteTargetId   = null;
        $this->deleteTargetName = '';
    }

    public function deleteBundle(): void
    {
        if (! $this->deleteTargetId) {
            return;
        }

        $bundle = Bundle::find($this->deleteTargetId);

        if (! $bundle) {
            Notification::make()->title('Bundle not found')->danger()->send();
            $this->cancelDelete();

            return;
        }

        $name = $bundle->name;
        $bundle->delete();
        $this->cancelDelete();

        Notification::make()
            ->title("Bundle \"{$name}\" deleted")
            ->success()
            ->send();
    }

    // ── Restore modal ─────────────────────────────────────────────────────

    public function confirmRestore(int $id): void
    {
        $bundle = Bundle::withTrashed()->find($id);

        if (! $bundle) {
            Notification::make()->title('Bundle not found')->danger()->send();

            return;
        }

        $this->restoreTargetId   = $id;
        $this->restoreTargetName = $bundle->name;
        $this->showRestoreModal  = true;
    }

    public function cancelRestore(): void
    {
        $this->showRestoreModal  = false;
        $this->restoreTargetId   = null;
        $this->restoreTargetName = '';
    }

    public function restoreBundle(): void
    {
        if (! $this->restoreTargetId) {
            return;
        }

        $bundle = Bundle::withTrashed()->find($this->restoreTargetId);

        if (! $bundle) {
            Notification::make()->title('Bundle not found')->danger()->send();
            $this->cancelRestore();

            return;
        }

        $name = $bundle->name;
        $bundle->restore();
        $this->cancelRestore();

        Notification::make()
            ->title("Bundle \"{$name}\" restored")
            ->success()
            ->send();
    }
}
