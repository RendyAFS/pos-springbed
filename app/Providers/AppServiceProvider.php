<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ActivityPolicy;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
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
        Carbon::setLocale(config('app.locale'));
        Gate::policy(Activity::class, ActivityPolicy::class);
        Gate::define('viewLogViewer', function (User $user) {
            return $user->hasRole('Super Admin');
        });
        Gate::define('viewPulse', function (User $user) {
            return $user->hasRole('Super Admin');
        });
    }
}
