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

        $user->update([
            'store_setting_id' => $this->store_setting_id,
        ]);

        session()->put('store_selected', true);

        return redirect()->intended('/admin');
    }

    public function getStores()
    {
        return StoreSetting::select('id', 'store_name', 'address')->get();
    }
}
