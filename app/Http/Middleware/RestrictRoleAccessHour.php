<?php

namespace App\Http\Middleware;

use App\Models\RoleAccessHour;
use App\Models\User;
use Closure;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictRoleAccessHour
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if ($request->routeIs('filament.admin.auth.logout')) {
            return $next($request);
        }

        if ($user->hasRole('Super Admin')) {
            return $next($request);
        }

        $roleIds = $user->roles()
            ->where('name', '!=', 'Super Admin')
            ->pluck('id');

        if ($roleIds->isEmpty()) {
            return $next($request);
        }

        $restrictions = RoleAccessHour::query()
            ->whereIn('role_id', $roleIds)
            ->where('is_active', true)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->get();

        if ($restrictions->isEmpty()) {
            return $next($request);
        }

        $now = Carbon::now()->format('H:i:s');

        foreach ($restrictions as $restriction) {
            $start = Carbon::parse($restriction->start_time)->format('H:i:s');
            $end = Carbon::parse($restriction->end_time)->format('H:i:s');

            $isAllowed = $start <= $end
                ? ($now >= $start && $now <= $end)
                : ($now >= $start || $now <= $end);

            if (!$isAllowed) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                Notification::make()
                    ->title('Akses Ditolak')
                    ->body("Akses untuk role Anda hanya diperbolehkan pada jam {$start} - {$end} WIB.")
                    ->danger()
                    ->persistent()
                    ->send();

                return redirect()->to('/admin/login');
            }
        }

        return $next($request);
    }
}
