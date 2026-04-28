<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class EnsureStoreSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user) {
            if ($user->hasRole('Super Admin')) {
                return $next($request);
            }

            if (!$request->session()->get('store_selected')) {
                if (!$request->is('admin/select-store')) {
                    return redirect()->to('/admin/select-store');
                }
            }
        }

        return $next($request);
    }
}
