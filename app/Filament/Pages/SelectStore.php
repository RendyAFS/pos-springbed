<?php

namespace App\Filament\Pages;

use App\Models\StoreSetting;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SelectStore extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected string $view = 'filament.pages.select-store';

    public ?int $store_setting_id = null;

    public function mount()
    {
        $this->store_setting_id = Auth::user()->store_setting_id;
    }

    public function submit()
    {
        /** @var User $user */
        $user = Auth::user();

        $store = StoreSetting::find($this->store_setting_id);

        $user->update([
            'store_setting_id' => $this->store_setting_id,
        ]);

        session()->put('selected_store', $store?->id);

        return redirect()->intended('/admin');
    }

    public function getStores()
    {
        /** @var User $user */
        $user = Auth::user();

        $allowedStores = $user->selected_store ?? [];

        if (empty($allowedStores)) {
            return StoreSetting::select('id', 'store_name', 'address')->get();
        }

        return StoreSetting::select('id', 'store_name', 'address')
            ->whereIn('id', $allowedStores)
            ->get();
    }
}
