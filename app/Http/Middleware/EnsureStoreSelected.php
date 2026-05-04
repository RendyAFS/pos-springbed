<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\StoreSetting;
use App\Models\User;

class EnsureStoreSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        if ($request->session()->get('selected_store')) {
            return $next($request);
        }

        $selectedStores = $user->selected_store ?? [];

        if (count($selectedStores) === 1) {
            $storeName = $selectedStores[0];
            $store = StoreSetting::where('store_name', $storeName)->first();

            if ($store) {
                $user->update(['store_setting_id' => $store->id]);
                session()->put('selected_store', $storeName);
            }

            return $next($request);
        }

        if (!$request->is('admin/select-store')) {
            return redirect()->to('/admin/select-store');
        }

        return $next($request);
    }
}
