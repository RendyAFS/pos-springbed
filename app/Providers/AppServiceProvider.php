<?php

namespace App\Providers;

use App\Policies\ActivityPolicy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Activity::class, ActivityPolicy::class);
        // LogViewer::auth(function (Request $request) {
        //     /** @var User|null $user */
        //     $user = $request->user('web');

        //     return $user?->hasRole('Super Admin') ?? false;
        // });
    }
}
